@extends('app')

@section('contentheader_title')
	
	<h1>
		Edit Feed
		&middot;
		<small>{!! link_to_route('admin.feeds.index', 'Back') !!}</small>
	</h1>
	
@stop

@section('main-content')
	
	<div>
		@include('feeds.form', array('model' => $feed)+compact('customers'))
	</div>

@stop
