@extends('Public.Staygig.ViewEvent.Layouts.EventPage')

@section('head')
    @include('Public.Staygig.ViewEvent.Partials.GoogleTagManager')
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/7.13.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.13.2/firebase-database.js"></script>

    <!-- TODO: Add SDKs for Firebase products that you want to use
        https://firebase.google.com/docs/web/setup#available-libraries -->
    <script src="https://www.gstatic.com/firebasejs/7.13.2/firebase-analytics.js"></script>

    <script>
        // Your web app's Firebase configuration
        var firebaseConfig = {
          apiKey: "AIzaSyBlCisNeYc1YjqxagPbWl1gC319YZZODFg",
          authDomain: "musictream.firebaseapp.com",
          databaseURL: "https://musictream.firebaseio.com",
          projectId: "musictream",
          storageBucket: "musictream.appspot.com",
          messagingSenderId: "835074870541",
          appId: "1:835074870541:web:ec4c7d1955da74c1c7f444",
          measurementId: "G-0762FB2157"
        };
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        firebase.analytics();
    </script>
@endsection

@section('content')

@include('Public.Staygig.ViewEvent.Partials.EventHeaderSection')

{{-- <div class="bg-poster z-0">
    <img src="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}">
</div> --}}

@if($attendee)
<div class="rounded-lg shadow-lg m-8 overflow-hidden flex md:flex-row relative">
    <div class="md:w-8/12 video-container flex bg-black relative">
        @include('Public.Staygig.ViewEvent.Partials.EventLiveVideo')
    </div>
    <div class="md:w-4/12 flex flex-col bg-gray-100 p-6">
        @include('Public.Staygig.ViewEvent.Partials.EventLiveChat')
    </div>
</div>
@else
    @include('Public.Staygig.ViewEvent.Partials.EventInvalidCodeSection')
@endif

<!--Container-->
<div class="container md:px-0 mx-auto px-4 md:pb-8 z-10 relative">

    <div class="mx-0">

        <div class="w-full text-gray-800 leading-normal rounded-t">

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

@include('Public.Staygig.ViewEvent.Partials.EventFooterSection')
@stop