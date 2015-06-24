@extends('admin.plantilla.app')
@section('custom_css')
<link type="text/css" rel="stylesheet" href="{{ asset('/blog/plugins/summernote/css/summernote.css') }}">
<link type="text/css" rel="stylesheet" href="{{ asset('/blog/plugins/bootstrap-tag/bootstrap-tagsinput.css') }}">
<link type="text/css" rel="stylesheet" href="{{ asset('/blog/plugins/bootstrap3-editable/css/bootstrap-editable.css') }}">
<link type="text/css" rel="stylesheet" href="{{ asset('/blog/css/blog.css') }}">



<style>
.uploader {position:relative; overflow:hidden; width:300px; height:350px; background:#f3f3f3; border:2px dashed #e8e8e8;}

#filePhoto{
    position:relative;
    width:auto;
    height:250px;
    top:-50px;
    left:0;
    z-index:2;
    opacity:0;
    cursor:pointer;
}

.uploader_featured.hover{
	border: 2px dashed #87cefa;
}

.uploader_featured{
	min-height: 300px;
}



.uploader_featured img{
	width: 100%;
	height: auto;
	display: none;
}
	
	</style>
@endsection
@section('breadcum')
<ul class="breadcrumb">
    <li>
        <a href="#">{{Lang::get('menu.administration.administration')}}</a>
    </li>
    <li>
        <a href="#">{{ trans('blog::blog.menu.create')  }}</a>
    </li>
</ul>
@endsection

@section('content')


<div class="modal fade fill-in" id="modalCategory" tabindex="-1" role="dialog" aria-labelledby="modalFillInLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="text-left p-b-5"><span class="semi-bold">{{Lang::get('blog::blog.categories.add')}}</span></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="feat_par_edit">
                    <div class="col-md-8">
                        <ul class="nav nav-tabs nav-tabs-linetriangle">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <li class="{{(strpos(LaravelLocalization::getCurrentLocale(), $localeCode )!== false) ? 'active' : '' }}">
                                <a data-toggle="tab" href="#parent-edit-{{strtolower($properties['name'])}}">{{{ $properties['native'] }}}</a>
                            </li>
                        @endforeach
                        </ul>
                        <div class="tab-content bg-transparent" id="parent_fields">

                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <div class="tab-pane {{(strpos(LaravelLocalization::getCurrentLocale(), $localeCode )!== false) ? 'active' : '' }}" id="parent-edit-{{strtolower($properties['name'])}}">
                                <div class="form-group form-group-default">
                                    <label>{{Lang::get('blog::blog.categories.name', array(), $localeCode)}}</label>
                                    <input type="text" name="name-{{$localeCode}}"  class="form-control clear-text" required>
                                </div>
                                
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div clas="col-md-4" style="float: right;margin-top: 68px;">
                        <button type="submit" id="createCategory" class="btn btn-primary btn-lg btn-large fs-15">{{Lang::get('blog::blog.actions.save')}}</button>
                        <button type="submit" class="btn btn-danger btn-lg btn-large fs-15 close_modal">{{Lang::get('blog::blog.actions.cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade fill-in" id="modalEditCategory" tabindex="-1" role="dialog" aria-labelledby="modalFillInLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="text-left p-b-5"><span class="semi-bold">{{Lang::get('blog::blog.categories.update')}}</span></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="edit-catId">
                    <div class="col-md-8">
                        <ul class="nav nav-tabs nav-tabs-linetriangle">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <li class="{{(strpos(LaravelLocalization::getCurrentLocale(), $localeCode )!== false) ? 'active' : '' }}">
                                <a data-toggle="tab" href="#category-edit-{{strtolower($properties['name'])}}">{{{ $properties['native'] }}}</a>
                            </li>
                        @endforeach
                        </ul>
                        <div class="tab-content bg-transparent" id="cat_fields">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <div class="tab-pane {{(strpos(LaravelLocalization::getCurrentLocale(), $localeCode )!== false) ? 'active' : '' }}" id="category-edit-{{strtolower($properties['name'])}}">
                                <div class="form-group form-group-default">
                                    <label>{{Lang::get('blog::blog.categories.name', array(), $localeCode)}}</label>
                                    <input type="text" name="name-edit-{{$localeCode}}"  class="form-control clear-text" required>
                                </div>
                                
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div clas="col-md-4" style="float: right;margin-top: 68px;">
                        <button type="submit" id="updateCategory" class="btn btn-primary btn-lg btn-large fs-15">{{Lang::get('blog::blog.actions.update')}}</button>
                        <button type="submit" class="btn btn-danger btn-lg btn-large fs-15 close_modal">{{Lang::get('blog::blog.actions.cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade fill-in" id="modalRemoveCategory" tabindex="-1" role="dialog" aria-labelledby="modalFillInLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">  
                <h5 class="text-center p-b-5">{{Lang::get('blog::blog.categories.sure_delete')}} <span class="semi-bold" id="chosen_category"> </span></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="remove-catId" id="remove-catId">
                <div class="row">
                    <div clas="col-md-12" style="text-align:center">
                        <button type="submit" id="removeCategoryYes" class="btn btn-primary btn-lg btn-large fs-15">{{Lang::get('blog::blog.actions.yes')}}, {{strtolower(Lang::get('blog::blog.actions.delete'))}}</button>
                        <button type="submit" class="btn btn-danger btn-lg btn-large fs-15 close_modal">{{Lang::get('blog::blog.actions.no')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{!! Form::open(['url' => LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog/update'), 'method'=> 'post', 'files' => true ]) !!}
<input type="hidden" name="postId" id="postId" value="{{$post->id}}">
<input type="hidden" name="categories" id="categories" value="{{ implode(",", $post->categories()->lists('id')) }}">
<input type="hidden" name="image_changed" id="image_changed" value="0">
<div class="panel col-md-9">
    <div class="panel-heading">
        <div class="panel-title">{{ trans('blog::blog.menu.create')  }}
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
		<div class="panel panel-transparent">
            <ul class="nav nav-tabs nav-tabs-fillup">
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <li class="{{(strpos(LaravelLocalization::getCurrentLocale(), $localeCode )!== false) ? 'active' : '' }}">
                    <a data-toggle="tab" href="#{{strtolower($properties['name'])}}">{{{ $properties['native'] }}}</a>
                </li>
            @endforeach
            </ul>
            <div class="tab-content bg-transparent">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <div class="tab-pane {{(strpos(LaravelLocalization::getCurrentLocale(), $localeCode )!== false) ? 'active' : '' }}" id="{{strtolower($properties['name'])}}">
                    <div class="form-group form-group-default ">
                        <label>{{Lang::get('blog::blog.tables.title', array(), $localeCode)}}</label>
                        <input type="text" name="title-{{$localeCode}}" class="form-control" value=" {{ old('title-'.$localeCode, $post->translate($localeCode)->title)}} " required>
                    </div>
                    <p class="hint-text small"><strong>{{Lang::get('blog::blog.slug.permalink', array(), $localeCode)}}:</strong> {{url('/blog/')}}/<span id="slug-container-{{$localeCode}}"><span id="slug_modificar-{{$localeCode}}" data-type="text" data-title="{{Lang::get('blog::blog.slug.change', array(), $localeCode)}}">{{$post->translate($localeCode)->slug}}</span></span></p>
                        <input type="hidden" name="slug-{{$localeCode}}" id="slug-{{$localeCode}}" value="{{$post->translate($localeCode)->slug}}">

                    <div class="form-group form-group-default ">
                        <label>{{Lang::get('blog::blog.tables.description', array(), $localeCode)}}</label>
                        <textarea id="content-{{$localeCode}}" name="content-{{$localeCode}}" class="clear-text">{{ old('content-'.$localeCode, $post->translate($localeCode)->content)}}</textarea> 
                    </div>
                </div>
                @endforeach
            </div>
			<div class="form-group form-group-default ">
		        <label>{{ trans('blog::blog.tables.tags')  }}</label>
		        <input type="text" name="tags" id="tags" class="form-control" value="{{ implode(",", $post->tag()->lists('name')) }}"">
		    </div>	
        </div>
    </div>
</div>
<div class=" col-md-3">
	<div class="panel">
		<div class="panel-heading">
			<div class="panel-title">{{ trans('blog::blog.tables.options')  }}</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<div id="chose_date" class="input-group date">
					    <input type="hidden" name="publish_date" id="publish_date" class="form-control" value="<?php echo date('d/m/Y') ?>">
					    <p class="pull-left m-r-10">{{ trans('blog::blog.tables.publish_date')  }}: <span id="fecha_publicacion" class="bold"><?php echo date('d/m/Y') ?></span></p>
					    <span class="add-on hand" title="Pulsa para cambiar"><i class="fa fa-calendar"></i>
					    </span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<p>Fecha de Creación: <span id="" class="bold"><?php echo date('d/m/Y') ?></span></p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<label>{{ trans('blog::blog.tables.state')  }}:</label>
					<select name="state" class="cs-select cs-skin-slide" data-init-plugin="cs-select">
					    <option value="0" {{ ($post->state == 0) ? 'selected' : ''  }}>{{ trans('blog::blog.states.draft')  }}</option>
					    <option value="1" {{ ($post->state == 1) ? 'selected' : ''  }}>{{ trans('blog::blog.states.published')  }}</option>
					</select>
				</div>
			</div>
			<hr />
			<div class="row">
				<div class="col-md-6">
					<a href="{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog')) }}" class="btn btn-lg col-md-12 btn-danger">{{ trans('blog::blog.actions.cancel')  }}</a>
				</div>
				<div class="col-md-6">
					<button type="submit" class="btn btn-lg col-md-12 btn-complete">{{ trans('blog::blog.actions.save')  }}</button>
				</div>
			</div>
			
			
		</div>
	</div>
	<div class="panel">
		<div class="panel-heading">
			<div class="panel-title">{{ trans('blog::blog.menu.categories')  }}</div>
			<div class="pull-right right-panel-options">
				<a href="#"  data-toggle="modal" data-target="#modalCategory">{{Lang::get('blog::blog.categories.add')}}</a>
			</div>
		</div>
		<div class="panel-body">
			<div id="categories_container">
			<?php $catChosen = $post->categories()->get()->lists('id'); ?>
			@foreach($categories as $category)
				<div class="checkbox check-success">
				
					@if(  false !== array_search($category->id, $catChosen))
							<input type="checkbox" checked="checked" value="{{$category->id}}" id="category_{{$category->id}}">
					@else
							<input type="checkbox" value="{{$category->id}}" id="category_{{$category->id}}">
					@endif
				    
				    <label for="category_{{$category->id}}">{{  $category->getName() }}</label>
				    @if($category->id != 1)
				    	<div class="pull-right cat-options">
                            <a class="right-panel-options green editCategory" href="#" data-cat="{{$category->id}}" data-name="{{$category->getName()}}" data-toggle="modal" data-target="#modalEditCategory">{{ trans('blog::blog.actions.edit')  }}</a>
                            <a class="right-panel-options red removeCategory" href="#" data-cat="{{$category->id}}" data-name="{{$category->getName()}}" data-toggle="modal" data-target="#modalRemoveCategory">{{ trans('blog::blog.actions.delete')  }}</a>
                        </div>
				    @endif
				    
				</div>
			@endforeach
			</div>	
		</div>
	</div>
	<div class="panel">
		<div class="panel-heading">
			<div class="panel-title">{{ trans('blog::blog.tables.photo')  }}</div>
		</div>
		<div class="panel-body">
			<div class="uploader_featured" onclick="$('#filePhoto').click()">
			<div class="text-center">Haz click aquí o arrastra una imagen</div>
			    <img id="featured" src="{{asset('blog/uploads/'.date('m-Y', strtotime($post->publish_date))."/".Config::get('blog.thumbnail_folder').'/'.$post->featured_image)}}" style="display: inline;"/>
			    <input type="file" name="featured_image"  id="filePhoto"  />
			</div>
			
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('custom_js')
	<script src="{{ asset('/admin/assets/plugins/classie/classie.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/blog/plugins/summernote/js/summernote.image.min.js') }}" type="text/javascript"></script>
    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
    <script src="{{ asset('/blog/plugins/summernote/lang/summernote-'.$properties['code'].'.js') }}" type="text/javascript"></script>
    @endforeach
	<script src="{{ asset('/blog/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/blog/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/blog/plugins/bootstrap3-editable/js/bootstrap-editable.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/blog/plugins/jslug/jslug.js') }}" type="text/javascript"></script>

    
	<script>

		@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            $('#content-{{$localeCode}}').summernote({
                height: 300,
                minHeight: 300,
                lang: "{{$properties['code']}}",
                onChange: function(contents, $editable) {
                    clean_html(this, "b", contents);
                    clean_html(this, "i", contents);
                },
                onpaste: function(content) {
                    setTimeout(function () {
                        editor.code(content.target.textContent);
                    }, 10);
                }
            });

            //Slug Editor
            $.fn.editable.defaults.mode = 'inline';
            $('#slug_modificar-{{$localeCode}}').editable();

            $('#slug_modificar-{{$localeCode}}').on('save', function(e, params) {
                var slug = params.newValue.slug()
                createEditable("{{$localeCode}}", slug);
            });
        @endforeach


        function createEditable(lang, slug){
            $("#slug-"+lang).val(slug);
            $('#slug_modificar-'+lang).editable("destroy");
            $('#slug_modificar-'+lang).remove();
            var span = $('<span />').attr('id', 'slug_modificar-'+lang ).html(slug);
            $("#slug-container-"+lang).append(span);
            $('#slug_modificar-'+lang).editable();

            $('#slug_modificar-'+lang).on('save', function(e, params) {
                var slug = params.newValue.slug()
                createEditable(lang, slug);
            });

        }

        function clean_html(editor, type, value){  
            if (value.indexOf("<"+type+">") >= 0){
                if(type == "b"){
                    marca = /<b(?:.*?)>(?:.*?)<\/b>/g;
                    replaceIniTag = "<strong>";
                    replaceEndTag = "</strong>";
                } else {
                    marca = /<i(?:.*?)>(?:.*?)<\/i>/g;
                    replaceIniTag = "<em>";
                    replaceEndTag = "</em>";
                }
                var matches = value.match(marca), 
                    len = matches.length,
                    i;
                for (i = 0; i < len; i++) { 
                    demo = $(matches[i]).text();
                    demo = replaceIniTag+demo+replaceEndTag;
                    value = value.replace(matches[i], demo);
                }
                 $(editor).code(value);
            }
        }


        



        var imageUpload = document.getElementById('filePhoto');
	    imageUpload.addEventListener('change', handleImageFeatured, false);


		imageUpload.ondragover = function () { $('.uploader_featured').addClass('hover'); return false; };
		imageUpload.ondragend = function () { $('.uploader_featured').removeClass('hover'); return false; };
		acceptedTypes = {
	      'image/png': true,
	      'image/jpeg': true,
	      'image/gif': true,
	      'image/bmp': false
	    };
		function handleImageFeatured(e) {
		    var readerUp = new FileReader();
		    readerUp.onload = function (event) {
		    	var buffer = readerUp.result;
		    	 var int32View = new Int32Array(buffer);


		    	var mimeType = event.target.result.split(",")[0].split(":")[1].split(";")[0];

		        if(acceptedTypes[mimeType] === true){
		        	$('.uploader_featured img').attr('src',event.target.result);
		        	$('.uploader_featured img').show();
		        	$('.uploader_featured').removeClass('hover');
		        	$('#image_changed').val(1);
		        }
		    }
		    readerUp.readAsDataURL(e.target.files[0]);
		}


		//categories



        $('#tags').tagsinput();
		$('#chose_date').datepicker({
			format: "dd/mm/yyyy",
            language: "es",
            autoclose: true
		}).on('changeDate', function(e){
	       	$("#fecha_publicacion").text($("#publish_date").val());
	    }).on('show', function(e){
	       	$(".datepicker").css("z-index", "");
	    });

        //Check the categories chosen */
        $('input[type="checkbox"]').click(function() {
            var categories = $("#categories").val();
            categories = categories.split(",");
               if ($(this).is(':checked')){
                    categories.push($(this).val());
               } else {
                    var index = categories.indexOf($(this).val());
                    categories.splice(index, 1);
               }
             if($(this).val()  != 1){
                $("#category_1").removeAttr('checked');
                $("#categories").val(0);
                var index = categories.indexOf("1");
                if(index != -1){
                    categories.splice(index, 1);
                }
             }
            $("#categories").val(categories.join());
            if(categories.length == 0){
                $("#category_1").attr('checked', 'checked');
                $("#categories").val(1)
            }
        });


	    //Categories
	    $(document).on('click','#createCategory',function(e){
        	e.preventDefault();
        	locales = [];
        		@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        		locales.push("{{$localeCode}}");
        		@endforeach
        	
        	var datos = {
        		lang : locales,
        		@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        		'name_{{$localeCode}}': $('[name="name-{{$localeCode}}"]').val(),
        		@endforeach
            };
            //console.log(datos);
            $.ajax({
                url: "{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog/categories/store')) }}",
                beforeSend: function (request){
                	request.setRequestHeader("X-CSRF-TOKEN", $('[name="_token"]').val());
            	},
                type: 'post',
                cache: false,
                dataType: 'json',
                data: datos,
                success: function(data) {
                	$('.modal').modal('hide');
                	var checkbox = '<div class="checkbox check-success"> \
                                        <input type="checkbox"  value="'+ data.id +'" id="category_'+ data.id +'">  \
                                        <label for="category_'+ data.id +'">'+ data.name +'</label> \
                                        <div class="pull-right cat-options"> \
                                            <a class="right-panel-options green editCategory" href="#" data-cat="'+ data.id +'" data-toggle="modal" data-target="#modalEditCategory">{{ trans("blog::blog.actions.edit")  }}</a> \
                                            <a class="right-panel-options red removeCategory" href="#" data-cat="'+ data.id +'" data-toggle="modal" data-target="#modalRemoveCategory">{{ trans("blog::blog.actions.delete")  }}</a> \
                                        </div> \
                                    </div>';
			    	console.log(checkbox);
                	$("#categories_container").append(checkbox)
                    $.fn.notifica({
                        type: "message",
                        message: "{{Lang::get('ajax.category_added')}}"
                    });
                },
                error: function(error){
                	$('.modal').modal('hide');
                    $.fn.notifica({
                        type: "error",
                        message: "{{Lang::get('ajax.error_503')}}"
                    });
                }
            });
			
        	return false;
        });


		$(document).on('click','.editCategory',function(){
            var datos = {
                id : $(this).attr("data-cat")
            }
            $.ajax({
                url: "{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog/categories/edit')) }}",
                beforeSend: function (request){
                    request.setRequestHeader("X-CSRF-TOKEN", $('[name="_token"]').val());
                },
                type: 'get',
                cache: false,
                dataType: 'json',
                data: datos,
                success: function(data) {


                	 console.log(data);
                    $('[name="edit-catId"]').val(data.id)
                    $.each(data["languages"], function(i, item) {
                        $('[name="name-edit-'+i+'"]').val(item.name)
                
                    });
                    $('#modalEditCategory').modal('show')

                    
                },
                error: function(error){
                    $('.modal').modal('hide');
                    $.fn.notifica({
                        type: "error",
                        message: "{{Lang::get('ajax.error_503')}}"
                    });
                }
            });
        });

		$(document).on('click','#updateCategory',function(){
            locales = [];
    		@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
    		locales.push("{{$localeCode}}");
    		@endforeach
            var datos = {
            	catId : $('[name="edit-catId"]').val(),
        		lang : locales,
        		@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        		'name_{{$localeCode}}': $('[name="name-edit-{{$localeCode}}"]').val(),
        		@endforeach
            };
            $.ajax({
                url: "{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog/categories/update')) }}",
                beforeSend: function (request){
                    request.setRequestHeader("X-CSRF-TOKEN", $('[name="_token"]').val());
                },
                type: 'post',
                cache: false,
                dataType: 'json',
                data: datos,
                success: function(data) {
                    console.log(data);

                    $('label[for=category_'+data.id+']').text(data.name);
                    $('.modal').modal('hide');
                    $.fn.notifica({
                        type: "message",
                        message: "{{Lang::get('ajax.category_added')}}"
                    });
                    
                },
                error: function(error){
                    $.fn.notifica({
                        type: "error",
                        message: "{{Lang::get('ajax.error_503')}}"
                    });
                }
            });
        });

        $(document).on('click','.removeCategory',function(){

            var id = $(this).attr("data-cat");
            
            $("#remove-catId").val(id);
            $("#chosen_category").text($(this).attr("data-name"));
            
        });

        $(document).on('click','#removeCategoryYes',function(){
            var datos = {
                id : $("#remove-catId").val()
            }
            $.ajax({
                url: "{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog/categories/remove')) }}",
                beforeSend: function (request){
                    request.setRequestHeader("X-CSRF-TOKEN", $('[name="_token"]').val());
                },
                type: 'post',
                cache: false,
                dataType: 'json',
                data: datos,
                success: function(data) {
                    console.log(data);

                    $('label[for=category_'+data+']').remove();
                    $('.modal').modal('hide');
                    $.fn.notifica({
                        type: "message",
                        message: "{{Lang::get('blog::blog.database.category_deleted')}}"
                    });
                    
                },
                error: function(error){
                    $('.modal').modal('hide');
                    $.fn.notifica({
                        type: "error",
                        message: "{{Lang::get('blog::blog.database.error')}}"
                    });
                }
            });
            
        });


	    $(document).on('click','.close_modal',function(){
            $('.modal').modal('hide');
        });
	</script>
@endsection