@if($event->start_date->isPast() && $event->end_date->isFuture())
    <div class="w-full p-4 shadow-lg rounded-lg bg-white mb-6">
        <div class="flex flex-grow text-lg flex-col w-full md:w-auto">
            <h2 class="title">@lang("Public_ViewEvent.event_online")</h2>
            <p class="text-gray-600" id="checkInMessages">@lang("Public_ViewEvent.enter_your_access_code")</p>
        </div>
        <div class="flex flex-col items-end">
            <div class="flex w-full md:w-50 my-2">
                <div class="w-full">
                {!! Form::label("check_in_email", trans("Public_ViewEvent.email"), array('class' => "block text-gray-600 mb-1")) !!}
                {!! 
                    Form::text("check_in_email", null, [
                        'id' => 'access_email',
                        'class' => 'form-control form-text w-full',
                        'placeholder' => 'john@doe.com'
                    ]) 
                !!}
                </div>
            </div>
            <div class="flex w-full md:w-44 my-2">
                <div class="w-full">
                {!! Form::label("check_in_email", trans("Public_ViewEvent.access_code"), array('class' => "block text-gray-600 mb-1")) !!}
                {!! 
                    Form::text("check_in_code", null, [
                        'id' => 'access_code',
                        'class' => 'form-control form-text w-full',
                        'placeholder' => 'XDA3339911'
                    ]) 
                !!}
                </div>
            </div>
            <div class="w-full items-end my-2">
                {!!Form::submit(trans("Public_ViewEvent.enter_event"), [
                    'class' => 'block bg-green-700 text-white font-bold py-2 px-4 rounded w-full h-12 cursor-pointer',
                    'id' => 'apply_check_in_code',
                ])!!}
            </div>
        </div>
    </div>

    @endif