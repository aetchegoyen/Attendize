{{-- Eventos próximos --}}
<div class="w-full text-gray-800 leading-normal rounded-t pt-8 pb-20 bg-gray-100 overflow-hidden clipped">
<section class="container md:px-0 mx-auto p-4 md:pb-8 md:pt-8 z-10 relative mb-4">
    @include('Public.Staygig.ViewOrganiser.Partials.EventListingPanel',
        [
            'panel_title' => trans("Public_ViewOrganiser.upcoming_events"),
            'events'      => $upcoming_events,
            'class'       => "large"
        ]
    )
</section>
</div>

{{-- Eventos pasados --}}
<section class="container md:px-0 mx-auto p-4 md:pb-8 md:pt-8 z-10 relative mb-4">
    @include('Public.Staygig.ViewOrganiser.Partials.EventListingPanel',
        [
            'panel_title' => trans("Public_ViewOrganiser.past_events"),
            'events'      => $past_events,
            'class'       => ""
        ]
    )
</section>

{{-- Redes --}}
<div class="w-full text-gray-800 leading-normal rounded-t pt-8 pb-20 bg-gray-100 overflow-hidden clipped">
    <section class="container md:px-0 mx-auto p-4 md:pb-8 md:pt-8 z-10 relative">
        <h2 class="text-2xl text-gray-800 font-bold mb-6">{{ $organiser->facebook }} en las redes</h2>
        <div class="flex md:flex-row">
            <div class="flex md:w-1/2 fb-page"data-href="https://www.facebook.com/{{$organiser->facebook}}"
                data-tabs="timeline" data-small-header="false"
                data-adapt-container-width="true"
                data-with="100%"
                data-hide-cover="false" data-show-facepile="true">
                <div class="fb-xfbml-parse-ignore">
                    <blockquote cite="https://www.facebook.com/facebook">
                        <a href="https://www.facebook.com/{{$organiser->facebook}}">Facebook</a>
                    </blockquote>
                </div>
            </div>
            <div class="flex md:w-1/2">
                <a class="twitter-timeline" data-height="400" href="https://twitter.com/{{ $organiser->twitter }}">Tweets by {{ $organiser->twitter }}</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>
        </div>
    </section>
</div>