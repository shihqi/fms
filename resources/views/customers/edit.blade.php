@extends('app')

@section('contentheader_title')
	
	<h1>
		Edit Customer
		&middot;
		<small>{!! link_to_route('admin.customers.index', 'Back') !!}</small>
	</h1>
	
@stop

@section('main-content')
	
	<div>
		@include('customers.form', array('model' => $customer))
	</div>

@stop
