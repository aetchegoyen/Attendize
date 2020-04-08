<?php

namespace App\Http\Controllers;

use App\Attendize\Repositories\AttendeeRepository;
use App\Attendize\Repositories\EventRepository;
use App\Attendize\Requests\Attendee\ExportAttendeesRequest;
use App\Attendize\Requests\Attendee\ImportAttendeeRequest;
use App\Attendize\Requests\Attendee\InviteAttendeeRequest;
use App\Attendize\Requests\Attendee\MessageAttendeeRequest;
use App\Attendize\Requests\Attendee\MessageMultipleAttendeeRequest;
use App\Attendize\Services\Attendee\ExportAttendeesService;
use App\Attendize\Services\Attendee\ImportAttendeeService;
use App\Attendize\Services\Attendee\InviteAttendeeService;
use App\Attendize\Services\Attendee\MessageAttendeeService;
use App\Attendize\Services\Attendee\MessageMultipleAttendeeService;
use App\Jobs\GenerateTicket;
use App\Jobs\SendAttendeeTicket;
use App\Models\Attendee;
use App\Models\EventStats;
<<<<<<< HEAD
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
=======
use App\Models\Message;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Order as OrderService;
use App\Models\Ticket;
use Auth;
use Config;
use DB;
use Excel;
>>>>>>> master
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Omnipay\Omnipay;
use Illuminate\Validation\Validator;
use PDF;


class EventAttendeesController extends MyBaseController
{
    protected $attendeeRepository;
    protected $eventRepository;
    protected $attendeeService;

    public function __construct(
        AttendeeRepository $attendeeRepository,
        EventRepository $eventRepository
    ) {
        $this->attendeeRepository = $attendeeRepository;
        $this->eventRepository = $eventRepository;

        parent::__construct();
    }

    /**
     * Show the attendees list
     *
     * @param Request $request
     * @param int $event_id
     * @return View
     */
    public function showAttendees(Request $request, $event_id)
    {
        $allowed_sorts = ['first_name', 'email', 'ticket_id', 'order_reference'];

        $searchQuery = $request->get('q');
        $sort_order = $request->get('sort_order') == 'asc' ? 'asc' : 'desc';
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'created_at');

        $event = $this->eventRepository->find($event_id);

        if ($searchQuery) {
            $attendees = $this->eventRepository->getAttendeesByTerm($event, $searchQuery, $sort_by, $sort_order);
        } else {
            $attendees = $this->eventRepository->getAttendees($event, $sort_by, $sort_order);
        }

        $data = [
            'attendees' => $attendees,
            'event' => $event,
            'sort_by' => $sort_by,
            'sort_order' => $sort_order,
            'q' => $searchQuery ? $searchQuery : '',
        ];

        return view('ManageEvent.Attendees', $data);
    }

    /**
     * Show the 'Invite Attendee' modal
     *
     * @param Request $request
     * @param int $eventId
     * @return string|View
     */
    public function showInviteAttendee(Request $request, $eventId)
    {
        $event = $this->eventRepository->find($eventId);

        if ($event->tickets->count() === 0) {
            return '<script>showMessage("'.trans("Controllers.addInviteError").'");</script>';
        }

        return view('ManageEvent.Modals.InviteAttendee', [
<<<<<<< HEAD
            'event' => $event,
=======
            'event'   => $event,
>>>>>>> master
            'tickets' => $event->tickets()->pluck('title', 'id'),
        ]);
    }

    /**
     * @param InviteAttendeeRequest $request
     * @param InviteAttendeeService $inviteAttendeeService
     * @param int $eventId
     * @return \Illuminate\Http\JsonResponse
     */
<<<<<<< HEAD
    public function postInviteAttendee(
        InviteAttendeeRequest $request,
        InviteAttendeeService $inviteAttendeeService,
        $eventId
    ) {
        if ($inviteAttendeeService->handle($request)) {
            session()->flash('message', __('Attendee Successfully Invited'));
=======
    public function postInviteAttendee(Request $request, $event_id)
    {
        $rules = [
            'first_name' => 'required',
            'ticket_id'  => 'required|exists:tickets,id,account_id,' . \Auth::user()->account_id,
            'email'      => 'email|required',
        ];

        $messages = [
            'ticket_id.exists'   => trans("Controllers.ticket_not_exists_error"),
            'ticket_id.required' => trans("Controllers.ticket_field_required_error"),
        ];
>>>>>>> master

            return response()->json([
<<<<<<< HEAD
                'status' => self::RESPONSE_SUCCESS,
=======
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        $ticket_id = $request->get('ticket_id');
        $event = Event::findOrFail($event_id);
        $ticket_price = 0;
        $attendee_first_name = strip_tags($request->get('first_name'));
        $attendee_last_name = strip_tags($request->get('last_name'));
        $attendee_email = $request->get('email');
        $email_attendee = $request->get('email_ticket');

        DB::beginTransaction();

        try {

            /*
             * Create the order
             */
            $order = new Order();
            $order->first_name = $attendee_first_name;
            $order->last_name = $attendee_last_name;
            $order->email = $attendee_email;
            $order->order_status_id = config('attendize.order_complete');
            $order->amount = $ticket_price;
            $order->account_id = Auth::user()->account_id;
            $order->event_id = $event_id;

            // Calculating grand total including tax
            $orderService = new OrderService($ticket_price, 0, $event);
            $orderService->calculateFinalCosts();
            $order->taxamt = $orderService->getTaxAmount();

            if ($orderService->getGrandTotal() == 0) {
                $order->is_payment_received = 1;
            }

            $order->save();

            /*
             * Update qty sold
             */
            $ticket = Ticket::scope()->find($ticket_id);
            $ticket->increment('quantity_sold');
            $ticket->increment('sales_volume', $ticket_price);
            $ticket->event->increment('sales_volume', $ticket_price);

            /*
             * Insert order item
             */
            $orderItem = new OrderItem();
            $orderItem->title = $ticket->title;
            $orderItem->quantity = 1;
            $orderItem->order_id = $order->id;
            $orderItem->unit_price = $ticket_price;
            $orderItem->save();

            /*
             * Update the event stats
             */
            $event_stats = new EventStats();
            $event_stats->updateTicketsSoldCount($event_id, 1);
            $event_stats->updateTicketRevenue($ticket_id, $ticket_price);

            /*
             * Create the attendee
             */
            $attendee = new Attendee();
            $attendee->first_name = $attendee_first_name;
            $attendee->last_name = $attendee_last_name;
            $attendee->email = $attendee_email;
            $attendee->event_id = $event_id;
            $attendee->order_id = $order->id;
            $attendee->ticket_id = $ticket_id;
            $attendee->account_id = Auth::user()->account_id;
            $attendee->reference_index = 1;
            $attendee->save();


            if ($email_attendee == '1') {
                $this->dispatch(new SendAttendeeInvite($attendee));
            }

            session()->flash('message', trans("Controllers.attendee_successfully_invited"));

            DB::commit();

            return response()->json([
                'status'      => 'success',
>>>>>>> master
                'redirectUrl' => route('showEventAttendees', [
                    'event_id' => $eventId,
                ]),
            ]);
<<<<<<< HEAD
=======

        } catch (Exception $e) {

            Log::error($e);
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'error'  => trans("Controllers.attendee_exception")
            ]);
>>>>>>> master
        }

        return response()->json([
            'status' => self::RESPONSE_ERROR,
            'message' => __('There was an error inviting this attendee. Please try again')
        ]);
    }

    /**
     * @param int $event_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function showImportAttendee($event_id)
    {
        $event = $this->eventRepository->find($event_id);

        if ($event->tickets->count() === 0) {
            return '<script>showMessage("'.trans("Controllers.addInviteError").'");</script>';
        }

        return view('ManageEvent.Modals.ImportAttendee', [
<<<<<<< HEAD
            'event' => $event,
=======
            'event'   => $event,
>>>>>>> master
            'tickets' => $event->tickets()->pluck('title', 'id'),
        ]);
    }

    /**
     * Imports attendees from CSV file
     *
     * @param ImportAttendeeRequest $request
     * @param ImportAttendeeService $importAttendeeService
     * @param int $eventId
     * @return \Illuminate\Http\JsonResponse
     */
    public function postImportAttendee(
        ImportAttendeeRequest $request,
        ImportAttendeeService $importAttendeeService,
        $eventId
    )
    {
<<<<<<< HEAD
        if ($importAttendeeService->handle($request)) {
            session()->flash('message', __('Attendees Successfully Invited'));
=======
        $rules = [
            'ticket_id'      => 'required|exists:tickets,id,account_id,' . \Auth::user()->account_id,
            'attendees_list' => 'required|mimes:csv,txt|max:5000|',
        ];

        $messages = [
            'ticket_id.exists' => trans("Controllers.ticket_not_exists_error"),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
>>>>>>> master
            return response()->json([
                'status' => self::RESPONSE_SUCCESS,
                'redirectUrl' => route('showEventAttendees', [
                    'event_id' => $eventId,
                ]),
            ]);
        }

<<<<<<< HEAD
=======
        $ticket_id = $request->get('ticket_id');
        $event = Event::findOrFail($event_id);
        $ticket_price = 0;
        $email_attendee = $request->get('email_ticket');
        $num_added = 0;
        if ($request->file('attendees_list')) {

            $the_file = Excel::load($request->file('attendees_list')->getRealPath(), function ($reader) {
            })->get();

            // Loop through
            foreach ($the_file as $rows) {
                if (!empty($rows['first_name']) && !empty($rows['last_name']) && !empty($rows['email'])) {
                    $num_added++;
                    $attendee_first_name = strip_tags($rows['first_name']);
                    $attendee_last_name = strip_tags($rows['last_name']);
                    $attendee_email = $rows['email'];

                    error_log($ticket_id . ' ' . $ticket_price . ' ' . $email_attendee);


                    /**
                     * Create the order
                     */
                    $order = new Order();
                    $order->first_name = $attendee_first_name;
                    $order->last_name = $attendee_last_name;
                    $order->email = $attendee_email;
                    $order->order_status_id = config('attendize.order_complete');
                    $order->amount = $ticket_price;
                    $order->account_id = Auth::user()->account_id;
                    $order->event_id = $event_id;

                    // Calculating grand total including tax
                    $orderService = new OrderService($ticket_price, 0, $event);
                    $orderService->calculateFinalCosts();
                    $order->taxamt = $orderService->getTaxAmount();

                    if ($orderService->getGrandTotal() == 0) {
                        $order->is_payment_received = 1;
                    }

                    $order->save();

                    /**
                     * Update qty sold
                     */
                    $ticket = Ticket::scope()->find($ticket_id);
                    $ticket->increment('quantity_sold');
                    $ticket->increment('sales_volume', $ticket_price);
                    $ticket->event->increment('sales_volume', $ticket_price);

                    /**
                     * Insert order item
                     */
                    $orderItem = new OrderItem();
                    $orderItem->title = $ticket->title;
                    $orderItem->quantity = 1;
                    $orderItem->order_id = $order->id;
                    $orderItem->unit_price = $ticket_price;
                    $orderItem->save();

                    /**
                     * Update the event stats
                     */
                    $event_stats = new EventStats();
                    $event_stats->updateTicketsSoldCount($event_id, 1);
                    $event_stats->updateTicketRevenue($ticket_id, $ticket_price);

                    /**
                     * Create the attendee
                     */
                    $attendee = new Attendee();
                    $attendee->first_name = $attendee_first_name;
                    $attendee->last_name = $attendee_last_name;
                    $attendee->email = $attendee_email;
                    $attendee->event_id = $event_id;
                    $attendee->order_id = $order->id;
                    $attendee->ticket_id = $ticket_id;
                    $attendee->account_id = Auth::user()->account_id;
                    $attendee->reference_index = 1;
                    $attendee->save();

                    if ($email_attendee == '1') {
                        $this->dispatch(new SendAttendeeInvite($attendee));
                    }
                }
            };
        }

        session()->flash('message', $num_added . ' Attendees Successfully Invited');

>>>>>>> master
        return response()->json([
            'status' => self::RESPONSE_ERROR,
            'message' => __('There was an error importing these attendees. Please try again')
        ]);
    }

    /**
     * @param int $eventId
     * @return \Illuminate\View\View
     */
    public function showPrintAttendees($eventId)
    {
        $data['event'] = $this->eventRepository->find($eventId);
        $data['attendees'] = $data['event']
            ->attendees()
            ->withoutCancelled()
            ->orderBy('first_name')
            ->get();

        return view('ManageEvent.PrintAttendees', $data);
    }

    /**
     * @param int $eventId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showMessageAttendee($eventId)
    {
        $attendee = $this->attendeeRepository->find($eventId);

        $data = [
            'attendee' => $attendee,
            'event' => $attendee->event,
        ];

        return view('ManageEvent.Modals.MessageAttendee', $data);
    }

    /**
     * Send message to attendee
     *
     * @param MessageAttendeeRequest $request
     * @param MessageAttendeeService $messageAttendeeService
     * @param int $attendeeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function postMessageAttendee(
        MessageAttendeeRequest $request,
        MessageAttendeeService $messageAttendeeService,
        $attendeeId
    ) {
        if ($messageAttendeeService->handle($request, $attendeeId)) {
            return response()->json([
                'status' => self::RESPONSE_SUCCESS,
                'message' => __('Message Successfully Sent'),
            ]);
        }

<<<<<<< HEAD
        return response()->json([
            'status' => self::RESPONSE_ERROR,
            'message' => __('Message failed to send'),
=======
        $attendee = Attendee::scope()->findOrFail($attendee_id);

        $data = [
            'attendee'        => $attendee,
            'message_content' => $request->get('message'),
            'subject'         => $request->get('subject'),
            'event'           => $attendee->event,
            'email_logo'      => $attendee->event->organiser->full_logo_path,
        ];

        //@todo move this to the SendAttendeeMessage Job
        Mail::send('Emails.messageReceived', $data, function ($message) use ($attendee, $data) {
            $message->to($attendee->email, $attendee->full_name)
                ->from(config('attendize.outgoing_email_noreply'), $attendee->event->organiser->name)
                ->replyTo($attendee->event->organiser->email, $attendee->event->organiser->name)
                ->subject($data['subject']);
        });

        /* Could bcc in the above? */
        if ($request->get('send_copy') == '1') {
            Mail::send('Emails.messageReceived', $data, function ($message) use ($attendee, $data) {
                $message->to($attendee->event->organiser->email, $attendee->event->organiser->name)
                    ->from(config('attendize.outgoing_email_noreply'), $attendee->event->organiser->name)
                    ->replyTo($attendee->event->organiser->email, $attendee->event->organiser->name)
                    ->subject($data['subject'] . trans("Email.organiser_copy"));
            });
        }

        return response()->json([
            'status'  => 'success',
            'message' => trans("Controllers.message_successfully_sent"),
>>>>>>> master
        ]);
    }

    /***
     * @param int $eventId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showMessageAttendees($eventId)
    {
        $data = [
<<<<<<< HEAD
            'event' => $this->eventRepository->find($eventId),
            'tickets' => $this->eventRepository->find($eventId)->tickets()->pluck('title', 'id')->toArray(),
=======
            'event'   => Event::scope()->find($event_id),
            'tickets' => Event::scope()->find($event_id)->tickets()->pluck('title', 'id')->toArray(),
>>>>>>> master
        ];

        return view('ManageEvent.Modals.MessageAttendees', $data);
    }


    /***
     * @param MessageMultipleAttendeeRequest $request
     * @param MessageMultipleAttendeeService $messageMultipleAttendeeService
     * @param int $eventId
     * @return \Illuminate\Http\JsonResponse
     */
    public function postMessageAttendees(
        MessageMultipleAttendeeRequest $request,
        MessageMultipleAttendeeService $messageMultipleAttendeeService,
        $eventId
    ) {
        $messageMultipleAttendeeService->handle($request, $eventId);

        return response()->json([
            'status' => self::RESPONSE_SUCCESS,
            'message' => __('There was an error sending the message'),
        ]);
    }

    /**
<<<<<<< HEAD
     * Download a ticket as PDF
     *
     * @param $eventId
     * @param $attendeeId
=======
     * @param $event_id
     * @param $attendee_id
>>>>>>> master
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function showDownloadTicketAsPDF($eventId, $attendeeId)
    {
        $attendee = $this->attendeeRepository->find($attendeeId);

        $this->dispatch(
            new GenerateTicket($attendee->order->order_reference . "-" . $attendee->reference_index)
        );

        $pdfFileName = $attendee->order->order_reference . '-' . $attendee->reference_index;
        $pdfFilePath = public_path(config('attendize.event_pdf_tickets_path')) . '/' . $pdfFileName;
        $pdfFile = $pdfFilePath . '.pdf';

        return response()->download($pdfFile);
    }

    /**
     * Downloads an export of attendees
     *
     * @param $eventId
     * @param string $exportAs (xlsx, xls, csv, html)
     */
    public function showExportAttendees(
        ExportAttendeesRequest $exportAttendeesRequest,
        ExportAttendeesService $exportAttendeesService,
        $eventId,
        $exportAs = ExportAttendeesService::DEFAULT_EXPORT_FILE_TYPE)
    {
<<<<<<< HEAD
        $exportAttendeesService->handle($exportAttendeesRequest);
=======

        Excel::create('attendees-as-of-' . date('d-m-Y-g.i.a'), function ($excel) use ($event_id) {

            $excel->setTitle('Attendees List');

            // Chain the setters
            $excel->setCreator(config('attendize.app_name'))
                ->setCompany(config('attendize.app_name'));

            $excel->sheet('attendees_sheet_1', function ($sheet) use ($event_id) {
                DB::connection();
                $data = DB::table('attendees')
                    ->where('attendees.event_id', '=', $event_id)
                    ->where('attendees.is_cancelled', '=', 0)
                    ->where('attendees.account_id', '=', Auth::user()->account_id)
                    ->join('events', 'events.id', '=', 'attendees.event_id')
                    ->join('orders', 'orders.id', '=', 'attendees.order_id')
                    ->join('tickets', 'tickets.id', '=', 'attendees.ticket_id')
                    ->select([
                        'attendees.first_name',
                        'attendees.last_name',
                        'attendees.email',
			'attendees.private_reference_number',
                        'orders.order_reference',
                        'tickets.title',
                        'orders.created_at',
                        DB::raw("(CASE WHEN attendees.has_arrived THEN 'YES' ELSE 'NO' END) AS has_arrived"),
                        'attendees.arrival_time',
                    ])->get();

                $data = array_map(function($object) {
                    return (array)$object;
                }, $data->toArray());

                $sheet->fromArray($data);
                $sheet->row(1, [
                    'First Name',
                    'Last Name',
                    'Email',
		    'Ticket ID',
                    'Order Reference',
                    'Ticket Type',
                    'Purchase Date',
                    'Has Arrived',
                    'Arrival Time',
                ]);

                // Set gray background on first row
                $sheet->row(1, function ($row) {
                    $row->setBackground('#f5f5f5');
                });
            });
        })->export($export_as);
>>>>>>> master
    }

    /**
     * Show the 'Edit Attendee' modal
     *
     * @param Request $request
     * @param $event_id
     * @param $attendee_id
     * @return View
     */
    public function showEditAttendee(Request $request, $event_id, $attendee_id)
    {
        $attendee = Attendee::scope()->findOrFail($attendee_id);

        $data = [
            'attendee' => $attendee,
<<<<<<< HEAD
            'event' => $attendee->event,
            'tickets' => $attendee->event->tickets->pluck('title', 'id'),
=======
            'event'    => $attendee->event,
            'tickets'  => $attendee->event->tickets->pluck('title', 'id'),
>>>>>>> master
        ];

        return view('ManageEvent.Modals.EditAttendee', $data);
    }

    /**
     * Updates an attendee
     *
     * @param Request $request
     * @param $event_id
     * @param $attendee_id
     * @return mixed
     */
    public function postEditAttendee(Request $request, $event_id, $attendee_id)
    {
        $rules = [
            'first_name' => 'required',
            'ticket_id' => 'required|exists:tickets,id,account_id,' . Auth::user()->account_id,
            'email' => 'required|email',
        ];

        $messages = [
<<<<<<< HEAD
            'ticket_id.exists' => 'The ticket you have selected does not exist',
            'ticket_id.required' => 'The ticket field is required. ',
=======
            'ticket_id.exists'   => trans("Controllers.ticket_not_exists_error"),
            'ticket_id.required' => trans("Controllers.ticket_field_required_error"),
>>>>>>> master
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        $attendee = Attendee::scope()->findOrFail($attendee_id);
        $attendee->update($request->all());

        session()->flash('message',trans("Controllers.successfully_updated_attendee"));

        return response()->json([
            'status' => 'success',
            'id' => $attendee->id,
            'redirectUrl' => '',
        ]);
    }

    /**
     * Shows the 'Cancel Attendee' modal
     *
     * @param Request $request
     * @param $event_id
     * @param $attendee_id
     * @return View
     */
    public function showCancelAttendee(Request $request, $event_id, $attendee_id)
    {
        $attendee = Attendee::scope()->findOrFail($attendee_id);

        $data = [
            'attendee' => $attendee,
<<<<<<< HEAD
            'event' => $attendee->event,
            'tickets' => $attendee->event->tickets->pluck('title', 'id'),
=======
            'event'    => $attendee->event,
            'tickets'  => $attendee->event->tickets->pluck('title', 'id'),
>>>>>>> master
        ];

        return view('ManageEvent.Modals.CancelAttendee', $data);
    }

    /**
     * Cancels an attendee
     *
     * @param Request $request
     * @param $event_id
     * @param $attendee_id
     * @return mixed
     */
    public function postCancelAttendee(Request $request, $event_id, $attendee_id)
    {
        $attendee = Attendee::scope()->findOrFail($attendee_id);
        $error_message = false; //Prevent "variable doesn't exist" error message

        if ($attendee->is_cancelled) {
            return response()->json([
<<<<<<< HEAD
                'status' => 'success',
                'message' => 'Attendee Already Cancelled',
=======
                'status'  => 'success',
                'message' => trans("Controllers.attendee_already_cancelled"),
>>>>>>> master
            ]);
        }

        $attendee->ticket->decrement('quantity_sold');
        $attendee->ticket->decrement('sales_volume', $attendee->ticket->price);
        $attendee->ticket->event->decrement('sales_volume', $attendee->ticket->price);
        $attendee->is_cancelled = 1;
        $attendee->save();

        $eventStats = EventStats::where('event_id', $attendee->event_id)->where('date',
            $attendee->created_at->format('Y-m-d'))->first();
        if ($eventStats) {
            $eventStats->decrement('tickets_sold', 1);
            $eventStats->decrement('sales_volume', $attendee->ticket->price);
        }

        $data = [
            'attendee' => $attendee,
            'email_logo' => $attendee->event->organiser->full_logo_path,
        ];

        if ($request->get('notify_attendee') == '1') {
            Mail::send('Emails.notifyCancelledAttendee', $data, function ($message) use ($attendee) {
                $message->to($attendee->email, $attendee->full_name)
                    ->from(config('attendize.outgoing_email_noreply'), $attendee->event->organiser->name)
                    ->replyTo($attendee->event->organiser->email, $attendee->event->organiser->name)
                    ->subject(trans("Email.your_ticket_cancelled"));
            });
        }

        if ($request->get('refund_attendee') == '1') {

            try {
                // This does not account for an increased/decreased ticket price
                // after the original purchase.
                $refund_amount = $attendee->ticket->price;
                $data['refund_amount'] = $refund_amount;

                $gateway = Omnipay::create($attendee->order->payment_gateway->name);

                // Only works for stripe
                $gateway->initialize($attendee->order->account->getGateway($attendee->order->payment_gateway->id)->config);

                $request = $gateway->refund([
                    'transactionReference' => $attendee->order->transaction_id,
                    'amount' => $refund_amount,
                    'refundApplicationFee' => false,
                ]);

                $response = $request->send();

                if ($response->isSuccessful()) {

                    // Update the attendee and their order
                    $attendee->is_refunded = 1;
                    $attendee->order->is_partially_refunded = 1;
                    $attendee->order->amount_refunded += $refund_amount;

                    $attendee->order->save();
                    $attendee->save();

                    // Let the user know that they have received a refund.
                    Mail::send('Emails.notifyRefundedAttendee', $data, function ($message) use ($attendee) {
                        $message->to($attendee->email, $attendee->full_name)
                            ->from(config('attendize.outgoing_email_noreply'), $attendee->event->organiser->name)
                            ->replyTo($attendee->event->organiser->email, $attendee->event->organiser->name)
                            ->subject(trans("Email.refund_from_name", ["name"=>$attendee->event->organiser->name]));
                    });
                } else {
                    $error_message = $response->getMessage();
                }

            } catch (\Exception $e) {
<<<<<<< HEAD
                Log::error($e);
                $error_message = 'There has been a problem processing your refund. Please check your information and try again.';
=======
                \Log::error($e);
                $error_message = trans("Controllers.refund_exception");
>>>>>>> master

            }
        }

        if ($error_message) {
            return response()->json([
                'status' => 'error',
                'message' => $error_message,
            ]);
        }

        session()->flash('message', trans("Controllers.successfully_cancelled_attendee"));

        return response()->json([
            'status' => 'success',
            'id' => $attendee->id,
            'redirectUrl' => '',
        ]);
    }

    /**
     * Show the 'Message Attendee' modal
     *
     * @param Request $request
     * @param $attendee_id
     * @return View
     */
    public function showResendTicketToAttendee(Request $request, $attendee_id)
    {
        $attendee = Attendee::scope()->findOrFail($attendee_id);

        $data = [
            'attendee' => $attendee,
            'event' => $attendee->event,
        ];

        return view('ManageEvent.Modals.ResendTicketToAttendee', $data);
    }

    /**
     * Send a message to an attendee
     *
     * @param Request $request
     * @param $attendee_id
     * @return mixed
     */
    public function postResendTicketToAttendee(Request $request, $attendee_id)
    {
        $attendee = Attendee::scope()->findOrFail($attendee_id);

        $this->dispatch(new SendAttendeeTicket($attendee));

        return response()->json([
<<<<<<< HEAD
            'status' => 'success',
            'message' => 'Ticket Successfully Resent',
=======
            'status'  => 'success',
            'message' => trans("Controllers.ticket_successfully_resent"),
>>>>>>> master
        ]);
    }


    /***
     * @param Request $request
     * @param $attendeeId
     * @return bool
     */
    public function showAttendeeTicket(Request $request, $attendeeId)
    {
        $attendee = Attendee::scope()->findOrFail($attendeeId);

        $data = [
            'order' => $attendee->order,
            'event' => $attendee->event,
            'tickets' => $attendee->ticket,
            'attendees' => [$attendee],
            'css' => file_get_contents(public_path('assets/stylesheet/ticket.css')),
            'image' => base64_encode(file_get_contents(public_path($attendee->event->organiser->full_logo_path))),

        ];

        if ($request->get('download') == '1') {
            return PDF::html('Public.ViewEvent.Partials.PDFTicket', $data, 'Tickets');
        }
        return view('Public.ViewEvent.Partials.PDFTicket', $data);
    }

}


