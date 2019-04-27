
		{{ HTML::style('css/bootstrap.css') }}
		{{ HTML::style('css/bootstrap-responsive.css') }}
		{{ HTML::style('css/style.css') }}
		{{ HTML::script('js/jquery-1.10.2.js') }}

	<div class="span6" style="padding:50px; margin: 110px 0px 0px 345px;border: ; border-radius: 20px; box-shadow: 0px 0px 5px gray;">
		{{Form::open(['class'=>'form-horizontal'])}}
		<legend>
			<h1>
				Facelift User Login
			</h1>
		</legend>
			
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

			@if(Session::has('bad-report'))
		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">&times;</button>
				{{Session::get('bad-report')}}
		</div>
		@endif

		@if(Session::has('report'))
		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">&times;</button>
				{{Session::get('report')}}
		</div>
		@endif

		<div class="control-group">
			<div class="control-label">{{Form::label('username','Username')}}</div>
			<div class="controls">
				{{Form::text('username',Input::old('username'),['class'=>'input-medium','style'=>'height: 30px'])}}
			</div>
		</div>

		<div class="control-group">
			<div class="control-label">{{Form::label('password','Password')}}</div>
			<div class="controls">
				{{ Form::password('password', ['class'=>'input-medium','style'=>'height: 30px']) }}
			</div>
		</div>


{{Form::submit('Login',['class'=>"btn btn-large btn-info pull-right"])}}


	</div>

		{{ HTML::script('js/bootstrap-affix.js') }}
		{{ HTML::script('js/bootstrap-alert.js') }}
		{{ HTML::script('js/bootstrap-button.js') }}
		{{ HTML::script('js/bootstrap-carousel.js') }}
		{{ HTML::script('js/bootstrap-collapse.js') }}
		{{ HTML::script('js/bootstrap-dropdown.js') }}
		{{ HTML::script('js/bootstrap-modal.js') }}
		{{ HTML::script('js/bootstrap-popover.js') }}
		{{ HTML::script('js/bootstrap-scrollspy.js') }}
		{{ HTML::script('js/bootstrap-tab.js') }}
		{{ HTML::script('js/bootstrap-tooltip.js') }}
		{{ HTML::script('js/bootstrap-transition.js') }}
		{{ HTML::script('js/bootstrap-typeahead.js') }}
