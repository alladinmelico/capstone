@extends('layouts.reports')

@section('content')
  <header>
    <img src="ssc.png" alt="" width="150" height="150"> <img src="tup.png" alt="" width="150" height="150">
    <p>
      <strong>
        Safe and Smart Campus: A Scheduling and Monitoring System for Technological University of the Philippines
        – Taguig Campus (SSC)
      </strong>
    </p>

  </header>
  <h2>TEMPERATURE REPORT</h2>
  <h4>{{ $startDate }} to {{ $endDate }}</h4>
  <table>
    <tr>
      <td>Average Temperature</td>
      <td>{{ $averageTemperature }}</td>
    </tr>
    <tr>
      <td>Total number of users who have temperature of 38°C or higher</td>
      <td>{{ $total38Higher }}</td>
    </tr>
  </table>
  <br>
  <br>
  <br>
  <table class="center">
    <tr>
      <th rowspan="1">Date and Time</th>
      <th rowspan="1">Temperature</th>
      <th rowspan="1">User ID</th>
      <th rowspan="1">School ID</th>
    </tr>
    @foreach ($temperatures as $item)
      <tr>
        <td>{{ $item->created_at }}</td>
        <td>{{ $item->temperature }}</td>
        <td>{{ $item->user_id }}</td>
        <td>{{ $item->user->school_id }}</td>
      </tr>
    @endforeach
  </table>
@endsection
