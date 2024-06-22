<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\RequisitionMaster;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
public function index()
{
    $totalItem = Item::count();
    $totalSupplier = Supplier::count();
    $totalUser = User::count();
    $totalRequisitionMaster = RequisitionMaster::count();

    return view('dashboard', compact('totalItem', 'totalSupplier', 'totalUser', 'totalRequisitionMaster'));
}
}
