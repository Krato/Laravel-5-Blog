<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>Children friendly - Blog</title>
	<link rel="icon" type="image/png" href="{{ asset('/assets/ico/ico192.png') }}" />
    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset('assets/ico/60.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/ico/76.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/ico/120.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/ico/152.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    @yield('facebook')

	
	<link href='http://fonts.googleapis.com/css?family=Dosis:400,500,600,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="{{ asset('css/estilos.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/fonts/icomoon/style.css') }}">
	<style>
		#goog-gt-tt {display:none !important;}
		.goog-te-banner-frame {display:none !important;}
		.goog-te-menu-value:hover {text-decoration:none !important;}
		#google_translate_element2 {display:none!important;}
		body {top:0 !important;}
	</style>
	<script src="//use.typekit.net/ngc2qtg.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>
</head>
<body>

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
	

		<!-- HEADER -->
	<header id="header-blog" class="bg bgt">

	<div class="wrapper all-w">
		<div class="xs-12 m-4 l-3 col">
			<a href="{{url('/')}}">
				<i class="icon-children-friendly"></i>
			</a>
		</div>
		<nav id="menu-home" class="xs-12 m-8 l-9 col menu primary-menu">

			@if(!$currentUser)
				<ul class="text-right">
					<li><a href="{{ url('informacion') }}">{{Lang::get('front/menu.info')}}</a></li>
					<li><a href="#" class="js-login-register">{{Lang::get('front/menu.register')}}</a></li>
					<li><a id="login-access" href="#" class="js-login-access">{{Lang::get('front/menu.login')}}</a></li>
					<li><a href="{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.public_blog')) }}">{{Lang::get('front/menu.blog')}}</a></li>
					<li class="dropdown">
						<a href="#" style="display:block">
							Idiomas
							<i class="icon-down"></i>
						</a>
						<ul class="sub-menu">
							<li><a href="#" onclick="doGTranslate('es|es');return false;">Español</a></li>
							<li><a href="#" onclick="doGTranslate('es|en');return false;">Inglés</a></li>
							<li><a href="#" onclick="doGTranslate('es|fr');return false;">Francés</a></li>
							<li><a href="#" onclick="doGTranslate('es|de');return false;">Alemán</a></li>
							<li><a href="#" onclick="doGTranslate('es|ru');return false;">Ruso</a></li>
						</ul>
					</li>
				</ul>
				@else
				<ul class="text-right">
					<li><a href="{{ url('informacion') }}">{{Lang::get('front/menu.info')}}</a></li>
					<li><a href="{{ url(LaravelLocalization::transRoute('routes.user/favorites')) }}">{{Lang::get('front/menu.logged.favorites')}}</a></li>
					<li class="dropdown">
						<a href="">
							<img src="{{$currentUser->profile->avatar()}}" alt="{{$currentUser->profile->name}} {{$currentUser->profile->lastaname}}" class="portrait">
							@if(!empty(trim($currentUser->profile->fullname())))
								{{$currentUser->profile->name}}
							@else
								{{$currentUser->nameOfEmail()}}
							@endif
							<i class="icon-down"></i>
						</a>

						<ul class="sub-menu">
							@if($currentUser->hasRole('hotel'))
							<li><a href="{{ url(LaravelLocalization::transRoute('routes.company/profile')) }}"><i class="icon-user"></i>{{Lang::get('front/menu.logged.control-panel')}}</a></li>
							<li><a href="{{ url('registrar_hotel') }}"><i class="icon-instalaciones"></i>Registra tu Hotel</a></li>
							@else
							<li><a href="{{ url(LaravelLocalization::transRoute('routes.user/profile')) }}"><i class="icon-user"></i>{{Lang::get('front/menu.logged.control-panel')}}</a></li>
							@endif
							<?php if (Auth::user()->hasRole('admin') ){ ?>
							<li><a href="{{ url(LaravelLocalization::transRoute('routes.admin')) }}"><i class="icon-cuenta"></i>{{Lang::get('menu.administration.administration')}}</a></li>
							<?php } ?>
							<li><a href="{{ url(LaravelLocalization::transRoute('routes.auth/logout')) }}"><i class="icon-off"></i>{{Lang::get('front/menu.logged.close-session')}}</a></li>
						</ul>
					</li>
					<li><a href="{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.public_blog')) }}">{{Lang::get('front/menu.blog')}}</a></li>
					<li class="dropdown">
						<a href="#" style="display:block">
							Idiomas
							<i class="icon-down"></i>
						</a>
						<ul class="sub-menu">
							<li><a href="#" onclick="doGTranslate('es|es');return false;">Español</a></li>
							<li><a href="#" onclick="doGTranslate('es|en');return false;">Inglés</a></li>
							<li><a href="#" onclick="doGTranslate('es|fr');return false;">Francés</a></li>
							<li><a href="#" onclick="doGTranslate('es|de');return false;">Alemán</a></li>
							<li><a href="#" onclick="doGTranslate('es|ru');return false;">Ruso</a></li>
						</ul>
					</li>
				</ul>
				@endif
			
		</nav>
		
		<div class="burger burger--home  small-only cursor">	<!-- ##AÑADIDO: todo el div -->
			<div class="burger__shape"></div>
			<div class="burger__shape"></div>
			<div class="burger__shape"></div>
		</div>

	</div>
		
	</header>



	<!-- MAIN CONTENT -->
	<div class="main-container">
