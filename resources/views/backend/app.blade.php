<!DOCTYPE html>
<html lang="en">
@php $info = \App\Models\Information::first(); @endphp
<head>
        <meta charset="utf-8" />
        <title>{{$info->site_name}} Admin Panel</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('uploads/img/'.$info->site_logo)}}">

        <!-- third party css -->
        <link href="{{ asset('backend/css/vendor/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />
        <!-- third party css end -->

        <!-- App css -->
        <link href="{{ asset('backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('backend/css/app-creative.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
        <style>
            @media print
            {    
                .no-print, .no-print *
                {
                    display: none !important;
                }
            }
          
          strong, th, td, h4, h5, .cl_manage, .form-label, .me-2 {
  	color: black !important;
  }
        </style>

  
  
        @stack('css')

    </head>

    <body class="loading" data-layout="detached" data-layout-color="light" data-rightbar-onstart="true">

        <!-- Topbar Start -->
        <div class="navbar-custom topnav-navbar topnav-navbar-dark">
            <div class="container-fluid">

                <!-- LOGO -->
                <a href="{{ route('admin.dashboard')}}" class="topnav-logo">
                    <span class="topnav-logo-lg">
                        <img src="{{ asset('images/logo1.png')}}" alt="" height="40">
                    </span>
                    <span class="topnav-logo-sm">
                        <img src="{{ asset('images/logo1.png')}}" alt="" height="16">
                    </span>
                </a>

                <ul class="list-unstyled topbar-menu float-end mb-0">

                    <li class="dropdown notification-list d-xl-none">
                        <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="dripicons-search noti-icon"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                            <form class="p-3">
                                <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                            </form>
                        </div>
                    </li>


                    <li class="notification-list">
                        <a class="nav-link" href="{{ route('front.home') }}" target="_blank">
                            <i class="dripicons-home noti-icon"></i>
                        </a>
                    </li>
                    <!--<li class="notification-list">-->
                    <!--    <a class="nav-link end-bar-toggle" href="javascript: void(0);">-->
                    <!--        <i class="dripicons-gear noti-icon"></i>-->
                    <!--    </a>-->
                    <!--</li>-->
    
                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" id="topbar-userdrop" href="#" role="button" aria-haspopup="true"
                            aria-expanded="false">
                            <span class="account-user-avatar"> 
                                <img src="{{ getImage('uploads/img', Auth::user()->image)}}" alt="user-image" class="rounded-circle">
                            </span>
                            <span>
                                <span class="account-user-name">{{ auth()->user()->first_name}}</span>
                               
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown" aria-labelledby="topbar-userdrop">
                            <!-- item-->
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>
    
                            <!-- item-->
                            <a href="{{ route('admin.profile') }}" class="dropdown-item notify-item">
                                <i class="mdi mdi-account-circle me-1"></i>
                                <span>My Account</span>
                            </a>                            
                            <!-- item-->
                            <a href="{{ route('admin.password') }}" class="dropdown-item notify-item">
                                <i class="mdi mdi-account-circle me-1"></i>
                                <span>Change Password</span>
                            </a>
    
    
                            @can('product.delete')
                            <!-- item-->
                            
                            <a href="{{ route('admin.settings.index') }}" class="dropdown-item notify-item">
                                <i class="mdi mdi-account-edit me-1"></i>
                                <span>Settings</span>
                            </a>
                            @endcan
    
                            <!-- item-->
                            <a class="dropdown-item notify-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                                                      
                                                      >
                                <i class="mdi mdi-logout me-1"></i>
                                <span>Logout</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
    
                        </div>
                    </li>

                </ul>
                <a class="button-menu-mobile disable-btn">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
            </div>
        </div>
        <!-- end Topbar -->
        
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- Begin page -->
            <div class="wrapper">

                <!-- ========== Left Sidebar Start ========== -->
                <div class="leftside-menu leftside-menu-detached" style="min-width: 220px !important;max-width: 220px !important;">

                    <div class="leftbar-user">
                        <a href="javascript: void(0);">
                            <img src="{{  getImage('uploads/img', \App\Models\Information::first()->site_logo)}}" alt="user-image" height="42" class="rounded-circle shadow-sm">
                            <span class="leftbar-user-name"> {{ auth()->user()->name}} </span>
                        </a>
                    </div>

                    <!--- Sidemenu -->
                    @include('backend.partials.navbar')

                    <!-- End Sidebar -->

                    <div class="clearfix"></div>
                    <!-- Sidebar -left -->

                </div>
                <!-- Left Sidebar End -->

                <div class="content-page">
                    <div class="content">
                        
                        @yield('content')
                    </div> <!-- End Content -->

                    <!-- Footer Start -->
                    <footer class="footer">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">

                                </div>
                                <!--<div class="col-md-6">-->
                                <!--    <div class="text-md-end footer-links d-none d-md-block">-->
                                <!--        <a href="javascript: void(0);" style="color: black;">About</a>-->
                                <!--        <a href="javascript: void(0);" style="color: black;">Support</a>-->
                                <!--        <a href="javascript: void(0);" style="color: black;">Contact Us</a>-->
                                <!--    </div>-->
                                <!--</div>-->
                            </div>
                        </div>
                    </footer>
                    <!-- end Footer -->

                </div> <!-- content-page -->

            </div> <!-- end wrapper-->
        </div>
        <!-- END Container -->


        <!-- Right Sidebar -->
        <div class="end-bar">

            <div class="rightbar-title">
                <a href="javascript:void(0);" class="end-bar-toggle float-end">
                    <i class="dripicons-cross noti-icon"></i>
                </a>
                <h5 class="m-0 text-light">Settings</h5>
            </div>

            <div class="rightbar-content h-100" data-simplebar>

                <div class="p-3">
             

                    <!-- Settings -->
                    <h5 class="mt-3">Color Scheme</h5>
                    <hr class="mt-1" />

                    <div class="form-check form-switch mb-1">
                        <input type="checkbox" class="form-check-input" name="color-scheme-mode" value="light"
                            id="light-mode-check" checked />
                        <label class="form-check-label" for="light-mode-check">Light Mode</label>
                    </div>

                    <div class="form-check form-switch mb-1">
                        <input type="checkbox" class="form-check-input" name="color-scheme-mode" value="dark"
                            id="dark-mode-check" />
                        <label class="form-check-label" for="dark-mode-check">Dark Mode</label>
                    </div>

                    <!-- Width -->
                    <h5 class="mt-4">Width</h5>
                    <hr class="mt-1" />
                    <div class="form-check form-switch mb-1">
                        <input type="checkbox" class="form-check-input" name="width" value="fluid" id="fluid-check" checked />
                        <label class="form-check-label" for="fluid-check">Fluid</label>
                    </div>
                    <div class="form-check form-switch mb-1">
                        <input type="checkbox" class="form-check-input" name="width" value="boxed" id="boxed-check" />
                        <label class="form-check-label" for="boxed-check">Boxed</label>
                    </div>

                </div> <!-- end padding-->

            </div>
        </div>
        <div class="modal fade" id="common_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"></div>

        <div class="rightbar-overlay"></div>
        <!-- /End-bar -->


        @include('backend.partials.js')

        <!-- demo app -->

        <!-- end demo js-->
        
    </body>

</html>
