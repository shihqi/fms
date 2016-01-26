@extends('app')


@section('htmlheader_title')
	<h1>
		All Roles ({!! $roles->count() !!})
		&middot;
		<small>{!! link_to_route('admin.roles.create', 'Add New') !!}</small>
	</h1>
@stop

@section('contentheader_title')
    All Roles
@endsection

@section('main-content')

	<table class="table">
		<thead>
			<th>No</th>
			<th>Name</th>
			<th>Alias</th>
			<th>Description</th>
			<th>Permissions</th>
			<th>Created At</th>
			<th class="text-center">Action</th>
		</thead>
		<tbody>
			@foreach ($roles as $role)
			<tr>
				<td>{!! $role->id !!}</td>
				<td>{!! $role->name !!}</td>
				<td>{!! $role->display_name !!}</td>
				<td>{!! $role->description !!}</td>
                <td></td>
				<td>{!! $role->created_at !!}</td>
				
			</tr>
			@endforeach
		</tbody>
	</table>

	<div class="text-center">

	</div>
@stop
