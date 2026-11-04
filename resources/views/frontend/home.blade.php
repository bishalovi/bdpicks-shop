@extends('frontend.app')
@section('content')

<style>

    .popular_product {
    align-items: center;
    display: flex;
    flex-flow: row wrap;
    justify-content: space-between;
    position: relative;
    width: 100%;
    margin-top: 5%;
    margin-bottom: 2%;
    color: #014F00;
}

.popular_product b {
    background-color: currentColor;
    display: block;
    flex: 1;
    height: 2px;
    opacity: .1;
}

.popular_product span {
    font-size: 24px;
    font-family: 'Hind Siliguri', sans-serif;
    margin-bottom: 0px;
    color: #00276C;
    font-weight: 800;
}

    .slide-arrow {
        position: absolute !important;
        z-index: 9999 !important;
        top: 50% !important;
        width: 50px !important;
    }
    .slick-arrow i {
    font-size: 20px;
    color: black;
}

.slick-slider .prev-arrow {
    left: 20px !important;
}

.slick-slider .next-arrow {
    right: 20px !important;
}

    @media only screen and (min-width: 320px) and (max-width: 375px) {
        .cat-image {
            height: 90px !important;
            width: 90px !important;
        }
        .cat-image a {
            padding: 0px !important;
            font-size: 80% !important;
        }
        .slider_cat {
            padding-left: 5px !important;
            padding-right: 5px !important;
        }
    }
    
    @media only screen and (min-width: 376px) and (max-width: 470px) {
        .cat-image {
            height: 103px !important;
            width: 103px !important;
        }
        .cat-image a {
            padding: 0px !important;
            font-size: 80% !important;
        }
        .slider_cat {
            padding-left: 5px !important;
            padding-right: 5px !important;
        }
    }

    @media only screen and (min-width: 1000px) and (max-width: 1101px) {
        .cat-image {
            height: 90px !important;
            width: 90px !important;
        }
        .cat-image a {
            padding: 0px !important;
        }
        .cat-image .title_cat{
            font-size: 80% !important;
        }
    }
    
    @media only screen and (min-width: 1102px) and (max-width: 1200px) {
        .cat-image {
            height: 100px !important;
            width: 100px !important;
        }
        .cat-image a {
            padding: 0px !important;
        }
        .cat-image .title_cat{
            font-size: 80% !important;
        }
    }
    
    @media only screen and (min-width: 1201px) and (max-width: 1300px) {
        .cat-image {
            height: 112px !important;
            width: 112px !important;
        }
        .cat-image a {
            padding: 0px !important;
        }
        .cat-image .title_cat{
            font-size: 80% !important;
        }
    }
    
    @media only screen and (min-width: 1301px) and (max-width: 1400px) {
        .cat-image {
            height: 122px !important;
            width: 122px !important;
        }
        .cat-image a {
            padding: 0px !important;
        }
        .cat-image .title_cat{
            font-size: 80% !important;
        }
    }
    
    @media only screen and (min-width: 1401px) and (max-width: 1500px) {
        .cat-image {
            height: 131px !important;
            width: 131px !important;
        }
        .cat-image a {
            padding: 0px !important;
        }
        .cat-image .title_cat{
            font-size: 80% !important;
        }
    }
    
    @media only screen and (min-width: 1501px) and (max-width: 1600px) {
        .cat-image {
            height: 142px !important;
            width: 142px !important;
        }
        .cat-image a {
            padding: 0px !important;
        }
        .cat-image .title_cat{
            font-size: 80% !important;
        }
    }
    
    @media only screen and (min-width: 1601px) and (max-width: 2700px) {
        .cat-image {
            height: 145px !important;
            width: 145px !important;
        }
        .cat-image a {
            padding: 0px !important;
        }
        .cat-image .title_cat{
            font-size: 80% !important;
        }
    }

     @media only screen and (max-width: 768px) {
         .cat-image img{
             display: block !important;
         }
     }

    @media only screen and (min-width: 768px) {
        .row>[class*=col] {
            padding-left: 5px !important;
            padding-right: 5px !important;
        }
    }
    
    @media only screen and (max-width: 767px) {
     .row>[class*=col] {
    padding-left: 5px !important;
    padding-right: 5px !important;
}

.view_all a {
    margin-right: 10% !important;
}

.view_left a > h4 {
    margin-top: 3px !important;
}

    }
    
    .cat-image {
    height: 128px;
    width: 128px;
    border-radius: 15px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    padding: 2px;
    background: #ffe8c5;
    transition: 0.3s;
    margin-bottom: 10px;
}

.mainmenu .img-fluid {
    width: 50px;
    height: 50px !important;
    border-radius: 10px;
    transition: .5s ease-in-out !important;
    border: none !important;
}

.cat-image:hover {
    color: white;
    background: #033199;
}

.cat-image:hover a {
    color: white;
}

.cat-image a {
    padding: 6px;
    font-weight: 700;
}

.carousel-inner {
    box-shadow: 1px 1px 10px 0px #00276C !important;
    border-radius: 7px;
}

@media (max-width: 380px)
.header_navbar .mainmenu-nav {
    padding: 0px;
}

@media (max-width: 992px) {
    .header_navbar .mainmenu-nav {
        display: block;
        position: static;
        top: 0;
        bottom: 0;
        width: 100%;
        /* background-color: var(--color-white); */
        z-index: 100;
        transition: all .3s ease-in-out;
        padding: 20px 30px 10px;
        visibility: visible;
        opacity: 1;
        gap: 10px;
    }
}

@media only screen and (max-width: 767px) {
    .header-main-nav .mainmenu-nav {
        right: none;
        left: -250px;
        padding: 0px;
    }
}

@media (max-width: 992px) {
    .header_navbar .mainmenu-nav .mainmenu {
        gap: 10px;
    }
}


@media (max-width: 992px) {
    .header_navbar .mainmenu-nav .mainmenu {
        display: flex;
        height: 100%;
        overflow-y: hidden;
        margin: 0;
    }  
}

@media only screen and (max-width: 767px) {
    .header-main-nav .mainmenu-nav .mainmenu {
        padding: 10px;
        padding-top: 0;
        padding-bottom: 20px;
    }
}

@media (max-width: 575px) {
    .header_navbar .mainmenu-nav .mainmenu>li {
        width: 30%;
    }
}


@media (max-width: 992px) {
    .header_navbar .mainmenu-nav .mainmenu>li {
        margin: 0px 0 !important;
        transform: translateY(20px);
        opacity: 1;
        transition: all .3s ease-in-out;
        padding-top: 0;
    }
}




</style>

<!-- Start Categorie Area  -->
<!--<div class="category-mobileview axil-categorie-area bg-color-white">-->
<!--    <div class="container-fluid">-->
<!--        <div class="page-card card-channel" data-module-id="channels" data-spm="icons" style="">-->
<!--          <ul class="card-content channel-content">-->
              <!--<li class="channel-item">-->
              <!--    <a href="{{ route('front.categories')}}" class="channel-redirect" data-spm="1">-->
              <!--        <div class="channel-icon">-->
              <!--            <div class="img-wrap">-->
              <!--                <img src="{{asset('images/category.png')}}" alt="Mart" />-->
              <!--            </div>-->
              <!--        </div>-->
              <!--        <div class="channel-name">-->
              <!--            <div class="text-wrap text-top">Categories</div>-->
              <!--        </div>-->
              <!--    </a>-->
              <!--</li>-->
<!--            <div class="d-flex justify-content-between col-12 category-slider">-->
<!--            	@foreach($cats as $cat)-->

<!--              <li class="channel-item">-->
<!--                  <a href="{{ route('front.subCategories1',[$cat->url])}}">-->
<!--                      <div class="channel-icon">-->
<!--                          <div class="img-wrap">-->
<!--                              <img src="{{ getImage('categories', $cat->image) }}" alt="{{ $cat->name}}" />-->
<!--                          </div>-->
<!--                      </div>-->
<!--                      <div class="channel-name">-->
<!--                          <div class="text-wrap text-top">{{ $cat->name}}</div>-->
<!--                      </div>-->
<!--                  </a>-->
<!--              </li>-->
<!--            @endforeach-->
<!--            </div>-->

             
<!--          </ul>-->
<!--      </div>-->
<!--    </div>-->
<!--</div>-->
<!-- End Categorie Area  -->  

<!--<div class="desktop home-menu">-->
<!--    <div class="container-fluid-fluid">-->
<!--        <div class="header-navbar">-->
<!--            <div class="header-main-nav" style="box-shadow: 2px 5px 7px 0px #808080a6; margin-bottom: 30px;">-->
                <!-- Start Mainmanu Nav -->
<!--                <nav class="mainmenu-nav pe-5">-->
                 
<!--                    <ul class="mainmenu slick-mainmenu ">                      -->
                      
<!--                        @foreach($cats as $cat)-->
<!--                        <li class="{{ $cat->subcats->count() >0? 'menu-item-has-children':'' }}">-->
<!--                            <div class="border border-muted cat-image">-->
<!--                            <a href="{{ route('front.subCategories1',[$cat->url])}}">-->
<!--                                <img class="img-fluid" src="{{ getImage('categories', $cat->image) }}" alt="{{ $cat->name}}">-->
<!--                            </a>-->
<!--                            </div>-->
<!--                            <a href="{{ route('front.subCategories1',[$cat->url])}}">{{ $cat->name}}</a>-->
<!--                            @if($cat->subcats->count())-->
<!--                            <ul class="axil-submenu">-->
<!--                                @foreach($cat->subcats as $sub)-->
<!--                                <li><a href="{{ route('front.subsubCategories',[$sub->url])}}">{{ $sub->name}}</a></li>-->
<!--                                @endforeach-->
<!--                            </ul>-->
<!--                            @endif-->
<!--                        </li>-->
<!--                        @endforeach                  -->
                       
<!--                    </ul>-->
<!--                </nav>-->
                <!-- End Mainmanu Nav -->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!-- End Mainmenu Area -->
        
<main class="main-wrapper"> 

<div class="desktop-slide slider axil-main-slider-area main-slider-style-2" style="margin-top: 15px;">
    <div class="container-fluid" style="margin-bottom: 15px;">
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000"> 
            <div class="carousel-inner">
                @foreach($sliders as $key=>$s)
                <div class="carousel-item  {{ $key==0 ?'active':''}}">
                  	<a href="{{$s->link}}">
                    	<img src="{{ getImage('sliders', $s->image) }}" class="d-block w-100" alt="..." />
                  	</a>
                </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>
<!-- Start Mobile Slider Area -->
<div class="mobile-slide slider axil-main-slider-area main-slider-style-2" style="padding-top: 5px;">
    <div class="container-fluid">
        <div id="McarouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($sliders as $key=>$s)
                <div class="carousel-item  {{ $key==0 ?'active':''}}">
                  	<a href="{{$s->link}}">
                  		<img src="{{ getImage('mobile_sliders', $s->mobile_image) }}" style="display:none" class="d-block w-100" alt="..." />
                    </a>
                </div>
                @endforeach
            </div>            
        </div>
    </div>
</div>
<!-- End Slider Area -->

<div class="desktop home-menu mt-0">
    <div class="container-fluid mb-4 my-lg-5 slider_cat">
        <div class="header-navbar header_navbar">
            <div class="header-main-nav">
                <div class="popular_product">   
                    <b></b>    
                    <span>জনপ্রিয় ক্যাটাগরি</span>    
                    <b></b>    
                </div>
                <div class="container-fluid my-4">
                    <div class="row g-3 category-grid">
                        @foreach($cats as $cat)
                            <div class="col-4 col-md-4 col-lg-3 col-xl-custom">
                                <a href="{{ route('front.subCategories1', [$cat->url]) }}" 
                                   class="text-decoration-none text-center d-block border rounded-3 bg-light p-3 h-100 shadow-sm hover-scale">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <img src="{{ getImage('categories', $cat->image) }}" 
                                             alt="{{ $cat->name }}" 
                                             class="w-100 object-fit-contain" 
                                             style="max-height:120px;">
                                    </div>
                                    <p class="mt-3 mb-0 fw-medium text-dark small" 
                                       style="font-family: 'Hind Siliguri', sans-serif;">
                                        {{ $cat->name }}
                                    </p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
<style>
/* Hover effect */
.hover-scale {
    transition: all 0.25s ease;
}
.hover-scale:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.object-fit-contain {
    object-fit: contain;
}

/* Custom 8-column layout for ≥1200px */
@media (min-width: 1200px) {
    .col-xl-custom {
        flex: 0 0 12.5%;   /* 100 / 8 = 12.5% */
        max-width: 12.5%;
    }
}
</style>


                <!-- Start Mainmanu Nav -->
                <nav class="mainmenu-nav d-none">
                    <ul class="mainmenu">                   
                      @foreach($cats as $cat)
                        <li class="{{ $cat->subcats->count() >0? 'menu-item-has-children':'' }}">
                            <div class="border border-muted cat-image">
                                <a href="{{ route('front.subCategories1',[$cat->url])}}">
                                    <img class="img-fluid" src="{{ getImage('categories', $cat->image) }}" alt="{{ $cat->name}}">
                                </a>
                                <a class="title_cat" href="{{ route('front.subCategories1',[$cat->url])}}" style="font-family: 'Hind Siliguri', sans-serif">{{ $cat->name}}</a>
                            </div>
                        </li>
                        @endforeach                 
                    </ul>
                </nav>
                <!-- End Mainmanu Nav -->
            </div>
        </div>
    </div>
</div>

@foreach ($homeProducts as $categoryId => $products)
<div class="container-fluid px-0">
    <div class="card border-0">
        <div class="card-body p-1">
            <div class="container-fluid" style="margin-bottom: 20px;padding-left: 5px;padding-right: 5px;">
                <div class="bg-gradient container-fluid" style="padding: 10px 1px;">
                    <div class="col-12 product-header">
                        <div class="section_title text-light">
                             @if(!empty($products->first()->category->id))
                            <div class="d-flex border-bottom border-2 border-dark">
                                <div class="flex-fill view_left">
                                    <a href="{{ route('front.subCategories1',[$products->first()->category->url])}}" style="color: #218A41;"> 
                                        <h4 class="p-2 pe-3 m-0 prodCatcus " style="text-align:left;font-family: 'Hind Siliguri', sans-serif">
                                            <span style="width: 100%;" class=" d-block">{{ $products->first()->category->name }}</span>
                                        </h4> 
                                    </a>
                                </div>
                                <div class="view_all">
                                    <a href="{{ route('front.subCategories1',[$products->first()->category->url])}}" class="rounded-5 btn btn-lg main-bg" style=" 
                                        color: #ffffff;
                                        white-space: nowrap;
                                        border-radius: 5px;font-family: 'Hind Siliguri', sans-serif"> 
                                        View All
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                </div>
                <div class="container-fluid" style="margin-bottom: 20px;">
                <div class="slick-single-layout">
                    <div class="row row--15">
                        @foreach($products as $product)
                        <div class="col-lg-2 col-md-3 col-6 mb--30">
                            @include('frontend.products.partials.product_section')
                        </div>
                        @endforeach
                    </div>
                </div>
                </div>
        </div>
    </div>
</div>
@endforeach


@if(count($brands))
<!-- Start Categorie Area  -->
<!--<div class="brand bg-color-white" style="margin-top: -15px;">-->
<!--    <div class="container-fluid">-->
<!--      <h2 class="title">Popular Brand</h2>        -->
        
<!--        <div class="row pt-5">-->
<!--            @foreach($brands as $item)-->
<!--            <div class="col-6 col-sm-4 col-lg-2">-->
<!--                <a title="{{$item->name}}" style="transition: all 0.5s ease-in-out;box-shadow: 0 0 12px rgb(0 0 0 / 42%);" href="{{ route('front.products.index')}}?brand_id={{ $item->id}}" class="cat-block">-->
<!--                    <figure>-->
<!--                        <span>-->
<!--                            <img style="padding: 10px;" src="{{ getImage('types', $item->image)}}" />-->
<!--                        </span>-->
<!--                    </figure>-->
<!--                </a>-->
<!--            </div>-->
<!--            @endforeach-->
            <!-- End .col-sm-4 col-lg-2 -->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!-- End Categorie Area  -->

<div class="brand bg-color-white mt-5 d-none" style="">
    <div class="container-fluid">
      <h2 class="title">Product Brand</h2>        
        
        <div class="row pt-5 home_brand">
            @foreach($brands as $item)
            <div class="col-6 col-sm-4 col-lg-2" style="margin: 10px;">
                <a title="{{$item->name}}" style="transition: all 0.5s ease-in-out;box-shadow: 0 0 12px #00276c73;" href="{{ route('front.products.index')}}?brand_id={{ $item->id}}" class="cat-block">
                    <figure>
                        <span>
                            <img style="padding: 10px;" src="{{ getImage('types', $item->image)}}" />
                        </span>
                    </figure>
                </a>
            </div>
            @endforeach
            <!-- End .col-sm-4 col-lg-2 -->
        </div>
    </div>
</div>


@endif

</main>

@endsection

@push('js')

<script type="text/javascript">
    $(document).ready(function(){
        // getTrending();
        // getHotDeal();
        // getRecommended();

        function getTrending(){
            let url='{{ route("front.trendingProduct")}}';
            $.ajax({
                url: url,
                method: 'GET',
                data:{},
                dataType :"JSON",
                success: function (res) {

                    if (res.success) {
                        $('div#trending_data').html(res.html);
                    }
                   
                }
            });
        }

        function getHotDeal(){
            let url='{{ route("front.hotdealProduct")}}';
            $.ajax({
                url: url,
                method: 'GET',
                data:{},
                dataType :"JSON",
                success: function (res) {

                    if (res.success) {
                        $('div#hotdeal_data').html(res.html);
                    }
                   
                }
            });
        }

        function getRecommended(){
            let url='{{ route("front.recommendedProduct")}}';
            $.ajax({
                url: url,
                method: 'GET',
                data:{},
                dataType :"JSON",
                success: function (res) {

                    if (res.success) {
                        $('div#recommended_data').html(res.html);
                    }
                   
                }
            });
        }


    });
</script>


<script>
    $(document).on('click', 'a#product_show', function(e) {
        e.preventDefault();
    
        let product_id = $(this).data('productid'); 
        let product_name = $(this).data('productname'); 
        let category_id = $(this).data('categoryid'); 
        let sell_price = parseFloat($(this).closest('.axil-product').find('.product-price-variant .current-price').text().replace(/[^\d.]/g, ''));
        let quantity = 1; 
        
   
        window.dataLayer = window.dataLayer || [];
        dataLayer.push({
            event: "view_item",
            ecommerce: {
                currency: "BDT",
                value: sell_price, 
                items: [
                    {
                        item_id: product_id,
                        item_name: product_name,
                        item_category: category_id,
                        price: sell_price,
                        quantity: quantity
                    }
                ]
            }
        });
    
        let url = $(this).attr('href');
        if (url) {
            document.location.href = url;
        } else {
            
        }
        
    });
</script>

@endpush
