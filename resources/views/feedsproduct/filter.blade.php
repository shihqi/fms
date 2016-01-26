@extends('app')

@section('contentheader_title')
    <h1>
        All Products in {!! $feed->first()->name !!} ( {!! $total + $disables !!} )
    </h1>
@endsection

@section('main-content')
    @if (session('message'))
        <div class="text-danger">
            {{ session('message') }}
        </div>
    @endif
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li class="active">
          <a href="#enable" role="tab" data-toggle="tab">
              <icon class="fa fa-play-circle"></icon> 目前啟用的產品({!! $total !!})
          </a>
      </li>
      <li><a href="#disable" role="tab" data-toggle="tab">
          <i class="fa fa-trash"></i> 目前停用的產品({!! $disables !!})
          </a>
      </li>
    </ul>
        <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade active in" id="enable">
            <!-- Datatable -->
            <div class="box">
                <div class="box-body"><!-- remove .table-responsive -->
                    @if ( $total > 0)
                    {!! Form::open(['method' => 'PUT', 'route' => ['admin.feeds.product.update',$feed->first()->id,$feed->first()->id]]) !!}
                    {!! Form::hidden('action', 'disable') !!}
                    <table id="enable_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>選取</th>
                                <th>項目編號</th>
                                <th>名稱</th>
                                <th>描述</th>
                                <th>圖像</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    {!! Form::submit('Set Disable', ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                    @endif
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
        <div class="tab-pane fade" id="disable">
            <!-- Datatable -->
            <div class="box">
                <div class="box-body"><!-- remove .table-responsive -->
                    @if ( $disables > 0)
                    {!! Form::open(['method' => 'PUT', 'route' => ['admin.feeds.product.update',$feed->first()->id, $feed->first()->id]]) !!}
                    {!! Form::hidden('action', 'enable') !!}
                    <table id="disable_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>選取</th>
                                <th>項目編號</th>
                                <th>名稱</th>
                                <th>描述</th>
                                <th>圖像</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                    {!! Form::submit('Set Enable', ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                    @endif
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
    <!--<link href="{{ asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" />-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/bs-3.3.5/jq-2.1.4,dt-1.10.10/datatables.min.css"/>
    <!--<script src="{{ asset('js/datatables/jquery.dataTables.js') }}" type="text/javascript"></script>-->
    <script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="{{ asset('/public/js/datatables/dataTables.bootstrap.js' )}}" type="text/javascript"></script>
    <script type="text/javascript">
           $(function() {
                $(document).ready(function() {
                    //console.log('ajax');
                    $('#enable_list').DataTable({
                        processing:true,
                        serverSide: true,
                        ajax: "{!! route('admin.feeds.ajaxProduct_enable',array('feedid' => $feed->first()->id))!!}",
                        bSort: false
                    });
                     $('#disable_list').DataTable({
                        processing:true,
                        serverSide: true,
                        ajax: "{!! route('admin.feeds.ajaxProduct_disable',array('feedid' => $feed->first()->id))!!}",
                        bSort: false
                    });
                });
            });
    </script>
@stop
