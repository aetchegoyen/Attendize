<div class="flex rounded-lg shadow-lg items-start" id='order_form'>
    <div class="w-full md:w-8/12 bg-cover bg-center rounded-tl-lg rounded-bl-lg md:rounded-lg p-6 bg-white">
        <div class="bg-gray-200 -mt-6 -mx-6 mb-4 p-6 rounded-t-lg">
            <div class="flex flex-row items-start">

                @include('Public.Staygig.ViewEvent.Partials.EventDateSection')
                <div>
                    <h1 class="w-full flex px-0 title">{{{$event->title}}}</h1>
                    <p class="text-gray-800 text-lg">Por {{$event->organiser->name}}</p>  
                    <p class="text-gray-600 text-sm">{!! @trans("Public_ViewEvent.time", ["time"=>"<span id='countdown'></span>"]) !!}</p>              
                </div>
            </div>
        </div>
        <div class="event_order_form text-base p-4 md:pr-8">
            <h2 class="title">@lang("Public_ViewEvent.order_summary")</h2>
            @lang("Public_ViewEvent.below_order_details_header")

            <table class="table-auto text-base w-full my-6 rounded-lg overflow-hidden">
                @foreach($tickets as $ticket)
                <tr>
                    <td class="border-l border-t border-b p-2 text-right">{{$ticket['qty']}}</td>
                    <td class="border-t border-b px-2">{{{$ticket['ticket']['title']}}}</td>
                    <td class="border-t border-b border-r px-4 py-2 text-right">
                        @if((int)ceil($ticket['full_price']) === 0)
                            @lang("Public_ViewEvent.free")
                        @else
                            {{ money($ticket['full_price'], $event->currency) }}
                        @endif
                    </td>
                </tr>
                @endforeach
                @if($order_total > 0)
                <tr class="bg-gray-100">
                    <td colspan="2" class="border-t border-b border-l px-2">@lang("Public_ViewEvent.sub_total")</td>
                    <td class="border-t border-b border-r px-4 py-2 text-right">
                        <b>{{ $orderService->getOrderTotalWithBookingFee(true) }}</b>
                    </td>
                </tr>
                @endif
                @if($event->organiser->charge_tax)
                <tr class="bg-gray-100">
                    <td colspan="2" class="border-t border-b border-l px-2">{{ $event->organiser->tax_name }} ({{ $event->organiser->tax_value }}%)</td>
                    <td class="border-t border-b border-r px-4 py-2 text-right">
                        <b>{{ $orderService->getTaxAmount(true) }}</b>
                    </td>
                </tr>
                @endif
                <tr class="bg-gray-200">
                    <td colspan="2" class="border-t border-b border-l px-2">@lang("Public_ViewEvent.grand_total")</td>
                    <td class="border-t border-b border-r px-4 py-2 text-right">
                        <b>{{ $orderService->getGrandTotal(true) }}</b>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="w-full md:w-4/12 flex flex-col flex-grow shadow-lg md:-mt-6 md:-ml-8  md:rounded-lg overflow-hidden">
        @if($event->images->first()['image_path'])
        <img src="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" class="w-full h-auto rounded-t">
        @else
        <img src="https://source.unsplash.com/1600x900/?music,live" class="w-full h-auto rounded-t">
        @endif
        <div class="flex-1 bg-white rounded-t rounded-b-none md:rounded-t-none shadow-lg p-6">

            <h2 class="title">@lang("Public_ViewEvent.payment_information")</h2>
            @if($payment_failed)
            <div class="row">
                <div class="col-md-8 alert-danger" style="text-align: left; padding: 10px">
                    @lang("Order.payment_failed")
                </div>
            </div>
            @endif

            @if($order_requires_payment)
                @include('Public.Staygig.ViewEvent.Partials.OfflinePayments')
            @endif

            <?php $payment_gateway['checkout_blade_template'] = str_replace("Public.ViewEvent", "Public.Staygig.ViewEvent", $payment_gateway['checkout_blade_template']); ?>
            @if(View::exists($payment_gateway['checkout_blade_template']))
                @include($payment_gateway['checkout_blade_template'])
            @endif

        </div>
    </div>
</div>
{{-- 
    
</section>
@if(session()->get('message'))
<script>showMessage('{{session()->get('message')}}');</script>
@endif
 --}}
