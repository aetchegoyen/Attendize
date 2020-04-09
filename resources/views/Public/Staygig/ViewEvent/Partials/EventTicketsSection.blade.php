<div class="w-full md:w-7/12 rounded-tl-lg rounded-bl-lg flex flex-grow-0 flex-col">
    <div class="flex">
        @if($event->images->first()['image_path'])
        <img src="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" class="w-full h-auto rounded-t flex">
        @else
        <img src="https://source.unsplash.com/1600x900/?music,live" class="w-full h-auto rounded-t flex">
        @endif
    </div>
</div>

<div class="w-full md:w-5/12 flex flex-col flex-grow flex-shrink shadow-lg bg-white md:rounded-lg">

    <div class="flex-1 bg-white rounded-t rounded-b-none shadow-lg p-4">
        <div class="flex flex-row items-start">
            @include('Public.Staygig.ViewEvent.Partials.EventDateSection')

            <div>
                <div class="w-full flex s font-bold text-2xl text-gray-900 mt-0 leading-tight">{{{$event->title}}}</div>
                <p class="text-gray-800 text-lg mb-5">Por {{$event->organiser->name}}</p>
                <div class="text-sm -mt-2 p-0">
                    @if($tickets->count() == 0)
                    <div class="p-2 bg-yellow-600 items-center text-yellow-100 leading-none lg:rounded-full flex lg:inline-flex" role="alert">
                        <span class="font-semibold mx-2 text-left flex-auto">@lang("Public_ViewEvent.tickets_are_currently_unavailable")</span>
                    </div>
                    @else
                        @if($event->start_date->isFuture())
                        <div class="p-2 bg-green-600 items-center text-green-100 leading-none lg:rounded-full flex lg:inline-flex" role="alert" data-start_date="{{$event->start_date->format("Y-m-d H:i:s")}}">
                            <span class="font-semibold mx-2 text-left flex-auto"></span>
                        </div>
                        @endif
                        @if($event->start_date->isPast() && $event->end_date->isFuture())
                        <div class="p-2 bg-green-800 items-center text-green-100 leading-none lg:rounded-full flex lg:inline-flex" role="alert">
                            <span class="font-semibold mx-2 text-left flex-auto">@lang("Public_ViewEvent.event_already", ['started' => trans('Public_ViewEvent.event_already_started')])</span>
                        </div>
                        @endif
                        @if($event->end_date->isPast())
                        <div class="p-2 bg-indigo-800 items-center text-indigo-100 leading-none lg:rounded-full flex lg:inline-flex" role="alert">
                            <span class="font-semibold mx-2 text-left flex-auto">@lang("Public_ViewEvent.event_already", ['started' => trans('Public_ViewEvent.event_already_ended')])</span>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @if($tickets->count() > 0 && $event->end_date->isFuture())
            {!! Form::open(['url' => route('postValidateTickets', ['event_id' => $event->id]), 'class' => 'ajax']) !!}
            <?php $is_free_event = true; ?>

            @foreach($tickets->where('is_hidden', false) as $ticket)
            <div class="pb-4 pt-0">
                <div class="flex bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="w-4/5 p-4">
                        <h1 class="text-gray-900 font-bold text-lg">{{$ticket->title}}</h1>
                        <p class="mt-1 text-gray-600 text-sm">{{$ticket->description}}</p>
                        <div class="flex item-center justify-between mt-1">
                            <p class="text-blue-700 font-bold text-lg">
                                @if($ticket->is_free)
                                    @lang("Public_ViewEvent.free")
                                    <meta property="price" content="0">
                                @else
                                    <?php $is_free_event = false; ?>
                                    {{money($ticket->price, $event->currency)}}
                                    <meta property="priceCurrency" content="{{ $event->currency->code }}">
                                    <meta property="price" content="{{ number_format($ticket->price, 2, '.', '') }}">
                                    <span class="text-xs text-gray-500">{{ ($event->organiser->tax_name && $event->organiser->tax_value) ? '(+'.money(($ticket->total_price*($event->organiser->tax_value)/100), $event->currency).' '.$event->organiser->tax_name.')' : '' }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="w-1/5 flex items-center pr-4">
                        @if($ticket->is_paused)
                            <span class="text-orange-500 font-bold"> @lang("Public_ViewEvent.currently_not_on_sale") </span>
                        @else
                            @if($ticket->sale_status === config('attendize.ticket_status_sold_out'))
                                <span class="text-red-500 font-bold" property="availability" content="http://schema.org/SoldOut">@lang("Public_ViewEvent.sold_out")</span>
                            @elseif($ticket->sale_status === config('attendize.ticket_status_before_sale_date'))
                                <span class="text-orange-500 font-bold">@lang("Public_ViewEvent.sales_have_not_started")</span>
                            @elseif($ticket->sale_status === config('attendize.ticket_status_after_sale_date'))
                                <span class="text-orange-500 font-bold">@lang("Public_ViewEvent.sales_have_ended")</span>
                            @else
                                {!! Form::hidden('tickets[]', $ticket->id) !!}
                                <meta property="availability" content="http://schema.org/InStock">
                                <div class="relative w-full">
                                    <select name="ticket_{{$ticket->id}}" class="text-base form-control w-full">
                                    <div class="relative">    
                                        <option value="0">0</option>
                                        @for($i=$ticket->min_per_person; $i<=$ticket->max_per_person; $i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div> 
                </div>
            </div>
            @endforeach
            @if ($tickets->where('is_hidden', true)->count() > 0 && false)
            <div class=" has-access-codes" data-url="{{route('postShowHiddenTickets', ['event_id' => $event->id])}}">
                <div class="flex rounded-lg overflow-hidden">
                    <div class="w-4/5 p-4">
                        <p class="mt-1 text-gray-600 text-sm">@lang("Public_ViewEvent.has_unlock_codes")</p>
                        <div class="flex item-center justify-between mt-1">
                            {!!  Form::text('unlock_code', null, [
                                'class' => 'form-control outline-none',
                                'id' => 'unlock_code',
                                'style' => 'display:inline-block;width:65%;text-transform:uppercase;',
                                'placeholder' => 'XXXDDAA',
                            ]) !!}
                        </div>
                    </div>

                    <div class="w-1/5  flex items-center pr-4">
                        {!! Form::button(trans("basic.apply"), [
                            'class' => "bg-blue-300 text-blue-800 p-2 rounded-lg text-base w-full",
                            'id' => 'apply_access_code',
                            'style' => 'display:inline-block;margin-top:-2px;',
                            'data-dismiss' => 'modal',
                        ]) !!}
                    </div> 
                </div>
            </div>
            @endif
        @endif

        <div class="formErrors text-red-500 text-center p-4"></div>

    </div>

    <div class="flex-none mt-auto bg-white rounded-b rounded-t-none overflow-hidden shadow-lg p-4 pt-0">
        <div class="flex items-center justify-between">
            {!!Form::submit(trans("Public_ViewEvent.register"), [
                'class' => 'btn-block bg-green-700 opacity-50 cursor-not-allowed text-white font-bold py-2 px-4 rounded w-full cursor-pointer',
                "disabled" => "disabled"
            ])!!}
        </div>
    </div>
    {!! Form::hidden('is_embedded', $is_embedded) !!}
    {!! Form::close() !!}
</div>