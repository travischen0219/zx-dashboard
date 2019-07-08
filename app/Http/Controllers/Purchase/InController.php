<?php

namespace App\Http\Controllers\Purchase;

use App\Model\In;
use App\Model\Lot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->status ?? '';

        $data = [];

        $ins = In::orderBy('id', 'desc');
        if ($status != '') $ins->where('status', $status);
        $ins = $ins->get();

        $data['ins'] = $ins;
        $data['statuses'] = In::statuses();
        $data['status'] = $status;

        return view('purchase.in.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];

        $data['in'] = new In;
        $data['statuses'] = In::statuses();

        return view('purchase.in.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\In  $in
     * @return \Illuminate\Http\Response
     */
    public function show(In $in)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\In  $in
     * @return \Illuminate\Http\Response
     */
    public function edit(In $in)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\In  $in
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, In $in)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\In  $in
     * @return \Illuminate\Http\Response
     */
    public function destroy(In $in)
    {
        //
    }
}
