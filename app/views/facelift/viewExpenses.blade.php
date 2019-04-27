@extends('facelift.layouts.main')
<?php  /*$date = $report['year'].'-'.$report['month'];  
		$strf = '%Y-%m';*/
?>


@section('content')
<div id="expModal"  class="modal hide fade" style="padding: 20px;"></div>
	<div class="container">
		<div>
			<h1 class="span6">
				{{strftime('%B, %Y',strtotime('today'))}} Expenses
			</h1>
			<div class="span4 pull-right" style="padding-top: 10px; padding-left: 15px;">
			<h4>Search Expenses</h4>
	{{Form::open(['class'=>'form-inline','method'=>'get','style'=>'background-color: #ececec;padding: 10px 0px 10px 15px;'])}}
		{{Form::label('month','Month: ')}}
		{{ Form::selectMonth('month', Strftime('%m',strtotime('today')),['class'=>'input-small']) }}
		{{Form::label('year','Year: ')}}
		{{ Form::selectRange('year', 2010, 2020, strftime('%Y',strtotime('today')),['class'=>'input-small']) }}
		{{Form::submit('Search',['class'=>'btn btn-small btn-info'])}}
	{{Form::close()}}
	</div>
	</div>
			<div class="btn-group span10">
				{{HTML::link('facelift/expenses-control', 'New Expense',['class'=>'btn btn-info'])}}
					{{HTML::link('facelift/new-budget', 'New Budget',['class'=>'btn btn-info'])}}	
					{{HTML::link('facelift/all-expenses', 'All Expenses',['class'=>'btn btn-info'])}}	
					{{HTML::link('facelift/view-expenses', 'View Expenses',['class'=>'btn btn-info'])}}	
					{{HTML::link('facelift/view-expenses-by-unit', 'View Expenses by Unit',['class'=>'btn btn-info'])}}	
			</div>
			<div class="span10" style="margin-top: 10px">
			<!-- info -->
		@if(Session::has('info'))
		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">
				&times;
			</button>
			<div>
				{{Session::get('info')}}
			</div>
		</div>
	@endif
	</div>
		</div>
		<div>
		



		<table class="table table-striped span10 container" style="border: 1px solid #ececec">
	<th>
		Date
	</th>
	<th>
		Particulars
	</th>
	<th>
		Staff
	</th>
	<th>
		Approval
	</th>
	<th>Amount</th>
	<th>Unit</th>
	<th>Edit</th>
	@foreach($allExpenses as $exp) 

		<tr>
			<td>
				{{strftime('%Y-%m-%d',strtotime($exp->created_at))}}
			</td>
			<td>
				{{ucfirst($exp->particulars)}}
			</td>
			<td>
				{{ucfirst($exp->staff)}}
			</td>
			<td>
				{{ucfirst($exp->approval)}}
			</td>
			<td>
				{{number_format($exp->amount)}}
			</td>
			<td>
				{{ucfirst($exp->user_unit)}}
			</td>
			<td>
				{{ HTML::link('#expModal', 'edit', ['name'=>$exp->id,'class'=>'expEditBtn btn btn-info btn-small','role'=>'button','data-toggle'=>'modal']) }}
			</td>
		</tr>


	@endforeach


</table>

			
		</div>
	</div>
@stop

@section('scripts')
<script type="text/javascript">
	$('.expEditBtn').click(function() {
		$('#expModal').html('Loading........');

		$.get('/index.php/facelift/edit-expenses/'+this.name,function(data) {
			$('#expModal').html(data);
		});
	});

</script>
@stop