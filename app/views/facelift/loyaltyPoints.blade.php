@extends('facelift.layouts.main')

@section('content')
<div class="span9">
	<h1>
		Loyalty Points
	</h1>
</div>
<div class="span10" style="border-bottom: 1px solid #ececec">
<div class="span3 btn-group" style='border: ;'>
		{{HTML::link('facelift/add-customer','New Customer',['class'=>'btn btn-info'])}}
		{{HTML::link('facelift/all-services','All Services',['class'=>'btn btn-info'])}}
		{{HTML::link('facelift/new-service','New Service',['class'=>'btn btn-info'])}}

</div>

	


<div class="span3 pull-right" style='border: ;'>
{{Form::open(['class'=>'form-search'])}}
<div class="input-append">
	{{Form::text('search_query', NULL,['class'=>'input-medium','style'=>'height: 20px','placeholder'=>'Customer Name'])}}
	{{Form::button('Search', ['class'=>'btn btn-info'])}}
</div>
{{Form::close() }}
</div>
@if(Session::has('report'))
<div class="alert alert-success">
<button class="close" data-dismiss="alert">&times;</button>
{{ Session::get('report') }}

</div>
@endif

@if(Session::has('info'))
	<div class="alert alert-info">
	<button class="close" data-dismiss='alert'>&times;</button>
		{{ucfirst(Auth::user()->username).': '.Session::get('info')}}
	</div>
@endif

</div>


<div class="container">
<?PHP $lpA = array() ?>
@foreach($lp as $eachlp) 
	<?php $lpA[] = $eachlp->client_id ?>

@endforeach

<table class="table table-striped" style="border:1px solid #ececec">
<th>
	S/N
</th>
<th >
	Today
</th>
<th>
	Client Name
</th>
<th>
	Loyalty Points
</th>
<th>
	Goal
</th>
<?php  $count = 1;   
			if(isset($_GET['page']) && $_GET['page'] != 1) {
				$count = ($_GET['page'] * 10) - 9;
			}
		?>
<?php  $uniqueLpa = array_unique($lpA); $c = 1;?>

@foreach($uniqueLpa as $eachLoyP)
	<?php $allLp = FaceliftCustomers::find($eachLoyP)->loyalty_points()->orderBy('created_at','DESC')->get();
			$client = FaceliftCustomers::find($eachLoyP); 
			$lpArray = array();
			?>

	<tr>
	<td>
		{{$count}}
	</td>
	<td>
		{{strftime('%d-%m-%Y',strtotime('today'))}}
	</td>
		<td>
			{{$client->client_name}}
		</td>
		<td>
			
			@foreach($allLp as $oneLp)
				<?php $lpArray[] = $oneLp->loyalty_points; ?>
				
			@endforeach

			{{ array_sum($lpArray) }}
			
		</td>
		<td>
			@if(array_sum($lpArray) == 50000 || is_int(array_sum($lpArray)/50000))
				{{'<span style="text-decoration: blink; color: red;">50000 points Reached</span>'}}
			@else 
			{{'Goal not Reached'}}
			@endif
		</td>
	</tr>
<?php $count++; ?>
@endforeach

</table>

</div>

@stop