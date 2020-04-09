<div class="rounded w-22 h-24 overflow-hidden bg-white text-center w-20 shadow-lg mr-4 mt-1 flex-shrink-0">
    <div class="bg-red-700 text-white py-1 text-sm">{{ explode("|", trans("basic.months_short"))[$event->start_date->format('n')] }}</div>
    <div class="border-l border-r">
        <span class="text-3xl font-bold">{{ $event->start_date->format('d') }}</span>
    </div>
    <div class="border-l border-r border-b-full rounded-b -mt-4">
        <span class="text-xs font-bold">{{ $event->start_date->format('h:i A') }}</span>
    </div>
</div>