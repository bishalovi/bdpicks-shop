@extends('backend.app')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">SIS</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">CRM</a></li>
                    <li class="breadcrumb-item active">User Create</li>
                </ol>
            </div>
            <h4 class="page-title">User Create</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <a href="{{route('admin.users.index')}}" class="btn btn-secondary">Back</a><br><br>
                <form action="{{route('admin.users.update', $user->id)}}" method="POST" id="ajax_form">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                    <strong for="first_name">First Name</strong>
                                    <input type="text" id="first_name" class="form-control" name="first_name" placeholder="First name..." value="{{$user->first_name}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong for="last_name">Last Name</strong>
                                <input type="text" id="last_name" class="form-control" name="last_name" placeholder="Last name..." value="{{$user->last_name}}">
                            </div>
                        </div>
                      
                      	<div class="col-md-6">
                            <div class="form-group">
                                <strong for="last_name">Business Name</strong>
                                <input type="text" class="form-control" name="business_name" placeholder="Business name..." value="{{$user->business_name}}">
                            </div>
                        </div>
                      
                      
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong for="email">Email</strong>
                                <input type="email" id="email" class="form-control" name="email" placeholder="Email..." value="{{$user->email}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong for="username">Username</strong>
                                <input type="text" id="username" class="form-control" name="username" placeholder="Username..." value="{{$user->username}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong for="password">Password</strong>
                                <input type="password" id="password" class="form-control" name="password" placeholder="Password..." autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong for="role">Role</strong>
                                 <select name="role" id="role" class="form-control">
                                    <option value="" disabled selected>Choose a Role</option>
                                    @foreach($roles as $role)
                                            <option value="{{$role->id}}" @if($role->id == $user->hasRole($role))  selected @endif>{{$role->name}}</option>
                                               
                                    @endforeach
                                 </select>   
                            </div>
                        </div>
                    </div>
                    <br>
                    <input type="submit" value="Update" class="btn btn-success">
                    <hr>
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection 

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
  
</script>

@endpush