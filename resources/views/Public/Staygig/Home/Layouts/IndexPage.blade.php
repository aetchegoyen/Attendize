<!DOCTYPE html>
<html lang="es">
    <head>
        <title>StreamingTickets</title>

        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0" />
        <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;800&display=swap" rel="stylesheet">
        {{-- <link rel="canonical" href="{{$event->event_url}}" /> --}}


        <!-- Open Graph data -->
        {{-- <meta property="og:title" content="{{{$event->title}}}" /> --}}
        <meta property="og:type" content="article" />
        {{-- <meta property="og:url" content="{{$event->event_url}}?utm_source=fb" /> --}}
        {{-- @if($event->images->count()) --}}
        {{-- <meta property="og:image" content="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" /> --}}
        {{-- @endif --}}
        {{-- <meta property="og:description" content="{{Str::words(strip_tags(Markdown::convertToHtml($event->description))), 20}}" /> --}}
        {{-- <meta property="og:site_name" content="Attendize.com" /> --}}
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        @yield('head')

        {!!HTML::style('assets/stylesheet/public/dist/main.css')!!}
        {!!HTML::style('//cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css')!!}

    </head>
    <body class="bg-white font-sans text-base leading-normal tracking-normal">
        @yield('content')

        {!! HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js') !!}
        @include("Shared.Partials.LangScript")
        @include('Shared.Partials.GlobalFooterJS')
        {!! HTML::script('assets/javascript/dist/public.js') !!}

    </body>
</html>
