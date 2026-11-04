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
                            <li class="axil-breadcrumb-item active" aria-current="page">My Account</li>
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
                            <h5 class="title mb-0">Hello Annie</h5>
                            <span class="joining-date">eTrade Member Since Sep 2020</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-md-4">
                        <aside class="axil-dashboard-aside">
                            <nav class="axil-dashboard-nav">
                                <div class="nav nav-tabs" role="tablist">
                                    <a class="nav-item nav-link" href="{{ route('front.dashboard.index')}}"><i class="fas fa-th-large"></i>Dashboard</a>
                                    <a class="nav-item nav-link" href="{{ route('front.orders.index')}}" ><i class="fas fa-shopping-basket"></i>Orders</a>
                                    <a class="nav-item nav-link active" href="{{ route('front.account_details.index')}}"><i class="fas fa-home"></i>Account Details</a>
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
                            <div class="axil-dashboard-account">
                                <form class="account-details-form" method="post" action="{{ route('front.account_details.update',[$user->id])}}" id="ajax_form">
                                    @csrf
                                    @method('PATCH')

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" class="form-control" value="{{ $user->first_name}}" name="first_name">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" class="form-control" value="{{ $user->last_name}}" name="last_name">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input type="text" class="form-control" value="{{ $user->username}}" name="username" readonly>
                                            </div>
                                        </div>


                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>E-mail Address</label>
                                                <input type="text" class="form-control" value="{{ $user->email}}" name="email">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Mobile Number</label>
                                                <input type="text" class="form-control" value="{{ $user->mobile}}" name="mobile">
                                            </div>
                                        </div>

                                        
                                        <div class="col-12">
                                            <h5 class="title">Password Change</h5>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" class="form-control" name="old_password">
                                            </div>
                                            <div class="form-group">
                                                <label>New Password</label>
                                                <input type="password" class="form-control" name="password">
                                            </div>
                                            <div class="form-group">
                                                <label>Confirm New Password</label>
                                                <input type="password" class="form-control" name="password_confirmation">
                                            </div>
                                            <div class="form-group mb--0">
                                                <input type="submit" class="axil-btn" value="Save Changes">
                                            </div>
                                        </div>
                                    </div>
                                </form>
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