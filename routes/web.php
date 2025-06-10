<?php

use App\Http\Controllers\Auth\LockScreenController;
use App\Http\Controllers\Budgeting\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\Budgeting\BudgetAllocationController;
use App\Http\Controllers\Budgeting\BudgetApproverController;
use App\Http\Controllers\Budgeting\PurchaseController;
use App\Http\Controllers\Budgeting\BudgetListController;
use App\Http\Controllers\Budgeting\BudgetRequestController;
use App\Http\Controllers\Budgeting\CategoryController;
use App\Http\Controllers\Budgeting\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Role\PermissionController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\UserController;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Route;
use App\Models\Department;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    SEOMeta::setTitle('Intra SMII - Dashboard');
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    /* Dashboard */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-finance', [DashboardController::class, 'index-finance'])->name('dashboard-finance');
    Route::resource('/dashboard/report', ReportController::class);
    Route::get('/dashboard/getReportData', [ReportController::class, 'getReportData'])->name('get.report.data');
    Route::get('/dashboard/getReportYear', [ReportController::class, 'getReportYear'])->name('get.report.year');
    Route::get('/api/requisitions/{year}', [DashboardController::class, 'getRequisitionsByYear'])->name('dashboard.requisitions.byYear');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile', [UserController::class, 'updateProfile'])->name('profile.updates');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/getUsersData', [UserController::class, 'getUsersData'])->name('get.users.data');


    /*Locked */
    Route::get('locked', [LockScreenController::class, 'show'])
        ->name('locked');

    Route::post('locked', [LockScreenController::class, 'store']);

    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/markAllAsRead', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/clear', [NotificationController::class, 'clearAll'])->name('notifications.clear');

    Route::get('/notifications/count', function () {
        return response()->json(['count' => auth()->user()->unreadNotifications->count()]);
    })->name('notifications.count');

    Route::prefix('management')->group(function () {
        Route::resource('budget-allocation', BudgetAllocationController::class);
        Route::resource('budget-list', BudgetListController::class);
        Route::resource('category', CategoryController::class);
        Route::resource('budget-request', BudgetRequestController::class);
        Route::resource('activity', ActivityLogController::class);
        Route::resource('approver', BudgetApproverController::class);
        Route::resource('purchase-request', PurchaseController::class);
        Route::get('purchaserequest', [PurchaseController::class, 'getData'])->name('purchase.data');
        Route::get('getYear', [PurchaseController::class, 'getYear'])->name('get.year');
        Route::get('getBalanceByYear', [PurchaseController::class, 'getBalanceByYear'])->name('get.balance.by.year');
        Route::get('purchases/{purchase_no}/details', [PurchaseController::class, 'getDetails'])->name('purchase.details');
        
        
        Route::get('budget-approval', [BudgetRequestController::class, 'BudgetRequestApprovalIndex'])->name('budget-request.approval');
        Route::get('getBudgetRequestApprovalList-approval', [BudgetRequestController::class, 'getBudgetRequestApprovalList'])->name('get.budget-request.approval.list');

        Route::get('getCategoryData', [CategoryController::class, 'getCategoryData'])->name('get.category.data');
        Route::get('getBudgetAllocationYear', [BudgetAllocationController::class, 'getBudgetAllocationYear'])->name('get.budget-allocation.year');
        Route::get('getBudgetData', [BudgetAllocationController::class, 'getBudgetData'])->name('get.budget.data');
        Route::get('getBudgetAllocationAll', [BudgetAllocationController::class, 'getBudgetAllocationAll'])->name('get.budget-allocation.all');
        Route::get('getBudgetNo', [BudgetAllocationController::class, 'getBudgetNo'])->name('get.budget.no');
        Route::get('getBudgetListYear', [BudgetListController::class, 'getBudgetListYear'])->name('get.budget-list.year');
        Route::get('getBudgetList', [BudgetListController::class, 'getBudgetList'])->name('get.budget.list');
        Route::get('getBudgetRequestYear', [BudgetRequestController::class, 'getBudgetRequestYear'])->name('get.budget-request.year');
        Route::get('getBudgetRequestList', [BudgetRequestController::class, 'getBudgetRequestList'])->name('get.budget.request.list');
        Route::get('getApproverData', [BudgetApproverController::class, 'getApproverData'])->name('get.approver.data');
        
        Route::get('getBudgetRequestNo', [BudgetRequestController::class, 'getBudgetRequestNo'])->name('get.budget.request.no');

        Route::get('getLogsData', [ActivityLogController::class, 'getLogsData'])->name('get.logs.data');
    });

    // get data department
    Route::get('getDepartmentData', [DepartmentController::class, 'getDepartmentData'])->name('get.department.data');
});

route::prefix('page')->group(function () {
    Route::get('/budgeting/request/approve', [PurchaseController::class, 'approved'])->name('budgeting.request.approved');
    Route::get('/budgeting/request/reject', [PurchaseController::class, 'rejected'])->name('budgeting.request.reject');
    Route::get('/budgeting/request/endview', [PurchaseController::class, 'endview'])->name('budgeting.request.endview');
    Route::post('/budgeting/request/reject-feedback', [PurchaseController::class, 'submitRejectFeedback'])->name('budgeting.request.reject.feedback');

});

Route::group(['middleware' => ['role:super-admin|admin']], function () {

    Route::resource('permissions', PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [PermissionController::class, 'destroy']);

    Route::resource('roles', RoleController::class);
    Route::get('roles/{roleId}/delete', [RoleController::class, 'destroy']);
    Route::get('roles/{roleId}/give-permissions', [RoleController::class, 'addPermissionToRole']);
    Route::put('roles/{roleId}/give-permissions', [RoleController::class, 'givePermissionToRole']);

    Route::resource('users', UserController::class);
    Route::delete('users/{userId}/delete', [UserController::class, 'destroy']);

    Route::get('departments', [DepartmentController::class, 'index'])->name('department.index');
    Route::post('departments', [DepartmentController::class, 'store'])->name('department.store');
    Route::delete('departments/{department:department_slug}/delete', [DepartmentController::class, 'destroy'])->name('department.destroy');
    Route::put('departments/{department:department_slug}/update', [DepartmentController::class, 'update'])->name('department.update');

    Route::get('positions', [PositionController::class, 'index'])->name('position.index');
    Route::delete('positions/{position:position_slug}/delete', [PositionController::class, 'destroy'])->name('positions.destroy');
    Route::put('positions/{position:position_slug}/update', [PositionController::class, 'update'])->name('positions.update');
    Route::post('positions', [PositionController::class, 'store'])->name('position.store');

    Route::get('levels', [LevelController::class, 'index'])->name('level.index');
    Route::put('levels/{level:level_slug}/update', [LevelController::class, 'update'])->name('level.update');
    Route::post('levels', [LevelController::class, 'store'])->name('level.store');
    Route::delete('levels/{level:level_slug}/delete', [LevelController::class, 'destroy'])->name('level.destroy');

    Route::resource('purchase-request', PurchaseController::class);
    Route::get('purchaserequest', [PurchaseController::class, 'getData'])->name('purchase.data');
    Route::get('getYear', [PurchaseController::class, 'getYear'])->name('get.year');
    Route::get('getBalanceByYear', [PurchaseController::class, 'getBalanceByYear'])->name('get.balance.by.year');
    Route::get('/purchases/{purchase_no}/details', [PurchaseController::class, 'getDetails'])->name('purchase.details');
});









require __DIR__ . '/auth.php';
