<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Courier;


class CourierController extends Controller
{
    public function index()
    {
        $items=Courier::all();
        return view('backend.couriers.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->can('size.create'))
        {
            abort(403, 'unauthorized');
        }

        return view('backend.couriers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->can('size.create'))
        {
            abort(403, 'unauthorized');
        }

        $data=$request->validate([
             'name'=> 'required',
             'phone'=> '',
             'email'=> '',
             'address'=> 'required',
        ]);

        Courier::create($data);

        return response()->json(['status'=>true ,'msg'=>'Courier Is  Created !!','url'=>route('admin.couriers.index')]);
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
        if(!auth()->user()->can('size.edit'))
        {
            abort(403, 'unauthorized');
        }

        $item=Courier::find($id);
        return view('backend.couriers.edit', compact('item'));
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

        $category=Courier::find($id);
        $data=$request->validate([
             'name'=> 'required',
             'phone'=> '',
             'email'=> '',
             'address'=> 'required',
        ]);
       
        $category->update($data);

        return response()->json(['status'=>true ,'msg'=>'Courier Is Updated !!','url'=>route('admin.couriers.index')]);

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

        $category=Courier::find($id);
        $category->delete();
        return response()->json(['status'=>true ,'msg'=>'Courier Is Deleted !!']);

    }


}
