<?php

use App\Http\Controllers\QAD\AccountController;
use App\Http\Controllers\QAD\ApproverController;
use App\Http\Controllers\Auth\LockScreenController;
use App\Http\Controllers\Auth\UnlockableController;
use App\Http\Controllers\QAD\CostCenterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\QAD\EmployeeController;
use App\Http\Controllers\QAD\ItemController;
use App\Http\Controllers\QAD\LevelController;
use App\Http\Controllers\PCR\PCRController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QAD\RequisitionApprovalDetailController;
use App\Http\Controllers\QAD\RequisitionMasterController;
use App\Http\Controllers\Role\PermissionController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\QAD\SubAccountController;
use App\Http\Controllers\QAD\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WSA\RQMController;
use App\Models\QAD\RequisitionMaster;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Route;


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
    Route::get('/api/requisitions/{year}', [DashboardController::class, 'getRequisitionsByYear'])->name('dashboard.requisitions.byYear');
    Route::get('/dashboard-sales', [DashboardController::class, 'index-sales'])->name('dashboard-sales');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile', [UserController::class, 'updateProfile'])->name('profile.updates');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/rqm-maintenance', [RQMController::class, 'index'])->name('rqm.index');
    Route::get('/rqm-browser', [RQMController::class, 'requisitionBrowser'])->name('rqm.browser');
    Route::get('/rqm-report', [RequisitionMasterController::class, 'report'])->name('rqm.report');
    Route::get('/rqm-approval', [RequisitionMasterController::class, 'approval'])->name('rqm.approval');

    Route::get('/rqm/approve/{rqmNbr}/{token}', [RequisitionMasterController::class, 'approveEmail'])->name('rqm.approveEmail');
    Route::get('/rqm/decline/{rqmNbr}/{token}', [RequisitionMasterController::class, 'declineEmail'])->name('rqm.declineEmail');
    Route::get('/rqm/notification-approval', [RequisitionMasterController::class, 'notificationApproval'])->name('rqm.notificationApproval');


    Route::get('/rqm/approve/{rqmNbr}', [RequisitionMasterController::class, 'approve'])->name('rqm.approve');
    Route::get('/rqm/decline/{rqmNbr}', [RequisitionMasterController::class, 'decline'])->name('rqm.decline');

    Route::post('/requisition/bulk-approved', [RequisitionMasterController::class, 'bulkApproved'])->name('rqm.bulkApproved');

    Route::get('/requisition/resend/{rqmNbr}/{resendTo}', [RequisitionMasterController::class, 'resend'])->name('rqm.resend');

    Route::post('/requisition/bulk-print', [RequisitionMasterController::class, 'bulkPrint'])->name('rqm.bulkPrint');
    Route::post('/getpr', [RQMController::class, 'getpr'])->name('get.pr.number');
    Route::get('/edit-rqm/{rqmNbr}', [RQMController::class, 'edit'])->name('rqm.edit');
    Route::post('/store', [RQMController::class, 'store'])->name('store');
    Route::get('/get-master', [RQMController::class, 'getDataMaster'])->name('get.master');

    Route::post('/update-rqm/{rqmNbr}', [RQMController::class, 'update'])->name('rqm.update');
    Route::post('/deletepr/{rqmNbr}', [RQMController::class, 'delete'])->name('rqm.delete');
    Route::get('/requisition/print/{rqmNbr}', [RQMController::class, 'printRequisition'])->name('rqm.print');

    /* items */
    Route::post('/getitems', [ItemController::class, 'getItemAndStoreMaster'])->name('get.items');


    /* supplier */
    Route::post('/getSupplier', [SupplierController::class, 'getSuppliersAndStoreMaster'])->name('get.suppliers');


    /* costcenter */
    Route::post('/getcost', [CostCenterController::class, 'getCostCenterAndStoreMaster'])->name('get.costcenter');

    /* getaccount */
    Route::post('/getaccount', [AccountController::class, 'getAccountAndStoreMaster'])->name('get.account');


    /* getapprover */
    Route::post('/getapprover', [ApproverController::class, 'getApproverAndStoreMaster'])->name('get.approver');

    /* getEmployees */
    Route::post('/getEmployees', [EmployeeController::class, 'getEmployees'])->name('get.employees');

    /* Sub Account */
    Route::post('/getSubAccount', [SubAccountController::class, 'getSubAccounts'])->name('get.subaccount');

    Route::get('locked', [LockScreenController::class, 'show'])
        ->name('locked');

    Route::post('locked', [LockScreenController::class, 'store']);


    /*PCR*/
    Route::get('pcr', [PCRController::class, 'index'])->name('pcr.index');
    Route::get('pcr/create', [PCRController::class, 'create'])->name('pcr.create');
    Route::get('pcr/edit', [PCRController::class, 'edit'])->name('pcr.edit');
    Route::post('pcr/store', [PCRController::class, 'store'])->name('pcr.store');
    Route::post('pcr/update', [PCRController::class, 'update'])->name('pcr.update');
    Route::post('pcr/delete', [PCRController::class, 'delete'])->name('pcr.delete');

    Route::get('pcr/approvals', [PCRController::class, 'approvals'])->name('pcr.approvals');
    Route::get('pcr/initiator-approvals', [PCRController::class, 'initiatorApprovals'])->name('pcr.initiator.approval');
    Route::get('pcr/committee-approvals', [PCRController::class, 'committeeApprovals'])->name('pcr.committee.approval');
    Route::get('pcr/initiators', [PCRController::class, 'initiators'])->name('pcr.initiators');
    Route::get('pcr/nature-of-changes', [PCRController::class, 'natureOfChanges'])->name('pcr.nature.of.changes');
    Route::get('pcr/pcc', [PCRController::class, 'PCC'])->name('pcr.pcc');
    Route::get('pcr/products', [PCRController::class, 'products'])->name('pcr.products');
    Route::get('pcr/revision', [PCRController::class, 'revision'])->name('pcr.revision');
});
Route::get('/get-approver', [ApproverController::class, 'getApprover']);
Route::get('/getSupplier', [SupplierController::class, 'getSupplierAjax'])->name('get.suppliers.ajax');
Route::get('/getitems', [ItemController::class, 'getItemAjax'])->name('get.items.ajax');
Route::get('/getaccount', [AccountController::class, 'getAccountAjax'])->name('get.account.ajax');
Route::post('/delete-line', [RQMController::class, 'deleteLine'])->name('rqm.deleteLine');
Route::post('rqm/bulk-delete', [RQMController::class, 'bulkDelete'])->name('rqm.bulk-delete');
Route::delete('/notifications/clear', [RequisitionMasterController::class, 'clearAll'])->name('notifications.clear');
Route::post('rqm/checkCurr', [RQMController::class, 'checkCurr'])->name('check.curr');
Route::post('rqm/checkPrice', [RQMController::class, 'checkPrice'])->name('check.price');


Route::get('/requisition-approval-status', [RequisitionApprovalDetailController::class, 'getApprovalStatus']);
Route::get('/long-polling', [RequisitionApprovalDetailController::class, 'getAllApprovalStatuses']);
Route::patch('/notifications/{notification}', [RequisitionMasterController::class, 'markAsRead']);




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
});








require __DIR__ . '/auth.php';
