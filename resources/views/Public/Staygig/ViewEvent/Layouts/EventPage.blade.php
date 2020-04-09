<!DOCTYPE html>
<html lang="es">
    <head>
        <title>{{{$event->title}}} - StreamingTickets</title>

        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0" />
        <link rel="canonical" href="{{$event->event_url}}" />


        <!-- Open Graph data -->
        <meta property="og:title" content="{{{$event->title}}}" />
        <meta property="og:type" content="article" />
        <meta property="og:url" content="{{$event->event_url}}?utm_source=fb" />
        @if($event->images->count())
        <meta property="og:image" content="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" />
        @endif
        <meta property="og:description" content="{{Str::words(strip_tags(Markdown::convertToHtml($event->description))), 20}}" />
        <meta property="og:site_name" content="Attendize.com" />
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        @yield('head')

        {!!HTML::style('assets/stylesheet/public/dist/main.css')!!}
        {!!HTML::style('/vendor/video.js/dist/video-js.min.css')!!}
        {!!HTML::style('//cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css')!!}

        {{-- @if ($event->bg_type == 'color' || Input::get('bg_color_preview'))
            <style>body {background-color: {{(Input::get('bg_color_preview') ? '#'.Input::get('bg_color_preview') : $event->bg_color)}} !important; }</style>
        @endif

        @if (($event->bg_type == 'image' || $event->bg_type == 'custom_image' || Request::input('bg_img_preview')) && !Request::input('bg_color_preview'))
            <style>
                body {
                    background: url({{(Request::input('bg_img_preview') ? URL::to(Request::input('bg_img_preview')) :  asset(config('attendize.cdn_url_static_assets').'/'.$event->bg_image_path))}}) no-repeat center center fixed;
                    background-size: cover;
                }
            </style>
        @endif --}}

    </head>
    <body class="bg-gray-900 font-sans text-base leading-normal tracking-normal">
        @yield('content')

        {!! HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js') !!}
        @include("Shared.Partials.LangScript")
        @include('Shared.Partials.GlobalFooterJS')
        {!! HTML::script('assets/javascript/dist/public.js') !!}

        @if(isset($secondsToExpire))
        <script>if($('#countdown')) {setCountdown($('#countdown'), {{$secondsToExpire}});}</script>
        @endif
    </body>
</html>
