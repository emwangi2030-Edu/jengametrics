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
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ElementController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\ItemMaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AttendanceController;

use Illuminate\Support\Facades\Auth;




// Home Route
Route::get('/', function () {
    return view('auth.login');
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
Route::get('boq', [BqDocumentController::class, 'index'])->name('boq');
// Sections Routes
Route::get('bq_documents/sections/create', [BqSectionController::class, 'create'])->name('bq_sections.create');
Route::post('bq_documents/sections', [BqSectionController::class, 'store'])->name('bq_sections.store');
Route::get('section/{id}', [BqSectionController::class, 'show'])->name('section.show');

Route::get('sections/{bqSection}/edit', [BqSectionController::class, 'edit'])->name('bq_sections.edit');
Route::put('bq_documents/{bqDocument}/sections/{bqSection}', [BqSectionController::class, 'update'])->name('bq_sections.update');
Route::put('sections/bqitems/{id}', [BqSectionController::class, 'updateItem'])->name('bq_items.update');
Route::delete('/bq_sections/item/{id}', [BqSectionController::class, 'destroyItem'])->name('bq_sections.item.destroy');

Route::get('/get/elements', [BqDocumentController::class, 'getElements'])->name('get.elements');
Route::get('/get/items', [BqDocumentController::class, 'getItems'])->name('get.items');

// Items Routes
Route::get('bq_documents/{bqDocument}/items/create', [BqItemController::class, 'create'])->name('bq_documents.items.create');
Route::post('bq_documents/{bqDocument}/items', [BqItemController::class, 'store'])->name('bq_documents.items.store');
Route::get('bq_documents/{bqDocument}/items/{bqItem}/edit', [BqItemController::class, 'edit'])->name('bq_documents.items.edit');




Route::delete('bq_documents/{bqDocument}/items/{bqItem}', [BqItemController::class, 'destroy'])->name('bq_documents.items.destroy');




Route::post('save-item', ['as'=>'save_bq_item', 'uses' => '\App\Http\Controllers\BqItemController@store']);
Route::get('create-bq-item', ['as'=>'create_bq_item', 'uses' => '\App\Http\Controllers\BqItemController@create']);




Route::resource('boms', BOMController::class);



Route::get('reports', ['as'=>'reports', 'uses' => '\App\Http\Controllers\BOMController@report']);

Route::resource('projects', ProjectController::class);


// Document Upload & List Route
Route::get('/documents/upload', [DocumentController::class, 'index'])->name('documents.upload');

// Route to store the uploaded document
Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');

// Workers Table Route
Route::resource('workers', WorkerController::class);

// Route to show the worker's attendance
Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'create'])->name('attendance.create');
Route::post('/attendance', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');


// Route to Suppliers page
Route::resource('suppliers', SupplierController::class);

// Route to Materials page
Route::resource('materials', MaterialController::class);
Route::post('/materials-store', [MaterialController::class, 'store'])->name('m.store');

// Routes for Supplier Name and Contact Autocomplete Feature
Route::get('/suppliers/autocomplete', [SupplierController::class, 'autocomplete'])->name('suppliers.autocomplete');
Route::get('/suppliers/autocompleteContact', [SupplierController::class, 'autocompleteContact'])->name('suppliers.autocompleteContact');

// Route to store new supplier name and contact
Route::post('/suppliers/ajax-store', [SupplierController::class, 'ajaxStore'])->name('suppliers.ajaxStore');

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

// Admin Sections Routes
Route::resource('sections', SectionController::class);
Route::get('/sections/{section}/elements', [SectionController::class, 'elements'])->name('sections.elements');

// Elements Routes
Route::post('/elements', [ElementController::class, 'store'])->name('elements.store');
Route::put('/elements/{element}', [ElementController::class, 'update'])->name('elements.update');
Route::delete('/elements/{element}', [ElementController::class, 'destroy'])->name('elements.destroy');
Route::get('sections/{section}/elements/{element}/subelements', [ElementController::class, 'subelements'])->name('elements.subelements');
Route::get('/get-elements-by-section', [ElementController::class, 'getElementsBySection'])->name('get.elements.by.section');

// Items Routes
Route::get('subelements/{id}/items', [ItemsController::class, 'index'])->name('subelements.items');
Route::post('/subelements/{id}/items', [ItemsController::class, 'store'])->name('subelements.items.store');
Route::put('items/{id}', [ItemsController::class, 'update'])->name('items.update');
Route::delete('items/{id}', [ItemsController::class, 'destroy'])->name('items.destroy');
Route::get('/get-items-by-element', [ItemsController::class, 'getItemsByElement'])->name('get.items.by.elements');

// Item Material Routes
Route::get('items/{id}/materials', [ItemMaterialController::class, 'index'])->name('items.materials');
Route::post('items/materials', [ItemMaterialController::class, 'store'])->name('materials.store');
Route::put('materials/items/{id}', [ItemMaterialController::class, 'update'])->name('materials.item.update');
Route::delete('items/materials/{id}', [ItemMaterialController::class, 'destroy'])->name('items.materials.destroy');

// Product Route
Route::get('admin/sections/products', [ItemMaterialController::class, 'index_materials'])->name('admin.sections.products');


// routes/web.php
Route::resource('products', ProductController::class);


// Auth Routes
require __DIR__.'/auth.php';
