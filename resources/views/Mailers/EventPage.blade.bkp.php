@extends('Public.ViewEvent.Layouts.EventPage')

@section('head')
    @include('Public.ViewEvent.Partials.GoogleTagManager')
@endsection

@section('content')
<div class="container">
    @include('Public.ViewEvent.Partials.EventHeaderSection')
    @include('Public.ViewEvent.Partials.EventShareSection')
    <div class="row">
        <div class="col-sm-7">
            @include('Public.ViewEvent.Partials.EventDescriptionSection')
        </div>
        <div class="col-sm-5">
            @include('Public.ViewEvent.Partials.EventTicketsSection')      
        </div>
    </div>
    {{-- @include('Public.ViewEvent.Partials.EventOrganiserSection') --}}
    {{-- @include('Public.ViewEvent.Partials.EventFooterSection') --}}
</div>
@stop

