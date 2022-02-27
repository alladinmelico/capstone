@extends('layouts.reports')

@push('styles')
    <style>
        body {
            background-color: red;
        }
    </style>
@endpush

@section('content')
    <table class="center">
    <tr>
      <th>ID</th>
      <th>TEMPERATURE</th>
      <th>USER-ID</th>
    </tr>
    <tr>
      <td>1</td>
      <td>36.5</td>
      <td>33</td>
    </tr>
    <tr>
      <td>2</td>
      <td>37.4</td>
      <td>14</td>
    </tr>
  </table>
@endsection
