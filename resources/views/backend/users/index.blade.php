@extends('backend.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">User List</li>
                </ol>
            </div>
            <h4 class="page-title">User List</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(Session::has('success'))
                    <div class="alert alert-success">
                        <strong>{{Session::get('success')}}</strong>
                    </div>
                @endif
                <br>
                <div class="row mb-2">
                    <div class="col-xl-3">
                        <div class="text-xl-end mt-xl-0 mt-2">
                            <form>
                                <input type="text" name="q" class="form-control" placeholder="search here.." value="{{ request('q')??''}}">
                            </form>
                        
                        </div>
                    </div><!-- end col-->
                    
                    <div class="col-xl-3">
                        <div class="text-xl-end mt-xl-0 mt-2">
                          <select class="form-control" name="role">
                            <option value="" selected disabled>Select One</option>
                            <option value="">All User</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div><!-- end col-->                    
                  <div class="col-xl-3">
                        <div class="text-xl-end mt-xl-0 mt-2">
                            @can('user.edit')
                                <a type="button" href="{{route('admin.userStatusUpdate')}}?status=0" class="status btn btn-primary btn-sm mb-2 me-2"> Active</a>
                                <a type="button" href="{{route('admin.userStatusUpdate')}}?status=1" class="status btn btn-info btn-sm mb-2 me-2">De-Active</a>
                            @endcan
                        
                        </div>
                    </div><!-- end col-->
                    
                    <div class="col-xl-3">
                        <div class="text-xl-end mt-xl-0 mt-2">
                            @can('user.create')
                                <a type="button" href="{{route('admin.users.create')}}" class="btn btn-danger mb-2 me-2"><i class="mdi mdi-basket me-1"></i> Add New User</a>
                            @endcan
                        
                        </div>
                    </div><!-- end col-->
                </div>

                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" id="parent_item"></th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>businessname</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th style="width: 125px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td><input type="checkbox" value="{{ $user->id}}" class="user_status"></td>
                                <td>{{$user->first_name}}</td>
                                <td>{{$user->last_name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->username}}</td>
                                <td>{{$user->business_name}}</td>
                                <td>
                                    @foreach($user->getRoleNames() as $role)
                                        <span class="badge bg-success">{{$role}}</span>
                                    @endforeach
                                </td>
                                <td>{{ $user->status==null ?'De-Active':'Active'}}</td>
                                <td>
                                @can('user.edit')
                                <a href="{{ route('admin.users.edit',[$user->id])}}" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                                @endcan
                                @can('user.delete')
                                    <a href="{{ route('admin.users.destroy',[$user->id])}}" class="delete action-icon"> <i class="mdi mdi-delete"></i></a>
                                @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$users->links()}}
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->

<div class="row">
    <div class="col-md-12">
        <h3 style="text-align: center;color: red;">কমপক্ষে ১ জন Active Worker রাখুন। অন্যথায় Site এ Order আসতে Problem হবে।</3>
    </div>
</div>

@endsection 

@push('js')
<script>

$(document).ready(function(){
    
    $(".check_all").on('change',function(){
      $(".checkbox").prop('checked',$(this).is(":checked"));
    });
    
    
    $(document).on('click', 'a.status', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
    
        var user = $('input.user_status:checked').map(function(){
          return $(this).val();
        });
        var user_ids=user.get();
        
        if(user_ids.length ==0){
            toastr.error('Please Select A Product First !');
            return ;
        }
        
        $.ajax({
           type:'GET',
           url:url,
           data:{user_ids},
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
    
    $(document).on('change', 'select[name="role"]', function(e){
      	let query = $('input[name="q"]').val();
      	let value = $(this).val();
      	location.href = '?q='+query+'&role='+value;
    });
  
    $('#parent_item').change(function(){
        if($(this).prop('checked'))
        $('.user_status').prop('checked', true);
        else $('.user_status').prop('checked', false);
    });    
    
    $('.user_status').change(function(){
        if($('.user_status:checked').length == $('.user_status').length)
        $('#parent_item').prop('checked', true);
        else $('#parent_item').prop('checked', false);
    });
});
    
    
</script>
@endpush