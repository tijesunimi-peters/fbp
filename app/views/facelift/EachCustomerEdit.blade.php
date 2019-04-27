


{{Form::model($customer, ['class'=>'form-horizontal','id'=>'customerEditForm','name'=>$customer->client_id])}}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Editing: {{ucfirst($customer->client_name).' - '.$customer->client_id}}</h3>
    </div>
 <div id='bodyModal' class="modal-body">

<div id="postReport">
	
</div>

<div class="control-group">
	<div class="control-label">
		{{Form::label('client_name','Client Name')}}
	</div>
	<div class="controls">
		{{Form::text('client_name',Input::old('client_name'),['class'=>'input-medium','style'=>'height: 30px'])}}
	</div>

</div>
	<div class="control-group">
	<div class="control-label">
		{{Form::label('gender','Sex')}}
	</div>
	<div class="controls">
		{{Form::select('gender',[''=>'none','female'=>'Female','male'=>'Male'],Input::old('gender'),['class'=>'input-medium','style'=>'height: 30px'])}}
	</div>
	
</div>

<div class="control-group">
	<div class="control-label">
		{{Form::label('houseNo','House Number')}}
	</div>
	<div class="controls">
		{{Form::text('houseNo',Input::old('houseNo'),['class'=>'input-medium','style'=>'height: 30px'])}}
	</div>
	
</div>

<div class="control-group">
	<div class="control-label">
		{{Form::label('street','Street')}}
	</div>
	<div class="controls">
		{{Form::text('street',Input::old('street'),['class'=>'input-medium','style'=>'height: 30px'])}}
	</div>
	
</div>

<div class="control-group">
	<div class="control-label">
		{{Form::label('location','Location')}}
	</div>
	<div class="controls">
		{{Form::text('location',Input::old('location'),['class'=>'input-medium','style'=>'height: 30px'])}}
	</div>
	
</div>

<div class="control-group">
	<div class="control-label">
		{{Form::label('city','City')}}
	</div>
	<div class="controls">
		{{Form::text('city',Input::old('city'),['class'=>'input-medium','style'=>'height: 30px'])}}
	</div>
	
</div>

<div class="control-group">
	<div class="control-label">
		{{Form::label('religion','Religion')}}
	</div>
	<div class="controls">
		{{Form::select('religion',[''=>'none','islam'=>'Islam','christianity'=>'Christianity','others'=>'Others'],Input::old('religion'),['class'=>'input-medium','style'=>'height: 30px'])}}
	</div>
	
</div>

<div class="control-group">
	<div class="control-label">
		{{Form::label('status','Status')}}
	</div>
	<div class="controls">
		{{Form::select('status',[''=>'none','single'=>'Single','married'=>'Married','divorced'=>'Divorced'],Input::old('status'),['class'=>'input-medium','style'=>'height: 30px'])}}
	</div>
	
</div>

<div class="control-group">
	<div class="control-label">
		{{Form::label('wedding_anniversary','Wedding Anniversary')}}
	</div>
	<div class="controls">
		{{Form::text('wedding_anniversary',Input::old('wedding_anniversary'),['class'=>'input-medium date','style'=>'height: 30px'])}}
	</div>
	
</div>

<div class="control-group">
	<div class="control-label">
		{{Form::label('DOB','Date of Birth')}}
	</div>
	<div class="controls">
		{{Form::text('DOB',Input::old('DOB'),['class'=>'input-medium date','style'=>'height: 30px'])}}
	</div>
	
</div>

<div class="control-group">
	<div class="control-label">
		{{Form::label('occupation','Occupation')}}
	</div>
	<div class="controls">
		{{Form::text('occupation',Input::old('occupation'),['class'=>'input-medium','style'=>'height: 30px'])}}
	</div>
	
</div>

<div class="control-group">
	<div class="control-label">
		{{Form::label('phone_no','Phone Number')}}
	</div>
	<div class="controls">
		{{Form::text('phone_no',Input::old('phone_no'),['class'=>'input-medium','style'=>'height: 30px'])}}
	</div>
	
</div>

<div class="control-group">
	<div class="control-label">
		{{Form::label('email','Email')}}
	</div>
	<div class="controls">
		{{Form::text('email',Input::old('email'),['class'=>'input-medium','style'=>'height: 30px'])}}
	</div>
	
</div>



	
</div>


<div class="modal-footer">
	<a href="#" class="close btn btn-danger" data-dismiss='modal'>Cancel</a>
	{{Form::submit('Save', ['class'=>'pull-right btn btn-info'])}}
	
</div>

{{Form::close()}}

<script type="text/javascript">
	$('#customerEditForm').submit(function(e) {
		
		var fields = $('#customerEditForm').serialize();
		$('#postReport').html('Loading...');
		$.post('/index.php/facelift/edit-customer/'+this.name,fields, function(data) {

			$('#postReport').html(data);
			
			setTimeout(function() {
				location.reload();
			}, 500);
			return false;
		});
		return false;
	});
</script>

<script type="text/javascript">
		$('.date').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});

</script>
{{ HTML::script('js/jquery-1.10.2.js') }}
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
		{{ HTML::script('js/jquery.ui.core.js') }}
		{{ HTML::script('js/jquery.ui.position.js') }}
		{{ HTML::script('js/jquery.ui.widget.js') }}
		{{ HTML::script('js/jquery.ui.menu.js') }}
		{{ HTML::script('js/jquery.ui.datepicker.js') }}
		{{ HTML::script('js/jquery.ui.autocomplete.js') }}









