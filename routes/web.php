<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ApproverController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Role\PermissionController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WSA\RQMController;
use App\Models\CostCenter;
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
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    /* Dashboard */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-finance', [DashboardController::class, 'index-finance'])->name('dashboard-finance');
    Route::get('/dashboard-sales', [DashboardController::class, 'index-sales'])->name('dashboard-sales');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/rqm-maintenance', [RQMController::class, 'index'])->name('rqm.index');
    Route::get('/rqm-browser', [RQMController::class, 'requisitionBrowser'])->name('rqm.browser');
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
});
Route::get('/get-approver', [ApproverController::class, 'getApprover']);
Route::get('/getSupplier', [SupplierController::class, 'getSupplierAjax'])->name('get.suppliers.ajax');
Route::get('/getitems', [ItemController::class, 'getItemAjax'])->name('get.items.ajax');
Route::get('/getaccount', [AccountController::class, 'getAccountAjax'])->name('get.account.ajax');
Route::post('/delete-line', [RQMController::class, 'deleteLine'])->name('rqm.deleteLine');





Route::group(['middleware' => ['role:super-admin|admin']], function () {

    Route::resource('permissions', PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [PermissionController::class, 'destroy']);

    Route::resource('roles', RoleController::class);
    Route::get('roles/{roleId}/delete', [RoleController::class, 'destroy']);
    Route::get('roles/{roleId}/give-permissions', [RoleController::class, 'addPermissionToRole']);
    Route::put('roles/{roleId}/give-permissions', [RoleController::class, 'givePermissionToRole']);

    Route::resource('users', UserController::class);
    Route::get('users/{userId}/delete', [UserController::class, 'destroy']);

    Route::get('departments', [DepartmentController::class, 'index'])->name('department.index');
    Route::get('departments/create', [DepartmentController::class, 'create'])->name('department.create');
    Route::post('departments', [DepartmentController::class, 'store'])->name('department.store');
    Route::get('departments/{department:slug}/delete', [DepartmentController::class, 'destroy'])->name('department.destroy');
    Route::get('departments/{department:slug}/edit', [DepartmentController::class, 'edit'])->name('department.edit');
    Route::put('departments/{department:slug}/edit', [DepartmentController::class, 'update'])->name('department.update');

    Route::get('positions', [PositionController::class, 'index'])->name('position.index');
    Route::get('positions/create', [PositionController::class, 'create'])->name('position.create');
    Route::get('positions/{position:slug}', [PositionController::class, 'show'])->name('position.show');
    Route::get('positions/{position:slug}/delete', [PositionController::class, 'destroy']);
    Route::get('positions/{position:slug}/edit', [PositionController::class, 'edit']);
    Route::put('positions/{position:slug}/edit', [PositionController::class, 'update']);
    Route::post('positions', [PositionController::class, 'store'])->name('position.store');

    Route::get('levels', [LevelController::class, 'index'])->name('level.index');
    Route::get('levels/create', [LevelController::class, 'create'])->name('level.create');
    Route::get('levels/{level:slug}', [LevelController::class, 'show'])->name('level.show');
    Route::get('levels/{level:slug}/delete', [LevelController::class, 'destroy']);
    Route::get('levels/{level:slug}/edit', [LevelController::class, 'edit']);
    Route::put('levels/{level:slug}/edit', [LevelController::class, 'update']);
    Route::post('levels', [LevelController::class, 'store'])->name('level.store');
});



require __DIR__ . '/auth.php';
