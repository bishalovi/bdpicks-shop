<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Assign User </h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form action="{{ route('admin.assignUserStore')}}" method="POST" id="order_assign_form">
      @csrf
      <div class="modal-body">
          
          <div class="div-group">
              <label>Status</label>
                <select class="form-control" name="assign_user_id" id="assign_user_id">
                   @foreach($users as $user)
                   <option value="{{ $user->id}}"> {{ $user->username}} </option>
                   @endforeach
                </select>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"> Submit</button>
      </div>
    </form>
  </div>
</div>