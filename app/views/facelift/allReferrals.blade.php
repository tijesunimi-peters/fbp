@extends('facelift.layouts.main')


@section('content')
<div class="span10">
	<h1>
		Referrals Directory
	</h1>
	@if(Session::has('info'))
	<div class="alert alert-info">
	<button class="close" data-dismiss='alert'>&times;</button>
		{{Session::get('info')}}
	</div>
@endif
</div>
<div class="span10">
<div class="span3 btn-group" style='border: ;'>
		{{HTML::link('facelift/add-customer','New Customer',['class'=>'btn btn-info'])}}
		{{HTML::link('facelift/all-services','All Services',['class'=>'btn btn-info'])}}
		{{HTML::link('facelift/new-service','New Service',['class'=>'btn btn-info'])}}
</div>
<div class="span3 pull-right" style='border: ;'>{{Form::open(['class'=>'form-search'])}}
<div class="input-append">
	{{Form::text('search_query', NULL,['class'=>'input-medium','placeholder'=>'Referral Search'])}}
	{{Form::button('Search', ['class'=>'btn btn-info'])}}
</div>
</div>

{{Form::close() }}
</div>

<div class="container">
<div class="pagination">
		{{ $allReferrals->links() }}
	</div>
	<table class="table table-striped" style="border:1px solid #ececec">
		<thead>
			<th>S/N</th>
			<th>Client Id</th>

			<th>Refered</th>
			<th>Date Added</th>

			<th>Referee</th>
		</thead>
		<?php  $count = 1;   
			if(isset($_GET['page']) && $_GET['page'] != 1) {
				$count = ($_GET['page'] * 10) - 9;
			}
		?>
		@foreach($allReferrals as $eachReferral)
			<tr>
				<td>
					{{$count}}
				</td>
				<td>
					{{ ucfirst($eachReferral->client_id) }}
				</td>
				<td>
					{{ ucfirst($eachReferral->refered) }}
				</td>
				<td>
					{{ strftime('%a %d, %m %Y',strtotime($eachReferral->created_at)) }}
				</td>
				<td>
					@if($eachReferral->staff_id) 

						<?php $staff = FaceliftStaff::find($eachReferral->staff_id); ?>

						{{ ucfirst($staff->name).' - Staff' }}
						
					@elseif($eachReferral->customer_id)
					<?php  $customer = FaceliftCustomers::find($eachReferral->customer_id); ?>
					{{ ucfirst($customer->client_name).' - Customer' }}
					@else 
					{{ 'NILL' }}
					@endif
				</td>
			</tr>

			<?php  $count++;  ?>
		@endforeach
	</table>
	<div class="pagination">
		{{ $allReferrals->links() }}
	</div>
</div>


@stop