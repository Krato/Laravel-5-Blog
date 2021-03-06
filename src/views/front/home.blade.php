<?php $date =  new \Jenssegers\Date\Date; ?>

@section('facebook')
	<meta property="og:url"           content="{{ Request::url() }}" />
	<meta property="og:type"          content="website" /	>
	<meta property="og:title"         content="Mundo Children Friendly" />
	<meta property="og:description"   content="Descubre los mejores alojamientos para disfrutar en familia" />
	<meta property="og:image"         content="{{ asset('/assets/img/home-3.jpg') }}" />
@stop
@include('blog::front.header')
		<section id="blog-header" class="bg">
			<div class="wrapper">
				<div class="xs-12 col text-center all-w">
					<h1>Mundo Children Friendly</h1>
				</div>
			</div>
		</section>


		<section id="blog-filters">
			<div class="wrapper text-center">
				<div class="blog-filters-item"><a href="" class="cat-p active">Todos</a></div>
				@if(count($categories) > 0)
					@foreach($categories as $category)
						@if($category->id != 1)
							@if($category->posts_active()->count() > 0)
							<div class="blog-filters-item"><a href="{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.public_blog') .'/categoria/'.$category->getSlug())}}" class="{{$category->color}}">{{$category->getName()}}</a></div>
							@endif
						@endif
					@endforeach
				@endif
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
