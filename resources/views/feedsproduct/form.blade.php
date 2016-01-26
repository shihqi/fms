@if(isset($model))
{!! Form::model($model, ['method' => 'PUT', 'files' => true, 'route' => ['admin.feedsproduct.update', $model->id]]) !!}
@else
{!! Form::open(['files' => true, 'route' => 'admin.feedsproduct.store']) !!}
@endif
    <div class="form-group">
		{!! Form::label('customer_id', 'Customer:') !!}
		{!! Form::select('customer_id', $customers, null , ['class' => 'form-control']) !!}
		{!! $errors->first('customer_id', '<div class="text-danger">:message</div>') !!}
	</div>
	<div class="form-group">
		{!! Form::label('location', 'Location:') !!}
		{!! Form::text('location', null, ['class' => 'form-control']) !!}
		{!! $errors->first('location', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('type', 'type:') !!}
		{!! Form::select('type', array('complete' => 'complete', 'modify' => 'modify'), null , ['class' => 'form-control']) !!}
		{!! $errors->first('type', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('description', 'Description:') !!}
		{!! Form::textarea('description', null, ['class' => 'form-control']) !!}
		{!! $errors->first('description', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('feed', 'feed file:') !!}
		{!! Form::file('feed', null, ['class' => 'form-control']) !!}
		{!! $errors->first('feed', '<div class="text-danger">:message</div>') !!}
	</div>
	<div class="form-group">
		{!! Form::submit(isset($model) ? 'Update' : 'Save', ['class' => 'btn btn-primary']) !!}
	</div>
{!! Form::close() !!}
