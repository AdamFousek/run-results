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
            ->orderBy('date', 'desc')
            ->get();
        $visitors = MeasurementEvent::selectRaw("COUNT(DISTINCT visitorid) unique_visitors, DATE(created_at) date")
            ->withoutRobots()
            ->groupBy(['date'])
            ->orderBy('date', 'desc')
            ->get();

        $data = [
            'page_views' => $views->groupBy('date'),
            'unique_visitors' => $visitors->groupBy('date'),
        ];

        return Inertia::render('Admin/Measurement/Index', $data);
    }
}
