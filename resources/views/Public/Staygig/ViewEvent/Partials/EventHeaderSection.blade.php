<!--Nav-->
<nav id="header" class="w-full z-30 text-white relative" style="background-color: {{$event->organiser->page_header_bg_color}};">

    <div class="w-full container mx-auto mt-0 py-2 px-4">

        <div class="flex items-center">
            <a class="toggleColour text-white no-underline hover:no-underline font-bold text-2xl lg:text-4xl"
                href="/">
                <img src="{{URL::to($event->organiser->full_logo_path)}}" class="h-12" />
            </a>
        </div>
    </div>

    <hr class="border-b border-gray-100 opacity-25 my-0 py-0" />
</nav>
@if(!$event->is_live)
<section id="goLiveBar" class="bg-yellow-00 text-yellow-800 text-xs text-center p-2">
    @if(!$event->is_live)
    {{ @trans("ManageEvent.event_not_live") }}
    <a href="{{ route('MakeEventLive' , ['event_id' => $event->id]) }}"
        class="py-1 px-2 bg-green-300 text-green-600 text-xs rounded-lg">{{ @trans("ManageEvent.publish_it") }}</a>
    @endif
</section>
@endif