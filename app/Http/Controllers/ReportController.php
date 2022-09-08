<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Schedule;
use App\Models\Facility;
use App\Models\Temperature;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function schedule(ReportRequest $request)
    {
        $startDate = new Carbon($request->start_date);
        $endDate = new Carbon($request->end_date);

        $schedules = Schedule::whereBetween('created_at', [$startDate, $endDate])
            ->with(['classroom.users', 'batches', 'facility', 'user'])
            ->orderBy('created_at', 'desc')->get();

        $averageUsersCount = $schedules->map(function ($value, $key) {
            return $value->batches->count();
        })->avg();

        $daysOfWeek = $schedules->map(function ($value, $key) {
            return $value->days_of_week;
        })->flatten()->countBy();

        $startDate = $startDate->toDateTimeString();
        $endDate = $endDate->toDateTimeString();

        $pdf = \PDF::loadView('reports.schedule', compact('schedules', 'startDate', 'endDate', 'averageUsersCount', 'daysOfWeek'));
        $pdf->setOptions(['isPhpEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->download("schedule-report_{$startDate}-{$endDate}.pdf");
    }

    public function temperature(ReportRequest $request)
    {
        $startDate = new Carbon($request->start_date);
        $endDate = new Carbon($request->end_date);

        $temperatures = Temperature::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $averageTemperature = $temperatures->avg('temperature');
        $totalFeverList = $temperatures->filter(function ($value, $key) {
            return $value->temperature >= env("MAX_TEMP", 37.5);
        });

        $total38Higher = $totalFeverList->count();

        $startDate = $startDate->toDateTimeString();
        $endDate = $endDate->toDateTimeString();


        $pdf = \PDF::loadView('reports.temperature', compact('temperatures', 'startDate', 'endDate', 'averageTemperature', 'total38Higher', 'totalFeverList'));
        $pdf->setOptions(['isPhpEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->download("temperature-report_{$startDate}-{$endDate}.pdf");
    }

    public function facility(ReportRequest $request)
    {
        $startDate = new Carbon($request->start_date);
        $endDate = new Carbon($request->end_date);

        $facilities = Facility::with('schedules')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $avgSchedules = round($facilities->filter(function ($value, $key) {
            return $value->schedules->isNotEmpty();
        })->map(function ($value, $key) {
            return $value->schedules->count();
        })->avg(), 2);

        $avgCapacity = round($facilities->map(function ($value, $key) {
            return $value->capacity;
        })->avg(), 2);

        $pdf = \PDF::loadView('reports.facility', compact('facilities', 'startDate', 'endDate', 'avgSchedules', 'avgCapacity'));
        $pdf->setOptions(['isPhpEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->download("facility-report_{$startDate}-{$endDate}.pdf");
    }

    public function user(Request $request)
    {
        $request->validate(['year' => 'sometimes|integer']);
        $year = $request->year;

        $users = User::with(['classrooms', 'schedules', 'batches', 'temperatures'])
            ->when(!empty($year), function ($query) use ($request) {
                return $query->where('year', $request->year);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $avgAbsent = round((($users->filter(function ($item, $key) {
            return $item->batches->filter(function ($value, $key) {
                return $value->is_absent;
            })->isNotEmpty();
        })->count() / $users->count()) * 100), 2);

        $avgSchedule = round($users->filter(function ($value, $key) {
            return $value->schedules->isNotEmpty();
        })->map(function ($value, $key) {
            return $value->schedules->count();
        })->avg(), 2);

        $users = $users->map(function ($item, $key) {
            $absentCount = $item->batches->filter(function ($value, $key) {
                return $value->is_absent;
            })->count();
            if ($absentCount != 0) {
                $absentCount = round(($absentCount / $item->batches->count()) * 100, 2);
            }
            $item->avg_absent = $absentCount;
            return $item;
        });

        $now = now();

        $pdf = \PDF::loadView('reports.user', compact('users', 'year', 'avgAbsent', 'avgSchedule'));
        $pdf->setOptions(['isPhpEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->download("user-report_{$year}-{$now}.pdf");
    }
}
