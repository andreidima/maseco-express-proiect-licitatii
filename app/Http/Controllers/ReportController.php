<?php

namespace App\Http\Controllers;

use App\Services\Insights\InsightsService;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function users(InsightsService $insights)
    {
        $stats = $insights->reportUsers();

        return view('reports.users', compact('stats'));
    }

    public function support(InsightsService $insights)
    {
        $stats = $insights->reportSupportAndNotifications();

        return view('reports.support', compact('stats'));
    }

    public function auctions(InsightsService $insights)
    {
        $stats = $insights->reportAuctions();

        return view('reports.auctions', compact('stats'));
    }

    public function bids(InsightsService $insights)
    {
        $stats = $insights->reportBids();

        return view('reports.bids', compact('stats'));
    }

    public function contracts(InsightsService $insights)
    {
        $stats = $insights->reportContracts();

        return view('reports.contracts', compact('stats'));
    }

    public function masterData(InsightsService $insights)
    {
        $stats = $insights->reportMasterData();

        return view('reports.master-data', compact('stats'));
    }
}
