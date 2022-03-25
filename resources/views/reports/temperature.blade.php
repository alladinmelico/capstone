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
      <td>{{ $averageTemperature }}°C</td>
    </tr>
    <tr>
      <td>Total number of users who have temperature of >=37.5°C</td>
      <td>{{ $total38Higher }}</td>
    </tr>
  </table>
  <br>
  <br>
  <h4>List of Users with >=37.5°C temperatures</h4>
  <table class="center">
    <tr>
      <th rowspan="1">Date and Time</th>
      <th rowspan="1">Temperature (°C)</th>
      <th rowspan="1">User</th>
      <th rowspan="1">School ID</th>
    </tr>
    @foreach ($totalFeverList as $item)
      <tr>
        <td>{{ $item->created_at }}</td>
        <td>{{ $item->temperature }}</td>
        <td>{{ $item->user?->name }}</td>
        <td>{{ $item->user?->school_id ? $item->user?->school_id : '[profile not updated]' }}</td>
      </tr>
    @endforeach
  </table>
  <br>
  <br>
  <br>
  <table class="center">
    <tr>
      <th rowspan="1">Date and Time</th>
      <th rowspan="1">Temperature (°C)</th>
      <th rowspan="1">User</th>
      <th rowspan="1">School ID</th>
    </tr>
    @foreach ($temperatures as $item)
      <tr>
        <td>{{ $item->created_at }}</td>
        <td>{{ $item->temperature }}</td>
        <td>{{ $item->user?->name }}</td>
        <td>{{ $item->user?->school_id ? $item->user?->school_id : '[profile not updated]' }}</td>
      </tr>
    @endforeach
  </table>
@endsection
