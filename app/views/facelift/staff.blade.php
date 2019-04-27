@extends('facelift.layouts.main')

@section('content') 

<h1>
	Staff Directory
</h1>
@if(Session::has('info'))
	<div class="alert alert-info">
	<button class="close" data-dismiss='alert'>&times;</button>
		{{Session::get('info')}}
	</div>
@endif
<table class="table table-striped" style="border:1px solid #ececec">
	<thead>
		<th>S/N</th>
		<th>Staff Id</th>
		<th>Staff Name</th>
		<th>Address</th>
		<th>Phone Number</th>
		<th>Email</th>
		<th>Date Appointed</th>
		{{--<th>edit</th>--}}
		<th>deactivate</th>
		<th>delete</th>
	</thead>
<?php  $count = 1;   
			if(isset($_GET['page']) && $_GET['page'] != 1) {
				$count = ($_GET['page'] * 10) - 9;
			}
		?>

	@foreach($staffList as $eachStaff)
		

		<tr>
			<td>
				{{$count}}
			</td>
			<td>
				{{$eachStaff->staff_id}}
			</td>
			<td>
				{{$eachStaff->name}}
			</td>

			<td>
				{{$eachStaff->address}}
			</td>
			<td>
				{{$eachStaff->phone_no}}
			</td>
			<td>
				{{$eachStaff->email}}
			</td>
			<td>
				{{strftime('%a %d, %m %Y',strtotime($eachStaff->created_at))}}
			</td>
			{{--<td>
				{{HTML::link('#', 'edit',['class'=>'btn btn-small'])}}
			</td>--}}
			<td>
			@if(strftime('%Y-%m-%d',strtotime($eachStaff->deleted_at)) == strftime('%Y-%m-%d',strtotime('NULL')))

				 {{Form::open(['url'=>'facelift/deactivate/'.$eachStaff->staff_id])}}
				{{Form::submit('deactivate',['class'=>'btn btn-small btn-danger'])}}
				{{Form::close()}}
			@else 
				{{Form::open(['url'=>'facelift/activate/'.$eachStaff->staff_id])}}
				{{Form::submit('activate',['class'=>'btn btn-small btn-success'])}}
				{{Form::close()}}
			@endif
			</td>
			<td>

				 {{Form::open(['url'=>'facelift/delete-staff/'.$eachStaff->staff_id])}}
				{{Form::submit('delete',['class'=>'staffdel btn btn-small btn-danger'])}}
				{{Form::close()}}
			</td>
		</tr>
		<?php $count++; ?>
		
	@endforeach



</table>
<div class="pagination"> 
{{$staffList->links()}}
</div>

<script type="text/javascript">
	$('.staffdel').click(function() {
		if(confirm('Are you Sure?')) {
			return true;
		} else {
			return false;
		}
	});

</script>

@stop