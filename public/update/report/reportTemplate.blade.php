@extends('facelift.report.layout.main')

<?php  $date = $report['year'].'-'.$report['month'];  
		$strf = '%Y-%m';
		$frommonth = $report['frommonth'];
		$fromyear = $report['fromyear'];
		$tomonth = $report['tomonth'];
		$toyear = $report['toyear'];
?>
@section('content')


<div class="container">
<?php  $c = 1;  ?>
{{---------------------------------Staff Commissions----------------------------------------------------------------------}}
@if($report['type'] == 'staff_commission')
	<table class="table table-striped" style="max-width: 1000px; margin: 0 auto; border:1px solid #ececec">
	
	<thead>
		<td>
			S/N
		</td>
		<th>
			Staff Name
		</th>
		<th>
			Commissions
		</th>
		<th>
			Total
		</th>
	</thead>
	<?php $s = FaceliftStaff::find($staff[3]->staff_id)->commission()->get(); ?>
	
	@if($report['type'] == 'staff_commission')

		@foreach($staff as $eachStaff) 

		<?php   
			//$commissions = '';
			if($report['all'] != 1) {
			$commissions = FaceliftStaff::find($eachStaff->staff_id)->commission()->whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();
		} else {
			$commissions = FaceliftStaff::find($eachStaff->staff_id)->commission()->get();
		}
			//dd($commissions);
			$comTotal = array();
		?>
			<tr>
				<td>
					{{$c}}
				</td>
				<td>
					{{ucfirst($eachStaff->name)}}
				</td>
				<td>
				@if(count($commissions) > 0)
					<table class="table">
						<thead>
							<th>
								Date
							</th>
							<th>
								Commission
							</th>
						</thead>
						@foreach($commissions as $commission)
						
						
						<?php $comTotal[] = $commission->total;  ?>
						<tr>
							<td>{{ strftime('%d-%m-%Y',strtotime($commission->created_at)) }}</td>
							<td> {{number_format($commission->total) }} </td>
						</tr>
						
						
						@endforeach
					</table>

					@else 
					{{ 'No Commissions' }}
					@endif
				</td>
				<td>
					{{number_format(array_sum($comTotal))}}
				</td>
			</tr>
			<?php $c++; ?>
		@endforeach
	@endif
	</table>
{{---------------------------------------Staff Services--------------------------------------------------------}}
@elseif($report['type'] == 'staff_services')

	<table class="table table-striped" style="font-size: 14px; border:1px solid #ececec;">
		<thead>
			<th>
				S/N
			</th>
			<th>
				Staff Name
			</th>
			<th>
				Services
			</th>
			<th>
				Total Amount
			</th>
		</thead>
		<?php $wT = array(); ?>
		@foreach($staff as $eachStaff)
		<?php
			if($report['all'] == 1 ) {

				$services = FaceliftStaff::find($eachStaff->staff_id)->services;

			}else {

				$services = FaceliftStaff::find($eachStaff->staff_id)->services()->whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();

			}
	   		$serviceTotal = array();

			?>
			@if(count($services) >= 1)
				<tr>
					<td> {{$c}} </td>
					<td> {{$eachStaff->name}} </td>
					<td>
						@if($services)
							<table class="table" style="font-size: 14px;">
							<th>Date</th>
							<th>Service Type</th>
								@foreach($services as $eS)
									<tr>
									<?php $serviceTotal[] = $eS->amount;  ?>
										<td>
											{{strftime('%d-%m-%Y %H:%M',strtotime($eS->created_at))}}
										</td>
										<td>
											{{ preg_replace('/; /','<br />',$eS->service_type) }}
										</td>
									</tr>
								@endforeach
							</table>
						@endif
					</td>
					<td><?php $wT[] = array_sum($serviceTotal); ?>
						{{number_format(array_sum($serviceTotal))}}
					</td>
				</tr>
				<?php $c++; ?>
			@endif
			
		@endforeach
	</table>
	<div class="row">
	<div class="pull-right">
			<h4 class="span2">Total: </h4>
			<h4 class="span2">{{number_format(array_sum($wT))}}</h4>
	</div>
	</div>
{{-------------------------------Staff Referrals-------------------------------------------------}}
@elseif($report['type'] == 'staff_referrals') 
	<table class="table table-striped" style="border:1px solid #ececec">
		<tr><div class="span3">
			<h4>{{'LifeTime Staff Referrals: '.count($referrals)}} </h4>
		</div></tr>
		<th>
			S/N
		</th>
		<th>
			Staff Name
		</th>
		<th>
			Referrals
		</th>
		<th>
			Total Referral per Staff
		</th>
		
		@foreach($staff as $eachStaff)
		<?php $refCount = array(); ?>
		<?php  
			if($report['all'] != 1) {
				
			$referrals = FaceliftReferrals::whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();
			

		
		} 
		$chkRef = FaceliftStaff::find($eachStaff->staff_id)->referral()->get();

		?>
		@if(count($chkRef) >= 1)
			<tr>
				<td>{{$c}}</td>
				<td>{{ucfirst($eachStaff->name)}}</td>
				<td>
				<table class="table">
								<th>
									Date
								</th>
								<th>
									Refered
								</th>
					@foreach($referrals as $eachRef)

						@if($eachRef->staff_id == $eachStaff->staff_id)
							<?php  $refCount[] = $eachRef->refered; ?>
								<tr>
									<td>
										{{strftime('%a %d-%m-%Y',strtotime($eachRef->created_at))}}
									</td>
									<td>
										{{$eachRef->refered}}
									</td>
								</tr>
							

						@endif
					@endforeach
					</table>
				</td>
				<td>
					{{count($refCount)}}
				</td>
			</tr>
			<?php $c++;?>
			@endif
		@endforeach
	</table>
{{-----------------------------------Client Referrals-----------------------------------------}}
@elseif($report['type'] == 'client_referrals') 
	
	<table class="table table-striped" style="border:1px solid #ececec">
		<tr><div class="span3">
			<h4>{{'All Time Client Referrals: '.count($referrals)}} </h4>
		</div></tr>
		<th>
			S/N
		</th>
		<th>
			Client Name
		</th>
		<th>
			Referrals
		</th>
		<th>
			Total Referral per Client
		</th>
		
		@foreach($clients as $eachClient)
		<?php $refCount = array(); ?>
		<?php if($report['all'] != 1) {
				
			$referrals = FaceliftReferrals::whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();

		
		} 
			$cliRef = FaceliftCustomers::find($eachClient->client_id)->referrals()->get();
			//dd($cliRef);
		?>
		
			<tr>
				<td>{{$c}}</td>
				<td>{{ucfirst($eachClient->client_name)}}</td>
				<td>
				<table class="table">
								<th>
									Date
								</th>
								<th>
									Refered
								</th>
					@foreach($referrals as $eachRef)

						@if($eachRef->client_id == $eachClient->client_id)
							<?php  $refCount[] = $eachRef->refered; ?>
								<tr>
									<td>
										{{strftime('%a %d-%m-%Y',strtotime($eachRef->created_at))}}
									</td>
									<td>
										{{$eachRef->refered}}
									</td>
								</tr>
							

						@endif
					@endforeach
					</table>
				</td>
				<td>
					{{count($refCount)}}
				</td>
			</tr>
			<?php $c++;?>
		
		@endforeach
	</table>
{{------------------------------------Gender Breakdown--------------------------------------------------}}	
@elseif($report['type'] == 'gender_breakdown')
	<table class="table table-striped" style="border:1px solid #ececec">
	<tr>
	<h3 class="text-center">
	Male Clients
	</h3>
	</tr>
		<th>S/N</th>
		<th>Client Id</th>
		<th>Client Name</th>
		<th>Phone No</th>
		<th>Email</th>
		@foreach($clients as $eachClient)
			@if($eachClient->gender == 'male')
				<tr>
					<td>{{$c}}</td>
					<td>{{$eachClient->client_id}}</td>
					<td>{{$eachClient->client_name}}</td>
					<td>{{$eachClient->phone_no}}</td>
					<td>{{$eachClient->email}}</td>
				</tr>
				<?php $c++; ?>
			@endif
	
		@endforeach
	</table>
	<table class="table table-striped" style="border:1px solid #ececec">
		<tr><h3 class="text-center">Female Clients</h3></tr>
			<th>S/N</th>
			<th>Client Id</th>
			<th>Client Name</th>
			<th>Phone Number</th>
			<th>Email</th>
			<?php $d = 1; ?>
			@foreach($clients as $eachClient)
				@if($eachClient->gender == 'female')
					<tr>
						<td>{{$d}}</td>
						<td>{{$eachClient->client_id}}</td>
						<td>{{$eachClient->client_name}}</td>
						<td>{{$eachClient->phone_no}}</td>
						<td>{{$eachClient->email}}</td>
					</tr>
					<?php $d++; ?>
				@endif
		
			@endforeach
	</table>

{{---------------------------------Birthdays-------------------------------------------------------}}
@elseif($report['type'] == 'birthdays')
	<table class="table table-striped" style="border:1px solid #ececec">
		<th>S/N</th>
		<th>Client Name </th>
		<th>Birthday</th>
		<th>Phone Numbers</th>
		<th>Email</th>
		@foreach($clients as $eachClient)
			
				<tr>
					<td>{{$c}}</td>
					<td>{{$eachClient->client_name}}</td>
					<td>{{strftime('%d'.'th, '.'%B',strtotime($eachClient->DOB))}}</td>
					<td>{{$eachClient->phone_no}}</td>
				</tr>
			
			<?php $c++; ?>
		@endforeach
	</table>
{{----------------------------------wedding Anniversaries--------------------------------------------}}
@elseif($report['type'] == 'wedding_anniversaries')
	<table class="table table-striped" style="border:1px solid #ececec">
		<th>S/N</th>
		<th>Client Name </th>
		<th>Wedding Anniversary</th>
		<th>Phone Numbers</th>
		@foreach($clients as $eachClient)
				<tr>
					<td>{{$c}}</td>
					<td>{{$eachClient->client_name}}</td>
					<td>{{strftime('%d'.'th, '.'%B',strtotime($eachClient->wedding_anniversary))}}</td>
					<td>{{$eachClient->phone_no}}</td>
				</tr>
		<?php $c++; ?>
		@endforeach
	</table>
{{----------------------------------Client Service History-------------------------------------------}}
@elseif($report['type'] == 'client_history')
	<table class="table table-striped" style="font-size: 14px; border:1px solid #ececec;">
		<thead>
			<th>
				S/N
			</th>
			<th>
				Client Name
			</th>
			<th>
				Services
			</th>
			<th>
				Total Amount
			</th>
		</thead>
		<?php $wholeTotal = array(); ?>
		@foreach($clients as $eachC) 
		
		<?php

			//$eachClient = FaceliftCustomers::find($eachC->client_id);
		if($report['all'] == 1) {
	   		$services = FaceliftCustomers::find($eachC->client_id)->services;
	   	} else {
	   		$services = FaceliftCustomers::find($eachC->client_id)->services()->whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->orderBy('year','DESC')->orderBy('month','DESC')->get();
	   	}
	   		$serviceTotal = array();

			?>
			@if(count($services) >= 1)
			
				<tr>
					<td> {{$c}} </td>
					<td> {{$eachC->client_name}} </td>
					<td>
						
							<table class="table" style="font-size: 14px;">
							<th>Date</th>
							<th>Service Type</th>
								@foreach($services as $serve)
									
									<tr>
									<?php $serviceTotal[] = $serve->amount;  ?>
										<td>
											{{strftime('%d-%m-%Y %H:%M',strtotime($serve->created_at))}}
										</td>
										<td>
											{{ preg_replace('/; /','<br />',$serve->service_type) }}
										</td>
									</tr>
									@endforeach
							</table>
						
					</td>
					<td><?php $wholeTotal[] = array_sum($serviceTotal); ?>
						{{number_format(array_sum($serviceTotal))}}
					</td>
				</tr>
				<?php $c++; ?>
			@endif

			
			
		@endforeach
		
	</table>
	<div class="row">
	<div class="pull-right">
			<h4 class="span2">Total: </h4>
			<h4 class="span2">{{number_format(array_sum($wholeTotal))}}</h4>
	</div>
	</div>

{{---------------------------------Sales Report----------------------------------------------------------}}
@elseif($report['type'] == 'sales_report')

	<table class="table table-striped" style="border:1px solid #ececec">
		<th>S/N</th>
		<th>Date</th>
		<th>Client</th>
		<th>Service</th>
		<th>Time Start</th>
		<th>Time End</th>
		<th>Attendant</th>
		<th>Arrival</th>
		<th>Dept.</th>
		<th>Amount</th>
		<?php $amntArray = array(); ?>
		@foreach($allServices as $eachService)
		<?php 
		$client = FaceliftCustomers::find($eachService->client_id);  
		?>
			
				<?php $amntArray[] = $eachService->amount;?>
				<tr>
					<td>{{$c}}</td>
					<td>{{strftime('%d-%m-%Y',strtotime($eachService->created_at))}}</td>
					<td>{{$client->client_name}}</td>
					<td>{{preg_replace('/; /','<br />',$eachService->service_type)}}</td>
					<td>{{$eachService->service_start}}</td>
					<td>{{$eachService->service_end}}</td>
					<td>{{$eachService->attendant}}</td>
					<td>{{$eachService->arrival}}</td>
					<td>{{$eachService->departure}}</td>
					<td>{{number_format($eachService->amount)}}</td>
					
					
				</tr>
			
			<?php $c++; ?>
		@endforeach
		<div class="span10" style="border: 1px solid #ececec">
			<div class="">
			<h4 class="span2">Total Sum:</h4>
			<h4 class="span4">{{number_format(array_sum($amntArray))}}</h4>
			</div>
		</div>

	</table>

{{---------------------------------Profit n Loss--------------------------------------------------------------}}
@elseif($report['type'] == 'profit_loss')

	<table class="table table-striped" style="border: 1px solid #ececec">
	<th>
	Sales
</th>
<th>
	Expenses
</th>
<th>
	Profit/Loss
</th>

	
		<?php $expenses = array(); $sales = array(); ?>

		<tr>
		<td>
		<table class="table">
			<th>
				Date
			</th>
			<th>
				Service
			</th>
			<th>
				Amount
			</th>
		
		@foreach ($allServices as $eachService) 
			<?php $sales[] = $eachService->amount ?>
			<tr>
				<td>
					{{strftime('%Y-%m-%d',strtotime($eachService->created_at))}}
				</td>
				<td>
					{{preg_replace('/; /','<br />',$eachService->service_type)}}
				</td>
				<td>
					{{number_format($eachService->amount)}}
				</td>
			</tr>
		@endforeach
			</table>
			</td>
		<td>
		<table class="table">
			<th>
				Date
			</th>
			<th>
				Expense
			</th>
			<th>
				Amount
			</th>
			
				@foreach($allExpenses as $eachExpense) 
			<?php $expenses[] = $eachExpense->amount; ?>
				<tr>
				<td>
					{{strftime('%Y-%m-%d',strtotime($eachExpense->created_at))}}
				</td>
				<td><?php $budCat = FaceliftBudgetCategories::find($eachExpense->category_id); ?>
					{{$budCat['category']}}
				</td>
				<td>
					{{number_format($eachExpense->amount)}}
				</td>
					
				</tr>

			@endforeach
			</table>
			</td>
		</tr>
		
		
		<tr>
		<th>
			Total Sales
		</th>
		<th>
			Total Expenses
		</th>
		<th>
		@if((array_sum($sales) - array_sum($expenses)) < 0)
			{{'Loss'}}
		@else 
		{{'Profit'}}
		@endif
		</th>
		<tr>
			<td>
				{{number_format(array_sum($sales))}}
			</td>
			<td>
				{{number_format(array_sum($expenses))}}
			</td>
			<td>
				{{number_format(array_sum($sales) - array_sum($expenses))}}
			</td>
			</tr>
		</tr>
	</table>
{{-----------------------------------Pre Booked Dates------------------------------------------------}}
@elseif($report['type'] == 'pre_booked_dates')
	<table class="table table-striped" style="border:1px solid #ececec">
		<th>S/N</th>
		<th>Client</th>
		<th>Pre-Booked Date</th>
		<?php   $filterNames = array(); $filterBooks = array(); ?>
		@foreach($allServices as $eachService)
		<?php $client = FaceliftCustomers::find($eachService->client_id); $filter = ''; ?>
			
				<?php   
						$filterNames[] = $client->client_name.'-'.$eachService->rebooked_date; 
						$filterBooks[] = $eachService->rebooked_date;
						
						$filter = array_combine($filterNames, $filterBooks);
				?>
			
			
			
		@endforeach
		
		@if(!empty($filter))
		@foreach($filter as $name => $eachBook)

			<tr>
				<td>{{$c}}</td>
				<td> {{ucfirst(substr($name, 0,-11))}} </td>
				<td> {{strftime('%a %d, %b %Y',strtotime($eachBook))}} </td>
			</tr>
			<?php $c++; ?>
		
		@endforeach
		@else 
		{{'No Pre-Booked Dates'}}
		@endif
	</table>
{{--------------------------------------Phone Numbers---------------------------------------------}}
@elseif($report['type'] == 'phone_numbers')
<table class="table table-striped" style="border:1px solid #ececec">
	<th>S/N</th>
	<th>Client Name </th>
	<th>Phone Numbers</th>
	@foreach($clients as $eachClient)
			<tr>
				<td>{{$c}}</td>
				<td>{{$eachClient->client_name}}</td>
				<!--<td>{{strftime('%d'.'th, '.'%B',strtotime($eachClient->wedding_anniversary))}}</td>-->
				<td>{{$eachClient->phone_no}}</td>
			</tr>
		
<?php $c++; ?>
	@endforeach
</table>
{{----------------------------------------Emails--------------------------------------}}
@elseif($report['type'] == 'emails')
<table class="table table-striped" style="border:1px solid #ececec">
	<th>S/N</th>
	<th>Client Name </th>
	<th>Emails</th>
	@foreach($clients as $eachClient)
			<tr>
				<td>{{$c}}</td>
				<td>{{$eachClient->client_name}}</td>
				<td>{{$eachClient->email}}</td>
			</tr>
		
<?php $c++; ?>
	@endforeach
</table>

{{------------------------------------------loyalty Points------------------------------------}}
@elseif($report['type'] == 'loyalty_points')

<?PHP $lpA = array() ?>
@foreach($lp as $eachlp) 
	<?php $lpA[] = $eachlp->client_id ?>

@endforeach

<table class="table table-striped" style="border:1px solid #ececec">
<th>
	S/N
</th>
<th >
	Today
</th>
<th>
	Client Name
</th>
<th>
	Loyalty Points
</th>
<th>
	Goal
</th>

<?php  $uniqueLpa = array_unique($lpA); $c = 1;?>

@foreach($clients as $eachC)
	<?php 

			if($report['all'] == 1)
			{ $allLp = FaceliftCustomers::find($eachC->client_id)->loyalty_points()->orderBy('created_at','DESC')->get(); }
			else {
				$allLp = FaceliftCustomers::find($eachC->client_id)->loyalty_points()->whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();
			}
			//$client = FaceliftCustomers::find($eachLoyP); 
			$lpArray = array();
	

			?>

	@if(count($allLp) >= 1)

	<tr>
	<td>
		{{$c}}
	</td>
	<td>
		{{strftime('%d-%m-%Y',strtotime('today'))}}
	</td>
		<td>
			{{$eachC->client_name}}
		</td>
		<td>
			
			@foreach($allLp as $oneLp)
				<?php $lpArray[] = $oneLp->loyalty_points; ?>
				
			@endforeach

			{{ number_format(array_sum($lpArray)) }}
			
		</td>
		<td>
			@if(array_sum($lpArray) == 50000 || is_int(array_sum($lpArray)/50000))
				{{'<span style="text-decoration: blink; color: red;">'.number_format(50000).'points Reached</span>'}}
			@else 
			{{'Goal not Reached'}}
			@endif
		</td>
	</tr>
<?php $c++; ?>
@endif
@endforeach

</table>


{{---------------------------------------------Clients Indormation------------------------------------------}}
@elseif($report['type'] == 'client_info')
	<table class="table table-striped" style="border:1px solid #ececec">
	<th>S/N</th>
	<th>Client Id Number</th>
	<th>Client Name</th>
	<th>Gender</th>
	<th>Address</th>
	<th>Religion</th>
	<th>Status</th>
	<th>Wedding<br />Anniversary</th>
	<th>Occupation</th>
	<th>DOB</th>
	<th>Phone</th>
	<th>Email</th>
	<th>Date Added</th>
	<th>Last Service</th>
	<th>Mode</th>

	@foreach($clients as $eachClient)
	<?php 	$lastService = FaceliftServices::where('client_id','=',$eachClient->client_id)
			->orderBy('created_at','DESC')
			->select('created_at')
			->first(); 

			$t = strtotime('+2months') - strtotime('today');
			$lstDate = strtotime($lastService['created_at']);
			//dd($lstDate);
			$d = $lstDate + $t; ?>

			<tr>
				<td>{{$c}}</td>
				<td>{{$eachClient->client_id}}</td>
				<td>{{$eachClient->client_name}}</td>
				<td> @if(!empty($eachClient->gender))
					@if($eachClient->gender == 'male')
						{{'M'}}
					@elseif($eachClient->gender == 'female') 
						{{'F'}}
					@else 
					{{ 'NILL'}}
					@endif
				@else 
				{{ 'NILL' }}
				@endif
				</td>
				<td> @if(!empty($eachClient->houseNo) && !empty($eachClient->street) && !empty($eachClient->city) && !empty($eachClient->location))
				{{$eachClient->houseNo.', '.$eachClient->street.' '.$eachClient->city.' '.$eachClient->location}}
				@else 
				{{ 'NILL' }}
				@endif
				</td>
				<td> @if(!empty($eachClient->religion))
				{{$eachClient->religion}}
				@else 
				{{ 'NILL' }}
				@endif</td>
				<td> @if(!empty($eachClient->status))
				 {{$eachClient->status}}
				 @else 
				{{ 'NILL' }}
				@endif
				</td>
				<td> @if(!empty($eachClient->wedding_anniversary) && strftime('%Y-%m-%d',strtotime($eachClient->wedding_anniversary)) != strftime('%Y-%m-%d',strtotime('0000-00-00')) && $eachClient->status == 'married')
				{{strftime('%d'.'th, '.'%B',strtotime($eachClient->wedding_anniversary))}}
				@else 
				{{ 'NILL' }}
				@endif
				</td>
				<td> @if(!empty($eachClient->occupation))
				{{$eachClient->occupation}}
				@else 
				{{ 'NILL' }}
				@endif
				</td>
				<td> @if(!empty($eachClient->DOB) && strftime('%Y-%m-%d',strtotime($eachClient->DOB)) != strftime('%Y-%m-%d',strtotime('0000-00-00')))
				{{strftime('%d'.'th, '.'%B',strtotime($eachClient->DOB))}}
				@else 
				{{ 'NILL' }}
				@endif
				</td>
				<td> @if(!empty($eachClient->phone_no))
				{{$eachClient->phone_no}}
				@else 
				{{ 'NILL' }}
				@endif
				</td>
				<td> @if(!empty($eachClient->email))
				{{$eachClient->email}}
				@else 
				{{ 'NILL' }}
				@endif
				</td>
				<td> @if(!empty($eachClient->created_at) && strftime('%Y-%m-%d',strtotime($eachClient->created_at)) != strftime('%Y-%m-%d',strtotime('0000-00-00')))
				{{strftime('%d'.'th, '.'%b %Y',strtotime($eachClient->created_at))}}
				@else 
				{{ 'NILL' }}
				@endif
				</td>
				<td>
					@if($lastService['created_at'])
				{{strftime('%d,%m %Y',strtotime($lastService['created_at']))}}
				@else
				{{'NILL'}}
				@endif
				</td>
				<td>
					
			@if(strtotime('today') >= $d && !empty($lastService['created_at']))
				{{'<span style="color: rgb(150,10,10);">Not Active</span>'}}
			@elseif($lastService['created_at'] == '')
				{{'No Service Yet'}}
			@else 
				{{'<span style="color: rgb(10,150,10);">Active</span>'}}
			@endif
				</td>
			</tr>
		
<?php $c++; ?>
	@endforeach
</table>
{{-------------------------------------------Expense Control Report-----------------------------------------}}
@elseif($report['type'] == 'expense_control')
<table class="table table-striped" style="border: 1px solid #ececec">
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
	<?php   $budgetCol = FaceliftBudgetCategories::find($eachCat->category_id)->budgets()->whereBetween('month',[$report['frommonth'],$report['tomonth']])->whereBetween('year',[$report['fromyear'],$report['toyear']])->orderBy('created_at','DESC')->first();  
			//dd($budgetCol);
			$expenseCol = FaceliftBudgetCategories::find($eachCat->category_id)->expenses()->whereBetween('month',[$report['frommonth'],$report['tomonth']])->whereBetween('year',[$report['fromyear'],$report['toyear']])->orderBy('created_at','DESC')->get();
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
	Total Expenses: {{' '.number_format(array_sum($totalExpense))}}
</div>
<div class="span3">
	Total Balance: {{' '.number_format(array_sum($totalBal))}}
</div>

</div>


@endif
</div>
@stop