@extends('backend.app')
@section('content')

<style>
 th, td, h4, .img_manage, .form-label {
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
                    <li class="breadcrumb-item active img_manage">Home Section Image Manage</li>
                </ol>
            </div>
            <h4 class="page-title">Home Section Image Manage</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row ">
    <div class="p-1 m-0 col-sm-12 col-md-12  col-lg-5">
        <div class="card">
            <div class="card-header">
                <h4> Home Section Image Create</h4>
            </div>
            <div class="card-body">
   
            @can('image.create')
                <form method="POST" action="{{ route('admin.home_section_images.store')}}" id="ajax_form">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label  class="form-label">Desktop Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            
                            <div class="mb-3">
                                <label  class="form-label">Mobile Image</label>
                                <input type="file" name="mobile_image" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label  class="form-label">Sections</label>
                                <select class="form-control" name="section">
                                    @foreach(getSectionLists() as $key=>$i)
                                    <option value="{{$key}}">{{ $i}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label  class="form-label">Link</label>
                                <input type="text" name="link" class="form-control">
                            </div>

                            <div class="mb-3">

                                <input type="checkbox" class="form-check-input" id="exampleCheck1" name="is_for_small" value="1">
                                <label class="form-check-label" for="exampleCheck1" style="color: black;"> Is For Small </label>

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
    <div class="p-1 m-0 col-sm-12 col-md-12 col-lg-7">
        <div class="card">
            <div class="card-body">
   

                <div class="table-responsive">
                    <table class="table table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>SL</th>
                                <th>Section</th>
                                <th>Desktop Image</th>
                                <th>Mobile Image</th>
                                <th>Is For Small</th>
                                <th style="width: 125px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $key=> $item)
                            <tr>
                                <td> {{$key+1}} </td>
                                <td> {{ getSectionLists()[$item->section]}} </td>
                                <td>
                                    <img src="{{ getImage('homeimages', $item->image)}}" width="120"> 
                                </td>
                                
                                <td>
                                    <img src="{{ getImage('homeimages', $item->mobile_image)}}" width="120"> 
                                </td>

                                <td> {{ $item->is_for_small=='1' ?'yes':''}} </td>

                                <td>
                                @can('image.edit')
                                    <a href="{{ route('admin.home_section_images.edit',[$item->id])}}" class="action-icon btn_modal"> 
                                        <i class="mdi mdi-square-edit-outline"></i>
                                    </a>
                                @endcan
                                @can('image.delete')
                                    <a href="{{ route('admin.home_section_images.destroy',[$item->id])}}" class="delete action-icon"> <i class="mdi mdi-delete"></i></a>
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