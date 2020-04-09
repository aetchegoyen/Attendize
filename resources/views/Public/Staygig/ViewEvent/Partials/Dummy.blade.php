<form class="online_payment ajax" action="<?php echo route('postCreateOrder', ['event_id' => $event->id]); ?>" method="post">
    <div class="online_payment text-base my-4">

        <div class="">
            {!! Form::label("card-number", trans("Public_ViewEvent.card_number"), array('class' => "block text-gray-600 mb-1")) !!}
            {!! Form::text("order_first_name", null, [
                'required' => 'required', 
                'autocomplete' => "off",
                'placeholder' => "**** **** **** ****",
                'size' => "20",
                'data' => "number",
                'class' => 'form-control form-text card-number']) !!}
        </div>

        <div class="flex flex-row mt-4">
            <div class="flex-grow">
                {!! Form::label('card-expiry-month', trans("Public_ViewEvent.expiry"), array('class' => "block text-gray-600 mb-1")) !!}
                <div class="flex-row flex items-center">
                    <div class="relative">
                        {!! Form::selectRange('card-expiry-month', 1, 12, null, [
                            'class' => 'form-control card-expiry-month',
                            'data' => 'exp_month'
                            ] ) !!}
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                    
                    <div class="mx-4">/</div>

                    <div class="relative">
                        {!! Form::selectRange('card-expiry-year',date('Y'),date('Y')+10,null, [
                        'class' => 'form-control card-expiry-year',
                        'data' => 'exp_year'
                        ] ) !!}
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="flex flex-col">
                {!! Form::label('card-expiry-year', trans("Public_ViewEvent.cvc_number"), array('class' => "block text-gray-600 mb-1")) !!}
                {!! Form::text("order_first_name", null, [
                    'required' => 'required', 
                    'autocomplete' => "off",
                    'placeholder' => "***",
                    'size' => "4",
                    'data' => "cvc",
                    'class' => 'form-control form-text card-cvc']) !!}
            </div>
        </div>

        

        {!! Form::token() !!}

        {!! Form::submit(trans("Public_ViewEvent.complete_payment"), ['class' => 'btn-block bg-green-700 text-white font-bold py-2 px-4 mt-6 rounded w-full']) !!}

    </div>
</form>

