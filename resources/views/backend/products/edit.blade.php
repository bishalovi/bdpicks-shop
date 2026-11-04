@extends('backend.app')
@push('css')
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
                    <li class="breadcrumb-item active">Product Update</li>
                </ol>
            </div>
            <h4 class="page-title">Product Update</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.products.update',[$item->id])}}" id="ajax_form">
                    @csrf
                     {{ method_field('PATCH') }}
                    <div class="row">
                        
                        <div class="col-lg-4 mb-3">
                            <label  class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Product Name" value="{{ $item->name}}">
                        </div>
                      
                      	<div class="col-lg-4 mb-3">
                            <label  class="form-label">Product Sku</label>
                            <input type="text" name="sku" class="form-control" placeholder="Product Sku" value="{{ $item->sku}}">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label  class="form-label">Product Brand</label>
                            <select class="form-select" name="type_id">
                                <option value="">Select One</option>
                                @foreach($types as $type)
                                <option value="{{$type->id}}" {{$type->id == $item->type_id ?'selected':''}}> {{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div>
                                <img src="{{ getImage('products',$item->image)}}" width="50" class="mb-2">
                            </div>
                            <label for="example-email" class="form-label">Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <style>
                            .img-box{
                                position: relative;
                                padding: 5px;
                            }
                            .img-box a{
                                    position: absolute;
                                    top: 2px;
                                    right: 12px;
                                    font-size: 22px;
                                    font-weight: bold;
                                    color: red;
                            }
                        </style>
                        <div class="col-lg-4 mb-3">
                            <div class="d-flex">
                                @foreach ($item->images as $key => $image) 
                                <div class="img-box">
                                    <a href="{{ route('admin.deleteImage',[$image->id])}}" class="" onclick="return confirm(' you want to delete?');">&times;</a> 
                                    <img src="{{ getImage('products',$image->image)}}" width="50">
                                </div>
                                @endforeach
                            </div>
                            <label for="example-email" class="form-label">Multi Image</label>
                            <input type="file" name="images[]" class="form-control" multiple>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label for="example-email" class="form-label">Video Embedded Code</label>
                            <textarea name="video_link" class="form-control">{{ $item->video_link }}</textarea>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label  class="form-label">Product Category</label>
                            <select class="form-select" name="category_id">
                                <option value="">Select One</option>
                                @foreach($cats as $cat)
                                <option value="{{$cat->id}}" {{$cat->id == $item->category_id ?'selected':''}}> {{$cat->name}}</option>
                                @endforeach
                                    
                            </select>
                        </div>


                        <div class="col-lg-4 mb-3">
                            <label  class="form-label">Sub Category</label>
                            <select class="form-select" name="sub_category_id">
                                <option value="">Select One</option>
                                @foreach($subs as $sub)
                                <option value="{{$sub->id}}" {{$sub->id == $item->sub_category_id ?'selected':''}}> {{$sub->name}}</option>
                                @endforeach
                                
                            </select>
                        </div>

                        <!--<div class="col-lg-4 mb-3">-->
                        <!--    <label  class="form-label">Purchase Price</label>-->
                        <!--    <input type="text" name="purchase_price" class="form-control" value="{{ $item->purchase_price}}">-->
                        <!--</div>-->

                        <!--<div class="col-lg-4 mb-3">-->
                        <!--    <label  class="form-label">Sell Price</label>-->
                        <!--    <input type="text" name="sell_price" class="form-control" value="{{ $item->sell_price}}">-->
                        <!--</div>-->
                        
                        <!--<div class="col-lg-4 mb-3 d-none">-->
                        <!--    <label  class="form-label">Regular Price</label>-->
                        <!--    <input type="text" name="regular_price" class="form-control" value="{{ $item->regular_price}}">-->
                        <!--</div>-->

                        <div class="col-lg-3 mb-3 d-none">
                            <label  class="form-label">Manage Stock</label>
                            <select name="is_stock" class="form-control">
                                <!--<option value="1" {{ $item->is_stock == '1' ? 'selected' : '' }} >Yes</option>-->
                                <option value="0" selected>No</option>
                            </select>
                        </div>
                        
                        @if($item->discount_type == null)
                        @else
                        <div id="dis_type" class="col-lg-3 mb-3 dis_type">
                            <label  class="form-label">Discount Type</label>
                             <select class="form-control dicount_type" name="discount_type">
                                <option value="">Select Discount Type</option>
                                <option value="fixed" {{ $item->discount_type == 'fixed' ? 'selected' : '' }} >Fixed</option>
                                <option value="percentage" {{ $item->discount_type == 'percentage' ? 'selected' : '' }} >Percentage</option>
                        </select>
                        </div>
                        
                        <div id="dis_amount" class="col-lg-3 mb-3 dis_amount">
                            <label  class="form-label">Discount Amount</label>
                            <input type="number" step="any" name="dicount_amount" class="form-control dicount_amount" value="{{ $item->dicount_amount }}">
                        </div>
                        @endif
                        
                        <div class="col-lg-4 mb-3">
                            <label  class="form-label">Purchase Price</label>
                            <input type="text" name="purchase_prices" class="form-control" value="{{ $item->purchase_prices}}">
                        </div>
                        
                        <div class="col-lg-4 mb-3">
                            <label  class="form-label">Sell Price</label>
                            <input type="text" id="sell_price" name="sell_price" class="form-control" placeholder="Product Sku" value="{{ $item->sell_price}}">
                        </div>
                        
                            <div class="col-lg-4 mb-3">
                                <label  class="form-label">After Discount Price</label>
                                <input type="text" id="after_discount" name="after_discount" class="form-control" placeholder="Product Sku" value="{{ $item->after_discount}}">
                            </div>
                        <div class="col-lg-4 mb-3">
                            <label  class="form-label">Stock Quantity</label>
                            <input type="text" name="pro_quantity" class="form-control" placeholder="Product Sku" value="{{ $item->stock_quantity}}">
                        </div>
                        
                    
                        
                    </div>
                    <hr>
                    
                    
                    
                    <div class="row">
                    @if($item->type == 'variable')
                       <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap table-bordered text-center">
                                    <thead>
                                        <tr>
                                           <th>Size</th>
                                            <th>Color</th>
                                            <th style="width: 20%;">Purchase Price</th>
                                            <th style="width: 20%;">Price</th>
                                            <th style="width: 20%;">Discount Price</th>
                                            <th style="width: 20%;">Stock Quantity</th>
                                            <th width="5">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->variations as $v)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="variation_id[]" value="{{$v->id}}">
                                                <input type="hidden" name="product_id[]" value="{{$item->id}}">
                                                <select name="size_id[]" class="form-control">
                                                    @foreach($sizes as $size)
                                                    <option {{$size->id==$v->size_id ?'selected':''}} value="{{$size->id}}">{{ $size->title }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                            <select name="color_id[]" class="form-control">
                                                    @foreach($colors as $color)
                                                    <option {{$color->id==$v->color_id ?'selected':''}} value="{{$color->id}}">{{$color->name}}</option>
                                                    @endforeach
                                            </select>
                                            </td>
                                            <td style="width: 16%;">
                                                <input class="variable_purchase_price form-control" type="number" value="{{ $v->purchase_price }}" name="purchase_price[]" placeholder="Price">
                                            </td>
                                            <td style="width: 16%;">
                                                <input class="variable_sell_price form-control" type="number" value="{{ $v->price }}" name="price[]" placeholder="Price">
                                            </td>
                                            <td>
                                                <input class="variable_dis_price form-control" value="{{$v->after_discount_price }}" type="number" name="after_discount_price[]" placeholder="Discount Price">
                                            </td>
                                            <td>
                                                <input class="quantity form-control" type="number" value="{{ $v->stock_quantity }}" name="quantity[]" placeholder="Price">
                                            </td>
                                            <td>
                                                <a class="action-icon btn-primary add_moore"><i class="mdi mdi-plus"></i> </a>
                                                <a class="action-icon btn-danger remove"><i class="mdi mdi-delete"></i> </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else
                    @endif
                        <!--<div class="col-lg-12">-->
                        <!--    <label  class="form-label">Feature</label>-->
                        <!--    <textarea class="form-control" name="feature" id="feature" rows="5">{!! $item->feature !!}</textarea>-->
                        <!--</div>-->

                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label  class="form-label">Product Body</label>
                                <textarea class="form-control" name="body" id="body" rows="5">{!! $item->body !!}</textarea>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update</button>
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
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!--<script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    // ===== Feature Editor =====
    $('#feature').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture', 'video', 'hr']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        callbacks: {
            onImageUpload: function(files) {
                let editor = $(this); // Current editor
                let data = new FormData();
                data.append('upload', files[0]);
                data.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: "{{ route('admin.ckeditor.upload') }}",
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(resp) {
                        if (resp.url) {
                            editor.summernote('insertImage', resp.url); // Insert into #feature
                        } else {
                            alert('Image upload failed.');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Upload error.');
                    }
                });
            }
        }
    });

    // ===== Body Editor =====
    $('#body').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture', 'video', 'hr']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        callbacks: {
            onImageUpload: function(files) {
                let editor = $(this); // Current editor
                let data = new FormData();
                data.append('upload', files[0]);
                data.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: "{{ route('admin.ckeditor.upload') }}",
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(resp) {
                        if (resp.url) {
                            editor.summernote('insertImage', resp.url); // Insert into #body
                        } else {
                            alert('Image upload failed.');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Upload error.');
                    }
                });
            }
        }
    });
});
</script>
<script type="text/javascript">
    // CKEDITOR.replace('body', {
    //     filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
    //     filebrowserUploadMethod: 'form'
    // });
    // CKEDITOR.replace('feature');

    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });

    $("document").ready(function () {
        $('select[name="category_id"]').on('change', function () {
            var cat_id = $(this).val();
            if (cat_id) {
                $.ajax({
                    url: '{{ route("admin.getSubcategory")}}',
                    type: "GET",
                    dataType: "json",
                    data:{cat_id},
                    success: function (data) {
                        $('select[name="sub_category_id"]').empty();
                        $.each(data, function (key, value) {
                            $('select[name="sub_category_id"]').append('<option value=" ' + key + '">' + value + '</option>');
                        })
                    }

                })
            } else {
                $('select[name="sub_category_id"]').empty();
            }
        });

        // add moore
        
        // $(document).on('blur', '#sell_price', function() {
        //     var sell_price = $(this).val(); 
            
        //     var discount_type=$("select[name='discount_type']").val();
        //     var discount_amount = $("input[name='dicount_amount']").val();
        //     if (discount_type=='percentage') {
        //         new_price= (sell_price / 100) * discount_amount;
        //         new_price=sell_price - new_price;
        //     }else{
        //         new_price= sell_price - discount_amount;
        //     }
        //     $('#after_discount').val(new_price);
            
        // });
        
        $(document).on('blur', '.dicount_amount', function(){
            
            let discount_amount=$(this).val();
            let new_price=0;
            var price=$("input[name='sell_price']").val();
            var discount_type=$("select[name='discount_type']").val();
            if (discount_type=='percentage') {
                new_price= (price / 100) * discount_amount;
                new_price=price - new_price;
            }else{
                new_price= price - discount_amount;
            }
            $("input[name='after_discount']").val(new_price.toFixed(2));
            $(".variable_dis_price").val(new_price.toFixed(2));
            $(".variable_dis_price_extra").val(12);
            // $(".variable_dis_price_extra").val(new_price.toFixed(2));
        });
        
        $(document).on('blur', '.variable_sell_price', function() {
            let tblrow = $(this).closest("tr");
            
            var discount_type=$("select[name='discount_type']").val();
            
            let variable_sell_price = tblrow.find('td input.variable_sell_price').val();
            var discount_amount = $("input[name='dicount_amount']").val();
            
            if (discount_type=='percentage') {
                new_price= (variable_sell_price / 100) * discount_amount;
                new_price=variable_sell_price - new_price;
            }else{
                new_price= variable_sell_price - discount_amount;
            }
            tblrow.find('td input.variable_dis_price').val(new_price);
        });

        $(document).on('click','a.add_moore', function(){
            
            let tblrow = $(this).closest("tr");
            let variable_purchase_price = tblrow.find('td input.variable_purchase_price').val();
            let variable_sell_price = tblrow.find('td input.variable_sell_price').val();
            let variable_dis_price = tblrow.find('td input.variable_dis_price').val();
            let quantity = tblrow.find('td input.quantity').val();
            
            let type='{{ $item->type}}';

            if (type=='single') {
                toastr.error('For Single Product You Can\'t Add Moore');
                return;
            }
            let row=`<tr>
                        <td>
                            <select name="size_id[]" class="form-control">
                                @foreach($sizes as $size)
                                <option value="{{$size->id}}">{{ $size->title }}</option>
                                @endforeach
                            </select>
                        </td>
                        
                        <td>
                            <select name="color_id[]" class="form-control">
                                @foreach($colors as $color)
                                <option  value="{{$color->id}}">{{$color->name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                        <input class="variable_purchase_price form-control" type="number" value="${variable_purchase_price}" name="variable_purchase_price[]" placeholder="Purchase Price">
                        </td>
                        <td>
                        <input class="variable_sell_price form-control" type="number" value="${variable_sell_price}" name="price[]" placeholder="Price">
                        </td>
                        <td>
                        <input class="variable_dis_price form-control" type="number" value="${variable_dis_price}" name="after_discount_price[]" placeholder="Discount Price">
                        </td>
                        <td>
                        <input class="form-control quantity" type="number" value="${quantity}" name="quantity[]" placeholder="Stock Quantity">
                        </td>
                        <td>
                            <a class="action-icon btn-primary add_moore"><i class="mdi mdi-plus"></i> </a>
                            <a class="action-icon btn-danger remove"><i class="mdi mdi-delete"></i> </a>
                        </td>
                    </tr>`;
            $(document).find('.table tbody').append(row);

        });

        $(document).on('click', "a.remove",function(e) {
            var whichtr = $(this).closest("tr");
            whichtr.remove();      
        });


    });

</script>

@endpush