@if(isset($model))
{!! Form::model($feed, ['method' => 'PUT', 'route' => ['admin.feeds.update', $model->first()->id]]) !!}
    <div class="form-group">
		{!! Form::label('name', 'Name:') !!}
		{!! Form::text('name', $model->first()->name , ['class' => 'form-control']) !!}
		{!! $errors->first('name', '<div class="text-danger">:message</div>') !!}
	</div>
	<div class="form-group">
		{!! Form::label('location', 'Location:') !!}
		{!! Form::text('location', $model->first()->location , ['class' => 'form-control']) !!}
		{!! $errors->first('location', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('type', 'type:') !!}
		{!! Form::select('type', array('file' => 'file', 'url' => 'url'), $model->first()->type , ['class' => 'form-control']) !!}
		{!! $errors->first('type', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('content', 'content:') !!}
		{!! Form::select('content', array('complete' => 'complete', 'modify' => 'modify'), $model->first()->content , ['class' => 'form-control']) !!}
		{!! $errors->first('content', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('description', 'Description:') !!}
		{!! Form::textarea('description', $model->first()->description, ['class' => 'form-control']) !!}
		{!! $errors->first('description', '<div class="text-danger">:message</div>') !!}
	</div>
	<div class="form-group">
        <a class="btn btn-danger" href="{!! route('admin.feeds.index') !!}"><i class="fa fa-angle-double-left"></i> Back</a>
		{!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
	</div>
@else
{!! Form::open(['route' => 'admin.feeds.store']) !!}
<div class="form-group">
		{!! Form::label('customer_id', 'Customer:') !!}
		{!! Form::select('customer_id', $customers, null , ['class' => 'form-control']) !!}
		{!! $errors->first('customer_id', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('name', 'Name:') !!}
		{!! Form::text('name', null, ['class' => 'form-control']) !!}
		{!! $errors->first('name', '<div class="text-danger">:message</div>') !!}
	</div>
	<div class="form-group">
		{!! Form::label('location', 'Location:') !!}
		{!! Form::text('location', null, ['class' => 'form-control']) !!}
		{!! $errors->first('location', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('type', 'type:') !!}
        
		{!! Form::select('type', array('file' => 'file', 'url' => 'url'), ['class' => 'form-control']) !!}
		{!! $errors->first('type', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('content', 'content:') !!}
		{!! Form::select('content', array('complete' => 'complete', 'modify' => 'modify'),  ['class' => 'form-control']) !!}
		{!! $errors->first('content', '<div class="text-danger">:message</div>') !!}
	</div>
    <div class="form-group">
		{!! Form::label('description', 'Description:') !!}
		{!! Form::textarea('description', null, ['class' => 'form-control']) !!}
		{!! $errors->first('description', '<div class="text-danger">:message</div>') !!}
	</div>
	<div class="form-group">
        <a class="btn btn-danger" href="{!! route('admin.feeds.index') !!}"><i class="fa fa-angle-double-left"></i> Back</a>
		{!! Form::submit('Save', ['class' => 'btn btn-success']) !!}
	</div>
@endif
{!! Form::close() !!}
