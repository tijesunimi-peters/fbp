@extends('facelift.layouts.main')

@section('content')

<div class="container">
		<div>
			<h1 class="span4">
				New Budget
			</h1>
			<div class="span4 pull-right" style="padding-top: 10px; padding-left: 15px;">
			<h4>Search Expenses</h4>
	{{Form::open(['url'=>'facelift/all-expenses','class'=>'form-inline','method'=>'get','style'=>'background-color: #ececec;padding: 10px 0px 10px 15px;'])}}
		{{Form::label('month','Month: ')}}
		{{ Form::selectMonth('month', Strftime('%m',strtotime('today')),['class'=>'input-small']) }}
		{{Form::label('year','Year: ')}}
		{{--Form::selectYear()--}}
		{{ Form::selectRange('year', 2010, 2020, strftime('%Y',strtotime('today')),['class'=>'input-small']) }}
		{{Form::submit('Search',['class'=>'btn btn-small btn-info'])}}
	{{Form::close()}}
	</div>
	</div>
			<div class="btn-group span10">
				{{HTML::link('facelift/expenses-control', 'New Expense',['class'=>'btn btn-info'])}}
					{{HTML::link('facelift/new-budget', 'New Budget',['class'=>'btn btn-info'])}}	
					{{HTML::link('facelift/all-expenses', 'All Expenses',['class'=>'btn btn-info'])}}
			</div>
		</div>
		<div class="span6">
<!-- errors -->
	@if($errors->has())
	<div class="alert alert-error">
	<button class="close" data-dismiss="alert">&times;</button>
	<ul>
	@foreach ($errors->all('<li>:message</li>') as $eachError) 
	{{$eachError}}
	@endforeach
	</ul>
	</div>
	@endif

	<!-- Success -->
	@if(Session::has('report'))
		<div class="alert alert-success">
			<button class="close" data-dismiss="alert">
				&times;
			</button>
			<div>
				{{Session::get('report')}}
			</div>
		</div>
	@endif

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
		
	<div class="container" style="margin-top: 50px">
		{{Form::open(['class'=>'form-horizontal span6'])}}
		{{Form::hidden('month-year',strftime('%m-%Y',strtotime('today')))}}
			<div class="control-group">
				<div class="control-label">
					{{Form::label('month','Month')}}
				</div>

				<div class="controls">
					{{Form::selectMonth('month',strftime('%m',strtotime('today')))}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('year','Year')}}
				</div>

				<div class="controls">
					{{Form::selectRange('year',2010,2030,strftime('%Y',strtotime('today')))}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('category','Expenditure Category: ')}}
				</div>
				<div class="controls">
					{{Form::select('category',[
									'Staff Cost' => [
									'1'=>'Salaries & Wages',
									'2'=>'Staff Training Fund',
									'3'=>'Staff Incentives',
									'4'=>'Medical Expenses',
									'5'=>'Staff Award Expenses',
									'6'=>'TGIF'
									],
									'Power Generating Expenses'=>[
									'7'=>'Electricity',
									'8'=>'Fuelling of Generator',
									'9'=>'Generator Repairs'
									],
									'Business Promotion'=>['10'=>'Advertisements',
									'11'=>'Biz Promotion',
									'12'=>'Printing & Stationaries',
									'13'=>'PR & Entertainment',
									'14'=>'Corp. Social Responsibility'
									],
									'Salon Inventory & Supplies'=>[
									'15'=>'Salon Supplies',
									'16'=>'Cost of Goods Sold'],
									'Office Utilities & Supplies'=>[
									'17'=>'Telephone Bill',
									'18'=>'Office Maintenance',
									'19'=>'Supplies[toiletries,Cway]',
									'20'=>'Satelite Tv Subs',
									'21'=>'Cleaning Expenses',
									'22'=>'Internet/IT Consumables',
									'23'=>'Newspaper & Magazines'
									],
									'Tax & Depreciation'=>[
									'24'=>'Taxes',
									'25'=>'Depreciation'
									],
									'Miscellaneous'=>[
									'26'=>'Training Materials',
									'27'=>'Insurance',
									'28'=>'Transport & Travelling',
									'29'=>'Security Expenses',
									'30'=>'Bank Charges',
									'31'=>'Industry Association Fee',
									'32'=>'Others',
									'33'=>'Director\'s WithDrawal',
									'34'=>'Rent',
									'35'=>'Loan Repayment'
									]
									])}}
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					{{Form::label('budget','Budget Provision')}}
				</div>

				<div class="controls">
					{{Form::text('budget',Input::old('budget'))}}
				</div>
			</div>

			{{Form::submit('Save',['class'=>'btn btn-large btn-info pull-right'])}}
		{{Form::close()}}
	</div>


@stop