<!doctype html>
<html>
<head>
	<title>Facelift Beauty Palace App</title>
		{{ HTML::style('css/jquery.ui.datepicker.css') }}
		{{ HTML::style('css/jquery.ui.autocomplete.css') }}
		{{ HTML::style('uikit/css/uikit.css') }}
		{{ HTML::style('css/bootstrap.css') }}
		{{ HTML::style('css/bootstrap-responsive.css') }}
		{{ HTML::style('css/style.css') }}
		{{ HTML::script('js/jquery-1.10.2.js') }}

		<style type="text/css">
		a {
			color: rgb(189,195,199);
		}


		</style>
</head>
<body>

	
	<div class="" style="padding: ;">
	<div class="row" style="background-color: rgb(0,0,0);">
	<header class="container" style="background-color: rgb(0,0,0); padding: 10px" name="12,57,43">
	<div class="span6">
		<div class="span1" style="height:70px;">
				{{ HTML::image('img/fl-logo.jpg', 'facelift logo',['style'=>'height: 70px']) }}
			</div> <br />
		<h3 class="" style="color: whitesmoke; margin-left: 110px">
			 Facelift Beauty Palace 
		</h3>
	</div>
	<div class="span5 pull-right" style="padding: 10px; margin-right: 0px; color: white;">
			<div class="span2">
				@if(Session::has('user')) 
				{{ 'Hello '.ucfirst(Session::get('user')) }}
				@else 
					{{ "Not Logged In" }}
				@endif
			{{' '.HTML::link('facelift/user-logout', 'Log Out',['class'=>'btn btn-info btn-mini']) }}
			</div>

			<div class="span2"> {{ 'Today: '.strftime('%d, %B %Y',strtotime('today')) }}
			</div>
		</div>
		
		<nav class='span10'> 
			<ul class="nav navbar nav-pills">
				<li>{{HTML::link('facelift/index', 'Home')}}</li>
				<li class="dropdown">{{HTML::link('facelift/customers', 'Customers',['class'=>'dropdown-toggle','id'=>'customersDropdown','role'=>'button','data-toggle'=>'dropdown'])}}
				<ul class="dropdown-menu" role='menu' aria-labelledby='customersDropdown'>
					<li>{{HTML::link('facelift/customers', 'Customers Directory')}}</li>
					<li>{{HTML::link('facelift/add-customer', 'Add Customer')}}</li>

									<li>{{HTML::link('facelift/loyalty-points', 'Loyalty Points')}}</li>

				</ul>
				</li>
				


				<li class="dropdown">{{HTML::link('facelift/all-services','Service',['class'=>'dropdown-toggle','id'=>'serviceDd','role'=>'button','data-toggle'=>'dropdown'])}}

				<ul class="dropdown-menu" role="menu" aria-lebelledby='serviceDd'>
					<li>{{HTML::link('facelift/all-services', 'Service History')}}</li>
					<li>{{HTML::link('facelift/new-service', 'New Service')}}</li>
				</ul>
				</li>
				


				<li class="dropdown">{{HTML::link('facelift/all-referrals','Referrals',['class'=>'dropdown-toggle','id'=>'referralsDd','role'=>'button','data-toggle'=>'dropdown'])}}

				<ul class="dropdown-menu" role="menu" aria-labelledby='referralsDd'>
					<li>
						{{HTML::link('facelift/all-referrals', 'All Referrals')}}
					</li>
					<li>
						{{HTML::link('facelift/all-referrals/staff', 'Staff Referrals')}}
					</li>
					<li>
						{{HTML::link('facelift/all-referrals/clients', 'Customer Referrals')}}
					</li>
					
				</ul>

				</li>
				<li class="dropdown">

					{{HTML::link('#', 'Staff', ['class'=>'dropdown-toggle','id'=>'staffDd','role'=>'button','data-toggle'=>'dropdown'])}}
					<ul class="dropdown-menu" role='menu' aria-labelledby='staffDd'>
						<li>
							{{HTML::link('facelift/new-staff', 'New Staff')}}
						</li>
						<li>
							{{HTML::link('facelift/all-staff', 'Manage Staff')}}
						</li>
						<li>
							{{HTML::link('facelift/staff-commissions', 'Staff Commissions')}}
						</li>
					</ul>
				</li>


				<li class="dropdown">{{HTML::link('facelift/users','Users', ['class'=>'dropdown-toggle','id'=>'usersDd','role'=>'button','data-toggle'=>'dropdown'])}}

				<ul class="dropdown-menu" role='menu' aria-labelledby='usersDd'>
					<li>
						{{HTML::link('facelift/all-users', 'Manage Users')}}
					</li>
					<li>
						{{HTML::link('facelift/user-registration', 'Add Users')}}
					</li>
				</ul>
				</li>
				<li>{{HTML::link('facelift/all-expenses','Expenditure Control & MGT')}}</li>
				<li>{{HTML::link('facelift/report','Report')}}</li>
				<li class="dropdown">{{HTML::link('#','Uploads', ['class'=>'dropdown-toggle','id'=>'UpDd','role'=>'button','data-toggle'=>'dropdown'])}}

				<ul class="dropdown-menu" role='menu' aria-labelledby='UpDd'>
					<li>
						{{HTML::link('facelift/upload-income', 'Income Upload')}}
					</li>
					<li>
						{{HTML::link('facelift/upload-expenses', 'Expenses Upload')}}
					</li>
				</ul>
				</li>
			</ul>
		</nav>
		
		
	</header>
	</div>
	

	<div class="container" id="contentCont" style="padding: 0px 5px 0px 5px; margin-bottom: 100px">
	
		<p>
		

		

			@yield('content')

		</p>
		
	</div>


	</div>






		{{ HTML::script('js/bootstrap-affix.js') }}
		{{ HTML::script('js/bootstrap-alert.js') }}
		{{ HTML::script('js/bootstrap-button.js') }}
		{{ HTML::script('js/bootstrap-carousel.js') }}
		{{ HTML::script('js/bootstrap-collapse.js') }}
		{{ HTML::script('js/bootstrap-dropdown.js') }}
		{{ HTML::script('js/bootstrap-modal.js') }}
		{{ HTML::script('js/bootstrap-popover.js') }}
		{{ HTML::script('js/bootstrap-scrollspy.js') }}
		{{ HTML::script('js/bootstrap-tab.js') }}
		{{ HTML::script('js/bootstrap-tooltip.js') }}
		{{ HTML::script('js/bootstrap-transition.js') }}
		{{ HTML::script('js/bootstrap-typeahead.js') }}
		{{ HTML::script('js/jquery.ui.core.js') }}
		{{ HTML::script('js/jquery.ui.position.js') }}
		{{ HTML::script('js/jquery.ui.widget.js') }}
		{{ HTML::script('js/jquery.ui.menu.js') }}
		{{ HTML::script('js/jquery.ui.datepicker.js') }}
		{{ HTML::script('js/jquery.ui.autocomplete.js') }}

		<script type="text/javascript">
		$('.date').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});

		</script>

		@yield('scripts')

		<footer style="margin: 50px;">
	<div class="text-center well">Facelift Beauty Palace @ {{strftime('%Y',strtotime('today'))}}</div>
</footer>
	
</body>
</html>