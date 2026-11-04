@extends('frontend.app')
@section('content')
<main class="main-wrapper">
    <!-- Start Breadcrumb Area  -->
    <div class="axil-breadcrumb-area">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-8">
                    <div class="inner">
                        <ul class="axil-breadcrumb">
                            <li class="axil-breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="separator"></li>
                            <li class="axil-breadcrumb-item active" aria-current="page">My Dashboard</li>
                        </ul>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <!-- End Breadcrumb Area  -->

    <!-- Start My Account Area  -->
    <div class="axil-dashboard-area">
        <div class="container-fluid">
            <div class="axil-dashboard-warp p-5">
                <div class="axil-dashboard-author">
                    <div class="media">
                        
                        <div class="media-body">
                            <h5 class="title mb-0">Hello {{ auth()->user()->first_name.' '.auth()->user()->last_name}}</h5>
                            <span class="joining-date">eK2 Member Since {{ date('M Y', strtotime(auth()->user()->created_at))}}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-md-4">
                        <aside class="axil-dashboard-aside">
                            <nav class="axil-dashboard-nav">
                                <div class="nav nav-tabs" role="tablist">
                                    <a class="nav-item nav-link active" href="{{ route('front.dashboard.index')}}"><i class="fas fa-th-large"></i>Dashboard</a>
                                    <a class="nav-item nav-link" href="{{ route('front.orders.index')}}" ><i class="fas fa-shopping-basket"></i>Orders</a>
                                    <a class="nav-item nav-link" href="{{ route('front.account_details.index')}}"><i class="fas fa-home"></i>Account Details</a>
                                    <a class="nav-item nav-link" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fal fa-sign-out"></i>Logout</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                </div>
                            </nav>
                        </aside>
                    </div>
                    <div class="col-xl-9 col-md-8">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="nav-dashboard" role="tabpanel">
                                <div class="axil-dashboard-overview">
                                    <div class="welcome-text">Hello {{ auth()->user()->first_name}}</div>
                                    <p>From your account dashboard you can view your recent orders, manage your shipping and billing addresses, and edit your password and account details.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End My Account Area  -->
</main>



@endsection