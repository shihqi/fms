@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-sm-offset-2 col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					New platforms
				</div>

				<div class="panel-body">
					<!-- Display Validation Errors -->
					@include('common.errors')

					<!-- New Permission Form -->
					<form action="/platform" method="POST" class="form-horizontal">
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
										<!-- platform Delete Button -->
										<td>
											<form action="/platform/{{ $platform->id }}" method="POST">
												{{ csrf_field() }}
												{{ method_field('DELETE') }}

												<button type="submit" class="btn btn-danger">
													<i class="fa fa-trash"></i>Delete
												</button>
											</form>
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
@endsection
