@extends('facelift.layouts.main')

@section('content')

@if(Session::has('info'))
	<div class="alert alert-info">
	<button class="close" data-dismiss='alert'>&times;</button>
		{{Session::get('info')}}
	</div>
@endif

<div class="container" style="margin-top: 0px;">

<table class="table" style="background-color: rgba(0,0,0,0.7);color: white; ">
	
	<td class="span2" style="padding-left: 10px;">
	<h1 >
		{{ 'User: '.ucfirst(Auth::user()->username) }} 
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

<?php  $monthServices = array(); $yearServices=array(); $allServicesAmount = array();?>
@foreach($serviceAll as $service)
	
		@if(strftime("%m-%Y",strtotime($service->created_at)) == strftime('%m-%Y',strtotime('today')))
			<?php $monthServices[] = $service->amount; ?>

		@endif

		@if(strftime('%Y',strtotime($service->created_at)) == strftime('%Y',strtotime('this year')))
			<?php $yearServices[] = $service->amount; ?>
		@endif

		<?php $allServicesAmount[] = $service->amount; ?>
@endforeach

<div class="" style="padding-left: 100px;">
<div class="span3 profileTabs" style="background-color:rgb(52,73,94);">
<h4>
		Service Summary
	
</h4>
<table class="table ">

	<tr>
		<td>
			{{'Last Service: '.HTML::link('facelift/all-services','see more', ['class'=>'btn btn-small btn-info'])}}
		</td>
		<td>
			{{number_format($lastService->amount).' ('.'By: '.ucfirst($lastService->attendant).')'}}
		</td>
	</tr>
	<tr>
		<td>
			{{ strftime("%B",strtotime('today')).' Services: '}}
		</td>
		<td>
			{{number_format(count($monthServices))}}
		</td>
	</tr>
	<tr>
		<td>
			{{'Services this Year: '}}
		</td>
		<td>
			{{number_format(count($yearServices))}}
		</td>
	</tr>
	<tr>
		<td>
			{{'Lifetime Services: '}}
		</td>
		<td>
			{{number_format($services)}}
		</td>
	</tr>
	
</table>	
	
</div>
<div class="span3 profileTabs" style="background-color:rgb(44,62,80)">
<h4>
	Customers Summary
</h4>
<table class="table">
<?php 
		$day = strftime('%w');
		$formatS = '%Y-%m-%d 00:00:00';
		$formatE = '%Y-%m-%d 23:59:59';
		$rawWeekStart = strtotime('-'.$day.' days');
		$rawWeekEnd = strtotime('+'.(6 - $day).' days');
		$rawDayStart = strtotime('midnight today');
		$rawDayEnd = strtotime('midnight tomorrow');
		
		$wkStart = strftime($formatS,strtotime('-'.$day.' days'));
		$wkEnd = strftime($formatE,strtotime('+'.(6 - $day).' days'));
		// $wkStart = strftime($formatS,$rawWeekStart);
		// $wkEnd = strftime($formatE,$rawWeekEnd);

		$today = strftime($formatS,strtotime('today'));
		$endToday = strftime($formatE,strtotime('today'));


		$todayServices = FaceliftServices::whereBetween('created_at',[$today,$endToday])->groupBy('client_id')->get();
		$weekServices = FaceliftServices::whereBetween('created_at',[$wkStart,$wkEnd])->groupBy('client_id')->get();
		$t = ['newCustWeek'=>0,'retCustWeek'=>0,'newCustDay'=>0,'retCustDay'=>0];


		$newCustWeek = 0;
		$retCustWk = 0;
		$newCustDay = 0;
		$retCustDay = 0;
		//$client = [];

		/*For returning clients for the week there registeration must be older than the week 
		and that is all in the services that i got for the week

		*/
		if($weekServices) {
			for ($i=0; $i < count($weekServices); $i++) { 
				$client = FaceliftCustomers::find($weekServices[$i]->client_id);
				if(strtotime($client->created_at) < $rawWeekStart) {
					$t['retCustWeek'] = $retCustWk++;
				} else {
					$t['newCustWeek'] = $newCustWeek++;
				}

			}

		}

		
		if($todayServices) {
			for ($i=0; $i < count($todayServices); $i++) { 
				$client = FaceliftCustomers::find($todayServices[$i]->client_id);
				if(strtotime($client_id->created_at) < $rawDayStart) {
					$t['retCustDay'] = $retCustDay++;
				} else {
					$t['newCustDay'] = $newCustDay++;
				}
			}
		}


 ?>
	<tr>
		<td>
			{{'Total Customers: '}}
		</td>
		<td>
			{{number_format($customers)}}
		</td>

	</tr>
	<tr>
		<td>
			{{'New Cust. of Today '}}
		</td>
		<td>
			{{ number_format($t['newCustDay']) }}
		</td>

	</tr>
	<tr>
		<td>
			{{'Ret Cust. of Today '}}
		</td>
		<td>
			{{ number_format($t['retCustDay']) }}
		</td>

	</tr>
	<tr>
		<td>
			{{'New Cust. from : '.strftime('%Y-%m-%d',strtotime($wkStart))}}
		</td>
		<td>
			{{number_format($t['newCustWeek'])}}
		</td>

	</tr>
	<tr>
		<td>
			{{'Ret Cust. from : '.strftime('%Y-%m-%d',strtotime($wkStart))}}
		</td>
		<td>
			{{ number_format($t['retCustWeek']) }}
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
			{{'#'.number_format(array_sum($monthServices))}}
		</td>
		</tr>
		<tr>
			<td>
				Year Sales:
			</td>
			<td>
				{{'#'.number_format(array_sum($yearServices))}}
			</td>
		</tr>
		<tr>
			<td>
				Lifetime Sales:
			</td>
			<td>
				{{'#'.number_format(array_sum($allServicesAmount))}}
			</td>
		</tr>
	</table>
</div>
<div class="span3 profileTabs" style="background-color:rgb(127,140,141); overflow: scroll-y;">
	<h4>Upcoming Birthdays</h4>
	<?php $dob = array(); ?>
	@foreach($allCustomers as $customer) 
		@if(strftime('%Y:%m:%d',strtotime($customer->DOB)) >= strftime('%Y:%m:%d',strtotime('today')))
			<?php $dob[] = array('name'=>$customer->client_name, 'dob'=> $customer->DOB)?>
		@endif
	@endforeach
	<table class="table">
		
	@if(!empty($dob))

		@for($v = 0; $v <= count($dob);$v++)
			@if($v >= count($dob)) 
				<?php break; ?>
			@endif
			<tr>
				<td> 
				@if($dob[$v]['name']) 
				{{ ucfirst($dob[$v]['name']) }} 
				@endif


				</td>

				<td> 
				@if($dob[$v]['name']) 
				{{ $dob[$v]['dob'] }} 
				@endif 
				</td>
			</tr>
			@if($v == 3)
				<?php break; ?>
			@endif
			
		@endfor
	@else 
	{{'No Record of Birthdays for this Month'}}
	@endif
	</table>
</div>
<div class="span3 profileTabs" style="background-color:rgb(155,89,182)">
	<h4>Upcoming Anniversaries</h4>
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



	
</div> {{--- end for row ---}}

@stop