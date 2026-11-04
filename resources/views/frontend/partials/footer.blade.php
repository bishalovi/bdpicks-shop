  <!-- Start Footer Area  -->
@php
use App\Models\Information;
$info = Information::first();

@endphp
<footer class="py-4 text-light border-top main-bg border-secondary">
    <div class="container">
        <div class="row align-items-center">
            <!-- Logo -->
            <div class="col-md-3 text-center text-md-start mb-3 mb-md-0">
                <img src="{{ asset('uploads/img/'.$info->site_logo)}}" class="img-fluid">
            </div>

            <!-- First nav -->
            <div class="col-md-6 mb-3 mb-md-0">
                <p class="text-center h4">Popular Categories</p>
                <nav class="d-flex flex-wrap justify-content-center gap-3">
                    @foreach(DB::table('categories')->where('is_popular', 1)->take(5)->get() as $cat)
                        <a href="{{ route('front.subCategories1',[$cat->url])}}" class="text-decoration-none text-light hover-text-primary">{{ $cat->name}}</a>
                    @endforeach
                </nav>
            </div>

            <!-- Copyright -->
            <div class="col-md-3 text-center text-md-end d-none d-lg-block">
                <div class="small">{!! $info->copyright !!} <a href="https://www.facebook.com/bizcareit" target="_blank" rel="noopener noreferrer">
  Design and Development By Biz Care IT
</a></div>
            </div>
        </div>

        <!-- Second row for another nav -->
        <div class="row mt-3">
            <div class="col text-center">
                <p class="text-center h4">Legal Pages</p>
                <nav class="d-flex flex-wrap justify-content-center gap-4">
                    @foreach(DB::table('pages')->take(5)->get() as $key => $page)
                        <a href="{{ route('front.page.name', $page->page)}}" class="text-decoration-none text-light hover-text-primary">{{ $page->title }}</a>
                    @endforeach
                </nav>
            </div>
        </div>
        <div class="col-md-3 mt-5 text-center text-md-end d-block d-lg-none">
                <div class="small">{!! $info->copyright !!}<a href="https://www.facebook.com/bizcareit" target="_blank" rel="noopener noreferrer">
  Design and Development By Biz Care IT
</a></div>
            </div>
    </div>
    <div class="d-lg-none d-block p-5"></div>
</footer>
<footer class="main-bg d-none">
<style>
.footerWidget {
    padding: 30px 20px;
    color: white;
}
.footerWidget *{
    color: white;
}
.marginRight {
    border-right: 1px solid white;
}
ul.contact {
    margin: 0;
    padding: 0;
    list-style: none;
}
ul.contact li {
    padding: 3px 0;
    margin: 0;
}
ul.contact li span {
    width: 100px;
    display: inline-block;
    white-space: nowrap;
}
ul.contact li span i {
    margin-right: 5px;
    font-size: 20px;
}
    ul.quickLink {
        list-style: none;
        display: flex;
        flex-direction: column;
        margin-bottom: 10px;
    }
    
    ul.quickLink li {
        margin: 2px;
    }
    
    ul.quickLink li a {
        color: white;
    }
    
    ul.quickLink li a:hover {
        color: #ef6600;
    }
    
    @media(max-width: 569px){
        ul.contact li span {
            width: 75px;
            font-size: 13px;
            display: inline-block;
        }
        ul.contact li span i {
            margin-right: 8px;
            font-size: 18px;
        }
        ul.contact li{
            font-size: 14px;
        }
    }
    
    
    #fb-root .fb_dialog .fb_dialog_content iframe {
        background: none;
    border-radius: 60px;
    bottom: 24px;
    box-shadow: rgba(0, 0, 0, 0.15) 0px 4px 12px 0px;
    display: block;
    height: 60px;
    margin: 0px 12px;
    overflow: visible;
    padding: 0px;
    position: fixed;
    left: 23px !important;
    top: 67% !important;
    width: 60px;
    z-index: 2147483644;
    }
    
@media (min-width: 320px) and (max-width: 768px) {
    .marginRight{
        border-right: none;
    }
    .top_socials{
        text-align: center;
        display: grid;
        width: 100% !important;
    }
    footer .socials {
        margin-top: 0px;
        margin-bottom: 13px;
    } 
    .addresss{
        width: 100% !important;
        text-align: center;
    }
    .footer-nav {
        display: block !important;
    }
}

</style>
    <div class="footerWidget">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 mb-3 marginRight">
                    <div class="widget">
                        <h5 class="mb-4">
                           <i class="fa fa-home"></i> HEAD OFFICE
                        </h5>
                        <ul class="contact ps-lg-4 ps-3">
                            <li><span><i class="fa fa-map-marker"></i>  Address:</span> {{ $info->address }}</li>
                            <li><span><i class="fa fa-phone"></i>  Hotline:</span> {{ $info->owner_phone }}</li>
                            <li><span><i class="fa fa-envelope"></i>  E-mail:</span> {{ $info->owner_email }}</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 mb-3 marginRight">
                    <div class="widget" style="">
                        <h5 class="mb-4">
                           <i class="fa fa-book"></i> PAGE
                        </h5>
                        <ul class="quickLink ps-lg-4 ps-3">
                            <li><a href="{{ route('front.aboutUs')}}">About Ecom</a></li>
                            <li><a href="{{ route('front.privacyPolicy')}}">Privacy policy</a></li>
                            <li><a href="{{ route('front.termCondition')}}">Terms &amp; Condition</a></li>
                            <li><a href="{{ route('front.returnPolicy')}}">Return policy</a></li>
                        </ul>
                        <h5 class="my-4">
                           <i class="fas fa-star"></i> FOLLOW US
                        </h5>
                        <div class="socials ps-3 mb-4" style="margin: 0;">
                            @if(!empty($info->facebook))
                                <a href="{{ $info->facebook }}" target="_blank" class="btn btn-sm btn-primary me-2 fs-3" title="Facebook">
                                    <i class="fab fa-facebook"></i>
                                </a>
                            @endif
                        
                            @if(!empty($info->youtube))
                                <a href="{{ $info->youtube }}" target="_blank" class="btn btn-sm btn-danger me-2 fs-3" title="Youtube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            @endif
                        
                            @if(!empty($info->instagram))
                                <a href="{{ $info->instagram }}" target="_blank" class="btn btn-sm btn-danger me-2 fs-3" title="Instagram" style="background: #E1306C; border-color: #E1306C;">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                        
                            @if(!empty($info->tiktok))
                                <a href="{{ $info->tiktok }}" target="_blank" class="btn btn-sm btn-dark me-2 fs-3 fw-bold px-3" title="Tiktok">
                                    ♪
                                </a>
                            @endif
                        
                            @if(!empty($info->twitter))
                                <a href="{{ $info->twitter }}" target="_blank" class="btn btn-sm btn-dark me-2 fs-3 px-3" title="Twitter">
                                    <span style="font-family: sans-serif;">𝕏</span>
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="widget d-none">
                        <img src="{{ asset('uploads/img/'.$info->site_logo)}}" style="max-width:100%;margin-bottom:20px;">
                    </div>
                    <div class="widget" style="">
                        <h5 class="mb-4">
                           <i class="fa fa-book"></i> Popular Categories
                        </h5>
                        <ul class="quickLink ps-lg-4 ps-3">
                            @foreach(DB::table('categories')->whereNull('parent_id')->take(5)->get() as $cat)
                                <li><a href="{{ route('front.products.index')}}?category_id={{$cat->id}}">{{ $cat->name}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="copyRight text-dark" style="background: #fff;text-align: center;">
            <div class="container-fluid">
                <p class="m-0" style="padding: 5px 0;">
                    {!! $info->copyright !!} <a style="color: #000;font-family: 'Hind Siliguri', sans-serif" href="https://softitglobal.com/">SoftitGlobal.com</a>
                </p>
            </div>
        </div>
</footer>
<style>
    .first_div_seperate{
        display: none;    
    }
    
    @media (max-width: 575px) {
        .footer-nav {
            display: block !important;
        }
        .first_div_seperate{
            display: block !important;
        }
    }

    .footer-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #ffffff;
        display: none;
        z-index: 99;
    }
    
    .m-nav-main {
        display: flex;
    }
    
    .m-nav-main .button-shop {
        flex-grow: 1;
        padding: 10px;
        padding-bottom: 5px;
        color: white;
        font-size: 15px;
        font-weight: bold;
        text-align: center;
    }
    
    .quick-link {
        padding-bottom: 45px !important;
    }
    
    #fb-root .fb_dialog .fb_dialog_content iframe {
        background: none;
    border-radius: 60px;
    bottom: 24px;
    box-shadow: rgba(0, 0, 0, 0.15) 0px 4px 12px 0px;
    display: block;
    height: 60px;
    margin: 0px 12px;
    overflow: visible;
    padding: 0px;
    position: fixed;
    left: 23px !important;
    top: 67% !important;
    width: 60px;
    z-index: 2147483644;
    }
    .button-shop a.footerBtn i {
        font-size: 25px;
        display: grid;
        text-align: center;
        color: #1e65b2;
    }
</style>
 
<div class="footer-nav">
     <div class="m-nav-main">
        <div class="button-shop">
             <a href="{{ route('front.home')}}" class="text-light footerBtn">
                 <i class="fa fa-home"></i>
                 <!--<img src="{{ asset('frontend/images/my_home.png') }}" style="height: 28px;display: block; margin: auto;">-->
                <span style="color: #1e65b2;">Home</span> 
             </a>
         </div>
         
         <div class="button-shop">
             
             <a href="#" class="text-light footerBtn mobile-nav-toggler">
                 <i class="fa fa-bars"></i>
                 <!--<img width="28" height="28" style="display: block;margin:0 auto;" src="https://img.icons8.com/color/48/facebook-new.png" alt="facebook-new"/>-->
                 <span style="color: #1e65b2;">Categories</span>
            </a>
            
         </div>
         
         <div class="button-shop">
            <a href="https://wa.me/+88{{ $info->owner_phone }}" class="text-light footerBtn">
                <i class="fab fa-whatsapp"></i>
                 <!--<img src="{{ asset('frontend/images/phne.jpg') }}" style="height: 28px;display: block; margin: auto;">-->
                 <span style="color: #1e65b2;">WhatsApp</span>
             </a>
         </div>
         
         <div class="button-shop">
             <a href="tel:{{ $info->owner_phone }}" class="text-light footerBtn">
                 <i class="fa fa-phone-volume"></i>
                 <!--<img src="{{ asset('frontend/images/whatsapp.jpg') }}" style="height: 28px;display: block; margin: auto;">-->
               <span style="color: #1e65b2;">Call</span>
             </a>
         </div>
     </div>
 </div>
 
<div class="cart-dropdown" id="cart-dropdown">
    
</div>

    
@include('frontend.partials.js')

</body>

<html>