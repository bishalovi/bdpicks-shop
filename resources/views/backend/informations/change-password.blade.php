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
                    <li class="breadcrumb-item active">Change Password</li>
                </ol>
            </div>
            <h4 class="page-title">Change Password</h4>
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
                <form action="{{route('admin.password.update')}}" method="POST" enctype="multipart/form-data" id="password_update_form">
                    @csrf
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong for="role">Old Password</strong>
                                    <input type="password" id="password" class="form-control" name="password" placeholder="Old password..." value="{{ old('password') }}">
                                </div>
                                 <p class="text-danger" id="password_error"></p>
                            </div>                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong for="role">New Password</strong>
                                    <input type="password" id="new_password" class="form-control" name="new_password" placeholder="New password..." value="{{ old('new_password') }}">
                                </div>
                                 <p class="text-danger" id="new_password_error"></p>
                            </div>                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong for="role">Confirm Password</strong>
                                    <input type="password" id="cpassword" class="form-control" name="password_confirmation" placeholder="Confirm password..." value="{{ old('password_confirmation') }}">
                                </div>
                                 <p class="text-danger" id="cpassword_error"></p>
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
       $("#password_update_form").submit(function(e){
            e.preventDefault();
            let url = $(this).attr("action");
            let method = $(this).attr("method");
            let data = $(this).serialize();
            $.ajax({
               url,
               method,
               data,
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
                        if(errors.password)
                        {
                            $("#password_error").html(errors.password[0]);
                        }
                        else 
                        {
                            $("#password_error").html('');  
                        }                        
                        if(errors.new_password)
                        {
                            $("#new_password_error").html(errors.new_password[0]);
                        }
                        else 
                        {
                            $("#new_password_error").html('');  
                        }                        
                        if(errors.password_confirmation)
                        {
                            $("#cpassword_error").html(errors.password_confirmation[0]);
                        }
                        else 
                        {
                            $("#cpassword_error").html('');  
                        }
                        
                    }
                    else if(res.success)
                    {
                           $("#password_error").html(''); 
                           $("#new_password_error").html('');  
                           $("#cpassword_error").html('');  
                            $("#message").html(`<div class="alert alert-success"><strong>${res.success}</strong></div>`);
                            
                            setTimeout(function(){
                                 $("#message").html('');
                                 location.reload();
                            },1000);
                    }                    
                    else if(res.error)
                    {
                           $("#password_error").html(''); 
                           $("#new_password_error").html(''); 
                           $("#cpassword_error").html('');  
                           $("#message").html(`<div class="alert alert-danger"><strong>${res.error}</strong></div>`);
                    }
                    
               }
            });
       });
    });
  
</script>

@endpush