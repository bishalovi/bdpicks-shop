@extends('backend.app')
@section('content')

<style>
 th, td, h4, .cr_manage, .form-label {
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
                    <li class="breadcrumb-item active cr_manage">Home Category Manage</li>
                </ol>
            </div>
            <h4 class="page-title">Home Category Manage</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="p-1 col-lg-4 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>Home Category Create</h4>
            </div>
            <div class="card-body">
   
            @can('category.create')
                <form method="POST" action="{{ route('admin.store-homecat')}}" id="ajax_form">
                    @csrf
                    <div class="row">
                        
                        <div class="col-lg-12">

                            <div class="mb-3">
                                <label  class="form-label">Add Home Category</label>
                                
                                <select class="form-control select2" name="category_id">
                                    <option value="" hidden>Select Category ..</option>
                                    @foreach($all_categories as $key=>$cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label  class="form-label">Serial</label>
                                <input type="text" name="serial" class="form-control" placeholder="Serial">
                            </div>

                        </div>

                        <div class="col-lg-12">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>

                </form>
                @endcan
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
    <div class="p-1 col-md-12 col-sm-12 col-lg-8">
        
        
        <div class="card">
            <div class="card-body">
                <div class="col-lg-12">
                    <div class="row">
                      
                        <div class="col-lg-6 offset-md-6" style="text-align: right;">
                            <form class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                                <div class="col-auto">
                                    <label for="inputPassword2" class="visually-hidden">Search</label>
                                    <input type="search" class="form-control" id="inputPassword2" placeholder="Search..." name="q" value="{{ $q??''}}">
                                </div>
                                
                                <div class="col-auto">
                                    <label for="submit" class="visually-hidden">Submit</label>
                                    <input type="submit" class="form-control btn btn-sm btn-primary" id="submit" value="Submit">
                                    
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-12 mt-4">
        
                    <div class="table-responsive">
                        <table class="table table-centered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Category Name</th>
                                    <th>Serial</th>
                                    <th style="width: 125px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($home_categories as $key=> $item)
                                <tr>
                                   <td> {{ $item->category != null ? $item->category->name  : '' }} </td>
                                    <td> {{$item->serial}} </td>
                                    <td>
                                        <a href="{{ route('admin.del_homecat', ['id' => $item->id]) }}" class="action-icon delete"> <i class="mdi mdi-delete"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                  <p>{!! urldecode(str_replace("/?","?",$home_categories->appends(Request::all())->render())) !!}</p>
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
    
    
    $(document).on('click', 'a.popular_update', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
    
        var product = $('input.checkbox:checked').map(function(){
          return $(this).val();
        });
        var cat_ids=product.get();
        
        if(cat_ids.length ==0){
            toastr.error('Please Select A Product First !');
            return ;
        }
        
        $.ajax({
           type:'GET',
           url:url,
           data:{cat_ids},
           success:function(res){
               if(res.status==true){
                toastr.success(res.msg);
                window.location.reload();
                
            }else if(res.status==false){
                toastr.error(res.msg);
            }
           }
        });
    
    })
    
    
})
    
    
</script>
@endpush