<!DOCTYPE html>
<html lang="es">
    <head>
        <title>{{{$organiser->name}}}</title>

        <!-- Open Graph data -->
        <meta property="og:title" content="{{{$organiser->name}}}" />
        <meta property="og:type" content="article" />
        <meta property="og:url" content="{{URL::to('')}}" />
        <meta property="og:image" content="{{URL::to($organiser->full_logo_path)}}" />
        <meta property="og:description" content="{{{Str::words(strip_tags($organiser->description)), 20}}}" />
        <meta property="og:site_name" content="Attendize.com" />
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->


        <!-- Open Graph data -->
        {{-- <meta property="og:title" content="{{{$event->title}}}" /> --}}
        <meta property="og:type" content="article" />
        {{-- <meta property="og:url" content="{{$event->event_url}}?utm_source=fb" /> --}}
        {{-- @if($event->images->count()) --}}
        {{-- <meta property="og:image" content="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" /> --}}
        {{-- @endif --}}
        {{-- <meta property="og:description" content="{{Str::words(strip_tags(Markdown::convertToHtml($event->description))), 20}}" /> --}}
        {{-- <meta property="og:site_name" content="Attendize.com" /> --}}
        @yield('head')

        {!!HTML::style('assets/stylesheet/public/dist/main.css')!!}
        {!!HTML::style('//cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css')!!}

    </head>
    <body class="font-sans text-base leading-normal tracking-normal" style="background-color: {{$organiser->page_bg_color}} !important;">
        @include('Shared.Partials.FacebookSdk')
        @yield('content')

        {!! HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js') !!}
        @include("Shared.Partials.LangScript")
        @include('Shared.Partials.GlobalFooterJS')
        {!! HTML::script('assets/javascript/dist/public.js') !!}
        @yield('foot')

    </body>
</html>