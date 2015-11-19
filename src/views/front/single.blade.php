<?php $date =  new \Jenssegers\Date\Date; ?>

<?php $text = explode ('.' , $post->getContent() ,2 ); ?>
<?php $summary = strip_tags($text[0]); ?>
@section('facebook')
	<meta property="og:url"           content="{{ Request::url() }}" />
	<meta property="og:type"          content="website" /	>
	<meta property="og:title"         content="{{$post->title}}" />
	<meta property="og:description"   content="{{$summary}}" />
	<meta property="og:image"         content="{{ $post->getFeaturedImage() }}" />

@stop

@include('blog::front.header')
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.4";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script>window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return t;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);
 
  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };
 
  return t;
}(document, "script", "twitter-wjs"));</script>
		<section id="blog-single-header" class="bg" style="background-image: url( {{ $post->getFeaturedImage() }} );">
			<div class="wrapper">
				<div class="xs-12 col text-center">
					<h1>{{$post->title}}</h1>
					<h6 class="c-2">{{$post->categories->first()->getName()}}</h6>
					<h6>{{$date->parse($post->publish_date)->format('d \d\e F Y')}}</h6>
				</div>
			</div>
		</section>
		<section id="blog-single-content" class="bg-gris"	>
			@if(count($post->tag()->get()) > 0)
			<div class="etiquetas wrapper">
				<div class="xs-12 l-8 col center text-center">
				@foreach($post->tag()->get() as $tag)
					<a href="{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.public_blog') .'/tag/'.$tag->slug)}}">{{$tag->name}}</a>
				@endforeach
				</div>
			</div>
			@endif

			<div class="wrapper text-center">
				 <div class="fb-share-button" 
			        data-href="{{Request::url('/')}}" 
			        data-layout="button_count">
			    </div>
			    <div class="pt-5  twitter-btn">
				    <a class="twitter-share-button"
					  href="https://twitter.com/intent/tweet?text={{$post->title}}&via=children-friendly&url={{ Request::url() }}"
					  data-counturl="{{ Request::url() }}">
					Twittear</a>
				</div>
			</div>


			<div class="wrapper pt-20">
				<div class="xs-12 l-10 col center">
					{!! $post->getContent() !!}
				</div>
			</div>
		</section>
		@if($post->categories->first()->id == 7 || $post->categories->first()->id == 12)
		<section id="contacto-form" class="bg-gris">
			<form method="post" action="{{ url('contacto') }}">
				<div class="wrapper">
					<div class="xs-12 m-9 l-6 col  center text-center  contacto-top">
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
						<input type="text" class="input-full input-grey mt-15 mb-15" name="name" id="contact-name" placeholder="Nombre" required>
						<input type="email" class="input-full input-grey mt-15 mb-15" name="email" id="contact-email" placeholder="Correo electrónico" required>
						<input type="text" class="input-full input-grey mt-15 mb-15" name="subject" id="contact-subject" placeholder="Asunto">
						<textarea class="input-full input-grey mt-15 mb-45" name="message" id="contact-message" cols="30" rows="10" placeholder="Mensaje"></textarea>
						<input type="submit" class="button" value="Enviar Mensaje">
					</div>
				</div>
			</form>
		</section>
		@endif
@include('blog::front.footer')
