@extends('app')

@section('contentheader_title')
    <h1>
        All Products in {!! $feed->first()->description !!} ({!! $total !!})
    </h1>
@endsection

@section('main-content')

        {!! Form::open(['files' => true,'method' => 'POST', 'enctype' => 'multipart/form-data', 'route' => ['admin.feeds.product.store',$feed->first()->id]]) !!}
        {!! Form::hidden('feed_id', $feed->first()->id) !!}
        <div class="form-group">
            {!! Form::label('feed', '上傳Feed XML:') !!}
            {!! Form::file('feed', null, ['class' => 'form-control']) !!}
            {!! $errors->first('feed', '<div class="text-danger">:message</div>') !!}
        </div>
        <div class="form-group has-error">
             {!! Form::checkbox('update', 1, true) !!} {!! Form::label('update', '更新資料-(保留目前已有的產品資料)', ['class' => 'control-label']) !!}
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
                           
                        </tbody>
                    </table>
                   
                </div><!-- /.box-body -->
                
            </div><!-- /.box -->
            
    <link href="{{ asset('/public/css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <!--<script src="{{ asset('js/datatables/jquery.dataTables.js') }}" type="text/javascript"></script>-->
    <script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="{{ asset('/public/js/datatables/dataTables.bootstrap.js' )}}" type="text/javascript"></script>
    <script type="text/javascript">
            $(function() {
                $(document).ready(function() {
                    /*$.ajax({
                        type: "get",
                        url: '{!! route('admin.feeds.ajaxData') !!}',
                        data: {feedid: "{!! $feed->first()->id !!}"},
                        success: function( msg ) {
                            //$("#ajaxResponse").append("<div>"+msg+"</div>");
                            alert(msg);
                        }
                    });*/
                    console.log('ajax');
                    $('#product_list').DataTable({
                        //processing: true,
                        serverSide: true,
                        ajax: "{!! route('admin.feeds.ajaxData',array('feedid' => $feed->first()->id))!!}",
                        //data: {feedid: "{!! $feed->first()->id !!}"},
                        //ajax:{
                            //"url": "{!! route('admin.feeds.ajaxData') !!}",
                            //"data": function (d) {
                                //d.feedid = "{!! $feed->first()->id !!}";
                            //}
                        //},
                        /*columns: [
                            { data: 'id', name: 'id' },
                            { data: 'name', name: 'name' },
                            { data: 'url', name: 'url' },
                            { data: 'image', name: 'image' },
                            { data: 'brand', name: 'brand' },
                            { data: 'price', name: 'price' },
                            { data: 'retail_parice', name: 'retail_parice' },
                            { data: 'availability', name: 'availability' }
                        ]*/
                        //"url": "{!! route('admin.feeds.ajaxData') !!}",
                        //"data": function (d) {
                            //d.feedid = "{!! $feed->first()->id !!}";
                            // d.custom = $('#myInput').val();
                            // etc
                        //}
                        //console.log('ajax2');
                    });
                    
                });
            });
    </script>
@stop
