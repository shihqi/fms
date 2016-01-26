@extends('app')

@section('contentheader_title')
    <h1>
        All Products in {!! $feed->first()->description !!} ({!! $products->count() !!})
    </h1>
@endsection

@section('main-content')

        {!! Form::open(['files' => true, 'route' => ['admin.feeds.product.store',$feed->first()->id]]) !!}
        {!! Form::hidden('feed_id', $feed->first()->id) !!}
        <div class="form-group">
            {!! Form::label('feed', '上傳Feed XML:') !!}
            {!! Form::file('feed', null, ['class' => 'form-control']) !!}
            {!! $errors->first('feed', '<div class="text-danger">:message</div>') !!}
        </div>
        <div class="form-group has-error">
             {!! Form::checkbox('update', 1, false) !!} {!! Form::label('update', '只更新資料-(保留目前已有的產品資料)', ['class' => 'control-label']) !!}
        </div>   
        <div class="form-group">
            {!! Form::submit('Upload', ['class' => 'btn btn-primary']) !!}
            @if (session('message'))
                <div class="text-danger">
                    {{ session('message') }}
                </div>
            @endif
        </div>
        {!! Form::close() !!}
	        @if ($products->count() > 0)
            <!-- Datatable -->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">All Products</h3>
                </div><!-- /.box-header -->
                
                <div class="box-body"><!-- remove .table-responsive -->
                    
                    <table id="product_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>項目編號</th>
                                <th>名稱</th>
                                <th>目標網頁</th>
                                <th>圖像</th>
                                <th>品牌</th>
                                <th>售價</th>
                                <th>定價</th>
                                <th>供應情況</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                            <tr>
                                <td>{!! link_to_route('admin.feeds.product.edit', $product->id , [$feed->first()->id, $product->id])  !!}</td>
                                <td>{!! $product->name !!}</td>
                                <td>{!! $product->url !!}</td>
                                <td><img src="{!! $product->image !!}" height="50" width="50"></td>
                                <td>{!! $product->brand !!}</td>
                                <td>{!! $product->price !!}</td>
                                <td>{!! $product->retail_price !!}</td>
                                <td>{!! $product->availability !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                   
                </div><!-- /.box-body -->
                
            </div><!-- /.box -->
            @endif
    <link href="{{ asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('js/datatables/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/datatables/dataTables.bootstrap.js' )}}" type="text/javascript"></script>
    <script type="text/javascript">
            $(function() {
                $('#product_list').dataTable();
            });
    </script>
@stop
