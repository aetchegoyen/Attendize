<div class="rounded-lg shadow-lg rounded-tl-lg rounded-bl-lg md:rounded-lg p-6 bg-white">
    {{-- Thanks --}}
    <div class="bg-green-500 -mt-6 -mx-6 mb-4 p-6 rounded-t-lg text-center">
        <h1 class="px-0 text-green-100 text-2xl">{{ @trans("Public_ViewEvent.thank_you_for_your_order") }}</h1>
        {{-- Ticket download --}}
        <div class="text-center py-4 lg:px-4 text-base">
            <div class="py-2 px-4 bg-green-200 items-center text-green-800 leading-none rounded-full flex lg:inline-flex" role="alert">
                <span class="font-semibold mr-2 text-center flex-auto">
                    {{ @trans("Public_ViewEvent.confirmation_email") }}
                </span>
            </div>
        </div>
    </div>
    
    <div class="flex items-start flex-col md:flex-row">
        <div class="w-full md:w-7/12 bg-cover md:p-4">

            {{-- Event messages --}}
            @if($event->post_order_display_message)
                <div class="alert alert-dismissable alert-info">
                    {{ nl2br(e($event->post_order_display_message)) }}
                </div>
            @endif

            <div class="text-base p-4 -mt-6 -mx-6 md:mx-0 md:-mt-3 bg-gray-200 rounded-lg">

                <h3 class="title mb-4 text-gray-800">
                    @lang("Public_ViewEvent.order_details")
                </h3>
                <b>@lang("Public_ViewEvent.email")</b><br>{{$order->email}}
                
                <div class="flex flex-row flex-wrap text-left">

                    <div class="md:w-1/3 w-1/2">
                        <b>@lang("Public_ViewEvent.first_name")</b><br> {{$order->first_name}}
                    </div>

                    <div class="md:w-1/3 w-1/2">
                        <b>@lang("Public_ViewEvent.last_name")</b><br> {{$order->last_name}}
                    </div>

                    <div class="md:w-1/3 w-1/2">
                        <b>@lang("Public_ViewEvent.amount")</b><br> {{$order->event->currency_symbol}}{{number_format($order->total_amount, 2)}}
                        @if($event->organiser->charge_tax)
                        <small>{{ $orderService->getVatFormattedInBrackets() }}</small>
                        @endif
                    </div>

                    <div class="md:w-1/3 w-1/2">
                        <b>@lang("Public_ViewEvent.reference")</b><br> {{$order->order_reference}}
                    </div>

                    @if ($order->is_business)
                    <div class="md:w-1/3 w-1/2">
                        <b>@lang("Public_ViewEvent.business_name")</b><br> {{$order->business_name}}
                    </div>
                    <div class="md:w-1/3 w-1/2">
                        <b>@lang("Public_ViewEvent.business_tax_number")</b><br> {{$order->business_tax_number}}
                    </div>
                    <div class="md:w-1/3 w-1/2">
                        <b>@lang("Public_ViewEvent.business_address")</b><br />
                        @if ($order->business_address_line_one)
                        {{$order->business_address_line_one}},
                        @endif
                        @if ($order->business_address_line_two)
                        {{$order->business_address_line_two}},
                        @endif
                        @if ($order->business_address_state_province)
                        {{$order->business_address_state_province}},
                        @endif
                        @if ($order->business_address_city)
                        {{$order->business_address_city}},
                        @endif
                        @if ($order->business_address_code)
                        {{$order->business_address_code}}
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap">
                @foreach($order->attendees as $attendee)
                
                <div class="w-full lg:w-1/2 lg:pr-6 py-4">
                    <div class="rounded-lg shadow-lg p-6 text-base">
                        <div class="text-gray-100 -mt-6 -mx-6 mb-4 px-4 py-2 rounded-t-lg bg-gray-600">{{{$attendee->ticket->title}}}</div>
                        <p class="text-gray-600 mt-2">@lang("Public_ViewEvent.access_code"): <b>{{$attendee->private_reference_number}}</b></p>
                        @if($attendee->is_cancelled)
                            @lang("Public_ViewEvent.attendee_cancelled")
                        @endif
                    </div>
                </div>
                @endforeach
            </div>



            <table class="table-auto text-base w-full my-6 rounded-lg overflow-hidden hidden md:block">
                <thead>
                    <tr class="text-left bg-gray-200">
                        <th class="py-2 px-4"> @lang("Public_ViewEvent.ticket") </th>
                        <th class="text-right py-2 px-4"> @lang("Public_ViewEvent.quantity_full") </th>
                        <th class="text-right py-2 px-4"> @lang("Public_ViewEvent.price") </th>
                        <th class="text-right py-2 px-4"> @lang("Public_ViewEvent.booking_fee") </th>
                        <th class="text-right py-2 px-4"> @lang("Public_ViewEvent.total") </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $order_item)
                        <tr>
                            <td class="py-2 px-4"> {{$order_item->title}} </td>
                            <td class="text-right py-2 px-4"> {{$order_item->quantity}} </td>
                            <td class="text-right py-2 px-4">
                                @if((int)ceil($order_item->unit_price) == 0)
                                    @lang("Public_ViewEvent.free")
                                @else
                            {{money($order_item->unit_price, $order->event->currency)}}
                                @endif
                            </td>
                            <td class="text-right py-2 px-4">
                                @if ((int)ceil($order_item->unit_booking_fee) > 0)
                                    @if((int)ceil($order_item->unit_price) == 0)
                                    -
                                    @else
                                    {{money($order_item->unit_booking_fee, $order->event->currency)}}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right py-2 px-4">
                                @if((int)ceil($order_item->unit_price) == 0)
                                    @lang("Public_ViewEvent.free")
                                @else
                                {{money(($order_item->unit_price + $order_item->unit_booking_fee) * ($order_item->quantity), $order->event->currency)}}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-100">
                        <td colspan="4" class="bold text-right py-2 px-4">@lang("Public_ViewEvent.sub_total")</td>
                        <td class="text-right py-2 px-4">{{ $orderService->getOrderTotalWithBookingFee(true) }}</td>
                    </tr>
                    @if($event->organiser->charge_tax)
                    <tr class="bg-gray-100">
                        <td colspan="4" class="bold text-right py-2 px-4">{{$event->organiser->tax_name}}<em>({{$order->event->organiser->tax_value}}%)</em></td>
                        <td class="text-right py-2 px-4"> {{ $orderService->getTaxAmount(true) }} </td>
                    </tr>
                    @endif
                    <tr class="bg-gray-200">
                        <td colspan="4" class="text-right py-2 px-4"><b>Total</b> </td>
                        <td class="text-right bold py-2 px-4"> {{ $orderService->getGrandTotal(true) }} </td>
                    </tr>
                    @if($order->is_refunded || $order->is_partially_refunded)
                        <tr>
                            <td colspan="4" class="text-right bold py-2 px-4">@lang("Public_ViewEvent.refunded_amount")</td>
                            <td class="text-right bold py-2 px-4"> {{money($order->amount_refunded, $order->event->currency)}} </td>
                        </tr>
                        <tr">
                            <td colspan="4" class="text-right bold py-2 px-4">@lang("Public_ViewEvent.total")</td>
                            <td class="text-right bold py-2 px-4"> {{money($order->total_amount - $order->amount_refunded, $order->event->currency)}} </td>
                        </tr">
                    @endif
                </tbody>
            </table>
        </div>
        <div class="w-full md:w-5/12 flex flex-col bg-white md:rounded-lg overflow-hidden">
             {{-- Event Data --}}
             <div class="flex flex-row items-start mb-6">
                @include('Public.Staygig.ViewEvent.Partials.EventDateSection')
                <div>
                    <h1 class="w-full flex px-0 title">{{{$event->title}}}</h1>
                    <p class="text-gray-800 text-lg">Por {{$event->organiser->name}}</p>  
                </div>
            </div>
            @if($event->images->first()['image_path'])
            <img src="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" class="w-full h-auto rounded-lg">
            @else
            <img src="https://source.unsplash.com/1600x900/?music,live" class="w-full h-auto rounded-lg">
            @endif
            <a target="_blank" class="text-center btn-block bg-green-700 text-white font-bold py-2 px-4 mt-6 rounded w-full" href="{{ route('showOrderTickets', ['order_reference' => $order->order_reference] ).'?download=1' }}">
                {{ @trans("Public_ViewEvent.download_tickets") }}
            </a>

            <a class="text-center btn-block bg-gray-600 text-white font-bold py-2 px-4 mt-6 rounded w-full" href="{{ route('showEventPage', ['event_id' => $event->id, 'event_slug' => Str::Slug($event->title)] ) }}">
                {{ @trans("Public_ViewEvent.go_to_event") }}
            </a>

        </div>
    </div>
</div>