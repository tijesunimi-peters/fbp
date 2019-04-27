<html>
	<head>
		<title>
		
		@if($report['all'] == 1)
		{{$report['cat']}}
		@else
		{{$report['cat']}}
		@endif
		- Facelift Beauty Palace Report
		</title>
		{{HTML::style('css/bootstrap.css')}}
		{{HTML::style('css/bootstrap-responsive.css')}}
		{{HTML::style('css/style.css')}}
	</head>
	<body>
		
		<div class="container">
			<div class="row">
				<div class="span12">
					<div class="row">
						<div class="span12">
							<div class="heading">
								<div class="well well-small">
									<h1>
										@if(isset($report['cat']))
											{{$report['cat']}}
										@endif
									</h1>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="span12">
							<div class="date">
								<h3 style="text-weight: normal">
									@if(isset($report['cat']))
									@if(isset($report['from']) && isset($report['to']))
										{{"From ".$report['from']." to ".$report['to'] }}
									@else 
										<?php  $fromdate = $report['fromyear'].'-'.$report['frommonth']; $todate = $report['toyear'].'-'.$report['tomonth']; ?>
										{{ ' From '.strftime('%B, %Y',strtotime($fromdate)).' to '.strftime('%B, %Y',strtotime($todate)) }}
									@endif
								@endif
								</h3>
								
							</div>
						</div>
					</div>
					
					
				</div>
			</div>
			<div class="row">
				<div class="span12">
					@yield('content')
				</div>
			</div>
			
			
		</div>
		<footer style="margin: 50px;">
			<div class="text-center well">Facelift Beauty Palace @ {{strftime('%Y',strtotime('today'))}}</div>
		</footer>
	</body>
</html>