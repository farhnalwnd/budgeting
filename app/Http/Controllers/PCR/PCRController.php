<?php

namespace App\Http\Controllers\PCR;

use App\Http\Controllers\Controller;
use App\Models\PCR\PCR;
use Illuminate\Http\Request;

class PCRController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('page.pcr.list-pcr');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.pcr.create-pcr');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PCR $pCR)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PCR $pCR)
    {
        return view('page.pcr.edit-pcr');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PCR $pCR)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PCR $pCR)
    {
        //
    }

    /*
    * Initiator Approval
    */
    public function initiatorApprovals()
    {
        return view('page.pcr.initiator-approval');
    }

    /*
    * PCC Approval
    */
    public function committeeApprovals()
    {
        return view('page.pcr.pcc-approval');
    }
}
