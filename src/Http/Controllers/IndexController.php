<?php

namespace Satishsinghdevbha\DbScheduler\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use Satishsinghdevbha\DbScheduler\Models\DBScheduler;

class IndexController extends BaseController {

    public function index(Request $request) {
        
        if($request->ajax()) {
            return response()->json(DBScheduler::query()->get());
        }
        
        return view('dbscheduler::index');
    }

    public function store(Request $request): JsonResponse {

        
        $validated = $request->validate([
            'command' => 'required|string',
            'cron_expression' => 'required|string',
            'arguments' => 'nullable|string',
            'options' => 'nullable|string',
            'environments' => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);

        DBScheduler::create($validated);
        
        return response()->json(['message' => 'Schedule created successfully', 'schedule' => $validated], 201);
    }

    /**
     * Fetch a single schedule by ID.
     */
    public function show($id): JsonResponse
    {
        $schedule = DBScheduler::findOrFail($id);
        return response()->json($schedule);
    }

    /**
     * Update an existing schedule.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'command' => 'required|string',
            'cron_expression' => 'required|string',
            'arguments' => 'nullable|string',
            'options' => 'nullable|string',
            'environments' => 'nullable|string',
            'status' => 'required|in:1,0',
        ]);

        $schedule = DBScheduler::findOrFail($id);
        $schedule->update($validated);

        return response()->json(['message' => 'Schedule updated successfully', 'schedule' => $validated]);
    }

    /**
     * Delete a schedule.
     */
    public function destroy($id): JsonResponse
    {
        $schedule = DBScheduler::findOrFail($id);
        $schedule->delete();

        return response()->json(['message' => 'Schedule deleted successfully']);
    }
}