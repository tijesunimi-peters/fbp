
@extends('facelift.layouts.main')


@section('content')

@if($errors->has())
<div class="alert alert-error" style="">
<button class="close" data-dismiss="alert">&times;</button>
<ul>
@foreach($errors->all('<li>:message</li>') as $eachError)
{{ $eachError }}
@endforeach
</ul>
</div>

@endif

@if(Session::has('bad-report'))
<div class="alert alert-error" style="">
<button class="close" data-dismiss="alert">&times;</button>
<ul>
{{ Session::get('bad-report') }}
</ul>
</div>

@endif

@if(Session::has('report'))
<div class="alert alert-success" style="">
<button class="close" data-dismiss="alert">&times;</button>
<ul>
{{ Session::get('report') }}
</ul>
</div>

@endif

@if(!empty($customer))

<div class="container">
	<div class="span6">
		<h1>Customer: {{ ucfirst($customer->client_name) }}</h1>
	</div>
	<div class="span4">
		{{Form::open(['url'=>'facelift/add-loyalty-points','class'=>'form-inline'])}}
			{{ Form::hidden('client_id', $customer->client_id) }}
			{{Form::label('points','New Loyalty Points')}}
			{{Form::input('number', 'loyalty-points', NULL,['class'=>'input-medium'])}}
			{{Form::submit('Add Points',['class'=>'btn btn-success'])}}
		{{Form::close()}}
	</div>
</div>


<div class="container well">
 <table class="table table-striped span5">
 <tr><td>Client Id Number</td><td> {{ $customer->client_id }} </td></tr>
 		<tr><td>Sex </td><td> {{ $customer->gender }} </td></tr>
 		<tr><td>Date of Birth</td><td> {{ $customer->DOB }} </td></tr>
 		<tr><td>Marital Status</td><td> {{ $customer->status }} </td></tr>
 </table>
 <div class="span4">
 <?php
 	//dd($lp);
 	$lpArray = array();
 	foreach($lp as $elp) {
 		$lpArray[] = $elp->loyalty_points;
 	}

 ?>
 	<h2>
 		Loyalty Points : {{array_sum($lpArray)}}
 	</h2>
 </div>
</div>
<div class="container"> 
{{Form::open(['class'=>'form-horizontal span4','url'=>'facelift/add-new-service'])}}

<fieldset><h4>New Service Info</h4>
{{ Form::hidden('client_id', $customer->client_id) }}
	
				{{ Form::hidden('date', strftime('%Y-%m-%d',strtotime('today')), ['class'=>'input-medium date','style'=>'height: 30px;'])}}
			
			<div class="control-group">
				<div class="control-label">
					{{Form::label('service','Service')}}
				</div>
				<div class="controls">
					{{-- Form::select('service',[
										'haircut'=>'Haircut',
										'hairstyle'=>'Hairstyle',
										'nails'=>'Nails',
										'makeup'=>'Make-Up',
										'headgear'=>'Head-Gear',
										'beads'=>'Beads',
										'special'=>'Special Package',
										'mobile-salon'=>'Mobile Salon'
										], Input::old('service'), ['class'=>'input-medium','style'=>'height: 30px;'])--}}

					{{ Form::textarea('service',Input::old('service'),['class'=>'input-medium','id'=>'service','style'=>'height: 100px;']) }}


				</div>
			</div>

			<div class="control-group">
				<div class="control-label">
					{{Form::label('service-start','Service Start')}}
				</div>
				<div class="controls">
					{{ Form::text('service-start', Input::old('service-start'), ['placeholder'=>'am or pm','class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('service-end','Service End')}}
				</div>
				<div class="controls">
					{{ Form::text('service-end', Input::old('service-end'), ['placeholder'=>'am or pm','class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('attendant','Attendant')}}
				</div>
				<div class="controls">
					{{ Form::text('attendant', Input::old('attendant'), ['class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('amount','Amount')}}
				</div>
				<div class="controls">
					{{ Form::text('amount', Input::old('amount'), ['class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('arrival','Arrival')}}
				</div>
				<div class="controls">
					{{ Form::text('arrival', Input::old('arrival'), ['placeholder'=>'am or pm','class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('departure','Departure')}}
				</div>
				<div class="controls">
					{{ Form::text('departure', Input::old('departure'), ['placeholder'=>'am or pm','class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('rebooked-date','Rebooked Date')}}
				</div>
				<div class="controls">
					{{ Form::text('rebooked-date', Input::old('rebooked-date'), ['class'=>'input-medium date','style'=>'height: 30px;'])}}
				</div>
			</div>


</fieldset>
{{ Form::submit('Save', ['class'=>'pull-right btn btn-primary'])}}
{{  Form::close() }}
<div class="span5" style="border: 1px soli;"><h2>Customer's History</h2>
{{-- dd($serviceHistory) --}}

@if(!empty($serviceHistory))
<table class="table table-striped">
<thead>
	<th>Service Type</th>
	<th>Date</th>
	<th>Time</th>
	<th>Amount</th>
</thead>
	@foreach($serviceHistory as $eachService) 
	<tr>
	<td>
		{{ preg_replace('/; /', '<br />', $eachService->service_type) }}
	</td>
	<td>
		{{ strftime('%d %b, %Y',strtotime($eachService->created_at)) }}
	</td>
	<td>
		{{ strftime('%H:%M',strtotime($eachService->created_at)) }}
	</td>
	<td>
		{{ $eachService->amount }}
	</td>
	</tr>
	@endforeach
</table>
@else 
{{ 'No Service History' }}
@endif
</div>
</div>

@else 

<div class="alert alert-error">
<button class="close" data-dismiss="alert">&times;</button>
<h2>No customer was Selected</h2>
{{HTML::link('facelift/new-service', 'Select Customer', ['class'=>'btn btn-large btn-primary'])}}
</div>

@endif

@stop

@section('scripts')
<script>
	$(function() {
		var availableTags = [
					'Haircut',
					'Hairstyle',
					'Nails',
					'Make-Up',
					'Head-Gear',
					'Beads',
					'Special Package',
					'Mobile Salon'			
		];
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}

		$( "#service" )
			// don't navigate away from the field on tab when selecting an item
			.bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
						$( this ).autocomplete( "instance" ).menu.active ) {
					event.preventDefault();
				}
			})
			.autocomplete({
				minLength: 0,
				source: function( request, response ) {
					// delegate back to autocomplete, but extract the last term
					response( $.ui.autocomplete.filter(
						availableTags, extractLast( request.term ) ) );
				},
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {
					var terms = split( this.value );
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push( ui.item.value );
					// add placeholder to get the comma-and-space at the end
					terms.push( "" );
					this.value = terms.join( ", " );
					return false;
				}
			});
	});
	</script>

@stop