<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Combo;
use App\Models\Product;
use App\Models\Category;
use App\Models\Type;
use App\Models\Size;
use App\Models\Information;
use App\Models\LandingPage;
use App\Models\DeliveryCharge;
use App\Models\Variation;
use App\Facades\FacebookConversion;

class ProductController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {

            $q=request('q');
            $type_id=request('brand_id');
            $cat_id=request('cat_id');
            $size_id=request('size_id');
            $shorting=request('shorting');

            $query=Product::with('variation')
                        ->orderByRaw('IF(priority IS NULL, 1, 0), priority ASC')
                        ->Leftjoin('categories as c','c.id','products.category_id')
                        ->Leftjoin('variations as v','v.product_id','products.id')
                        ->select('products.id','products.name','products.type','products.purchase_prices','products.regular_price','products.sell_price','products.image','is_stock','products.discount','products.discount_type','products.category_id','products.after_discount','products.dicount_amount', 'products.stock_quantity');
            if(!empty($cat_id)){
                $query->whereIn('products.category_id',$cat_id);
            } 

            if(!empty($type_id)){
                $query->where('products.type_id',$type_id);
            } 

            if(!empty($size_id)){
                $query->whereIn('v.size_id',$size_id);
            } 
            
            if(!empty($q)){
                $query->where(function($row) use ($q){
                    $row->where('products.name','Like','%'.$q.'%');
                    $row->orwhere('products.description','Like','%'.$q.'%');
                });
            }

            if(!empty($shorting)){

                if ($shorting=='desc') {
                    $query->orderBy('products.id', 'desc');
                }else if ($shorting=='asc') {
                    $query->orderBy('products.id', 'asc');
                }else if ($shorting=='name') {
                    $query->orderBy('products.name', 'asc');
                }else if ($shorting=='price_low') {
                    $query->orderBy('products.sell_price', 'asc');
                }else if ($shorting=='price_high') {
                    $query->orderBy('products.sell_price', 'desc');
                }
                
            } 

            $items=$query->groupBy('products.id','products.name','products.is_free_shipping','products.type','products.purchase_prices','products.regular_price','products.sell_price','products.image','is_stock','products.discount','products.discount_type','products.category_id','products.after_discount','products.dicount_amount')
            ->where('status', 1)->paginate(30);

            return view('frontend.products.index_data', compact('items'))->render();
        }


        $types=Type::orderBy('name')->get();
        $cats=Category::whereNull('parent_id')->get();
        $sizes=Size::all();
        return view('frontend.products.index', compact('cats','sizes','types'));
    }
    
    public function comboProducts (){
        
        $items=Combo::with('product')->paginate(30);
        return view('frontend.products.combo', compact('items'));
        
    }

    public function show($id)
    {

        $recent_product = session()->get('recent_product', []);

        // dd($recent_product);
  
        if(!in_array($id,$recent_product)) {
           array_push($recent_product,$id);
           session()->put('recent_product', $recent_product);
        } 

        // $singleProduct=Product::with('sizes')->whereHas('reviews', function($q){
        //     $q->where('status', 1);
        // })->find($id);
        $singleProduct = Product::with(['sizes', 'reviews' => function($q){
            $q->where('status', 1);
        }])->find($id);


        // dd($singleProduct);
        
        try {
            $eventId = "SV_" . $singleProduct->id . "_" . now()->format('ymdhi');
            $finalPrice = (isset($singleProduct->after_discount) && $singleProduct->after_discount > 0) 
                ? $singleProduct->after_discount 
                : $singleProduct->sell_price;
            // Hashed user identifiers (if logged-in)
            $userData = [
                'em' => [hash('sha256', strtolower(trim(auth()->user()->email ?? '')))],
                'ph' => [hash('sha256', preg_replace('/\D/', '', auth()->user()->phone_number ?? ''))],
                'fn' => [hash('sha256', strtolower(trim(auth()->user()->name ?? '')))],
                'external_id' => [hash('sha256', auth()->user()->id ?? '')],
            ];
            
            FacebookConversion::sendViewContent([
                'product_id' => $singleProduct->id,
                'product_name' => $singleProduct->name,
                'value' => $finalPrice,
                'currency' => 'BDT',
                'content_type' => 'product',
                'content_category' => $singleProduct->category->name ?? 'Unknown Category',
                'event_time' => now()->timestamp,
                'action_source' => 'website',
                'user_data' => $userData
            ], $eventId);

            
        } catch (\Exception $e) {
            \Log::error('Facebook CAPI Error: ' . $e->getMessage());
        }
        
        $products=Product::where('id','!=',$id)
        ->where('category_id', $singleProduct->category_id)->orderByRaw('IF(priority IS NULL, 1, 0), priority ASC')
         ->where('status', 1)
        ->take(6)->get();
        $charges = DeliveryCharge::all();
        return view('frontend.products.show', compact('singleProduct','products', 'charges'));
    }
  
  	public function relativeProduct($id){

        $product = Product::with('sizes','sizes.stocks')->find($id);

        $products=Product::with('variation')
                
                ->select('products.id','products.name','products.is_free_shipping','products.type','products.purchase_price','products.regular_price','products.sell_price','products.image','is_stock','products.category_id','products.discount_type','products.discount','products.after_discount','products.dicount_amount')
                ->where('products.category_id', $product->category_id)
                ->orderByRaw('IF(priority IS NULL, 1, 0), priority ASC')
                ->whereNotIn('products.id', [$id])
                ->where('status', 1)
                ->take(12)
                ->get();
        $view=view('frontend.products.partials.relative_product', compact('products'))->render();

        return response()->json(['success'=>true,'html'=>$view]);

    }

    public function trendingProduct(){
      
       $info = Information::first();
       $newarrival_num = $info->newarrival_num; 
        $products=Product::with('variation')
                ->whereNull('products.discount_type')
                ->orderByRaw('IF(priority IS NULL, 1, 0), priority ASC')
                ->select('products.id','products.name','products.is_free_shipping','products.type','products.purchase_price','products.regular_price','products.sell_price','products.image','is_stock','products.category_id','products.discount_type','products.discount','products.after_discount','products.dicount_amount')
                ->where('status', 1)
                ->latest()
                ->take($newarrival_num)
                ->get();

        $view=view('frontend.products.partials.trending_product', compact('products'))->render();
        return response()->json(['success'=>true,'html'=>$view]);
    }

    public function hotdealProduct(){
      
      $info = Information::first();
        $discount_num = $info->discount_num;  
      
        $products=Product::with('variation')
                ->whereNotNull('products.discount_type')
                ->orderByRaw('IF(priority IS NULL, 1, 0), priority ASC')
                ->select('products.id','products.name','products.is_free_shipping','products.type','products.purchase_price','products.regular_price','products.sell_price','products.image','is_stock','products.category_id','products.discount_type','products.discount','products.after_discount','products.dicount_amount')
                ->where('status', 1)
                ->take($discount_num)->get();
        $view=view('frontend.products.partials.hotdeal_product', compact('products'))->render();
        return response()->json(['success'=>true,'html'=>$view]);
    }

    public function recommendedProduct(){
      
        $info = Information::first();
        $recom_num = $info->recommend_num;   

        $products=Product::with('variation')
                ->orderByRaw('IF(priority IS NULL, 1, 0), priority ASC')
                ->select('products.id','products.name','products.is_free_shipping','products.type','products.purchase_price','products.regular_price','products.sell_price','products.image','is_stock','products.category_id','products.discount_type','products.discount','products.after_discount','products.dicount_amount')
                ->where('status', 1)
                ->where('is_recommended', 1)
                ->take($recom_num)
                ->get();
        $view=view('frontend.products.partials.recommended_product', compact('products'))->render();
        return response()->json(['success'=>true,'html'=>$view]);
    }

    public function discountProduct(Request $request){

            if ($request->ajax()) {
                $items=Product::with('variation')
                    ->orderByRaw('IF(priority IS NULL, 1, 0), priority ASC')
                    ->whereNotNull('products.discount_type')
                    ->select('products.id','products.name','products.is_free_shipping','products.type','products.purchase_price','products.regular_price','products.sell_price','products.image','is_stock','products.category_id','products.discount_type','products.discount','products.after_discount','products.dicount_amount')
                    ->where('status', 1)
                    ->latest()
                    ->paginate(24);
                $view=view('frontend.products.partials.discount', compact('items'))->render();

                return response()->json(['success'=>true,'html'=>$view]);
            }
        return view('frontend.products.discount');
    }

    public function brands(){
        $items=Type::orderBy('name')->get();
        return view('frontend.brands', compact('items'));
    }
    
     public function landing_page($id)
    {
        
        $ln_pg = LandingPage::with('images')->find($id);
        $title = $ln_pg->title1;
        $charges=DeliveryCharge::whereNotNull('status')->get();
        return view('backend.landing_pages.land_page', compact('ln_pg','charges','title'));
    }
    
    public function landing_pages_two($id) {
        $ln_pg = LandingPage::with('images')->find($id);
        $title = $ln_pg->title1;
        $charges=DeliveryCharge::whereNotNull('status')->get();
        return view('backend.landing_pages.land_page_two', compact('ln_pg','charges','title'));
    }
  	
  	public function subCategories($slug){
    
        $cat=Category::where('url',$slug)->first();
        $query=Category::whereNotNull('parent_id');
      				if($cat){ 
                    	$query->where('parent_id', $cat->id);
                    }
        $subs=$query->get();

        return view('frontend.sub_categories', compact('subs'));
  	}
  	
  	public function subCategories1($slug){
        $cat=Category::where('url',$slug)->first();
        $cat_id = $cat['id'];  
      
       $items =  Product::with('variation')->orderByRaw('IF(priority IS NULL, 1, 0), priority ASC')->where('category_id', $cat_id)->where('status', 1)->paginate(30);    
       $types=Type::orderBy('name')->get();
       $cats=Category::whereNull('parent_id')->get();
       $sizes=Size::all();
       
      
       return view('frontend.products.another_index',compact('items','types','cats','sizes','cat'));
  	}

    public function subsubCategories($slug){
      
       $s_cat=Category::where('url',$slug)->first();
       $scat_id = $s_cat['id']; 
      
       $items =  Product::with('variation')->orderByRaw('IF(priority IS NULL, 1, 0), priority ASC')->where('sub_category_id', $scat_id)->where('status', 1)->paginate(30);    
       $types=Type::orderBy('name')->get();
       $cats=Category::whereNull('parent_id')->get();
       $sizes=Size::all();
      
       return view('frontend.products.another_sub_index',compact('items','types','cats','sizes','s_cat'));
  	}
    
    public function categories(){
        $category_id=request('category_id');
        $cats=Category::whereNull('parent_id')->get();
        $query=Category::whereNotNull('parent_id');
                if(!empty($category_id)){
                    $query->where('parent_id',$category_id);
                }
        $subs=$query->get();

        return view('frontend.categories', compact('cats','subs'));
    }
    
    public function free_shipping() {
        
        $items=Product::with('variation')
                ->where('is_free_shipping', 1)
                ->orderByRaw('IF(priority IS NULL, 1, 0), priority ASC')
                ->select('products.id','products.name','products.is_free_shipping','products.type','products.purchase_price','products.regular_price','products.sell_price','products.image','is_stock','products.category_id','products.discount_type','products.discount','products.after_discount','products.dicount_amount')
                ->latest()
                ->get();

        return view('frontend.products.free_shipping_products', compact('items'));
        
    }
    
    // Get The Price Of Variation Product
    
    public function get_variation_price(Request $request)
    {
        $data = Product::find($request->product_id);
        $discount_amount = (int)$data->dicount_amount;
        $discount_type = $data->discount_type;
        
        return response()->json([
            'success' => true,
            'discount_amount' => $discount_amount,
            'discount_type' => $discount_type
        ]);
    }
}
