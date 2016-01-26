@extends('app')

@section('contentheader_title')
	<h1>
		{!! $title or 'All Users' !!} ({!! $users->count() !!})
		&middot;
		<small>{!! link_to_route('admin.users.create', 'Add New') !!}</small>
	</h1>
@stop

@section('main-content')
	<table class="table">
		<thead>
			<th>No</th>
			<th>Name</th>
			<th>Email</th>
			<th>Created At</th>
			<th class="text-center">Action</th>
		</thead>
		<tbody>
			@foreach ($users as $user)
			<tr>
				<td>{!! $user->id !!}</td>
				<td>{!! $user->name !!}</td>
				<td>{!! $user->email !!}</td>
				<td>{!! $user->created_at !!}</td>
				<td class="text-center">
					<a class="btn btn-warning" href="{!! route('admin.users.edit', $user->id) !!}"><i class="fa fa-pencil"></i>Edit</a>
					&middot;
					@include('partials.modal', ['data' => $user, 'name' => 'users'])
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
@stop
