<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Home Image Update</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form action="{{ route('admin.home_section_images.update',[$item->id]) }}" method="POST" id="ajax_form">
      @csrf
      {{ method_field('PATCH') }}
      <div class="modal-body">

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
                  <option value="{{ $key}}" {{ $item->section==$key ?'selected':''}}>{{ $i}}</option>
                  @endforeach
              </select>
          </div>

          
          
            <div class="mb-3">
                <label  class="form-label">Link</label>
                <input type="text" name="link" class="form-control" value="{{$item->link}}">
            </div>
            
            <div class="mb-3">

                  <input type="checkbox" class="form-check-input" id="exampleCheck1" name="is_for_small" value="1" {{$item->section=='1' ?'checked':''}}>
                  <label class="form-check-label" for="exampleCheck1"> Is For Small </label>
    
              </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"> Update</button>
      </div>
    </form>
  </div>
</div>