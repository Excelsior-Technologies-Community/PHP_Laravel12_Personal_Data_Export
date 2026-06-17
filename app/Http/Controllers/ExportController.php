<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Export;
use ZipArchive;
use Illuminate\Support\Facades\Hash;

class ExportController extends Controller
{
    public function export(Request $request, $id)
    {
        $request->validate([
            'password' => 'required',
            'format' => 'required|in:json,csv,xml'
        ]);

        $user = User::with('profile')->findOrFail($id);

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['password' => 'Incorrect password for this user!']);
        }

        $fields = $request->fields ?? ['name', 'email', 'phone', 'address'];
        $format = $request->format ?? 'json';
        
        $data = $this->prepareData($user, $fields);
        
        $fileName = "user-data-{$user->id}-" . time();
        
        switch ($format) {
            case 'csv':
                $fullPath = $this->generateCSV($data, $fileName);
                break;
            case 'xml':
                $fullPath = $this->generateXML($data, $fileName);
                break;
            default:
                $fullPath = $this->generateJSON($data, $fileName);
        }
        
        $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
        
        Export::create([
            'user_id' => $user->id,
            'file_name' => basename($fullPath),
            'file_size' => $fileSize,
            'format' => $format,
            'exported_at' => now(),
            'ip_address' => $request->ip(),
            'is_bulk' => false
        ]);
        
        return response()->download($fullPath)->deleteFileAfterSend(true);
    }
    
    public function bulkExport(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'user_ids' => 'required|json',
            'format' => 'required|in:json,csv'
        ]);

        $admin = User::find(1);
        if ($admin && !Hash::check($request->password, $admin->password)) {
            return redirect()->back()->withErrors(['password' => 'Admin password incorrect!']);
        }

        $userIds = json_decode($request->user_ids, true);
        $format = $request->format ?? 'json';
        
        if (empty($userIds)) {
            return redirect()->back()->withErrors(['error' => 'No users selected']);
        }
        
        $users = User::with('profile')->whereIn('id', $userIds)->get();
        $allData = [];
        
        foreach ($users as $user) {
            $allData['user_' . $user->id] = $this->prepareData($user, ['name', 'email', 'phone', 'address']);
        }
        
        $fileName = "bulk-export-" . time();
        
        $fullPath = ($format === 'csv') 
            ? $this->generateBulkCSV($allData, $fileName) 
            : $this->generateBulkJSON($allData, $fileName);
        
        $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
        
        foreach ($users as $user) {
            Export::create([
                'user_id' => $user->id,
                'file_name' => basename($fullPath),
                'file_size' => $fileSize,
                'format' => $format,
                'exported_at' => now(),
                'ip_address' => $request->ip(),
                'is_bulk' => true
            ]);
        }
        
        return response()->download($fullPath)->deleteFileAfterSend(true);
    }
    
    public function history(Request $request)
    {
        $query = Export::latest();
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('file_name', 'like', "%{$request->search}%")
                  ->orWhere('user_id', $request->search);
            });
        }
        
        if ($request->format) {
            $query->where('format', $request->format);
        }
        
        $exports = $query->paginate(15);
        
        $stats = [
            'total_exports' => Export::count(),
            'total_size' => Export::sum('file_size'),
            'unique_users' => Export::distinct('user_id')->count('user_id'),
            'formats' => Export::select('format')->distinct()->get()
        ];
        
        return view('history', compact('exports', 'stats'));
    }
    
    public function deleteExport($id)
    {
        $export = Export::findOrFail($id);
        $filePath = storage_path($export->file_name);
        
        if (file_exists($filePath)) unlink($filePath);
        
        $export->delete();
        
        return redirect()->route('history')->with('success', 'Export deleted successfully');
    }
    
    public function deleteAllExports()
    {
        $exports = Export::all();
        foreach ($exports as $export) {
            $filePath = storage_path($export->file_name);
            if (file_exists($filePath)) unlink($filePath);
        }
        
        Export::truncate();
        
        return redirect()->route('history')->with('success', 'All exports deleted successfully');
    }
    
    private function prepareData($user, $fields)
    {
        $data = [];
        if (in_array('name', $fields)) $data['name'] = $user->name;
        if (in_array('email', $fields)) $data['email'] = $user->email;
        if (in_array('phone', $fields) && $user->profile) $data['phone'] = $user->profile->phone ?? '';
        if (in_array('address', $fields) && $user->profile) $data['address'] = $user->profile->address ?? '';
        
        $data['exported_at'] = now()->toDateTimeString();
        $data['exported_by'] = request()->ip();
        
        return $data;
    }
    
    private function generateJSON($data, $fileName)
    {
        $fullPath = storage_path($fileName . '.json');
        file_put_contents($fullPath, json_encode($data, JSON_PRETTY_PRINT));
        return $fullPath;
    }
    
    private function generateCSV($data, $fileName)
    {
        $fullPath = storage_path($fileName . '.csv');
        $file = fopen($fullPath, 'w');
        fputcsv($file, array_keys($data));
        fputcsv($file, array_values($data));
        fclose($file);
        return $fullPath;
    }
    
    private function generateXML($data, $fileName)
    {
        $fullPath = storage_path($fileName . '.xml');
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><user></user>');
        $this->arrayToXml($data, $xml);
        $xml->asXML($fullPath);
        return $fullPath;
    }
    
    private function generateBulkJSON($allData, $fileName)
    {
        $fullPath = storage_path($fileName . '.json');
        file_put_contents($fullPath, json_encode($allData, JSON_PRETTY_PRINT));
        return $fullPath;
    }
    
    private function generateBulkCSV($allData, $fileName)
    {
        $fullPath = storage_path($fileName . '.csv');
        $file = fopen($fullPath, 'w');
        fputcsv($file, ['User ID', 'Field', 'Value']);
        foreach ($allData as $userId => $userData) {
            foreach ($userData as $key => $value) {
                fputcsv($file, [$userId, $key, $value]);
            }
        }
        fclose($file);
        return $fullPath;
    }
    
    private function arrayToXml($data, &$xml)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subnode = $xml->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xml->addChild($key, htmlspecialchars((string)$value));
            }
        }
    }
}