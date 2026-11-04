@extends('backend.app')
@section('content')

<style>
 th, td, h4, .pr_list, .form-label {
  	color: black !important;
  }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">SIS</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">CRM</a></li>
                    <li class="breadcrumb-item active pr_list">Product List</li>
                </ol>
            </div>
            <h4 class="page-title">Product List</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="col-12 p-1">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    
                    <div class="col-md-6">
                    
                        <div class="#" style=" ">
                            <a class=" btn btn-sm btn-info recomm_update" href="{{ route('admin.recommendedUpdate')}}?is_recommended=1" style=" margin-right: 10px;margin-bottom: 10px; ">
                              Active (Home)
                          </a>
                            <a class=" btn btn-sm btn-danger recomm_update" href="{{ route('admin.recommendedUpdate')}}?is_recommended=0" style=" margin-bottom: 10px; ">
                              De-active (Home)
                          </a>
                          <a class=" btn btn-sm btn-info show_update" href="{{ route('admin.showUpdate')}}?status=1" style=" margin-right: 10px;margin-bottom: 10px; ">
                              Show
                          </a>
                            <a class=" btn btn-sm btn-danger show_update" href="{{ route('admin.showUpdate')}}?status=0" style=" margin-bottom: 10px; ">
                              Hide)
                          </a>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="text-xl-end mt-xl-0 mt-2" style="  ">
                        @can('product.create')
                            <a href="{{ route('admin.products.create')}}" class="btn btn-danger mb-2 me-2"><i class="mdi mdi-basket me-1"></i> Add Product</a>
                        @endcan
                        <a type="button" href="{{ route('admin.productExport')}}" class="btn btn-light mb-2" style="color: black;">Export</a>
                        </div>
                    </div>
                    
                    
                    
                
                </div>
                  <div class="row mb-2">
                      <div class="col-md-4">
                    <select name="category" class="form-control select2" id="category">
                        <option value="">{{__('Select Category')}}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == $cat_id ? 'selected' : '' }} >{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                    <div class="col-md-8" style="">
                        <form class="row align-items-center" style="float: right;">
                            <div style="width:60%;margin-top: 0px;">
                                <label for="inputPassword2" class="visually-hidden">Search</label>
                                <input type="search" class="form-control" id="inputPassword2" placeholder="Search..." name="q" value="{{ $q??''}}">
                            </div>
                            
                            <div class="col-auto" style="margin-top: 0px;" >
                                <label for="submit" class="visually-hidden">Submit</label>
                                <input type="submit" class="form-control btn btn-sm btn-primary" id="submit" value="Submit">
                                
                            </div>
                        </form>
                        
                    </div>
                    <!-- end col-->
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 p-1">
                <div class="table-responsive">
                    <table class="table table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:10%">Action</th>
                                <th>
                                    
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input check_all" value="">
                                      </label>
                                    </div>
                                </th>
                                <th style="width:12%">Product</th>
                              	<th style="width:8%">Sku</th>
                                <th>Image</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Sell Price</th>
                                <th>Stock</th>
                                <th>Visibility</th>
                                <th style="width: 12%;">Priority</th>
                              	<th>Recommended</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                              <td>
                                  	<!--<a href="{{ route('admin.productCopy',[$item->id])}}" class="action-icon">copy</a>-->
                                    <!--<a href="{{ route('admin.products.show',[$item->id])}}" class="action-icon"> <i class="mdi mdi-details"></i></a>-->
                                    @can('product.edit')
                                    <a href="{{ route('admin.products.edit',[$item->id])}}" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                                    @endcan
                                    @can('product.delete')
                                    <a href="{{ route('admin.products.destroy',[$item->id])}}" class="delete action-icon"> <i class="mdi mdi-delete"></i></a>
                                    @endcan
                                </td>
                                <td>
                                    <input type="checkbox" class="checkbox" value="{{ $item->id}}">
                                    
                                </td>
                                <td> {{$item->name}}</td>
                              	<td> {{$item->sku}}</td>
                                <td>
                        
                                    <div class="flex-shrink-0">
                                        <img src="{{ getImage('thumb_products',$item->image)}}" class="rounded-circle avatar-xs" alt="friend">
                                    </div>
                                        
                                </td>
                                <td> {{$item->type}}</td>
                                <td>{{ $item->category?$item->category->name:''}}</td>
                                <td>{{$item->sell_price}}</td>
                                <td>{{ $item->stock_quantity ?? 0}}</td>
                                <td> {{$item->status=='1'?'Show':'Hide'}} </td>
                                <td>
                                    <input type="text" name="priority" class="priority-input" data-product-id="{{ $item->id }}" value="{{ $item->priority }}" style="width:30%"/>
                                </td> 
                              	<td> {{$item->is_recommended=='1'?'yes':'no'}} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p>{!! urldecode(str_replace("/?","?",$items->appends(Request::all())->render())) !!}</p>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection 
@push('js')
<script>

$(document).ready(function(){
    
    $(".check_all").on('change',function(){
      $(".checkbox").prop('checked',$(this).is(":checked"));
    });
    
    $('.priority-input').on('change', function () {
            var productId = $(this).data('product-id');
            var priorityValue = $(this).val();

            $.ajax({
                url: '/admin/update-priority/' + productId,
                type: 'POST',
                data: {
                    priority: priorityValue,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    console.log(response);
                    console.log(productId, priorityValue);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    // Handle error, if needed
                }
            });
        });
    
    $(document).on('click', 'a.recomm_update', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
    
        var product = $('input.checkbox:checked').map(function(){
          return $(this).val();
        });
        var product_ids=product.get();
        
        if(product_ids.length ==0){
            toastr.error('Please Select A Product First !');
            return ;
        }
        
        $.ajax({
           type:'GET',
           url:url,
           data:{product_ids},
           success:function(res){
               if(res.status==true){
                toastr.success(res.msg);
                window.location.reload();
                
            }else if(res.status==false){
                toastr.error(res.msg);
            }
           }
        });
    
    });
    
    $(document).on('change', '#category', function() {
        
        let category_id = $(this).val();
        let url = "{{ route("admin.cat_wise_product") }}"+'?category_id='+category_id;
        window.location.href = url;
    });

    $(document).on('click', 'a.show_update', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var product = $('input.checkbox:checked').map(function(){
          return $(this).val();
        });
        var product_ids=product.get();
        
        if(product_ids.length ==0){
            toastr.error('Please Select A Product First !');
            return ;
        }
        
        $.ajax({
           type:'GET',
           url:url,
           data:{product_ids},
           success:function(res){
               if(res.status==true){
                toastr.success(res.msg);
                window.location.reload();
                
            }else if(res.status==false){
                toastr.error(res.msg);
            }
           }
        });
    
    });
    
    
})
    
    
</script>
@endpush