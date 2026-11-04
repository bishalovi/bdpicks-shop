@php
use App\Models\Information;
use App\Models\Category;
  $information = Information::first();
  $categories = Category::where('parent_id', null)->where('is_menu', 1)->get();
@endphp

<style>

    .main-bg {
        background: {{ $information->gradient_code }} !important;
        border-color: rgba(32, 124, 202, 1);
        color: {{ $information->primary_color }} !important;
    }
    .main-bg:not(.bg_alt), .main-bg:not(.bg_alt) a, .main-bg:not(.bg_alt) p, .main-bg:not(.bg_alt) span {
        color: {{ $information->primary_color }} !important;
    }
    .main-bg .bg_alt *, .main-bg .bg_alt, .main-bg .bg_alt a, .main-bg .bg_alt p, .main-bg .bg_alt span{
        color: #000 !important;
    }
    .axil-product>.thumbnail .label-block .product-badget {
        padding: 6px 18px 5px;
    }
    
    @media only screen and (max-width: 991px) {
        .header-brand a img {
            max-height: 35px;
            width: 142px !important;
        }
        .brand_img {
            width: 60% !important;
        }
        .header_action {
            width: 21% !important;
        }
        .header-brand {
            text-align: center;
        }
    }
    @media only screen and (min-width: 767px) {
        .header-style-5 .axil-mainmenu {
             padding: 0px 0; 
        }
    }
    .header-action>ul>li>a>i{
        color: #000;
    }
    .mainmenu-nav a:hover {
        background-color: #f8f9fa;
        color: #0d6efd !important;
    }
    .mainmenu-nav .collapse a:hover {
        color: #0d6efd;
        padding-left: 8px;
        transition: all 0.2s ease-in-out;
    }
    .axil-mainmenu.axil-sticky{
        z-index: 9999;
    }
    @media only screen and (max-width: 767px) {
        i[class^="flaticon-"]:before, i[class*=" flaticon-"]:before {
            color: black;
            font-size: 24px;
        }
        .topbar{
            height: 35px;
        }
    }
    .cart-dropdown{
        z-index: 99999;
    }
    .container-fluid{
        max-width: 1600px;
        margin: 0 auto;
    }
    .cart-dropdown .cart-content-wrap{
        width: 420px;
        right: -420px;
    }
    @media(max-width: 769px){
        .cart-dropdown .cart-content-wrap{
            width: 320px;
            right: -320px;
        }
        .cart-dropdown{
            width: 320px;
            right: -320px;
        }
    }
</style>
<div class="topbar main-bg">
    <div class="container-fluid position-relative">
        <a href="tel:{{ $information->owner_phone }}" class="position-absolute text-dark d-none d-lg-block bg_alt" style="top: 0; padding: 0px 15px; z-index: 9; left: 0px; background: white; color: black !important; height: 100%; font-weight: bold; font-size: 22px; latter-spacing: 4px;"><i class="fas fa-headset"></i> {{ $information->owner_phone }}</a>
        <marquee class="fw-bold  py-1" style="font-family: 'Hind Siliguri', sans-serif;">{{ $information->topbar_notice }}</marquee>
    </div>
</div>
<header class="desktop header axil-header header-style-5">
        <!-- Start Mainmenu Area  -->
        <div id="axil-sticky-placeholder"></div>
        <div class="axil-mainmenu" style="background: #fff;">
            <div class="container-fluid" style="padding-top: 10px;">
                <div class="row header-navbar">
                    <div class="col-4 d-flex">
                        <!--<div>-->
                        <!--    <a href="{{ route('front.home')}}">-->
                        <!--        <img src="{{ asset('frontend/images/home_icon.png')}}" alt="Home" width="40" style="filter: brightness(0) invert(1);">-->
                        <!--    </a>-->
                        <!--</div>-->
                        <div class="header-brand" style="margin-left: 4%; height: 50px;display: flex; align-items: center;">
                            <a href="{{ route('front.home')}}" class="logo logo-dark" style="width: 200px;">
                                <img src="{{ asset('uploads/img/'.$information->site_logo)}}" alt="Site Logo" style="height: 50px; width: 200px;">
                            </a>
                            <a href="{{ route('front.home')}}" class="logo logo-light" style="width: 200px;">
                                <img src="{{ asset('uploads/img/'.$information->site_logo)}}" alt="Site Logo" style="height: 50px; width: 200px;">
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-4">
                        <form action="{{ route('front.products.index')}}">
                            <div class="header-top-dropdown dropdown-box-style">
                                
                                <div class="axil-search">
                                    <button type="submit" class="icon wooc-btn-search">
                                        <i class="far fa-search text-primary"></i>
                                    </button>
                                    <input type="search" class="placeholder product-search-input rounded-0 border border-primary" name="q" id="search2" value="{{ request('q')??''}}" maxlength="128" placeholder="What are you looking for...." autocomplete="off">
                                </div>
                                
                            </div>
                        </form>
                    </div> 
                    
                    <!--<div class="more-dp  col-3" style="text-align: center;">-->
                    <!--        <a href="" class=" more-btn" style="font-family: 'Hind Siliguri', sans-serif"> <i class="fas fa-truck"></i> Track Order</a>-->
                    <!--</div>-->
                    <div class="col-4">
                        <div class="header-action">
                            <ul class="action-list">
                                    
                                
                                <li class="shopping-cart">
                                    <a href="{{ route('front.carts.index')}}?segment={{request()->segment(1)}}"
                                    style="font-family: 'Hind Siliguri', sans-serif;"
                                    class="cart-dropdown-btn">
                                        <span class="cart-count" style="background: #00276C !important;font-family: 'Hind Siliguri', sans-serif">{{ getTotalCart()}}</span>
                                         <i class="flaticon-shopping-cart"></i> 
                                    </a>
                                    </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="desktopnav main-bg py-3" style="border-bottom: 1px solid #f2f2f2;">
                      <div class="container-fluid">
                          <div class="d-flex gap-1 justify-content-center">
                          @foreach($categories as $cat)
                                    <li class="nav-item">
                                    <a href="{{ route('front.subCategories1',[$cat->url])}}" class="nav-link  fw-bold">
                                        {{ $cat->name }}
                                        </a>
                                        @if($cat->subcats->count())
                                            <ul class="axil-submenu d-none">
                                               @foreach($cat->subcats as $sub)
                                              	<li><a href="{{ route('front.subsubCategories',[$sub->url])}}" class="fw-bold">{{ $sub->name}}</a></li>
                                                @endforeach
                                            </ul>
                                        @else
                                        @endif
                                </li>
                                @endforeach
                            </div>
                      </div>
            </div>
            
            
        </div>
        <!-- End Mainmenu Area -->
    </header>
    <!-- End Header -->

    
    <!-- Start Header -->
    <header class="mobile header axil-header header-style-5 bg-light" >
        <!-- Start Mainmenu Area  -->
        <div id="axil-sticky-placeholder"></div>
        <div class="axil-mainmenu" style="background-color: #fff;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-2">
                        <ul class="mainmenu">
                            <li class="axil-mobile-toggle mt-0">
                                <button class="menu-btn mobile-nav-toggler text-dark">
                                    <i class="fas fa-bars" style="color: black;"></i>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="col-4 brand_img">
                        <div class="header-brand">
                            <a href="{{ route('front.home')}}" class="logo logo-dark">
                                <img src="{{ asset('uploads/img/'.$information->site_logo)}}" alt="Site Logo">
                            </a>                            <a href="{{ route('front.home')}}" class="logo logo-light">
                                <img src="{{ asset('uploads/img/'.$information->site_logo)}}" alt="Site Logo">
                            </a>
                        </div>
                        
                    </div>
                    <div class="col-6 header_action">
                        <div class="header-action">
                            <ul class="action-list">
                                <li class="shopping-cart">
                                    <a href="{{ route('front.carts.index')}}?segment={{request()->segment(1)}}" class="cart-dropdown-btn">
                                        <span class="cart-count" style="background: #00276C !important;">{{ getTotalCart()}}</span>
                                        <i class="flaticon-shopping-cart" style="color: black;"></i>
                                    </a>
                                </li>
                                <!--<li class="my-account">-->
                                <!--    <a href=" {{ route('login')}} ">-->
                                <!--        Login-->
                                <!--    </a>-->
                                    
                                <!--</li>-->
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="header-navbar">
                    
                    <div class="header-main-nav">
                        <!-- Start Mainmanu Nav -->
                        <nav class="mainmenu-nav bg-white shadow-sm rounded-3 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <!-- Brand Logo -->
                                <a href="{{ route('front.home') }}" class="logo d-flex align-items-center text-decoration-none">
                                    <img src="{{ asset('uploads/img/'.$information->site_logo) }}" alt="Site Logo" class="me-2" style="height: 40px;">
                                </a>
                                <!-- User Info -->
                                <div class="my-account d-none">
                                    @guest
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">Sign In / Sign Up</a>
                                        <a href="{{ route('front.sellerRegister') }}" class="btn btn-primary btn-sm">Become a Seller</a>
                                    @else
                                        <a href="{{ route('front.dashboard.index') }}" class="btn btn-success btn-sm">
                                            {{ auth()->user()->first_name ? auth()->user()->first_name.' '.auth()->user()->last_name : auth()->user()->mobile }}
                                        </a>
                                    @endguest
                                </div>
                            </div>
                        
                            <!-- Categories Menu -->
                            <ul class="list-unstyled m-0" style="max-height: calc(100vh - 60px); overflow-y: auto;">
                                @foreach($categories as $key => $cat)
                                    <li class="mb-1 d-block">
                                        @if($cat->subcats->count() > 0)
                                            <button class="btn w-100 d-flex justify-content-between align-items-center text-start border-0 bg-light p-3 fs-2 rounded collapsed"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseCat_{{ $key }}"
                                                aria-expanded="false">
                                                <span>{{ $cat->name }}</span>
                                                <i class="fas fa-chevron-down small"></i>
                                            </button>
                        
                                            <div class="collapse mt-1" id="collapseCat_{{ $key }}">
                                                <ul class="list-unstyled ps-3">
                                                    @foreach($cat->subcats as $sub)
                                                        <li class="py-1">
                                                            <a href="{{ route('front.subsubCategories', [$sub->url]) }}" class="text-decoration-none text-secondary">
                                                                <i class="fas fa-angle-right me-1"></i>{{ $sub->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <a href="{{ route('front.subCategories1', [$cat->url]) }}" class="d-block p-3 bg-light rounded text-decoration-none text-dark hover-shadow-sm">
                                                {{ $cat->name }}
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                        <!-- End Mainmanu Nav -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Mainmenu Area -->
        <form action="{{ route('front.products.index')}}">
        <div class="mobilesearch col-12">
            <div class="header-top-dropdown dropdown-box-style">
                <div class="axil-search mt-lg-0 mt-2">
                    <button type="submit" class="icon wooc-btn-search">
                        <i class="far fa-search"></i>
                    </button>
                    <input type="search" class="placeholder product-search-input border border-info" name="q" id="search2" value="{{ request('q') ??''}}" maxlength="128" placeholder="What are you looking for...." autocomplete="off">
                </div>
            </div>
        </div>
        </form>
    </header>
    <!-- End Header -->
<a href="{{ route('front.carts.index')}}?segment={{request()->segment(1)}}" class="cart-dropdown-btn">
    <div class="fixed-cart-bottom">
        <p class="main-bg" style="border-top-left-radius: 12px;border-top-right-radius: 12px;">
            <i class="fas fa-shopping-cart" style="color: #ffffff !important;"></i>
        </p>
        <p class="main-bg cart-count" style="color: white;font-size: 10px;">
           @if(getTotalCart() > 1) {{ getTotalCart()}} items @else {{ getTotalCart()}} item @endif 
        </p>
        <p style="color: white;font-size: 10px;background: #00276C;border-bottom-left-radius: 12px;border-bottom-right-radius: 12px;" class="cart-amount">৳ {{ getTotalAmount() }}</p>
    </div>
</a>


@if($information->whats_active == '1') 
<a href="https://wa.me/+88{{ $information->whats_num }}" target="_blank" class="whats_btn">
    <span>
       <img width="60px" height="60px" src="https://img.icons8.com/color/48/whatsapp--v1.png" alt="whatsapp--v1"/>
<!--<img width="45" height="45" src="https://img.icons8.com/windows/45/whatsapp--v1.png" alt="whatsapp--v1"/>-->
       
    </span>
</a>
@endif


  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>