@extends('backend.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">SIS</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">CRM</a></li>
                    <li class="breadcrumb-item active">Slider Manage</li>
                </ol>
            </div>
            <h4 class="page-title">Slider Manage</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="p-1 m-0 col-sm-12 col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4> Slider Create</h4>
            </div>
            <div class="card-body">
   
            @can('slider.create')
                <form method="POST" action="{{ route('admin.sliders.store')}}" id="ajax_form">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label  class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Title">
                            </div>

                            <div class="mb-3">
                                <label  class="form-label">Description</label>
                                <input type="text" name="description" class="form-control" placeholder="Description">
                            </div>

                            <div class="mb-3">
                                <label  class="form-label">Desktop Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            
                            <div class="mb-3">
                                <label  class="form-label">Mobile Image</label>
                                <input type="file" name="mobile_image" class="form-control">
                            </div>
                            
                            
                            <div class="mb-3">
                                <label  class="form-label">Link</label>
                                <input type="text" name="link" class="form-control" placeholder="Link">
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
    <div class="p-1 m-0 col-sm-12 col-md-12 col-lg-8">
        <div class="card p-0 m-0">
            <div class="card-body">
  
                <div class="table-responsive">
                    <table class="table table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Desktop Image</th>
                                <th>Mobile Image</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th style="width: 125px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $key=> $item)
                            <tr>
                                <td>
                                    <img src="{{ getImage('sliders', $item->image)}}" width="120"> 
                                </td>
                                
                                 <td>
                                    <img src="{{ getImage('mobile_sliders', $item->mobile_image)}}" width="120"> 
                                </td>
                                
                                <td> {{$item->title}} </td>
                                <td> {{$item->description}} </td>
                                <td>
                                @can('slider.edit')
                                    <a href="{{ route('admin.sliders.edit',[$item->id])}}" class="action-icon btn_modal"> 
                                        <i class="mdi mdi-square-edit-outline"></i>
                                    </a>
                                @endcan
                                @can('slider.delete')
                                    <a href="{{ route('admin.sliders.destroy',[$item->id])}}" class="delete action-icon"> <i class="mdi mdi-delete"></i></a>
                                @endcan
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