@extends('frontend.app')
@php

use App\Models\Information;
use App\Models\BanglaText;
use App\Models\Page;
$aboutUs=Page::where('page','about')->first();
$termsCondition=Page::where('page','term')->first();
$info = Information::first();
$bangla_text = BanglaText::first();
$data=getProductInfo($singleProduct);
@endphp
@push('css')
<style>
    .cart-btn:hover p{
      height: 65px;
      text-align: center;
      padding-top: 10px;
      font-size: 13px;
      margin-top: -68px;
      transition: 0.5s;
    }
    .product-action-wrapper {
        flex-direction: inherit;
    }
    .single-desc h3 {
        font-family: 'Hind Siliguri', sans-serif !important;
    }
    .details_right {
        border: 1px solid #ddd;
        padding: 10px 20px;
        height: 100%;
        border-radius: 5px;
    }
    .breadcrumb ul li a {
        color: #666666b3;
        font-size: 16px;
    }
    li {
        display: inline-block;
        list-style: none;
    }
    ul, ol {
        margin: 0;
        padding: 0;
    }
    .breadcrumb ul li span {
        color: #666666b3;
    }
    .product-cart .name {
        font-size: 22px;
        font-weight: 600;
        text-transform: capitalize;
    }
    .details-price {
        font-size: 24px;
        font-weight: 600;
        color: #000;
        margin: 10px 0;
    }
    .details-price del {
        color: #bbb;
        margin: 5px 0;
        font-size: 19px;
    }
    .details-ratting-wrapper {
        margin-bottom: 10px;
    }
    .details-ratting-wrapper i {
        color: #FFDF00;
    }
    .details-ratting-wrapper i.far.fa-star {
        color: #959595;
    }
    .all-reviews-button {
        text-decoration: underline;
        margin-left: 20px;
    }
    .product-code p {
        display: inline-block;
        background: #3c7d17;
        color: #fff;
        padding: 0px 10px;
        border-top: 15px solid transparent;
        border-bottom: 15px solid transparent;
        border-right: 15px solid #fff;
        line-height: 0;
        margin-bottom: 10px;
    }
    .pro_brand {
        margin-bottom: 7px;
        margin-top: 2px;
    }
    .pro_brand p {
        font-weight: 600;
    }
    .qty-cart {
        width: auto;
        display: flex;
        align-items: center;
        column-gap: 20px;
    }
    .qty-cart .quantity {
        position: relative;
        border: 1px solid #222;
        height: 40px;
        overflow: hidden;
        width: 130px;
        margin-top: 10px;
    }
    .quantity .minus
     {
        position: absolute;
        left: 0;
        bottom: 0;
        z-index: 1;
        height: 40px;
        line-height: 40px;
        width: 40px;
        border-right: 1px solid #222;
        text-align: center;
        font-size: 40px;
        cursor: pointer;
    }
    
    .quantity input {
        position: relative;
        text-align: center;
        font-size: 16px;
        height: 100%;
        width: 100%;
        pointer-events: none;
        font-weight: 500;
    }
    .quantity .plus {
        position: absolute;
        right: 0;
        bottom: 0;
        z-index: 1;
        height: 40px;
        line-height: 40px;
        width: 40px;
        border-left: 1px solid #222;
        text-align: center;
        font-size: 26px;
        cursor: pointer;
    }
    
    .add_cart_btn {
        color: #fff;
        background-color: #3c7d17;
        border: 1px solid #3c7d17;
        border-radius: 0;
        width: 50%;
        height: 45px;
        margin-top: 10px;
        border-radius: 5px;
    }
    
    .order_now_btn {
        font-size: 18px; 
        color: #fff;
        border-radius: 3px;
        width: 50%;
        margin-left: 5px;
        font-family: "Potro Sans Bangla";
        height: 45px;
        margin-top: 10px;
        border-radius: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
    }
    
    .icon-with-text--vertical .icon-with-text__item {
        margin-bottom: calc(1.1* 2rem);
    }
    
    .icon-with-text__item {
        display: flex;
        align-items: center;
    }
    .icon-with-text__item .h4{
        padding-left: 20px;
        font-family: Poppins, sans-serif;
        font-size: calc(1.1 * 1.5rem);
        font-weight: normal;
    }
    .product__info-container .product-form, .product__info-container .product__description, .product__info-container .icon-with-text {
        margin: 2.5rem 0;
    }
    .nav.nav-tabs{
            border: 1px solid #00276C;
            border-radius: 10px;
            background: #f7fffb;
    }
    .nav.nav-tabs .nav-item{
        margin: 0;
    }
    .nav.nav-tabs .nav-item a {
        margin: 0;
        padding: 12px 20px;
        font-weight: 700;
        color: black;
        border-radius: 8px;
    }
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        color: {{ $info->primary_color }} !important;
        background: {{ $info->gradient_code }} !important;
        border-color: rgba(29, 95, 171, 1);
    }
    @media(max-width: 575px){
        .nav.nav-tabs .nav-item a {
            margin: 0;
            padding: 6px 12px;
            font-weight: 700;
            color: black;
            border-radius: 8px;
            font-size: 12px;
        }
        .mx_0{
            margin-left: -5px;
            margin-right: -5px;
        }
        .details_right{
            padding: 10px;
        }
    }
.small-thumb-wrapper .small-thumb-img{
    width: 100%;
}
.small-thumb-wrapper .small-thumb-img img{
    width: 100%;
}
.animated_text{
    font-size: 17px;
    transition: 0.5s;
}
.animated_text:hover{
    font-size: 20px;
    color: white;
}
.product-large-thumbnail-3 .slick-next{
    right: 25px;
}
.product-large-thumbnail-3 .slick-prev{
    left: 18px;
    z-index: 99;
}
.slick-prev:before, .slick-next:before{
    color: black;
    font-size: 30px;
}
.slick-prev, .slick-next{
    color: white;
}
</style>

@endpush
@section('content')


<main class="main-wrapper">
    <!-- Start Shop Area  -->
    <div class="axil-single-product-area p pb--0 bg-color-white">
        <div class="single-product-thumb mb--5">
            <div class="container-fluid p-lg-5 mobile_show">
                <div class="row">
                    <div class="col-lg-6 mb--10">
                        <div class="row mx_0">
                            <div class="col-lg-10 order-lg-2">
                                <div class="single-product-thumbnail-wrap zoom-gallery overflow-hidden">
                                    <div class="single-product-thumbnail product-large-thumbnail-3 img-section axil-product">
                                        <div class="thumbnail h-100 overflow-hidden">
                                            <a href="{{ getImage('products', $singleProduct->image)}}" class="popup-zoom">
                                                <img src="{{ getImage('products', $singleProduct->image)}}" alt="{{ $singleProduct->name}} Images">
                                            </a>
                                        </div>

                                        @foreach($singleProduct->images as $im)
                                        <div class="thumbnail h-100 overflow-hidden">
                                            <a href="{{ getImage('products', $im->image)}}" class="popup-zoom">
                                                <img src="{{ getImage('products', $im->image)}}" alt="{{ $singleProduct->name}} Images">
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                    @if($singleProduct->after_discount > 0)
                                        @php
                                            $price = $singleProduct->sell_price;
                                            $afterDiscount = $singleProduct->after_discount;
                                        
                                            // absolute discount amount
                                            $discountAmount = $price - $afterDiscount;
                                        
                                            // discount percentage
                                            $discountPercent = $price > 0 ? round(($discountAmount / $price) * 100, 0) : 0;
                                        @endphp
                                        <div class="label-block">
                                            <div class="product-badget" style="background: #00276C;">
                                                {{$discountPercent}} % Off</div>
                                        </div>
                                    @endif
                                    <div class="product-quick-view position-view">
                                        <a href="{{ getImage('products', $singleProduct->image)}}" class="popup-zoom">
                                            <i class="far fa-search-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 order-lg-1 px-lg-0">
                                <div class="product-small-thumb-3 small-thumb-wrapper">
                                    <div class="small-thumb-img mt-2">
                                        <img src="{{ getImage('products', $singleProduct->image)}}" alt="{{ $singleProduct->name}} image">
                                    </div>
                                    @foreach($singleProduct->images as $im)
                                    <div class="small-thumb-img mt-2">
                                        <img src="{{ getImage('products', $im->image)}}" alt="{{ $singleProduct->name}} image">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-6  mb--30">
                        <div class="details_right">
                            <div class="product">
                                <div class="product-cart">
                                    <p class="name">{{ $singleProduct->name}}</p>
                                    @php  
                                          $curr = $info->currency;                   
                                        @endphp
                                    <p class="details-price">
                                        @if($singleProduct->after_discount >0)
                                        <del id="product-old-price" class="price old-price">      
                                           @php  
                                          $curr = $info->currency;                   
                                        @endphp

                                        @if($curr == 'BDT')
                                           ৳ {{ (int)$data['old_price'] }}
                                        @elseif ($curr == 'Dollar') 
                                          $ {{ $data['old_price'] }}
                                        @elseif ($curr == 'Euro') 
                                          € {{ $data['old_price'] }}
                                        @elseif ($curr == 'Rupee') 
                                           {{ $data['old_price'] }}                 
                                        @else                  
                                         @endif 
                                          </del>
                                        @endif
                                        @if($curr == 'BDT')
                                          ৳ {{ (int)$data['price'] }}
                                        @elseif ($curr == 'Dollar') 
                                          $ {{ $data['price'] }}
                                        @elseif ($curr == 'Euro') 
                                          € {{ $data['price'] }}
                                        @elseif ($curr == 'Rupee') 
                                           {{ $data['price'] }}                 
                                        @else                  
                                         @endif  
                                    </p>
                                    <div class="">
                                        <span class="badge bg-{{ $singleProduct->stock_quantity && $singleProduct->stock_quantity > 0 ? 'success' : 'danger' }} text-light">{{ $singleProduct->stock_quantity && $singleProduct->stock_quantity > 0 ? $singleProduct->stock_quantity : '0' }} Items left</span>
                                    </div>
                                    <div class="details-ratting-wrapper d-none">
                                        @php
                                            $totalReviews = $singleProduct->reviews->count();
                                            $averageRating = $totalReviews > 0 ? round($singleProduct->reviews->avg('review'), 2) : 0;
                                        @endphp
                                    
                                        <span>{{ $totalReviews }} Reviews</span>
                                    
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $averageRating)
                                                <i class="fas fa-star text-warning"></i>
                                            @elseif ($i - 0.5 <= $averageRating)
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    
                                        <span>{{ number_format($averageRating, 2) }}/5</span>
                                    
                                        <a class="all-reviews-button" href="#writeReview">See Reviews</a>
                                    </div>
                                    
                                    @php
                                        $singleProductName = $singleProduct->name;
                                        $sku = $singleProduct->sku;
                                        $singleProductUrl = url("/product-show/{$singleProduct->id}");
                                        $message = "Hello, I am interested in your product: {$singleProductName} - ({$sku}). Here is the link: {$singleProductUrl}";
                                        $encodedMessage = urlencode($message);
                                        $whatsappNumber = preg_replace('/[^0-9]/', '', $info->whats_num ?? '');
                                    @endphp

                                    <div class="product-code d-none">
                                        <p><span>প্রোডাক্ট কোড : </span>{{ $singleProduct->sku }}</p>
                                    </div>
                                    <form  action="{{ route('front.carts.storeCart')}}" id="cart_submit" method="POST">
                                        @csrf                                           
                                        <input type="hidden" name="product_id" value="{{ $singleProduct->id }}">
                                        <input type="hidden" name="product_name" value="{{ $singleProduct->name}}">
                                        <input type="hidden" name="category_id" value="{{ $singleProduct->category->name??''}}">
                                        <div class="pro_brand d-none">
                                            <p>Brand :
                                                {{ $singleProduct->brand ? $singleProduct->brand->name : 'N/A' }}
                                            </p>
                                        </div>
                                        <input type="hidden" name="variation_id" value="{{ $singleProduct->variation->id }}">
                                        <div class="">
                                            <div class="qty-cart col-sm-12">
                                                <div class="quantity">
                                                    <span class="minus">-</span>
                                                    <input type="text" name="quantity" value="1">
                                                    <span class="plus">+</span>
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex single_product col-sm-12">
                                                <button type="submit" class="btn px-4 add_cart_btn animated_text" name="action_type" value="cart" style="font-family: 'Hind Siliguri', sans-serif">
                                                    কার্টে যোগ করুন
                                                </button>
                                                <button type="submit" class="btn px-4 order_now_btn animated_text order_now_btn_m main-bg" style="font-family: 'Hind Siliguri', sans-serif" name="action_type" value="order">
                                                    অর্ডার করুন
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mt-md-2 mt-2 ">
                                            <div class="shadow mt-2">
                                                <a href="tel:{{$info->supp_num1}}" class="btn btn-primary main-bg  d-block py-3 fs-3 text-light fw-bolder" style="font-family: 'Hind Siliguri', sans-serif;">
                                                কল করুন <i class="fas fa-phone-volume"></i> {{$info->supp_num1}}
                                                </a>
                                            </div>
                                            <div class="shadow mt-2">
                                                <a href="https://wa.me/+88{{ $whatsappNumber }}?text={{ $encodedMessage }}" target="_blank" class="btn btn-success py-3 d-block fs-3  text-light fw-bolder">
                                                Whatsapp <img width="20px" height="20px" src="https://img.icons8.com/color/48/whatsapp--v1.png" alt="whatsapp--v1"> {{$info->whats_num}} 
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mt-md-2 mt-2">
                                            <table class="table table-bordered border-1 border-dark" style="font-family: 'Hind Siliguri', sans-serif;">
                                                <tbody>
                                                    <tr>
                                                        <th colspan="2" class="text-center">
                                                            কুরিয়ার ডেলিভারি খরচ
                                                        </th>
                                                    </tr>
                                                    @foreach($charges as $charge)
                                                    <tr>
                                                        <td class="border border-dark">{{ $charge->title }}</td>
                                                        <td class="text-end border border-dark">৳ {{ number_format($charge->amount, 0) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <ul class="product-metas" style="font-family: 'Hind Siliguri', sans-serif;">
                                          {!! $singleProduct->feature !!}
                                        </ul>      
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <div class="woocommerce-tabs wc-tabs-wrapper">
        <div class="container-fluid">  
        <ul class="nav nav-tabs mb-4 gap-3" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Details</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="review-tab" data-bs-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false">Reviews</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
              <div class="product-desc-wrapper">
                <div class="">
                    <div class="col-lg-12 mb--20">
                        <h5 class="title"> Short Description </h5>
                        <div class="single-desc pt-4">
                            @if($singleProduct->video_link)
                            <div class="col-lg-5">
                              {!! $singleProduct->video_link !!}                                  
                            </div>
                            @endif
                            {!! $singleProduct->body !!}
                        </div>
                    </div>
                </div>
            </div>
          </div>
          <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
            <div class="woocommerce-tabs wc-tabs-wrapper" id="writeReview" style="background: #f7fffb;">
                <div class="container"> 
                    <div class="reviews-wrapper pt-4">
                        <div class="row">
                            <div class="col-lg-6 mb--20">
                                <div class="axil-comment-area pro-desc-commnet-area pt-3">
                                    <h5 class="title">({{$singleProduct->reviews->count()}}) Relative Product</h5>
                                    <ul class="comment-list">
                                    @include("frontend.products.partials.reviewList")
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 mb--20">
                                 Start Comment Respond  
                                <div class="comment-respond pro-des-commend-respond mt--0">
                                    <h5 class="title mb--10">Add a Review</h5>
                                    <div class="rating-wrapper d-flex-center mb--10">
                                        <div class="wrapper">
                                            <div class="master">
                                                <div class="rating-component">
                                                    <div class="status-msg">
                                                        <label>
                                                            <input class="rating_msg" type="hidden" name="rating_msg" value="" />
                                                        </label>
                                                    </div>
                                                    <div class="stars-box">
                                                        <i class="star fa fa-star" title="1 star" data-message="Poor" data-value="1"></i>
                                                        <i class="star fa fa-star" title="2 stars" data-message="Too bad" data-value="2"></i>
                                                        <i class="star fa fa-star" title="3 stars" data-message="Average quality" data-value="3"></i>
                                                        <i class="star fa fa-star" title="4 stars" data-message="Nice" data-value="4"></i>
                                                        <i class="star fa fa-star" title="5 stars" data-message="very good qality" data-value="5"></i>
                                                    </div>
                                                    <div class="starrate">
                                                        <label>
                                                            <input class="ratevalue" type="hidden" name="rate_value" value="" />
                                                        </label>
                                                    </div>
                                                </div>
            
                                                <div class="feedback-tags">
                                                    <div class="tags-container" data-tag-set="1">
                                                        <div class="question-tag">
                                                            Why was your experience so bad?
                                                        </div>
                                                    </div>
                                                    <div class="tags-container" data-tag-set="2">
                                                        <div class="question-tag">
                                                            Why was your experience so bad?
                                                        </div>
                                                    </div>
            
                                                    <div class="tags-container" data-tag-set="3">
                                                        <div class="question-tag">
                                                            Why was your average rating experience ?
                                                        </div>
                                                    </div>
                                                    <div class="tags-container" data-tag-set="4">
                                                        <div class="question-tag">
                                                            Why was your experience good?
                                                        </div>
                                                    </div>
            
                                                    <div class="tags-container" data-tag-set="5">
                                                        <div class="make-compliment">
                                                            <div class="compliment-container">
                                                                Give a compliment
                                                                <i class="far fa-smile-wink"></i>
                                                            </div>
                                                        </div>
                                                    </div>
            
                                                    <div class="tags-box">
                                                        <form action="{{ route('front.product-reviews.store')}}" method="POST" id="ajax_form2">
                                                          	@csrf
                                                          	<input type="hidden" name="product_id" value="{{$singleProduct->id}}" />
                                                          	<input type="hidden" name="review" id="review" value="" />
                                                          	
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <label>Other Notes (optional)</label>
                                                                        <textarea name="message" placeholder="Your Comment"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-12 m-0">
                                                                    <div class="form-group">
                                                                        <label>Name <span class="require">*</span></label>
                                                                        <input id="name" type="text" name="name"/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-12 m-0">
                                                                    <div class="form-group">
                                                                        <label>Image <span class="require">*</span></label>
                                                                        <input type="file" form="ajax_form2" class="form-control" name="image" style="padding-top: 12px;">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 m-0">
                                                                    <div class="button-box form-submit">
                                                                        <button type="submit" class="axil-btn btn-bg-primary w-auto">Submit Review</button>
                                                                    </div>
                                                                  <div class="submited-box">
                                                                      <div class="loader"></div>
                                                                      <div class="success-message">
                                                                          Thank you!
                                                                      </div>
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
                        </div>
                    </div>     
                </div>
            </div>
          </div>
        </div>
      </div>
            
    </div>
    <style>
        .row>[class*=col] {
            padding-left: 5px;
            padding-right: 5px;
        }
    </style>
    <div class="axil-product-area bg-color-white pt--10">
        <div class="container-fluid">
            <!--<a class="viewall-right" href="{{ route('front.products.index')}}"><span class="title-highlighter view all highlighter-primary"> View All >></span></a>-->
            <div class="section-title-wrapper">
                <h2 class="border-bottom border-2" style="font-family: 'Arial', sans-serif;">Related Products</h2>
            </div>
            <div class="explore-product-activation slick-layout-wrapper slick-layout-wrapper--15 axil-slick-arrow arrow-top-slide">
                <div class="slick-single-layout" id="relative_data">
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
</main>
@endsection

@push('js')

<script>
    $(document).ready(function(){
        setTimeout(() => viewContent(), 500);
        
        function viewContent() {
            let product_id   = {{ $singleProduct->id }};
            let product_name = {!! json_encode($singleProduct->name) !!};
            let categoryName = {!! json_encode($singleProduct->category->name ?? '') !!};
            let sell_price = {{ (isset($singleProduct->after_discount) && $singleProduct->after_discount > 0) ? $singleProduct->after_discount : $singleProduct->sell_price }};
            
            const ymdhi = "{{ now()->format('ymdhi') }}";
            const eventID = "SV_{{ $singleProduct->id }}_" + ymdhi;
        
            if (typeof fbq === 'function') {
                fbq('track', 'ViewContent', {
                    content_ids: [product_id],
                    content_name: product_name,
                    content_type: "product",
                    value: sell_price,
                    currency: "BDT",
                    contents: [{
                        id: product_id,
                        quantity: 1,
                        item_price: sell_price
                    }],
                    content_category: categoryName
                }, {
                    eventID: eventID
                });
        
                console.log('✅B Pixel ViewContent fired with EventID:', eventID);
            }
        }

    });


    document.addEventListener('DOMContentLoaded', function() {
        const minus = document.querySelector('.quantity .minus');
        const plus = document.querySelector('.quantity .plus');
        const input = document.querySelector('.quantity input[name="quantity"]');
    
        plus.addEventListener('click', () => {
            let value = parseInt(input.value) || 0;
            input.value = value + 1;
        });
    
        minus.addEventListener('click', () => {
            let value = parseInt(input.value) || 1;
            if (value > 1) {
                input.value = value - 1;
            }
        });
    
        // Prevent non-numeric input
        input.addEventListener('input', () => {
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value === '' || parseInt(input.value) < 1) {
                input.value = 1;
            }
        });
    });
</script>

<script type="text/javascript">

  $(document).on('submit','form#ajax_form2', function(e) {   
    e.preventDefault();
    
    var url=$(this).attr('action');
    var method=$(this).attr('method');
    var formData = new FormData($(this)[0]);
	$.ajax({
	     type: method,
         url: url,
         data: formData,
         async: false,
         processData: false,
         contentType: false,
        success: function (res) {
            if (res.status) {
                toastr.success(res.msg);
                if (res.view) {
                	$('.comment-list').empty().append(res.view);
                }

                if (res.item) {
                	$(document).find('li.comment').text(res.item);
                }
              
                if(res.url){
                	document.location.href = res.url;
                } else {
                    setTimeOut(function(){
                        window.location.reload();
                    }, 1000)
                }
                
            }else{
                toastr.error(res.msg);
            }
        },
        error:function (response){
            $.each(response.responseJSON.errors,function(field_name,error){
                toastr.error(error);
            })
        }
	}); 
 
}); 

$(document).on('submit', 'form#cart_submit', function(e) {   
    e.preventDefault();

    let form = $(this);
    let actionType = form.find('button[type=submit][clicked=true]').val(); // detect clicked button
    
    // Reset all clicked attributes
    form.find('button[type=submit]').removeAttr('clicked');

    // Example: read product info
    let product_id = $('input[name="product_id"]').val();
    let product_name = $('input[name="product_name"]').val();
    let category_id = $('input[name="category_id"]').val();
    let sell_price = {{ (isset($singleProduct->after_discount) && $singleProduct->after_discount > 0) ? $singleProduct->after_discount : $singleProduct->sell_price }};
    let quantity = $('input[name="quantity"]').val();
    
    // Google dataLayer event
    window.dataLayer = window.dataLayer || [];
    dataLayer.push({ ecommerce: null });
    dataLayer.push({
        event: "add_to_cart",
        ecommerce : {
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
    
    
    const eventID = "ATC_{{ now()->format('Ymdhi') }}";

    fbq('track', 'AddToCart', {
        content_ids: [product_id],
        content_name: product_name,
        content_type: 'product',
        value: sell_price,
        currency: 'BDT',
        quantity: quantity
    }, {
        eventID: eventID
    });
    
    let url = form.attr('action');
    let method = form.attr('method');
    let data = form.serialize() + '&action_type=' + actionType;

    $.ajax({
        url: url,
        method: method,
        data: data,
        success: function (res) {
            if (res.success) {
                toastr.success(res.msg);

                if (res.view) $('div#cart_section').html(res.view);
                if (res.item) $('span.cart-count').text(res.item);

                // redirect logic
                if (res.url) {
                    document.location.href = res.url;
                } else {
                    // window.location.reload();
                   $('.cart-dropdown-btn').trigger('click');
                   $('.cart-count').text(res.item);
                   $('.cart-amount').text('৳ '+res.amount);
                }
            } else {
                toastr.error(res.msg);
            }
        },
        error: function (response) {
            $.each(response.responseJSON.errors, function(field_name, error) {
                toastr.error(error);
            });
        }
    });
});

// Detect which button was clicked
$(document).on('click', 'form#cart_submit button[type=submit]', function() {
    $('form#cart_submit button[type=submit]').removeAttr('clicked');
    $(this).attr('clicked', 'true');
});

    
    $('li.size').click(function(){
        $('li.size').removeClass('active');
        $(this).addClass('active');
    });

    $('li.color').click(function(){
        $('li.color').removeClass('active');
        $(this).addClass('active');
    });
  
  	$(document).ready(function(){
        getRelatedProduct();
        
        function getRelatedProduct(){
            let url ='{{ route("front.products.relativeProduct",[$singleProduct->id])}}';
            $.ajax({
                url: url,
                method: 'GET',
                data:{},
                dataType :"JSON",
                success: function (res) {
                    if (res.success) {
                        $('div#relative_data').html(res.html);
                    }
                }
            });
        }
    });
</script>

<script>
$(document).ready(function(){
    var firstSizeElement = $('#sizess .size:first');
    firstSizeElement.click();
});
</script>

  <script type="text/javascript">
  
        $('#sizess .size').on('click', function(){
        // $('#sizess .size').removeClass('active');
        // $(this).addClass('active');
        // let value = $(this).attr('value');
        // let price = $(this).data('varprice');
        // let discount_price = $(this).data('disprice');
        // let size = $(this).data('size');
        // let color = $(this).data('color');
        
         $('#sizess .size').removeClass('active');

        // Add 'active' class to the clicked div
        $(this).addClass('active');
    
        // Remove 'hide_span' class from all spans with ID 'add_here'
        $('span#add_here').removeClass('hide_span');
    
        // Add 'hide_span' class to the span within the clicked div with ID 'add_here'
        $(this).find('span#add_here').addClass('hide_span');
    
        // Your additional logic here, for example, accessing data attributes
        let value = $(this).attr('value');
        let price = $(this).data('varprice');
        let discount_price = $(this).data('disprice');
        let size = $(this).data('size');
        let color = $(this).data('color');
        if(color == 'Default') {
            color = '';
        }
        
        $('span.size_name').text(size + '-' + color);
        
        if(discount_price == '') {
            var ultimate_price = price;
        } else {
            var ultimate_price = discount_price;
        }
        
        let product_id = $(this).data('proid');
        $('.current-price-product').text('৳'+ultimate_price);
        $('#price_val').val(ultimate_price);
        $('#price_val1').val(ultimate_price);
        
           $.ajax({
               type: 'get',
               url: '{{ route("front.get-variation_price") }}',
               data: {product_id},
               success: function(res)
               {
                   if(res.discount_type == 'fixed')
                   {   
                       if(res.discount_amount == '0')
                       {
                           document.getElementById('old-price-old').style.display = 'none';
                       } else {
                           $('#old-price-old').text(res.discount_amount+'TK OFF');
                           $('#product-old-price').text(price);
                       }
                       
                   } else if(res.discount_type == 'percentage') {
                       if(res.discount_amount == '0')
                       {
                           document.getElementById('old-price-old').style.display = 'none';
                       } else {
                           $('#old-price-old').text(res.discount_amount+'% OFF');
                           $('#product-old-price').text(price);
                       }
                   } else {
                       
                   }
               }
           });
         
           $("#size_value").val(value);
           $("#size_value1").val(value);
       });
    
    $('.increase-qty').on('click', function () {
            var proQty = $('.qty1').val();  
            var qtyInput = $(this).siblings('.qty');
            
            var newQuantity = parseInt(qtyInput.val()) + 1;
            
            $('.qty1').val(newQuantity);
            // proQty.val(newQuantity);
            qtyInput.val(newQuantity);
        });
    
        $('.decrease-qty').on('click', function () {
            var qtyInput = $(this).siblings('.qty');
            var newQuantity = parseInt(qtyInput.val()) - 1;
            if (newQuantity > 0) {
                qtyInput.val(newQuantity);
            }
            if(parseInt(qtyInput.val() != '0'))
            {
                $('.qty1').val(newQuantity);    
            }
        });
    
        $(".rating-component .star").on("mouseover", function () {
            var onStar = parseInt($(this).data("value"), 10); //
            $(this).parent().children("i.star").each(function (e) {
                if (e < onStar) {
                  $(this).addClass("hover");
                } else {
                  $(this).removeClass("hover");
                }
            });
        }).on("mouseout", function () {
            $(this).parent().children("i.star").each(function (e) {
            $(this).removeClass("hover");
        });
    });

    $(".rating-component .stars-box .star").on("click", function () {
      var onStar = parseInt($(this).data("value"), 10);
      var stars = $(this).parent().children("i.star");
      var ratingMessage = $(this).data("message");
      
        // Set the review input value
        $("input#review[name='review']").val(onStar);
    
      var msg = "";
      if (onStar > 1) {
        msg = onStar;
      } else {
        msg = onStar;
      }
      
      $(document).find('#review').val(onStar);
      $('.rating-component .starrate .ratevalue').val(msg);
     
      $(".fa-smile-wink").show();
      
      $(".button-box .done").show();
    
      if (onStar === 5) {
        $(".button-box .done").removeAttr("disabled");
      } else {
        $(".button-box .done").attr("disabled", "true");
      }
    
      for (i = 0; i < stars.length; i++) {
        $(stars[i]).removeClass("selected");
      }
    
      for (i = 0; i < onStar; i++) {
        $(stars[i]).addClass("selected");
      }
    
      $(".status-msg .rating_msg").val(ratingMessage);
      $(".status-msg").html(ratingMslick-slideressage);
      $("[data-tag-set]").hide();
      $("[data-tag-set=" + onStar + "]").show();
    });

    $(".feedback-tags  ").on("click", function () {
      var choosedTagsLength = $(this).parent("div.tags-box").find("input").length;
      choosedTagsLength = choosedTagsLength + 1;
    
      if ($(this).hasClass("choosed")) {
        $(this).removeClass("choosed");
        choosedTagsLength = choosedTagsLength - 2;
      } else {
        $(this).addClass("choosed");
        $(".button-box .done").removeAttr("disabled");
      }
    
      console.log(choosedTagsLength);
    
      if (choosedTagsLength <= 0) {
        $(".button-box .done").attr("enabled", "false");
      }
    });



    $(".compliment-container .fa-smile-wink").on("click", function () {
      $(this).fadeOut("slow", function () {
        $(".list-of-compliment").fadeIn();
      });
    });


    $(".done").on("click", function () {
      $(".rating-component").hide();
      $(".feedback-tags").hide();
      $(".button-box").hide();
      $(".submited-box").show();
      $(".submited-box .loader").show();
    
      setTimeout(function () {
        $(".submited-box .loader").hide();
        $(".submited-box .success-message").show();
      }, 1500);
    });

</script>
@endpush
