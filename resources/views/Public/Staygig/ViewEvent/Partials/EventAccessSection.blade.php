@if($event->start_date->isPast() && $event->end_date->isFuture())
    <div class="w-full flex flex-col md:flex-row shadow-lg bg-white md:rounded-lg bg-white mt-8 bg-white items-center">
        <div class="p-4 flex flex-grow md:text-right text-lg flex-col">
            <h2 class="title">@lang("Public_ViewEvent.event_online")</h2>
            <p class="text-gray-600" id="checkInMessages">@lang("Public_ViewEvent.enter_your_access_code")</p>
        </div>
        <div class="flex p-4 w-50">
            <div>
            {!! Form::label("check_in_email", trans("Public_ViewEvent.email"), array('class' => "block text-gray-600 mb-1")) !!}
            {!! 
                Form::text("check_in_email", null, [
                    'id' => 'access_email',
                    'class' => 'form-control form-text',
                    'placeholder' => 'john@doe.com'
                ]) 
            !!}
            </div>
        </div>
        <div class="flex p-4 w-44">
            <div>
            {!! Form::label("check_in_email", trans("Public_ViewEvent.access_code"), array('class' => "block text-gray-600 mb-1")) !!}
            {!! 
                Form::text("check_in_code", null, [
                    'id' => 'access_code',
                    'class' => 'form-control form-text',
                    'placeholder' => 'XDA3339911'
                ]) 
            !!}
            </div>
        </div>
        <div class="flex md:w-4/12 p-4 flex-col">
            {!!Form::submit(trans("Public_ViewEvent.enter_event"), [
                'class' => 'block bg-green-700 text-white font-bold py-2 px-4 rounded w-full h-12',
                'id' => 'apply_check_in_code',
            ])!!}
        </div>
    </div>
    @endif