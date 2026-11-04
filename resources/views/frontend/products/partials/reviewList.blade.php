<!-- Start Single Comment  -->
  
@foreach($singleProduct->reviews as $review)
<li class="comment d-block">
    <div class="comment-body">
        <div class="single-comment">
            <div class="comment-img">
                <img src="{{ asset('images/users.png')}}" alt="Author Images" style="width:60px">
            </div>
            <div class="comment-inner">
                <h6 class="commenter">
                    <a class="hover-flip-item-wrapper" href="#">
                        <span class="hover-flip-item">
                            <span data-text="Cameron Williamson">{{ $review->name }}</span>
                        </span>
                    </a>
                    <span class="commenter-rating ratiing-four-star">
                      	@for($i=1;$i<=5;$i++)
                          @if($i <= $review->review)
                            <a><i class="fas fa-star"></i></a>
                            @else
                            <a><i class="fas fa-star empty-rating"></i></a>
                            @endif
                        @endfor
                    </span>
                </h6>
                <div class="comment-text">
                    <p> {{ $review->message}}  </p>
                    @if($review->image)
                    <img src="{{ asset($review->image) }}" width="100">
                    @endif
                </div>
            </div>
        </div>
    </div>
</li>
@endforeach
<!-- End Single Comment  -->