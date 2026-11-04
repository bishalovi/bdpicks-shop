<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CouponCode;

class CouponCodeController extends Controller
{
    public function index()
    {
        $items=CouponCode::all();
        return view('backend.coupon_codes.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->can('coupon_codes.create'))
        {
            abort(403, 'unauthorized');
        }

        return view('backend.coupon_codes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->can('coupon_codes.create'))
        {
            abort(403, 'unauthorized');
        }

        $data=$request->validate([
                'code'=> 'required',
                'amount'=> 'required',
                'start'=> 'required',
                'end'=> 'required',
                'minimum_amount'=> '',
                'discount_type'=> 'required',
        ]);

        CouponCode::create($data);

        return response()->json(['status'=>true ,'msg'=>'CouponCode Is  Created !!','url'=>route('admin.coupon_codes.index')]);
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
        if(!auth()->user()->can('coupon_codes.edit'))
        {
            abort(403, 'unauthorized');
        }

        $item=CouponCode::find($id);
        return view('backend.coupon_codes.edit', compact('item'));
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
        if(!auth()->user()->can('coupon_codes.edit'))
        {
            abort(403, 'unauthorized');
        }

        $category=CouponCode::find($id);
        $data=$request->validate([
            'code'=> 'required',
             'amount'=> 'required',
             'start'=> 'required',
             'end'=> 'required',
             'minimum_amount'=> '',
             'discount_type'=> 'required',
        ]);
       
        $category->update($data);

        return response()->json(['status'=>true ,'msg'=>'CouponCode Is Updated !!','url'=>route('admin.coupon_codes.index')]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->can('coupon_codes.delete'))
        {
            abort(403, 'unauthorized');
        }

        $category=CouponCode::find($id);
        $category->delete();
        return response()->json(['status'=>true ,'msg'=>'CouponCode Is Deleted !!']);

    }
    
}
