<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CertificateRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas para documentos protegidas por autenticação
Route::middleware('auth')->group(function () {
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/certificates', [CertificateRequestController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/create', [CertificateRequestController::class, 'create'])->name('certificates.create');
    Route::post('/certificates', [CertificateRequestController::class, 'store'])->name('certificates.store');
    Route::get('/certificates/{certificate}', [CertificateRequestController::class, 'show'])->name('certificates.show');
    Route::put('/certificates/{certificate}', [CertificateRequestController::class, 'update'])->name('certificates.update');
    Route::get('/certificates/{certificate}/download', [CertificateRequestController::class, 'download'])->name('certificates.download');
});

require __DIR__.'/auth.php';
