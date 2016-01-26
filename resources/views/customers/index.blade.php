@extends('app')

@section('contentheader_title')
    <h1>
        All Customers ({!! $customers->count() !!})
        <small><i class="fa fa-plus"></i>{!! link_to_route('admin.customers.create', 'Add New') !!}</small>
    </h1>
@endsection

@section('main-content')

	<div class="content">

			<!-- Current Customers -->
            @if (count($customers) > 0)
				<div class="panel panel-default">
                        <table class="table">
                            <thead>
                                <th>No</th>
                                <th>Name</th>
                                <th>Alias</th>
                                <th>Created At</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                <tr>
                                    <td>{!! $customer->id !!}</td>
                                    <td>{!! $customer->name !!}</td>
                                    <td>{!! $customer->eng_name !!}</td>
                                    <td>{!! $customer->created_at !!}</td>
                                    <td class="text-center">
                                        <a class="btn btn-warning" href="{!! route('admin.customers.edit', $customer->id) !!}"><i class="fa fa-pencil"></i> Edit</a>
                                        &middot;
                                        @include('partials.modal', ['data' => $customer, 'name' => 'customers'])
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
				</div>
			@endif

	</div>
@stop
