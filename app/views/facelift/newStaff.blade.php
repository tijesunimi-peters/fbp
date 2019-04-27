@extends('facelift.layouts.main')

@section('content')


<div class="span6">
	{{Form::open(['class'=>'form-horizontal'])}}
	<legend>
		<h1>
	Add New Staff
</h1>
@if(Session::has('info'))
	<div class="alert alert-info">
	<button class="close" data-dismiss='alert'>&times;</button>
		{{Session::get('info')}}
	</div>
@endif
	</legend>
	@if($errors->has())

		<div class="alert alert-error">
		<button class="close" data-dismiss="alert">
			&times;
		</button>
		<ul>
		@foreach($errors->all('<li>:message</li>') as $eachError)
			{{$eachError}}

		@endforeach
		</div> 


	@endif

	@if(Session::has('info')) 
		<div class="alert">
			<button class="close" data-dismiss="alert">
				&times;
			</button>
			{{Session::get('info')}}
		</div>

	@endif
		{{ Form::hidden('staff_id',rand(1000,3000),['class'=>'input-medium','style'=>'height: 30px'])}}

		<div class="control-group">
			<div class="control-label">
				{{Form::label('name', 'Staff Name')}}
			</div>
			<div class="controls">
				{{Form::text('name',Input::old('name'), ['class'=>'input-medium'])}}
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				{{Form::label('address', 'Address')}}
			</div>
			<div class="controls">
				{{Form::textarea('address',Input::old('address'), ['class'=>'input-medium','style'=>'height: 50px'])}}
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				{{Form::label('phone_no', 'Phone Number')}}
			</div>
			<div class="controls">
				{{Form::text('phone_no',Input::old('phone_no'), ['class'=>'input-medium'])}}
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				{{Form::label('email', 'Email')}}
			</div>
			<div class="controls">
				{{Form::email('email',Input::old('email'), ['class'=>'input-medium'])}}
			</div>
		</div>
		{{Form::submit('Add Staff',['class'=>'pull-right btn btn-info btn-large'])}}

	{{Form::close()}}
</div>

@stop