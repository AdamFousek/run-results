<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Illuminate\Race;
use App\Models\IlluminateModel;
use App\Services\RecalculateTopResultsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReloadMeilisearchDataController extends Controller
{
    public function __construct(
        private readonly RecalculateTopResultsService $recalculateTopResultsService,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $entity = trim($request->input('entity', ''));
        $entityId = (int)$request->input('entityId', 0);
        $entity = 'App\\Models\\Illuminate\\' . $entity;

        if ($entityId === 0) {
            return response()->json([
                'result' => false,
                'message' => trans('messages.entity_id_is_required'),
            ]);
        }

        /** @var IlluminateModel $model */
        $model = (new $entity);
        $model = $model::whereId($entityId)->first();
        if ($model === null) {
            return response()->json([
                'result' => false,
                'message' => trans('messages.entity_not_found'),
            ]);
        }

        $model->searchable();

        if ($model instanceof Race) {
            $model->results()->searchable();

            if ($model->tag !== null) {
                $this->recalculateTopResultsService->handle($model->tag);
            }
        }

        return response()->json([
            'result' => true,
            'message' => trans('messages.entity_data_reloaded'),
        ]);
    }
}
