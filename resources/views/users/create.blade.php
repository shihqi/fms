@extends('app')

@section('contentheader_title')
	<h1>
		Add New
		&middot;
		<small>{!! link_to_route('admin.users.index', 'Back') !!}</small>
	</h1>

@stop

@section('main-content')
	<div>
		@include('users.form')
	</div>

@stop
