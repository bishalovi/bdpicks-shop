@php $cart = session()->get('cart', []); @endphp

<aside class="card border-0 shadow-sm rounded-4">
  <!-- Card Header -->
  <article class="card-body">
    <header class="mb-3">
      <h4 class="card-title fw-bold" style="font-family: 'Hind Siliguri', sans-serif; font-size: 18px;">
        Order Details
      </h4>
    </header>

    <!-- Table Responsive -->
    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th class="fw-bold text-dark" style="font-family: 'Hind Siliguri', sans-serif;">Image</th>
            <th class="fw-bold text-dark" style="font-family: 'Hind Siliguri', sans-serif;">Product</th>
            <th class="fw-bold text-dark" style="font-family: 'Hind Siliguri', sans-serif;">Price</th>
            <th class="fw-bold text-dark" style="font-family: 'Hind Siliguri', sans-serif;">QTY</th>
            <th class="fw-bold text-dark" style="font-family: 'Hind Siliguri', sans-serif;">Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @php
            $total = 0;
            $discount = 0;
          @endphp

          @foreach($cart as $key => $item)
            @php
              $price = $item['price'] * $item['quantity'];
              $total += $price;
              $discount += $item['discount'] * $item['quantity'];
            @endphp
            <tr class="cart-item">
              <!-- Image + Remove -->
              <td class="align-middle" style="width:70px;">
                <a href="{{ route('front.products.show', [$item['product_id']]) }}">
                  <img src="{{ getImage('products', $item['image']) }}" class="img-fluid rounded" style="max-width:50px;">
                </a>
              </td>

              <!-- Product Name -->
              <td class="align-middle" style="font-family: 'Hind Siliguri', sans-serif;">
                {{ $item['name'] }}
                @if($item['is_free_shipping'])
                <span class="badge bg-info">Free Ship</span>
                @endif
              </td>

              <!-- Price -->
              <td class="align-middle" style="font-family: 'Hind Siliguri', sans-serif;">
                {{ priceFormate($item['price']) }}
              </td>

              <!-- Quantity -->
              <td class="product-quantity" data-title="Qty">
                <div class="pro-qty" data-segment="{{ request()->segment(1)}}" data-href="{{ route('front.carts.edit',[$key])}}">
                  <span class="dec qtybtn">-</span>
                  <input type="number" class="quantity-input" value="{{ $item['quantity'] }}" style="font-family: 'Hind Siliguri', sans-serif">
                  <span class="inc qtybtn">+</span>
                </div>
              </td>

              <!-- Total -->
              <td class="align-middle" style="font-family: 'Hind Siliguri', sans-serif;">
                {{ priceFormate($price) }}
              </td>

              <!-- Remove -->
              <td class="align-middle">
                <a data-href="{{ route('front.carts.destroy',[$key])}}" class="btn btn-danger remove_item" type="button"><i class="fa fa-trash"></i></a> 
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </article>

  <!-- Totals Section -->
  <article class="card-body border-top bg-light rounded-bottom">
    <div class="d-flex justify-content-between mb-2" style="font-family: 'Hind Siliguri', sans-serif;">
      <span class="fs-3 fw-bold text-dark">Subtotal:</span>
      <span class="fs-3 fw-bold text-dark">{{ priceFormate($total) }}</span>
    </div>

    <div class="d-flex justify-content-between mb-2" style="font-family: 'Hind Siliguri', sans-serif;">
      <span class="fs-3 fw-bold text-dark">Delivery Charge:</span>
      <span class="text-danger delivery_charge fs-3 fw-bold text-dark">৳0</span>
    </div>

    <div class="d-flex justify-content-between fw-bold fs-5" style="font-family: 'Hind Siliguri', sans-serif;">
      <span class="fs-3 fw-bold text-dark">Total:</span>
      <span class="total fs-3 fw-bold text-dark">{{ priceFormate($total) }}</span>
    </div>
    <div class="mb-3 mt-3">
                            <div class="card bg-light">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <input type="radio" value="cash" form="checkout_form" checked class="form-check" name="payment_method"> <i class="fas fa-money-bill-wave"></i> Cash on Delivery 
                                </div>
                            </div>
                        </div>

    <input type="hidden" value="{{ $total }}" id="subtotal">
    <input type="hidden" value="{{ $total }}" name="amount" id="amount">
  </article>
</aside>
