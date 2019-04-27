@extends('facelift.uploads.layout-uploads')
@section('uploadPageTitle') <h3>Expenses Upload</h3> @stop
@section('formUpload')
<div class="container">
	<div class="row">
		<div class="span12">
			<div>
				<p>
					The file for upload must be in CSV format
				</p>
			</div>
		</div>
	</div>
</div>

<div class="span6">
	{{Form::open(['files'=>true,'method'=>'POST','class'=>'form-horizontal'])}}
	<div class="control-group">
		<div class="control-label">
			{{Form::label('file', 'File-Upload: ')}}
		</div>
		<div class="controls">
			{{Form::file('incomeCsv',['class'=>'file-upload'])}}
		</div>
	</div>
	<div class="pull-right">
		{{Form::submit('Upload', ['class'=>'btn btn-large btn-info'])}}
	</div>
	
	{{Form::close()}}
</div>
@stop