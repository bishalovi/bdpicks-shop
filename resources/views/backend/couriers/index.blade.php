@extends('backend.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">SIS</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">CRM</a></li>
                    <li class="breadcrumb-item active">Courier Manage</li>
                </ol>
            </div>
            <h4 class="page-title">Courier Manage</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    
                    <div class="col-md-8">
        
                    
                        
                    </div>
                    <div class="col-md-4">
                        <div class="text-xl-end mt-xl-0 mt-2" style="  ">
                       
                            <a href="{{ route('admin.couriers.create')}}" class="btn btn-danger mb-2 me-2 btn_modal"><i class="mdi mdi-basket me-1"></i> Add Courier</a>
                        </div>
                    </div><!-- end col-->
                </div>
   

                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th style="width: 125px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $key=> $item)
                            <tr>
                                <td> {{$key+1}} </td>
                                <td> {{$item->name}} </td>
                                <td> {{$item->phone}} </td>
                                <td> {{$item->email}} </td>
                                <td> {{$item->address}} </td>
                                <td>
                                @can('size.edit')
                                    <a href="{{ route('admin.couriers.edit',[$item->id])}}" class="action-icon btn_modal"> 
                                        <i class="mdi mdi-square-edit-outline"></i>
                                    </a>
                                @endcan
                                @can('size.delete')
                                    <a href="{{ route('admin.couriers.destroy',[$item->id])}}" class="delete action-icon"> <i class="mdi mdi-delete"></i></a>
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