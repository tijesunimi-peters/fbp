@extends('facelift.layouts.main')


@section('content') 
<div class="container" style="border: ;">
	<h1 class="span4" style="border: ;">
		Service Directory
	</h1>
	<div class="span4 pull-right" style="padding-top: 10px; padding-left: 15px;">
	{{Form::open(['class'=>'form-inline','method'=>'get','style'=>'background-color: #ececec;padding: 10px 0px 10px 15px;'])}}
		{{Form::label('month','Month: ')}}
		{{ Form::selectMonth('month', Strftime('%m',strtotime('today')),['class'=>'input-small']) }}
		{{Form::label('year','Year: ')}}
		{{--Form::selectYear()--}}
		{{ Form::selectRange('year', 2010, 2020, strftime('%Y',strtotime('today')),['class'=>'input-small']) }}
		{{Form::submit('Search',['class'=>'btn btn-small btn-info'])}}
	{{Form::close()}}
	</div>
	<div class="span10">
	@if(Session::has('info'))
	<div class="alert alert-info">
	<button class="close" data-dismiss='alert'>&times;</button>
		{{Session::get('info')}}
	</div>

@endif
</div>


</div>


<div class="modal hide fade" style="padding: 20px;" id="modal">

</div>

<div class="span10" style="">
<div class="span3 btn-group" style='border: ;'>
		{{HTML::link('facelift/add-customer','New Customer',['class'=>'btn btn-info'])}}
		{{HTML::link('facelift/all-services','All Services',['class'=>'btn btn-info'])}}
				{{HTML::link('facelift/new-service','New Service',['class'=>'btn btn-info'])}}

</div>
<div>
	{{ Form::open(['url'=>'facelift/service','class'=>'form-inline span3', 'method' => 'post', 'style'=>'']) }}

		{{ Form::label('client_name','New Service') }}
			&nbsp;
		{{ Form::text('client_name',Input::old('client_name'),['placeholder'=>'Client Name','class'=>'input-small','style'=>'height: 20px;'] ) }}
			

	{{Form::submit('Find',['class'=>'btn btn-primary'])}}
	
	{{ Form::close() }}
</div>
<div class="span3 pull-right" style='border: ;'>{{Form::open(['class'=>'form-search'])}}
<div class="input-append">
	{{Form::text('search_query', NULL,['class'=>'input-medium','placeholder'=>'Customer Search'])}}
	{{Form::button('Search', ['class'=>'btn btn-info'])}}
</div>
</div>

{{Form::close() }}
</div>

<div class="container">
<div class="span10">
<div class="pagination" style="">
		{{ '<h1 class="span1"><small>Page: </small></h1>'.$allServices->links() }}
	</div>
</div>
	<table class="table table-striped" style="border: 1px solid #ececec;">
		<thead>
			<th>S/N</th>
			<th>Client</th>
			<th>Service Type</th>
			<th>Amount (<e>N</e>)</th>
			<th>Service Date</th>

			<th>Attendant</th>
			<th>Service Start</th>
			<th>Service End</th>
			<th>Delete</th>
		</thead>
		<?php  $count = 1;   
			if(isset($_GET['page']) && $_GET['page'] != 1) {
				$count = ($_GET['page'] * 10) - 9;
			}
		?>
		@foreach($allServices as $eachService)
			<tr>
				<td>
					{{ $count }}
				</td>
				<td>
				<?php  $customer = FaceliftCustomers::find($eachService->client_id)  ?>
					{{ ucfirst($customer->client_name) }}
				</td>
				<td>
					{{ ucfirst(preg_replace('/; /', '<br />', $eachService->service_type)) }}
				</td>
				<td>
					{{ $eachService->amount }}
				</td>
				<td>
					{{ strftime('%a %d, %m %Y',strtotime($eachService->created_at)) }}
				</td>
				<td>
					{{ ucfirst($eachService->attendant) }}
				</td>
				<td>
					{{ ucfirst($eachService->service_start) }}
				</td>
				<td>
					{{ ucfirst($eachService->service_end) }}
				</td>
				<td>

					{{Form::open(['url'=>'facelift/delete-service/'.$eachService->id])}}
					{{ Form::submit('Delete', ['class'=>'serviceDeleteBtn btn btn-small btn-danger']) }}
					{{Form::close()}}
				</td>
			</tr>

			<?php   $count++;   ?>
		@endforeach
	</table>
	<div class="span10">
		<div class="pagination">
		{{ '<h1 class="span1"><small>Page: </small></h1>'.$allServices->links() }}
	</div>
	</div>
	
</div>


@stop

@section('scripts') 
<script type="text/javascript">
	$(function() {
	$('.serviceDeleteBtn').click(function() {
		
		if(confirm('Are You sure?')) {
			return true;
		} else {
			return false;
		}
		
	});
});

</script>


@stop