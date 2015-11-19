@extends('admin.layout')
@section('custom_css')

<link type="text/css" rel="stylesheet" href="{{ asset('/blog_assets/plugins/redactor/redactor.css') }}">
<link type="text/css" rel="stylesheet" href="{{ asset('/blog_assets/plugins/bootstrap-tag/bootstrap-tagsinput.css') }}">
<link type="text/css" rel="stylesheet" href="{{ asset('/blog_assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.css') }}">
<link type="text/css" rel="stylesheet" href="{{ asset('/blog_assets/css/blog.css') }}">
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

.simplecolorpicker.fontawesome span.color[data-selected]:after {
    font-family: 'FontAwesome';
    -webkit-font-smoothing: antialiased;

    content: '\f00c'; /* Ok/check mark */

    margin-right: 1px;
    margin-left: 1px;
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
                            <div class="form-group form-group-default">
                                <label>Color</label>
                                <select name="color_cat" class="colores_categorias_new">
                                    <option value="" data-tipo="cat-0">Sin Color</option>
                                    <option value="#06304c" data-tipo="cat-1">Marino</option>
                                    <option value="#07d090" data-tipo="cat-2">Verde</option>
                                    <option value="#fdd835" data-tipo="cat-3">Naranja</option>
                                    <option value="#eac0ff" data-tipo="cat-4">Rosa</option>
                                    <option value="#00e5ff" data-tipo="cat-5">Azul</option>
                                    <option value="#FF851C" data-tipo="cat-6">Naranja Neón</option>
                                    <option value="#00B295" data-tipo="cat-7">Cyan</option>
                                    <option value="#457B9D" data-tipo="cat-8">Azure</option>
                                    <option value="#E63946" data-tipo="cat-9">Rojo</option>
                                    <option value="#B33F62" data-tipo="cat-10">Bourbon</option>
                                </select>
                            </div>
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
                            <div class="form-group form-group-default">
                                <label>Color</label>
                                <select name="color_cat_edit" class="colores_categorias" id="color_cat_edit">
                                    <option value="" data-tipo="cat-0">Sin Color</option>
                                    <option value="#06304c" data-tipo="cat-1">Marino</option>
                                    <option value="#07d090" data-tipo="cat-2">Verde</option>
                                    <option value="#fdd835" data-tipo="cat-3">Naranja</option>
                                    <option value="#eac0ff" data-tipo="cat-4">Rosa</option>
                                    <option value="#00e5ff" data-tipo="cat-5">Azul</option>
                                    <option value="#FF851C" data-tipo="cat-6">Naranja Neón</option>
                                    <option value="#00B295" data-tipo="cat-7">Cyan</option>
                                    <option value="#457B9D" data-tipo="cat-8">Azure</option>
                                    <option value="#E63946" data-tipo="cat-9">Rojo</option>
                                    <option value="#B33F62" data-tipo="cat-10">Bourbon</option>
                                </select>
                            </div>
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

{!! Form::open(['url' => LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog/store'), 'method'=> 'post', 'files' => true ]) !!}

<input type="hidden" name="categories" id="categories" value="1">
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
                        <input type="text" name="title-{{$localeCode}}" class="form-control" value="{{ old('title-'.$localeCode) }}" required>
                    </div>
                    <div class="form-group form-group-default ">
                        <label>{{Lang::get('blog::blog.tables.description', array(), $localeCode)}}</label>
                        <textarea id="content-{{$localeCode}}" name="content-{{$localeCode}}">{{ old('content-'.$localeCode) }}</textarea> 
                    </div>
                </div>
                @endforeach
            </div>
			<div class="form-group form-group-default ">
		        <label>{{ trans('blog::blog.tables.tags')  }}</label>
		        <input type="text" name="tags" id="tags" class="form-control" value="{{ old('tags') }}">
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
					    <option value="0">{{ trans('blog::blog.states.draft')  }}</option>
					    <option value="1">{{ trans('blog::blog.states.published')  }}</option>
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
			@foreach($categories as $category)
				<div class="checkbox check-success  ">
				    <input type="checkbox" {{ ($category->id == 1) ? 'checked="checked"' : '' }} value="{{$category->id}}"" id="category_{{$category->id}}">
				    <label for="category_{{$category->id}}">{{  $category->getName() }}</label><span class="color-block {{$category->color}}"></span>
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
			    <img src=""/>
			    <input type="file" name="featured_image"  id="filePhoto" />
			</div>
			
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('custom_js')

	<script src="{{ asset('/admin_theme/assets/plugins/classie/classie.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/blog_assets/plugins/redactor/redactor.js') }}" type="text/javascript"></script>
    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
    <script src="{{ asset('/blog_assets/plugins/redactor/lang/'.$localeCode.'.js') }}" type="text/javascript"></script>
    @endforeach
    <script src="{{ asset('/admin_theme/assets/plugins/redactor/plugins/fullscreen.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/admin_theme/assets/plugins/redactor/plugins/fontcolor.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/admin_theme/assets/plugins/redactor/plugins/video.js') }}" type="text/javascript"></script>

	<script src="{{ asset('/blog_assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/blog_assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/blog_assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.js') }}" type="text/javascript"></script>

    <script>
        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            $('#content-{{$localeCode}}').redactor({
            minHeight: 350,
            maxHeight: 800,
            lang: "{{$localeCode}}",
            imageUpload: "{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog')) }}/upload_editor_image",
            cleanOnPaste: true,
            cleanSpaces: true,
            removeComments: true,
            removeEmpty: ['strong', 'em', 'span', 'p'],
            buttonsHide: ['orderedlist'],
            formatting: ['p', 'blockquote', 'h2', 'h3', 'h4'],
            plugins: ['fullscreen',  'video', 'fontcolor'],
            imageUploadCallback: function(image, json)
            {
                $(image).attr('data-name', json.name);
                $(image).attr('data-folder', json.folder);
            },
            imageDeleteCallback: function(url, image)
            {
                var datos = {
                    name : $(image).attr('data-name'),
                    folder : $(image).attr('data-folder')
                }
                $.ajax({
                    url: "{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.admin/blog')) }}/remove_editor_image",
                    beforeSend: function (request){
                        request.setRequestHeader("X-CSRF-TOKEN", $('[name="_token"]').val());
                    },
                    type: 'post',
                    cache: false,
                    dataType: 'json',
                    data: datos
                });
            }
        });
                @endforeach

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
                errorImageUpload = false;

                var size = document.getElementById('filePhoto').files[0].size;
                if(size > 4000000){
                    alert("Elige una imagen más pequeña");
                    errorImageUpload = true;
                }
                var buffer = readerUp.result;
                var int32View = new Int32Array(buffer);
                var mimeType = event.target.result.split(",")[0].split(":")[1].split(";")[0];

                if(acceptedTypes[mimeType] === true && !errorImageUpload){


                    var img = new Image;
                    img.src = readerUp.result;
                    img.onload = function() {
                        if(img.width > 3500 && img.height > 3500){
                            alert("Elige una imagen con dimesiones más pequeñas");
                            errorImageUpload = true;
                        } else {
                            $('.uploader_featured img').attr('src',event.target.result);
                            $('.uploader_featured img').show();
                            $('.uploader_featured').removeClass('hover');

                        }
                    };

                }
            }
            readerUp.readAsDataURL(e.target.files[0]);
        }


        //categories
        $('select.colores_categorias_new').simplecolorpicker({theme: 'fontawesome'});
        $('select.colores_categorias').simplecolorpicker({theme: 'fontawesome'});


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

            if ($("#categories").val() != $(this).val()){
                $('input[type="checkbox"]').not(this).prop('checked', false);
                $("#categories").val($(this).val());
            } else {
                $('input[type="checkbox"]').eq(0).prop('checked', true);
                $("#categories").val(1);
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
                        color: $('select[name="color_cat"]').find(':selected').attr('data-tipo'),
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
                                        <span class="color-block '+ data.color +'"></span> \
                                        <div class="pull-right cat-options"> \
                                            <a class="right-panel-options green editCategory" href="#" data-cat="'+ data.id +'" data-toggle="modal" data-target="#modalEditCategory">{{ trans("blog::blog.actions.edit")  }}</a> \
                                            <a class="right-panel-options red removeCategory" href="#" data-cat="'+ data.id +'" data-toggle="modal" data-target="#modalRemoveCategory">{{ trans("blog::blog.actions.delete")  }}</a> \
                                        </div> \
                                    </div>';
                    //console.log(checkbox);
                    $("#categories_container").append(checkbox)
                    $.fn.notifica({
                        type: "message",
                        message: "{{Lang::get('blog::blog.database.category_saved')}}"
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


                    $('[name="edit-catId"]').val(data.id)
                    $.each(data["languages"], function(i, item) {
                        $('[name="name-edit-'+i+'"]').val(item.name)
                    });
                    $('#color_cat_edit > option[data-tipo="'+data.color+'"]').prop('selected', true);
                    var color =$('#color_cat_edit').val();
                    $('select[name="color_cat_edit"]').simplecolorpicker('selectColor', color);
                    $('#modalEditCategory').modal('show');


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

        $(document).on('click','#updateCategory',function(){
            locales = [];
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            locales.push("{{$localeCode}}");
                    @endforeach
                    var datos = {
                        catId : $('[name="edit-catId"]').val(),
                        color: $('select[name="color_cat_edit"]').find(':selected').attr('data-tipo'),
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
                        message: "{{Lang::get('blog::blog.database.category_updated')}}"
                    });

                },
                error: function(error){
                    $.fn.notifica({
                        type: "error",
                        message: "{{Lang::get('blog::blog.database.error')}}"
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