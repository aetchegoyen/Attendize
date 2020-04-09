<!--Nav-->
<nav id="header" class="bg-gray-800 w-full z-30 text-white relative">

    <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 py-2">

        <div class="flex items-center">
            <a class="toggleColour text-white no-underline hover:no-underline font-bold text-2xl lg:text-4xl"
                href="#">
                STREAMING
            </a>
        </div>

        <div class="block lg:hidden">
            <button id="nav-toggle" class="flex items-center p-1 text-orange-800 hover:text-gray-900">
                <svg class="fill-current h-6 w-6" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <title>Menu</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                </svg>
            </button>
        </div>

        <div class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block mt-2 lg:mt-0 bg-white lg:bg-transparent text-black p-4 lg:p-0 z-20"
            id="nav-content">
            <ul class="list-reset lg:flex justify-end flex-1 items-center">
                <li class="mr-3">
                    <a class="inline-block py-2 px-4 text-white font-bold no-underline" href="#">Active</a>
                </li>
                <li class="mr-3">
                    <a class="inline-block text-gray-100 no-underline hover:text-gray-800 hover:text-underline py-2 px-4"
                        href="#">link</a>
                </li>
            </ul>
        </div>
    </div>

    <hr class="border-b border-gray-100 opacity-25 my-0 py-0" />
</nav>
@if(!$event->is_live)
<section id="goLiveBar" class="bg-yellow-400 text-yellow-800 text-xs text-center p-2">
    @if(!$event->is_live)
    {{ @trans("ManageEvent.event_not_live") }}
    <a href="{{ route('MakeEventLive' , ['event_id' => $event->id]) }}"
        class="py-1 px-2 bg-green-300 text-green-600 text-xs rounded-lg">{{ @trans("ManageEvent.publish_it") }}</a>
    @endif
</section>
@endif