<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BqDocumentController;
use App\Http\Controllers\BqSectionController;
use App\Http\Controllers\BqItemController;

// Home Route
Route::get('/', function () {
    return view('welcome');
});

// BoQ Documents Routes
Route::resource('bq_documents', BqDocumentController::class);

// Sections Routes
Route::get('bq_documents/{bqDocument}/sections/create', [BqSectionController::class, 'create'])->name('bq_sections.create');
Route::post('bq_documents/{bqDocument}/sections', [BqSectionController::class, 'store'])->name('bq_sections.store');
Route::get('bq_documents/{bqDocument}/sections/{bqSection}', [BqSectionController::class, 'show'])->name('bq_sections.show');
Route::get('bq_documents/{bqDocument}/sections/{bqSection}/edit', [BqSectionController::class, 'edit'])->name('bq_sections.edit');
Route::put('bq_documents/{bqDocument}/sections/{bqSection}', [BqSectionController::class, 'update'])->name('bq_sections.update');

// Items Routes
Route::get('bq_documents/{bqDocument}/items/create', [BqItemController::class, 'create'])->name('bq_documents.items.create');
Route::post('bq_documents/{bqDocument}/items', [BqItemController::class, 'store'])->name('bq_documents.items.store');
Route::get('bq_documents/{bqDocument}/items/{bqItem}/edit', [BqItemController::class, 'edit'])->name('bq_documents.items.edit');
Route::put('bq_documents/{bqDocument}/items/{bqItem}', [BqItemController::class, 'update'])->name('bq_documents.items.update');
Route::delete('bq_documents/{bqDocument}/items/{bqItem}', [BqItemController::class, 'destroy'])->name('bq_documents.items.destroy');




Route::post('save-item', ['as'=>'save_bq_item', 'uses' => '\App\Http\Controllers\BqItemController@store']);
Route::get('create-bq-item', ['as'=>'create_bq_item', 'uses' => '\App\Http\Controllers\BqItemController@create']);





// Dashboard Route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth Routes
require __DIR__.'/auth.php';

