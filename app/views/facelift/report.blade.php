@extends('facelift.layouts.main')
@section('content')
<div class="container" style="border: ;">
	<h1 class="span10" style="border: ;">
	Reports
	</h1>
	<!--<div class="span4 pull-right" style="padding-top: 10px; padding-left: 15px;">
		{{Form::open(['class'=>'form-inline','method'=>'get','style'=>'background-color: #ececec;padding: 10px 0px 10px 15px;'])}}
			{{Form::label('month','Month: ')}}
			{{ Form::selectMonth('month', Strftime('%m',strtotime('today')),['class'=>'input-small']) }}
			{{Form::label('year','Year: ')}}
			{{--Form::selectYear()--}}
			{{ Form::selectRange('year', 2010, 2020, strftime('%Y',strtotime('today')),['class'=>'input-small']) }}
			{{Form::submit('Search',['class'=>'btn btn-small btn-info'])}}
		{{Form::close()}}
	</div>-->
	
</div>
<div class="modal hide fade" style="padding: 20px;" id="modal">
</div>
<div class="span10" style="">
	<div class="span3 btn-group" style='border: ;'>
		{{HTML::link('facelift/add-customer','New Customer',['class'=>'btn btn-info'])}}
		{{HTML::link('facelift/all-services','All Services',['class'=>'btn btn-info'])}}
	</div>
	<div>
		{{ Form::open(['url'=>'facelift/service','class'=>'form-inline span3', 'method' => 'post', 'style'=>'']) }}
		{{ Form::label('client_name','New Service') }}
		&nbsp;
		{{ Form::text('client_name',Input::old('client_name'),['placeholder'=>'Client Name','class'=>'input-small','style'=>'height: 20px;'] ) }}
		
		{{Form::submit('Find',['class'=>'btn btn-primary'])}}
		
		{{ Form::close() }}
	</div>
	<div class="span3 pull-right" style='border: ;'>{{Form::open(['class'=>'form-search'])}}
		<div class="input-append">
			{{Form::text('search_query', NULL,['class'=>'input-medium','placeholder'=>'Customer Search'])}}
			{{Form::button('Search', ['class'=>'btn btn-info'])}}
		</div>
	</div>
	{{Form::close() }}
</div>
<div class="container">
	<div class="span10">
		@if(Session::has('info'))
		<div class="alert alert-info">
			<button class="close" data-dismiss='alert'>&times;</button>
			{{Session::get('info')}}
		</div>
		@endif
		@if($errors->has())
		<div class="alert alert-error" style="">
			<button class="close" data-dismiss="alert">&times;</button>
			<ul>
				@foreach($errors->all('<li>:message</li>') as $eachError)
				{{ $eachError }}
				@endforeach
			</ul>
		</div>
		@endif
		@if(Session::has('bad-report'))
		<div class="alert alert-error" style="">
			<button class="close" data-dismiss="alert">&times;</button>
			<ul>
				{{ Session::get('bad-report') }}
			</ul>
		</div>
		@endif
		@if(Session::has('report'))
		<div class="alert alert-success" style="">
			<button class="close" data-dismiss="alert">&times;</button>
			<ul>
				{{ Session::get('report') }}
			</ul>
		</div>
		@endif
	</div>
	<div class="tabbable span10">
		<ul class="nav nav-tabs">
			
			<li class="active"><a href="#generate_control" data-toggle="tab">Generate and Print Report</a></li>
			<li ><a href="#email" data-toggle="tab">Mail Report/Backup</a></li>
			<li ><a href="#back-up" data-toggle="tab">Download Backup</a></li>
			<li ><a href="#todaysReport" data-toggle="tab">Today's Report</a></li>
		</ul>
		<div class="tab-content">
			<!-- <div class="tab-pane active" id="generate_weekly">
				<div class="row-fluid">
					<div class="span12">
						<h1>Weekly Report</h1>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						{{ Form::open(['url' => 'facelift/weekly-report', 'method' => 'post', 'class'=>'form-horizontal']) }}

							<div class="row-fluid">
								<div class="span12">
									<button class="btn btn-success btn-large">Generate Report</button>
								</div>
							</div>
						
						{{ Form::close() }}
					</div>
				</div>
			</div> -->
			
			<div class="tab-pane active" id="generate_control">
				<p>
					<h1>Generate Report</h1><hr />
					{{ Form::open(['url'=>'facelift/report/','class'=>'form-horizontal span6']) }}
					{{Form::hidden('expense_control','true')}}
					<div class="control-group">
						<div class="control-label">
							{{Form::label('type','Report Section: ')}}
						</div>
						<div class="controls">
							{{Form::select('type',[
							'staff_commission'=>'Staff Commission',
							'staff_services'=>'Staff Services',
							'staff_referrals'=>'Staff Referrals',
							'client_referrals'=>'Client Referrals',
							'gender_breakdown'=>'Gender Breakdown',
							'birthdays'=>'Birthdays',
							'wedding_anniversaries'=>'Wedding Anniversaries',
							'client_history'=>'Client History',
							'sales_report'=>'Sales Report',
							'profit_loss'=>'Income and Expenditure Report',
							'phone_numbers'=>'Phone Numbers',
							'emails'=>'Emails',
							'expense_control'=>'Expenditure Control Report',
							'loyalty_points'=>'Loyalty Points',
							'client_info'=>'Clients Info',
							'pre_booked_dates'=>'Pre-Booked Dates'
							],Input::old('type'),['id'=>'typeSel'])}}
						</div>
						
					</div>
					<div class="control-group" id="allCont">
						<div class="control-label">
							{{Form::label('all','Generate All: ')}}
						</div>
						<div class="controls">
							{{Form::checkbox('all', '1')}}
						</div>
					</div>
					<div id="weekDate">
						<div class="control-group">
						<div class="control-label">
							<label>From Date: </label>
						</div>
						<div class="controls">
							{{Form::input('text','fromDate',strftime('%Y-%m-%d',strtotime('-1week')), ['class'=>'date'])}}
						</div>
					</div>
						<div class="control-group">
							<div class="control-label">
								<label>To Date: </label>
							</div>
							<div class="controls">
								{{Form::input('text','toDate',strftime('%Y-%m-%d',strtotime('today')),['class'=>'date'])}}
							</div>
						</div>
					</div>
					
					<div class="control-group " id="week4IE" >
						<div class="control-label">
							{{Form::label('week','Generate Week: ')}}
						</div>
						<div class="controls">
							{{Form::checkbox('week', '1')}}
						</div>
					</div>
					<div id="monthCont">
						<legend>From Month and Year</legend>
					<div class="control-group">
						<div class="control-label"> {{Form::label('month','Month: ')}} </div>
						<div class="controls">
							{{Form::selectMonth('frommonth',strftime('%m',strtotime('today')))}}
						</div>
					</div>
					<div class="control-group">
						<div class="control-label"> {{Form::label('year','Year: ')}} </div>
						<div class="controls">
							{{Form::selectRange('fromyear',2010,2030,strftime('%Y',strtotime('today')))}}
						</div>
					</div>
					<legend>
						To Month and Year
					</legend>
					<div class="control-group">
						<div class="control-label"> {{Form::label('month','Month: ')}} </div>
						<div class="controls">
							{{Form::selectMonth('tomonth',strftime('%m',strtotime('today')))}}
						</div>
					</div>
					<div class="control-group">
						<div class="control-label"> {{Form::label('year','Year: ')}} </div>
						<div class="controls">
							{{Form::selectRange('toyear',2010,2030,strftime('%Y',strtotime('today')))}}
						</div>
					</div>
					</div>
					
					{{Form::submit('Find',['class'=>'input-medium btn btn-large btn-primary pull-right'])}}
					{{Form::close()}}
				</p>
			</div>
			<div class="tab-pane" id="email">
				<div class="span6">
					{{Form::open(['url'=>'facelift/send','files'=>'true','class'=>'form-horizontal'])}}
					<div class="control-group">
						<div class="control-label">
							{{Form::label('backup','Attach File')}}
						</div>
						<div class="controls">
							{{Form::file('backup')}}
						</div>
					</div>
					{{Form::submit('Send Mail',['class'=>'btn btn-success btn-large pull-right'])}}
					{{Form::close()}}
				</div>
			</div>
			<div class="tab-pane" id="back-up">
				<div class="span6">
					{{Form::open(['url'=>'facelift/create-backup','class'=>'form-horizontal'])}}
					<div class="control-group">
						<div class="control-label">
							
						</div>
						<div class="controls">
							{{Form::submit('Backup Database',['class'=>'btn btn-success btn-large'])}}
						</div>
					</div>
					
					{{Form::close()}}
				</div>
			</div>
			<div class="tab-pane" id='todaysReport'>
				<div class="span6">
					{{Form::open(['url'=>'facelift/report/','class'=>'form-horizontal'])}}
					{{Form::hidden('type','todaysReport')}}
					
					<div class="control-group">
						<div class="control-label">
							
						</div>
						<div class="controls">
							{{Form::submit('Generate Report',['class'=>'btn btn-success btn-large'])}}
						</div>
					</div>
					
					{{Form::close()}}
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('#week4IE').addClass('dontDisplay');
		$('#weekDate').addClass('dontDisplay');
		$('#allCont').removeClass('dontDisplay');

		$('#typeSel').on('change', function() {
			if(this.value === 'profit_loss') {

				$('#week4IE').removeClass('dontDisplay');
			} else {
				$('#week4IE').addClass('dontDisplay');
				$('#weekDate').addClass('dontDisplay');
				$('#allCont').removeClass('dontDisplay');
				$('#monthCont').removeClass('dontDisplay');
			}
		});

		$('#week').on('change', function() {
			if(this.checked) {
				$('#allCont').addClass('dontDisplay');
				$('#weekDate').removeClass('dontDisplay');
				$('#monthCont').addClass('dontDisplay');
			} else {
				$('#monthCont').removeClass('dontDisplay');
				$('#weekDate').addClass('dontDisplay');
				$('#allCont').removeClass('dontDisplay');
			}
		});

		$('#monthCont').removeClass('dontDisplay');
	</script>
	
</div>
@stop