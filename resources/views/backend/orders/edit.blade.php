@extends('backend.app')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">SIS</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">CRM</a></li>
                    <li class="breadcrumb-item active">Order Edit</li>
                </ol>
            </div>
            <h4 class="page-title">Order Edit</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.update', $item->id)}}" id="ajax_form">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-12 row">
                            
                            <div class="col-md-4">
                                <label class="form-label">Pick a Date</label>
                                <input type="date" class="form-control" value="{{$item->date}}" required name="date" />
                            </div>
                            <div class="col-md-4">
                                <label for="validationDefault02" class="form-label">Reference Number</label>
                                <input type="text" class="form-control" id="validationDefault02" value="{{ $item->ref}}" name="ref" />
                            </div>                            
                            <div class="col-md-4">
                                <label  class="form-label">Order Status</label>
                                <select class="form-control" name="status">
                                   @foreach($status as $key=>$s)
                                   <option value="{{$key}}" {{$key==$item->status ?'selected':''}}> {{ $s}} </option>
                                   @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-lg-12 mt-3">
                            <div class="row height d-flex justify-content-center align-items-center">

                                <div class="col-md-8">
                                    <div class="search">
                                        <input type="text" id="search" class="form-control" placeholder="product search here">
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        <div class="col-lg-12 my-3">
                            <table class="table table-centered table-nowrap mb-0" id="product_table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th style="width: 120px;">Quantity</th>
                                        <th style="width: 150px;">Sell Price</th>
                                        <th style="width: 150px;">Discount</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="data">
                                    @foreach($item->details as $line)
                                        <tr>
                                          	<td><img src="{{ getImage('products', $line->product->image) }}" height="50" width="50"/></td>
                                            <td>{{$line->product->name}}</td>
                                            <td>
                                                <input class="form-control quantity" name="quantity[]" type="number" value="{{$line->quantity}}" required min="1" data-qty="{{$line->quantity+ $line->variation->stocks->sum('quantity')}}"/>
                                                <input type="hidden" name="product_id[]" value="{{$line->product_id}}" required/>
                                                <input type="hidden" name="variation_id[]" value="{{$line->variation_id}}"/>
                                                <input type="hidden" name="order_line_id[]" value="{{$line->id}}"/>
                                                <input type="hidden" name="is_stock" value="{{ $line->product->is_stock }}">
                                            </td>
                                            <td><input class="form-control unit_price" name="unit_price[]" type="number" value="{{$line->unit_price}}" required/></td>
                                            <td><input class="form-control unit_discount" name="unit_discount[]" type="number" value="{{$line->discount * $line->quantity}}" readonly=""/></td>

                                            <td class="row_total">{{$line->unit_price * $line->quantity}}</td>
                                            <td><a class="remove btn btn-sm btn-danger"> <i class="mdi mdi-delete"></i></a></td>
                                        </tr> 
                                    @endforeach
                                </tbody>
                                <hr>
                            </table>
                        </div>


                            <div class="col-md-4">
                                <label  class="form-label"> Customer Name</label>
                                <input type="text" class="form-control" value="{{ $item->first_name}}" name="first_name"  />
                            </div>


                            <div class="col-md-4">
                                <label  class="form-label"> Customer Mobile</label>
                                <input type="text" class="form-control" value="{{ $item->mobile}}" name="mobile" />
                            </div> 
                            
                            <div class="col-md-4">
                                <label  class="form-label"> Delivery Charge </label>
                                <select class="form-control" name="delivery_charge_id" id="delevery_charge">
                                    <option value="" data-charge="0">Select One</option>
                                    @foreach($charges as $charge)
                                    <option value="{{ $charge->id }}" {{ $charge->id==$item->delivery_charge_id ?'selected':'' }} data-charge="{{ $charge->amount }}"> {{ $charge->title }} </option>
                                    @endforeach
                                </select>
                            </div> 

                            <div class="col-md-12 my-2">
                                <label  class="form-label"> Customer Address</label>
                                <textarea rows="3" name="shipping_address" id="shipping_address" class="form-control">{{ $item->shipping_address}}</textarea>
                            </div> 
                            <div class="col-md-4 d-none">
                                <label  class="form-label">Courier Tracking ID</label>
                                <input type="text" class="form-control" value="{{ $item->courier_tracking_id}}" name="courier_tracking_id" />
                            </div> 
                            
                            <div class="col-md-4">
                                <label  class="form-label"> Courier  </label>
                                <select class="form-control" name="courier_id">
                                    <option value="" data-charge="0">Select One</option>
                                    @foreach($couriers as $courier)
                                    <option value="{{ $courier->id }}" {{ $courier->id==$item->courier_id ? 'selected' : '' }}> {{ $courier->name }} </option>
                                    @endforeach
                                </select>
                            </div> 
                            <div class="col-md-4">
                                <label  class="form-label"> Discount</label>
                                <input type="text" class="form-control" value="{{ $item->discount}}" name="discount" id="discount_amount" />
                            </div> 

                            <div class="col-md-4">
                                <label  class="form-label">Total</label>
                                <input type="text" class="form-control" value="{{ $item->final_amount}}" name="final_amount" id="purchase_total" />
                                <input type="hidden" id="without_discount" value="{{ $item->final_amount + $item->discount - $item->shipping_charge }}">
                                <input type="hidden" value="0" name="shipping_charge" id="shipping_charge" />
                            </div>
                            
                           <div class="row for_redx {{ $item->courier_id != 1 ? 'd-none' : '' }}">
                             <h5 class="text-danger mt-3">These fields only for Redx Courier Service</h5>
                             <div class="col-md-3">
                                <label  class="form-label">Choose Area</label>
                             	<select class="form-control select2" id="area_select">
                                  <option value="">Select One</option>
                                  @if($areas!==null)
                                  @foreach($areas as $key=>$area)
                                  <option value="{{ $area['id'] }}" {{ $item->area_id ==  $area['id'] ? 'selected' : '' }}>{{ $area['name'] }}</option>
                                  @endforeach
                                  @endif
                                </select> 
                            </div>                            
                            <div class="col-md-3">
                                  <label  class="form-label">Area ID</label>
                                  <input type="text" readonly class="form-control" id="area_id" name="redx_area_id" value="{{ $item->area_id}}"/>
                            </div>                          
                            <div class="col-md-3">
                                  <label  class="form-label">Area Name</label>
                                  <input type="text" readonly class="form-control" id="area_name" name="area_name" value="{{ $item->area_name}}"/>
                            </div>
                          </div>                           
                          <div class="row for_pathao {{ $item->courier_id != 2 ? 'd-none' : '' }}">
                            <h5 class="text-danger mt-3">These fields only for Pathao Courier Service</h5>                            
                            <div class="col-md-3">
                                <label  class="form-label">Choose City</label>
                             	<select class="form-select" id="city_select" name="city">
                                  <option value="">Select One</option>
                                  @foreach($cities as $key=>$city)
                                  <option value="{{ $city['city_id'] }}" {{ $item->city ==  $city['city_id'] ? 'selected' : '' }}>{{ $city['city_name'] }}</option>
                                  @endforeach
                                </select> 
                            </div>                           
                            <div class="col-md-3">
                                <label  class="form-label">Choose Zone</label>
                             	<select class="form-select" id="zone_select" name="state">
                                  <option value="{{ $item->state }}">Select One</option>
                                </select> 
                            </div>                         
                            <div class="col-md-3">
                                <label  class="form-label">Choose Area</label>
                             	<select class="form-select" id="pathao_area_id" name="pathao_area_id">
                                  <option value="{{ $item->area_id }}">Select One</option>
                                </select> 
                            </div>                                                     
                            <div class="col-md-3">
                                  <label  class="form-label">Item Weight</label>
                                  <input type="number" class="form-control" id="weight" step="0.5" min="0.5" max="10" name="weight" value="{{ $item->weight != null ? $item->weight : '0.5' }}"/>
                            </div>
                          </div>
                            <div class="col-12 mt-2">
                                <label  class="form-label"> Note</label>
                                <textarea class="form-control" name="note" placeholder="note">{{$item->note}}</textarea>
                            </div>
    
                            <div class="col-12 mt-2">
                                <button class="btn btn-success" type="submit">Update</button>
                            </div>
                        
                         <div class="col-lg-12 mt-3">
                            <table class="table table-centered table-nowrap mb-0" id="">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 120px;">Order Id</th>
                                        <th>Product</th>
                                        <th style="width: 120px;">Customer</th>
                                         <th style="width: 120px;">IP Address</th>
                                        <th style="width: 150px;">Status</th>
                                        <th style="width: 150px;">Assign User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach($orderbyNumber as $orbynum)
                                    
                                    <tr>
                                        <td>{{ $orbynum->id }}</td>
                                       <td>
                                           
                                          <?php 
                                           foreach($orbynum->details as $line)
                                           {
                                              if(!isset($line->product->name) || $line->product->name == '')
                                              {
                                                ?> <span style="color: red;">Unavailable</span> <?php  
                                              } else {
                                                  echo \Illuminate\Support\Str::limit($line->product->name, 20);
                                              }
                                           }
                                          
                                          ?>  
                                         
                                         
                                        </td>
                                        <td>
                                            {{ $orbynum->first_name }} {{ $orbynum->last_name }}
                                        </td>
                                        <td>{{ $orbynum->ip_address }}</td>
                                        <td>{{ $orbynum->status }}</td>
                                      <td> {{ $orbynum->assign?$orbynum->assign->username:''}} </td>
                                    </tr> 
                                    @endforeach
                                 </tbody>
                            </table>
                        </div>

                    </div>
                        
                    </div>

                </form>
       
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection 

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    var path = "{{ route('admin.getOrderProduct') }}";
    const products=[];
    
    $( "#search" ).autocomplete({
        selectFirst: true, //here
        minLength: 2,
        source: function( request, response ) {
          $.ajax({
            url: path,
            type: 'GET',
            dataType: "json",
            data: {
               search: request.term
            },
            success: function( data ) {
                if (data.length ==0) {
                    toastr.error('Product Or Stock Found');
                }
                else if (data.length ==1) {
                    if(products.indexOf(data[0].id) ==-1){
                        productEntry(data[0]);
                        products.push(data[0].id);
                    }
                    $('#search').val('');
                }else if (data.length >1) {
                    response(data);
                }
            }
          });
        },
        select: function (event, ui) {
           if(products.indexOf(ui.item.id) ==-1){
                productEntry(ui.item);
                products.push(ui.item.id);
            }
           $('#search').val('');
           return false;
        }
      });

    function productEntry(item){


        $.ajax({
            url: '{{ route("admin.orderProductEntry")}}',
            type: 'GET',
            dataType: "json",
            data: {id:item.id},
            success: function( res ) {
                if (res.html) {
                    $('tbody#data').append(res.html);
                    calculateSum();
                }
            }
          });
    }

    $(document).on('click',".remove",function(e) {
        var whichtr = $(this).closest("tr");
        whichtr.remove();  
        calculateSum();    
    });

    $(document).on('blur change',".quantity",function(e) {
        let current_stock=Number($(this).val());
        let stock=Number($(this).data('qty'));
        let is_stock = $('input[name="is_stock"]').val();
        if(is_stock != '0') {
           if (current_stock>stock) {
                toastr.error('Product Stock Not Available');
                $(this).val(stock);
                calculateSum();
                return false;
            } 
        } else {
            calculateSum();
        }
        
    });


    calculateSum();    

    $(document).on('blur',".quantity, .unit_price, .unit_discount",function(e) {
        calculateSum();    
    });

    $(document).on('change',"#delevery_charge",function(e) {
        calculateSum();    
        calculateTotal();    
    });    
  
  
  $(document).on('change', 'select[name="courier_id"]', function(e){
    	let courier_id = $(this).val();
    	if(courier_id == 1)
        {
          	$(document).find('div.for_redx').removeClass('d-none');
          	$(document).find('div.for_pathao').addClass('d-none');
        }    	
    	
    	else if(courier_id == 2)
        {
          	$(document).find('div.for_pathao').removeClass('d-none');
          	$(document).find('div.for_redx').addClass('d-none');
        }
    
    	else {
            $(document).find('div.for_pathao').addClass('d-none');
          	$(document).find('div.for_redx').addClass('d-none');
        }
  });
  
  
    function updateAddressDropdowns(address) {
        if(address.length < 3) return;
    
        $.ajax({
            url: "{{ route('admin.fetch.address.details') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                address: address
            },
            success: function(res) {
                if(res.city_id) {
                    $("#city_select").val(res.city_id).trigger("change");
    
                    $.get("{{ url('/admin/zones-by-city') }}/" + res.city_id, function(zoneRes) {
                        let zones = zoneRes.zones || [];
                        let $zone = $("#zone_select");
                        $zone.empty().append('<option value="">Select One</option>');
                        zones.forEach(function(z) {
                            $zone.append('<option value="'+z.zone_id+'">'+z.zone_name+'</option>');
                        });
    
                        if(res.zone_id) {
                            $zone.val(res.zone_id).trigger("change");
    
                            $.get("{{ url('/admin/areas-by-zone') }}/" + res.zone_id, function(areaRes) {
                                let areas = areaRes.areas || [];
                                let $area = $("#pathao_area_id");
                                $area.empty().append('<option value="">Select One</option>');
                                areas.forEach(function(a) {
                                    $area.append('<option value="'+a.area_id+'">'+a.area_name+'</option>');
                                });
    
                                if(res.area_id) {
                                    $area.val(res.area_id).trigger("change");
                                }
                            });
                        }
                    });
                }
            },
            error: function(err) {
                console.error("Fetch address details failed:", err);
            }
        });
    }
    
    $(document).on("change", "#shipping_address", function() {
        updateAddressDropdowns($(this).val());
    });
    
    $(document).ready(function() {
        let address = $("#shipping_address").val();
        if(address && address.length > 3) {
            updateAddressDropdowns(address);
        }
    });
  
  
  $(document).on('change', '#city_select', function(e){
    
    let city = $(this).val();
    var url = "{{ route('admin.zonesByCity', ":city") }}";
	url = url.replace(':city', city);
    $.ajax({
        url,
        type: 'GET',
        dataType: "json",
        success: function(res){
          if(res.success)
          {
            let html = "<option value=''>Select One</option>";
            for(let i = 0; i < res.zones.length; i++)
            {
               html += "<option value='"+res.zones[i].zone_id+"' >"+res.zones[i].zone_name+"</option>";
            }
            
            $('#zone_select').html(html);
            
          }
        }
      
    });
    
  });
  
   $(document).on('change', '#zone_select', function(e){
      
    let zone = $(this).val();
    var url = "{{ route('admin.areasByZone', ":zone") }}";
	url = url.replace(':zone', zone);
    $.ajax({
        url,
        type: 'GET',
        dataType: "json",
        success: function(res){
          if(res.success)
          {
            let html = "<option value=''>Select One</option>";
            for(let j = 0; j < res.areas.length; j++)
            {
               html += "<option value='"+res.areas[j].area_id+"' >"+res.areas[j].area_name+"</option>";
            }
            
            $('select[name="area_id"]').html(html);
            
          }
        }
      
    });
    
  });

    function calculateSum() {

        let tblrows = $("#product_table tbody tr");
        let sub_total=0;
        let row_discount=0;
        let charge=Number($("#delevery_charge option:selected").data('charge'));
        tblrows.each(function (index) {
            let tblrow = $(this);
            let qty=Number(tblrow.find('td input.quantity').val());
            let amount=Number(tblrow.find('td input.unit_price').val());
            let discount=Number(tblrow.find('td input.unit_discount').val());
            let row_total=Number(qty *amount);
            row_discount+=Number(discount);
            tblrow.find('td.row_total').text(row_total.toFixed(2));
            sub_total+=row_total;
        });
        
        sub_total+=charge;
        // $('input#purchase_total').val(sub_total.toFixed(2));
        // $('input#discount_amount').val(row_discount.toFixed(2));
        $('input#shipping_charge').val(charge.toFixed(2));
    }
    $('input#discount_amount').on('blur', function() {
        calculateTotal();
    });
    function calculateTotal(){
        let charge=Number($("#delevery_charge option:selected").data('charge'));
        let discount = Number($('input#discount_amount').val()) || 0;
        let total = Number($('input#without_discount').val()) || 0;
    
        // Calculate
        let total_amount = charge + total - discount;
    
        // Debug (optional)
        console.log("Total:", total);
        console.log("Discount:", discount);
        console.log("Final Amount:", total_amount);
    
        // Update field
        $('input#purchase_total').val(total_amount.toFixed(2));
    }

  
</script>

@endpush