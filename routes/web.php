<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BqDocumentController;
use App\Http\Controllers\BqSectionController;
use App\Http\Controllers\BqItemController;
use App\Http\Controllers\BqLevelController;
use App\Http\Controllers\BOMController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\WorkerController;
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
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\SubAccountController;
use App\Http\Controllers\LabourTaskController;
use App\Http\Controllers\ProgressCertificateController;
use Illuminate\Support\Facades\Auth;




// Home Route
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Wizard is intentionally disabled for demos; keep named routes for compatibility.
    Route::get('/wizard', fn () => redirect()->route('dashboard'))->name('wizard');
    Route::get('/wizard/step1', fn () => redirect()->route('dashboard'))->name('wizard.step1');
    Route::get('/wizard/step2', fn () => redirect()->route('dashboard'))->name('wizard.step2');
    Route::get('/wizard/step1-fragment', fn () => redirect()->route('dashboard'))->name('wizard.step1.fragment');
    Route::get('/wizard/step2-fragment', fn () => redirect()->route('dashboard'))->name('wizard.step2.fragment');
    Route::post('/wizard/complete', fn () => redirect()->route('dashboard'))->name('wizard.complete');
    Route::post('/wizard/step1', fn () => redirect()->route('dashboard'))->name('wizard.step1.post');
    Route::post('/wizard/step2', fn () => redirect()->route('dashboard'))->name('wizard.step2.post');

    // Minimal project index endpoint used by dashboard/controller redirects.
    Route::get('/projects', function () {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        if ($user->isSubAccount() || $user->is_admin()) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('dashboard');
    })->name('projects.index');

    Route::get('/users', function () {
        $user = Auth::user();
        if (!$user->has_school) {
            return redirect()->route('wizard.step1');
        }

        return view('users');
    })->name('view_users');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/users/{user}', [DashboardController::class, 'showAdminUser'])
        ->name('dashboard.admin.users.show');
    Route::post('/dashboard/project-steps', [DashboardController::class, 'storeProjectSteps'])
        ->name('dashboard.project_steps.store');
    Route::patch('/dashboard/project-steps/reorder', [DashboardController::class, 'reorderProjectSteps'])
        ->name('dashboard.project_steps.reorder');
    Route::patch('/dashboard/project-steps/{projectStep}', [DashboardController::class, 'toggleProjectStep'])
        ->name('dashboard.project_steps.toggle');
    Route::get('/dashboard/api-token', function () {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'error' => [
                    'code' => 'UNAUTHENTICATED',
                    'message' => 'Authentication is required.',
                ],
            ], 401);
        }

        $token = session('dashboard_api_token');
        if (! $token) {
            $user->tokens()->where('name', 'dashboard-shell')->delete();
            $token = $user->createToken('dashboard-shell')->plainTextToken;
            session(['dashboard_api_token' => $token]);
        }

        return response()->json(['data' => ['token' => $token]]);
    })->name('dashboard.api-token');

    Route::post('dashboard/projects', [ProjectController::class, 'store'])->name('dashboard.projects.store');

    Route::post('select-project', [ProjectController::class, 'selectProject'])->name('select_project');
    Route::get('projects/check-project-id', [ProjectController::class, 'checkProjectUid'])->name('projects.check_uid');

    // BoQ Documents Routes
    Route::get('boq', [BqDocumentController::class, 'index'])->name('boq');
    Route::get('bq_documents/{bqDocument}/copy', [BqDocumentController::class, 'copyForm'])
        ->name('bq_documents.copy');
    Route::post('bq_documents/{bqDocument}/copy', [BqDocumentController::class, 'copyStore'])
        ->name('bq_documents.copy.store');
    Route::resource('bq_documents', BqDocumentController::class)->only(['index', 'show']);
    Route::post('bq_documents', [BqDocumentController::class, 'store'])->name('bq_documents.store');
    Route::put('bq_documents/{bqDocument}', [BqDocumentController::class, 'update'])->name('bq_documents.update');
    Route::delete('bq_documents/{bqDocument}', [BqDocumentController::class, 'destroy'])->name('bq_documents.destroy');

    Route::post('libraries', [LibraryController::class, 'store'])->name('libraries.store');
    Route::put('libraries/{library}', [LibraryController::class, 'update'])->name('libraries.update');
    Route::delete('libraries/{library}', [LibraryController::class, 'destroy'])->name('libraries.destroy');
    Route::get('libraries/{library}/items', [LibraryController::class, 'items'])->name('libraries.items');
    Route::get('items/details', [ItemsController::class, 'getItemsDetails'])->name('items.details');
    Route::post('bq_documents/{bqDocument}/import-library', [BqDocumentController::class, 'importLibrary'])->name('bq_documents.import-library');

    // BoQ Level routes
    Route::post('bq_documents/{bqDocument}/levels', [BqLevelController::class, 'store'])->name('bq_levels.store');
    Route::put('bq_documents/{bqDocument}/levels/{bqLevel}', [BqLevelController::class, 'update'])->name('bq_levels.update');
    Route::post('bq_documents/{bqDocument}/levels/{bqLevel}/copy', [BqLevelController::class, 'copy'])->name('bq_levels.copy');
    Route::delete('bq_documents/{bqDocument}/levels/{bqLevel}', [BqLevelController::class, 'destroy'])->name('bq_levels.destroy');

    // BoQ Sections Routes (scoped to BoQ documents & levels)
    Route::get('bq_documents/{bqDocument}/levels/{bqLevel}', [BqSectionController::class, 'show'])->name('bq_levels.show');
    Route::get('bq_documents/{bqDocument}/levels/{bqLevel}/items/create', [BqSectionController::class, 'create'])->name('bq_levels.items.create');
    Route::post('bq_documents/{bqDocument}/levels/{bqLevel}/items', [BqSectionController::class, 'store'])->name('bq_levels.items.store');

    Route::get('sections/{bqSection}/edit', [BqSectionController::class, 'edit'])->name('bq_sections.edit');
    Route::put('bq_documents/{bqDocument}/sections/{bqSection}', [BqSectionController::class, 'update'])->name('bq_sections.update');
    Route::put('sections/bqitems/{id}', [BqSectionController::class, 'updateItem'])->name('bq_items.update');
    Route::delete('/bq_sections/item/{id}', [BqSectionController::class, 'destroyItem'])->name('bq_sections.item.destroy');

    // Consolidated AJAX endpoints (canonical controllers)
    Route::get('/get/elements', [ElementController::class, 'getElementsBySection'])->name('get.elements');
    Route::get('/get/items', [ItemsController::class, 'getItemsByElement'])->name('get.items');

    // Items Routes
    Route::get('bq_documents/{bqDocument}/items/create', [BqItemController::class, 'create'])->name('bq_documents.items.create');
    Route::post('bq_documents/{bqDocument}/items', [BqItemController::class, 'store'])->name('bq_documents.items.store');
    Route::get('bq_documents/{bqDocument}/items/{bqItem}/edit', [BqItemController::class, 'edit'])->name('bq_documents.items.edit');
    Route::delete('bq_documents/{bqDocument}/items/{bqItem}', [BqItemController::class, 'destroy'])->name('bq_documents.items.destroy');

    Route::get('boms/documents/{bqDocument}', [BOMController::class, 'showDocument'])->name('boms.documents.show');
    // Register concrete paths before boms/{bom} so "create" is not captured as a BOM id.
    Route::get('boms/create', [BOMController::class, 'create'])->name('boms.create');
    Route::post('boms', [BOMController::class, 'store'])->name('boms.store');
    Route::delete('boms/{bom}', [BOMController::class, 'destroy'])->name('boms.destroy');
    Route::resource('boms', BOMController::class)->only(['index', 'show']);

    Route::get('/requisitions', [RequisitionController::class, 'index'])->name('requisitions.index');
    Route::get('/requisitions/create', [RequisitionController::class, 'create'])->name('requisitions.create');
    Route::post('/requisitions', [RequisitionController::class, 'store'])->name('requisitions.store');

    // Approve / Reject actions
    Route::post('/requisitions/{id}/approve', [RequisitionController::class, 'approve'])->name('requisitions.approve');
    Route::post('/requisitions/{id}/reject', [RequisitionController::class, 'reject'])->name('requisitions.reject');
    Route::patch('/requisitions/{requisition}/toggle-status', [RequisitionController::class, 'toggleStatus'])
        ->name('requisitions.toggleStatus');
    Route::post('/requisitions/store-adhoc', [RequisitionController::class, 'storeAdhoc'])
        ->name('requisitions.storeAdhoc');

    Route::get('reports', [BOMController::class, 'report'])->name('reports');
    Route::get('reports/wages', \App\Http\Controllers\WagesReportController::class)->name('reports.wages');
    Route::get('reports/purchases', \App\Http\Controllers\PurchasesReportController::class)->name('reports.purchases');

    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Document Upload & List Route
    Route::get('/documents/upload', [DocumentController::class, 'index'])->name('documents.upload');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');

    // Workers Table Route
    Route::get('/workers/{id}/attendance-data', [WorkerController::class, 'attendanceData'])->name('workers.attendanceData');
    Route::post('/workers/{worker}/restore', [WorkerController::class, 'restore'])->name('workers.restore');
    Route::get('/workers/create', [WorkerController::class, 'create'])->name('workers.create');
    Route::post('/workers', [WorkerController::class, 'store'])->name('workers.store');
    Route::get('/workers/{worker}/edit', [WorkerController::class, 'edit'])->name('workers.edit');
    Route::put('/workers/{worker}', [WorkerController::class, 'update'])->name('workers.update');
    Route::delete('/workers/{worker}', [WorkerController::class, 'destroy'])->name('workers.destroy');
    Route::resource('workers', WorkerController::class)->only(['index', 'show']);

    // Route to show the worker's attendance
    Route::get('/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/fetch', [AttendanceController::class, 'fetchAttendance'])->name('attendance.fetch');
    Route::post('/workers/{worker}/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/workers/{worker}/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/labour-tasks', [LabourTaskController::class, 'index'])->name('labour_tasks.index');
    Route::get('/labour-tasks/groups/{group}', [LabourTaskController::class, 'showGroup'])->name('labour_tasks.groups.show');
    Route::post('/labour-tasks/groups', [LabourTaskController::class, 'storeGroup'])->name('labour_tasks.groups.store');
    Route::post('/labour-tasks/tasks', [LabourTaskController::class, 'storeTask'])->name('labour_tasks.tasks.store');
    Route::patch('/labour-tasks/tasks/{task}/complete', [LabourTaskController::class, 'completeTask'])->name('labour_tasks.tasks.complete');

    // Route to Suppliers page
    Route::resource('suppliers', SupplierController::class)->only(['index', 'show']);

    // Route to Materials page
    Route::post('/materials/{id}/use', [MaterialController::class, 'useMaterial'])->name('materials.use');
    Route::get('/materials/delivered', [MaterialController::class, 'materialsDelivered'])->name('materials.delivered');
    Route::get('/materials/inventory', [MaterialController::class, 'inventoryManagement'])->name('materials.inventory');
    Route::get('/materials/usage', [MaterialController::class, 'stockUsageHistory'])->name('materials.usage');
    Route::get('/materials/view-document/{id}', [MaterialController::class, 'viewDocument'])->name('materials.viewDocument');
    Route::get('/materials/create', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
    Route::put('/materials/{material}', [MaterialController::class, 'update'])->name('materials.update');
    Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');
    Route::resource('materials', MaterialController::class)->only(['index', 'show']);

    // Routes for Supplier Name and Contact Autocomplete Feature
    Route::get('/suppliers/autocomplete', [SupplierController::class, 'autocomplete'])->name('suppliers.autocomplete');
    Route::get('/suppliers/autocompleteContact', [SupplierController::class, 'autocompleteContact'])->name('suppliers.autocompleteContact');
    Route::post('/suppliers/ajax-store', [SupplierController::class, 'ajaxStore'])->name('suppliers.ajaxStore');

    // Cost Tracking Route
    Route::get('/cost-tracking', [CostTrackingController::class, 'index'])->name('cost-tracking.index');

    Route::get('/account', function () {
        return redirect()->route('settings.index');
    })->name('account');

    Route::get('/settings', function () {
        $user = Auth::user();
        $owner = $user && $user->isSubAccount() ? $user->parentUser : $user;
        $linkedUsersCount = $owner ? $owner->subAccounts()->count() : 0;

        return view('settings.index', [
            'user' => $user,
            'linkedUsersCount' => $linkedUsersCount,
        ]);
    })->name('settings.index');

    Route::get('/admin/settings', [ProjectController::class, 'settings'])->name('projects.settings');
    Route::patch('/admin/settings', [ProjectController::class, 'updateSettings'])->name('projects.settings.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/sub-accounts', [SubAccountController::class, 'index'])->name('sub_accounts.index');
    Route::post('/sub-accounts', [SubAccountController::class, 'store'])->name('sub_accounts.store');
    Route::put('/sub-accounts/{user}', [SubAccountController::class, 'update'])->name('sub_accounts.update');
    Route::delete('/sub-accounts/{user}', [SubAccountController::class, 'destroy'])->name('sub_accounts.destroy');
    Route::view('/ui-preview', 'ui.jenga-metrics')->name('ui.preview');

    // Progress certificate routes
    Route::get('/progress-certificates', [ProgressCertificateController::class, 'index'])->name('progress_certificates.index');
    Route::get('/progress-certificates/create', [ProgressCertificateController::class, 'create'])->name('progress_certificates.create');
    Route::post('/progress-certificates', [ProgressCertificateController::class, 'store'])->name('progress_certificates.store');
    Route::get('/progress-certificates/{progressCertificate}/edit', [ProgressCertificateController::class, 'edit'])->name('progress_certificates.edit');
    Route::put('/progress-certificates/{progressCertificate}', [ProgressCertificateController::class, 'update'])->name('progress_certificates.update');
    Route::post('/progress-certificates/{progressCertificate}/mark-sent', [ProgressCertificateController::class, 'markSent'])->name('progress_certificates.mark_sent');
    Route::post('/progress-certificates/{progressCertificate}/mark-paid', [ProgressCertificateController::class, 'markPaid'])->name('progress_certificates.mark_paid');

    // Admin Sections Routes
    Route::resource('sections', SectionController::class)->only(['index']);
    Route::get('/sections/{section}/elements', [SectionController::class, 'elements'])->name('sections.elements');
    Route::post('/sections', [SectionController::class, 'store'])->name('sections.store');
    Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
    Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');

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
    Route::post('items/materials', [ItemMaterialController::class, 'store'])->name('item_materials.store');
    Route::put('materials/items/{id}', [ItemMaterialController::class, 'update'])->name('materials.item.update');
    Route::delete('items/materials/{id}', [ItemMaterialController::class, 'destroy'])->name('items.materials.destroy');

    // Product Route
    Route::get('admin/sections/products', [ItemMaterialController::class, 'index_materials'])->name('admin.sections.products');
    Route::resource('products', ProductController::class)->only(['index', 'show']);
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});


// Auth Routes
require __DIR__.'/auth.php';
