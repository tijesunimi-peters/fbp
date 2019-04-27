	@if($errors->has())
	<div class="alert alert-error">
		<button class="close" data-dismiss="alert">
			&times;
		</button>
		<ul>
		@foreach($errors->all('<li> :message </li>') as $eachError)

			{{ $eachError }}
		@endforeach
		</ul>
	</div>
	@endif

	



