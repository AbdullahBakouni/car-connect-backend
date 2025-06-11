<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LikeModel;
use App\Models\ViewModel;
use App\Models\CommentModel;
use App\Models\RateModel;
use App\Models\ReportModel;
use App\Models\FavoriteModel;
use App\Models\OrderModel;
use App\Models\ReservationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function getStatistics(Request $request)
    {
        try {
            if (!$request->period || !$request->date) {
                return response()->json([
                    'error' => 'Missing required parameters. Both period and date are required.',
                    'required_params' => ['period' => ['day', 'month', 'year'], 'date' => 'YYYY-MM-DD']
                ], 400);
            }

            try {
                $date = Carbon::parse($request->date);
                
                switch ($request->period) {
                    case 'day':
                        $startDate = $date->copy()->startOfDay();
                        $endDate = $date->copy()->endOfDay();
                        break;
                    case 'month':
                        $startDate = $date->copy()->startOfMonth();
                        $endDate = $date->copy()->endOfMonth();
                        break;
                    case 'year':
                        $startDate = $date->copy()->startOfYear();
                        $endDate = $date->copy()->endOfYear();
                        break;
                    default:
                        return response()->json([
                            'error' => 'Invalid period. Use day, month, or year',
                            'provided' => $request->period
                        ], 400);
                }

            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Invalid date format. Please use YYYY-MM-DD format.',
                    'provided_date' => $request->date,
                    'message' => $e->getMessage()
                ], 400);
            }

            $statistics = [
                'period' => $request->period,
                'date' => $request->date,
                'metrics' => []
            ];

            DB::beginTransaction();
            try {
                if (Schema::hasTable('likes')) {
                    try {
                        $statistics['metrics']['likes'] = [
                            'total' => DB::table('likes')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->count(),
                            'details' => $this->getLikesDetails($startDate, $endDate)
                        ];
                    } catch (\Exception $e) {}
                }

                if (Schema::hasTable('view')) {
                    try {
                        $statistics['metrics']['views'] = [
                            'total' => DB::table('view')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->sum('count'),
                            'details' => $this->getViewsDetails($startDate, $endDate)
                        ];
                    } catch (\Exception $e) {}
                }

                if (Schema::hasTable('comment')) {
                    try {
                        $statistics['metrics']['comments'] = [
                            'total' => DB::table('comment')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->count(),
                            'details' => $this->getCommentsDetails($startDate, $endDate)
                        ];
                    } catch (\Exception $e) {}
                }

                if (Schema::hasTable('rate')) {
                    try {
                        $statistics['metrics']['rates'] = [
                            'total' => DB::table('rate')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->count(),
                            'average' => DB::table('rate')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->avg('value'),
                            'details' => $this->getRatesDetails($startDate, $endDate)
                        ];
                    } catch (\Exception $e) {}
                }

                if (Schema::hasTable('report')) {
                    try {
                        $statistics['metrics']['reports'] = [
                            'total' => DB::table('report')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->count(),
                            'details' => $this->getReportsDetails($startDate, $endDate)
                        ];
                    } catch (\Exception $e) {}
                }

                if (Schema::hasTable('favorite')) {
                    try {
                        $statistics['metrics']['favorites'] = [
                            'total' => DB::table('favorite')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->count(),
                            'details' => $this->getFavoritesDetails($startDate, $endDate)
                        ];
                    } catch (\Exception $e) {}
                }

                if (Schema::hasTable('order')) {
                    try {
                        $totalRevenue = DB::table('order')
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->sum(DB::raw('COALESCE(totalPrice, 0)'));

                        $statistics['metrics']['orders'] = [
                            'total' => DB::table('order')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->count(),
                            'total_by_status' => $this->getOrderStatusCounts($startDate, $endDate),
                            'revenue' => round($totalRevenue, 2),
                            'details' => $this->getOrdersDetails($startDate, $endDate)
                        ];
                    } catch (\Exception $e) {}
                }

                if (Schema::hasTable('reservations')) {
                    try {
                        $statistics['metrics']['reservations'] = [
                            'total' => DB::table('reservations')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->count(),
                            'active' => DB::table('reservations')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->count(),
                            'details' => $this->getReservationsDetails($startDate, $endDate)
                        ];
                    } catch (\Exception $e) {}
                }

                DB::commit();
                return response()->json($statistics, 200);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'error' => 'Database error',
                    'message' => $e->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getLikesDetails($startDate, $endDate)
    {
        $likes = DB::table('likes')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('carId, COUNT(*) as count')
            ->groupBy('carId')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return $likes->map(function($like) {
            $car = DB::table('car')->find($like->carId);
            return [
                'carId' => $like->carId,
                'carDesc' => $car ? $car->desc : 'Unknown',
                'count' => $like->count
            ];
        });
    }

    private function getViewsDetails($startDate, $endDate)
    {
        $views = DB::table('view')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return $views->map(function($view) {
            $car = DB::table('car')->find($view->carId);
            return [
                'carId' => $view->carId,
                'carDesc' => $car ? $car->desc : 'Unknown',
                'count' => $view->count
            ];
        });
    }

    private function getCommentsDetails($startDate, $endDate)
    {
        $comments = DB::table('comment')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('carId, COUNT(*) as count')
            ->groupBy('carId')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return $comments->map(function($comment) {
            $car = DB::table('car')->find($comment->carId);
            return [
                'carId' => $comment->carId,
                'carDesc' => $car ? $car->desc : 'Unknown',
                'count' => $comment->count
            ];
        });
    }

    private function getRatesDetails($startDate, $endDate)
    {
        $rates = DB::table('rate')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('carId, COUNT(*) as count, AVG(value) as average')
            ->groupBy('carId')
            ->orderBy('average', 'desc')
            ->limit(5)
            ->get();

        return $rates->map(function($rate) {
            $car = DB::table('car')->find($rate->carId);
            return [
                'carId' => $rate->carId,
                'carDesc' => $car ? $car->desc : 'Unknown',
                'count' => $rate->count,
                'average' => round($rate->average, 2)
            ];
        });
    }

    private function getReportsDetails($startDate, $endDate)
    {
        $reports = DB::table('report')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('carId, COUNT(*) as count')
            ->groupBy('carId')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return $reports->map(function($report) {
            $car = DB::table('car')->find($report->carId);
            return [
                'carId' => $report->carId,
                'carDesc' => $car ? $car->desc : 'Unknown',
                'count' => $report->count
            ];
        });
    }

    private function getFavoritesDetails($startDate, $endDate)
    {
        $favorites = DB::table('favorite')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('carId, COUNT(*) as count')
            ->groupBy('carId')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return $favorites->map(function($favorite) {
            $car = DB::table('car')->find($favorite->carId);
            return [
                'carId' => $favorite->carId,
                'carDesc' => $car ? $car->desc : 'Unknown',
                'count' => $favorite->count
            ];
        });
    }

    private function getOrderStatusCounts($startDate, $endDate)
    {
        return [
            'pending' => DB::table('order')->whereBetween('created_at', [$startDate, $endDate])->where('status', 0)->count(),
            'confirmed' => DB::table('order')->whereBetween('created_at', [$startDate, $endDate])->where('status', 1)->count(),
            'in_progress' => DB::table('order')->whereBetween('created_at', [$startDate, $endDate])->where('status', 2)->count(),
            'cancelled' => DB::table('order')->whereBetween('created_at', [$startDate, $endDate])->where('status', 3)->count(),
            'completed' => DB::table('order')->whereBetween('created_at', [$startDate, $endDate])->where('status', 4)->count()
        ];
    }

    private function getOrdersDetails($startDate, $endDate)
    {
        $orders = DB::table('order')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('carId, COUNT(*) as count, SUM(COALESCE(totalPrice, 0)) as total_revenue')
            ->groupBy('carId')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return $orders->map(function($order) {
            $car = DB::table('car')->find($order->carId);
            return [
                'carId' => $order->carId,
                'carDesc' => $car ? $car->desc : 'Unknown',
                'count' => $order->count,
                'revenue' => round($order->total_revenue, 2)
            ];
        });
    }

    private function getReservationsDetails($startDate, $endDate)
    {
        $reservations = DB::table('reservations')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('carId, COUNT(*) as count')
            ->groupBy('carId')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return $reservations->map(function($reservation) {
            $car = DB::table('car')->find($reservation->carId);
            return [
                'carId' => $reservation->carId,
                'carDesc' => $car ? $car->desc : 'Unknown',
                'count' => $reservation->count
            ];
        });
    }
} 