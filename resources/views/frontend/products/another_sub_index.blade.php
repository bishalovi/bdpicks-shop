@extends('frontend.app')
@section('content')

@php

use App\Models\Information;
$info = Information::first();

@endphp

<style>

.row>[class*=col] {
    padding-left: 5px;
    padding-right: 5px;
}
.row {
    margin-right: -12px;
    margin-left: -12px;
}
</style>

<main class="main-wrapper">
        <!-- Start Breadcrumb Area  -->
        <div class="axil-breadcrumb-area">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="inner">
                            <ul class="axil-breadcrumb">
                                <li class="axil-breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li> 
                                <li class="separator"></li>
                                <li class="axil-breadcrumb-item active" aria-current="page">{{ $s_cat->name }}</li>
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- End Breadcrumb Area  -->

        <!-- Start Shop Area  -->
        <div class="axil-shop-area axil-section-gap bg-color-white">
            <div class="container-fluid">
                <div class="row p-2">
                    <style>
                        @media only screen and (min-width: 568px) and (max-width: 768px) {
        .axil-product {
            max-height: 455px !important;
        }
    }
    
    @media only screen and (min-width: 478px) and (max-width: 567px) {
        .axil-product {
            max-height: 412px !important;
        }
    }
  
    @media only screen and (min-width: 1850px) and (max-width: 2600px) {
        .axil-product {
            max-height: 407px !important;
        }
        .product-content {
            margin-top: 15px !important;
        }
    }
  
  @media only screen and (min-width: 1024px) and (max-width: 1155px) {
        .axil-product {
            max-height: 407px !important;
        }
    }
                    </style>
                    <div class="col-lg-12 products-block">
                        <!--<div class="row">-->
                        <!--    <div class="col-lg-12">-->
                        <!--        <div class="axil-shop-top mb--40">-->
                        <!--            <div class="category-select align-items-center justify-content-lg-end justify-content-between">-->
                                        <!-- Start Single Select  -->
                        <!--                <span class="filter-results">Showing 1-12 of 84 results</span>-->
                        <!--                <select class="single-select">-->
                        <!--                    <option value="desc" selected>Short by Latest</option>-->
                        <!--                    <option value="asc">Short by Oldest</option>-->
                        <!--                    <option value="name">Short by Name</option>-->
                        <!--                    <option value="price_low">Short by Price Low</option>-->
                        <!--                    <option value="price_high">Short by Price High</option>-->
                        <!--                </select>-->
                                        <!-- End Single Select  -->
                        <!--            </div>-->
                        <!--            <div class="d-lg-none">-->
                        <!--                <button class="product-filter-mobile filter-toggle"><i class="fas fa-filter"></i> FILTER</button>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <!-- End .row -->
                        <div class="row row--15" id="product_data">
                           @forelse($items as $product)
<div class="col-xl-2 col-md-2 col-sm-6 col-6 mb--30">
   
@php
$data=getProductInfo($product);
@endphp
<style>
    .axil-product>.thumbnail>a img{
        transition:1.2s;
    }
    .axil-product:hover>.thumbnail>a img{
        transform: scale(1.5) !important;
    }
    .axil-product>.thumbnail{
        max-height: 280px;
        overflow: hidden;
    }
</style>
<div class="axil-product product-style-one" style="padding: 0px !important;">
    <div class="thumbnail" style="padding: 10px !important">
        <a href="{{ route('front.products.show',[$product->id])}}">
            <img src="{{ getImage('thumb_products', $product->image)}}" class="product_img" alt="Product Images">
        </a>

        @if($product->discount_type)
        <div class="label-block label-right">
            <div class="product-badget" style="background: #c2050b;">{{$product->discount_type=='fixed'?'':''}}{{$product->discount}} {{$product->discount_type=='fixed'?'':'%'}} Off</div>
        </div>
        @endif
        <div class="product-hover-action">
            <ul class="cart-action d-none">
                <li class="quickview">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#quick-view-modal"><i class="far fa-eye"></i></a>
                </li>
                <li class="wishlist">
                    <a href="wishlist.php"><i class="far fa-heart"></i></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="product-content" style="padding: 10px !important;">
        <div class="inner">
            <h5 class="title text-center"><a href="{{ route('front.products.show',[$product->id])}}">{{ \Illuminate\Support\Str::limit($product->name, 17), '...' }}</a></h5>
            <div class="product-price-variant text-center">
                <span class="price current-price" style="color: #c2050b">
                  
                  @php  
                    $curr = $info->currency;                   
                  @endphp
                  
                  @if($curr == 'BDT')
                    ৳ {{ $data['price'] }}
                  @elseif ($curr == 'Dollar') 
                    $ {{ $data['price'] }}
                  @elseif ($curr == 'Euro') 
                     {{ $data['price'] }}
                  @elseif ($curr == 'Rupee') 
                     {{ $data['price'] }}
                  @else
                  
                  @endif                   
                  
              </span>
                @if($data['discount_amount'] > 0 && $data['old_price'])
                <span class="price old-price" style="color: #c2050b">
                   @php  
                    $curr = $info->currency;                   
                  @endphp
                  
                  @if($curr == 'BDT')
                     {{ $data['old_price'] }}
                  @elseif ($curr == 'Dollar') 
                    $ {{ $data['old_price'] }}
                  @elseif ($curr == 'Euro') 
                     {{ $data['old_price'] }}
                  @elseif ($curr == 'Rupee') 
                     {{ $data['old_price'] }}                 
                  @else
                  
                   @endif
              </span>
                @endif
            </div>

           
            
        </div>
    </div>
   @if($product->type=="single")
            <form method="POST" action="{{ route('front.carts.store')}}" id="cart_form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id}}">
                @if($product->after_discount != '0')
                <input type="hidden" name="price" value="{{ $product->after_discount}}">
                @else
                <input type="hidden" name="price" value="{{ $product->sell_price}}">
                @endif
                <input type="hidden" name="variation_id" value="{{ $product->variation->id}}">
                <input type="hidden" name="is_stock" value="{{ $product->is_stock }}">
                <div class="desktop-cart cart-count" style="padding-bottom: 0px;">
                    <div class="product-add-to-cart col-12">
                        <ul class="cart-action col-12">
                            <li class="select-option col-12" style="margin-bottom: 0px;">
                                <button type="submit" class="btn p-0 button m-auto text-light col-12" style="background: #FFC610 !important;"> 
                                      
                                      
                                      @if($product->is_free_shipping == 0)
                                     
                                             <p><b>
                                          <i class="fas fa-shopping-cart" style="color: #00276C;"></i>
                                          
                                         &nbsp; অর্ডার করুন </b></p>
                                         
                                      @else
                                      
                                        <p><b>
                                          <i class="fas fa-shopping-cart" style="color: #00276C;"></i>
                                          
                                         &nbsp; ফ্রি শিপিং - অর্ডার করুন </b></p>
                                          
                                    @endif      
                                          
                                        <span>
                                            <i class="fas fa-shopping-cart" style="color: #00276C;"></i>
                                        </span>                                    
                                </button>                                
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
            @else
            <div class="desktop-cart cart-count">
                    <div class="product-add-to-cart">
                        <ul class="cart-action">
                            <li class="col-12 reg text-center" style="background: #FFC610; padding: 7px;border-radius: 4px;text-align: center;">                                
                                <a type="submit" style="color:#00276C;font-size: 13px;font-weight: 900;" href="{{ route('front.products.show',[$product->id])}}" ><i class="fas fa-shopping-cart" style="color: #00276C;"></i> &nbsp;  অর্ডার করুন </a>                                
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
            
         
  
</div>
</div>
@empty
<div class="col-lg-3 col-md-3 col-sm-6 col-6 mb--30">
    <div class="alert alert-warning"> No Products Found !!</div>
</div>
@endforelse
                          <div class="text-center pt--20">
    <p>{{ $items->links() }}</p>
</div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- End .container -->
        </div>
        <!-- End Shop Area  -->

    </main>

@endsection

@push('js')
<script type="text/javascript">

    $(document).ready(function(){

  
        $('#slider-range').slider({
            range: true,
            min: 0,
            max: 5000,
            values: [0, 3000],
            slide: function(event, ui) {
                $('#amount').val(' ' + ui.values[0] + '   ' + ui.values[1]);
            }
        });
        $('#amount').val('ট' + $('#slider-range').slider('values', 0) +
                '  ট' + $('#slider-range').slider('values', 1));

    

    })

    $('li.category').click(function(){

        
        if($(this).hasClass('current-cat')) {
            $(this).removeClass('current-cat');
        }else{
            $(this).addClass('current-cat');
        }
        fetchData();
    });
    
    
    $('li.brand').click(function(){
        if($(this).hasClass('current-cat')) {
            $(this).removeClass('current-cat');
        }else{
            $(this).addClass('current-cat');
        }
        fetchData();
    });
    


    $('li.size').click(function(){

        
        if($(this).hasClass('chosen')) {
            $(this).removeClass('chosen');
        }else{
            $(this).addClass('chosen');
        }
        fetchData();
    });

    $('select.single-select').change(function(){
        fetchData();
    });

//     $(document).on('click', ".pagination a", function(e) {
//       e.preventDefault();
//       $('li').removeClass('active');
//       $(this).parent('li').addClass('active');
//       var page = $(this).attr('href').split('page=')[1];
//       fetchData(page);
//   });

  
  $(document).on('click', ".apply_search", function(e) {
    fetchData();
  });

  $(document).ready(function(){

    
  });

  function fetchData(page=null){

    var size = $('li.chosen').map(function(){
      return $(this).data('value');
    });
    var size_id=size.get();

    var category = $('li.category.current-cat').map(function(){
      return $(this).data('value');
    });
    var cat_id=category.get();
    
    var brand = $('li.brand.current-cat').map(function(){
      return $(this).data('value');
    });
    var brand_id=brand.get();

    
    var q=$('input#search2').val();
    var shorting=$('select.single-select').val();

    $.ajax({
       type:'GET',
       url:'{{ route("front.products.index")}}?page='+page,
       data:{cat_id,size_id,q,shorting,brand_id},
       success:function(res){
          $('div#product_data').html(res);
       }
    });

  }
</script>
@endpush