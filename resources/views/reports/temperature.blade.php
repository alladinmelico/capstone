@extends('layouts.reports')

@section('content')
  <h2>TEMPERATURE REPORT</h2>
  <h4>{{ $startDate }} to {{ $endDate }}</h4>
  <table>
    <tr>
      <td>Average Temperature</td>
      <td>{{ number_format($averageTemperature, 2) }}°C</td>
    </tr>
    <tr>
      <td>Total number of users who have temperature of >= {{ env("MAX_TEMP", 37.5) }}°C</td>
      <td>{{ number_format($total38Higher, 2) }}</td>
    </tr>
  </table>
  <br>
  <br>
  <h4>List of Users with >= {{ env("MAX_TEMP", 37.5) }}°C temperatures</h4>
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
        <td>{{ number_format($item->temperature, 2) }}</td>
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
        <td>{{ $item->user?->name ? $item->user?->name : '[profile not updated]' }}</td>
        <td>{{ $item->user?->school_id ? $item->user?->school_id : '[profile not updated]' }}</td>
      </tr>
    @endforeach
  </table>
  <br>
  <br>
  <br>
  @foreach ($classrooms as $classroom)
    <h3>{{ $classroom['classroom'] }}</h3>
    <table class="center">
        <tr>
        <th rowspan="1">User</th>
        <th rowspan="1">Temperature (°C)</th>
        </tr>
        @foreach ($classroom['users'] as $user)
        <tr>
            <td>{{ $user['user'] }}</td>
            <td>{{ $user['temperature']['temperature'] }}</td>
        </tr>
        @endforeach
    </table>
  @endforeach
@endsection
