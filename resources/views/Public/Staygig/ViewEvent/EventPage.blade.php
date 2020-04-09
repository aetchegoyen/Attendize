@extends('Public.Staygig.ViewEvent.Layouts.EventPage')

@section('head')
    @include('Public.Staygig.ViewEvent.Partials.GoogleTagManager')
@endsection

@section('content')

@include('Public.Staygig.ViewEvent.Partials.EventHeaderSection')

{{-- <div class="bg-poster z-0">
    <img src="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}">
</div> --}}
<!--Container-->
<div class="container md:px-0 mx-auto p-4 md:pb-8 md:pt-10 z-10 relative">

    <div class="mx-0">

        <div class="w-full text-gray-800 leading-normal rounded-t">

            <!-- Event Tickets Card-->
            <div class="flex rounded-lg shadow-lg flex flex-col md:flex-row items-center">
                @include('Public.Staygig.ViewEvent.Partials.EventTicketsSection')
            </div>
            <!--/Event Tickets  Card-->

            @include('Public.Staygig.ViewEvent.Partials.EventAccessSection')

            <!--Posts Container-->
            <div class="flex flex-wrap justify-between pt-6 -mx-6">

                <!--2/3 col -->
                <div class="w-full md:w-2/3 p-6 flex flex-col flex-grow flex-shrink">
                    @include('Public.Staygig.ViewEvent.Partials.EventDescriptionSection')
                </div>

                <!--1/3 col -->
                <div class="w-full md:w-1/3 p-6 flex flex-col flex-grow flex-shrink">
                    @include('Public.Staygig.ViewEvent.Partials.EventOrganiserSection')
                </div>

            </div>
            <!--/ Post Content-->

        </div>

    </div>


</div>

<script>
    var checkInRoute = "<?php echo $codeCheckInRoute; ?>";
    var _token = "<?php echo csrf_token() ?>";
</script>

@include('Public.Staygig.ViewEvent.Partials.EventFooterSection')
@stop