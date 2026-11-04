@extends('backend.app')
@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">SIS</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">CRM</a></li>
                    <li class="breadcrumb-item active">Page update</li>
                </ol>
            </div>
            <h4 class="page-title">Page update</h4>
        </div>
    </div>
</div>   
<!-- end page title --> 

<div class="row">
    <div class="col-12">            
       
        <div class="card">
            
            <div class="card-body">
   
                <form method="POST" action="{{ route('admin.pages.update',[$item->id])}}" id="ajax_form">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label  class="form-label">Page Select</label>
                                <input type="text" name="page" value="{{ $item->page }}" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label  class="form-label">Page Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Title" value="{{ $item->title}}">
                            </div>

                            

                        </div>

                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label  class="form-label">Page Body</label>
                                <textarea class="form-control" name="body" rows="10" cols="10">{!! $item->body !!}</textarea>
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
</div> <!-- end row -->
@endsection 

@push('js')
<script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('body', {
        filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
</script>
@endpush