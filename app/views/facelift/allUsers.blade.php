@extends('facelift.layouts.main')

@section('content')
@if(Session::has('info'))
	<div class="alert alert-info">
	<button class="close" data-dismiss='alert'>&times;</button>
		{{Session::get('info')}}
	</div>
@endif
		<h1>
			Users
		</h1>


	<div>
		<table class="table table-striped" style="border:1px solid #ececec">
				<thead>
					<th>
						S/N
					</th>
					<th>
						Name
					</th>
					<th>
						Type
					</th>
					
					
					<th>
						delete
					</th>
				</thead>
				<?php $count = 1; ?>
				@foreach($users as $user)
					<tr>
						<td>
							{{$count}}
						</td>
						<td>
							{{$user->username}}
						</td>
						<td>
							@if($user->admin == 1)
								{{'Admin'}}
							@elseif($user->super_admin == 1)
								{{ 'Super Admin' }}
							@else
								{{'Staff'}}

							@endif

						</td>
						
						<td>

								{{Form::open(['url'=>'facelift/user-delete/'.$user->username])}}
							{{Form::hidden('username',$user->username)}}

									{{Form::submit('delete',['class'=>'userDelbtn btn btn-small btn-danger '])}}
								{{Form::close()}}
						</td>
					</tr>
					<?php $count++; ?>
				@endforeach


		</table>
		<script type="text/javascript">
		$(function() {
			$('.userDelbtn').click(function() {
				if(confirm('Are You Sure?')) {
					return true;
				} else {
					return false;
				}
			});
		});

		</script>
	</div>




@stop