<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ReportModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function addReport(Request $request)
    {
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

    public function getCarReports(Request $request)
    {
        try {
            $reports = ReportModel::where('carId', $request->carId)
                ->orderBy('created_at', 'desc')
                ->get();

            $formattedReports = [];
            foreach ($reports as $report) {
                $user = UserModel::find($report->userId);
                $formattedReports[] = [
                    'id' => $report->id,
                    'content' => $report->content,
                    'created_at' => $report->created_at,
                    'user' => [
                        'id' => $user ? $user->id : null,
                        'phone' => $user ? $user->phone : 'Unknown'
                    ]
                ];
            }

            return response()->json([
                'reports' => $formattedReports
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
