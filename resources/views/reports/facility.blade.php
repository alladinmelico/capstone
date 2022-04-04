@extends('layouts.reports')

@section('content')
  <h2>FACILITY REPORT</h2>
  <h4>{{ $startDate }} to {{ $endDate }}</h4>
  <table>
    <tr>
      <td>Average Schedules per Facility</td>
      <td>{{ number_format($avgSchedules, 2) }}</td>
    </tr>
    <tr>
      <td>Average Facility Capacity</td>
      <td>{{ number_format($avgCapacity, 2) }}</td>
    </tr>
  </table>
  <br>
  <br>
  <br>
  <table class="center">
    <tr>
      <th rowspan="1">Name</th>
      <th rowspan="1">Code</th>
      <th rowspan="1">Capacity</th>
      <th rowspan="1">Total Schedules</th>
    </tr>
    @foreach ($facilities as $item)
      <tr>
        <td>{{ $item->name }}</td>
        <td>{{ $item->code }}</td>
        <td>{{ $item->capacity }}</td>
        <td>{{ $item->schedules->count() }}</td>
      </tr>
    @endforeach
  </table>
@endsection
