@extends('facelift.layouts.main')
<?php  $date = $report['year'].'-'.$report['month'];  
		$strf = '%Y-%m';
?>


@section('content')
	<div class="container">
		<div>
			<h1 class="span6">
				{{strftime('%B, %Y',strtotime($date))}} Expenses
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
		<?php 
			$expCost = array(); ?>

			@if(count($allExpenses) > 0) 
			@foreach($allExpenses as $expense) 
			@if($month[0] != 'All')
				@if($expense->month == strftime('%m',strtotime($month[0]))) 
					<?php $expCost[] = $expense->amount; ?>
				@endif
			@else 
				@if($expense->month == strftime('%m',strtotime('today'))) 
					<?php $expCost[] = $expense->amount; ?>
				@endif
			@endif
			@endforeach
			@endif



		<table class="table table-striped span10 container" style="border: 1px solid #ececec">
	<th>
		Expenses Category
	</th>
	<th>
		Budget
	</th>
	<th>
		Expense Total
	</th>
	<th>
		Balance
	</th>
	
	<?php $totalBudget = array(); $totalExpense = array(); $totalBal = array(); ?>
	@foreach($categories as $eachCat)
	<?php $expT = array(); ?>
	<?php   $budgetCol = FaceliftBudgetCategories::find($eachCat->category_id)->budgets()->where('month','=',strftime('%m',strtotime($date)))->where('year','=',strftime('%Y',strtotime($date)))->orderBy('created_at','DESC')->first();  
			//dd($budgetCol);
			$expenseCol = FaceliftBudgetCategories::find($eachCat->category_id)->expenses()->where('month','=',strftime('%m',strtotime($date)))->where('year','=',strftime('%Y',strtotime($date)))->orderBy('created_at','DESC')->get();
	?>
	<tr>
		<td>
			{{$eachCat->category}}
		</td>
		<td>
			@if($budgetCol['budget'] == NULL)
			{{' - '}}
			@else 
			<?php  $totalBudget[] = $budgetCol['budget']; ?>
			{{number_format($budgetCol['budget'])}}
			@endif
		</td>
		<td>
			@foreach($expenseCol as $expense)
					<?php  $expT[] = $expense['amount']; $totalExpense[] = $expense['amount']; ?>
			@endforeach
			@if(array_sum($expT) == NULL)
			{{' - '}}
			@else 
			{{number_format(array_sum($expT))}}
			@endif
		</td>
		<td><?php  $totalBal[] = $budgetCol['budget'] - array_sum($expT); ?>
			{{number_format($budgetCol['budget'] - array_sum($expT))}}
		</td>
	</tr>

	@endforeach

</table>
<div class="span10 well" style="font-size: 20px">

<div class="span3">
	Total Budget: {{' '.number_format(array_sum($totalBudget))}}
</div>
<div class="span3">
	Total Expenses: {{' '.number_format(array_sum($totalExpense)) }}
</div>
<div class="span3">
	Total Balance: {{' '.number_format(array_sum($totalBal))}}
</div>

</div>
			
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