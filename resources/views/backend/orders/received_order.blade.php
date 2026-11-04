
                <div class="table-responsive">
                    <table class="table table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>                                    
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input check_all" value="">
                                      </label>
                                    </div>
                                </th>
                              	<th style="width:7%">Action</th>
                                <th>Invoice ID</th>
                                <th>Date Order</th>
                                <th>Customers</th>
                                <th>Product SKU</th>
                                <th>Status</th>
                                <th>Fraud Check</th>
                                <th>Payment Status</th>
                                <th>Assign User</th>
                                <th style="width:15%">Courier</th>
                                <th>Amount</th>                                                          
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($received_order as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" class="order_checkbox" value="{{ $item->id}}">                                    
                                </td>
                              	<td>
                                    <!--<a href="{{$item->status === 'pending' ? 'javascript:void(0)' : route('admin.orders.show',[$item->id])}}" target="{{$item->status === 'pending' ? '' : '_blank'}}" class="action-icon " title="{{$item->status === 'pending' ? 'pending invoice will not be printed' : 'Print Invoice'}}"> <i class="fa fa-print" aria-hidden="true"></i></a>-->
                                    <a href="{{ route('admin.orders.edit',[$item->id])}}" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                                    @can('order.delete')
                                    <a href="{{ route('admin.orders.destroy',[$item->id])}}" class="delete action-icon"> <i class="mdi mdi-delete"></i></a>
                                    @endcan
                                </td>
                                <td style="color: #000;">#{{$item->invoice_no}}</td>
                              	<td style="color: #000;">{{ dateFormate($item->date)}}</td>
                                <td style="color: #000;">{{$item->first_name.' '.$item->last_name}}<br>
                                    {{$item->shipping_address}}<br>
                                    {{$item->mobile}}
                                </td>
                              <td>
                                  
                                  <?php
                                    foreach($item->details as $detail)
                                    {
                                        if(!isset($detail->product['sku']) || $detail->product['sku'] == '')
                                        {
                                            ?> <span style="color: red;">Unavailable</span> <?php
                                            
                                        } else 
                                        {
                                            echo $detail->product['sku'];
                                        }
                                    }
                                  ?>
                                
                                </td>
                                <td><a class="btn_modal" href="{{ route('admin.orderStatus', $item->id)}}">
                                        <h5 class="my-0"><span class="badge badge-info-lighten">{{$item->status}}</span></h5>
                                    </a>
                                </td>
                                
                                <td>
                                    @php
                                    $percent =$item->getCourierPercent();
                                    @endphp
                                    
                                    @if($percent > 50)
                                   <div style="color:green;font-weight: bold;text-align:center">
                                    @elseif($percent > 20)
                                    <div style="color:yellow;font-weight: bold;text-align:center">
                                    @else
                                    <div style="color:red;font-weight: bold;text-align:center">
                                    @endif
                                    {{$percent}}%
                                    </div>
                                    <a href='javascript:void(0);' data-url="{{route('admin.fraudOrderCheck',$item->id)}}" class='btn btn-link btn-fraud' style='color: red;cursor: pointer;'> <i class='dripicons-search'></i></a>
                                </td>
                                
                                
                                
                                <td><a class="btn_modal" href="{{ route('admin.order_payments.edit', $item->id)}}">
                                        <h5 class="my-0"><span class="badge badge-danger-lighten">{{$item->payment_status}}</span></h5>    
                                    </a>
                                </td>

                                <td style="color: #000;">{{ $item->assign?$item->assign->username:''}}</td>
                                <td style="color: #000;">{{ $item->courier?$item->courier->name:''}} <br>{{ $item->courier_tracking_id ?? ''}}<br>
                                  
                                    @if($item->courier_id==1 && $item->courier_tracking_id)
                                    <a href="https://redx.com.bd/track-global-parcel/?trackingId={{$item->courier_tracking_id}}" target="_blank">Track Link</a>
                                    @elseif($item->courier_id==3 && $item->courier_tracking_code)
                                    <a href="https://steadfast.com.bd/t/{{$item->courier_tracking_code}}" target="_blank">Track Link</a>
                                    @elseif($item->courier_id==2 && $item->courier_tracking_id)
                                    <a href="https://merchant.pathao.com/tracking?consignment_id={{$item->courier_tracking_id}}" target="_blank">Track Link</a>
                                    @endif
                              	</td>
								<td style="color: #000;">
                                  @php 
                                    $final_amount = $item->final_amount;                                    
                                    $fa = intval($final_amount);                                    
                                   echo $fa;                                    
                                  @endphp                             
                              </td>  
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                  	 </div>
  <script>
    $(".check_all").on('change',function(){
      $(".order_checkbox").prop('checked',$(this).is(":checked"));
    });
    
</script>  
                