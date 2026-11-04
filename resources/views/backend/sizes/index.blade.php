@extends('backend.app')
@section('content')

<style>
 th, td, h4, .sz_manage, .form-label {
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
                    <li class="breadcrumb-item active sz">Size Manage</li>
                </ol>
            </div>
            <h4 class="page-title">Size Manage</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="col-6">            
        @can('size.create')
        <div class="card">
            <div class="card-header">
                <h4> Size Create</h4>
            </div>
            <div class="card-body">
   
                <form method="POST" action="{{ route('admin.sizes.store')}}" id="ajax_form">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label  class="form-label">Size Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Size Name">
                            </div>

                        </div>

                        <div class="col-lg-12">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>

                </form>
            
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>   
    @endcan
    <div class="col-6">
        <div class="card">
            <div class="card-body">
   

                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>SL</th>
                                <th>Size</th>
                                <th style="width: 125px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $key=> $item)
                            <tr>
                                <td> {{$key+1}} </td>
                                <td> {{$item->title}} </td>
                                <td>
                                 @if($key == 0)   
                                @can('size.edit')
                                    
                                @endcan
                                @else
                                <a href="{{ route('admin.sizes.edit',[$item->id])}}" class="action-icon btn_modal"> 
                                        <i class="mdi mdi-square-edit-outline"></i>
                                    </a>
                                @endif
                                @if($key == 0)
                                @can('size.delete')
                                    
                                @endcan
                                @else
                                <a href="{{ route('admin.sizes.destroy',[$item->id])}}" class="delete action-icon"> <i class="mdi mdi-delete"></i></a>
                                @endif
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
@endsection 