@extends('facelift.layouts.main')

@section('content')

<div class="container">
<div class="span9">
	<h1>
		Expenses Control Edit
	</h1>
</div>


<div class="span7">
{{Form::open(['url'=>'facelift/edit-expenses','class'=>"form-horizontal"])}}
	<div class="control-group">
				<div class="control-label">{{Form::label('type','Expenditure Type')}}</div>
				<div class="controls">
					{{Form::select('type',[
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
{{Form::submit('EDIT',['class'=>'btn btn-large btn-primary pull-right'])}}
{{Form::close()}}
</div>
</div>
@stop