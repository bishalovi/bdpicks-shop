@extends('backend.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">SIS</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">CRM</a></li>
                    <li class="breadcrumb-item active">Delivery Charge Manage</li>
                </ol>
            </div>
            <h4 class="page-title">Delivery Charge Manage</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="col-6">            
        @can('size.create')
        <div class="card">
            <div class="card-header">
                <h4> Delivery Create</h4>
            </div>
            <div class="card-body">
   
                <form method="POST" action="{{ route('admin.delivery_charge.store')}}" id="ajax_form">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label  class="form-label">Delivery Charge Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Delivery Charge Name">
                            </div>
                            
                            <div class="mb-3">
                                <label  class="form-label">Delivery Charge Amount</label>
                                <input type="text" name="amount" class="form-control" value="0">
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" name="status" class="form-check-input" value="1">Active
                                  </label>
                                </div>
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
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th style="width: 125px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $key=> $item)
                            <tr>
                                <td> {{$key+1}} </td>
                                <td> {{$item->title}} </td>
                                <td> {{$item->amount}} </td>
                                <td> {{$item->status =='1' ?'active':'no'}} </td>
                                <td>
                                @can('size.edit')
                                    <a href="{{ route('admin.delivery_charge.edit',[$item->id])}}" class="action-icon btn_modal"> 
                                        <i class="mdi mdi-square-edit-outline"></i>
                                    </a>
                                @endcan
                                @can('size.delete')
                                    <a href="{{ route('admin.delivery_charge.destroy',[$item->id])}}" class="delete action-icon"> <i class="mdi mdi-delete"></i></a>
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