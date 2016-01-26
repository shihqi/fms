@if(isset($model))
{!! Form::model($model, ['method' => 'PUT', 'files' => true, 'route' => ['admin.customers.update', $model->id]]) !!}
@else
{!! Form::open(['files' => true, 'route' => 'admin.customers.store']) !!}
@endif
	<div class="form-group">
		{!! Form::label('name', 'Name:') !!}
		{!! Form::text('name', null, ['class' => 'form-control']) !!}
		{!! $errors->first('name', '<div class="text-danger">:message</div>') !!}
	</div>
	<div class="form-group">
		{!! Form::label('eng_name', 'Alias:') !!}
		{!! Form::text('eng_name', null, ['class' => 'form-control']) !!}
		{!! $errors->first('eng_name', '<div class="text-danger">:message</div>') !!}
	</div>
	<div class="form-group">
		{!! Form::submit(isset($model) ? 'Update' : 'Save', ['class' => 'btn btn-primary']) !!}
	</div>
{!! Form::close() !!}
