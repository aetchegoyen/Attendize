@extends('Public.Staygig.Home.Layouts.IndexPage')

@section('content')

@include('Public.Staygig.Partials.PublicHeaderSection')

<div class="w-full text-gray-800 leading-normal rounded-t pt-8 pb-20 bg-gray-100 overflow-hidden clipped">

    <div class="container md:px-0 mx-auto p-4 md:pb-8 md:pt-10 z-10 relative">

        <h1 class="text-4xl text-black">Un nuevo escenario para artistas</h1>
        <h2 class="text-3xl text-gray-700">Una plataforma de Recitales, exposiciones, meet & greet y más.</h2>
        <h2 class="text-3xl text-red-500 font-bold mb-4 underline">EN VIVO</h2>
        <p>Asiste a los shows de tus artistas favoritos desde la comodidad de tu hogar.</p> 
        <p>Apoya su arte comprando tickets para acceder al streaming en vivo.</p>

    </div>
</div>

<!--Container-->
<div class="container md:px-0 mx-auto p-4 md:pb-8 md:pt-8 z-10 relative">

    <h2 class="text-2xl text-gray-800 font-bold mb-6">Próximos eventos</h2>


    <div class="flex flex-row items-start flex-wrap">
        @foreach ($events as $event)
        <a class="flex w-full md:w-1/2 lg:w-1/3 xl:w-1/4 hover:scale-105 transform transition-all duration-200 ease-in-out cursor-pointer pr-6 pb-6" href="{{$event->getEventUrlAttribute()}}">
            <div class="rounded-md shadow-lg flex-col w-full">
                <div class="div h-48 overflow-hidden items-end flex flex-col bg-gray-200 relative">

                    @if($event->images->first()['image_path'])
                    <img src="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" class="rounded-t-md overflow-hidden object-cover h-full z-0">
                    @else
                    <img src="https://source.unsplash.com/640x480/?music" class="rounded-t-md overflow-hidden h-full object-cover z-0">
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
    </div>


</div>

{{-- @include('Public.Staygig.ViewEvent.Partials.EventFooterSection') --}}
@stop