<?php $date =  new \Jenssegers\Date\Date; ?>
@section('facebook')
	<meta property="og:url"           content="{{ Request::url() }}" />
	<meta property="og:type"          content="website" /	>
	<meta property="og:title"         content="{{$tag->name}}" />
	<meta property="og:description"   content="Etiqueta {{$tag->name}} de Mundo Children Friendly" />
@stop
@include('blog::front.header')
		<section id="blog-header" class="bg">
			<div class="wrapper">
				<div class="xs-12 col text-center all-w">
					<h2>Mundo Children Friendly</h2>

				</div>
				<div class="xs-12 col text-center mt-25">
					<a href="{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.public_blog')) }}">Volver al blog</a>
				</div>
			</div>
		</section>

		<section id="blog-filters">
			<div class="wrapper text-center">
				<h4>Filtrado por la etiqueta: <strong>{{$tag->name}}</strong></h4>
			</div>
		</section>

		<section id="blog-resultados">
			<div class="wrapper all-w">
				@if(count($posts) > 0)
					@foreach($posts as $post)
						@if(date('Y-m-d') >= $post->publish_date)
						<div class="blog-noticia-item xs-12 m-6 l-3 col">
							<a href="{{url('blog/'.$post->slug)}}">
								<div class="round bg" style="background-image: url( {{asset('blog_assets/uploads/'.date('m-Y', strtotime($post->publish_date))."/".Config::get('blog.thumbnail_folder').'/'.$post->featured_image)}} )">
									<span class="{{$post->categories->first()->color}}">{{$post->categories->first()->name}}</span>
									<div>
										<h4>{{$post->getTitle()}}</h4>
									</div>
									<p>{{$date->parse($post->publish_date)->format('d \d\e F Y')}}</p>
								</div>
							</a>
						</div>
						@endif
					@endforeach
				@endif
			</div>
			<!-- paginacion -->
			<div class="wrapper">
				<div class="xs-12 col pagination pt-40">
					{!!$posts->render()!!}
				</div>
				
			</div>
		</section>
@include('blog::front.footer')
