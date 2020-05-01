<div class="flex-1 bg-white rounded-lg overflow-hidden shadow-lg">
    <a href="#" class="flex flex-wrap no-underline hover:no-underline">
        <div class="p-4 w-full mb-4 text-center" style="background-color: {{$event->organiser->page_header_bg_color}};">
            <img src="{{URL::to($event->organiser->full_logo_path)}}" class="object-contain rounded-t mx-auto">
        </div>
        <p class="w-full text-gray-600 text-xs md:text-sm px-6">Organizado por</p>
        <div class="w-full font-bold text-xl text-gray-900 px-6">{{$event->organiser->name}}</div>
        <div class="text-gray-800 font-sans text-base px-6 mt-2 mb-5">
            {!! nl2br($event->organiser->about) !!}
        </div>
    </a>
</div>
{{-- <section id="organiser" class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="event_organiser_details" property="organizer" typeof="Organization">
                <div class="logo">
                    <img alt="{{$event->organiser->name}}" src="{{asset($event->organiser->full_logo_path)}}" property="logo">
                </div>
                    @if($event->organiser->enable_organiser_page)
                    <a href="{{route('showOrganiserHome', [$event->organiser->id, Str::slug($event->organiser->name)])}}" title="Organiser Page">
                        {{$event->organiser->name}}
                    </a>
                    @else
                        {{$event->organiser->name}}
                    @endif
                </h3>

                <p property="description">
                    {!! nl2br($event->organiser->about)!!}
                </p>
                <p>
                    @if($event->organiser->facebook)
                        <a property="sameAs" href="https://fb.com/{{$event->organiser->facebook}}" class="btn btn-facebook">
                            <i class="ico-facebook"></i>&nbsp; @lang("Public_ViewEvent.Facebook")
                        </a>
                    @endif
                        @if($event->organiser->twitter)
                            <a property="sameAs" href="https://twitter.com/{{$event->organiser->twitter}}" class="btn btn-twitter">
                                <i class="ico-twitter"></i>&nbsp; @lang("Public_ViewEvent.Twitter")
                            </a>
                        @endif
                    <button onclick="$(function(){ $('.contact_form').slideToggle(); });" type="button" class="btn btn-primary">
                        <i class="ico-envelop"></i>&nbsp; @lang("Public_ViewEvent.Contact")
                    </button>
                </p>
                <div class="contact_form well well-sm">
                    {!! Form::open(array('url' => route('postContactOrganiser', array('event_id' => $event->id)), 'class' => 'reset ajax')) !!}
                    <h3>@lang("Public_ViewEvent.Contact") <i>{{$event->organiser->name}}</i></h3>
                    <div class="form-group">
                        {!! Form::label(trans("Public_ViewEvent.your_name")) !!}
                        {!! Form::text('name', null,
                            array('required',
                                  'class'=>'form-control',
                                  'placeholder'=>trans("Public_ViewEvent.your_name"))) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label(trans("Public_ViewEvent.your_email_address")) !!}
                        {!! Form::text('email', null,
                            array('required',
                                  'class'=>'form-control',
                                  'placeholder'=>trans("Public_ViewEvent.your_email_address"))) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label(trans("Public_ViewEvent.your_message")) !!}
                        {!! Form::textarea('message', null,
                            array('required',
                                  'class'=>'form-control',
                                  'placeholder'=>trans("Public_ViewEvent.your_message"))) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::submit(trans("Public_ViewEvent.send_message_submit"),
                          array('class'=>'btn btn-primary')) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
 --}}
