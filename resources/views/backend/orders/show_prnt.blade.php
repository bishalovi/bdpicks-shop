<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Order Invoice </title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('frontend/css/vendor/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('frontend/css/invoice_style.css')}}">
    <style>
        @media print
        {    
            .no-print, .no-print *
            {
                display: none !important;
            }
        }
    </style>
    
</head>
    <body onload="self.print()">
     
        <div class="col-lg-7 col-md-10 col-12 m-auto pt-5">
            <div class="container d-flex justify-content-between">
              <img src="{{getImage('uploads/img', $info->site_logo)}}" alt="" style="height: 35px;">
              <div align="right">
                <button class="btn btn-secondary btn-sm no-print" id="printBtn"><i class="fa fa-print"></i> Print Invoice</button>
            </div>
            </div>
            <div class="container-fluid header-box mb-4">
              <div class="header container pe-5">
                <span class="px-5">Invoice</span>
              </div>
            </div>
            
            <div class="col-lg-10 m-auto">
          <div class="row mt-5 justify-content-between" style="margin-top: 1rem !important;">
            <h2 class="serif bold" style="font-size: 18px;">Invoice to : </h2>
          
            
             <div class="table-responsive">
            <table class="table">             
              <tbody>                
                <tr>
                    <td class="bold" style="border-bottom-width: 0px !important;padding: 0px !important;">{{ $item->first_name }} {{ $item->last_name }}</td>
                    <td class="bold" style="border-bottom-width: 0px !important;padding: 0px !important;">Invoice</td>
                   <td class="bold" style="border-bottom-width: 0px !important;padding: 0px !important;text-align: right;">ID: #{{ $item->invoice_no}}</td>
                </tr> 
                <tr>
                    <td class="bold" style="border-bottom-width: 0px !important;padding: 0px !important;width: 68%;">{{ $item->shipping_address }}</td>
                  <td class="bold" style="border-bottom-width: 0px !important;padding: 0px !important;">Date</td>
                    <td class="bold" style="border-bottom-width: 0px !important;padding: 0px !important;text-align: right;">{{ dateFormate($item->date)}}</td>  
                   
                </tr> 
                
                <tr>
                    <td class="bold" style="border-bottom-width: 0px !important;padding: 0px !important;">{{ $item->mobile }}</td>                 
                   
                </tr> 
                              
              </tbody>
            </table>
          </div>
           
          </div>
        
          <div class="table-responsive border border-muted rounded-0">
            <table class="table table-striped table-borderless">
              @php 
              	$total = 0;
              @endphp
              <thead class="bg-dark text-light">
                <tr>
                  <th>SL.</th>
                  <th>Item Description</th>
                  <th>Color</th>
                  <th>Size</th>
                  <th>Price</th>
                  <th>Qty.</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach($item->details as $key=>$product)
                <tr>
                    <td>{{ $key+1}}</td>
                    <td>{{ $product->product->name}}  {{ $product->productsize?$product->productsize->title:''}}</td>
                    <td>
                        {{ $product->variations }}
                        <?php
                            if(!isset($product->variation->color->name) || $product->variation->color->name == null)
                            {
                                echo '';
                            } else {
                                if ($product->variation->color->name == 'Default')
                                {
                                    echo '';
                                } else {
                                    echo $product->variation->color->name;
                                }
                                
                            }
                        ?>
                        
                        
                    </td> 
                    <td>
                        
                        <?php
                            if(!isset($product->variation->size->title) || $product->variation->size->title == null)
                            {
                                echo '';
                            } else {
                                if($product->variation->size->title == 'free')
                                {
                                    echo '';
                                } else {
                                    echo $product->variation->size->title;
                                }
                                
                            }
                        ?>
                        
                        
                    </td> 
                    <td>{{ $product->product->sell_price }}</td>
                    <td>{{ $product->quantity}}</td>       
                  	@php 
                  		$row_total = $product->product->sell_price * $product->quantity;
                  		$total += $row_total;
                    @endphp
                    <td>{{ priceFormate($row_total)}}</td>
                </tr>              
                @endforeach                
              </tbody>
            </table>
          </div>
          <div class="row mt-3 mb-3 justify-content-between">
            <div class="left-info col-lg-6">
              <h5 class="font1 bold">{{ $product->order->note }}</h5>
            </div>
            <div class="right-info col-lg-6 row justify-content-between">
              <div class="col-6">
                <h5 class="bold">Sub Total : </h5>
                <h5 class="bold">Discount : </h5>
                <h5 class="bold">Delivery Charge : </h5>
               
              </div>
              <div class="text-end col-6">
                <h5>{{ priceFormate($total)}}</h5>
                <h5>{{ priceFormate($item->discount)}}</h5>
                <h5>{{ ($item->delivery_charge_id == 0) ? '0' : priceFormate($item->delivery_charge->amount)}}</h5>
                
              </div>
            </div>
          </div>
          
        </div>
            
      
        <div class="total-price col-6 ms-auto ps-4 bg-dark text-light">
          <div class="col-lg-10 d-flex mt-3 p-1 justify-content-between">
            <h4 class="font1" style="1.5rem !important;">Total:</h4>
            <h4 class="font1">
                @php
                $grand_total=$total-$item->discount+$item->delivery_charge->amount;
                @endphp
                {{ priceFormate($grand_total)}}</h4>
          </div>
        </div>
        <!--<div class="order-info-wrapper">-->
                
        <!--        <div class="order-info">-->
        <!--            <div>-->
        <!--                <h4>Bill To</h4>-->
        <!--                <p>{{ $item->first_name.' '.$item->last_name}}</p>-->
        <!--            </div>-->
        <!--            <div>-->
        <!--                <h4>Ship To</h4>-->
        <!--                <p> {{ $item->first_name.' '.$item->last_name}} </p>-->
        <!--                <p>{{ $item->shipping_address}}</p>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <div class="footer-line mt-5 mb-5 pe-5">
          <div class="signature ms-auto">
            <div class="signature-line"></div>
            <h5 class="text-center bold text-dark mt-2">Authorised Sign</h5>
          </div>
        </div>
        <div class="col-lg-5 ms-5 bold footer-address">
            <div class="col-7 d-flex justify-content-between">
              <h5 class="bold">Phone : </h5><span> {{$info->owner_phone}} </span>
              </div>
        </div>
        <div class="footer-address2 d-flex col-10 bg-dark text-light">
          Company Name : {{$info->site_name}} | Address :  {{$info->address}} 
        </div>
    </div>
    
      <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <script>
          let printBtn = document.querySelector('#printBtn');
          printBtn.addEventListener('click', function(){
              print();
          })
      </script>
    </body>
</html>
