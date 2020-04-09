@extends('Public.Staygig.ViewOrganiser.Layouts.OrganiserPage')

@section('head')
     <style>
          body { background-color: {{$organiser->page_bg_color}} !important; }
          section#intro {
               background-color: {{$organiser->page_header_bg_color}} !important;
               color: {{$organiser->page_text_color}} !important;
          }
          .event-list > li > time {
               color: {{$organiser->page_text_color}};
               background-color: {{$organiser->page_header_bg_color}};
          }

     </style>
     @if($organiser->google_analytics_code)
          @include('Public.Staygig.Partials.ga', ['analyticsCode' => $organiser->google_analytics_code])
     @endif
@stop

@section('content')
     @include('Public.Staygig.ViewOrganiser.Partials.OrganiserHeaderSection')
     @include('Public.Staygig.ViewOrganiser.Partials.OrganiserEventsSection')
     @include('Public.Staygig.ViewOrganiser.Partials.OrganiserFooterSection')
@stop

