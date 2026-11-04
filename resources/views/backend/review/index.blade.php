@extends('backend.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Review List</li>
                </ol>
            </div>
            <h4 class="page-title">Review List</h4>
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
                    <div class="col-xl-9">
                        <a href="{{ route('admin.reviews.action', ['status' => 1]) }}" 
                           class="btn btn-success action_btn">Approve</a>
                    
                        <a href="{{ route('admin.reviews.action', ['status' => 0]) }}" 
                           class="btn btn-warning action_btn">Panding</a>
                    
                        <a href="{{ route('admin.reviews.action', ['delete' => 1]) }}" 
                           class="btn btn-danger action_btn">Delete</a>
                    </div>

                    
                </div>

                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" id="parent_item"></th>
                                <th>Product</th>
                                <th>Name</th>
                                <th>Message</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th style="width: 125px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $review)
                            <tr>
                                <td><input type="checkbox" value="{{ $review->id}}" class="user_status"></td>
                                <td>{{$review->product ? $review->product->name : ''}}</td>
                                <td>{{$review->name}}</td>
                                <td>{!! $review->message !!}</td>
                                <td><img src="{{ asset($review->image) }}" alt="{{$review->name}}"></td>
                                <td>
                                    @if($review->status == 1)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Panding</span>
                                    @endif
                                </td>
                                <td>
                                    <!--<form action="{{ route('admin.reviews.destroy',[$review->id])}}" method="post">-->
                                    <!--    @csrf-->
                                    <!--<button type="submit" class="delete action-icon"> <i class="mdi mdi-delete"></i></button>-->
                                    <!--</form>-->
                                    
                                    <a href="{{ route('admin.reviews.destroy',[$review->id])}}" class="delete action-icon"> <i class="mdi mdi-delete"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$data->links()}}
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
  
    
    // Select/Deselect all checkboxes
    $('#parent_item').on('change', function(){
        $('.user_status').prop('checked', this.checked);
    });

    $('.user_status').on('change', function(){
        $('#parent_item').prop('checked', $('.user_status:checked').length === $('.user_status').length);
    });
    
    // Bulk Action (Approve / Reject / Delete)
    $(document).on('click', '.action_btn', function(e){
        e.preventDefault();

        var url = $(this).attr('href');
        var ids = $('input.user_status:checked').map(function(){ return $(this).val(); }).get();

        if (ids.length === 0) {
            toastr.error('Please select at least one review!');
            return;
        }
        if (url.includes('delete=1')) {
            if (!confirm('Are you sure you want to delete the selected reviews? This action cannot be undone.')) {
                return; // stop if cancelled
            }
        }
        $.ajax({
            type: 'GET',
            url: url,
            data: { ids },
            success: function(res){
                if(res.status){
                    toastr.success(res.msg);
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    toastr.error(res.msg || 'Something went wrong!');
                }
            },
            error: function(){
                toastr.error('Server error!');
            }
        });
    });

});
    
    
</script>
@endpush