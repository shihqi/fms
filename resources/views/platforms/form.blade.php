@if(isset($model))
{!! Form::model($model, ['method' => 'PUT', 'files' => true, 'route' => ['admin.platforms.update', $model->id]]) !!}
@else
{!! Form::open(['files' => true, 'route' => 'admin.platforms.store']) !!}
@endif
	<div class="form-group">
		{!! Form::label('name', 'Name:') !!}
		{!! Form::text('name', null, ['class' => 'form-control']) !!}
		{!! $errors->first('name', '<div class="text-danger">:message</div>') !!}
	</div>
	<div class="form-group">
		{!! Form::submit(isset($model) ? 'Update' : 'Save', ['class' => 'btn btn-primary']) !!}
	</div>
{!! Form::close() !!}
