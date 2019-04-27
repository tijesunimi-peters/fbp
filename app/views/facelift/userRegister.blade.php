@extends('facelift.layouts.main')


@section('content')

@if($errors->has())

<div class="alert alert-error">
	<button class="close" data-dismiss='alert'>&times;</button>

	<ul>
	@foreach($errors->all('<li>:message</li>') as $eachError)
		{{ $eachError }}
	@endforeach
	</ul>
</div>

@endif

@if(Session::has('report'))

<div class="alert alert-success">
	<button class="close" data-dismiss='alert'>&times;</button>
	{{ Session::get('report') }}
</div>

@endif

<div>
	{{Form::open(['class'=>'form-horizontal'])}}
	<legend><h1>Add User</h1></legend>
	<div class="control-group">
	<div class="control-label">
		{{ Form::label('username','User Name')}}
	</div>
	<div class="controls">
		{{Form::text('username',Input::old('username'), ['placeholder'=>'Username'])}}
	</div>

	</div>

	<div class="control-group">
	<div class="control-label">
		{{ Form::label('password','Password')}}
	</div>
	<div class="controls">
		{{Form::password('password',NULL, ['placeholder'=>'Password'])}}
	</div>

	</div>

	<div class="control-group">
	<div class="control-label">
		{{ Form::label('repeat_password','Repeat Password')}}
	</div>
	<div class="controls">
		{{Form::password('repeat_password',NULL, ['placeholder'=>'repeat Password'])}}
	</div>

	</div>

	<div class="control-group">
		<div class="control-label">
			{{Form::label('admin','Admin?: ')}}
		</div>

		<div class="controls">
		{{Form::checkbox('admin', 'true', Input::old('admin'))}} &nbsp;  Yes

		</div>
		
	</div>

	<div class="control-group">
			<div class="control-label">
				{{Form::label('super_admin','Super Admin?: ')}}
			</div>

			<div class="controls">
			{{Form::checkbox('super_admin', 'true', Input::old('super_admin'))}} &nbsp;  Yes

			</div>
			
		</div>




{{Form::submit('Add User',['class'=>'btn btn-large btn-info pull-right'])}}
</div>


@stop