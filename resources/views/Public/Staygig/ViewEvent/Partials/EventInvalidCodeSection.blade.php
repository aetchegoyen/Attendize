<div class="container md:px-0 mx-auto p-4 md:pb-8 md:pt-10 z-10 relative">

    <div class="mx-0">

        <div class="w-full text-gray-800 leading-normal rounded-lg">
            <div class="flex rounded-lg shadow-lg flex flex-col md:flex-row items-center">

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
                                </div>
                            </div>
                        </div>
                        <div class="my-8 mx-4 p-4 border-2 border-red-600 bg-red-500 text-xl rounded-lg text-red-100 font-bold text-center">
                            @lang("AccessCodes.no_tickets_matched")
                        </div>

                        <a class="bg-green-700 text-white text-center font-bold py-2 px-4 rounded-lg block mx-auto" href="{{route('showEventPage', ['event_id' => $event->id])}}" >{{trans("basic.back_to_page", ["page"=>$event->title])}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>