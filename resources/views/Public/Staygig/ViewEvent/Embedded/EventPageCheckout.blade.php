@extends('Public.Staygig.ViewEvent.Layouts.EmbeddedEventPage')

@section('head')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@stop

@section('content')
    @include('Public.Staygig.ViewEvent.Partials.EventCreateOrderSection')
    @include('Public.Staygig.ViewEvent.Embedded.Partials.PoweredByEmbedded')
    <script> var OrderExpires = {{strtotime($expires)}};</script>
@stop

