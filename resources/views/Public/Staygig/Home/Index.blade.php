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

    @include('Public.Staygig.ViewOrganiser.Partials.EventListingPanel',
        [
            'panel_title' => trans("Public_ViewOrganiser.upcoming_events"),
            'events'      => $events,
            'class'       => "large"
        ]
    )

</div>

{{-- @include('Public.Staygig.ViewEvent.Partials.EventFooterSection') --}}
@stop