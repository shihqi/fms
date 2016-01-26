@extends('app')

@section('contentheader_title')
    <h1>
        All Platforms ({!! $platforms->count() !!})
    </h1>
@endsection

@section('main-content')

	<div class="container">
		<div class="col-sm-offset-2 col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					New platforms
				</div>
                @if(Session::has('message'))
                    <div class="alert-box success">
                        <h2>{{ Session::get('message') }}</h2>
                    </div>
                @endif
				<div class="panel-body">
					<!-- Display Validation Errors -->
					@include('common.errors')

					<!-- New Permission Form -->
					<form action="platforms" method="POST" class="form-horizontal">
						{{ csrf_field() }}

						<!-- Permission Name -->
						<div class="form-group">
							<label for="platform" class="col-sm-3 control-label">Platform</label>

							<div class="col-sm-6">
								<input type="text" name="name" id="platform-name" class="form-control" value="{{ old('platform') }}">
							</div>
						</div>

						<!-- Add Permission Button -->
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-6">
								<button type="submit" class="btn btn-default">
									<i class="fa fa-plus"></i>Add platform
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>

			<!-- Current Platforms -->
			@if (count($platforms) > 0)
				<div class="panel panel-default">
					<div class="panel-heading">
						Current Platforms
					</div>

					<div class="panel-body">
						<table class="table table-striped task-table">
							<thead>
								<th>Platform</th>
								<th>&nbsp;</th>
							</thead>
							<tbody>
								@foreach ($platforms as $platform)
									<tr>
                                        <td class="table-text"><div>{{ $platform->name }}</div></td>
                                        <td class="text-center">
                                            <a class="btn btn-warning" href="{!! route('admin.platforms.edit', $platform->id) !!}"><i class="fa fa-pencil"></i> Edit</a>
                                            &middot;
                                            @include('partials.modal', ['data' => $platform, 'name' => 'platforms'])
                                        </td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			@endif
		</div>
	</div>
@stop
