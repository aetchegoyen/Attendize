<div class="rounded-md w-16 overflow-hidden bg-white text-center shadow-lg mr-4 -ml-2 flex-shrink-0 flex-grow-0">
    <div class="bg-red-700 font-bold text-white py-1 text-xs">{{ explode("|", trans("basic.months_short"))[$event->start_date->format('n')] }}</div>
    <div class="border-l border-r">
        <span class="text-2xl font-bold">{{ $event->start_date->format('d') }}</span>
    </div>
    <div class="border-l border-r border-b-full rounded-b -mt-2 text-gray-700">
        <span class="text-xs font-bold">{{ $event->start_date->format('h:i A') }}</span>
    </div>
</div>