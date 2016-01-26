@extends('app')

@section('contentheader_title')
    <h1>
        All Feeds ({!! $feeds->count() !!})
        <small><i class="fa fa-plus"></i>{!! link_to_route('admin.feeds.create', 'Add New') !!}</small>
    </h1>
@endsection

@section('main-content')

	<div class="content">

			<!-- Current Feeds -->
            @if (count($feeds) > 0)
				<div class="panel panel-default">
                        <table class="table">
                            <thead>
                                <th>編號</th>
                                <th>名稱</th>
                                <th>描述</th>
                                <th>建立時間</th>
                                <th class="text-center">內容操作</th>
                                <th class="text-center">動作</th>
                            </thead>
                            <tbody>
                                @foreach ($feeds as $feed)
                                <tr>
                                    <td>{!! $feed->id !!}</td>
                                    <td>{!! $feed->name !!}</td>
                                    <td>{!! $feed->description !!}</td>
                                    <td>{!! $feed->created_at !!}</td>
                                    <td class="text-center">
                                        <a class="btn btn-info" href="{!! route('admin.feeds.product.index', $feed->id) !!}"><i class="fa fa-list-ul"></i> Detail</a>
                                        &middot;
                                        <a class="btn btn-info" href="{!! route('admin.feeds.product.index', [$feed->id,'edit'=>'filter']) !!}"><i class="fa fa-filter"></i> Filter</a>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-warning" href="{!! route('admin.feeds.edit', $feed->id) !!}"><i class="fa fa-pencil"></i> Edit</a>
                                        &middot;
                                        @include('partials.modal', ['data' => $feed, 'name' => 'feeds'])
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
				</div>
			@endif

	</div>
@stop
