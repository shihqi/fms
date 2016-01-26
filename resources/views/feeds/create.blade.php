@extends('app')


@section('contentheader_title')
	<h1>
		Add Feed
		&middot;
		<small>{!! link_to_route('admin.feeds.index', 'Back') !!}</small>
	</h1>
	
@stop

@section('main-content')
	
	<div>
		@include('feeds.form',compact('customers'))
	</div>

@stop
