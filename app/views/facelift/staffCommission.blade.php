@extends('facelift.layouts.main')

@section('content')
<h1>
	Staff Commissions
</h1>
@if(Session::has('info'))
	<div class="alert alert-info">
	<button class="close" data-dismiss='alert'>&times;</button>
		{{Session::get('info')}}
	</div>
@endif
<table class="table table-striped" style="border:1px solid #ececec">
<thead>
	<th>
		S/N
	</th>
	<th>
		Date
	</th>
	<th>
		Staff
	</th>
	<th>
		Services
	</th>
	<th>
		Customer
	</th>
	<th>
		Amount
	</th>
	<th>
		Total<br /> Commission
	</th>
	<th>
		Delete
	</th>
</thead>
<?php  $count = 1;   
			if(isset($_GET['page']) && $_GET['page'] != 1) {
				$count = ($_GET['page'] * 10) - 9;
			}
		?>

@if($commissions)
	@foreach($commissions as $com)
	<tr>
	<td>
		
			{{ $count }}
		
	</td>
	<td>
		{{strftime('%a %d, %b %Y',strtotime($com->created_at))}}
	</td>
	<td>
	<?php $staffName = FaceliftStaff::find($com->staff_id); ?>
		@if(!empty($staffName->name))
		{{ ucfirst($staffName->name) }}
		@endif
	</td>
	<td>
		{{ preg_replace('/; /','<br />',$com->services) }}
	</td>
	<td>
		<?php $customerName = FaceliftCustomers::find($com->customer_id) ?>
		@if(!empty($customerName->client_name))
		{{ ucfirst($customerName->client_name) }}
		@endif
	</td>
	<td>
		{{ $com->amount }}
	</td>
	<td>
		{{ $com->total }}
	</td>
	<td>
		{{ HTML::link('#', 'delete', ['class'=>'btn btn-small btn-danger'])}}
	</td>
	</tr>
	<?php $count++; ?>
	@endforeach
@else 
	{{'No Commissions to Staff Yet'}}
@endif
</table>
<div class="pagination">
{{ $commissions->links() }}
	
</div>
	
@stop