<?php

namespace App\Http\Controllers;

use App\Models\User;
use ZipArchive;

class ExportController extends Controller
{
    public function export($id)
    {
        $user = User::with('profile')->findOrFail($id);

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->profile->phone ?? '',
            'address' => $user->profile->address ?? ''
        ];

        $fileName = "user-data-{$user->id}.zip";
        $zipPath = storage_path($fileName);

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {

            $zip->addFromString(
                'user-data.json',
                json_encode($data, JSON_PRETTY_PRINT)
            );

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}