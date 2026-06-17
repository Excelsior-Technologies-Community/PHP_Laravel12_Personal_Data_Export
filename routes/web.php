<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
Route::get('/history', [ExportController::class, 'history'])->name('history');


Route::post('/export/bulk', [ExportController::class, 'bulkExport'])->name('export.bulk');
Route::delete('/export/delete-all', [ExportController::class, 'deleteAllExports'])->name('export.delete-all');

Route::post('/export/{id}', [ExportController::class, 'export'])->name('export');
Route::delete('/export/{id}', [ExportController::class, 'deleteExport'])->name('export.delete');