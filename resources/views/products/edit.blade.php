@extends('app')

@section('contentheader_title')
	
	<h1>
		Edit Product
		&middot;
		<small>{!! link_to_route('admin.feeds.product.index', 'Back', $feed->first()->id) !!}</small>
	</h1>
	
@stop

@section('main-content')
	
	<div>
		@include('products.form', array('model' => $products) + compact('feed'))
	</div>

@stop
