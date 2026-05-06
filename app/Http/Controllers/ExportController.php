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

        $data = [];

        if (in_array('name', $fields)) {
            $data['name'] = $user->name;
        }

        if (in_array('email', $fields)) {
            $data['email'] = $user->email;
        }

        if (in_array('phone', $fields)) {
            $data['phone'] = $user->profile->phone ?? '';
        }

        if (in_array('address', $fields)) {
            $data['address'] = $user->profile->address ?? '';
        }

        // fallback if nothing selected
        if (empty($data)) {
            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->profile->phone ?? '',
                'address' => $user->profile->address ?? ''
            ];
        }

        $fileName = "user-data-{$user->id}-" . time() . ".zip";
        $zipPath = storage_path($fileName);

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {

            $zip->addFromString(
                'user-data.json',
                json_encode($data, JSON_PRETTY_PRINT)
            );

            $zip->close();
        }

        // SAVE HISTORY
        Export::create([
            'user_id' => $user->id,
            'file_name' => $fileName,
            'exported_at' => now()
        ]);

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}