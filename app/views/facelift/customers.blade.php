@extends('facelift.layouts.main')

@section('content')
<div class="container">
<div class="span9">
	<h1>
		Customers Directory
	</h1>
</div>



<div id='modal' class="modal hide fade" style="padding: 20px;">
    
</div>

<div class="span10" style="border-bottom: 1px solid #ececec">
<div class="span3 btn-group" style='border: ;'>
		{{HTML::link('facelift/add-customer','New Customer',['class'=>'btn btn-info'])}}
		{{HTML::link('facelift/all-services','All Services',['class'=>'btn btn-info'])}}
		{{HTML::link('facelift/new-service','New Service',['class'=>'btn btn-info'])}}
</div>

<div class="span3 pull-right" style='border: ;'>
{{Form::open(['class'=>'form-search','method'=>'post'])}}
<div class="input-append">
	{{Form::text('search_query', NULL,['class'=>'input-medium','placeholder'=>'Customer Name'])}}
	{{Form::button('Search', ['class'=>'btn btn-info'])}}
</div>
{{Form::close() }}
</div>
<div class="span10">
@if(Session::has('report'))
<div class="alert alert-success">
<button class="close" data-dismiss="alert">&times;</button>
{{ Session::get('report') }} <br />
@if(Session::has('customer_id'))
{{ HTML::link('#modal', 'View New Customer', ['name'=>Session::get('customer_id'),'role'=>'button','data-toggle'=>'modal','id'=>'viewbtn','class'=>'viewbtn btn btn-small'])}}
@endif
</div>
@endif

@if(Session::has('info'))
	<div class="alert alert-info">
	<button class="close" data-dismiss='alert'>&times;</button>
		{{ucfirst(Auth::user()->username).': '.Session::get('info')}}
	</div>
@endif
</div>
</div>
<div class="pagination span10">
{{ '<h1 class="span1"><small>Page: </small></h1>'.$customers->links() }}
</div>
<table class="table table-striped" style="border:1px solid #ececec">

<thead>
	<th>S/N</th>
	<th>Client Id</th>
	<th>Name</th>
	<th>Gender</th>
	<th>Date Registered</th>
	<th>Service History</th>
	<th></th>
	<th></th>
	<th></th>
	<th>Active</th>
	<th>Last Service</th>
</thead>
<?php  $count = 1;   
			if(isset($_GET['page']) && $_GET['page'] != 1) {
				$count = ($_GET['page'] * 10) - 9;
			}
		?>
	@foreach($customers as $eachCustomer)
{{--  role="button" class="btn" data-toggle="modal"--}}
<?php 
			$lastService = FaceliftServices::where('client_id','=',$eachCustomer->client_id)
			->orderBy('created_at','DESC')
			->select('created_at')
			->first(); 

			$t = strtotime('+2months') - strtotime('today');
			$lstDate = strtotime($lastService['created_at']);
			//dd($lstDate);
			$d = $lstDate + $t;

?>
		<tr>
			<td>{{ $count }}</td>
			<td>{{$eachCustomer->client_id}}</td>
			<td>{{$eachCustomer->client_name}}</td>
			<td>{{$eachCustomer->gender}}</td>
			<td>{{strftime('%a %d, %b %Y', strtotime($eachCustomer->created_at))}}</td>
			<td>{{ HTML::link('#modal', 'View History', ['name'=>$eachCustomer->client_id,'class'=>'historybtn btn btn-info btn-small','role'=>'button','data-toggle'=>'modal']) }}</td>
			<td>{{ HTML::link('#modal', 'edit', ['name'=>$eachCustomer->client_id,'role'=>'button','data-toggle'=>'modal','id'=>'editbtn','class'=>'editbtn btn btn-primary btn-small'])}}</td>
			<td>{{ HTML::link('#modal', 'view', ['name'=>$eachCustomer->client_id,'role'=>'button','data-toggle'=>'modal','id'=>'viewbtn','class'=>'viewbtn btn btn-small'])}}</td>
			<td>{{ HTML::link('#modal', 'delete', ['name'=>$eachCustomer->client_id,'role'=>'button','data-toggle'=>'modal','id'=>'deletebtn','class'=>'deletebtn btn btn-danger btn-small'])}}</td>
			<td>

			@if(strtotime('today') >= $d && !empty($lastService['created_at']))
				{{--strftime('%a %d-%m-%Y',$d)--}}
				{{'<span style="color: rgb(150,10,10);">Not Active</span>'}}
			@elseif($lastService['created_at'] == '')
				{{'No Service Yet'}}
			@else 
				{{'<span style="color: rgb(10,150,10);">Active</span>'}}
			@endif
			</td>
			<td>
			@if($lastService['created_at'])
				{{strftime('%a %d,%m %Y',strtotime($lastService['created_at']))}}
			@else
				{{strftime('%a %d,%m %Y',strtotime($eachCustomer->created_at))}}
			@endif
			</td>
		</tr>
		<?php $count++; ?>
	@endforeach
</table>

<div class="pagination">
{{ '<h1 class="span1"><small>Page: </small></h1>'.$customers->links() }}
</div>
</div>

@stop

@section('scripts')
<script type="text/javascript">
	$('.viewbtn').click(function() {
		//alert(this.name);
		$('#modal').html('Loading...');
		$.get('/index.php/facelift/view-customer/'+this.name, function(data){
			$('#modal').html(data);
		});
	});
</script>

<script type="text/javascript">
	$('.deletebtn').click(function() {
		if(confirm('Are You Sure?:')) {
		$('#modal').html('Loading...');
		$.get('/index.php/facelift/delete-customer/'+this.name, function(data){
			$('#modal').html(data);
			setTimeout(function() {
				location.reload();
			}, 1000);
		});
		} else {
			return false;
		}
	});
</script>

<script type="text/javascript">
	$('.editbtn').click(function() {
		//alert('');
		$('#modal').html('Loading...');



		
		$.get('/index.php/facelift/edit-customer/'+this.name, function(data){
			$('#modal').html(data);

		});
	});
</script>

<script type="text/javascript">
	$('.historybtn').click(function() {
		$('#modal').html('Loading........');

		$.get('/index.php/facelift/customer-history/'+this.name,function(data) {
			$('#modal').html(data);
		});
	});

</script>

@stop