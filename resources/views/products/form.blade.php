@if(isset($model))
{!! Form::model($model, ['method' => 'PUT', 'files' => true, 'route' => ['admin.feeds.product.update', $feed->first()->id ,$model->first()->id]]) !!}
@else
{!! Form::open(['files' => true, 'route' => 'admin.feeds.product.store']) !!}
@endif
    <div class="form-group">
		{!! Form::label('id', 'Product:') !!}
		{!! Form::text('id', $model->first()->id, ['class' => 'form-control','readonly']) !!}
		{!! $errors->first('id', '<div class="text-danger">:message</div>') !!}
	</div>
	<div class="form-group">
		{!! Form::label('name', 'Name:') !!}
		{!! Form::text('name', $model->first()->name, ['class' => 'form-control']) !!}
		{!! $errors->first('name', '<div class="text-danger">:message</div>') !!}
	</div>
	<div class="form-group">
		{!! Form::label('description', 'description:') !!}
		{!! Form::text('description', $model->first()->description, ['class' => 'form-control']) !!}
		{!! $errors->first('description', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('url', 'url:') !!}
		{!! Form::text('url', $model->first()->url, ['class' => 'form-control']) !!}
		{!! $errors->first('url', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('image', 'image:') !!}
		{!! Form::text('image', $model->first()->image, ['class' => 'form-control']) !!}
		{!! $errors->first('image', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('image_preview', 'image preview:') !!}
		<img src ="{!! $model->first()->image !!}" height="50" width="50">
	</div>
    <div class="form-group">
		{!! Form::label('price', 'price:') !!}
		{!! Form::text('price', $model->first()->price, ['class' => 'form-control']) !!}
		{!! $errors->first('price', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('retail_price', 'retail_price:') !!}
		{!! Form::text('retail_price', $model->first()->retail_price, ['class' => 'form-control']) !!}
		{!! $errors->first('retail_price', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('category', 'category:') !!}
		{!! Form::text('category', $model->first()->category, ['class' => 'form-control']) !!}
		{!! $errors->first('category', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('google_category', 'google_category:') !!}
		{!! Form::text('google_category', $model->first()->google_category, ['class' => 'form-control']) !!}
		{!! $errors->first('google_category', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('brand', 'brand:') !!}
		{!! Form::text('brand', $model->first()->brand, ['class' => 'form-control']) !!}
		{!! $errors->first('brand', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('condition', 'condition:') !!}
		{!! Form::select('condition', ['new'=>'new','used'=>'used','refurbished'=>'refurbished'],$model->first()->condition, ['class' => 'form-control']) !!}
		{!! $errors->first('condition', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('availability', 'availability:') !!}
		{!! Form::select('availability', ['in stock'=>'in stock','out of stock'=>'out of stock','preorder'=>'preorder'], $model->first()->availability, ['class' => 'form-control']) !!}
		{!! $errors->first('availability', '<div class="text-danger">:message</div>') !!}
	</div>
	<div class="form-group">
		{!! Form::submit(isset($model) ? 'Update' : 'Save', ['class' => 'btn btn-primary']) !!}
	</div>
{!! Form::close() !!}
