<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BqDocumentController;
use App\Http\Controllers\BqSectionController;
use App\Http\Controllers\BqItemController;
use App\Http\Controllers\BOMController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\ProjectWizardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\CostTrackingController;
use App\Http\Controllers\DashboardController;




// Home Route
Route::get('/', function () {
    return view('welcome');
});


// Route for the first step
Route::get('/wizard/step1', [ProjectWizardController::class, 'step1'])->name('wizard.step1');

// Route for the second step
Route::get('/wizard/step2', [ProjectWizardController::class, 'step2'])->name('wizard.step2');

// Route for the third step
Route::get('/wizard/step3', [ProjectWizardController::class, 'step3'])->name('wizard.step3');

// Route to handle form submission
Route::post('/wizard/complete', [ProjectWizardController::class, 'complete'])->name('wizard.complete');

// Route for Step 1 POST request
Route::post('/wizard/step1', [ProjectWizardController::class, 'step1Post'])->name('wizard.step1.post');
// Route for Step 2 POST request
Route::post('/wizard/step2', [ProjectWizardController::class, 'step2Post'])->name('wizard.step2.post');
// Route to handle form submission and complete the wizard
Route::post('/wizard/complete', [ProjectWizardController::class, 'complete'])->name('wizard.complete');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!$user->has_project) {
            return redirect()->route('wizard.step1');
        }

        return view('dashboard');
    })->name('dashboard');

    Route::get('/users', function () {
        $user = Auth::user();
        if (!$user->has_school) {
            return redirect()->route('wizard.step1');
        }

        return view('users');
    })->name('view_users');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('projects', ProjectController::class);

Route::post('select-project', [ProjectController::class, 'selectProject'])->name('select_project');


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




Route::resource('boms', BOMController::class);

Route::resource('projects', ProjectController::class);


// Document Upload & List Route
Route::get('/documents/upload', [DocumentController::class, 'index'])->name('documents.upload');

// Route to store the uploaded document
Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');

// Workers Table Route
Route::resource('workers', WorkerController::class);

// Routes to add workers to database
Route::get('/workers/create', [WorkerController::class, 'create'])->name('workers.create');
Route::post('/workers', [WorkerController::class, 'store'])->name('workers.store');

// Route to show worker's details page
Route::get('/workers/{id}', [WorkerController::class, 'show'])->name('workers.show');


// Route to Suppliers page
Route::resource('suppliers', SupplierController::class);

// Route to Materials page
Route::resource('materials', MaterialController::class);

// Routes for Supplier Name and Contact Autocomplete Feature
Route::get('/suppliers/autocomplete', [SupplierController::class, 'autocomplete'])->name('suppliers.autocomplete');
Route::get('/suppliers/autocompleteContact', [SupplierController::class, 'autocompleteContact'])->name('suppliers.autocompleteContact');


Route::middleware(['web'])->group(function () {
    Route::resource('materials', MaterialController::class);
});

// Route to serve document
Route::get('/materials/view-document/{id}', [MaterialController::class, 'viewDocument'])->name('materials.viewDocument');

// Cost Tracking Route
Route::get('/cost-tracking', [CostTrackingController::class, 'index'])->name('cost-tracking.index');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth Routes
require __DIR__.'/auth.php';

