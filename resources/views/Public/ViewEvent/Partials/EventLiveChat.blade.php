<div class="-mx-6 -mt-6 bg-gray-300 px-4 py-2 shadow-lg flex flex-row flex-grow-0 items-center">
    <img src="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" class="w-auto h-10 rounded-md mr-4">
    <div class="flex flex-col flex-grow">
        <p class="text-base text-gray-600">{{$event->organiser->name}}</p>
        <h1 class="text-lg text-gray-600 font-bold">{{ $event->title }}</h1>
    </div>
</div>
<div class="flex overflow-y-scroll flex-grow relative">
    <ul id="messages" class="absolute top-0 bottom-0 chat-list flex flex-col" ></ul>
</div>
<form onsubmit="return sendMessage();" class="flex flex-grow-0 -mx-6 -mb-6 bg-gray-300 p-4 shadow-lg flex-row">
    {!! Form::text("message", null, [
            'id' => "chtMsg",
            'placeholder' => "Enter message",
            'autocomplete' => "off",
            'class' => 'flex flex-grow rounded-l-full px-4 py-2']) !!}
    {!! Form::hidden('chtName', $attendee->first_name."_".$attendee_key, [
            'id' => "chtName",
    ]) !!}
    {!! Form::hidden('chtKey', $attendee_key, [
            'id' => "chtKey",
    ]) !!}
    {!! Form::hidden('chtEid', $event->id, [
        'id' => "chtEid",
]) !!}
    <input type="submit" class="flex rounded-r-full px-4 py-2 cursor-pointer bg-gray-400 text-gray-600">
</form>