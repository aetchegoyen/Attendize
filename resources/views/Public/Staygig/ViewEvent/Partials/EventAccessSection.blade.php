@if($event->start_date->isPast() && $event->end_date->isFuture())
<div class="event-access">
    <div class="flex-col flex md:flex-row items-end">
        <div class="flex flex-col w-full md:w-4/12 md:pr-4">
            <div class="w-full">
            {!! Form::label("check_in_email", trans("Public_ViewEvent.email"), array('class' => "block text-gray-600 mb-1")) !!}
            {!! 
                Form::email("check_in_email", null, [
                    'class' => 'form-control form-text w-full access_email',
                    'placeholder' => 'john@doe.com'
                ]) 
            !!}
            </div>
        </div>
        <div class="flex flex-col w-full md:w-4/12 pt-4 md:pt-0 md:pr-4">
            <div class="w-full">
                {!! Form::label("check_in_email", trans("Public_ViewEvent.access_code"), array('class' => "block text-gray-600 mb-1")) !!}
                {!! 
                    Form::text("check_in_code", null, [
                        'class' => 'form-control form-text w-full access_code',
                        'placeholder' => 'XDA3339911'
                    ]) 
                !!}
            </div>
        </div>
        <div class="items-end flex w-full md:w-4/12 flex-col mt-4 md:mt-0">
            {!!Form::submit(trans("Public_ViewEvent.enter_event"), [
                'class' => 'apply_check_in_code block bg-green-700 text-white font-bold py-2 px-4 rounded w-full h-12 cursor-pointer',
            ])!!}
        </div>
    </div>
    <div class="text-center pt-4 text-gray-600">
        <span id="checkInMessages" class="text-gray-600">@lang("Public_ViewEvent.enter_your_access_code")</span>
        ¿No encuentras tus entradas? Completa tu email y presiona 
        <a href="#" id="resend_tickets" class="text-blue-500">reenviar entradas</a>
    </div>
    
</div>


@endif