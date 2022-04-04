@extends('layouts.reports')

@section('content')
  <h2>USER REPORT</h2>
  <h4>Year Level: {{ $year ? $year : 'All' }}</h4>
  <table>
    <tr>
      <td>Average Absenteeism Percentage</td>
      <td>{{ number_format($avgAbsent, 2) }} %</td>
    </tr>
    <tr>
      <td>Average Schedules per student</td>
      <td>{{ number_format($avgSchedule) }}</td>
    </tr>
  </table>
  <br>
  <br>
  <br>
  <table class="center">
    <tr>
      <th rowspan="1">School ID</th>
      <th rowspan="1">Name</th>
      <th rowspan="1">Total Classroom</th>
      <th rowspan="1">Total Schedule</th>
      <th rowspan="1">Absenteeism %</th>
      <th rowspan="1">Total Temperature Logs</th>
    </tr>
    @foreach ($users as $item)
      <tr>
        <td>{{ $item->school_id ? $item->school_id : '[profile not updated]' }}</td>
        <td>{{ $item->name }}</td>
        <td>{{ $item->classrooms->count() }}</td>
        <td>{{ $item->schedules->count() }}</td>
        <td>{{ $item->avg_absent }}</td>
        <td>{{ $item->temperatures->count() }}</td>
      </tr>
    @endforeach
  </table>
@endsection
