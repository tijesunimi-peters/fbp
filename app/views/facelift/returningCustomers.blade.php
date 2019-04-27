@extends('facelift.layouts.main')

@section('content')

@if($errors->has())
<div class="alert alert-error">
<button class="close" data-dismiss="alert">&times;</button>
<ul>
@foreach($errors->all('<li>:message</li>') as $eachError)
{{ $eachError }}

@endforeach
</ul>
</div>

@endif

<!--<div class="span10">
<div class="well well-large">
<h1 class="">New Service</h1>


	<div class="span4">Returning Customers 
	{{ Form::open(['url'=>'facelift/service','class'=>'form-inline', 'method' => 'post', 'style'=>'']) }}

		{{ Form::label('client-id','Client Id No') }}
			
				{{ Form::text('client_id',Input::old('client_id'),['class'=>'input-medium','style'=>'height: 30px;'] ) }}
			

	{{Form::submit('Find',['class'=>'btn btn-primary'])}}
	
	{{ Form::close() }}
	</div>

</div>
</div>-->
<div class="well well-large">
<h1>
	New Service
</h1>
<table class="table">
<thead>
	<th>
		New Customer
	</th>
	<th>
		Returning Customer
	</th>
</thead>
	<tr>
		<td>
			{{HTML::link('facelift/add-customer', 'New Customer', ['class'=>'btn btn-large btn-primary'])}}

		</td>
		<td>
			{{ Form::open(['url'=>'facelift/service','class'=>'form-inline', 'method' => 'post', 'style'=>'']) }}

		{{ Form::label('client-name','Client Name') }}
			
				{{ Form::text('client_name',Input::old('client_name'),['class'=>'input-medium','style'=>'height: 30px;'] ) }}
			

	{{Form::submit('Find',['class'=>'btn btn-primary'])}}
	
	{{ Form::close() }}
		</td>
	</tr>
</table>
</div>

@stop