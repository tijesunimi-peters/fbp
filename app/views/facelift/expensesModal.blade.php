{{Form::model($expense, ['method'=>'post','class'=>'form-horizontal','url'=>'facelift/edit-expenses/'.$id])}}
	<div class="modal-header">
		<h1>
			Expense Edit
		</h1>
	</div>
	<div class="modal-body">
			<div class="control-group">
				<div class="control-label">
					<label>Particulars</label>
				</div>
				<div class="controls">
					{{Form::text('particulars')}}
				</div>
			</div>
			<div class="control-group">
							<div class="control-label">
								<label>Staff</label>
							</div>
							<div class="controls">
								{{Form::text('staff')}}
							</div>
						</div>
			<div class="control-group">
							<div class="control-label">
								<label>Approval</label>
							</div>
							<div class="controls">
								{{Form::text('approval')}}
							</div>
						</div>
			<div class="control-group">
							<div class="control-label">
								<label>Amount</label>
							</div>
							<div class="controls">
								{{Form::input('number','amount')}}
							</div>
						</div>
			<div class="control-group">
							<div class="control-label">
								<label>Unit</label>
							</div>
							<div class="controls">
								{{Form::text('user_unit')}}
							</div>
						</div>



	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-large btn-primary">Submit</button>

	</div>

{{Form::close()}}