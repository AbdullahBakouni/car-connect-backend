<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ReportModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function addReport(Request $request)
    {
        
        Log::info('ddddddddddddddddddddddddddd');
        $existing = ReportModel::where('carId', $request->carId)
            ->where('userId', $request->userId)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'You have already reported this car.'
            ], 409);
        }

        $report = new ReportModel();
        $report->content = $request->content;
        $report->carId = $request->carId;
        $report->userId = $request->userId;
        $report->save();

        return response()->json([
            'message' => 'Report added successfully.',
            'report' => $report
        ], 201);
    }
}
