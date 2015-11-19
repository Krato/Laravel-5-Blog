@extends('admin.layout')
@section('custom_css')
<link type="text/css" rel="stylesheet" href="{{ asset('/admin_theme/assets/plugins/jquery-datatable/media/css/jquery.dataTables.css') }}">
<link type="text/css" rel="stylesheet" href="{{ asset('/admin_theme/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css') }}">
<link media="screen" type="text/css" rel="stylesheet" href="{{ asset('/admin_theme/assets/plugins/datatables-responsive/css/datatables.responsive.css') }}">
<link type="text/css" rel="stylesheet" href="{{ asset('/blog_assets/css/blog.css') }}">
@endsection
@section('breadcum')
<ul class="breadcrumb">
    <li>
        <a href="#">{{Lang::get('menu.administration.administration')}}</a>
    </li>
    <li>
        <a href="#">{{ trans('blog::blog.title')  }}</a>
    </li>
</ul>
@endsection
@section('content')
<div class="modal fade fill-in" id="modalRemovePost" tabindex="-1" role="dialog" aria-labelledby="modalFillInLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">  
                <h5 class="text-center p-b-5"><span class="semi-bold">{{Lang::get('blog::blog.database.post_trash_ask')}} </span></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="remove-postID" id="remove-postID">
                <div class="row">
                    <div clas="col-md-12" style="text-align:center">
                        <button type="submit" id="removePostYes" class="btn btn-primary btn-lg btn-large fs-15">{{Lang::get('blog::blog.actions.yes')}}, {{strtolower(Lang::get('blog::blog.actions.delete'))}}</button>
                        <button type="submit" class="btn btn-danger btn-lg btn-large fs-15 close_modal">{{Lang::get('blog::blog.actions.no')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade fill-in" id="modalRemoveForcePost" tabindex="-1" role="dialog" aria-labelledby="modalFillInLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">  
                <h5 class="text-center p-b-5"><span class="semi-bold">{{Lang::get('blog::blog.database.post_ask')}} </span></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="remove-force-postID" id="remove-force-postID">
                <div class="row">
                    <div clas="col-md-12" style="text-align:center">
                        <button type="submit" id="removeForcePostYes" class="btn btn-primary btn-lg btn-large fs-15">{{Lang::get('blog::blog.actions.yes')}}, {{strtolower(Lang::get('blog::blog.actions.delete'))}}</button>
                        <button type="submit" class="btn btn-danger btn-lg btn-large fs-15 close_modal">{{Lang::get('blog::blog.actions.no')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-transparent">
    <div class="panel-heading">
        <div class="row">
            <div class="panel-title col-sm-12">
                <h2>{{ trans('blog::blog.title')  }}</h2>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row col-md-9 pull-left">

            <ul id="list-states" class="list-inline p-t-10">
                <li data-state='all' class="cursor change_state active">{{Lang::get('blog::blog.states.all')}} ({{$all}})</li>
                <li>|</li>
                <li data-state='published' class="cursor change_state">{{Lang::get('blog::blog.states.published')}} ({{$published}})</li>
                <li>|</li>
                <li data-state='trash' class="cursor change_state">{{Lang::get('blog::blog.states.trash')}} ({{$trash}})</li>
            </ul>
            </div>
            <div class="row col-md-3 pull-right no-margin no-padding">
                <div class="col-xs-12 no-margin no-padding">
                    <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                </div>
            </div>
            
        </div>
        <div class="clearfix no-margin"></div>
    </div>
    <div class="panel-body">
        <table class="table" id="tableWithSearch">
            <thead>
                <tr>
                    <th style="width:15%">{{ trans('blog::blog.tables.photo') }}</th>
                    <th>{{ trans('blog::blog.tables.title') }}</th>
                    <th style="width:35%">{{ trans('blog::blog.tables.description') }}</th>
                    <th>{{ trans('blog::blog.tables.publish_date') }}</th>
                    <th>{{ trans('blog::blog.tables.state') }}</th>
                    <th style="width:10%" class="no-sort">{{ trans('blog::blog.tables.options') }}</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
@endsection
@section('custom_js')
<script src="{{ asset('/admin_theme/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/admin_theme/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js') }}" type="text/javascript" ></script>
<script src="{{ asset('/admin_theme/assets/plugins/datatables-responsive/js/datatables.responsive.js') }}" type="text/javascript"></script>
<script src="{{ asset('/admin_theme/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('/admin_theme/assets/plugins/datatables-responsive/js/lodash.min.js') }}" type="text/javascript"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.7/api/fnReloadAjax.js" type="text/javascript"></script>
<script type="text/javascript">
var table;
	var initTableWithSearch = function() {
    table = $('#tableWithSearch');
    	var settings = {
	        "sDom": "<'table-responsive't><'row'<F ip>>",
	        "sPaginationType": "bootstrap",
	        "destroy": true,
	        "responsive": true,
	        "scrollCollapse": true,
	        "processing": true,
            "serverSide": true,
	        "language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.6/i18n/Spanish.json"
			},
			"ajax" : "{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog')) }}/data_blog?type=all",
	        "iDisplayLength": 5,
	        "columnDefs": [ {
    			"targets"	 : 'no-sort',
    			"orderable": false,
    			"searchable": false
    			}],
            "order": [[ 3, "desc" ]],
			"columns": [
		        {data: 'photo', name: 'photo'},
		        {data: 'title', name: 'title'},
		        {data: 'content', name: 'content'},
		        {data: 'publish_date', name: 'publish_date'},
		        {data: 'state', name: 'state'},
		        {data: 'opciones', name: 'opciones'}
		    ]
	    };

    	table.dataTable(settings);
	    // search box for table
	    $('#search-table').keyup(function() {
	        table.fnFilter($(this).val());
	    });
	}

	initTableWithSearch();
    //var newUrl = "{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog')) }}/data_blog_trash";
    //table.fnReloadAjax(newUrl);
    $(document).on('click','.removePost',function(){
        var id = $(this).attr("data-id");
        $("#remove-postID").val(id);
        
    });

    $(document).on('click','#removePostYes',function(){
        var datos = {
            id : $("#remove-postID").val()
        }
        console.log(datos);
        $.ajax({
            url: "{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog/remove')) }}",
            beforeSend: function (request){
                request.setRequestHeader("X-CSRF-TOKEN", $('[name="_token"]').val());
            },
            type: 'post',
            cache: false,
            dataType: 'json',
            data: datos,
            success: function(data) {
                $('.modal').modal('hide');
                location.reload();                
            },
            error: function(error){
                $('.modal').modal('hide');
                new PNotify({
                    title: "Error",
                    text: "{{Lang::get('blog::blog.database.error')}}",
                    type: "error"
                });
            }
        });
        
    });


    $(document).on('click','.removeForcePost',function(){
        var id = $(this).attr("data-id");
        $("#remove-force-postID").val(id);
        
    });

    $(document).on('click','#removeForcePostYes',function(){
        var datos = {
            id : $("#remove-force-postID").val()
        }
        console.log(datos);
        $.ajax({
            url: "{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog/remove_force')) }}",
            beforeSend: function (request){
                request.setRequestHeader("X-CSRF-TOKEN", $('[name="_token"]').val());
            },
            type: 'post',
            cache: false,
            dataType: 'json',
            data: datos,
            success: function(data) {
                $('.modal').modal('hide');
                location.reload();                
            },
            error: function(error){
                $('.modal').modal('hide');
                new PNotify({
                    title: "Error",
                    text: "{{Lang::get('blog::blog.database.error')}}",
                    type: "error"
                });
            }
        });
        
    });


    $(document).on('click','.restorePost',function(){
        var datos = {
            id : $(this).attr("data-id")
        }
        console.log(datos);
        $.ajax({
            url: "{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog/restore')) }}",
            beforeSend: function (request){
                request.setRequestHeader("X-CSRF-TOKEN", $('[name="_token"]').val());
            },
            type: 'post',
            cache: false,
            dataType: 'json',
            data: datos,
            success: function(data) {
                $('.modal').modal('hide');
                location.reload();                
            },
            error: function(error){
                $('.modal').modal('hide');
                new PNotify({
                    title: "Error",
                    text: "{{Lang::get('blog::blog.database.error')}}",
                    type: "error"
                });
            }
        });
        
    });

    $(document).on('click','.change_state',function(){
        var state = $(this).data("state");
        var newUrl = "{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog')) }}/data_blog?type="+state;
        table.fnReloadAjax(newUrl);

        $("#list-states").find("li").removeClass("active");

        $(this).addClass("active");
    });
    

    $(document).on('click','.close_modal',function(){
        $('.modal').modal('hide');
    });
</script>
@endsection