<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #0EA3AC;">
            <a href="{{url('admin/products')}}">
                <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="Campaign Sent"> Total Products </h5>
                        <h3 class="my-2 py-1 text-light">{{ number_format($products,0)}}</h3>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #F5BD08;">
            <a href="{{url('admin/users')}}">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-dark fw-normal mt-0 text-truncate" title="Deals">Total Employee</h5>
                        <h3 class="my-2 py-1 text-dark">{{ number_format($users,0)}}</h3>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
            </a>
        </div> <!-- end card -->
    </div> <!-- end col -->
    
    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #133F5C;">
            <a href="{{ route('admin.settings.index') }}">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="Booked Revenue">Site Info</h5>
                      	<h3 class="my-2 py-1 text-light">Settings</h3>
                        
                    </div>
                    
                </div> <!-- end row-->
            </div> <!-- end card-body -->
            </a>
        </div> <!-- end card -->
    </div> 
    
    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #EB5F5E;">
    <a href="{{ url('admin/order-report') }}">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="Booked Revenue">Report</h5>
                      	<h3 class="my-2 py-1 text-light">View Report</h3>
                        
                    </div>
                    
                </div> <!-- end row-->
            </div> <!-- end card-body -->
            </a>
        </div> <!-- end card -->
    </div>

    <div class="col-md-6 col-xl-3 d-none">
        <div class="card" style="background-color: #C9213B;">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="Booked Revenue">Today Sell</h5>
                      	<h3 class="my-2 py-1 text-light">৳ {{ number_format($today_sell,2)}}</h3>
                        
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<!-- end row -->

<div class="row d-none">
    
    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #133F5C;">
            <a href="{{ route('admin.settings.index') }}">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="Booked Revenue">Site Info</h5>
                      	<h3 class="my-2 py-1 text-light">Settings</h3>
                        
                    </div>
                    
                </div> <!-- end row-->
            </div> <!-- end card-body -->
            </a>
        </div> <!-- end card -->
    </div> <!-- end col -->
            <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #EB5F5E;">
    <a href="{{ url('admin/order-report') }}">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="Booked Revenue">Report</h5>
                      	<h3 class="my-2 py-1 text-light">View Report</h3>
                        
                    </div>
                    
                </div> <!-- end row-->
            </div> <!-- end card-body -->
            </a>
        </div> <!-- end card -->
    </div> <!-- end col -->
    </a>
    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #893CFB;">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="Booked Revenue">This Month Revenue</h5>
                      	<h3 class="my-2 py-1 text-light">৳ {{ number_format($current_month_sell,2)}}</h3>
                        
                    </div>
                    
                </div> <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
    <div class="col-md-6 col-xl-3">
        <div class="card" style="background-color: #893CFB;">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h5 class="text-light fw-normal mt-0 text-truncate" title="Booked Revenue">Last Month Revenue</h5>
                      	<h3 class="my-2 py-1 text-light">৳ {{ number_format($prev_month_sell,2)}}</h3>
                        
                    </div>
                    
                </div> <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
  
    <!-- end col-->
</div>
<!-- end row-->