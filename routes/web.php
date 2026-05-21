<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\UserController;
use App\Models\Export;

Route::get('/', function () {
    return view('welcome');
});

// User routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

// Export routes
Route::get('/export/{id}', [ExportController::class, 'export'])->name('export');
Route::post('/export/bulk', [ExportController::class, 'bulkExport'])->name('export.bulk');
Route::delete('/export/{id}', [ExportController::class, 'deleteExport'])->name('export.delete');
Route::delete('/export/delete-all', [ExportController::class, 'deleteAllExports'])->name('export.delete-all');

// History route
Route::get('/history', [ExportController::class, 'history'])->name('history');