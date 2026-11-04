@extends('backend.app')
@section('content')

<style>
 th, td, h4, .pr_dis, .form-label {
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
                    <li class="breadcrumb-item active pr_dis">Free Shipping Product Discount List</li>
                </ol>
            </div>
            <h4 class="page-title">Free Shipping Product List</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-xl-8">
                        <form class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <label for="inputPassword2" class="visually-hidden">Search</label>
                                <input type="search" class="form-control" id="inputPassword2" placeholder="Search...">
                            </div>
                        </form>                            
                    </div>
                    <div class="col-xl-4">
                        <div class="text-xl-end mt-xl-0 mt-2">
                        @can('discount.create')
                            <a href="{{ route('admin.create_free_shipping')}}" class="btn btn-danger mb-2 me-2"><i class="mdi mdi-basket me-1"></i> Add Free Shipping Product</a>
                        @endcan
                            <button type="button" class="btn btn-light mb-2" style="color: black;">Export</button>
                        </div>
                    </div><!-- end col-->
                </div>

                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                
                                <th>Product</th>
                                <th>Image</th>
                                <th style="width: 125px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>
                                    {{$item->name}}
                                </td>
                                <td>
                        
                                    <div class="flex-shrink-0">
                                        <img src="{{ getImage('products',$item->image)}}" class="rounded-circle avatar-xs" alt="friend">
                                    </div>
                                        
                                </td>
                             
                                <input type="hidden" id="id" name="id" value="{{ $item->id }}">
                                <td>
                                
                                
                                <button value="{{ $item->id }}" id="delete_fr_shipping"><i class="mdi mdi-delete"></i></button>
                                    
                               
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script type="text/javascript">

$(document).on('click', '#delete_fr_shipping', function (){
    let id = $(this).val();
    $.ajax({
            url: "{{ route('admin.free-shipping.fshippingdestroy')}}",
            type: 'GET',
            dataType: "json",
            data: {product_id: id},
            success: function( res ) {
                
                if(res.status==true){
                toastr.success(res.msg);
                window.location.reload();
                }
                
                            }
          });
});


</script>


@endsection 



