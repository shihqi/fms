@extends('app')


@section('contentheader_title')
	<h1>
		Add New
		&middot;
		<small>{!! link_to_route('admin.platforms.index', 'Back') !!}</small>
	</h1>
	
@stop
@section('main-content')
	
	<div>
		@include('platforms.form')
	</div>

@stop
