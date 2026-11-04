@extends('frontend.app')
@section('content')

<style>
  .stripe-button-el {
	display: none;

}    
  input[type='text'], input[type='number'], #selectCourier {
      border: 1px solid #00276C;
    }
    
    .form-group label{
        font-family: 'Hind Siliguri', sans-serif;
    }
    @media (max-width: 767px) {
            .table>:not(caption)>*>* {
                font-size: 12px;
            }
        }
        @media (max-width: 767px) {
            .pro-qty input {
                font-size: 12px;
                width: 32px;
                margin: 0;
            }
        }
        @media (max-width: 767px) {
            .checkout_details * {
                font-size: 13px;
            }
            .product-quanity{
                width: 50px;
            }
        }
        .hind{
            font-family: 'Hind Siliguri', sans-serif;
        }
</style>  

@php
use App\Models\Information;
use App\Models\BanglaText;
$info = Information::first();
$bangla_text = BanglaText::first();
$coupon_visibility = $info->coupon_visibility;
$cart = session()->get('cart', []);

$is_free = 0;
foreach($cart as $key => $item){
    if(!empty($item['is_free_shipping']) && $item['is_free_shipping'] == 1){
        $is_free = 1;
        break;
    }
}

@endphp
<main class="main-wrapper">
 <section class="section-content py-5" style="margin-top:60px; background: #f5f6fa;">
    <div class="container">
        <form action="{{ route('front.checkouts.store')}}" method="POST" id="checkout_form">
            @csrf
            <div class="row g-4">
                
                <!-- Checkout Form -->
                <div class="col-lg-6">
                    <aside class="card border-0 shadow-sm rounded-4 p-4">
                        <h4 class="mb-4 fw-bold" style="font-family: 'Hind Siliguri', sans-serif; text-align: justify;">
                            {{ $bangla_text->checkout_form_top_text }}
                        </h4>
                        
                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label fs-2 text-dark hind">{{ $bangla_text->name_text }}</label>
                            <input type="text" name="first_name" id="name" class="form-control rounded-3" placeholder="">
                        </div>

                        <!-- Mobile -->
                        <div class="mb-3">
                            <label class="form-label fs-2 text-dark hind">{{ $bangla_text->mobile_text }}</label>
                            <input type="text" maxlength="11" name="mobile" id="mobile" class="form-control rounded-3" placeholder="">
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label class="form-label fs-2 text-dark hind">{{ $bangla_text->address_text }}</label>
                            <input type="text" name="shipping_address" id="address" class="form-control rounded-3" placeholder="">
                        </div>

                        <input type="hidden" name="ip_address" id="ip_address" value="">

                        <!-- Delivery Charge -->
                        @php
                            $shipping_value = [];
                            foreach($cart as $item) { $shipping_value[] = $item['is_free_shipping']; }
                        @endphp

                        <div class="mb-3">
                            @if(in_array(null, $shipping_value) || $is_free != 1)
                                <label class="form-label fs-2 text-dark hind">{{ $bangla_text->delivery_text }}</label>
                                <select required name="delivery_charge_id" id="selectCourier" class="form-select rounded-3 fs-2" style="font-family: 'Hind Siliguri', sans-serif;">
                                    @foreach($charges as $charge)
                                        <option value="{{ $charge->id }}" data-charge="{{ $charge->amount }}">
                                            {{ $charge->title }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <label class="form-label fs-2 text-dark hind">ফ্রী শিপিং</label>
                                <select name="delivery_charge_id" class="form-select rounded-3 fs-2">
                                    <option value="0">ফ্রী শিপিং</option>
                                </select>
                            @endif
                        </div>
                        <style>
                            input[type=checkbox], input[type=radio]{
                                opacity: 1;
                                font-size: 18px;
                                position: static;
                                height: 30px;
                                width: 20px;
                            }
                        </style>
                        <!-- Payment Method -->
                        

                        <!-- Submit Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" id="chk_btn" class="btn btn-lg fw-bold main-bg fs-2 text-white rounded-3 hind">
                                {{ $bangla_text->order_confirm_text }}
                            </button>
                        </div>
                    </aside>
                </div>

                <!-- Order Details -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 orderDetails bg-white sticky-top" style="top: 100px;">
                        @include('frontend.cart.details')
                    </div>
                </div>

                <!-- Hidden Cart Data -->
                @foreach($cart as $item)
                    <div class="cart-item-data" 
                         data-product-id="{{ $item['product_id'] }}" 
                         data-product-name="{{ $item['name'] }}" 
                         data-category-name="{{ $item['category_name'] }}"
                         data-price="{{ $item['price'] }}" 
                         data-quantity="{{ $item['quantity'] }}">
                    </div>
                @endforeach
                <input type="hidden" name="total_cart_price" value="{{ $totalPrice }}">
            </div>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fields = ['name', 'mobile', 'address', 'selectCourier'];

    // Load saved values from localStorage
    fields.forEach(function(field) {
        const el = document.getElementById(field);
        if (el) {
            if (localStorage.getItem(field)) {
                el.value = localStorage.getItem(field);
            } else if (field === 'selectCourier') {
                // Select option with the highest data-charge if no localStorage
                let maxCharge = -1;
                let maxOption = null;
                el.querySelectorAll('option').forEach(function(option) {
                    const charge = Number(option.dataset.charge) || 0;
                    if (charge > maxCharge) {
                        maxCharge = charge;
                        maxOption = option;
                    }
                });
                if (maxOption) {
                    maxOption.selected = true;
                }
            }
        }
    });

    // Save input changes to localStorage
    fields.forEach(function(field) {
        const el = document.getElementById(field);
        if (el) {
            el.addEventListener('input', function() {
                localStorage.setItem(field, el.value);
            });
            // For select element, also save change
            if (el.tagName === "SELECT") {
                el.addEventListener('change', function() {
                    localStorage.setItem(field, el.value);
                });
            }
        }
    });

    // On form submit, clear localStorage
    document.getElementById('checkout_form').addEventListener('submit', function() {
        fields.forEach(function(field) {
            localStorage.removeItem(field);
        });
    });

    // Update total on page load if needed
    calculate_total();
});
</script>


    <!-- Start Checkout Area  -->
    <div class="axil-checkout-area axil-section-gap">
        <div class="container">
            <form action="{{ route('front.checkouts.store')}}" method="POST" id="">
                @csrf

                <div class="row">
                    <div class="col-lg-5">
                         <div class="axil-checkout-notice" style="display: {{ ($coupon_visibility == '1') ? 'block' : 'none' }}"> 
                            
                             <div class="axil-toggle-box">
                                <div class="toggle-bar"><i class="fas fa-pencil"></i> Have a coupon? <a href="javascript:void(0)" class="toggle-btn">Click here to enter your code <i class="fas fa-angle-down"></i></a>
                                </div>

                                <div class="axil-checkout-coupon toggle-open">
                                    <p>If you have a coupon code, please apply it below.</p>
                                    <div class="input-group">
                                        <input placeholder="Enter coupon code" type="text" id="coupon_code">
                                        <div class="apply-btn">
                                            <button type="button" class="axil-btn btn-bg-primary" id="coupon_apply">Apply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div> 
                        <div class="axil-checkout-billing d-none">
                            <h4 class="title mb--30">Billing details</h4>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label style="color: black;">আ  <span>*</span></label>
                                        <input type="text" id="first_name" class="first_name" placeholder="Adam" value="" name="">
                                        <span id="errfirst_name" style="color: red;"></span>
                                        <span id="errfirst_name1" style="color: red;" class="text-danger">{{ $errors->first('first_name')  }}</span>
                                    </div>
                                </div>
                               
                            </div>

                            <div class="form-group">
                                <label style="color: black;">   <span>*</span></label>
                                <input type="tel" id="mobile" class="mobile" name="" value="">
                                <span id="errmobile" style="color: red;"></span>
                              <span id="errmobile1" style="color: red;" class="text-danger">{{ $errors->first('mobile')  }}</span>
                            </div>
                          
                          <tr class="order-shipping">
                                            <td colspan="2">    
                                              
                                              <!--
                                                <div class="shipping-amount" style="margin-bottom: 10px;">
                                                    <span class="title" style="color: black;">Shipping Method</span>
                                                    <span style="margin-left: 20px;color: black;" class="amount" id="charge">0.00</span>
                                                </div>

                                               -->
                                              
                                              	@foreach($charges as $key=>$charge)
                                                <div class="input-group">
                                                    <input type="radio" value="{{ $charge->id}}" class="charge_radio" id="{{ $charge->id}}" {{ $charge->id==2 ?'checked':''}}  name="" data-charge="{{ $charge->amount}}">
                                                    <label style="color: black;" for="{{ $charge->id}}">{{ $charge->title}} {{ $charge->amount}}</label>
                                                  <span id="errshipping_address1" style="color: red;" class="text-danger">{{ $errors->first('delivery_charge_id')  }}</span>
                                                </div>
                                                @endforeach                                             
                                              
                                            </td>
                                        </tr>                            

                            <div class="form-group" style="margin-top: 35px;">
                                <label style="color: black;">  র া <span>*</span></label>
                                <textarea name="" id="shipping_address" class="shipping_address" placeholder="Shipping Address"></textarea>
                                <span id="errshipping_address" style="color: red;"></span>
                                <span id="errshipping_address1" style="color: red;" class="text-danger">{{ $errors->first('shipping_address')  }}</span>
                            </div>                         
                            
                            
                        </div>
                    </div>
                    <div class="col-lg-7 d-none">
                        <div class="axil-order-summery order-checkout-summery" style="padding: 0px !important;background: none;">
                            <h5 class="title mb--20">Your Order</h5>
                            <div class="summery-table-wrap" style="padding: 0px !important;">
                                <table class="table table-bordered table-responsive">
                                    <thead>
                                        <tr style="border: none;">
                                            <th style="width: 46%;border-left: 0px;border-right: 0px;padding: 10px 0px;">Product</th>
                                           <th style="width: 18%;border-left: 0px;border-right: 0px;padding: 10px 0px;">Price</th>
                                          <th style="width: 18%;border-left: 0px;border-right: 0px;padding: 10px 0px;">Quantity</th>
                                            <th style="width: 18%;border-left: 0px;border-right: 0px;padding: 10px 0px;">Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @php
                                            $total=0;
                                      		$discount=0;
                                        @endphp
                                        
                                        @foreach($cart as $key=>$item)
                                        @php
                                        $price=$item['price']*$item['quantity'];
                                        $total +=$price;
                                      	$discount +=$item['discount']*$item['quantity'];
                                        @endphp
                                        <tr class="order-product">                                          
                                            <td style="padding: 10px 0px;">{{ $item['name']}}</td>
                                            <td style="padding: 10px 0px;"> {{ priceFormate($item['price'])}}</td> 
                                            <td style="padding: 10px 0px;"> {{ $item['quantity'] }}</td> 
                                            <td style="padding: 10px 0px;"> {{ priceFormate($price)}}</td>
                                        </tr>
                                        @endforeach
                                         
                                      
                                      	<tr class="order-total" style="border-bottom: none;">
                                            <td style="font-size: 17px;padding: 10px 0px;font-family: 'Hind Siliguri', sans-serif;">Sub Total</td>
                                            <td></td>
                                            <td></td>
                                            <td style="font-size: 15px;padding: 10px 0px;font-family: 'Hind Siliguri', sans-serif" class="">{{ priceFormate($total+$discount)}}</td>
                                        </tr>
                                      
                                      	
                                      	@if($discount>0)
                                        <tr class="order-total">
                                            <td style="font-size: 17px;"> Discount </td>
                                            <td class="">- {{ priceFormate($discount)}}</td>
                                        </tr>
                                      	@endif
                                      	
                                      	@if(getCouponDiscount()>0)
                                        <tr class="order-total">
                                            <td>Coupon Discount</td>
                                            <td class="">- {{ priceFormate(getCouponDiscount())}}</td>
                                        </tr>
                                      	@endif    
                                      
                                      
                                      <tr class="order-total" style="border-bottom: none;border-top: none;">
                                            <td style="font-size: 17px;padding: 10px 0px;font-family: 'Hind Siliguri', sans-serif">Total</td>
                                            <td></td>
                                            <td></td>
                                            <td style="font-size: 15px;padding: 10px 0px;font-family: 'Hind Siliguri', sans-serif" rowspan="2" class="order-total-amount">{{ priceFormate($total - getCouponDiscount())}}</td>
                                        </tr>
                                      
                                      @php 

                                        $total = $total - getCouponDiscount();
                                        $chr_amount = $charge->amount;
                                        $gr_total = $total + $chr_amount;
                                             
                                        @endphp
                                            <input type="hidden" value="{{ $gr_total }}" name="amount">
                                    </tbody>
                                </table>
                              
                            </div>
                            <div class="order-payment-method">
                                
                                <div class="single-payment">
                                 
                                    <div class="input-group" style="display: {{ $info->bkash == 0 ? 'none' : 'block' }} ">
                                        <input type="radio" id="radio6" name="payment_method" value="bkash">
                                        <label for="radio6" onclick="paymentInput('bkash')">Bkash ({{ $info->bkash_number}})</label>
                                    </div>                                      
                                  <div class="input-group" style="display: {{ $info->rocket == 0 ? 'none' : 'block' }} ">
                                        <input type="radio" id="radio7" name="payment_method" value="rocket">
                                        <label for="radio7" onclick="paymentInput('rocket')">Rocket ({{ $info->rocket_number}})</label>
                                  </div> 
                                  
                                  <div class="input-group" style="display: {{ $info->nogod == 0 ? 'none' : 'block' }} ">
                                        <input type="radio" id="radio8" name="payment_method" value="nogod">
                                        <label for="radio8" onclick="paymentInput('nogod')" class="payment-online">Nogod ({{ $info->nogod_number}})</label>
                                  </div> 
                                  
                                   <div class="input-group" style="display: {{ $info->paypal == 0 ? 'none' : 'block' }} ">
                                        <input type="radio" id="radio13" name="payment_method" value="paypal">
                                        <label for="radio13" onclick="paymentInput('paypal')" class="payment-online">Paypal ({{ $info->paypal_account}})</label>
                                  </div> 
                                  
                                  <div class="input-group" style="display: {{ $info->stripe == 0 ? 'none' : 'block' }} ">
                                        <input type="radio" id="radio16" name="payment_method" value="stripe">
                                        <label for="radio16" onclick="paymentInput('stripe')" class="payment-online">Stripe ({{ $info->stripe_account}})</label>
                                  </div>
                                  
                                   <div class="input-group">
                                        <input type="radio" id="radio5" name="payment_method"  value="cash" checked>
                                        <label for="radio5" style="font-size: 18px;" onclick="paymentInput('cash')" class="payment-online">Cash on delivery</label>
                                        
                                    </div>
                                  	<div id="payment_input" style="display:none">
                                       <label for="pay_num">Account Number</label>
                                      <input type="text" name="pay_num" placeholder="Enter account number" id="pay_num"><br/>
                                      <label for="tnx_id">Transaction Number</label>
                                      <input type="text" name="tnx_id" placeholder="Enter trx number" id="tnx_id"><br/>
                                  </div>
                                </div>
                                 
                            </div>
                            <button type="button" id="chk_btn" class="axil-btn checkout-btn" style="background: #c2050b !important;color: white;">র র </button>
                            <div id="paypal-button-container" style="display: none;"></div>
                          
                          <!--<script-->
                          <!--    src="https://checkout.stripe.com/checkout.js"-->
                          <!--    class="stripe-button"-->
                          <!--    data-key="pk_test_51MqweQJRdIwi69uLBsb2pvAJXY9HUOVEiQRtkqK8YwkNdBDuBNRDXPPQa2qgt6M99jhlKoX5TLwAuLI89KBgwptf00bUlFLfC0"-->
                          <!--    data-name="Pay Here"-->
                          <!--    data-description="Pay With Stripe"-->
                          <!--    data-amount=""-->
                          <!--    data-currency="usd">-->
                          <!--  </script>-->
                          
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Checkout Area  -->
</main>

@endsection

@push('js')
<script src="{{ asset('frontend/js/checkout.js')}}"></script>


<script>
    // Fetch user's IP address using a third-party API
    fetch('https://api.ipify.org?format=json', {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('ip_address').value = data.ip;
    })
    .catch(error => {
        console.error('Error fetching IP address:', error);
    });
    
    $(document).on('change','#mobile', function (e) {
        e.preventDefault();
        let mobile = $(this).val();
        let name = $('#name').val();
        let address = $('#address').val();
        let url = "{{ route('incompleteStore') }}";
        
        var constantValue=1;
        if (!mobile || mobile.length < 11 || mobile.length > 11   ) {
            constantValue = 0;
        }

        if(constantValue==1){
            console.log(url);
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{'mobile': mobile, 'name': name, 'address':address},
                success: function (response) {
                    //  alert(response.message);
                    console.log(response.message);
                },
                error: function (xhr) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred';
                    // alert(errorMessage);
                    console.log(errorMessage);
                }
            });
        }
    });
    
</script>

    
<script type="text/javascript">

    $(document).ready(function(){
        // calculate_total();
        
        generateDataLayers();
        
      $(document).on('click', 'a.remove_item', function(e){
         e.preventDefault();
         let url = $(this).attr('data-href');
         let method = "DELETE";
         let segment = "checkouts";
         if(confirm('Are you sure?'))
         {
			$.ajax({
              url,
              method,
              data: {segment},
              success: function(res)
              {
               	if(res.success)
                {
                  toastr.success(res.msg);
                   $('div#cart-dropdown').html(res.html);
                   $(document).find('div.orderDetails').empty().html(res.html2);
                   $(document).find('div.cart_other_details').html(res.html3);
                  if(res.item <= 0)
                  {
                    document.location.href = res.url;
                  }
                }
                else{
                 	toastr.error('Someting went wrong!'); 
                }
                
                calculate_total();
              }
              
            });
         }
     
      });
      
    //   document.getElementsByClassName('stripe-button-el')[0].disabled = true;

      
        $('.charge_radio').click(function(){
            getCharge();
        });


        function getCharge(){
            
            var testval = $('input:radio:checked.charge_radio').map(function(){
                return Number($(this).data('charge')); }).get().join(",");
            $('span#charge').text(Number(testval).toFixed(2));
            let sub_total='{{$total - getCouponDiscount()}}';
            let total=Number(testval)+Number(sub_total);
            $('td.order-total-amount').text(total.toFixed(2));
            $('input[name="ship_charge"]').val(testval);
        }
      $("#selectCourier").change(function(e){
			calculate_total();
      });
      
      function generateDataLayers() {
        var items = [];
        $('.cart-item-data').each(function() {
            var product_id = $(this).data('product-id');
            var product_name = $(this).data('product-name');
            var category_name = $(this).data('category-name');
            var price = $(this).data('price');
            var quantity = $(this).data('quantity');
            items.push({
                item_id: product_id,
                item_name: product_name,
                item_category: category_name,
                price: price,
                quantity: quantity
            });
        });
        let total = $('input[name="total_cart_price"]').val();

        // Push data to data layer
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            event: 'begin_checkout',
            ecommerce: {
                currency: "BDT",
                value: total, 
                items: items
            }
        });
        
        if (typeof fbq === 'function') { 
            const eventID = "IC_{{ now()->format('Ymdhi') }}";
        
            fbq('track', 'InitiateCheckout', {
                value: total,
                currency: 'BDT',
                contents: items.map(i => ({
                    id: i.item_id,
                    quantity: i.quantity,
                    item_price: i.price
                })),
                content_type: 'product',
                content_ids: items.map(i => i.item_id)
            }, {
                eventID: eventID // ✅ pass eventID for deduplication
            });
        
            console.log("InitiateCheckout EventID:", eventID);
        }

    }
      
    });
    
    $(document).on('submit', 'form#checkout_form', function(e) {
    e.preventDefault();
    $('span.textdanger').text('');
    let ele = $('form#checkout_form');
    var url = ele.attr('action');
    var method = ele.attr('method');
    var formData = ele.serialize();
    var first_name = $('input[name="first_name"]').val();
    var mobile = $('input[name="mobile"]').val();
    var shipping_address = $('input[name="shipping_address"]').val();
    var ship_charge = $('input[name="ship_charge"]').val();
    
    // Function to generate data layer
    function generateDataLayer(transaction_id) {
        var items = [];
        $('.cart-item-data').each(function() {
            var product_id = $(this).data('product-id');
            var product_name = $(this).data('product-name');
            var category_name = $(this).data('category-name');
            var price = $(this).data('price');
            var quantity = $(this).data('quantity');
            items.push({
                item_id: product_id,
                item_name: product_name,
                item_category: category_name,
                price: price,
                quantity: quantity
            });
        });
        let total = $('input[name="total_cart_price"]').val();

        // Push data to data layer
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            event: 'purchase',
            ecommerce: {
                currency: "BDT",
                value: total, 
                shipping: ship_charge,
                transaction_id: transaction_id,
                items: items
            },
            customer: {
                first_name: first_name,
                phone: mobile,
                shipping_address: shipping_address
            }
        });
    }
      

    $.ajax({
        type: method,
        url: url,
        data: formData,
        success: function(res) {
            if (res.success == true) {
                var transaction_id = res.invoice_no;
                generateDataLayer(transaction_id);
                
                toastr.success(res.msg);
                if (res.url) {
                    document.location.href = res.url;
                } else {
                    window.location.reload();
                }

            } else if (res.success == false) {
                toastr.error(res.msg);
            }
        },
        error: function(response) {
            $.each(response.responseJSON.errors, function(field_name, error) {
                $(document).find('[name=' + field_name + ']').after('<span class="textdanger" style="color:red">' + error + '</span>');
            })
        }
    });
});
  
  function paymentInput(type)
      {
        if(type == 'cash'){
        document.getElementById('payment_input').style.display = 'none';
        document.getElementById('paypal-button-container').style.display = 'none';
        document.getElementById('chk_btn').style.display = 'block';
          document.getElementsByClassName('stripe-button-el')[0].style.display = 'none';
          document.getElementsByClassName('stripe-button-el')[0].disabled = true;
        }
		else if(type == 'bkash')
        {
          document.getElementById('payment_input').style.display = 'block';
          document.getElementById('paypal-button-container').style.display = 'none';
          document.getElementById('chk_btn').style.display = 'block';
          document.getElementsByClassName('stripe-button-el')[0].style.display = 'none';

        } else if(type == 'stripe')
        {
          document.getElementById('payment_input').style.display = 'none';
          document.getElementById('paypal-button-container').style.display = 'none';
          document.getElementById('chk_btn').style.display = 'none';           
          document.getElementsByClassName('stripe-button-el')[0].style.display = 'block';	
          document.getElementsByClassName('stripe-button-el')[0].disabled = false;
        }  else if(type == 'rocket')
        {
          document.getElementById('payment_input').style.display = 'block';
          document.getElementById('paypal-button-container').style.display = 'none';
          document.getElementById('chk_btn').style.display = 'block';
          document.getElementsByClassName('stripe-button-el')[0].style.display = 'none';

        } else if(type == 'nogod')
        {
          document.getElementById('payment_input').style.display = 'block';
          document.getElementById('paypal-button-container').style.display = 'none';
          document.getElementById('chk_btn').style.display = 'block';
          document.getElementsByClassName('stripe-button-el')[0].style.display = 'none';
        }
        
        else{
          document.getElementById('payment_input').style.display = 'none';
          document.getElementById('paypal-button-container').style.display = 'block';
          document.getElementById('chk_btn').style.display = 'none';  
          document.getElementsByClassName('stripe-button-el')[0].style.display = 'none';
        }
      }
    
    
</script>


// <script>  
  
//     paypal.Buttons({
//       onClick(){     
//         var first_name = document.getElementById("first_name").value;
//         var mobile = document.getElementById("mobile").value;
//         var shipping_address = document.getElementById("shipping_address").value;     
        
//         $('#errfirst_name').text("");  
//         $('#errmobile').text("");          
//         $('#errshipping_address').text("");  
//         $('#errfirst_name1').text("");  
//         $('#errmobile1').text("");          
//         $('#errshipping_address1').text("");  
        
        
//         if(first_name.length == 0) {
//             $('#errfirst_name').text("First Name Is Required!!");              
//           } 
        
//         if(mobile.length == 0) {
//             $('#errmobile').text("Mobile Is Required!!");              
//           } 
        
//         if(shipping_address.length == 0) {
//             $('#errshipping_address').text("Shipping Address Is Required!!");              
//           } 
        
//         if(first_name.length == 0 || mobile.length == 0 || shipping_address.length == 0){
//         	return false;
//         }
        
//       },
      
//       // Sets up the transaction when a payment button is clicked
//       createOrder: (data, actions) => {
//         return actions.order.create({
//           purchase_units: [{
//             amount: {
//               value: '{{ $gr_total }}' // Can also reference a variable or function
//             }
//           }]
//         });
//       },
//       // Finalize the transaction after payer approval
//       onApprove: (data, actions) => {
//         return actions.order.capture().then(function(orderData) {
//           // Successful capture! For dev/demo purposes:
//          // console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
//           const transaction = orderData.purchase_units[0].payments.captures[0];
//          // alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
//           // When ready to go live, remove the alert and show a success message within this page. For example:
//           // const element = document.getElementById('paypal-button-container');
//           // element.innerHTML = '<h3>Thank you for your payment!</h3>';
//           // Or go to another URL:  actions.redirect('thank_you.html');

//           var firstname = $('.first_name').val();
//           var lastname = $('.last_name').val();  
//           var mobile = $('.mobile').val();  
//           var email = $('.email').val();  
//           var shipping_address = $('.shipping_address').val();
//           var note = $('.note').val();
//           var delivery_charge_id = $('.delivery_charge_id').val();
//           var tnx_id = transaction.id

//           $.ajax({
//             method: "POST",
//             url: "{{ route('front.store.checkout') }}",
//             data: {
//                 'firstname': firstname,
//                 'lastname': lastname,
//                 'mobile': mobile,
//                 'email': email,               
//                 'shipping_address': shipping_address,
//                 'note': note,
//                 'delivery_charge_id': delivery_charge_id,
//                 'tnx_id': tnx_id
//             },
//             success: function(response){
//             window.location.href = response.url    
//             }
//         }); 

//         });
//       }
//     }).render('#paypal-button-container');
//   </script>

@endpush