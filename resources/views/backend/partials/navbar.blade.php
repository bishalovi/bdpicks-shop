<ul class="side-nav">
    
    <li class="side-nav-item">
        <a href="{{ route('admin.dashboard')}}" class="side-nav-link">
            <i class="uil-home-alt"></i>
            <span> Dashboard </span>
        </a>
    </li>

    <!--<li class="side-nav-title side-nav-item">Apps</li>-->

    @if(auth()->user()->can('order.view') || auth()->user()->can('permission.view'))
    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#orders" aria-expanded="false" aria-controls="orders" class="side-nav-link">
            <i class="uil-folder-plus"></i>
            <span> Orders Manage</span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="orders">
            <ul class="side-nav-second-level">
              	@if(auth()->user()->can('user.view'))
               	@foreach(getOrderStatus(1) as $key=> $item)
              	<li>
                    <a href="{{ url('admin/orders?q=&status='.$key)}}" class="">
                        <i class="uil-folder-plus"></i>
                        <span>{{$item}} Order</span>
                    </a>
                </li>
      	        @endforeach
              	@endif

            </ul>
        </div>        
    </li>
  @endif


   

    @if(auth()->user()->can('product.view') || auth()->user()->can('type.view') || auth()->user()->can('size.view') || auth()->user()->can('category.view') || auth()->user()->can('discount.view') || auth()->user()->can('color.view'))
    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#products" aria-expanded="false" aria-controls="products" class="side-nav-link">
            <i class="uil-table"></i>
            <span> Products </span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="products">
            <ul class="side-nav-second-level">
              	@if(auth()->user()->can('type.view'))
                <li class="">
                    <a href="{{ route('admin.types.index')}}" class="">
                        <i class="uil-folder-plus"></i>
                        <span> Brand Manage </span>
                    </a>
                </li>
              	@endif
            
            	@if(auth()->user()->can('category.view'))
                <li class="">
                    <a href="{{ route('admin.categories.index')}}" class="">
                        <i class="uil-folder-plus"></i>
                        <span> Category Manage </span>
                    </a>
                </li>
                
              @endif
              
              <li class="">
                    <a href="{{ route('admin.homecat')}}" class="">
                        <i class="uil-folder-plus"></i>
                        <span>Home Category Manage </span>
                    </a>
                </li>
              
              
              
            	@if(auth()->user()->can('size.view'))
                <!--<li class="">-->
                <!--    <a href="{{ route('admin.sizes.index')}}" class="">-->
                <!--        <i class="uil-folder-plus"></i>-->
                <!--        <span> Size Manage </span>-->
                <!--    </a>-->
                <!--</li>-->
              	@endif
				
              	@if(auth()->user()->can('color.view'))
                <!--<li class="">-->
                <!--    <a href="{{ route('admin.colors.index')}}" class="">-->
                <!--        <i class="uil-folder-plus"></i>-->
                <!--        <span> Color Manage </span>-->
                <!--    </a>-->
                <!--</li>-->
              @endif
            
            	@if(auth()->user()->can('product.view'))
                <li class="">
                    <a href="{{ route('admin.products.index')}}" class="">
                        <i class="uil-folder-plus"></i>
                        <span> Products Manage</span>
                    </a>
                </li>
              @endif
            	
              	@if(auth()->user()->can('discount.view'))
                <!--<li class="">-->
                <!--    <a href="{{ route('admin.product_discounts.index')}}" class="">-->
                <!--        <i class="uil-folder-plus"></i>-->
                <!--        <span> Products Discount Manage</span>-->
                <!--    </a>-->
                <!--</li>-->
              @endif
              
              <li class="">
                    <a href="{{ route('admin.free_shipping')}}" class="">
                        <i class="uil-folder-plus"></i>
                        <span> Free Shipping Manage</span>
                    </a>
                </li>
                
                <!--<li class="">-->
                <!--    <a href="{{ route('admin.landing_pages.index') }}" class="">-->
                <!--        <i class="uil-folder-plus"></i>-->
                <!--        <span>Manage Landing Page Data</span>-->
                <!--    </a>-->
                <!--</li>-->
                    
                <li class="">
                    <a href="{{ route('admin.landing_pages_two') }}" class="">
                        <i class="uil-folder-plus"></i>
                        <span>Manage Landing Page</span>
                    </a>
                </li>
               
            </ul>
        </div>
    </li>
  @endif   

    
	@if(auth()->user()->can('purchase.view'))
      	<!--<li class="side-nav-item">-->
       <!-- <a href="{{ route('admin.purchase.index')}}" class="side-nav-link">-->
       <!--   <i class="uil-folder-plus"></i>-->
       <!--   <span> Purchase Manage</span>-->
       <!-- </a>-->
      	<!--</li>  -->
      @endif    
    
    <li class="side-nav-item">
        <a href="{{ route('admin.reviews.index')}}" class="side-nav-link">
            <i class="uil-folder-plus"></i>
            <span> Reviews</span>
        </a>
    </li>
    
    
    
    @if(auth()->user()->can('page.view') || auth()->user()->can('image.view') || auth()->user()->can('slider.view'))
    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#front_page" aria-expanded="false" aria-controls="front_page" class="side-nav-link">
            <i class="uil-table"></i>
            <span> Front Page </span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="front_page">
            <ul class="side-nav-second-level">
             		
              		@if(auth()->user()->can('page.view'))	
                    <li class="">
                        <a href="{{ route('admin.pages.index') }}" class="">
                            <i class="uil-folder-plus"></i>
                            <span>Manage Page Data</span>
                        </a>
                    </li>
              		@endif
                         	
             		@if(auth()->user()->can('image.view'))	
                    <!--<li class="">-->
                    <!--    <a href="{{ route('admin.social_icons.index') }}" class="">-->
                    <!--        <i class="uil-folder-plus"></i>-->
                    <!--        <span>Social Icon</span>-->
                    <!--    </a>-->
                    <!--</li>-->
              		@endif
                    @if(auth()->user()->can('slider.view'))	
                    <li class="">
                        <a href="{{ route('admin.sliders.index')}}" class="">
                            <i class="uil-folder-plus"></i>
                            <span> Slider Manage </span>
                        </a>
                    </li>
              		@endif
                	
              		@if(auth()->user()->can('image.view'))	
                    <!--<li class="">-->
                    <!--    <a href="{{ route('admin.home_section_images.index')}}" class="">-->
                    <!--        <i class="uil-folder-plus"></i>-->
                    <!--        <span> Home Section Image </span>-->
                    <!--    </a>-->
                    <!--</li>-->
              		@endif
    
            </ul>
        </div>
    </li>
    @endif
    
    @if(auth()->user()->can('coupon_codes.view'))
    <!--<li class="side-nav-item">-->
    <!--    <a href="{{ route('admin.coupon_codes.index')}}" class="side-nav-link">-->
    <!--        <i class="uil-folder-plus"></i>-->
    <!--        <span> Coupon Code </span>-->
    <!--    </a>-->
    <!--</li> -->
  	@endif
  	
    @if(auth()->user()->can('delivery_charge.view'))
    <li class="side-nav-item">
        <a href="{{ route('admin.delivery_charge.index')}}" class="side-nav-link">
            <i class="uil-folder-plus"></i>
            <span> Delivery Charge </span>
        </a>
    </li> 
  	@endif


    @if(auth()->user()->can('couriers.view'))
    <li class="side-nav-item">
        <a href="{{ route('admin.couriers.index')}}" class="side-nav-link">
            <i class="uil-folder-plus"></i>
            <span> Courier Manage </span>
        </a>
    </li> 
    @endif
    
 
    
     @if(auth()->user()->can('combo.view') || auth()->user()->can('permission.view') || auth()->user()->can('role.view'))
    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#user" aria-expanded="false" aria-controls="user" class="side-nav-link">
            <i class="uil-table"></i>
            <span> Users </span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="user">
            <ul class="side-nav-second-level">
              	@if(auth()->user()->can('user.view'))
                <li>
                    <a href="{{ route('admin.users.index')}}" class="">
                        <i class="uil-folder-plus"></i>
                        <span>Manage User</span>
                    </a>
                </li>
              	@endif
                @if(auth()->user()->can('permission.view'))
                <!--<li>-->
                <!--    <a href="{{ route('admin.permissions.index')}}" class="">-->
                <!--        <i class="uil-folder-plus"></i>-->
                <!--        <span>Manage Permission</span>-->
                <!--    </a>-->
                <!--</li>-->
              	@endif
                	
              	@if(auth()->user()->can('role.view'))
                <!--<li>-->
                <!--    <a href="{{ route('admin.roles.index')}}" class="">-->
                <!--        <i class="uil-folder-plus"></i>-->
                <!--        <span>Manage Role</span>-->
                <!--    </a>-->
                <!--</li>-->
              	@endif
    
    
            </ul>
        </div>
    </li>
  @endif
    
    @if(auth()->user()->can('order.view') || auth()->user()->can('permission.view'))
    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#reports" aria-expanded="false" aria-controls="reports" class="side-nav-link">
            <i class="uil-table"></i>
            <span> Reports </span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="reports">
            <ul class="side-nav-second-level">
              	@if(auth()->user()->can('user.view'))
                <li>
                    <a href="{{ route('admin.report.order')}}" class="">
                        <i class="uil-folder-plus"></i>
                        <span>Order Report</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.expenses.index')}}" class="">
                        <i class="uil-folder-plus"></i>
                        <span>Expense</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.report.user')}}" class="">
                        <i class="uil-folder-plus"></i>
                        <span>User Report</span>
                    </a>
                </li>
                
              	<li>
                    <a href="{{ route('admin.ipblock')}}" class="">
                        <i class="uil-folder-plus"></i>
                        <span>Block Ip</span>
                    </a>
                </li>
              <li>
                    <!--<a href="{{ route('admin.report.product')}}" class="">-->
                    <!--    <i class="uil-folder-plus"></i>-->
                    <!--    <span>Product Report</span>-->
                    <!--</a>-->
                </li>
              	@endif
              	
            </ul>
        </div>
    </li>
  @endif
    @if(auth()->user()->can('product.delete'))
    <li class="side-nav-item">
        <a href="{{ route('admin.settings.index') }}" class="side-nav-link">
            <i class="dripicons-gear noti-icon"></i>
            <span> Settings Manage </span>
        </a>
    </li> 
    @endif

    
</ul>