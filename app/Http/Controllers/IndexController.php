<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndexController extends Controller
{
    /**
     * redirect index page
     * @param  Request $request http request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showIndex(Request $request)
    {
        $allowed_sorts = ['created_at', 'start_date', 'end_date', 'title'];

        $searchQuery = $request->get('q');
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'start_date');

        $events = $searchQuery
            ? Event
                ::where('title', 'like', '%' . $searchQuery . '%')
                ->where("is_live",1)
                ->where("end_date",">",new \DateTime)
                ->paginate(12)
            : Event
                ::where("is_live",1)
                ->where("end_date",">",new \DateTime)
                ->paginate(12)
            ;

        foreach($events as $event){
            $event->is_free = false;
            $event->slug = Str::slug($event->title);
            foreach($event->tickets as $ticket){
                if($ticket->is_free){
                    $event->is_free = true;
                }
            }
        }

        $data = [
            'events'    => $events,
            'search'    => [
                'q'        => $searchQuery ? $searchQuery : '',
                'sort_by'  => $request->get('sort_by') ? $request->get('sort_by') : '',
                'showPast' => $request->get('past'),
            ],
        ];

        return view('Public.Staygig.Home.Index', $data);
    }
}
