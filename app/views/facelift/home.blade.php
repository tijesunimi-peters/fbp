@extends('facelift.layouts.main')

@section('content')
@if(Session::has('info'))
	<div class="alert alert-info">
	<button class="close" data-dismiss='alert'>&times;</button>
		{{Session::get('info')}}
	</div>
@endif
<div class="container" style="">
<table class="table span9" style="background-color: rgba(0,0,0,0.7);color: white; ">
	
	<td class="span2" style="padding-left: 10px;">
	<h1 >
		{{'User: '.ucfirst(Auth::user()->username)}} 
	</h1>
	</td>
		<td class="span2" style=""> <h1><small style="color: white;">User Type: @if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {{ ' Admin'}}
				@else {{' Staff'}}
				@endif </small></h1>
		</td>
	
	
	
	<td style="" class="span2">
		<h1> <small style="color: white;">{{ 'Today: '.strftime('%d, %B %Y',strtotime('today')) }}</small></h1>
	</td>
	
</table>
</div>

<?php  $monthServices = array(); $yearServices=array();?>
@foreach($serviceAll as $service)
	
		@if(strftime("%m-%Y",strtotime($service->created_at)) == strftime('%m-%Y',strtotime('today')))
			<?php $monthServices[] = $service->amount; ?>

		@endif

		@if(strftime('%Y',strtotime($service->created_at)) == strftime('%Y',strtotime('this year')))
			<?php $yearServices[] = $service->amount; ?>
		@endif
@endforeach

<div class="row">
<div class="span3 profileTabs" style="background-color:rgb(52,73,94);">
<h4>
		Service Summary
	
</h4>
<table class="table ">

	<tr>
		<td>
			{{'Last Service: '.HTML::link('#','see more', ['class'=>'btn btn-small btn-info'])}}
		</td>
		<td>
			{{$lastService->amount.' ('.'By: '.ucfirst($lastService->attendant).')'}}
		</td>
	</tr>
	<tr>
		<td>
			{{ strftime("%B",strtotime('today')).' Services: '}}
		</td>
		<td>
			{{count($monthServices)}}
		</td>
	</tr>
	<tr>
		<td>
			{{'Services this Year: '}}
		</td>
		<td>
			{{count($yearServices)}}
		</td>
	</tr>
	<tr>
		<td>
			{{'Lifetime Services: '}}
		</td>
		<td>
			{{$services}}
		</td>
	</tr>
	
</table>	
	
</div>
<div class="span3 profileTabs" style="background-color:rgb(44,62,80)">
<h4>
	Customers Summary
</h4>
<table class="table">
	<tr>
		<td>
			{{'Total Customers: '}}
		</td>
		<td>
			{{$customers}}
		</td>

	</tr>
	<tr>
		<td>
			Top Clients
		</td>
		<td>
			coming soon
		</td>
	</tr>
</table>
	
</div>
<div class="span3 profileTabs" style="background-color:rgb(149,165,166)">
	<h4 style="color: black;">Sales Report</h4>
	<table class="table">
	<tr>
		<td>
			Total Sales this Month: 
		</td>
		<td>
			{{'#'.array_sum($monthServices)}}
		</td>
		</tr>
		<tr>
			<td>
				Year Sales:
			</td>
			<td>
				{{'#'.array_sum($yearServices)}}
			</td>
		</tr>
		<tr>
			<td>
				Lifetime Sales:
			</td>
			<td>
				#0000000
			</td>
		</tr>
	</table>
</div>
<div class="span3 profileTabs" style="background-color:rgb(127,140,141); overflow: scroll-y;">
	<h4>Upcoming Birthdays<small>{{' '.HTML::link('#', 'View All',['class'=>'btn btn-small btn-info'])}}</small></h4>
	<?php $dob = array(); ?>
	@foreach($allCustomers as $customer) 
		@if(strftime('%Y:%m:%d',strtotime($customer->DOB)) >= strftime('%Y:%m:%d',strtotime('today')))
			<?php $dob[] = array('name'=>$customer->client_name, 'dob'=> $customer->DOB)?>
		@endif
	@endforeach
	<table class="table">
		
		@for($v = 0; $v <= 3;$v++)
			<tr>
				<td>{{ ucfirst($dob[$v]['name']) }}</td>
				<td>{{ $dob[$v]['dob'] }}</td>
			</tr>
		@endfor
	</table>
</div>
<div class="span3 profileTabs" style="background-color:rgb(155,89,182)">
	<h4>Upcoming Anniversaries<small>{{' '.HTML::link('#', 'View All', ['class'=>'btn btn-small btn-info'])}}</small></h4>
	<?php $ann = array(); ?>
	@foreach($WA as $customer) 
		@if(strftime('%Y:%m:%d',strtotime($customer->wedding_anniversary)) >= strftime('%Y:%m:%d',strtotime('today')))
			<?php $ann[] = array('name'=>$customer->client_name, 'ann'=> $customer->wedding_anniversary)?>
		@endif
	@endforeach
	<table class="table">
		@for($s = 0; $s < count($ann);$s++)
			<tr>
				<td>{{ ucfirst($ann[$s]['name']) }}</td>
				<td>{{ $ann[$s]['ann'] }}</td>
			</tr>
			@if($s == 3)
				<?php break; ?>
			@endif
		@endfor
	</table>
</div>
<div class="span3 profileTabs" style="background-color:rgb(142,68,173)">
	<h4>Pre-booked Service Dates</h4>
	<?php $rd = array(); ?>
	@foreach($RD as $customer) 
		<?php $name = FaceliftCustomers::find($customer->client_id); ?>
		@if(strftime('%Y:%m:%d',strtotime($customer->rebooked_date)) >= strftime('%Y:%m:%d',strtotime('today')))
			<?php $rd[] = array('name'=>$name->client_name, 'rd'=> $customer->rebooked_date)?>
		@endif
	@endforeach
	<table class="table">
		@for($d = 0; $d < count($rd);$d++)
			<tr>
				<td>{{ ucfirst($rd[$d]['name']) }}</td>
				<td>{{ $rd[$d]['rd'] }}</td>
			</tr>
			@if($d == 3)
				<?php break; ?>
			@endif
		@endfor
	</table>
</div>
<div class="span3 profileTabs" style="background-color:rgb(192,57,43)">
	Referrals
</div>
<div class="span3 profileTabs" style="background-color:rgb(231,76,60)">
	Budget and expenditure Report
</div>
<div class="span3 profileTabs" style="background-color:rgb(211,84,0)">
	Miscellaneous
</div>
	
</div> {{--- end for row ---}}

@stop