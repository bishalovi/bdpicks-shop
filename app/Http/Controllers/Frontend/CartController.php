<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Variation;
use App\Facades\FacebookConversion;
use App\Utils\Util;

class CartController extends Controller
{

    public function __construct(Util $util){

        $this->util=$util;
    }


    public function index(){
        $cart = session()->get('cart', []);

            if (request()->ajax()) {
                
                $segm='home';
                if(request()->segment){
                    $segm=request()->segment;
                }                
                
                $view=view('frontend.partials.cart_sidebar', compact('cart','segm'))->render();

                return response()->json(['success'=>true,'html'=>$view]);
            }
        return view('frontend.cart.index', compact('cart'));
    }
    
    public function storeCart(Request $request) {

        $data=$request->validate([
            'product_id' => 'required|numeric',
            'variation_id' => 'required|numeric',
        ]);
        
        $segm='home';
        if(request()->segment){
            $segm=request()->segment;
        }
        $product_id=$request->product_id;
        $variation_id=$request->variation_id;
        $quantity= $request->quantity;
        $product = Product::findOrFail($product_id);
        // dd($product);
        $finalPrice = (isset($product->after_discount) && $product->after_discount > 0) 
                ? $product->after_discount 
                : $product->sell_price;
        $variation=Variation::find($variation_id);
        $data=getProductInfo($product);
        $cart = session()->get('cart', []);
        
        
        // if($request->is_stock != 0) {
        // 	$stock=$this->util->checkProductStock($product_id, $variation_id);
        // if($stock < $quantity){
        //     return response()->json(['success'=>false,'msg'=>' Stock Not Available!']);
        // }	
        // }
            if($request->quantity != 0) {
                      if($product->stock_quantity < $quantity){
                          return response()->json(['success'=>false,'msg'=>' Stock Not Available!']);              
                      }          
                }else{
                    return response()->json(['success' => false, 'msg' => 'Please Select Minimum 1 Quantity']);
                }
        
        if(isset($cart[$variation_id])) {
          
          	$new_stock=$cart[$variation_id]['quantity']+1;       
          
            // if($request->is_stock != 0) {          
            //       $stock=$this->util->checkProductStock($product_id, $variation_id);
            //       if($stock < $new_stock){
            //           return response()->json(['success'=>false,'msg'=>' Stock Not Available!']);              
            //       }          
            //   }   
                //   $stock=$this->util->checkProductStock($product_id, $variation_id);
                if($request->is_stock != 0) {
                      if($product->stock_quantity < $new_stock){
                          return response()->json(['success'=>false,'msg'=>' Stock Not Available!']);              
                      }          
                }
          	$cart[$variation_id]['quantity']=$new_stock;
          	$cart[$variation_id]['variation_id']=$variation_id;
        } else {
            
            $cart[$variation_id] = [
                "name" => $product->name,
                "size" => $variation->size?$variation->size->title:'',
                "color" => $variation->color?$variation->color->name:'',
                "quantity" => $quantity,
                "price" => $finalPrice,
                "discount" => $product->after_discount ? $product->sell_price - $product->after_discount : 0,
                "variation_id" => $variation_id,
                "product_id" => $product_id,
                "category_name" => $product->category->name??'',
                "purchase_price" => $product->purchase_prices,
                "image" => $product->image,
                "is_stock" => $product->is_stock,
                "is_free_shipping" => $product->is_free_shipping
            ];
        }
        session()->put('cart', $cart);
        
        try {
            $eventId = "ATC_" . now()->format('Ymdhi');
            
            FacebookConversion::sendAddToCart([
                'content_ids' => [$product->id],
                'value' => $finalPrice * $quantity,
                'currency' => 'BDT',
                'contents' => [
                    [
                        'id' => $product->id,
                        'quantity' => $quantity,
                        'item_price' => $finalPrice
                    ]
                ]
            ], $eventId);
            
        } catch (\Exception $e) {
            \Log::error('Facebook CAPI AddToCart Error: ' . $e->getMessage());
        }
        
        $view=view('frontend.partials.cart_sidebar',compact('cart','segm'))->render();
        $total_item=getTotalCart();
        $total_amount=getTotalAmount();
        if($request->action_type == 'cart'){
            $url = '';
        }else{
            $url=route('front.checkouts.index');
        }
        return response()->json(['success'=>true,'msg'=>'Product added to cart successfully!','html'=>$view,'item'=>$total_item, 'amount' => $total_amount, 'url' => $url]);
    }
    
    public function store(Request $request){
       
        $data=$request->validate([
            'product_id' => 'required|numeric',
            'variation_id' => 'required|numeric',
        ]);
      
        $segm='home';
        if(request()->segment){
            $segm=request()->segment;
        }
        $product_id=$request->product_id;
        $variation_id=$request->variation_id;
        if($request->quantity == null || $request->quantity == '')
        {
            $quantity= "1";
        } else {
            $quantity= $request->quantity;
        }
        $product = Product::findOrFail($product_id);
        $finalPrice = (isset($product->after_discount) && $product->after_discount > 0) 
                ? $product->after_discount 
                : $product->sell_price;
        $variation=Variation::find($variation_id);
        $data=getProductInfo($product);
        $cart = session()->get('cart', []);
        if($quantity != 0) {
              if($product->stock_quantity < $quantity){
                  return response()->json(['success'=>false,'msg'=>' Stock Not Available!']);              
              }          
        }else{
            return response()->json(['success' => false, 'msg' => 'Please Select Minimum 1 Quantity']);
        }
        // if($request->is_stock != 0) {
        // 	$stock=$this->util->checkProductStock($product_id, $variation_id);
        // if($stock < $quantity){
        //     return response()->json(['success'=>false,'msg'=>' Stock Not Available!']);
        // }
        // } 
      

  
        if(isset($cart[$variation_id])) {
          
          	$new_stock=$cart[$variation_id]['quantity']+1;       
          
            // if($request->is_stock != 0) {          
            //       $stock=$this->util->checkProductStock($product_id, $variation_id);
            //       if($stock < $new_stock){
            //           return response()->json(['success'=>false,'msg'=>' Stock Not Available!']);              
            //       }          
            //   }        
          
          	$cart[$variation_id]['quantity']=$new_stock;
          	$cart[$variation_id]['variation_id']=$variation_id;
        } else {
            $cart[$variation_id] = [
                "name" => $product->name,
                "size" => $variation->size?$variation->size->title:'',
                "color" => $variation->color?$variation->color->name:'',
                "quantity" => $quantity,
                "purchase_price" => $product->purchase_prices,
                "price" => $finalPrice,
                "discount" => $product->after_discount ? $product->sell_price- $product->after_discount : 0,
                "variation_id" => $variation_id,
                "product_id" => $product_id,
                "category_name" => $product->category->name??'',
                "image" => $product->image,
                "is_stock" => $product->is_stock,
                "is_free_shipping" => $product->is_free_shipping
            ];
        }
        session()->put('cart', $cart);
        
        // try {
        //     $eventId = "ATC_" . now()->format('Ymdhi');
            
        //     FacebookConversion::sendAddToCart([
        //         'content_ids' => [$product->id],
        //         'value' =>  $finalPrice * $quantity,
        //         'currency' => 'BDT',
        //         'contents' => [
        //             [
        //                 'id' => $product->id,
        //                 'quantity' => $quantity,
        //                 'item_price' => $finalPrice
        //             ]
        //         ]
        //     ], $eventId);
            
        // } catch (\Exception $e) {
        //     \Log::error('Facebook CAPI AddToCart Error: ' . $e->getMessage());
        // }

        $view=view('frontend.partials.cart_sidebar',compact('cart','segm'))->render();
        $total_item=getTotalCart();
        $url=route('front.checkouts.index');
        return response()->json(['success'=>true,'msg'=>'Product added to cart successfully!','html'=>$view,'item'=>$total_item,'url'=>$url]);

    }

    public function edit(Request $request,$id)
    {
        if($id){
            $qty=$request->quantity;
          	
          	$cart = session()->get('cart');
          	
          	$totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
          	
          	$segm='home';
          	if($request->segment){
            	$segm=$request->segment;
          	}
          
          	if($qty==0){
            	if(isset($cart[$id])) {
                    unset($cart[$id]);
                }
          	}else{
               	$product_id=$cart[$id]["product_id"];
               	$is_stock=$cart[$id]["is_stock"];
              	$stock=$this->util->checkProductStock($product_id, $id);
              	if($is_stock == 1 && $stock <$qty){
                  	return response()->json(['success'=>false,'msg'=>' Stock Not Available!']);
              	}
              	
              	$cart[$id]["quantity"] = $qty;
              
            }
           
            session()->put('cart', $cart);
            $view=view('frontend.partials.cart_sidebar',compact('cart','segm'))->render();
            $view2=view('frontend.cart.details')->render();
            $view3=view('frontend.cart.other_details',compact('totalPrice'))->render();
            return response()->json(['success'=>true,'msg'=>'Update cart successfully!','html'=>$view, 'html2'=>$view2,'html3'=>$view3,'segment'=>$segm]);
        }else{
            return response()->json(['success'=>false,'msg'=>' Something Went Wrong !']);
        }
    }


    public function destroy($id)
    {
        if($id) {
            $segm='home';
            if(request()->segment){
                $segm=request()->segment;
            }
            
            $cart = session()->get('cart');
            if(isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }

            $view=view('frontend.partials.cart_sidebar',compact('cart','segm'))->render();
            $view2=view('frontend.cart.details')->render();
            $view3=view('frontend.cart.other_details')->render();
            $total_item=getTotalCart();
            $url = route('front.home');;
            return response()->json([
              'success'=>true,
              'msg'=>'Product removed successfully !',
              'html'=>$view,
              'html2'=>$view2,
              'html3'=>$view3,
              'item'=>$total_item,
              'segment'=>$segm,
              'url'=>$url,
            ]);
        }else{
            return response()->json(['success'=>false,'msg'=>' Something Went Wrong !']);
        }
    }
  
  public function clearAll()
  {
   	session()->put('cart', []);
    return redirect()->route('front.home');
  }


}
