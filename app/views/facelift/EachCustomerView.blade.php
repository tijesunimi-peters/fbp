 {{--dd($customer)--}}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Viewing: {{ucfirst($customer->client_name).' - '.$customer->client_id}}</h3>
    </div>
    <div id='bodyModal' class="modal-body">
    <table class="table table-striped">

<thead>
	<th>Field</th>
	<th>Details</th>
</thead>

@foreach($customer as $key => $details)
	<tr>
		<td> @if($key == 'client_type') {{ 'Client Type' }}
		 @elseif($key == 'client_id') {{ 'Client Id Number' }}
		 @elseif($key == 'client_name') {{ 'Client Name' }}
		 @elseif($key == 'houseNo') {{ 'House Number' }}
		 @elseif($key == 'wedding_anniversary') {{ 'Wedding Anniversary' }}
		 @elseif($key == 'flk') {{ 'Facelift Knowledge' }}
		 @elseif($key == 'created_at') {{ 'Date Registered' }}
		 @elseif($key == 'updated_at') {{ 'Date Edited' }}
		 @elseif($key == 'DOB') {{ 'Date of Birth' }}
		 @elseif($key == 'phone_no') {{ 'Phone Number' }}
		 @else {{ ucwords($key) }} 
		 @endif
		 </td>
		<td>{{ $details }}</td>
	</tr>



@endforeach

</table>
    </div>
    <div class="modal-footer">
    <a href="#" class="btn" class="close" data-dismiss='modal' >Close</a>
    
    </div>

