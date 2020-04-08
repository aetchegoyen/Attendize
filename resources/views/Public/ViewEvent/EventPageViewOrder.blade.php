@extends('Public.ViewEvent.Layouts.EventPage')

@section('content')

    @include('Public.ViewEvent.Partials.EventHeaderSection')

<!--Container-->
<div class="container md:px-0 mx-auto p-4 md:pb-8">

    <div class="mx-0">

        <div class="bg-gray-900 w-full text-gray-800 leading-normal rounded-t">

            <!-- Event Tickets Card-->
            <div class="flex rounded-lg shadow-lg flex flex-col md:flex-row items-center">
                @include('Public.ViewEvent.Partials.EventViewOrderSection')
            </div>
            <!--/Event Tickets  Card-->

            <!--Posts Container-->
            <div class="flex flex-wrap justify-between pt-6 -mx-6">

                <!--2/3 col -->
                <div class="w-full md:w-2/3 p-6 flex flex-col flex-grow flex-shrink">
                    @include('Public.ViewEvent.Partials.EventDescriptionSection')
                </div>

                <!--1/3 col -->
                <div class="w-full md:w-1/3 p-6 flex flex-col flex-grow flex-shrink">
                    @include('Public.ViewEvent.Partials.EventOrganiserSection')
                </div>

            </div>
            <!--/ Post Content-->

        </div>

    </div>


</div>

@include('Public.ViewEvent.Partials.EventFooterSection')
@stop
