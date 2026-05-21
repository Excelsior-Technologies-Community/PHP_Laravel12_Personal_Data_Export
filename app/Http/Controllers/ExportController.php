<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Export;
use ZipArchive;

class ExportController extends Controller
{
    public function export(Request $request, $id)
    {
        $user = User::with('profile')->findOrFail($id);
        
        $fields = $request->fields ?? [];
        $format = $request->format ?? 'json';
        
        $data = $this->prepareData($user, $fields);
        
        // Generate file based on format
        $fileName = "user-data-{$user->id}-" . time();
        $filePath = storage_path($fileName);
        
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
        
        // Calculate file size
        $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
        
        // SAVE HISTORY
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
        $userIds = json_decode($request->user_ids, true) ?? [];
        $format = $request->format ?? 'json';
        
        if (empty($userIds)) {
            return redirect()->back()->with('error', 'No users selected');
        }
        
        $users = User::with('profile')->whereIn('id', $userIds)->get();
        $allData = [];
        
        foreach ($users as $user) {
            $data = $this->prepareData($user, ['name', 'email', 'phone', 'address']);
            $allData['user_' . $user->id] = $data;
        }
        
        $fileName = "bulk-export-" . time();
        
        switch ($format) {
            case 'csv':
                $fullPath = $this->generateBulkCSV($allData, $fileName);
                break;
            default:
                $fullPath = $this->generateBulkJSON($allData, $fileName);
        }
        
        $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
        
        // Save bulk export history
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
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('file_name', 'like', '%' . $request->search . '%')
                  ->orWhere('user_id', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by format
        if ($request->has('format') && $request->format) {
            $query->where('format', $request->format);
        }
        
        $exports = $query->paginate(15);
        
        // Get statistics with error handling
        $stats = [
            'total_exports' => Export::count(),
            'total_size' => Export::sum('file_size') ?? 0,
            'unique_users' => Export::distinct('user_id')->count('user_id'),
            'formats' => Export::select('format')->distinct()->whereNotNull('format')->get()
        ];
        
        return view('history', compact('exports', 'stats'));
    }
    
    public function deleteExport($id)
    {
        $export = Export::findOrFail($id);
        $filePath = storage_path($export->file_name);
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $export->delete();
        
        return redirect()->route('history')->with('success', 'Export deleted successfully');
    }
    
    public function deleteAllExports()
    {
        $exports = Export::all();
        
        foreach ($exports as $export) {
            $filePath = storage_path($export->file_name);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        Export::truncate();
        
        return redirect()->route('history')->with('success', 'All exports deleted successfully');
    }
    
    private function prepareData($user, $fields)
    {
        $data = [];
        
        if (in_array('name', $fields)) {
            $data['name'] = $user->name;
        }
        
        if (in_array('email', $fields)) {
            $data['email'] = $user->email;
        }
        
        if (in_array('phone', $fields) && $user->profile) {
            $data['phone'] = $user->profile->phone ?? '';
        }
        
        if (in_array('address', $fields) && $user->profile) {
            $data['address'] = $user->profile->address ?? '';
        }
        
        if (empty($data)) {
            $data = [
                'name' => $user->name,
                'email' => $user->email,
            ];
            if ($user->profile) {
                $data['phone'] = $user->profile->phone ?? '';
                $data['address'] = $user->profile->address ?? '';
            }
        }
        
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