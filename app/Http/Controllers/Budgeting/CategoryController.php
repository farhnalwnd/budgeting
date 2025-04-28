<?php

namespace App\Http\Controllers\Budgeting;

use App\Http\Controllers\Controller;
use App\Models\Budgeting\CategoryMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        confirmDelete();
        return view('page.budgeting.management.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Mulai transaction untuk memastikan integritas data
        DB::beginTransaction();

        try{
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $user = Auth::user();
            $category = CategoryMaster::create([
                'name' => $validatedData['name']
            ]);
            
            // Commit transaksi
            DB::commit();
            activity()
                ->performedOn($category)
                ->inLog('category')
                ->event('Create')
                ->causedBy($user)
                ->withProperties(['no' => $category->id, 'action' => 'create', 'category' => $category->name])
                ->log('Create category ' . $category->name . ' by ' . $user->name . ' at ' . now());

            Alert::toast('Category successfully saved!', 'success');
            return redirect()->route('category.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error saving the category.'.$e->getMessage(), 'error');
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Mulai transaction untuk memastikan integritas data
        DB::beginTransaction();

        try{
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $user = Auth::user();
            $category = CategoryMaster::find($id);
            $categoryOldName = $category->name;
            $category->update([
                'name' => $validatedData['name']
            ]);
            
            // Commit transaksi
            DB::commit();
            activity()
                ->performedOn($category)
                ->inLog('category')
                ->event('Update')
                ->causedBy($user)
                ->withProperties(['no' => $category->id, 'action' => 'create', 'category' => $category->name])
                ->log('Update category ' . $categoryOldName . ' to ' . $category->name . ' by ' . $user->name . ' at ' . now());

            Alert::toast('Category successfully saved!', 'success');
            return redirect()->route('category.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error saving the category.'.$e->getMessage(), 'error');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getCategoryData(){
        $category = CategoryMaster::all();
        return response()->json($category);
    }
}
