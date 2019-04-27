<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Service History for {{ucfirst($customer->client_name).' - '.$customer->client_id}}</h3>
</div>
<div class="modal-body">

@if(!empty($customerHistory))
	<table class="table table-striped">
		<thead>
			<th>Date</th>
			<th>Time</th>
			<th>Service Type</th>
			<th>Total Amount</th>
		</thead>


		
		@foreach($customerHistory as $eachHistory)
		<tr>
			<td>
				{{ strftime('%a %d, %b %Y',strtotime($eachHistory->created_at)) }}
			</td>
			<td>
				{{ strftime('%H:%M',strtotime($eachHistory->created_at)) }}
			</td>
			<td>
				{{ ucfirst(preg_replace('/; /','<br />',$eachHistory->service_type)) }}
			</td>
			<td>
				{{ $eachHistory->amount }}
			</td>
		</tr>


		@endforeach
	</table>

@else
<h1><small>No Service History</small></h1>

@endif
</div>

<div class="modal-footer">
	 <a href="#" class="btn" class="close" data-dismiss='modal' >Close</a>
</div>
