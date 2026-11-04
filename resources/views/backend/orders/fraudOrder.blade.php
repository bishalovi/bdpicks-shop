@if($result)
<style>
    .courierSummeryFraud tr td img {
        max-height: 40px;
    }
    .courierSummeryFraud tr th {
        background: #e5e7eb;
        padding: 6px;
        text-align: center;
        text-wrap: nowrap;
        min-width: 100px;
        border: 1px solid #dee2e6;
    }
    .courierSummeryFraud tr td {
        vertical-align: middle;
        padding: 6px;
        text-align: center;
        border: 1px solid #dee2e6;
    }
    .cusOrderTable tr td {
        vertical-align: middle;
    }
</style>

<div style="text-align:center;">
    <h3 style="font-size: 28px;">Fraud Tracker Report</h3>
    <p style="color: #24d164;">[ {{$result->customerPhone}} ]</p>
</div>

<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        @if($result->total_ratio > 0)
        @php
            $progressColor = $result->total_ratio > 50 ? '#629e75' : ($result->total_ratio > 20 ? '#f0ad4e' : '#ff4d4f');
        @endphp
        <div style="box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .1);padding: 10px 20px;text-align: center;background: #f9fafb;margin-bottom: 20px;">
            @if($result->total_ratio > 50)
                <span>Customer is awesome</span>
            @elseif($result->total_ratio > 20)
                <span>Customer is good</span>
            @else
                <span>Customer is not good</span>
            @endif
            
            <div class="progress" style="height: 20px;background-color: #ff4d4f;">
              <div class="progress-bar" role="progressbar" style="width: {{$result->total_ratio}}%;background-color: {{$progressColor}};" aria-valuenow="{{$result->total_ratio}}" aria-valuemin="0" aria-valuemax="100">
                Success rate - {{$result->total_ratio}}%
              </div>
            </div>
        </div>
        @else
        <div style="box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .1);padding:20px;text-align: center;background: #ffdbdc;margin-bottom: 20px;font-size: 30px;">
            <span>No Courier Data Found</span>
        </div>
        @endif
    </div>
</div>

@if($result->total_ratio > 0)
<div class="row">
    <div class="col-md-4">
        <div class="widget-rounded-circle card-box order" style="background: #d3e0fb;padding: 1.3rem;">
            <div class="text-center">
                <h3 class="text-dark m-0" style="font-size: 28px;">
                    <span>{{ $result->total_parcels }}</span>
                </h3>
                <p class="m-0 text-truncate" style="color: black;">Total</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="widget-rounded-circle card-box order" style="background: #ccead5;padding: 1.3rem;">
            <div class="text-center">
                <h3 class="text-dark m-0" style="font-size: 28px;">
                    <span>{{ $result->total_delivered }}</span>
                </h3>
                <p class="m-0 text-truncate" style="color: black;">Delivered</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="widget-rounded-circle card-box order" style="background: #ffdbdc;padding: 1.3rem;">
            <div class="text-center">
                <h3 class="text-dark m-0" style="font-size: 28px;">
                    <span>{{ $result->total_canceled }}</span>
                </h3>
                <p class="m-0 text-truncate" style="color: black;">Returned</p>
            </div>
        </div>
    </div>
</div>
<br>

<div class="table-responsive">
    <table class="table table-bordered courierSummeryFraud permission-table">
        <thead class="bg-gray-200">
            <tr>
                <th>Courier</th>
                <th>Total</th>
                <th>Delivered</th>
                <th>Returned</th>
                <th>Success Ratio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($result->purcelsdatas as $courier => $data)
                <tr class="@if($loop->even) bg-gray-100 @else bg-white @endif">
                    <td>
                        <img src="{{ asset('public/backend/images/' . strtolower(str_replace(' ', '', $courier)) . '.png') }}" alt="img" class="w-2/6 h-auto object-contain block mx-auto">
                    </td>

                    @php
                        $total = $delivered = $returned = 0;
                    @endphp

                    @foreach($data as $key => $value)
                        @if($key == 'Total Parcels' || $key == 'Total Delivery')
                            @php $total += $value; @endphp
                            <td>{{ $total }}</td>
                        @elseif($key == 'Delivered Parcels' || $key == 'Successful Delivery')
                            @php $delivered += $value; @endphp
                            <td>{{ $delivered }}</td>
                        @elseif($key == 'Canceled Parcels' || $key == 'Canceled Delivery')
                            @php $returned += $value; @endphp
                            <td>{{ $returned }}</td>
                        @endif
                    @endforeach

                    @php
                        $successRatio = $total > 0 ? round(($delivered / $total) * 100, 2) : 0;
                    @endphp
                    <td>{{ $successRatio }}%</td>
                </tr>
            @endforeach
            @foreach($result->purcelsdatas as $courier => $data)
                @if(isset($data['Details']) && is_array($data['Details']) && count($data['Details']) > 0)
                <tr>
                    <td colspan="5" style="text-align: left; color: #ff4d4f;">
                        <strong>Details:</strong>
                        <ul>
                            @foreach($data['Details'] as $detail)
                                <li>{{ $detail }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>


<hr>
<br>

<div class="table-responsive">
    <p style="font-weight: bold;text-align: center;color: black">Customer Order History</p>

    @php
        $number = $result->customerPhone ?: '000000000000';
        $oldOrders = App\Models\Order::whereHas('user', function($q) use ($number) {
            $q->where('mobile','like','%' . $number . '%');
        })->get();
    @endphp

    <table class="table table-bordered cusOrderTable">
        <tr>
            <th style="width:100px;min-width:100px;">Order ID</th>
            <th style="min-width:300px;">Product Info</th>
            <th style="width:100px;min-width:100px;">Status</th>
        </tr>
        @foreach($oldOrders as $oldOrder)
            <tr>
                <td>{{$oldOrder->invoice_no}}</td>
                <td>
                    @foreach($oldOrder->details()->whereHas('product')->get() as $item)
                        <div style="display: flex;align-items: center;margin-bottom: 5px;">
                            <img src="{{ getImage('products', $item->product->image)}}" style="width:40px;height:40px;margin-right: 10px;">
                            <div>
                                {{$item->product->name}} <br>
                                Amount: {{number_format($item->unit_price * $item->quantity)}} TK <br>
                                QTY: {{ $item->quantity }}
                            </div>
                        </div>
                    @endforeach
                </td>
                <td>{{$oldOrder->status}}</td>
            </tr>
        @endforeach
    </table>
</div>

@endif

@else
<h3>No order Found</h3>
@endif
