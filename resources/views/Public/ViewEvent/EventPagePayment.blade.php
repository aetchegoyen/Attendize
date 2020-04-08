@extends('Public.ViewEvent.Layouts.EventPage')

@section('head')

@stop

@section('content')
    @include('Public.ViewEvent.Partials.EventHeaderSection')

    <!--Container-->
    <div class="container md:px-0 mx-auto mt-10">
        <div class="mx-0">
            <div class="bg-gray-900 w-full text-gray-800 leading-normal rounded-t">
                @include('Public.ViewEvent.Partials.EventPaymentSection')
            </div>
        </div>
    </div>

@include('Public.ViewEvent.Partials.EventFooterSection')
@stop

