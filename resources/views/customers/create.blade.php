@extends('app')


@section('contentheader_title')
	<h1>
		Add Customer
		&middot;
		<small>{!! link_to_route('admin.customers.index', 'Back') !!}</small>
	</h1>
	
@stop

@section('main-content')
	
	<div>
		@include('customers.form')
	</div>

@stop
