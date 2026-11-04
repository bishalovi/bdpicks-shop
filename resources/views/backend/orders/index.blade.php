@extends('backend.app')
@section('content')

<style>
  .ps-2, .page-title {
      color: black !important;
  }
 .disable-click{
    pointer-events:none;
}
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">SIS</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">CRM</a></li>
                    <li class="breadcrumb-item active">Order List</li>
                </ol>
            </div>
            <h4 class="page-title">Order List</h4> 
        </div>
    </div>
</div>   
<!-- end page title --> 
<style>
    .btn.btn-sm{
        font-size: 12px;
    }    
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">

                    <div class="col-xl-7 text-left">
                        <div class="#" style="">
                            @can('product.create')
                                <a href="{{ route('admin.orders.create')}}" class="btn btn-sm btn-danger mb-1"><i class="mdi mdi-basket me-1"></i> Add New Order</a>
                            @endcan
                            
                            @can('product.edit')
                                <a class="btn_modal btn btn-sm btn-info mb-1" href="{{ route('admin.assignUser')}}"><i class="mdi mdi-plus me-1"></i>
                                    Assign User
                                </a>
                            @endcan
                            <a class="btn_modal btn btn-sm btn-info mb-1" href="{{ route('admin.orderStatusUpdateMulti')}}"><i class="mdi mdi-plus me-1"></i>
                               Status Change
                            </a>
                            
                            @can('product.delete')
                                <a class="multi_order_delete btn btn-sm btn-danger mb-1" href="{{ route('admin.deleteAllOrder')}}"><i class="mdi mdi-plus me-1"></i>
                                    Delete All
                                </a>
                            @endcan
                            @php $isPending = \App\Models\Order::where(['status' => 'pending'])->first(); @endphp
                            <a class="multi_order_print btn btn-sm btn-success mb-1" href="{{ route('admin.orderList')}}"><i class="mdi mdi-plus me-1"></i>
                                Print
                            </a>                            
                          <a class="send_to_redx btn btn-sm btn-dark mb-1" href="{{ route('admin.createRedxParcel')}}">
                                Send to Redx
                          </a>                          
                          <a class="send_to_pathao btn btn-sm btn-primary mb-1" href="{{ route('admin.createPathaoParcel')}}">
                                Send to Pathao
                          </a>
                          
                          <a class="send_to_steadfast btn btn-sm btn-success mb-1" href="{{ route('admin.createSteadfastParcel')}}">
                            Send to Steadfast
                          </a>
                          
                          <button class="btn btn-sm btn-info mb-1" id="btn_courier_status" href="{{ route('admin.updateCourierStatus') }}">
                            Update Courier Status
                            </button>
                          
                          <!--<a class="btn btn-sm btn-success mb-1" href="{{ route('admin.trashed_orders') }}">-->
                          <!--  Trashed Orders-->
                          <!--</a>-->
                          
                          <!--<div class="col-md-4">-->
                          <!--  <select class="select2" name="courier_type">-->
                          <!--    <option value="" disabled selected>Choose Courier Option</option>-->
                          <!--    <option value="">All</option>-->
                          <!--    <option value="redx">Redx</option>-->
                          <!--    <option value="pathao">Pathao</option>-->
                          <!--    <option value="none">None</option>-->
                          <!--  </select>-->
                          <!--</div>                          -->
                          <div class="col-md-4 d-none">
                            <select class="select2" name="redx_status">
                              <option value="" disabled selected>Choose Courier Status</option>
                              <option value="">All</option>
                              <option value="yes">Yes ({{$yes_count}})</option>
                              <option value="no">No ({{$no_count}})</option>
                            </select>
                          </div>
                        </div>
                    </div>
                    <div class="col-xl-4 d-none">
                        <div class="text-xl-end mt-xl-0 mt-2">                        
                            <a type="button" href="{{ route('admin.orderExport')}}" class="btn btn-light mb-2">Export</a>
                        </div>
                    </div><!-- end col-->
                </div>   
              
              <div class="d-none d-md-block d-lg-block">
                <form class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between" id="filter_form">
              <div class="col-12 d-flex justify-content-between align-items-center">
           
                <div class="d-flex">              
                  
                  @foreach(getOrderStatus() as $key=>$value)
                  <label class="ps-2">
                    <input type="radio" class="order_sts"  name="status" value="{{$key}}"/>                    
                    @if(Auth::user()->hasRole('worker'))
                    
                    {{$value}} ({{\App\Models\Order::whereHas('details.product', function($q) {
                    	$q->whereNotNull('name');
                    })->where('status', $key)->where('assign_user_id', Auth::user()->id)->count()}})
                    
                    @else
                    
                    {{$value}} ({{\App\Models\Order::whereHas('details.product', function($q) {
                    	$q->whereNotNull('name');
                    })->where('status', $key)->count()}})
                    
                    @endif                 
                    
                    
                  </label>
                  @endforeach                  
                  
                                                 
                </div>
                 
                <div class="col-2">
                    <label for="inputPassword2" class="visually-hidden">Searching Here</label>
                    <input type="search" class="form-control" id="inputPassword2" placeholder="Searching Here..." name="q">
                  </div>
                
                  <div class="col-auto">
                    <label for="submit" class="visually-hidden">Submit</label>
                    <input type="button" class="form-control btn btn-sm btn-dark py-1" id="submit_search" value="Submit">
                  	</div>
     				</div>
                </form>   
              </div>

              <div class="col-sm-12 mt-2" id="rcvd_order">
                <div class="table-responsive">
                    <table class="table table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>                                    
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input check_all" value="">
                                      </label>
                                    </div>
                                </th>

                              	<th style="width:7%">Action</th>
                                <th>Invoice ID</th>
                                <th>Date Order</th>
                                <th>Order Time</th>
                                <th>Customers</th>
                                <th>Product SKU</th>
                                <th>Status</th>
                                <th>Fraud Check</th>
                                <th>Payment Status</th>
                                <th>Assign User</th>
                              	<th>Courier</th>
                              	<th>Courier Status</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" class="order_checkbox" value="{{ $item->id}}">                                    
                                </td>
                              	<td>
                                    <!--<a href="{{$item->status === 'pending' ? 'javascript:void(0)' : route('admin.orders.show',[$item->id])}}" target="{{$item->status === 'pending' ? '' : '_blank'}}" class="action-icon " title="{{$item->status === 'pending' ? 'pending invoice will not be printed' : 'Print Invoice'}}"> <i class="fa fa-print" aria-hidden="true"></i></a>-->
                                    <a href="{{ route('admin.orders.edit',[$item->id])}}" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                                    @can('order.delete')
                                    <a href="{{ route('admin.orders.destroy',[$item->id])}}" class="delete action-icon"> <i class="mdi mdi-delete"></i></a>
                                    @endcan
                                </td>
                                <td style="color: #000;">#{{$item->invoice_no}}</td>
                              	<td style="color: #000;">{{ dateFormate($item->date)}}</td>
                              	<td>{{ $item->created_at->diffForHumans(); }}</td> 
                                <td style="color: #000;">{{$item->first_name.' '.$item->last_name}}<br>
                                    {{$item->shipping_address}}<br>
                                    {{$item->mobile}}
                                </td>
                              <td>
                                  
                                  <?php
                                    foreach($item->details as $detail)
                                    {
                                        if(!isset($detail->product['sku']) || $detail->product['sku'] == '')
                                        {
                                            ?> <span style="color: red;">Unavailable</span> <?php
                                            
                                        } else 
                                        {
                                            echo $detail->product['sku'];
                                        }
                                    }
                                  ?>
                                
                                </td>
                                <td><a class="btn_modal" href="{{ route('admin.orderStatus', $item->id)}}">
                                        <h5 class="my-0"><span class="badge badge-info-lighten">{{$item->status}}</span></h5>
                                    </a>
                                </td>
                                
                                <td>
                                    @php
                                    $percent =$item->getCourierPercent();
                                    @endphp
                                    
                                    @if($percent > 50)
                                   <div style="color:green;font-weight: bold;text-align:center">
                                    @elseif($percent > 20)
                                    <div style="color:yellow;font-weight: bold;text-align:center">
                                    @else
                                    <div style="color:red;font-weight: bold;text-align:center">
                                    @endif
                                    {{$percent}}%
                                    </div>
                                    <a href='javascript:void(0);' data-url="{{route('admin.fraudOrderCheck',$item->id)}}" class='btn btn-link btn-fraud' style='color: red;cursor: pointer;'> <i class='dripicons-search'></i></a>
                                </td>
                                
                                <td><a class="btn_modal" href="{{ route('admin.order_payments.edit', $item->id)}}">
                                        <h5 class="my-0"><span class="badge badge-danger-lighten">{{$item->payment_status}}</span></h5>    
                                    </a>
                                </td>

                                <td style="color: #000;">{{ $item->assign?$item->assign->username:''}}</td>
                                <td style="color: #000;">{{ $item->courier?$item->courier->name:''}} <br> {{ $item->courier_tracking_id ?? ''}}
                                <br>
                                @if($item->courier_id==1 && $item->courier_tracking_id)
                                <a href="https://redx.com.bd/track-global-parcel/?trackingId={{$item->courier_tracking_id}}" target="_blank">Track Link</a>
                                @elseif($item->courier_id==3 && $item->courier_tracking_code)
                                <a href="https://steadfast.com.bd/t/{{$item->courier_tracking_code}}" target="_blank">Track Link</a>
                                @elseif($item->courier_id==2 && $item->courier_tracking_id)
                                <a href="https://merchant.pathao.com/tracking?consignment_id={{$item->courier_tracking_id}}" target="_blank">Track Link</a>
                                @endif
                                </td>
                              	<td>
                                 @if($item->courier_status)
                                    {{ $item->courier_status ?? '' }}
                                  @endif
                                </td>
								<td style="color: #000;">
                                  @php 
                                    $final_amount = $item->final_amount;                                    
                                    $fa = intval($final_amount);                                    
                                   echo $fa;                                    
                                  @endphp                             
                              </td>  
                               
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                  	 </div>
                    <p>{!! urldecode(str_replace("/?","?",$items->appends(Request::all())->render())) !!}</p>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->



<div tabindex="-1" role="dialog" class="orderFraudModal modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" 
                        style="font-size: 14px;border: 1px solid #24d164;padding: 8px 15px;border-radius: 5px;color: #24d164;font-weight: bold;">
                    <span aria-hidden="true" style="font-size: 18px;color: black;margin-right: 5px;">×</span> Close
                </button>
                <div class="orderFraud">
                    <!-- Modal content here -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 

@push('js')
<script src="{{ asset('backend/js/order.js')}}"></script>
<script>
$(document).ready(function(){
    
     $(document).on('click', '.btn-fraud', function (e) {
        e.preventDefault();
        var url = $(this).attr('data-url');
        $.ajax({
            type: "get",
            url: url,
            success: function (response) {
                console.log(response);
                $('.orderFraudModal .modal-body .orderFraud').empty().append(response);
                $('.orderFraudModal').modal('toggle');
            }
            
        });
    });
  
  $("select[name='redx_status']").on('change', function(){
      getOrderList();
  });   
  
  $("select[name='courier_type']").on('change', function(){
      getOrderList();
  });  
  
  $('.order_sts').on('click', function(){
      getOrderList();
  });
  
  function getOrderList()
  {
   	  var statusValue = $("input[name='status']:checked").val();
      var redx_status = $("select[name='redx_status']").val();
      var courier_type = $("select[name='courier_type']").val();
      $.ajax({
        type: 'GET',
      	url: "{{ route('admin.status_wise_order') }}",
        data: {statusValue, redx_status, courier_type},
        success: function(res){
      		if(res.success == true){            
              $('#rcvd_order').html(res.view);             	
            }
        }     
      }); 
  }
  
    $('#submit_search').on('click', function(){
      var searchValue = $("input[name='q']").val();
      $.ajax({
        type: 'GET',
      	url: "{{ route('admin.searchOrder') }}",
        data: {searchValue},
        success: function(res){
      		if(res.success == true){            
              $('#rcvd_order').html(res.view);             	
            }
        }     
      });
      
    });

    $(".check_all").on('change',function(){
      $(".order_checkbox").prop('checked',$(this).is(":checked"));
    });

    $(document).on('submit', 'form#order_status_update_form', function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        let status=$(document).find('select#multi_status').val();
    
        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();
        
        if(order_ids.length ==0){
            toastr.error('Please Select An Order First !');
            return ;
        }
        
        $.ajax({
           type:'GET',
           url:url,
           data:{status,order_ids},
           success:function(res){
               if(res.status==true){
                toastr.success(res.msg);
                window.location.reload();
                
            }else if(res.status==false){
                toastr.error(res.msg);
            }
           }
        });
    
    });

    $(document).on('submit', 'form#order_assign_form', function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        let assign_user_id=$(document).find('select#assign_user_id').val();
    
        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();
        
        if(order_ids.length ==0){
            toastr.error('Please Select An Order First !');
            return ;
        }
        
        $.ajax({
           type:'GET',
           url:url,
           data:{assign_user_id,order_ids},
           success:function(res){
               if(res.status==true){
                toastr.success(res.msg);
                window.location.reload();
                
            }else if(res.status==false){
                toastr.error(res.msg);
            }
           }
        });
    
    });

    $(document).on('click', 'a.multi_order_delete', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
    
        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();
        
        if(order_ids.length ==0){
            toastr.error('Please Select An Order First !');
            return ;
        }
        
        $.ajax({
           type:'GET',
           url:url,
           data:{order_ids},
           success:function(res){
               if(res.status==true){
                toastr.success(res.msg);
                window.location.reload();
                
            }else if(res.status==false){
                toastr.error(res.msg);
            }
           }
        });
    
    }); 
    
    $(document).on('click', 'a.multi_order_print', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
    
        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();
        
        if(order_ids.length ==0){
            toastr.error('Please Select Atleast One Order!');
            return ;
        }
        
        $.ajax({
           type:'GET',
           url,
           data:{order_ids},
           success:function(res){
               if(res.status==true){
                   console.log(res.items, res.info);                          
                   var myWindow = window.open("", "_blank");                   
  				   myWindow.document.write(res.view);                   
                // toastr.success(res.msg);
                // window.location.reload();
                   
            }else if(res.status==false){
                toastr.error(res.msg);
            }
           }
        });
    
    }); 
    
     //Steadfast Courier Status Update
  $(document).on('click', 'button#btn_courier_status', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
    	let link = $(this);
        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();
        if(order_ids.length ==0){
            toastr.error('Please Select Atleast One Order!');
            return ;
        }

        if(confirm('Are you sure?'))
        {
           $.ajax({
               type:'GET',
               url,
               data:{order_ids},
               beforeSend: function(){
                 link.addClass('disable-click');
                 link.text('Please wait...');
               },
               success:function(res){
                   link.removeClass('disable-click');
                   link.text('Update Courier Status');
                   if(res.status){
                    toastr.success(res.msg);
    
                }else{
                    toastr.error(`Invoice No. : ${res.invoice} something went wrong!`);
                  	console.log(res.errors);
                }
               }
            });
        }
      
    });
  
  
  //Redx Courier Service
  $(document).on('click', 'a.send_to_redx', function(e){
        e.preventDefault();
    // 	var statusValue = $("input[name='status']:checked").val();
        var url = $(this).attr('href');
    	let link = $(this);
        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();
        
        if(order_ids.length ==0){
            toastr.error('Please Select Atleast One Order!');
            return ;
        }        
    	
        // else if(statusValue != 'on_the_way'){
        //     toastr.error('Only On The Way Orders are Accepted!');
        //     return ;
        // }
    	
        
        $.ajax({
           type:'GET',
           url,
           data:{order_ids},
           beforeSend: function(){
             link.addClass('disable-click');
             link.text('Please wait...');
           },
           success:function(res){
               link.removeClass('disable-click');
               link.text('Send to Redx');
               if(res.status){               
                toastr.success(res.msg);
                   
            }else{
                toastr.error(res.msg);
            }
           }
        });
    
    });
  
  //Pathao Courier Service
  $(document).on('click', 'a.send_to_pathao', function(e){
        e.preventDefault();
    	var statusValue = $("input[name='status']:checked").val();
        var url = $(this).attr('href');
    	let link = $(this);
        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();
        
        if(order_ids.length ==0){
            toastr.error('Please Select Atleast One Order!');
            return ;
        }    
    	
        
    	
        
        $.ajax({
           type:'GET',
           url,
           data:{order_ids},
           beforeSend: function(){
             link.addClass('disable-click');
             link.text('Please wait...');
           },
           success:function(res){
               link.removeClass('disable-click');
               link.text('Send to Pathao');
               if(res.status){               
                toastr.success(res.msg);
                   
            }else{
                toastr.error(`Invoice :${res.invoice} something went wrong!`);
              	console.log(res.errors);
            }
           }
        });
    
    });

  //Steadfast Courier Service
  $(document).on('click', 'a.send_to_steadfast', function(e){
        e.preventDefault();
    	var statusValue = $("input[name='status']:checked").val();
        var url = $(this).attr('href');
    	let link = $(this);
        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();
        
        if(order_ids.length ==0){
            toastr.error('Please Select Atleast One Order!');
            return ;
        }        
    	
        // else if(statusValue != '0'){
        //     toastr.error('Only On The Way Orders are Accepted!');
        //     return ;
        // }
    	
        
        $.ajax({
           type:'GET',
           url,
           data:{order_ids},
           beforeSend: function(){
             link.addClass('disable-click');
             link.text('Please wait...');
           },
           success:function(res){
               link.removeClass('disable-click');
               link.text('Send to Steadfast');
               if(res.status){               
                toastr.success(res.msg);
                   
            }else{
                toastr.error(`Invoice :${res.invoice} something went wrong!`);
              	console.log(res.errors);
            }
           }
        });
    });
})
  
</script>
@endpush