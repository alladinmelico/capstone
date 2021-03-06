@extends('layouts.reports')

@section('content')
  <h2>SCHEDULE REPORT</h2>
  <h4>{{ $startDate }} to {{ $endDate }}</h4>
  <table>
    <tr>
      <td>Average Users per Schedule</td>
      <td>{{ round($averageUsersCount, 2) }}</td>
    </tr>
  </table>
  <h5>Total schedules each day of the week</h5>
  <table>
    <tr>
      <td>Monday</td>
      <td>Tuesday</td>
      <td>Wednesday</td>
      <td>Thursday</td>
      <td>Friday</td>
    </tr>
    <tr>
      <td>{{ $daysOfWeek['monday'] ?? 'n/a' }}</td>
      <td>{{ $daysOfWeek['tuesday'] ?? 'n/a' }}</td>
      <td>{{ $daysOfWeek['wednesday'] ?? 'n/a' }}</td>
      <td>{{ $daysOfWeek['thursday'] ?? 'n/a' }}</td>
      <td>{{ $daysOfWeek['friday'] ?? 'n/a' }}</td>
    </tr>
  </table>
  <br>
  <br>
  <br>
  <table class="center">
    <tr>
      <th rowspan="1">Created Date</th>
      <th rowspan="1">ID</th>
      <th rowspan="1">Start Date</th>
      <th rowspan="1">End Date</th>
      <th rowspan="1">Total Users</th>
      <th rowspan="1">Total Absent Users</th>
    </tr>
    @foreach ($schedules as $item)
      <tr>
        <td>{{ $item->created_at }}</td>
        <td>{{ $item->id }}</td>
        <td>{{ $item->start_date }}</td>
        <td>{{ $item->end_date ? $item->end_date : $item->start_date }}</td>
        <td>{{ $item->classroom?->users?->count() }}</td>
        <td>{{
                $item->batches?->filter(function ($value, $key) {
                    return $value->is_absent;
                })->count()
            }}
        </td>
      </tr>
    @endforeach
  </table>
@endsection
