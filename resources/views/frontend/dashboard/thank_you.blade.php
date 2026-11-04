@extends('frontend.app')
@section('content')

<main class="main-wrapper">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg rounded-4 text-center p-5" style="background: linear-gradient(135deg, #F5F7FA 0%, #FFFFFF 100%); font-family: 'Hind Siliguri', sans-serif;">
                    
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="90" height="90" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                        </svg>
                    </div>

                    <!-- Thank You Text -->
                    <h1 class="fw-bold mb-3" style="font-family: 'Hind Siliguri', sans-serif; font-size: 3rem; color:#0FA298;">ধন্যবাদ !</h1>
                    <h3 class="text-muted mb-3" style="font-family: 'Hind Siliguri', sans-serif; font-size: 2rem;">আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে।</h3>
                    <p class="mb-4 px-lg-5" style="color:#555; font-size: 1.8rem; font-family: 'Hind Siliguri', sans-serif">
                        আমাদের একজন বিক্রয় প্রতিনিধি শীঘ্রই আপনার সাথে যোগাযোগ করবে অর্ডার নিশ্চিত করার জন্য।
                    </p>

                    <!-- Invoice -->
                    <p class="mb-4" style="font-size: 1.8rem; font-family: 'Hind Siliguri', sans-serif">
                        আপনার অর্ডার নম্বর: 
                        <b>
                            <a href="{{ route('front.orders.show', [$order->id]) }}" target="_blank" class="text-decoration-none">
                                #{{$order->invoice_no}}
                            </a>
                        </b>
                    </p>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center flex-wrap gap-3">
                        <a href="{{ route('front.home') }}" class="btn main-bg text-white rounded-pill px-5 py-3 shadow-sm fs-3" style="font-family: 'Hind Siliguri', sans-serif">হোমে ফিরে যান</a>
                        <!--<a href="{{ route('front.dashboard.index') }}" class="btn btn-outline-success rounded-pill px-5 py-3 shadow-sm fs-5">ড্যাশবোর্ড</a>-->
                        <a target="_blank" href="{{ route('front.orders.show', [$order->id]) }}" class="btn btn-outline-primary rounded-pill px-5 py-3 shadow-sm fs-3" style="font-family: 'Hind Siliguri', sans-serif;">ইনভয়েস প্রিন্ট করুন</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>
<?php
$number = $order->user->mobile ?? $order->mobile;
?>
<!-- DataLayer Script for Analytics -->
<script>
setTimeout(customerPercentageCheck, 500);
function customerPercentageCheck(){
    const customerPhone = @json($number);
    const customer_id = @json($order->user_id);
    if (!customerPhone) {
        console.error('Phone number is required');
        return;
    }
    $.ajax({
        url: "{{ route('courierPercentage') }}",
        type: 'GET',
        dataType: 'json',
        data: {'phone': customerPhone,'id':customer_id},
        success: function(response) {
            console.log('Customer percentage data:', response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching customer percentage:', error);
        },
        complete: function() {
            
        }
    });
}


function generateDataLayer(transaction_id) {
    var items = [];
    @foreach($order->details as $detail)
        items.push({
            item_id: "{{ $detail->product_id }}",
            item_name: "{{ $detail->product->name }}",
            price: {{ $detail->unit_price }},
            quantity: {{ $detail->quantity }}
        });
    @endforeach

    let total = {{ $order->final_amount }};
    let ship_charge = {{ $order->shipping_charge }};
    let first_name = "{{ $order->first_name }}";
    let mobile = "{{ $order->mobile }}";
    let shipping_address = "{{ $order->shipping_address }}";

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
    
    const eventId = 'PUR_' + transaction_id;
    if (typeof fbq === 'function') {
        fbq('track', 'Purchase', {
            value: total,
            currency: 'BDT',
            contents: items.map(i => ({
                id: i.item_id,
                quantity: i.quantity,
                item_price: i.price
            })),
            content_type: 'product',
            content_ids: items.map(i => i.item_id)
        }, { eventID: eventId });
    }
}

setTimeout(() => {
    generateDataLayer("{{ $order->id }}");
}, 500);
</script>

@endsection
