<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Models\Export;

Route::get('/', function () {
    return view('welcome');
});

// Export route
Route::get('/export/{id}', [ExportController::class, 'export'])->name('export');

// History route
Route::get('/history', function () {
    $exports = Export::latest()->get();
    return view('history', compact('exports'));
});