@extends('layout')

@section('content')
<div class="container mt-5">

<h3>Scheduled Flights</h3>

<table class="table table-bordered">
<thead class="table-dark">
<tr>
<th>Aircraft</th>
<th>Start</th>
<th>End</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>
@foreach($schedules as $s)
<tr>
<td>{{ $s->flight->registration_number }}</td>
<td>{{ $s->start_time }}</td>
<td>{{ $s->end_time }}</td>
<td>
<span class="badge bg-success">{{ $s->status }}</span>
</td>
<td>
<a href="{{ route('flights.schedule.edit',$s->id) }}" class="btn btn-sm btn-warning">Edit</a>

<form method="POST"
      action="{{ route('flights.schedule.cancel',$s->id) }}"
      style="display:inline">
@csrf
<button class="btn btn-sm btn-danger">Cancel</button>
</form>
</td>
</tr>
@endforeach
</tbody>
</table>

</div>
@endsection
