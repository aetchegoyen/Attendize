{!! Form::open(['url' => route('postValidateOrder', ['event_id' => $event->id ]), 'class' => 'ajax payment-form']) !!}
<div class="flex rounded-lg shadow-lg items-start">
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
            {!! Form::hidden('event_id', $event->id) !!}

            <h2 class="title"> @lang("Public_ViewEvent.your_information")</h2>

            <div class="my-4 flex flex-row flex-wrap">
                <div class="mb-4 md:w-1/2 max-w-sm pr-4">
                    <div>
                        {!! Form::label("order_first_name", trans("Public_ViewEvent.first_name"), array('class' => "block text-gray-600 mb-1")) !!}
                        {!! Form::text("order_first_name", null, [
                            'required' => 'required', 
                            'class' => 'form-control form-text']) !!}
                    </div>
                </div>
                <div class="mb-4 md:w-1/2 max-w-sm pr-4">
                    <div>
                        {!! Form::label("order_last_name", trans("Public_ViewEvent.last_name"), array('class' => "block text-gray-600 mb-1")) !!}
                        {!! Form::text("order_last_name", null, [
                            'required' => 'required', 
                            'class' => 'form-control form-text']) !!}
                    </div>
                </div>
                <div class="mb-4 md:w-1/2 max-w-sm pr-4">
                    <div>
                        {!! Form::label("order_email", trans("Public_ViewEvent.email"), array('class' => "block text-gray-600 mb-1")) !!}
                        {!! Form::text("order_email", null, [
                            'required' => 'required', 
                            'class' => 'form-control form-text']) !!}
                    </div>
                </div>
                <div class="mb-4 md:w-1/2 max-w-sm px-4 md:pt-8">
                    <div>
                        {!! Form::checkbox('is_business', 1, null, ['data-toggle' => 'toggle', 'id' => 'is_business', 'class' => 'mr-2']) !!}
                        {!! Form::label('is_business', trans("Public_ViewEvent.is_business"), ['class' => 'control-label']) !!}
                    </div>
                </div>
            </div>
            <div class="my-4 flex flex-row flex-wrap hidden" id="business_details">
                <div class="mb-4 md:w-1/2 max-w-sm pr-4">
                    <div>
                        {!! Form::label("business_name", trans("Public_ViewEvent.business_name"), array('class' => "block text-gray-600 mb-1")) !!}
                        {!! Form::text("business_name", null, [
                            'class' => 'form-control form-text']) !!}
                    </div>
                </div>
                <div class="mb-4 md:w-1/2 max-w-sm pr-4">
                    <div>
                        {!! Form::label("business_tax_number", trans("Public_ViewEvent.business_tax_number"), array('class' => "block text-gray-600 mb-1")) !!}
                        {!! Form::text("business_tax_number", null, [
                            'class' => 'form-control form-text']) !!}
                    </div>
                </div>
                <div class="mb-4 md:w-1/2 max-w-sm pr-4">
                    <div>
                        {!! Form::label("business_address_line1", trans("Public_ViewEvent.business_address_line1"), array('class' => "block text-gray-600 mb-1")) !!}
                        {!! Form::text("business_address_line1", null, [
                            'class' => 'form-control form-text']) !!}
                    </div>
                </div>
                <div class="mb-4 md:w-1/2 max-w-sm pr-4">
                    <div>
                        {!! Form::label("business_address_line2", trans("Public_ViewEvent.business_address_line2"), array('class' => "block text-gray-600 mb-1")) !!}
                        {!! Form::text("business_address_line2", null, [
                            'class' => 'form-control form-text']) !!}
                    </div>
                </div>
                <div class="mb-4 md:w-1/2 max-w-sm pr-4">
                    <div>
                        {!! Form::label("business_address_state", trans("Public_ViewEvent.business_address_state_province"), array('class' => "block text-gray-600 mb-1")) !!}
                        {!! Form::text("business_address_state", null, [
                            'class' => 'form-control form-text']) !!}
                    </div>
                </div>
                <div class="mb-4 md:w-1/2 max-w-sm pr-4">
                    <div>
                        {!! Form::label("business_address_city", trans("Public_ViewEvent.business_address_city"), array('class' => "block text-gray-600 mb-1")) !!}
                        {!! Form::text("business_address_city", null, [
                            'class' => 'form-control form-text']) !!}
                    </div>
                </div>
                <div class="mb-4 md:w-1/2 max-w-sm pr-4">
                    <div>
                        {!! Form::label("business_address_code", trans("Public_ViewEvent.business_address_code"), array('class' => "block text-gray-600 mb-1")) !!}
                        {!! Form::text("business_address_code", null, [
                            'class' => 'form-control form-text']) !!}
                    </div>
                </div>
            </div>
                

            <div class="ticket_holders_detailsmt-4" >
                <h3 class="title mb-4">@lang("Public_ViewEvent.ticket_holder_information")</h3>
                <?php
                    $total_attendee_increment = 0;
                ?>
                <button class="bg-transparent text-sm hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-2 border border-blue-300 hover:border-transparent rounded" id="mirror_buyer_info">
                    @lang("Public_ViewEvent.copy_buyer")
                </button>
                <div class="flex flex-row flex-wrap items-start">
                    @foreach($tickets as $ticket)
                        @for($i=0; $i<=$ticket['qty']-1; $i++)
                        <div class="md:w-1/2 flex">
                            <div class="rounded-lg border-solid border-gray-400 border-solid overflow-hidden shadow-lg my-4">
                                <div class="bg-gray-600 py-2 px-4 text-gray-100">
                                    <b>{{$ticket['ticket']['title']}}</b>: @lang("Public_ViewEvent.ticket_holder_n", ["n"=>$i+1])
                                </div>
                                <div class="p-4">
                                    <div class="mb-4">
                                        {!! Form::label("ticket_holder_first_name[{$i}][{$ticket['ticket']['id']}]", trans("Public_ViewEvent.first_name"), array('class' => "block text-gray-600 mb-1")) !!}
                                        {!! Form::text("ticket_holder_first_name[{$i}][{$ticket['ticket']['id']}]", null, ['required' => 'required', 'class' => "ticket_holder_first_name.$i.{$ticket['ticket']['id']} ticket_holder_first_name form-control form-text"]) !!}
                                    </div>
                                    <div class="mb-4">
                                        {!! Form::label("ticket_holder_last_name[{$i}][{$ticket['ticket']['id']}]", trans("Public_ViewEvent.last_name"), array('class' => "block text-gray-600 mb-1")) !!}
                                        {!! Form::text("ticket_holder_last_name[{$i}][{$ticket['ticket']['id']}]", null, ['required' => 'required', 'class' => "ticket_holder_last_name.$i.{$ticket['ticket']['id']} ticket_holder_last_name form-control form-text"]) !!}
                                    </div>
                                    <div class="mb-4">
                                        {!! Form::label("ticket_holder_email[{$i}][{$ticket['ticket']['id']}]", trans("Public_ViewEvent.email_address"), array('class' => "block text-gray-600 mb-1")) !!}
                                        {!! Form::text("ticket_holder_email[{$i}][{$ticket['ticket']['id']}]", null, ['required' => 'required', 'class' => "ticket_holder_email.$i.{$ticket['ticket']['id']} ticket_holder_email form-control form-text"]) !!}
                                    </div>
                                    <div class="row">
                                        @include('Public.Staygig.ViewEvent.Partials.AttendeeQuestions', ['ticket' => $ticket['ticket'],'attendee_number' => $total_attendee_increment++])
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    @endforeach
                </div>
            </div>

        </div>
    </div>
    <div class="w-full md:w-4/12 flex flex-col flex-grow shadow-lg md:-mt-6 md:-ml-8  md:rounded-lg overflow-hidden">
        @if($event->images->first()['image_path'])
        <img src="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" class="w-full h-auto rounded-t">
        @else
        <img src="https://source.unsplash.com/1600x900/?music,live" class="w-full h-auto rounded-t flex">
        @endif
        <div class="flex-1 bg-white rounded-t rounded-b-none md:rounded-t-none shadow-lg p-6">

            <h1 class="title">@lang("Public_ViewEvent.order_details")</h1>
            <p class="text-base">@lang("Public_ViewEvent.below_order_details_header")</p>
            
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

            @if($event->pre_order_display_message)
            <div class="well well-small">
                {!! nl2br(e($event->pre_order_display_message)) !!}
            </div>
            @endif

            @if(session()->get('message'))
                <script>showMessage('{{session()->get('message')}}');</script>
            @endif

        {!! Form::submit(trans("Public_ViewEvent.checkout_order"), ['class' => 'btn-block bg-green-700 text-white font-bold py-2 px-4 rounded w-full']) !!}
        </div>
    </div>
    {!! Form::hidden('is_embedded', $is_embedded) !!}
    {!! Form::close() !!}
</div>

