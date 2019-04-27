@extends('facelift.layouts.main')

@section('content')

@if(Session::has('report'))
	<div class="alert alert-success">
	<button class="close" data-dismiss="alert">&times;</button>
	<div>
		{{ Session::get('report') }}
	</div>
		
	</div>

@endif

@if(Session::has('error'))
	<div class="alert alert-error">
	<button class="close" data-dismiss="alert">&times;</button>
	<div>
		{{ Session::get('error') }}
	</div>
		
	</div>

@endif

@if($errors->has())
	<div class="alert alert-error">
	<button class="close" data-dismiss="alert">&times;</button>
	<div>
	<ul>
		@foreach($errors->all('<li>:message</li>') as $eachError)

		{{ $eachError }}

		@endforeach
	</div>
	</ul>	
	</div>

@endif

<div class="container">

	<legend><h1 class="">New Customer</h1></legend>
	<div class="span6" style="border: ; margin-left: 300px;"> 
	{{ Form::open(['url'=>'facelift/add-customer','class'=>'form-horizontal']) }}
	<fieldset><h1 class=""><small>Customer Info</small> </h1>
				{{ Form::hidden('date', strftime('%Y-%m-%d',strtotime('today')), ['class'=>'input-medium date','style'=>'height: 30px;'])}}
		<div class="control-group">
			<div class="control-label">
				{{Form::label('client_type','Client Type')}}
			</div>
			<div class="controls">
				{{ Form::select('client_type',[''=>'none','walk-in'=>'Walk In','referral'=>'Referral'], Input::old('client_type',''), ['class'=>'input-medium','style'=>'height: 30px;'])}}
			</div>
		</div>

				{{ Form::hidden('client_id', rand(1000,4000), ['class'=>'input-medium','style'=>'height: 30px;'])}}

		<div class="control-group">
			<div class="control-label">
				{{Form::label('name','Client Name')}}
			</div>
			<div class="controls">
				{{ Form::text('name', Input::old('name'), ['class'=>'input-medium','style'=>'height: 30px;'])}}
			</div>
		</div>

		<div class="control-group">
			<div class="control-label">
				{{Form::label('sex','Gender')}}
			</div>
			<div class="controls">
				{{ Form::select('sex', [''=>'none','male'=>'Male','female'=>'Female'], Input::old('sex',''), ['class'=>'input-medium','style'=>'height: 30px;'])}}
			</div>
		</div>
		
		
			<div class="control-group">
				<div class="control-label">
					{{Form::label('houseNo','House Number')}}
				</div>
				<div class="controls">
					{{ Form::text('houseNo', Input::old('houseNo'), ['class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('street','Street')}}
				</div>
				<div class="controls">
					{{ Form::text('street', Input::old('street'), ['class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('location','Location')}}
				</div>
				<div class="controls">
					{{ Form::text('location', Input::old('location'), ['class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('city','City')}}
				</div>
				<div class="controls">
					{{ Form::text('city', Input::old('city'), ['class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('religion','Religion')}}
				</div>
				<div class="controls">
					{{ Form::select('religion',[''=>'none','islam'=>'Islam','christianity'=>'Christianity'], Input::old('religion',''), ['class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('status','Marital Status')}}
				</div>
				<div class="controls">
					{{ Form::select('status',[''=>'none','single'=>'Single','married'=>'Married','divorced'=>'Divorced'], Input::old('status',''), ['class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('anniversary','Wedding Anniverary')}}
				</div>
				<div class="controls">
					{{ Form::text('anniversary', Input::old('anniversary'), ['class'=>'input-medium date','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('DOB','Date of Birth')}}
				</div>
				<div class="controls">
					{{ Form::text('DOB', Input::old('DOB'), ['class'=>'input-medium date','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('occupation','Occupation')}}
				</div>
				<div class="controls">
					{{ Form::text('occupation', Input::old('occupation'), ['class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('phone_no','Phone Number')}}
				</div>
				<div class="controls">
					{{ Form::text('phone_no', Input::old('phone_no'), ['class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('email','Email')}}
				</div>
				<div class="controls">
					{{ Form::email('email', Input::old('email'), ['class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>

		</fieldset>

		
		<fieldset>
			<legend><h4>Other Info</h4></legend>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('flk','Facelift Knowledge')}}
				</div>
				<div class="controls">
					{{ Form::textarea('flk', Input::old('flk'), ['class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
		</fieldset>
		<div class="control-group">
				<div class="control-label">
					{{Form::label('referee','Referee')}}
				</div>
				<div class="controls">
					{{ Form::text('referee', Input::old('referee'), ['class'=>'input-medium','style'=>'height: 30px;'])}}
				</div>
			</div>
		</fieldset>

		

		{{ Form::submit('Save', ['class'=>'pull-right btn btn-large btn-primary'])}}
	{{ Form::close() }}
	</div>
</div>

@stop