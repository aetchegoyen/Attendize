<div class="flex-1 bg-white rounded overflow-hidden shadow-lg pt-6">
    <a href="#" class="flex flex-wrap no-underline hover:no-underline">
        <p class="w-full text-gray-600 text-xs md:text-sm px-6">@lang("Public_ViewEvent.event_details")</p>
        <div class="text-gray-800 font-sans text-base px-6 mt-5 mb-5 p-spaced">
            {!! Markdown::parse($event->description) !!}
        </div>
    </a>
</div>
{{-- 
<section id="details" class="container-fluid">
    <div class="row">
        <h1 class="section_head">
            @lang("Public_ViewEvent.event_details")
        </h1>
    </div>
    <div class="row">
        @php
            $descriptionColSize =  $event->images->count()
                && in_array($event->event_image_position, ['left', 'right'])
                ? '7' : '12';
        @endphp

        @if ($event->images->count() && $event->event_image_position == 'left')
            <div class="col-md-5">
                <div class="content event_poster">
                    <img alt="{{$event->title}}" src="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" property="image">
                </div>
            </div>
        @endif
        @if ($event->images->count() && $event->event_image_position == 'before')
            <div class="col-md-12" style="margin-bottom: 20px">
                <div class="content event_poster">
                    <img alt="{{$event->title}}" src="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" property="image">
                </div>
            </div>
        @endif

        <div class="col-md-{{ $descriptionColSize }}">
            <div class="content event_details" property="description">
                {!! Markdown::parse($event->description) !!}
            </div>
        </div>

        @if ($event->images->count() && $event->event_image_position == 'right')
            <div class="col-md-5">
                <div class="content event_poster">
                    <img alt="{{$event->title}}" src="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" property="image">
                </div>
            </div>
        @endif
        @if ($event->images->count() && $event->event_image_position == 'after')
            <div class="col-md-12" style="margin-top: 20px">
                <div class="content event_poster">
                    <img alt="{{$event->title}}" src="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" property="image">
                </div>
            </div>
        @endif
    </div>
</section> --}}