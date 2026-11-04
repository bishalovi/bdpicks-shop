@extends('frontend.app')
@section('content')
<main class="main-wrapper">
        <!-- Start Breadcrumb Area  -->
        <!--<div class="axil-breadcrumb-area">-->
        <!--    <div class="container-fluid">-->
        <!--        <div class="row align-items-center">-->
        <!--            <div class="col-lg-6 col-md-8">-->
        <!--                <div class="inner">-->
        <!--                    <ul class="axil-breadcrumb">-->
        <!--                        <li class="axil-breadcrumb-item"><a href="index.php">Home</a></li>-->
        <!--                        <li class="separator"></li>-->
        <!--                        <li class="axil-breadcrumb-item active" aria-current="page">Shop</li>-->
        <!--                    </ul>-->
        <!--                </div>-->
        <!--            </div>-->
                    
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        <!-- End Breadcrumb Area  -->

        <!-- Start Shop Area  -->
        <div class="axil-shop-area axil-section-gap bg-color-white" style="">
            <div class="container-fluid">
                <div class="row p-2">
                    <div class="col-lg-3" style="display: none;">
                        <div class="axil-shop-sidebar">
                            <div class="d-lg-none" style="display: none;">
                                <button class="sidebar-close filter-close-btn"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="toggle-list product-categories active">
                                <h6 class="title">CATEGORIES</h6>
                                <div class="shop-submenu">
                                    <ul>
                                        @foreach($cats as $cat)
                                        <li class="category {{ request('category_id') == $cat->id?'current-cat':''}}" data-value="{{ $cat->id}}"><a> {{ $cat->name}} </a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="toggle-list product-categories active">
                                <h6 class="title">BRANDS</h6>
                                <div class="shop-submenu">
                                    <ul>
                                        @foreach($types as $type)
                                        <li class="brand {{ request('brand_id') == $type->id?'current-cat':''}}" data-value="{{ $type->id}}"><a> {{ $type->name}} </a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            
                            <a href="{{ route('front.products.index')}}" class="axil-btn btn-bg-primary">All Reset</a>
                        </div>
                        <!-- End .axil-shop-sidebar -->
                    </div>
                    <style>
                        @media only screen and (max-width: 767px){
                            .products-block .row>[class*=col] {
                                padding-left: 5px;
                                padding-right: 5px;
                            }
                            .axil-shop-area.axil-section-gap.bg-color-white {
                                padding: 10px 0;
                            }
                        }
                    </style>
                    <div class="col-lg-12 products-block">
                      
                        <!--    <div class="col-lg-12">-->
                        <!--        <div class="axil-shop-top mb--40">-->
                        <!--            <div class="category-select align-items-center justify-content-lg-end justify-content-between">-->
                                        <!-- Start Single Select  -->
                                        <!--<span class="filter-results">Showing 1-12 of 84 results</span>-->
                        <!--                <select class="single-select">-->
                        <!--                    <option value="desc" selected>Short by Latest</option>-->
                        <!--                    <option value="asc">Short by Oldest</option>-->
                        <!--                    <option value="name">Short by Name</option>-->
                        <!--                    <option value="price_low">Short by Price Low</option>-->
                        <!--                    <option value="price_high">Short by Price High</option>-->
                        <!--                </select>-->
                                        <!-- End Single Select  -->
                        <!--            </div>-->
                        <!--            <div class="d-lg-none" style="display: none;">-->
                        <!--                <button class="product-filter-mobile filter-toggle"><i class="fas fa-filter"></i> FILTER</button>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <!-- End .row -->
                        <div class="row row--15" id="product_data">
                            
                            
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
                $('#amount').val('ট ' + ui.values[0] + '  ট ' + ui.values[1]);
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

    $(document).on('click', ".pagination a", function(e) {
      e.preventDefault();
      $('li').removeClass('active');
      $(this).parent('li').addClass('active');
      var page = $(this).attr('href').split('page=')[1];
      fetchData(page);
  });

  
  $(document).on('click', ".apply_search", function(e) {
    fetchData();
  });

  $(document).ready(function(){

    fetchData();
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