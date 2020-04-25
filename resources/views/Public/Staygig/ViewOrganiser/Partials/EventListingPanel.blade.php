
<h2 class="text-2xl text-gray-800 font-bold mb-6">{{ $panel_title }}</h2>


<div class="flex flex-row items-start flex-wrap">
    @if(count($events))
        @foreach($events as $event)
    <a class="flex w-full md:w-1/2 lg:w-1/3 xl:w-1/4 hover:scale-105 transform transition-all duration-200 ease-in-out cursor-pointer md:pr-6 pb-6 {{$class}}" href="{{$event->getEventUrlAttribute()}}">
        <div class="rounded-md shadow-lg flex-col w-full">
            <div class="div h-48 overflow-hidden items-end flex flex-col bg-gray-200 relative">

                @if($event->images->first()['image_path'])
                <img src="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" class="rounded-t-md overflow-hidden object-cover h-full z-0">
                @else
                <img src="https://source.unsplash.com/640x480/?music" class="rounded-t-md overflow-hidden object-cover h-full z-0">
                @endif

                @if($event->start_date->isFuture())
                <div class="flex flex-grow-0 rounded-full bg-green-500 text-green-200 text-sm px-2 py-0 z-10 absolute b-20 r-20" data-start_date="{{$event->start_date->format("Y-m-d H:i:s")}}"></div>
                @endif

                @if($event->start_date->isPast() && $event->end_date->isFuture())
                <div class="flex flex-grow-0 rounded-full bg-green-500 text-green-200 text-sm px-2 py-0 z-10 absolute right-0 bottom-0 mr-4 mb-4">En vivo ahora</div>
                @endif
            </div>
            <div class="p-4">
                <div class="flex flex-row items-start">
                    @include('Public.Staygig.Partials.EventDateSection')
                    <div class="flex flex-col items-start flex-grow">
                        <h1 class="font-bold text-md">{{$event->organiser->name}}</h1>
                        <h2 class="text-md text-gray-700">{{$event->title}}</h2>
                        <div class="bg-gray-900 text-gray-100 font-bold flex-row items-start px-2 py-1 rounded-md text-sm ml-auto mt-2">
                            @if($event->tickets->count())
                                @if($event->is_free):
                                @else
                                    Tickets desde {{money($event->tickets->sortBy("price")->first()->price, $event->currency)}}
                                @endif
                                @else
                                    No hay tickets a la venta
                            @endif
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
    </a>
    @endforeach

    @else
        @lang("Public_ViewOrganiser.no_events", ["panel_title"=>$panel_title])
    @endif
</div>