@extends('backend.app')
@section('content')

<style type="text/css" media="print">
  nav{
     display: none;
  }
  
  table tbody td{
  		font-size: 12px !important;
  }
  
</style>  

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">SIS</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">CRM</a></li>
                    <li class="breadcrumb-item active">User report</li>
                </ol>
            </div>
            <h4 class="page-title">User Report</h4> 
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                 <div class="row mb-2">
                    <div class="col-md-12 no-print">
                        <form class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between" id="order_report_form" method="GET" action="{{ route('admin.report.order.search') }}">
                            @csrf
                                                       
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <label for="status-select" class="me-2">Assign By</label>
                                    <select class="form-select" id="assign" name="assign">
                                        <option selected value="">Choose...</option>
                                        @foreach($users as $user)
                                        <option value="{{$user->id}}" >{{full_name($user)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                                
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <label for="status-select" class="me-2">From:</label>
                                    <input type="date" name="from" id="from" class="form-control"/>
                                </div>
                            </div>                            
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <label for="status-select" class="me-2">To:</label>
                                    <input type="date" name="to" id="to" class="form-control"/>
                                </div>
                            </div>
                        </form>                            
                    </div>
                    <div align="right" class="no-print d-none">
                                      <a class="btn btn-dark" href="{{ route('admin.report.order.export', [
                                                      'query'=>request('query'),
                                                      'status'=>request('status'),
                                                      'assign'=>request('assign'),
                                                      'from'=>request('from'),
                                                      'to'=>request('to'),
                                                      'courier'=>request('courier')
                                                      ]) }}">Export</a>
                        <button class="btn btn-info print-btn">Print</button>
                    </div>
              <div class="col-sm-12 userReport">
                
              </div>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection 

@push('js')
<script src="{{ asset('backend/js/order.js')}}"></script>
<script>
$(document).ready(function(){
    
    getuserReport();
    
    $(".print-btn").click(function(){
        print();
    })    

});

$('input[name="from"]').on('change',function(){
    getuserReport();
});

$('input[name="to"]').on('change',function(){
    getuserReport();
});

$('select[name="assign"]').on('change',function(){
    getuserReport();
});

function getuserReport(){
    let startDate=$('input[name="from"]').val();
    let endDate=$('input[name="to"]').val();
    let assignUser=$('select[name="assign"]').val();
    
    $.ajax({
        url:"{{route('admin.report.user')}}",
        method:"GET",
        data:{startDate,endDate,assignUser},
        success:function(res){
            if(res.success){
                $('div.userReport').html(res.html);                
            }
        }
    });
}
  
</script>
@endpush