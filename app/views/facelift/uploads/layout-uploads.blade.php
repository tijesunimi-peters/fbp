@extends('facelift.layouts.main')
@section('content')
@if(Session::has('info'))
<div class="row">
	<div class="span12">
		<div class="alert alert-info">
			<button class="close" data-dismiss='alert'>&times;</button>
			{{Session::get('info')}}
		</div>
	</div>
	

@endif
@if(Session::has('error'))
<div class="span12">
		<div class="alert alert-danger">
			<button class="close" data-dismiss='alert'>&times;</button>
			<?php $c = 1; ?>
			@foreach (Session::get('error') as $key) 
				@if(!empty($key))
					@if(is_array($key))
						@foreach ($key as $value) 
							{{($c)}} - {{$value}} <br />
							<?php $c++; ?>
						@endforeach
					@else
						{{($c)}} - {{$key}} <br />
					@endif

				@endif
				<?php $c++; ?>
			@endforeach
		</div>
	</div>
@endif
@if(Session::has('success'))
<div class="span12">
		<div class="alert alert-success">
			<button class="close" data-dismiss='alert'>&times;</button>
			<?php $c = 1; ?>
				@foreach (Session::get('success') as $key) 
					@if(!empty($key))
						@if(is_array($key))
							@foreach ($key as $value) 
								{{($c)}} - {{$value}} <br />
								<?php $c++; ?>
							@endforeach
						@else
							{{($c)}} - {{$key}} <br />
						@endif
					@endif
					<?php $c++; ?>
				@endforeach
		</div>
	</div>
@endif
</div>
<div class="container">
	<div class="row">
		<div class="span12">
			<div>
				<h1>Upload Section</h1>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<div >
				@yield('uploadPageTitle')
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<div>
				@yield('formUpload')
			</div>
		</div>
	</div>
</div>
@stop