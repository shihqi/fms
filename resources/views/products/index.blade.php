@extends('app')

@section('contentheader_title')
    <h1>
        All Feeds ({!! $feeds->count() !!})
        <small>{!! link_to_route('admin.feeds.create', 'Add New') !!}</small>
    </h1>
@endsection

@section('main-content')

	<div class="content">

			<!-- Current Feeds -->
            @if (count($feeds) > 0)
				<div class="panel panel-default">
                        <table class="table">
                            <thead>
                                <th>No</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($feeds as $feed)
                                <tr>
                                    <td>{!! $feed->id !!}</td>
                                    <td>{!! $feed->name !!}</td>
                                    <td>{!! $feed->description !!}</td>
                                    <td>{!! $feed->created_at !!}</td>
                                    <td class="text-center">
                                        <a class="btn btn-warning" href="{!! route('admin.feeds.edit', $feed->id) !!}"><i class="fa fa-pencil"></i> Edit</a>
                                        &middot;
                                        @include('partials.modal', ['data' => $feed, 'name' => 'feeds'])
                                    </td>
                                    <td>
                                        <a class="btn btn-warning" href="{!! route('admin.feeds.product.index', $feed->id) !!}"><i class="fa fa-pencil"></i> Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
				</div>
			@endif

	</div>
@stop
