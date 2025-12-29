<?php

namespace App\Http\Controllers;

use App\Services\Insights\InsightsService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(InsightsService $insights)
    {
        if (Auth::user()?->role === 'Participant licitatii') {
            $stats = $insights->participantDashboard(Auth::id(), Auth::user()?->carrier_id);

            return view('participant.home', compact('stats'));
        }

        $stats = $insights->staffDashboard();

        return view('home', compact('stats'));
    }
}
