<div class="table-responsive">
                    <table class="table table-centered mb-0" id="order_report_table">
                        <thead class="table-light">
                            <tr>
                              	<th width="12%" style="font-size: 11px;">User Name</th>
                              	<th width="12%" style="font-size: 11px;">Total Order</th>
                              	<th width="12%" style="font-size: 11px;">Pending Orders</th>
                              	<th width="12%" style="font-size: 11px;">Processing Order</th>
                              	<th width="12%" style="font-size: 11px;">Courier Order</th>
                              	<th width="12%" style="font-size: 11px;">Courier Complete Order</th>
                              	<th width="12%" style="font-size: 11px;">On Hold Order</th>
                              	<th width="12%" style="font-size: 11px;">Complete Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>                           
                                <td style="font-size: 11px;color: #000;">{{ $item->assign_user_name }}</td>
                                <td style="font-size: 11px;color: #000;">{{ $item->total_orders }}</td>
                                <td style="font-size: 11px;color: #000;">{{ $item->pending_orders }}</td>
                                <td style="font-size: 11px;color: #000;">{{ $item->processing_orders }}</td>
                                <td style="font-size: 11px;color: #000;">{{ $item->courier_orders }}</td>
                                <td style="font-size: 11px;color: #000;">{{ $item->courier_complete_orders }}</td>
                                <td style="font-size: 11px;color: #000;">{{ $item->on_hold_orders }}</td>
                                <td style="font-size: 11px;color: #000;">{{ $item->complete_orders }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <br/>
                       <tfoot>
                            {{ $items->links() }}
                       </tfoot>
                    </table>
                 </div>