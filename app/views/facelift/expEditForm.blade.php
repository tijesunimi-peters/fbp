@extends('facelift.layouts.main')

@section('content')

<div class="container">
<div class="span9">
	<h1>
		Expenses Control Edit
	</h1>
</div>


<div class="span10">
{{Form::model($expense,['url'=>'','class'=>'form-horizontal'])}}


				<div class="control-group">
				<div class="control-label">{{Form::label('particulars','Particulars')}}</div>
				<div class="controls">
					{{Form::text('particulars',Input::old('particulars'))}}
				</div>
				</div>

				<div class="control-group">
				<div class="control-label">{{Form::label('amount','Amount')}}</div>
				<div class="controls">
					{{Form::text('amount',Input::old('amount'))}}
				</div>
			</div>

				<div class="control-group">
				<div class="control-label">{{Form::label('staff','Staff Involved')}}</div>
				<div class="controls">
					{{Form::text('staff',Input::old('staff'))}}
				</div>
			</div>

				<div class="control-group">
				<div class="control-label">{{Form::label('approval','Approval')}}</div>
				<div class="controls">
					{{Form::text('approval',Input::old('approval'))}}
				</div>
			</div>


			<div class="control-group">
				<div class="control-label">{{Form::label('user-unit','User Unit')}}</div>
				<div class="controls">
					{{Form::text('user_unit',Input::old('user_unit'))}}
				</div>
			</div>

			{{Form::submit('Save',['class'=>'pull-right btn btn-large btn-info'])}}

			</div>

{{Form::close()}}
</div>


</div>

@stop