
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<div class="modal-header">
	<h1>{{ 'Deleting: '.$service->client_id }}</h1>
</div>
	
<div class="modal-body">
	<h1>
		<small>
			This is only done by the Admin
		</small>
	</h1>
</div>	

<div class="modal-footer">
	{{ HTML::link('#', 'Close', ['class'=>'close btn btn-small btn-danger','data-dismiss'=>'modal']) }}
</div>