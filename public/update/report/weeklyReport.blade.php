@extends('facelift.report.layout.main')
@section('content')
<div class="span12">
	<table class="table table-striped" style="border:1px solid #ececec">
		<th>S/N</th>
		<th>Date</th>
		<th>Client</th>
		<th>Service</th>
		<th>Time Start</th>
		<th>Time End</th>
		<th>Attendant</th>
		<th>Arrival</th>
		<th>Dept.</th>
		<th>Amount</th>
		<?php $amntArray = array(); $c=1; ?>
		@foreach($allServices as $eachService)
		<?php
		$client = FaceliftCustomers::find($eachService->client_id);

		?>
		
		<?php $amntArray[] = $eachService->amount;?>
		<tr>
			<td>{{$c}}</td>
			<td>{{strftime('%d-%m-%Y',strtotime($eachService->created_at))}}</td>
			<td>{{$client->client_name}}</td>
			<td>{{preg_replace('/; /','<br />',$eachService->service_type)}}</td>
			<td>{{$eachService->service_start}}</td>
			<td>{{$eachService->service_end}}</td>
			<td>{{$eachService->attendant}}</td>
			<td>{{$eachService->arrival}}</td>
			<td>{{$eachService->departure}}</td>
			<td>{{number_format($eachService->amount)}}</td>
			
			
		</tr>
		
		<?php $c++; ?>
		@endforeach
		<div class="span10" style="border: 1px solid #ececec">
			<div class="">
				<h4 class="span2">Total Sum:</h4>
				<h4 class="span4">{{number_format(array_sum($amntArray))}}</h4>
			</div>
		</div>
	</table>
</div>
@stop