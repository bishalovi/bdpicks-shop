<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Information;
use App\Models\OrderDetails;
use App\Models\DeliveryCharge;
use App\Models\Variation;
use App\Models\Courier;
use App\Models\User;
use App\Models\BlockedIp;
use Auth;
use App\Utils\Util;
use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
  	private $redx_api_base_url = '';
  	private $redx_api_access_token = '';  	
  	private $pathao_api_base_url = '';
  	private $pathao_api_access_token = '';
    private $pathao_store_id = '';
    private $steadfast_api_base_url = '';
  	private $steadfast_api_key = '';
    private $steadfast_secret_key = '';
    private $util = '';
    public function __construct(Util $util)
    {
		$info = Information::first();
		
      	$this->redx_api_base_url = $info->redx_api_base_url.'/';
      	$this->redx_api_access_token = 'Bearer '.$info->redx_api_access_token;   
      	
      	$this->pathao_api_base_url = $info->pathao_api_base_url;
      	$this->pathao_api_access_token = $info->pathao_api_access_token;
      	$this->pathao_store_id = $info->pathao_store_id;
      	
        $this->steadfast_api_base_url = $info->steadfast_api_base_url;
      	$this->steadfast_api_key = $info->steadfast_api_key;
      	$this->steadfast_secret_key = $info->steadfast_secret_key;
      	
        $this->util = $util;
    }

    public function orderExport()
    {
        return Excel::download(new OrderExport, 'orders.xlsx');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
      	if(!auth()->user()->can('order.view'))
        {
            abort(403, 'unauthorized');
        }
        $status=$request->status;
        $q=$request->q;
		$query=Order::whereHas('details.product', function($q){
          			$q->whereNotNull('name');
        		});
                if(!empty($q)){
                    $query->where(function($row) use ($q){
                        $row->where('invoice_no','Like','%'.$q.'%');
                    });
                }
                
                if(!empty($status)){         
                    $query->where('status','Like','%'.$status.'%');                    
                }
                
        if(Auth::user()->hasRole('worker'))
        {
            $query->where('assign_user_id', Auth::id());
        }
        
        $yes_count = Order::whereNotNull('courier_tracking_id')->where('status', 'courier')->count();
        $no_count = Order::whereNull('courier_tracking_id')->where('status', 'courier')->count();
        $items=$query->latest()->paginate(30);
        
        $items->map(function($orderItem){
            $orderItem->courier_tracking_link = '';
            $url = '';
            if($orderItem->courier_id == 1 && $orderItem->courier_tracking_id) 
            {
                $url = "https://redx.com.bd/track-global-parcel/?trackingId={$orderItem->courier_tracking_id}";
            }
            
            else if($orderItem->courier_id == 3 && $orderItem->courier_tracking_code) 
            {
                $url = "https://steadfast.com.bd/t/{$orderItem->courier_tracking_code}";
            }
            
            if($url)
            {
               $orderItem->courier_tracking_link = "<a href='{$url}' target='_blank'>Tracking Link</a>";
            }
            
            return $orderItem;
        });
        
        return view('backend.orders.index', compact('items', 'status','q', 'yes_count', 'no_count'));
    }
  
  	
  	public function IPBlock(){
        return redirect('backend.reports.ipblock.ipblock'); 
    }

    public function IPBlockSubmit(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'ip_address' => 'required|ip',
            'reason' => 'required|string',
        ]);

        $ipAddress = $request->input('ip_address');
        $reason = $request->input('reason');

        // Check if the IP address is already blocked
        if (BlockedIp::where('ip_address', $ipAddress)->exists()) {
            return redirect()->back()->with('error', 'IP address is already blocked.');
        }

        // Block the IP address
        BlockedIp::create([
            'ip_address' => $ipAddress,
            'reason' => $reason,
        ]);

        return redirect()->back()->with('success', 'IP address blocked successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status=getOrderStatus();
        $charges=DeliveryCharge::all();
        $couriers=Courier::all();
        $areas = $this->getRedxAreaList();
        //$stores = $this->getPathaoStoreList();
      	$cities = $this->getPathaoCityList();
        return view('backend.orders.create', compact('status','charges','couriers', 'areas', 'cities'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        if(!auth()->user()->can('order.create'))
        {
            abort(403, 'unauthorized');
        }   
       
        $data=$request->validate([
             'note'=> '',
             'first_name'=> 'required',
             'last_name'=> '',
             'mobile'=> 'required',
             'zip_code'=> '',
             'area_id'=> '',
             'area_name'=> '',
             'city'=> '',
             'state'=> '',
             'store_id'=> '',
             'weight'=> '',
             'shipping_address'=> 'min:10',
             'courier_id'=> '',
             'date'=> 'required',
             'status'=> 'required',
             'discount'=> '',
             'shipping_charge'=> 'required',
             'delivery_charge_id'=> 'required',
             'final_amount'=> 'required|numeric',
        ]);

        $data['amount']=$data['final_amount']+$data['shipping_charge']+$data['discount'];
        $data['user_id']=auth()->user()->id;
        //$data['invoice_no']=time();
        $data['invoice_no']=rand(111111,999999);
        
         DB::beginTransaction();

        try {         
          
         $result = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();
         
         
         
        //  if($result->role_id == 8) {
        //  	$data['assign_user_id'] = auth()->user()->id;
        //  } else {
        //  	$user = DB::table('model_has_roles')->where('role_id', 8)->inRandomOrder()->first();
        //     if($user)
        //     {
        //          $data['assign_user_id'] = $user->model_id;
        //     }
        //     else  $data['assign_user_id'] = 1;
        //  }          
        
            $assign_user_id=1;
            $users=User::whereHas('roles', function($query){
                              $query->where('roles.name','worker');
                            })->where('status',1)
                            ->select('id')
                            ->pluck('id')->toArray();
                           
            $ordering=count($users)-1;
            if(count($users)==1){
                $assign_user_id=$users[0];
                $data['assign_user_id'] = $assign_user_id;
            }else if($ordering>0){
                $order=Order::latest()->take($ordering)->get()->pluck('assign_user_id')->toArray();
               
                $output = array_merge(array_diff($order, $users), array_diff($users, $order));
    
                if(!empty($output)){
                    $assign_user_id=$output[0];
                    $data['assign_user_id'] = $assign_user_id;
                }
    
                else {
                    $data['assign_user_id'] = $assign_user_id;
                }
    
            } 
           
            $order=Order::create($data);

            // update purchase line and product stock
            if (isset($request->product_id)) {
                $data=[];
                foreach ($request->product_id as $key => $product_id) {
                    //product stock increase/decrease
                    
                    $qty=$request->quantity[$key];
                    $variation_id=$request->variation_id[$key];
                    $data[]=[
                        'product_id'=>$product_id,
                        'quantity'=>$qty,
                        'variation_id'=>$variation_id,
                        'unit_price'=>$request->unit_price[$key],
                        'discount'=>$request->unit_discount[$key],
                    ];
                    $this->util->decreaseProductStock($product_id,$variation_id, $qty);                    
                    
                
                }
                if (!empty($data)) {
                    $order->details()->createMany($data);
                }
                
            }

            DB::commit();
            return response()->json(['status'=>true ,'msg'=>'Order Is  Updated !!','url'=> route('admin.orders.index')]);
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
      	if(!auth()->user()->can('order.view'))
        {
            abort(403, 'unauthorized');
        }
        
        $item=Order::with('details','details.product','payments', 'delivery_charge')->find($id);
        if($item->status == 'processing')
        {
            $item->status = 'courier';
            $item->save();
        }
        
        $info=Information::first();
        return view('backend.orders.show_prnt', compact('item', 'info'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){

        $item=Order::with('details','details.product','payments')->find($id);
        $orderbyNumber = Order::with('details','details.product','assign')->where('mobile', $item->mobile)->get();
        
        $status=getOrderStatus();
        $charges=DeliveryCharge::all();
        $couriers=Courier::all();
        $areas = $this->getRedxAreaList();
      	//$stores = $this->getPathaoStoreList();
      	$cities = $this->getPathaoCityList();
        return view('backend.orders.edit', compact('item','status','charges','couriers', 'areas', 'cities','orderbyNumber'));

    }
    
    public function orderList()
    {
      	if(!auth()->user()->can('order.view'))
        {
            abort(403, 'unauthorized');
        }
        
        $items=Order::with('details','details.product','payments')->whereIn('id', request('order_ids'))->get();
        
        
        $status_array = [];
        foreach($items as $item)
        {
            $status_array[] = $item->status;
        }
        
        if(in_array('pending', $status_array))
        {
            return response()->json(['status'=>false,'msg'=>'Pending Order Found !!']);
        } else {
            foreach($items as $item)
            {
                if($item->status == 'processing')
                {
                    $item->status = 'courier';
                    $item->save();
                }
            }
        }
        
        $info=Information::first();
      
        $view = view('backend.orders.show', compact('items','info'))->render();        
        return response()->json(['status'=> true, 'items'=> $items, 'info'=> $info,'view'=>$view]);
    }

    public function getOrderProduct(Request $request)
    {

        $data = Variation::join('products' ,'products.id','variations.product_id')
                    ->join('sizes' ,'sizes.id','variations.size_id')
                    ->join('colors' ,'colors.id','variations.color_id')
                    // ->join('product_stocks' ,'product_stocks.variation_id','variations.id')
                    ->select("variations.id",

                        DB::raw("TRIM(CONCAT(products.name,' (',sizes.title,'),(',colors.name,')')) AS value")
                        )
                    // ->where('product_stocks.quantity','>',0)
                    ->where('products.name', 'LIKE', '%'. $request->get('search'). '%')
                    ->get();

        
        return response()->json($data);

    }
    
    public function getOrderProduct2(Request $request){

        $data = Variation::join('products' ,'products.id','variations.product_id')
                    ->join('sizes' ,'sizes.id','variations.size_id')
                    ->join('colors' ,'colors.id','variations.color_id')
                    ->select("variations.id",

                        DB::raw("TRIM(CONCAT(products.name,' (',sizes.title,'),(',colors.name,')')) AS value")
                        )
                    ->where('products.name', 'LIKE', '%'. $request->get('search'). '%')
                    ->get();

        return response()->json($data);

    }

    public function orderProductEntry(Request $request){ 

        $id=$request->id;
        $variation=Variation::with('product')->find($id);
        $data=getProductInfo($variation->product);

        if ($variation) { 
            $html='<tr><td><img src="/products/'.$variation->product->image.'" height="50" width="50"/></td>
            		<td>'.$variation->product->name.'</td>
                    <td>'.$variation->size->title.'</td>
                    <td>'.$variation->color->name.'</td>
                    <td>
                        <input class="form-control quantity" name="quantity[]" type="number" value="1" required min="1" data-qty="'.$variation->stocks->sum('quantity').'"/>
                        <input type="hidden" class="form-control" name="variation_id[]" type="number" value="'.$variation->id.'" required/>
                        <input type="hidden" class="form-control" name="product_id[]" type="number" value="'.$variation->product_id.'" required/>
                    </td>
                    <td><input class="form-control unit_price" name="unit_price[]" type="number" value="'.$data['price'].'" required/></td>
                    <td><input class="form-control unit_discount" name="unit_discount[]" type="number" value="'.$data['discount_amount'].'" required/></td>
                    <td class="row_total">'.$data['price'].'</td>
                    <td>
                        <a class="remove btn btn-sm btn-danger"> <i class="mdi mdi-delete"></i> </a>
                    </td>
                    </tr> ';

            return response()->json(['success'=>true,'html'=>$html]);
        }else{
            return response()->json(['success'=>false,'msg'=>'Product Note Found !!']);
        }
    }
    
    public function landingProductEntry(Request $request){

        $id=$request->id;
        $variation=Variation::with('product')->find($id);
        $pr_id = $variation->product->id;
        $data=getProductInfo($variation->product);

        if ($variation) {
            $html = '<table class="table table-centered table-nowrap mb-0" id="product_table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>Size</th>
                                        <th>Color</th>
                                        <th style="width: 120px;">Quantity</th>
                                        <th style="width: 150px;">Sell Price</th>
                                        <th style="width: 150px;">Discount</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="data">
                                   <tr><td><img src="/products/'.$variation->product->image.'" height="50" width="50"/></td>
            		<td>'.$variation->product->name.'</td>
                    <td>'.$variation->size->title.'</td>
                    <td>'.$variation->color->name.'</td>
                    <td>
                        <input class="form-control quantity" name="quantity[]" type="number" value="1" required min="1" data-qty="'.$variation->stocks->sum('quantity').'"/>
                        <input type="hidden" class="form-control" name="variation_id[]" type="number" value="'.$variation->id.'" required/>
                        
                    </td>
                    <td><input class="form-control unit_price" name="unit_price[]" type="number" value="'.$data['price'].'" required/></td>
                    <td><input class="form-control unit_discount" name="unit_discount[]" type="number" value="'.$data['discount_amount'].'" required/></td>
                    <td class="row_total">'.$data['price'].'</td>
                    <td>
                        <a class="remove btn btn-sm btn-danger"> <i class="mdi mdi-delete"></i> </a>
                    </td>
                    </tr>
                                </tbody>
                            </table>';




            $htmldfhgdf='<tr><td><img src="/products/'.$variation->product->image.'" height="50" width="50"/></td>
            		<td>'.$variation->product->name.'</td>
                    <td>'.$variation->size->title.'</td>
                    <td>'.$variation->color->name.'</td>
                    <td>
                        <input class="form-control quantity" name="quantity[]" type="number" value="1" required min="1" data-qty="'.$variation->stocks->sum('quantity').'"/>
                        <input type="hidden" class="form-control" name="variation_id[]" type="number" value="'.$variation->id.'" required/>
                        <input type="hidden" class="form-control" name="product_id[]" type="number" value="'.$variation->product_id.'" required/>
                    </td>
                    <td><input class="form-control unit_price" name="unit_price[]" type="number" value="'.$data['price'].'" required/></td>
                    <td><input class="form-control unit_discount" name="unit_discount[]" type="number" value="'.$data['discount_amount'].'" required/></td>
                    <td class="row_total">'.$data['price'].'</td>
                    <td>
                        <a class="remove btn btn-sm btn-danger"> <i class="mdi mdi-delete"></i> </a>
                    </td>
                    </tr> ';

            return response()->json(['success'=>true,'html'=>$html,'pr_id' => $pr_id]);
        }else{
            return response()->json(['success'=>false,'msg'=>'Product Note Found !!']);
        }
    }
  
    public function status_wise_order(Request $request){        
      	$redx_status = $request->redx_status;
      	$courier_type = $request->courier_type;
        $query = Order::whereHas('details.product', function($q){
          			$q->whereNotNull('name');
        		});
      	if(!empty($redx_status))
        {
          if($redx_status == 'yes')
          {
            $query->whereNotNull('courier_tracking_id');
          }
          else if($redx_status == 'no')
          {
          	$query->whereNull('courier_tracking_id');
          }
        }      	
      
      	if(!empty($courier_type))
        {
            if($courier_type == 'none')
            {
              $query->whereNull('courier_id');
            }
            else if($courier_type == 'redx')
            {
              $query->where('courier_id', 1);
            }            
          
          	else if($courier_type == 'pathao')
            {
              $query->where('courier_id', 2);
            }
         }     
      
        if(Auth::user()->hasRole('worker'))
        {
            $received_order = $query->where('status', $request->statusValue)
               ->where('assign_user_id', Auth::id())
               ->latest()->get();
        }
      
        else if(Auth::user()->hasRole('admin'))
        {
            $received_order = $query->where('status', $request->statusValue)             
               ->latest()->get();
        } else {        
        	
        }      
      
        $view = view('backend.orders.received_order', compact('received_order'))->render();
        return response()->json(['success'=>true, 'view'=>$view]);
    }
      
    public function searchOrder(Request $request){
    	$searchStr = $request->searchValue;        
        $query = Order::query();
        
        if(!empty($searchStr))
        {
           $query->where(function($row) use ($searchStr){
           		$row->where('invoice_no', 'like','%'.$searchStr.'%')
                    ->orWhere('first_name','like','%'.$searchStr.'%')
                    ->orWhere('last_name','like','%'.$searchStr.'%')
                    ->orWhere('mobile','like','%'.$searchStr.'%')
             ->orWhere('shipping_address','like','%'.$searchStr.'%');                    
           });
          
           $received_order = $query->get();           
           $view = view('backend.orders.received_order', compact('received_order'))->render();          
           
          return response()->json(['success'=>true, 'view'=>$view]);
        }
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
        if(!auth()->user()->can('order.edit'))        
        {
            abort('403', 'Unauthorized');
        }

        $order=Order::find($id);
        $data=$request->validate([
             'note'=> '',
             'first_name'=> '',
             'last_name'=> '',
             'zip_code'=> '',
            //  'area_id'=> '',
             'area_name'=> '',
             'city'=> '',
             'state'=> '',
             'store_id'=> '',
             'weight'=> '',
             'mobile'=> '',
             'shipping_address'=> 'min:10',
             'courier_id'=> '',
             'courier_tracking_id'=> '',
             'date'=> 'required',
             'status'=> 'required',
             'discount'=> '',
             'shipping_charge'=> '',
             'delivery_charge_id'=> '',
             'final_amount'=> 'required|numeric',
        ]);
       
       if($request->redx_area_id != null)
       {
           
           $data['area_id'] = $request->redx_area_id;
       } else if ($request->pathao_area_id != null) 
       {
          
           $data['area_id'] = $request->pathao_area_id;
       } else {
            $data['area_id'] = null;
       }
     
       

        if(isset($request->courier_id) && $order->status === 'pending')
        {
            $data['status'] = 'processing';
        }
        
        $data['amount']=$data['final_amount']+$data['shipping_charge']+$data['discount'];

        DB::beginTransaction();

        try {

            $order->update($data);

            // delete purchase line and decrease product stock
            if (isset($request->order_line_id)) {
                $delete_line=OrderDetails::where('order_id', $id)
                                ->whereNotIn('id', $request->order_line_id)
                                ->get();
               
                if ($delete_line->count()) {
                    foreach ($delete_line as $key => $line) {
                        $this->util->increaseProductStock($line->product_id, $line->variation_id, $line->quantity);
                        $line->delete();
                    }
                }
            }
            else{
              
                foreach ($order->details as $key => $line) {
                    $this->util->increaseProductStock($line->product_id, $line->variation_id, $line->quantity);
                    $line->delete();
                }
            }
            // update purchase line and product stock
            if (isset($request->product_id)) {
                $data=[];
                foreach ($request->product_id as $key => $product_id) {
                    //product stock increase/decrease
                    if (isset($request->order_line_id[$key])) {
                        
                        $qty=$request->quantity[$key];
                        $line_id=$request->order_line_id[$key];
                        $line=OrderDetails::find($line_id);
                        $this->util->updateProductStock($line->product_id, $line->variation_id,$qty, $line->quantity);
                        $line->quantity=$qty;
                        $line->unit_price=$request->unit_price[$key];
                        $line->save();

                    }
                    //product stock increase
                    else{
                       
                        $qty=$request->quantity[$key];
                        $variation_id=$request->variation_id[$key];
                        $data[]=[
                            'product_id'=>$product_id,
                            'quantity'=>$qty,
                            'variation_id'=>$variation_id,
                            'unit_price'=>$request->unit_price[$key],
                            'discount'=>$request->unit_discount[$key],
                        ];
                        $this->util->decreaseProductStock($product_id,$variation_id, $qty);                    
                    }
                    
                }
                if (!empty($data)) {
                    $order->details()->createMany($data);
                }
                
            }

            DB::commit();
            return response()->json(['status'=>true ,'msg'=>'Order Is  Updated !!','url'=> route('admin.orders.index')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
        }
    }
  

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

    try {
        if(!auth()->user()->can('order.delete'))
        {
            abort(403, 'unauthorized');
        }

        $item=Order::find($id);

        if($item->details()->count()){
            foreach ($item->details as $key => $line) {
                $this->util->increaseProductStock($line->product_id,$line->variation_id,$line->quantity);
            }
            $item->details()->delete();
        }

        if($item->payments()->count()){
            $item->payments()->delete();
        }

        $item->delete();

            DB::commit();
            return response()->json(['status'=>true ,'msg'=>'Order Is Deleted!!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
        }
    }
    
    public function trashed_orders(Request $request)
    {
        
        $status=$request->status;
        $q=$request->q;
        // $query=Order::onlyTrashed()->whereHas('details.product', function($q){
        //     $q->whereNotNull('name');
        // });
		$query=Order::onlyTrashed()->with('details','details.product','payments');

                if(!empty($q)){
                    $query->where(function($row) use ($q){
                        $row->where('invoice_no','Like','%'.$q.'%');
                    });
                }

                if(!empty($status)){
                    $query->where('status','Like','%'.$status.'%');
                }

        if(Auth::user()->hasRole('worker'))
        {
            $query->where('assign_user_id', Auth::id());
        }
        
        $yes_count = Order::whereNotNull('courier_tracking_id')->where('status', 'courier')->count();
        $no_count = Order::whereNull('courier_tracking_id')->where('status', 'courier')->count();
        $trashed_orders=$query->latest()->paginate(30);

        return view('backend.orders.trashed_orders', compact('trashed_orders', 'status','q', 'yes_count', 'no_count'));
    }

    public function restore_order(Request $request)
    {
        $restore_order = Order::where('id', $request->id)->withTrashed()->first();
        $restore_order_details = OrderDetails::where('order_id', $restore_order->id)->withTrashed()->get();
        
        foreach($restore_order_details as $restore_details)
        {
            $this->util->increaseProductStock($restore_details->product_id,$restore_details->variation_id,$restore_details->quantity);
            $restore_details->restore();
        }

        $restore_order->restore();
        return response()->json(['success'=>true ,'msg'=>'Order Is Restored !!']);
    }
    
    public function forceDel($id) {
        
        try {
            $del_orders = Order::where('id', $id)->withTrashed()->first();
            
            $del_order_details = OrderDetails::where('order_id', $id)->withTrashed()->get();

        foreach($del_order_details as $del_details)
        {
            $this->util->decreaseProductStock($del_details->product_id,$del_details->variation_id,$del_details->quantity);
            $del_details->forceDelete();
        }

        $del_orders->forceDelete();
        DB::commit();
        return response()->json(['status'=>true ,'msg'=>'Order Is Deleted Permanently!!']);
        
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
        }
        
    } 
    
    public function category_wise_order(Request $request)
    {
        
        $subCats = Category::where('parent_id', $request->category_id)->get();
        $products_data = Product::where('category_id', $request->category_id)->get();
        $products_id = [];
        foreach($products_data as $data_p)
        {
            $products_id[] = $data_p->id;
        }
        $all_order_id = [];
        $all_details = [];
        foreach($products_id as $pr_id)
        {
            $all_details = OrderDetails::where('product_id', $pr_id)->first();
            if(!isset($all_details->order_id) || $all_details->order_id == null)
            {
                
            } else {
                $all_order_id[] = $all_details->order_id;
            }
        }
        $items = [];
        foreach($all_order_id as $order_id)
        {
            $items_order = Order::with('details','details.product','payments')->where('id', $order_id)->first();
            if($items_order == null)
            {
                
            } else {
                $items[] = $items_order;
            }
        }
        $category_id = $request->category_id;
        $sub_category_id = $request->sub_category_id;
        $all_category = Category::where('parent_id', null)->get();
        $yes_count = Order::whereNotNull('courier_tracking_id')->where('status', 'courier')->count();
        $no_count = Order::whereNull('courier_tracking_id')->where('status', 'courier')->count();
        return view('backend.orders.cat_wise_order', compact('items','yes_count', 'no_count','all_category','subCats','category_id','sub_category_id'));
    }

    public function orderStatus($id){

        $item=Order::find($id);
        $status=getOrderStatus();
        return view('backend.orders.status_update', compact('item','status'));
    }

    public function orderStatusUPdate($id){
        $item=Order::with('user')->find($id);
        $items=Order::with('details','details.product','user')->find($id);
        foreach($item->details as $line) {
            $old_status = $item->status;
            $change_status = request('status');
            if(($old_status == 'cancell' || $old_status == 'return') && ($change_status == 'pending' || $change_status == 'processing' || $change_status == 'courier' || $change_status == 'on_hold' || $change_status == 'complete')){
                $this->util->decreaseProductStock($line->product_id,$line->variation_id, $line->quantity);
            }
            if(($old_status == 'pending' || $old_status == 'processing' || $old_status == 'courier' || $old_status == 'on_hold' || $old_status == 'complete') && ($change_status == 'cancell' || $change_status == 'return')) {
                $this->util->increaseProductStock($line->product_id,$line->variation_id, $line->quantity);
            }
        }
        
        $item->status=request('status');
        $item->save();
      
      	/*if(request('status') == 'on_the_way' && $item->courier_id == 1 && $item->courier_tracking_id == NULL)
        {
			  $status = $this->createRedxParcel($item);
              if($status['tracking_id'])
              {
                $item->courier_tracking_id = $status['tracking_id'];
                $item->save();
              }
        }*/

        return response()->json(['status'=>true ,'msg'=>'Order Status Is Updatedssssssssssss !!']);

    }

    public function assignUser(){
        $users=User::whereHas('roles', function($q){
          	$q->whereNotNull('name');
        })->get();
        return view('backend.orders.assign_user', compact('users'));

    }

    public function orderStatusUpdateMulti(){

        $status=getOrderStatus();
        return view('backend.orders.all_status_update', compact('status'));

    }

    public function multuOrderStatusUpdate(){
      
        foreach(request('order_ids') as $id)
        {   
          	$item=Order::with('user')->find($id);
            $item->status=request('status');
            $item->save();
           /*if(request('status') == 'on_the_way' && $item->courier_id == 1 && $item->courier_status != 1)
           {
              $status = $this->createRedxParcel($item);
              if($status['tracking_id'])
              {
                $item->courier_status = 1;
                $item->save();
              }
           } */
          
        }
      
        return response()->json(['status'=>true ,'msg'=>'Order Status Updated!!']);
    }
    
    function updateCourierStatus()
  {
        foreach(request('order_ids') as $id)
        {
           $item=Order::with('user')->find($id);
           if($item->courier_id == NULL || $item->courier_id !== 3)
           {
                return response()->json(['status'=>false ,'invoice'=>$item->order_id, 'errors'=>'This order only for Steadfast Courier']);
           }
           else if($item->courier_tracking_code == NULL)
           {
                return response()->json(['status'=>false ,'invoice'=>$item->order_id, 'errors'=>'Steadfast Courier Tracking Code Not Found!']);
           }
           else
           {
              $response = Http::withHeaders([
                    'Api-Key' => $this->steadfast_api_key,
                    'Secret-Key' => $this->steadfast_secret_key,
                    'Content-Type' => 'application/json'
              ])->get($this->steadfast_api_base_url.'status_by_trackingcode/'.$item->courier_tracking_code);
        
              $status =  $response->json();
              if($status && $status['status'] == '200'  && $status['delivery_status'])
              {
                $delivery_status = $status['delivery_status'];
                $item->courier_status = $delivery_status;
                if(!$item->save())
                {
                    return response()->json(['status'=>false ,'invoice'=>$item->order_id, 'errors'=>'Something went wrong!']);
                }
              }
              else
              {
                    return response()->json(['status'=>false ,'invoice'=>$item->order_id, 'errors'=>'Something went wrong!']);
              }

           }

        }
        
        return response()->json(['status'=>true ,'msg'=>'Courier Status Updated Successfully!!']);
  }

    public function assignUserStore(){
        

        DB::table('orders')->whereIn('id', request('order_ids'))->update(['assign_user_id'=>request('assign_user_id')]);
        return response()->json(['status'=>true ,'msg'=>'User Assigned!!']);

    }

    public function deleteAllOrder(){

            DB::beginTransaction();

        try {

            if(!auth()->user()->can('order.delete'))
            {
                abort(403, 'unauthorized');
            }

            $orders=DB::table('orders')->select('id')->whereIn('id', request('order_ids'))->get();

            foreach ($orders as $key => $order) {
                $item=Order::find($order->id);

                if($item->details()->count()){
                    foreach ($item->details as $key => $line) {
                        $this->util->increaseProductStock($line->product_id,$line->variation_id,$line->quantity);
                    }
                    $item->details()->delete();
                }

                if($item->payments()->count()){
                    $item->payments()->delete();
                }

                $item->delete();

            }

            DB::commit();
            return response()->json(['status'=>true ,'msg'=>'Order Is Deleted!!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
        }


    }
  
  
  //Redx Courier Service
  function OrderSendToRedx()
  {
      $data = "";
        foreach(request('order_ids') as $id)
        {   
           $item=Order::with('user')->find($id);
           if($item->status == 'courier' && $item->courier_id == 1 && $item->courier_tracking_id == NULL)
           {
              $status = $this->createRedxParcel($item);
              
              //$data = $status['tracking_id'];
              if(!empty($status['tracking_id']))
              {
                $item->courier_tracking_id = $status['tracking_id'];
                $item->save();
              }
             else if(!empty($status['message']))
             {
               return response()->json(['status'=>false ,'msg'=>'Invoice: '.$item->invoice_no.' '.$status['message']]);
             }
           } else {
               return response()->json(['status'=>false ,'msg'=>'Something were wrong!!']); 
           }
          
        }
        return response()->json(['status'=>true ,'msg'=>'Order Send to Redx Successfully!!']);
        //return response()->json(['status'=>true ,'msg'=>$data]);
  }
  


    function getRedxAreaList($by = null, $value = null)
    {
        
        try {
            $response = Http::withHeaders([
                'API-ACCESS-TOKEN' => $this->redx_api_access_token,
            ])->get($this->redx_api_base_url.'areas');
            $areas = $response->json()['areas'];
            return $areas;
        } catch (\Exception $e) {
            return null;
        }
    }

  function createRedxParcel($item)
  {
      //$area = $this->getRedxAreaList('post_code', $item->zip_code)[0];
    
      $response = Http::withHeaders([
            'API-ACCESS-TOKEN' => $this->redx_api_access_token,
            'Content-Type' => 'application/json'
        ])->post($this->redx_api_base_url.'parcel', [
            "customer_name" 		 => $item->first_name ?? $item->user->first_name.' '.$item->last_name ?? $item->user->last_name, 
            "customer_phone"         => $item->mobile ?? $item->user->mobile,
            "delivery_area"          => $item->area_name, // delivery area name
            "delivery_area_id"       => $item->area_id, // area id
            "customer_address"       => $item->shipping_address, 
            "merchant_invoice_id"    => $item->invoice_no,
            "cash_collection_amount" => $item->final_amount,
            "parcel_weight"          => "500", //parcel weight in gram
            "instruction"            => "",
            "value"                  => $item->final_amount, //compensation amount
            "pickup_store_id"        => 0, // store id
            "parcel_details_json"    => []
        ]); 
    
    return $response->json();
  }
  
  //Pathao Courier Service
  
    public function fetchAddressDetails(Request $request){
        $address = strtolower($request->input('address'));

        $city = null;
        $zone = null;
        $area = null;
    
        $cities = $this->getPathaoCityList();
        $city = collect($cities)->first(function ($c) use ($address) {
            return str_contains($address, strtolower($c['city_name']));
        });
    
        if ($city) {
            $zonesResponse = $this->getPathaoZoneListByCity($city['city_id']);
            $zones = $zonesResponse->getData(true)['zones'] ?? [];
    
            $zone = collect($zones)->first(function ($z) use ($address) {
                return str_contains($address, strtolower($z['zone_name']));
            });
    
            if ($zone) {
                $areasResponse = $this->getPathaoAreaListByZone($zone['zone_id']);
                $areas = $areasResponse->getData(true)['areas'] ?? [];
    
                $area = collect($areas)->first(function ($a) use ($address) {
                    return str_contains($address, strtolower($a['area_name']));
                });
            }
        }
    
        return response()->json([
            'city_id' => $city['city_id'] ?? null,
            'zone_id' => $zone['zone_id'] ?? null,
            'area_id' => $area['area_id'] ?? null,
        ]);
    }

    function getPathaoStoreList(){
        $info = Information::first();
        if ($info->pathao_status == 0) {
            return response()->json(['success' => true, 'message' => 'Pathao De Active']);
        }
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . ($this->pathao_api_access_token ?? ''),
            ])->get(($this->pathao_api_base_url ?? '') . 'aladdin/api/v1/stores');
    
            if ($response->failed()) {
                Log::error('Pathao Store API failed', ['response' => $response->body()]);
                return [];
            }
    
            return $response->json()['data']['data'] ?? [];
        } catch (\Exception $e) {
            Log::error('Pathao Store API exception', ['message' => $e->getMessage()]);
            return [];
        }
    }
    
    function getPathaoCityList(){
        $info = Information::first();
        if ($info->pathao_status == 0) {
            return response()->json(['success' => true, 'message' => 'Pathao De Active. Not show city list']);
        }
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . ($this->pathao_api_access_token ?? ''),
            ])->get(($this->pathao_api_base_url ?? '') . 'aladdin/api/v1/countries/1/city-list');
    
            if ($response->failed()) {
                Log::error('Pathao City API failed', ['response' => $response->body()]);
                return [];
            }
    
            return $response->json()['data']['data'] ?? [];
        } catch (\Exception $e) {
            Log::error('Pathao City API exception', ['message' => $e->getMessage()]);
            return [];
        }
    }
    
    function getPathaoZoneListByCity($city){
        $info = Information::first();
        if ($info->pathao_status == 0) {
            return response()->json(['success' => true, 'message' => 'Pathao De Active. So not show zone list']);
        }
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . ($this->pathao_api_access_token ?? ''),
            ])->get(($this->pathao_api_base_url ?? '') . 'aladdin/api/v1/cities/' . $city . '/zone-list');
    
            if ($response->failed()) {
                Log::error('Pathao Zone API failed', ['response' => $response->body()]);
                return response()->json(['success' => true, 'zones' => []]);
            }
    
            $zones = $response->json()['data']['data'] ?? [];
            return response()->json(['success' => true, 'zones' => $zones]);
        } catch (\Exception $e) {
            Log::error('Pathao Zone API exception', ['message' => $e->getMessage()]);
            return response()->json(['success' => true, 'zones' => []]);
        }
    }
    
    function getPathaoAreaListByZone($zone){
        $info = Information::first();
        if ($info->pathao_status == 0) {
            return response()->json(['success' => true, 'message' => 'Pathao De Active. so not show zone list']);
        }
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . ($this->pathao_api_access_token ?? ''),
            ])->get(($this->pathao_api_base_url ?? '') . 'aladdin/api/v1/zones/' . $zone . '/area-list');
    
            if ($response->failed()) {
                Log::error('Pathao Area API failed', ['response' => $response->body()]);
                return response()->json(['success' => true, 'areas' => []]);
            }
    
            $areas = $response->json()['data']['data'] ?? [];
            return response()->json(['success' => true, 'areas' => $areas]);
        } catch (\Exception $e) {
            Log::error('Pathao Area API exception', ['message' => $e->getMessage()]);
            return response()->json(['success' => true, 'areas' => []]);
        }
    }
  
  
  
    function OrderSendToPathao(Request $request){
        DB::beginTransaction();
        try {
            foreach(request('order_ids') as $id){
                $item=Order::with('user')->find($id);
                if($item->courier_id == 2 && $item->courier_tracking_id == NULL){
                    $status = $this->createPathaoParcel($item);
                    if(!empty($status['data']['consignment_id'])){
                        $item->courier_status = $status['data']['order_status'];
                        $item->courier_tracking_id = $status['data']['consignment_id'];
                        $item->save();
                    }else if(!empty($status['errors'])){
                       return response()->json(['status'=>0 ,'invoice'=>$item->invoice_no, 'errors'=>$status['errors']]);
                    }
                }else{
                    return response()->json(['status'=>0 ,'msg'=>'Invoice: '.$item->invoice_no.' Error occured']);
                }
            
            }
            
            DB::commit();
            return response()->json(['status'=>1 ,'msg'=>'Order Send to Pathao Successfully!!']);
        } catch (\Exception $e) {
           DB::rollback();
           return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
    }
  
  
  
  function createPathaoParcel($item){
        $info = Information::first();
        if ($info->pathao_status == 0) {
            return response()->json(['success' => true, 'message' => 'Pathao De Active. Please Active and then send data in pathao']);
        }
        
      $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->pathao_api_access_token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->pathao_api_base_url.'aladdin/api/v1/'.'orders', [
        	"store_id" 		 		 		=> $this->pathao_store_id, 
            "merchant_order_id"      		=> $item->invoice_no,
            "sender_name"          			=> auth()->user()->username ?? 'Admin', 
            "sender_phone"       			=> auth()->user()->mobile ?? '01700000000',
            "recipient_name"       			=> $item->first_name ?? $item->user->first_name.' '.$item->last_name ?? $item->user->last_name, 
            "recipient_phone"    			=> $item->mobile ?? $item->user->mobile,
            "recipient_address" 			=> $item->shipping_address,
            "recipient_city"          		=> $item->city,
            "recipient_zone"                => $item->state,
            "recipient_area"                => $item->area_id,
            "delivery_type"        			=> 48, 
            "item_type"    					=> 2,
            "special_instruction"    		=> "",
            "item_quantity"    				=> 1,
            "item_weight"    				=> $item->weight ?? 0.5,
            "amount_to_collect"    			=> (int) $item->final_amount,
            "item_description"    			=> $item->note,
        
        ]); 
    
    return $response->json();
  }
  
    //Steadfast Courier Service
    function OrderSendToSteadfast(){
        foreach(request('order_ids') as $id){   
           $item=Order::with('user')->find($id);
           if($item->courier_id == NULL || $item->courier_id !== 3){
                return response()->json(['status'=>false ,'invoice'=>$item->invoice_no, 'errors'=>'This order only for Steadfast Courier']);

           }else if($item->courier_tracking_id != NULL){
              return response()->json(['status'=>false ,'invoice'=>$item->invoice_no, 'errors'=>'This order already send to Steadfast Courier']);

           }else if($item->courier_id == 3 && $item->courier_tracking_id == NULL){
               
              $status = $this->createSteadfastParcel($item);
             
              if(!empty($status['consignment']['consignment_id'])){
                $item->courier_tracking_id   = $status['consignment']['consignment_id'];
                $item->courier_tracking_code = $status['consignment']['tracking_code'];
                $item->courier_status = $status['consignment']['status'];
                $item->save();
              }else{
                return response()->json(['status'=>false ,'invoice'=>$item->order_id, 'errors'=>'Something went wrong!']);
              }

           }
        }
        return response()->json(['status'=>true ,'msg'=>'Order Send to Steadfast Successfully!!']);
    }

  
  function createSteadfastParcel($item) {
      $response = Http::withHeaders([
            'Api-Key' => $this->steadfast_api_key,
            'Secret-Key' => $this->steadfast_secret_key,
            'Content-Type' => 'application/json'
        ])->post($this->steadfast_api_base_url.'/create_order', [
            "invoice"      		=> $item->invoice_no,
            "recipient_name"    => $item->first_name ? $item->first_name.' '.$item->last_name : $item->user->first_name.' '.$item->user->last_name, 
            "recipient_phone"   => $item->mobile ?? $item->user->mobile,
            "recipient_address" => $item->shipping_address,
            "cod_amount"    	=> (int) $item->final_amount,
            "note"    			=> $item->note,
        ]); 
    
    return $response->json();
  }
  
  public function viewAccessToken()
  {
   return view('backend.informations.generate-pathao-access-token'); 
  }
  
  public function generatePathaoAccessToken(Request $request)
  {
     	$response = Http::withHeaders([
            'content-type' => 'application/json',
            'accept' => 'application/json',
        ])->post($this->pathao_api_base_url.'aladdin/api/v1/issue-token', [
        	"client_id" 		 		=> $request->client_id,
            "client_secret"      		=> $request->client_secret,
            "username"          		=> $request->client_email,
            "password"       			=> $request->client_password,
            "grant_type"       			=>  "password"

        ]);
    
    $tokenData = $response->json();
    return view('backend.informations.generate-pathao-access-token-view', compact('tokenData'));
  }
  
  
    public function fraudulentCheck($mobileNo) {
        $info = Information::first();
        $dataList = Http::get('https://dash.hoorin.com/api/courier/search.php', [
            'apiKey' => $info->fraudApi,
            'searchTerm' => $mobileNo
        ]);
        $data = $dataList->json();
        return json_encode($data);
    }
    
    public function fraudOrderCheck($id){
        
        $result = Order::select(['id', 'user_id'])->find($id);
        if($result) {

            $result->customerPhone =$result->user?$result->user->mobile:'01782889864';
            $totalSummery =$this->courierSummery($result->customerPhone); 
            
            $datas =$totalSummery['Summaries'];
            $datas2 =$totalSummery['TotalSummary'];
            
            $customer =$result->user;
            if($customer && isset($totalSummery['TotalSummary'])){
                $customer->curier_summery =$datas2;
                $customer->save();
            }
            
        
            $datas2 =$datas2['Summaries'] ?? [];

            $result->total_parcels =isset($datas2['Total Parcels'])?$datas2['Total Parcels']:0;
            $result->total_delivered =isset($datas2['Total Delivered'])?$datas2['Total Delivered']:0;
            $result->total_canceled =isset($datas2['Total Canceled'])?$datas2['Total Canceled']:0;
            $result->total_ratio = ($result->total_parcels > 0) 
            ? round(($result->total_delivered / $result->total_parcels) * 100,0) 
            : 0;
            $result->purcelsdatas =count($datas) > 0?$datas:null;
            
        }
        
        $view =view('backend.orders.fraudOrder',compact('result'));
        
        return response($view);
        
    }
    
    public function courierSummery($number){
        $info = Information::first();
        $apiKey = $info->fraudApi;
        
        $url1 = "https://dash.hoorin.com/api/courier/search.php?apiKey=$apiKey&searchTerm=$number";
        $url2 = "https://dash.hoorin.com/api/courier/sheet.php?apiKey=$apiKey&searchTerm=$number";
        
        $response1 = $this->callApi($url1);
        $response2 = $this->callApi($url2);
        
        $summary = [
            'Summaries' =>$response1['Summaries'] ?? [], 
            'TotalSummary' => $response2,
        ];
        
        return $summary;
    }
    
    private function callApi($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    
        return json_decode($response, true);
    }
  
}
