@extends('Public.Staygig.ViewEvent.Layouts.EmbeddedEventPage')

@section('content')
    @include('Public.Staygig.ViewEvent.Partials.EventShareSection')
    @include('Public.Staygig.ViewEvent.Partials.EventViewOrderSection')
    @include('Public.Staygig.ViewEvent.Embedded.Partials.PoweredByEmbedded')
@stop
