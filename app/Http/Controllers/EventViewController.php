<?php

namespace App\Http\Controllers;

use Auth;
use Mail;
use Cookie;
use Validator;
use Carbon\Carbon;
use App\Jobs\SendAttendeeTicket;
use App\Models\Event;
use App\Attendize\Utils;
use App\Models\Attendee;
use App\Models\Affiliate;
use App\Models\EventStats;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EventAccessCodes;

class EventViewController extends Controller
{
    /**
     * Show the homepage for an event
     *
     * @param Request $request
     * @param $event_id
     * @param string $slug
     * @param bool $preview
     * @return mixed
     */
    public function showEventHome(Request $request, $event_id, $slug = '', $preview = false)
    {
        $event = Event::findOrFail($event_id);

        if (!Utils::userOwns($event) && !$event->is_live) {
            return view(config('attendize.public_template_base').'ViewEvent.EventNotLivePage');
        }

        $data = [
            'event' => $event,
            'tickets' => $event->tickets()->orderBy('sort_order', 'asc')->get(),
            'is_embedded' => 0,
            'codeCheckInRoute' => route('postCheckInAttendeeCode', ['event_id' => $event->id]),
            'resendRoute' => route('postResendTicketToAttendeePublic', ['event_id' => $event->id])
        ];
        /*
         * Don't record stats if we're previewing the event page from the backend or if we own the event.
         */
        if (!$preview && !Auth::check()) {
            $event_stats = new EventStats();
            $event_stats->updateViewCount($event_id);
        }

        /*
         * See if there is an affiliate referral in the URL
         */
        if ($affiliate_ref = $request->get('ref')) {
            $affiliate_ref = preg_replace("/\W|_/", '', $affiliate_ref);

            if ($affiliate_ref) {
                $affiliate = Affiliate::firstOrNew([
                    'name'       => $request->get('ref'),
                    'event_id'   => $event_id,
                    'account_id' => $event->account_id,
                ]);

                ++$affiliate->visits;

                $affiliate->save();

                Cookie::queue('affiliate_' . $event_id, $affiliate_ref, 60 * 24 * 60);
            }
        }

        return view(config('attendize.public_template_base').'ViewEvent.EventPage', $data);
    }

    /**
     * Show the homepage for an event
     *
     * @param Request $request
     * @param $event_id
     * @param string $slug
     * @param bool $preview
     * @return mixed
     */
    public function showLiveEventHome(Request $request, $event_id, $slug = '', $reference='')
    {
        $event = Event::findOrFail($event_id);
        $code = base64_decode($reference);
        $code = preg_replace('/[^A-Za-z0-9\-]/', '', $code);
        //var_dump($code); die;

        $attendee = Attendee::withoutCancelled()
        ->join('tickets', 'tickets.id', '=', 'attendees.ticket_id')
        ->where(function ($query) use ($event, $code) {
            $query->where('attendees.event_id', $event->id)
                ->where('attendees.private_reference_number', $code);
        })->select([
            'attendees.id',
            'attendees.order_id',
            'attendees.first_name',
            'attendees.last_name',
            'attendees.email',
            'attendees.reference_index',
            'attendees.arrival_time',
            'attendees.has_arrived',
            'tickets.title as ticket',
        ])->first();


        // var_dump($attendee); die;
        if (!Utils::userOwns($event) && !$event->is_live) {
            $attendee = false;
        }

        $attendee_key = false;

        if($attendee){
            $attendee_key = substr(md5($attendee->id),0,5);
        }

        $data = [
            'event' => $event,
            'attendee' => $attendee,
            'attendee_key' => $attendee_key,
            'tickets' => $event->tickets()->orderBy('sort_order', 'asc')->get(),
            'is_embedded' => 0,
            'codeCheckInRoute' => route('postCheckInAttendeeCode', ['event_id' => $event->id]),
            'resendRoute' => route('postResendTicketToAttendeePublic', ['event_id' => $event->id])
        ];

        return view(config('attendize.public_template_base').'ViewEvent.EventLivePage', $data);
    }


    /**
     * Show preview of event homepage / used for backend previewing
     *
     * @param $event_id
     * @return mixed
     */
    public function showEventHomePreview($event_id)
    {
        return showEventHome($event_id, true);
    }

    /**
     * Sends a message to the organiser
     *
     * @param Request $request
     * @param $event_id
     * @return mixed
     */
    public function postContactOrganiser(Request $request, $event_id)
    {
        $rules = [
            'name'    => 'required',
            'email'   => ['required', 'email'],
            'message' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        $event = Event::findOrFail($event_id);

        $data = [
            'sender_name'     => $request->get('name'),
            'sender_email'    => $request->get('email'),
            'message_content' => strip_tags($request->get('message')),
            'event'           => $event,
        ];

        Mail::send('Emails.messageReceived', $data, function ($message) use ($event, $data) {
            $message->to($event->organiser->email, $event->organiser->name)
                ->from(config('attendize.outgoing_email_noreply'), $data['sender_name'])
                ->replyTo($data['sender_email'], $data['sender_name'])
                ->subject(trans("Email.message_regarding_event", ["event"=>$event->title]));
        });

        return response()->json([
            'status'  => 'success',
            'message' => trans("Controllers.message_successfully_sent"),
        ]);
    }

    public function showCalendarIcs(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);

        $icsContent = $event->getIcsForEvent();

        return response()->make($icsContent, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="event.ics'
        ]);
    }

    /**
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postShowHiddenTickets(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);

        $accessCode = strtoupper(strip_tags($request->get('access_code')));
        if (!$accessCode) {
            return response()->json([
                'status' => 'error',
                'message' => trans('AccessCodes.valid_code_required'),
            ]);
        }

        $unlockedHiddenTickets = $event->tickets()
            ->where('is_hidden', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->filter(function($ticket) use ($accessCode) {
                // Only return the hidden tickets that match the access code
                return ($ticket->event_access_codes()->where('code', $accessCode)->get()->count() > 0);
            });

        if ($unlockedHiddenTickets->count() === 0) {
            return response()->json([
                'status' => 'error',
                'message' => trans('AccessCodes.no_tickets_matched'),
            ]);
        }

        // Bump usage count
        EventAccessCodes::logUsage($event_id, $accessCode);

        return view(config('attendize.public_template_base').'ViewEvent.Partials.EventHiddenTicketsSelection', [
            'event' => $event,
            'tickets' => $unlockedHiddenTickets,
            'is_embedded' => 0,
        ]);
    }

    /**
     * Check in an attendee
     *
     * @param $event_id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCheckInAttendeeCode($event_id, Request $request)
    {
        $event = Event::scope()->findOrFail($event_id);

        $code = $request->get('access_code');
        $email = $request->get('email');
        $attendee = Attendee::scope()->withoutCancelled()
            ->join('tickets', 'tickets.id', '=', 'attendees.ticket_id')
            ->where(function ($query) use ($event, $code, $email) {
                $query->where('attendees.event_id', $event->id)
                    ->where('attendees.private_reference_number', $code)
                    ->where('attendees.email', $email);
            })->select([
                'attendees.id',
                'attendees.order_id',
                'attendees.first_name',
                'attendees.last_name',
                'attendees.email',
                'attendees.reference_index',
                'attendees.arrival_time',
                'attendees.has_arrived',
                'tickets.title as ticket',
            ])->first();

        if (is_null($attendee)) {
            return response()->json([
                'status'  => 'error',
                'message' => trans("Controllers.invalid_ticket_error")
            ]);
        }

        $relatedAttendesCount = Attendee::where('id', '!=', $attendee->id)
            ->where([
                'order_id'    => $attendee->order_id,
                'has_arrived' => false
            ])->count();

        if ($attendee->has_arrived && false) {
            return response()->json([
                'status'  => 'error',
                'message' => trans("Controllers.attendee_already_checked_in", ["time"=> $attendee->arrival_time->format(config("attendize.default_datetime_format"))])
            ]);
        }

        Attendee::find($attendee->id)->update(['has_arrived' => true, 'arrival_time' => Carbon::now()]);

        return response()->json([
            'status'  => 'success',
            'name' => $attendee->first_name." ".$attendee->last_name,
            'reference' => $attendee->reference,
            'ticket' => $attendee->ticket,
            'dest' => route('showLiveEventPage', [ 
                'event_id' => $event_id, 
                'reference' => base64_encode($code), 
                'event_slug' => Str::slug($event->title) 
                ]
            )
        ]);
    }

    /**
     * Send a message to an attendee
     *
     * @param Request $request
     * @param $attendee_id
     * @return mixed
     */
    public function postResendTicketToAttendee($event_id, Request $request)
    {
        $email = $request->get('email');
        $attendee = Attendee::where(array(
            "email" => $email,
            "event_id" => $event_id
        ))->first();

        if($attendee){
            $this->dispatch(new SendAttendeeTicket($attendee));
            return response()->json([
                'status'  => 'success',
                'message' => trans("Controllers.ticket_successfully_resent"),
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => trans("Controllers.ticket_successfully_resent"),
        ]);
    }
}
