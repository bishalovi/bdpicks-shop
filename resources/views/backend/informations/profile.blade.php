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
                    <li class="breadcrumb-item active">My Account</li>
                </ol>
            </div>
            <h4 class="page-title">My Account</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <a href="{{route('admin.dashboard')}}" class="btn btn-secondary">Back</a><br><br>
                    <div id="message">
                        
                    </div>
                <form action="{{route('admin.profile.update')}}" method="POST" enctype="multipart/form-data" id="profile_update_form">
                    @csrf
                      <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong for="role">First Name</strong>
                                <input type="text" id="first_name" class="form-control" name="first_name" placeholder="First name..." value="{{ $data->first_name }}">
                            </div>
                             <p class="text-danger" id="first_name_error"></p>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong for="role">Last Name</strong>
                                <input type="text" id="last_name" class="form-control" name="last_name" placeholder="Last name..." value="{{ $data->last_name }}">
                            </div>
                            <p class="text-danger" id="last_name_error"></p>
                        </div>   
                    </div> 
                       <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong for="role">Email Address</strong>
                                 <input type="email" id="email" class="form-control" name="email" placeholder="Email address..." value="{{ $data->email }}" disabled>
                            </div>
                            <p class="text-danger" id="email_error"></p>
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong for="role">Username</strong>
                                 <input type="text" id="username" class="form-control" name="username" placeholder="Username..." value="{{ $data->username }}">
                            </div>
                              <p class="text-danger" id="username_error"></p>
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong for="role">Phone</strong>
                                 <input type="text" id="mobile" class="form-control" name="mobile" placeholder="Mobile number..." value="{{ $data->mobile }}">
                            </div>
                             <p class="text-danger" id="mobile_error"></p>
                        </div>
                          <div class="col-md-6">
                            <div class="form-group">
                                <strong for="role">Business Name</strong>
                                <input type="text" id="business_name" class="form-control" name="business_name" placeholder="Business name..." value="{{ $data->business_name }}">
                            </div>
                            <p class="text-danger" id="business_name_error"></p>
                        </div>
                    </div>
                    <div class="row mb-2">
                       <div class="col-md-6">
                        <div class="form-group">
                            <strong for="role">Profile Photo</strong>
                            <input type="file" id="image" class="form-control" name="image" accept="image/*">
                        </div>
                        <div class="mt-2">
                            <img src="{{ asset('uploads/img/'.$data->image) }}" height="100" width="100" id="preview_img"/>
                        </div>
                        <p class="text-danger" id="image_error"></p>
                    </div>  
                    </div>   
                    <hr>
                    <br>
                    <input type="submit" value="Update" class="btn btn-success" id="updateBtn">
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
    
    $(document).ready(function(){
       $("#image").change(function(e){
          let file = e.target.files[0];
          let temp = URL.createObjectURL(file);
          $("#preview_img").attr('src', temp);
       });
       
       $("#profile_update_form").submit(function(e){
            e.preventDefault();
            let url = $(this).attr("action");
            let method = $(this).attr("method");
            let data = new FormData(this);
            $.ajax({
               url,
               method,
               data,
               contentType:false,
               processData:false,
               beforeSend:function()
               {
                   $("#updateBtn").attr('disabled', true);
               },
               success:function(res)
               {
                    $("#updateBtn").attr('disabled', false);
                    if(res.errors)
                    {
                        let errors = res.errors;
                        if(errors.first_name)
                        {
                            $("#first_name_error").html(errors.first_name[0]);
                        }
                        else 
                        {
                            $("#first_name_error").html('');  
                        }
                        if(errors.last_name)
                        {
                            $("#last_name_error").html(errors.last_name[0]);
                        }
                        else 
                        { 
                            $("#last_name_error").html('');      
                        }
                        if(errors.username)
                        {
                            $("#username_error").html(errors.username[0]);
                        }
                        else 
                        {
                            $("#username_error").html('');
                        }                        
                        if(errors.mobile)
                        {
                            $("#mobile_error").html(errors.mobile[0]);
                        }
                        else 
                        {
                            $("#mobile_error").html('');
                        }                        
                        if(errors.business_name)
                        {
                            $("#business_name_error").html(errors.business_name[0]);
                        }
                        else 
                        {
                            $("#business_name_error").html('');
                        }                        
                        if(errors.image)
                        {
                            $("#image_error").html(errors.image[0]);
                        }
                        else 
                        {
                            $("#image_error").html('');
                        }
                    }
                    else if(res.success){
                            $("#first_name_error").html('');
                            $("#last_name_error").html('');
                            $("#username_error").html('');
                            $("#mobile_error").html('');
                            $("#business_name_error").html('');
                            $("#image_error").html('');
                            
                            $("#message").html(`<div class="alert alert-success"><strong>${res.success}</strong></div>`);
                            
                            setTimeout(function(){
                                 $("#message").html('');
                            },3000);
                    }
                    
               }
            });
       });
    });
  
</script>

@endpush