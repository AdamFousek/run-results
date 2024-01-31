<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeasurementEvent;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MeasurementController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $date = date(trim($request->get('date')));

        $views = MeasurementEvent::selectRaw("COUNT(*) views, attribute page, DATE(created_at) date")
            ->withoutRobots()
            ->groupBy(['date', 'attribute'])
            ->orderBy('date')
            ->get();
        $visitors = MeasurementEvent::selectRaw("COUNT(DISTINCT visitorid) unique_visitors, DATE(created_at) date")
            ->withoutRobots()
            ->groupBy(['date'])
            ->orderBy('date')
            ->get();

        $data = [
            'page_views' => $views->groupBy('date'),
            'unique_visitors' => $visitors->groupBy('date'),
        ];

        return Inertia::render('Admin/Measurement/Index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MeasurementEvent $measurementEvent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MeasurementEvent $measurementEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MeasurementEvent $measurementEvent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MeasurementEvent $measurementEvent)
    {
        //
    }
}
