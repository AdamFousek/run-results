<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Illuminate\Race;
use App\Models\Illuminate\Result;
use App\Models\Illuminate\Runner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function reloadRunners(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user->isAdmin()) {
            return response()->json([
                'result' => false,
                'message' => 'Not authorized'
            ]);
        }

        Runner::removeAllFromSearch();

        Runner::query()->chunk(200, function ($runners) {
            $runners->searchable();
        });

        return response()->json([
            'result' => true,
            'message' => trans('messages.entity_data_reloaded'),
        ]);
    }

    public function reloadRaces(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user->isAdmin()) {
            return response()->json([
                'result' => false,
                'message' => 'Not authorized'
            ]);
        }

        Race::removeAllFromSearch();

        Race::query()->chunk(200, function ($races) {
            $races->searchable();
        });

        return response()->json([
            'result' => true,
            'message' => trans('messages.entity_data_reloaded'),
        ]);
    }

    public function reloadResults(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user->isAdmin()) {
            return response()->json([
                'result' => false,
                'message' => 'Not authorized'
            ]);
        }

        Result::removeAllFromSearch();

        Result::query()->chunk(200, function ($races) {
            $races->searchable();
        });

        return response()->json([
            'result' => true,
            'message' => trans('messages.entity_data_reloaded'),
        ]);
    }
}
