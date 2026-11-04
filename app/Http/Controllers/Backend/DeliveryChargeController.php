<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliveryCharge;


class DeliveryChargeController extends Controller
{
    public function index()
    {
        if(!auth()->user()->can('delivery_charge.view'))
        {
            abort(403, 'unauthorized');
        }
        
        $items=DeliveryCharge::all();
        return view('backend.delivery_charge.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->can('delivery_charge.create'))
        {
            abort(403, 'unauthorized');
        }

        return view('backend.delivery_charge.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->can('delivery_charge.create'))
        {
            abort(403, 'unauthorized');
        }

        $data=$request->validate([
             'title'=> 'required',
             'amount'=> 'required',
             'status'=> '',
        ]);

        DeliveryCharge::create($data);

        return response()->json(['status'=>true ,'msg'=>'DeliveryCharge Is  Created !!','url'=>route('admin.delivery_charge.index')]);
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
        if(!auth()->user()->can('delivery_charge.edit'))
        {
            abort(403, 'unauthorized');
        }

        $item=DeliveryCharge::find($id);
        return view('backend.delivery_charge.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!auth()->user()->can('size.edit'))
        {
            abort(403, 'unauthorized');
        }

        $category=DeliveryCharge::find($id);
        $data=$request->validate([
             'title'=> 'required',
             'amount'=> 'required',
        ]);
        
        $data['status']=$request->status;
        
    
       
        $category->update($data);

        return response()->json(['status'=>true ,'msg'=>'DeliveryCharge Is Updated !!','url'=>route('admin.delivery_charge.index')]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->can('size.delete'))
        {
            abort(403, 'unauthorized');
        }

        $category=DeliveryCharge::find($id);
        $category->delete();
        return response()->json(['status'=>true ,'msg'=>'DeliveryCharge Is Deleted !!']);

    }
    
    
}
