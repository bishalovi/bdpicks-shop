<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Size;
use App\Models\Type;
use App\Models\Color;
use App\Models\Variation;
use DB;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use Image;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function productExport(){
        return Excel::download(new ProductExport, 'products.xlsx');
    }

    public function index()
    {
      
      	if(!auth()->user()->can('product.view'))
        {
            abort(403, 'unauthorized');
        }
        $cat_id = '';
        $q=request()->q;
        $query=Product::query();
                if(!empty($q)){
                    $query->where(function($row) use ($q){
                        $row->where('name','Like','%'.$q.'%');
                        $row->orwhere('description','Like','%'.$q.'%');
                      	$row->orwhere('sku','Like','%'.$q.'%');
                    });
                }
      			
      		if(auth()->user()->hasRole('admin')==false){
              $query->where('user_id', auth()->user()->id);
            }
            
        $categories = Category::where('parent_id', null)->get();    
     
        $items=$query->latest()->paginate(30);
        
        return view('backend.products.index', compact('items','q','categories','cat_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubcategory(){

        $cats=Category::where('parent_id', request('cat_id'))->select('name','id')->pluck('name','id')->toArray();

        return response()->json($cats);
    }
    public function create()
    {
        if(!auth()->user()->can('product.create'))
        {
            abort(403, 'unauthorized');
        }

        $cats=Category::whereNull('parent_id')->get();
        $sizes=Size::all();
        $types=Type::all();
        $colors=Color::all();
        return view('backend.products.create', compact('cats','sizes','types','colors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->can('product.create'))
        {
            abort(403, 'unauthorized');
        }

        $data=$request->validate([
             'name'=> 'required',
             'type'=> 'required',
             'image'=> 'required|image',
             'category_id'=> 'required',
             'sub_category_id'=> '',
             'type_id'=> '',
             'description'=> '',
             'body'=> '',
             'feature'=> '',
             'sku'=> '',
             'purchase_prices'=> '',
             'sell_price'=> 'required|numeric',
             'regular_price'=> '',
             'is_stock' => '',
             'video_link' => '',
             'discount_type' => '',
             'dicount_amount' => '',
             'after_discount' => '',
        ]);
      $data['user_id']=auth()->user()->id;
      $data['stock_quantity']=$request->pro_quantity;
      

    DB::beginTransaction();

        try {
            
        if($request->hasFile('image')) {
            $image = Image::make($request->file('image'));
            /**
             * Main Image Upload on Folder Code
             */
             
            $extention = $request->image->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention; 
            $destinationPath = public_path('products/');
            $image->resize(800,800);
            $image->save($destinationPath.$image_name);
            $data['image']=$image_name;
  
            /**
             * Generate Thumbnail Image Upload on Folder Code
             */
            $destinationPathThumbnail = public_path('thumb_products/');
            $image->resize(200,200);
            $image->save($destinationPathThumbnail.$image_name);
        }
       
        $product=Product::create($data);

        if(isset($request->images)) {

            $image_data=[];
            $imageName='';
            foreach ($request->images as $key => $image) {
                
                $extention = $image->getClientOriginalExtension();
                $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
                $image = Image::make($image);
                $destinationPath = public_path('products/');
                $image->resize(800,800);
                $image->save($destinationPath.$image_name);
                $image_data[]=['image'=>$image_name];
            }

            if (!empty($image_data)) {
                $product->images()->createMany($image_data);
            }
        } 
        
        if ($request->type == 'variable') {
            
            $variable_data=[];
            $stock_data = [];
            
            foreach ($request->size_id as $key => $size) {
                
               $var_data2 =  Variation::create([
                    'product_id' => $product->id,
                    'size_id' => $size,
                    'color_id' => $request->color_id[$key],
                    'purchase_price'=>$request->purchase_price[$key],
                    'price' => $request->price[$key],
                    'after_discount_price' => $request->after_discount_price[$key],
                    'stock_quantity' => $request->quantity[$key]
                ]);
                
                $stock_product = ProductStock::create([
                'product_id' => $product->id,
                'variation_id' => $var_data2->id,
                'quantity'   => $request->quantity[$key]
                ]);
                
                // $variable_data[]=[
                //     'size_id' => $size,
                //     'color_id' => $request->color_id[$key],
                //     'price' => $request->price[$key],
                //     'after_discount_price' => $request->after_discount_price[$key]
                // ];
            }

            // if (!empty($variable_data)) {
            //     $product->variations()->createMany($variable_data);
            // }
            
        } else {
           
            $variable_data=[];
            $var_data3 = Variation::create([
                'product_id' => $product->id,
                'size_id'=>"3",
                'color_id'=>"1",
                'purchase_price'=>$request->purchase_prices,
                'price'=>$request->sell_price,
                'after_discount_price'=>$request->after_discount,
                'stock_quantity'=>$request->pro_quantity
            ]);
            
            $stock_product = ProductStock::create([
                'product_id' => $product->id,
                'variation_id' => $var_data3->id,
                'quantity'   => $request->pro_quantity
            ]);
            
            // if (!empty($variable_data)) {
            //     $product->variations()->createMany($variable_data);
            // }
        }

        DB::commit();
        return response()->json(['status'=>true ,'msg'=>'Product Is  Created !!','url'=>route('admin.products.index')]);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
    }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('sizes','sizes.stocks')->find($id);
        return view('backend.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->can('product.edit'))
        {
            abort(403, 'unauthorized');
        }

        $item=Product::with('sizes')->find($id);
        $cats=Category::whereNull('parent_id')->get();
        $sizes=Size::all();
        $types=Type::all();
        $colors=Color::all();

        $subs=Category::where('parent_id', $item->category_id)->get();

        return view('backend.products.edit', compact('item','cats','sizes','types','subs','colors'));
    }
  
  	public function productCopy($id){
    	$item=Product::with('sizes')->find($id);
        $cats=Category::whereNull('parent_id')->get();
        $sizes=Size::all();
        $types=Type::all();
        $colors=Color::all();

        $subs=Category::where('parent_id', $item->category_id)->get();
      	return view('backend.products.copy', compact('item','cats','sizes','types','subs','colors'));
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

    //     if(!auth()->user()->can('product.edit'))
    //     {
    //         abort(403, 'unauthorized');
    //     }

    //     $product=Product::find($id);
    //     $data=$request->validate([
    //          'name'=> 'required',
    //          'category_id'=> 'required',
    //          'sub_category_id'=> '',
    //          'type_id'=> '',
    //          'description'=> '',
    //          'body'=> '',
    //          'feature'=> '',
    //          'sku'=> '',
    //          'purchase_price'=> '',
    //          'regular_price'=> '',
    //          'sell_price'=> 'required|numeric',
    //          'is_stock' => '',
    //          'discount_type' => ''
    //     ]);
        
    //     $data['after_discount'] = $request->after_discount;
    //     $data['dicount_amount'] = $request->dicount_amount;
    //     $data['sell_price'] = $request->sell_price;
        
    //     $data['stock_quantity']=$request->pro_quantity;

    // DB::beginTransaction();

    //     try {
        
    //     if($request->hasFile('image')) {
    //         deleteImage('products', $product->image);
    //         $image = Image::make($request->file('image'));
    //         /**
    //          * Main Image Upload on Folder Code
    //         */
            
    //         $extention = $request->image->getClientOriginalExtension();
    //         $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
    //         $destinationPath = public_path('products/');
    //         $image->resize(800,800);
    //         $image->save($destinationPath.$image_name);
    //         $data['image']=$image_name;
  
    //         /**
    //          * Generate Thumbnail Image Upload on Folder Code
    //          */
    //         deleteImage('thumb_products', $product->image);
    //         $destinationPathThumbnail = public_path('thumb_products/');
    //         $image->resize(200,200);
    //         $image->save($destinationPathThumbnail.$image_name);
    //     }
        
    //     if(isset($request->images)) {

    //         $image_data=[];
    //         $imageName='';
    //         foreach ($request->images as $key => $image) {
                
    //             $extention = $image->getClientOriginalExtension();
    //             $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
    //             $image = Image::make($image);
                
    //             $destinationPath = public_path('products/');
    //             $image->resize(800,800);
    //             $image->save($destinationPath.$image_name);
    //             $image_data[]=['image'=>$image_name];
    //         }

    //         if (!empty($image_data)) {
    //             $product->images()->createMany($image_data);
    //         }
            
    //     }
    
    //     $product->update($data);
        
    //     if($product->type == 'variable') {
            
    //       $delete_variations=Variation::where('product_id',$id)->whereNotIn('id',$request->variation_id)->get();
        
        
    //         if ($delete_variations->count()) {
    //             foreach ($delete_variations as $key => $dvariation) {
    //                 $dvariation->delete();
    //             }
    //         }   
    //     } else {
            
    //     }

    //     if (isset($request->size_id)) {
    //         $variable_data=[];
    //         foreach ($request->size_id as $key => $size) {
    //             if (isset($request->variation_id[$key])) {
    //                 $variable=Variation::where('id', $request->variation_id[$key])->first();
    //                 $stock = ProductStock::where('product_id', $request->product_id[$key])->where('variation_id', $request->variation_id[$key])->first();
    //                 $variable->size_id=$size;
    //                 $variable->color_id=$request->color_id[$key];
    //                 $variable->price=$request->price[$key];
    //                 $variable->after_discount_price=$request->after_discount_price[$key];
    //                 $variable->stock_quantity=$request->quantity[$key];
                    
    //                 // $stock->product_id = $request->product_id[$key];
    //                 $stock->quantity = $request->quantity[$key];
    //                 // $stock->variation_id = $request->variation_id[$key];
    //                 $variable->save();
    //                 $stock->save();
    //             }else{
    //                 $variable_data = Variation::create([
    //                     'product_id'           => $product->id,
    //                     'size_id'              => $size,
    //                     'color_id'             => $request->color_id[$key],
    //                     'price'                => $request->price[$key],
    //                     'after_discount_price' =>$request->after_discount_price[$key],
    //                     'stock_quantity'       => $request->quantity[$key]
    //                 ]);
                    
    //                 ProductStock::create([
    //                     'product_id'   => $product->id,
    //                     'variation_id' => $variable_data->id,
    //                     'quantity'     => $request->quantity[$key]
    //                 ]);
    //             }                
    //         }
            
    //     } else {
           
    //         $variable=Variation::where('product_id', $product->id)->first();
    //         $stock = ProductStock::where('product_id', $product->id)->where('variation_id', $variable->id)->first();
    //         $variable->price = $request->sell_price;
    //         $variable->after_discount_price = $request->after_discount;
    //         $stock->quantity = $request->pro_quantity;
            
    //         $variable->save();
    //         $stock->save();
    //     }
    
    //     DB::commit();
    //     return response()->json(['status'=>true ,'msg'=>'Product Is Updated !!','url'=>route('admin.products.index')]);
    // } catch (\Exception $e) {
    //     DB::rollback();
    //     return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
    // }     
    
    if(!auth()->user()->can('product.edit'))
        {
            abort(403, 'unauthorized');
        }

        $product=Product::find($id);
        $data=$request->validate([
             'name'=> 'required',
             'category_id'=> 'required',
             'sub_category_id'=> '',
             'type_id'=> '',
             'description'=> '',
             'body'=> '',
             'feature'=> '',
             'sku'=> '',
             'purchase_prices'=> '',
             'regular_price'=> '',
             'sell_price'=> 'required|numeric',
             'is_stock' => '',
             'discount_type' => '',
             'video_link' => ''
        ]);
        
        $data['after_discount'] = $request->after_discount;
        $data['dicount_amount'] = $request->dicount_amount;
        $data['sell_price'] = $request->sell_price;
        
        $data['stock_quantity']=$request->pro_quantity;

    DB::beginTransaction();

        try {
        
        if($request->hasFile('image')) {
            deleteImage('products', $product->image);
            $image = Image::make($request->file('image'));
            /**
             * Main Image Upload on Folder Code
            */
            
            $extention = $request->image->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $destinationPath = public_path('products/');
            $image->resize(800,800);
            $image->save($destinationPath.$image_name);
            $data['image']=$image_name;
  
            /**
             * Generate Thumbnail Image Upload on Folder Code
             */
            deleteImage('thumb_products', $product->image);
            $destinationPathThumbnail = public_path('thumb_products/');
            $image->resize(200,200);
            $image->save($destinationPathThumbnail.$image_name);
        }
        
        if(isset($request->images)) {

            $image_data=[];
            $imageName='';
            foreach ($request->images as $key => $image) {
                
                $extention = $image->getClientOriginalExtension();
                $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
                $image = Image::make($image);
                
                $destinationPath = public_path('products/');
                $image->resize(800,800);
                $image->save($destinationPath.$image_name);
                $image_data[]=['image'=>$image_name];
            }

            if (!empty($image_data)) {
                $product->images()->createMany($image_data);
            }
            
        }
    
        $product->update($data);
        
        if($product->type == 'variable') {
            
          $delete_variations=Variation::where('product_id',$id)->whereNotIn('id',$request->variation_id)->get();
        
        
            if ($delete_variations->count()) {
                foreach ($delete_variations as $key => $dvariation) {
                    $dvariation->delete();
                }
            }   
        } else {
            
        }

        if (isset($request->size_id)) {
            $variable_data=[];
            foreach ($request->size_id as $key => $size) {
                if (isset($request->variation_id[$key])) {
                    $variable=Variation::where('id', $request->variation_id[$key])->first();
                    $stock = ProductStock::where('product_id', $request->product_id[$key])->where('variation_id', $request->variation_id[$key])->first();
                    $variable->size_id=$size;
                    $variable->color_id=$request->color_id[$key];
                    $variable->purchase_price=$request->purchase_price[$key];
                    $variable->price=$request->price[$key];
                    $variable->after_discount_price=$request->after_discount_price[$key];
                    $variable->stock_quantity=$request->quantity[$key];
                    
                    // $stock->product_id = $request->product_id[$key];
                    $stock->quantity = $request->quantity[$key];
                    // $stock->variation_id = $request->variation_id[$key];
                    $variable->save();
                    $stock->save();
                }else{
                    $variable_data = Variation::create([
                        'product_id'           => $product->id,
                        'size_id'              => $size,
                        'color_id'             => $request->color_id[$key],
                        'purchase_price' => $request->variable_purchase_price[$key],
                        'price'                => $request->price[$key],
                        'after_discount_price' =>$request->after_discount_price[$key],
                        'stock_quantity'       => $request->quantity[$key]
                    ]);
                    
                    ProductStock::create([
                        'product_id'   => $product->id,
                        'variation_id' => $variable_data->id,
                        'quantity'     => $request->quantity[$key]
                    ]);
                }                
            }
            
        } else {
           
            $variable=Variation::where('product_id', $product->id)->first();
            $stock = ProductStock::where('product_id', $product->id)->where('variation_id', $variable->id)->first();
            $variable->price = $request->sell_price;
            $variable->after_discount_price = $request->after_discount;
            $stock->quantity = $request->pro_quantity;
            
            $variable->save();
            $stock->save();
        }
    
        DB::commit();
        return response()->json(['status'=>true ,'msg'=>'Product Is Updated !!','url'=>route('admin.products.index')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
        } 
    

    }
    
    public function updatePriority(Request $request, $id)
        {
            try {
                $product = Product::findOrFail($id);
                $product->priority = $request->input('priority');
                $product->save();
        
                \Log::info('Product ID: ' . $id . ' Priority: ' . $request->input('priority'));
        
                return response()->json(['message' => 'Priority updated successfully']);
            } catch (\Exception $e) {
                \Log::error('Error updating priority: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
        
    public function cat_wise_product(Request $request) {
        $cat_id = $request->category_id;
        $items = Product::with('category')->where('category_id', $request->category_id)->orderBy('id', 'desc')->paginate(30);
      
        $categories = Category::where('parent_id', null)->get();

        // $html_view = view('admin.query_product', compact('cat_product','setting','orderProducts'))->render();
        
        return view('backend.products.index', compact('items','categories','cat_id'));
    }
        

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->can('product.delete'))
        {
            abort(403, 'unauthorized');
        }
    DB::beginTransaction();

        try {

        $product=Product::find($id);
        deleteImage('products', $product->image);
        deleteImage('products', $product->optional_image);

        if ($product->images()->count()) {
            
            foreach ($product->images as $key => $image) {
                deleteImage('products', $image->image);
            }
            $product->images()->delete();
        }

        foreach ($product->variations as $key => $dvariation) {
            $dvariation->delete();
        }
        $product->delete();
        DB::commit();
        return response()->json(['status'=>true ,'msg'=>'Product Is Deleted !!']);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
    }
        

    }

    public function deleteImage($id){

        $item=ProductImage::find($id);
        deleteImage('products', $item->image);
        $item->delete();
        return back();
    }

    public function fileUpload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
    
            // Move file
            $request->file('upload')->move(public_path('ck-images'), $fileName);
            $url = asset('ck-images/' . $fileName);
    
            // ✅ Detect CKEditor or Summernote
            if ($request->has('CKEditorFuncNum')) {
                // ========== CKEditor Response ==========
                $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                $msg = 'Image uploaded successfully';
                $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
                @header('Content-type: text/html; charset=utf-8');
                echo $response;
            } else {
                // ========== Summernote Response ==========
                return response()->json(['url' => $url]);
            }
        } else {
            // No file found
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    }

    
    public function recommendedUpdate (){
        
        $status=(request('is_recommended')==1)?1:null;
        DB::table('products')->whereIn('id', request('product_ids'))->update(['is_recommended'=>$status]);
        return response()->json(['status'=>true ,'msg'=>'Product Status Updated !!']);
    }
    
     public function showUpdate (){
        $status=(request('status')==1)?1:0;
        DB::table('products')->whereIn('id', request('product_ids'))->update(['status'=>$status]);
        return response()->json(['status'=>true ,'msg'=>'Product Status Updated !!']);
    }
    
}
