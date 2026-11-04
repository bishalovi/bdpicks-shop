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
                                <li class="axil-breadcrumb-item active" aria-current="page">{{ $cat->name }}</li>
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- End Breadcrumb Area  -->

        <!-- Start Shop Area  -->
        <div class="axil-shop-area bg-color-white">
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
<div class="col-xl-2 col-md-3 col-sm-6 col-6 mb--30 item_data">
   @include('frontend.products.partials.product_section')
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
                $('#amount').val('ট ' + ui.values[0] + '   ' + ui.values[1]);
            }
        });
        $('#amount').val('' + $('#slider-range').slider('values', 0) +
                '  ' + $('#slider-range').slider('values', 1));

    

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