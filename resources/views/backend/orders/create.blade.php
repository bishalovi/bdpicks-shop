@extends('backend.app')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">SIS</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">CRM</a></li>
                    <li class="breadcrumb-item active">Order Create</li>
                </ol>
            </div>
            <h4 class="page-title">Order Create</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.store')}}" id="ajax_form">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 row">
                            
                            <div class="col-md-4">
                                <label class="form-label">Pick a Date</label>
                                <input type="date" class="form-control" required name="date" />
                            </div>
                            <div class="col-md-4 d-none">
                                <label for="validationDefault02" class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" id="validationDefault02"  name="invoice_no" />
                            </div>                            
                            <div class="col-md-4">
                                <label  class="form-label">Order Status</label>
                                <select class="form-control" name="status">
                                   @foreach($status as $key=>$s)
                                   <option value="{{$key}}"> {{ $s}} </option>
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
                        

                        <div class="col-lg-12 mt-3">
                            <table class="table table-centered table-nowrap mb-0" id="product_table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>Size</th>
                                        <th>Color</th>
                                        <th style="width: 120px;">Quantity</th>
                                        <th style="width: 150px;">Sell Price</th>
                                        <th style="width: 150px;">Discount</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="data">
                                   
                                </tbody>
                            </table>
                        </div>


                        <div class="col-lg-12 row mt-3">
                            <div class="col-md-3">
                                <label  class="form-label"> Courier  </label>
                                <select class="form-control" name="courier_id">
                                    <option value="" data-charge="0">Select One</option>
                                    @foreach($couriers as $courier)
                                    <option value="{{ $courier->id }}"> {{ $courier->name }} </option>
                                    @endforeach
                                </select>
                            </div> 
                            
                            <div class="col-md-3">
                                <label  class="form-label"> Customer First Name</label>
                                <input type="text" class="form-control"  name="first_name"  />
                            </div> 

                            <div class="col-md-3">
                                <label  class="form-label"> Customer Last Name</label>
                                <input type="text" class="form-control" name="last_name" />
                            </div> 


                            <div class="col-md-3">
                                <label  class="form-label"> Customer Mobile</label>
                                <input type="text" class="form-control" name="mobile" />
                            </div> 

                            <div class="col-md-6">
                                <label  class="form-label"> Customer Address</label>
                                <textarea rows="3" name="shipping_address" class="form-control"></textarea>
                            </div>
							<div class="row for_redx d-none">
                             <h5 class="text-danger mt-3">These fields only for Redx Courier Service</h5>
                             <div class="col-md-3">
                                <label  class="form-label">Choose Area</label>
                             	<select class="form-control select2" id="area_select">
                                  <option value="">Select One</option>
                                  @foreach($areas as $key=>$area)
                                  <option value="{{ $area['id'] }}">{{ $area['name'] }}</option>
                                  @endforeach
                                </select> 
                            </div>                            
                            <div class="col-md-3">
                                  <label  class="form-label">Area ID</label>
                                  <input type="text" readonly class="form-control" id="area_id" name="area_id" value=""/>
                            </div>                          
                            <div class="col-md-3">
                                  <label  class="form-label">Area Name</label>
                                  <input type="text" readonly class="form-control" id="area_name" name="area_name" value=""/>
                            </div>
                          </div>                           
                          <div class="row for_pathao d-none">
                            <h5 class="text-danger mt-3">These fields only for Pathao Courier Service</h5>                              
                            <div class="col-md-3">
                                <label  class="form-label">Choose City</label>
                             	<select class="form-control select2" id="city_select" name="city">
                                  <option value="">Select One</option>
                                  @foreach($cities as $key=>$city)
                                  <option value="{{ $city['city_id'] }}">{{ $city['city_name'] }}</option>
                                  @endforeach
                                </select> 
                            </div>                           
                            <div class="col-md-3">
                                <label  class="form-label">Choose Zone</label>
                             	<select class="form-control select2" id="zone_select" name="state">
                                  <option value="">Select One</option>
                                </select> 
                            </div>                         
                            <div class="col-md-3">
                                <label  class="form-label">Choose Area</label>
                             	<select class="form-control select2" name="area_id">
                                  <option value="">Select One</option>
                                </select> 
                            </div>                                                     
                            <div class="col-md-3">
                                  <label  class="form-label">Item Weight</label>
                                  <input type="number" class="form-control" id="weight" step="0.5" min="0.5" max="10" name="weight" value="0.5"/>
                            </div>
                          </div>
                        </div>


                        <div class="col-lg-12 row mt-3">
                            
                            <div class="col-md-3">
                                <label  class="form-label"> Delivery Charge </label>
                                <select class="form-control" name="delivery_charge_id" id="delevery_charge">
                                    <option value="" data-charge="0">Select One</option>
                                    @foreach($charges as $charge)
                                    <option value="{{ $charge->id }}" data-charge="{{ $charge->amount }}"> {{ $charge->title }} </option>
                                    @endforeach
                                </select>
                            </div> 

                            <div class="col-md-3">
                                <label  class="form-label"> Discount</label>
                                <input type="text" class="form-control" name="discount" id="discount_amount" />
                            </div> 

                            <div class="col-md-3">
                                <label  class="form-label">Total</label>
                                <input type="text" class="form-control"  name="final_amount" id="purchase_total" />

                                <input type="hidden" value="0" name="shipping_charge" id="shipping_charge" />
                            </div> 
                        </div>


                        <div class="col-12 mt-2 p-1">
                            <label  class="form-label"> Note</label>
                            <textarea class="form-control" name="note" placeholder="note"></textarea>
                        </div>



                        <div class="col-12 mt-2">
                            <button class="btn btn-success" type="submit">Save</button>
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
                    toastr.error('Product Or Stock Not Found');
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

        if (current_stock>stock) {
            toastr.error('Product Stock Not Available');
            $(this).val(stock);
            calculateSum();
            return false;
        }
    });



    $(document).on('blur',".quantity, .unit_price, .unit_discount",function(e) {
        calculateSum();    
    });

    $(document).on('change',"#delevery_charge",function(e) {
        calculateSum();    
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
            row_discount+=Number(qty *discount);
            tblrow.find('td.row_total').text(row_total.toFixed(2));
            sub_total+=row_total;
        });
        sub_total+=charge;

        $('input#purchase_total').val(sub_total.toFixed(2));
        $('input#discount_amount').val(row_discount.toFixed(2));
        $('input#shipping_charge').val(charge.toFixed(2));
    }

  
</script>

@endpush