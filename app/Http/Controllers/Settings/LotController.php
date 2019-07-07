<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Lot;
use App\Model\Customer;
use App\Http\Requests\LotPostRequest;

class LotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];

        $data['lots'] = Lot::orderBy('is_finished', 'asc')->get();
        $data['customers'] = Customer::allWithKey();

        return view('settings.lot.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['lot'] = new Lot;
        $data['customers'] = Customer::allWithKey();

        return view('settings.lot.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LotPostRequest $request)
    {
        $validated = $request->validated();

        $lot = Lot::create($request->all());
        $lot->created_user = session('admin_user')->id;
        $lot->save();

        return redirect(route('lot.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [];
        $data['lot'] = Lot::find($id);
        $data['customers'] = Customer::allWithKey();

        return view('settings.lot.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LotPostRequest $request, $id)
    {
        $validated = $request->validated();

        $lot = Lot::find($id);
        $lot->name = $request->name;
        $lot->customer_id = $request->customer_id;
        $lot->start_date = $request->start_date;
        $lot->end_date = $request->end_date;
        $lot->status = $request->status;
        $lot->is_finished = $request->is_finished;
        $lot->memo = $request->memo;
        $lot->updated_user = session('admin_user')->id;
        $lot->save();

        return redirect(route('lot.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lot = Lot::find($id);

        $lot->deleted_user = session('admin_user')->id;
        $lot->save();

        $lot->delete();

        return redirect(route('lot.index'));
    }
}
