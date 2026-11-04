<!-- bundle -->
<script src="{{ asset('backend/js/vendor.min.js')}}"></script>
<script src="{{ asset('backend/js/app.min.js')}}"></script>
<script src="{{ asset('backend/js/ajax.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(document).on('click','a.delete', function(e) {
    var form=$(this);
    e.preventDefault(); 
    swal({
      title: "Are you sure?",
      text: "You want To Delete!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#006400",
      confirmButtonText: "Yes, do it!",
      cancelButtonText: "No, cancel plz!",
      closeOnConfirm: false,
      closeOnCancel: false
    },
    function(isConfirm){
      if (isConfirm) {

        var url=$(form).attr('href');

        $.ajax({
            type: 'DELETE',
            url: url,
            data: {"_token": "{{ csrf_token() }}"},
            success: function(res) {
                
                if(res.status==true){
                    toastr.success(res.msg);
                    if(res.url){
                        document.location.href = res.url;
                    }else{
                        window.location.reload();
                    }
                }else if(res.status==false){
                    toastr.error(res.msg);
                }
                
            },
            error:function (response){
                
            }
        });
      } else {
        swal("Cancelled", "Your imaginary file is safe :)", "error");
      }
    });
});
  
  $(document).ready(function() {
    $('.select2').select2();
  });

  $(document).on('change',"#area_select",function(e) {
    let area_id = $(this).val();
    
    let area_name = $("#area_select option:selected").text();
    $("#area_id").val(area_id);
    $("#area_name").val(area_name);
  });

</script>
@stack('js')