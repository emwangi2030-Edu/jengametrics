<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BqDocumentController;
use App\Http\Controllers\BqSectionController;

Route::get('/', function () {
    return view('welcome');
});



Route::resource('bq_documents', BqDocumentController::class);
Route::get('bq_documents/{bqDocument}/sections/create', [BqSectionController::class, 'create'])->name('bq_sections.create');
Route::post('bq_documents/{bqDocument}/sections', [BqSectionController::class, 'store'])->name('bq_sections.store');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
