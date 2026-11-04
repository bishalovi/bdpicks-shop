<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeSectionImage;

class HomeSectionImageController extends Controller
{
    public function index()
    {
  
        $items=HomeSectionImage::paginate(20);
        return view('backend.home_section_images.index', compact('items'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->can('image.create'))
        {
            abort(403, 'unauthorized');
        }

        $data=$request->validate([
             'section'=> 'required',
             'image'=> 'required',
             'link'=> '',
             'is_for_small'=> '',
        ]);
        
        if($request->hasFile('mobile_image')) {
            $originName = $request->file('mobile_image')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('mobile_image')->getClientOriginalExtension();
            $fileName =$fileName.time().'.'.$extension;
        
            $request->file('mobile_image')->move(public_path('homeimages'), $fileName);
            $data['mobile_image']=$fileName;
        }
        
        if($request->hasFile('image')) {
            $originName = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName =$fileName.time().'.'.$extension;
        
            $request->file('image')->move(public_path('homeimages'), $fileName);
            $data['image']=$fileName;
        }

        HomeSectionImage::create($data);

        return response()->json(['status'=>true ,'msg'=>'HomeSectionImage Is  Created !!','url'=>route('admin.home_section_images.index')]);
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
        if(!auth()->user()->can('image.edit'))
        {
            abort(403, 'unauthorized');
        }

        $item=HomeSectionImage::find($id);
        return view('backend.home_section_images.edit', compact('item'));
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
        if(!auth()->user()->can('image.edit'))
        {
            abort(403, 'unauthorized');
        }

        $homeimage=HomeSectionImage::find($id);
        $data=$request->validate([
             'title'=> '',
             'text'=> '',
             'link'=> '',
             'section'=> 'required'
        ]);

        if($request->hasFile('image')) {
            deleteImage('homeimages', $homeimage->image);
            $originName = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName =$fileName.time().'.'.$extension;
        
            $request->file('image')->move(public_path('homeimages'), $fileName);
            $data['image']=$fileName;
        }
        
        
        if($request->hasFile('mobile_image')) {
            deleteImage('homeimages', $homeimage->mobile_image);
            $originName = $request->file('mobile_image')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('mobile_image')->getClientOriginalExtension();
            $fileName =$fileName.time().'.'.$extension;
        
            $request->file('mobile_image')->move(public_path('homeimages'), $fileName);
            $data['mobile_image']=$fileName;
        }
        
       
        $homeimage->update($data);

        return response()->json(['status'=>true ,'msg'=>'HomeSectionImage Is Updated !!','url'=>route('admin.home_section_images.index')]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->can('image.delete'))
        {
            abort(403, 'unauthorized');
        }

        $item=HomeSectionImage::find($id);
        deleteImage('homeimages', $item->image);
        deleteImage('homeimages', $item->mobile_image);
        $item->delete();
        return response()->json(['status'=>true ,'msg'=>'HomeSectionImage Is Deleted !!']);

    }
}
