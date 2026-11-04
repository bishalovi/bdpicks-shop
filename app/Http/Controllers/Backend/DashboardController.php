<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\User;
use App\Models\Expense;
use App\Models\ProductReview;
use Auth;
use Carbon\Carbon;
use DB;


class DashboardController extends Controller
{
    public function dashboard(Request $request){
        
      	if(!auth()->user()->can('dashboard.access'))
        {
            abort(403, 'unauthorized');
        }
        
        $status=$request->status;
        $q=$request->q;
        
        // $bestsellerProducts = OrderDetails::leftJoin('products','order_details.product_id','products.id')
        //                     ->leftJoin('variations','products.id','variations.product_id')
        //                     ->select('products.name', 'products.image', DB::raw('ANY_VALUE(variations.stock_quantity) as qty'), DB::raw('COUNT(*) as total_occurrences'))
        //                     ->groupBy('order_details.product_id')
        //                     ->orderByDesc('total_occurrences')
        //                     ->limit(5)
        //                     ->get();
        
        // $thismonthbestsellerProducts = OrderDetails::leftJoin('products', 'order_details.product_id', 'products.id')
        //                         ->leftJoin('variations','products.id','variations.product_id')
        //                         ->select('products.name', 'products.image', DB::raw('ANY_VALUE(variations.stock_quantity) as qty'), DB::raw('COUNT(*) as total_occurrences'))
        //                         ->whereYear('order_details.created_at', '=', Carbon::now()->year)
        //                         ->whereMonth('order_details.created_at', '=', Carbon::now()->month)
        //                         ->groupBy('order_details.product_id')
        //                         ->orderByDesc('total_occurrences')
        //                         ->limit(5)
        //                         ->get();
        
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
        
        $items=$query->latest()->take(20)->get();       
        $statuses=getOrderStatus();
        // return view('backend.dashboard', compact('items','status','q','statuses','bestsellerProducts','thismonthbestsellerProducts'));
        $total_stocks = Product::select('stock_quantity')->sum('stock_quantity');
        return view('backend.dashboard', compact('items','status','q','statuses', 'total_stocks'));
    }
    
    public function getDashboardData2(Request $request){
        $startDate=$request->startDate;
  	    $endDate=$request->endDate;
  	    
  	    $total_orders=Order::whereBetween('created_at',[$startDate,$endDate])->count();
  	    $pending_orders=Order::whereBetween('created_at',[$startDate,$endDate])->where('status','pending')->count();
  	    $complete_orders=Order::whereBetween('created_at',[$startDate,$endDate])->where('status','complete')->count();
  	    $cancell_orders=Order::whereBetween('created_at',[$startDate,$endDate])->where('status','cancell')->count();
  	    
  	    $sell_amount=Order::whereBetween('created_at', [$startDate,$endDate])->sum('final_amount');
  	    
  	 //   $query = Order::selectRaw('SUM(amount - total_purchase) as profit')
  	 //           ->where('orders.status','complete')
    //             ->whereBetween('created_at', [$startDate, $endDate]);
  	    $query = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->where('orders.status', 'complete')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('
                    SUM(
                        (order_details.unit_price - 
                         IF(order_details.purchase_price IS NOT NULL AND order_details.purchase_price > 0, 
                            order_details.purchase_price, 
                            products.purchase_prices)
                        ) * order_details.quantity
                    ) as profit
                ')
            );
        
        // dd($query->profit);
        
        $totalExpense=Expense::whereBetween('date',[$startDate,$endDate])->sum('amount');
        $profit = $query->value('profit');
        // dd($profit);
        if($profit!=null){
            $profit=$profit-$totalExpense;
        } else {
            $profit=0;
        }
        
    	return response()->json([
    	    'success'=>true,'profit'=>$profit,'totalExpense'=>$totalExpense,
    	    'total_orders'=>$total_orders,'pending_orders'=>$pending_orders,
    	    'complete_orders'=>$complete_orders,'cancell_orders'=>$cancell_orders,
    	    'sell_amount'=>$sell_amount
    	]);
    	
    }
    
    
    
    public function index(){
        $s=request('q');
        $query = ProductReview::latest();
                if(!empty($s)){
                    $query->where(function($row) use($s){
                       $row->where('name', 'Like','%'.$s.'%');
                    });
                }  
        $data=$query->paginate(30);
        
        return view('backend.review.index', compact('data'));
    }
    
    public function destroy($id){
        ProductReview::destroy($id);
        return response()->json(['status'=> true, 'msg' => 'User has been deleted']);
    }
  
  	public function getDashboardData(Request $request){
  	    $workerCount = User::whereHas('roles', function ($query) {
    $query->where('name', 'worker');
})->count();
    	$data['products']=Product::count();
      	$data['orders']=Order::count();
      	$data['users']=$workerCount;
        $data['current_month_sell']=Order::whereMonth('created_at', date('m'))->sum('final_amount');
      	$data['today_sell']=Order::whereDate('created_at', date('Y-m-d'))->sum('final_amount');
        $data['prev_month_sell']=Order::whereBetween('created_at', 
                                    [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()]
                                )->sum('final_amount');
                                
     	return view('backend.partials.dashboard_data', $data);
	}
    public function reviewAction(Request $request)
    {
        $ids = $request->ids ?? [];
    
        if (empty($ids)) {
            return response()->json(['status' => false, 'msg' => 'No reviews selected!']);
        }
    
        // Delete
        if ($request->has('delete')) {
            ProductReview::whereIn('id', $ids)->delete();
            return response()->json(['status' => true, 'msg' => 'Selected reviews deleted successfully!']);
        }
    
        // Approve or Reject
        if ($request->has('status')) {
            $status = $request->status == 1 ? 1 : 0;
            ProductReview::whereIn('id', $ids)->update(['status' => $status]);
            $msg = $status ? 'Selected reviews approved!' : 'Selected reviews rejected!';
            return response()->json(['status' => true, 'msg' => $msg]);
        }
    
        return response()->json(['status' => false, 'msg' => 'Invalid action!']);
    }

}
