@extends('backend.app')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Layouts</a></li>
                    <li class="breadcrumb-item active">Detached Sidenav</li>
                </ol>
            </div>
            <h4 class="page-title">Hello  {{ auth()->user()->first_name.' '.auth()->user()->last_name}}</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 
<div class="row d-none" id="loader">
    <div class="col-12">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
</div> 

<div id="dashboard_data_top">
    
    <?php
    // Get today's date
    $today = date('Y-m-d');
    
    // Calculate end date by adding one day to today's date
    $endDate = date('Y-m-d', strtotime($today . ' +1 day'));
    ?>

<form action="{{ route('admin.dashboard')}}" method="GET">
<div class="row">
    <div class="col-md-4 mb-3">
          <label for="validationDefault01">Start Date</label>
          <input type="date" class="form-control" name="startDate" value="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="col-md-4 mb-3">
          <label for="validationDefault01">End Date</label>
          <input type="date" class="form-control" name="endDate" value="{{ $endDate }}">
        </div>
</div>
</form>
<div class="row">
    
    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #0EA3AC;">
            <a href="{{url('admin/orders')}}">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="New Leads"> Total Order</h5>
                        <h3 class="my-2 py-1 text-light total_orders"></h3>
                       
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <div id="new-leads-chart" data-colors="#42d29d"></div>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
            </a>
        </div> <!-- end card -->
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #2DAC37;">
            <a href="{{url('admin/orders')}}">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="New Leads"> Pending Order</h5>
                        <h3 class="my-2 py-1 text-light pending_orders"></h3>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
            </a>
        </div> <!-- end card -->
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #F5BD08;">
            <a href="{{url('admin/orders')}}">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="New Leads"> Complete Order</h5>
                        <h3 class="my-2 py-1 text-light complete_orders"></h3>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
            </a>
        </div> <!-- end card -->
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #C9213B;">
            <a href="{{url('admin/orders')}}">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="New Leads"> Cancel Order</h5>
                        <h3 class="my-2 py-1 text-light cancell_orders"></h3>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
            </a>
        </div> <!-- end card -->
    </div>
    
    
    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #0EA3AC;">
            <a href="{{url('admin/products')}}">
                <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="Campaign Sent"> Sell Amount </h5>
                        <h3 class="my-2 py-1 text-light sell_amount"></h3>
                        
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
            </a>
        </div> <!-- end card -->
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #0EA3AC;">
            <a href="{{url('admin/products')}}">
                <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="Campaign Sent"> Expense </h5>
                        <h3 class="my-2 py-1 text-light total_expense"></h3>
                        
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
            </a>
        </div> <!-- end card -->
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #2DAC37;">
            <a href="{{url('admin/orders')}}">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="New Leads"> Net Profit</h5>
                        <h3 class="my-2 py-1 text-light total_net_profit"></h3>
                       
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
            </a>
        </div> <!-- end card -->
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #0EA3AC;">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="Booked Revenue">Current Stocks</h5>
                      	<h3 class="my-2 py-1 text-light">{{ $total_stocks }}</h3>
                        
                    </div>
                    
                </div> <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
</div>

<div id="dashboard_data">

</div>

<style>
    .btn.btn-sm{
        font-size: 12px;
    }
</style>



<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
              
              
              <style>
                table tr th,td {
                  color: black;
                }
                
              </style>  
              
            {{-- <div class="row">
                <div class="col-lg-6 col-sm-6">
                <h3>All Time Best Seller</h3>
                <div class="table-responsive">
                    <table class="table table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>SL</th>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bestsellerProducts as $key=>$item)
                            <tr>
                              	
                                <td>{{ $key+1 }}</td>
                                <td>
                                    <img src="{{ getImage('thumb_products',$item->image)}}" class="rounded-circle avatar-xs" alt="friend">
                                </td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->qty}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                  	 </div>
                </div>
                <div class="col-lg-6 col-sm-6">
                <h3>This Month Best Seller</h3>    
                <div class="table-responsive">
                    <table class="table table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>SL</th>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($thismonthbestsellerProducts as $key=>$item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>
                                    <img src="{{ getImage('thumb_products',$item->image)}}" class="rounded-circle avatar-xs" alt="friend">
                                </td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->qty}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                  	 </div>
                </div>
            </div>    --}}
              
            </div> 
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->




@endsection

@push('js')

<script src="{{ asset('backend/js/order.js')}}"></script>
<script>
$(document).ready(function(){

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

})
  
</script>

<script>
	
$(document).ready(function(){
    getDashboardData();
    getDashboardData2();
});

$('input[name="startDate"]').on('change',function(){
    getDashboardData2();
});

$('input[name="endDate"]').on('change',function(){
    getDashboardData2();
});

function getDashboardData2(){
    let url="{{ route('admin.getDashboardData2')}}";
    let startDate=$('input[name="startDate"]').val();
    let endDate=$('input[name="endDate"]').val();
    
  	$.ajax({
      type: 'GET',
      url: url,
      data: {startDate,endDate},
      success: function(res) {
           $('h3.total_net_profit').text(res.profit);
           $('h3.total_expense').text(res.totalExpense);
           $('h3.total_orders').text(res.total_orders);
           $('h3.pending_orders').text(res.pending_orders);
           $('h3.complete_orders').text(res.complete_orders);
           $('h3.cancell_orders').text(res.cancell_orders);
           $('h3.sell_amount').text(res.sell_amount);
      }
  	});
}

function getDashboardData(){
    let url="{{ route('admin.getDashboardData')}}";
  	$.ajax({
      type: 'GET',
      url: url,
      data: {},
      beforeSend: function() {
			$('#loader').show();
      },
      success: function(res) {
			$('#dashboard_data').html(res);
      },
      error: function(xhr) { // if error occured

      },
      complete: function() {
			$('#loader').hide();
      },
      dataType: 'html'
  	});
}

</script>
@endpush
                        