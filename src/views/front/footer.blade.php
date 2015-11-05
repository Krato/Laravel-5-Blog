</div>

	
	<footer id="footer" class="bg all-w" style="background-image: url({{asset('/assets/img/fondo-footer.jpg') }})">
			<div class="wrapper">
				
				<!-- Menus footer -->
				<div class="xs-12 m-12 l-1 col false"></div>

				<div class="xs-12 m-4 l-4 col"> <!-- ##MODIFICADO: clases modificadas -->
					<h4 class="small-hide">Usuarios</h4> <!-- ##MODIFICADO: clases añadidas -->
					@if(Auth::check())
					<ul>
						<li><a href="{{ url(LaravelLocalization::transRoute('routes.user/profile')) }}">{{Lang::get('front/menu.logged.control-panel')}}</a></li>
						<?php
						if (Auth::user()->hasRole('admin') ){
						?>
						<li><a href="{{ url(LaravelLocalization::transRoute('routes.admin')) }}">{{Lang::get('menu.administration.administration')}}</a></li>
						<?php } ?>
						<li><a href="{{ url(LaravelLocalization::transRoute('routes.auth/logout')) }}">{{Lang::get('front/menu.logged.close-session')}}</a></li>
					</ul>
					@else
					<ul>
						<li><a href="#" class="js-login-register">Registro</a></li>
						<li><a href="#" class="js-login-access">Iniciar Sesión</a></li>
					</ul>
					@endif	
				</div>

				<div class="xs-12 l-1 col false  medium-hide"></div> <!-- ##MODIFICADO: clases modificadas -->

				<div class="xs-12 m-4 l-3 col small-hide"> <!-- ##MODIFICADO: clases modificadas -->
					<h4>Children Friendly</h4>
					<ul>
						<li><a href="{{ url('informacion') }}">{{Lang::get('front/menu.info')}}</a></li>
						<li><a href="{{ url('contacto') }}">Contacto</a></li>
						<li><a href="{{ url(LaravelLocalization::getURLFromRouteNameTranslated(Config::get('app.locale'),'routes.public_blog')) }}">Blog</a></li>
					</ul>
				</div>

				<div class="xs-12 m-4 l-3 col pt-70  small-hide"> <!-- ##MODIFICADO: clases modificadas -->
					<ul>
						@if(Auth::check())
							@if($currentUser->hasRole('hotel'))
							<li><a href="{{url('registrar_hotel')}}">Registra tu Hotel</a></li>
							@else
							<li><a href="{{url('registrar_empresa')}}">Registra tu Hotel</a></li>
							@endif
						@else
						<li><a href="{{url('registrar_empresa')}}">Registra tu Hotel</a></li>
						@endif
						<li><a href="{{url('politica-de-privacidad')}}">Política de privacidad</a></li>
						<li><a href="{{url('politica-de-privacidad')}}">Aviso legal</a></li>
					</ul>
				</div>
				<!-- Fin menus footer -->

			</div>
			<div id="social-footer" class="wrapper mt-40 mb-40">
				
				<!-- Redes sociales -->
				<div class="xs-12 col text-center">
					<ul class="text-center">
						<li><a href="https://twitter.com/byqualitygaia"target="_blank"><i class="icon-twitter"></i></a></li>
						<li><a href="http://facebook.com/qualitygaia" target="_blank"><i class="icon-facebook"></i></a></li>
						<li><a href="http://www.enjoygram.com/children_friendly" target="_blank"><i class="icon-instagram"></i></a></li>
						<li><a href="https://plus.google.com/109904470576408640235/videos" target="_blank"><i class="icon-google"></i></a></li>
						<li><a href="https://www.youtube.com/channel/UCw9rMGzul1_gzZcSHcVpvTw" target="_blank"><i class="icon-youtube"></i></a></li>
					</ul>
				</div>

			</div>
			<div class="wrapper">
				
				<!-- Copyright -->
				<div class="xs-12 col text-center">
					<p>Children Friendly es una marca registrada de <a href="http://www.qualitygaia.com" target="_blank">Quality Gaia</a>. Todos los derechos reservados © 2015</p>
				</div>

			</div>
		</footer>

	@if(!Auth::check())
	<div id="login" class="modal-login__container {{ (count($errors) > 0) ? 'show' : '' }}">
		<i class="icon-close"></i>
		<div class="login round  modal-login">
			
			<div class="login-social">
				<a href="{{ URL::to('login/facebook')}}" class="button facebook">Inicia sesión con Facebook</a>
				<a href="{!!URL::to('login/google')!!}" class="button google">Inicia sesión con Google</a>
			</div>
			
			<div class="separacion">
				<span>0</span>
			</div>

			@if (count($errors) > 0)
                @foreach ($errors->all() as $error)
                <div class="error"><p>{{ $error }}</p></div>
                @endforeach
			@endif
			
			<div class="modal-login__form">
				<form method="post" action="{{ url('actions/acceder') }}" id="form-login" class="checkbox-custom">
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<input type="email" name="email" class="input-email" id="email" placeholder="Correo Electrónico">
					<input type="password" name="password" class="input-password" id="password" placeholder="Contraseña">
					<input type="checkbox" id="recordad" name="remember">
					<label for="recordad"><span class="checkbox"></span> <p>Recordar la sesión</p></label>

					<a href="#" id="olvidaste" class="text-center js-login-reset">¿Olvidaste tu contraseña?</a>

					<input type="submit" class="button" value="Iniciar sesión">


				</form>
			</div>

			<div class="separacion"></div>

			<div class="text-center">
				<p>¿no tienes cuenta de usuario?</p>
				<a href="#" class="js-login-register">¡Registrate!</a>
			</div>

		</div>
	</div>


	<div id="registro" class="modal-login__container {{ (Session::has('error_register')) ? 'show' : '' }} ">
		<i class="icon-close"></i>
		<div class="login round  modal-login">
			
			<div class="login-social">
				<a href="{!!URL::to('login/facebook')!!}" class="button facebook">Resgistro con Facebook</a>
				<a href="{!!URL::to('login/google')!!}" class="button google">Resgistro con Google</a>
			</div>
			
			<div class="separacion">
				<span>0</span>
			</div>
			
			@if(Session::has('error_register'))
				<div class="error"><p>{{ Session::get('error_register') }}</p></div>
			@endif
			
			<div class="modal-login__form">
				<form action="{{ url('actions/registrar') }}" method="post" class="checkbox-custom">
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<input type="email" name="email" class="input-email" id="email" placeholder="Correo Electrónico">
					<input type="password" name="password" class="input-password" id="password" placeholder="Contraseña">
					<input type="submit" class="button" value="Registro con Correo Electrónico">
				</form>
			</div>

			<div class="separacion"></div>

			<div class="text-center">
				<p>¿Ya tienes cuenta?</p>
				<a href="#" class="js-login-access">Accede a Children Friendly</a>
			</div>

			<div class="text-center">
				<p class="p-smaller">Al registrarme acepto las <a href="">Condiciones de servicio</a> y la <a href="">Política legal y de Cookies</a></p>
			</div>



		</div>
	</div>

	<div id="contrasena-mail" class="modal-login__container pt-80">
		<i class="icon-close"></i> <!-- ##AÑADIDO: para cerrar -->
		<div class="login round  modal-login">
			
			<div class="text-center">
				<p class="p-small  pb-30">Dinos tu correo electrónico y te enviaremos un correo para que puedas restablecer tu contraseña.</p>
			</div>
			
			
			
			<div class="modal-login__form">
				<form action="{{ url('reset_password') }}" method="POST">
					<input type="email" name="email" class="input-email" id="contra-mail" placeholder="Email">
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<input type="submit" class="button" value="Enviar datos">
				</form>
			</div>

		</div>
	</div>
	<div id="contrasena-reset" class="modal-login__container pt-80  {{ (Session::has('token_cambia')) ? 'show' : '' }}">
		<i class="icon-close"></i> <!-- ##AÑADIDO: para cerrar -->
		<div class="login round  modal-login">
			
			<div class="text-center">
				<p class="p-small  pb-30">Sólo te queda un paso. Elige tu nueva contraseña y accede a todas las ventajas Children Friendly.</p>
			</div>
			
			@if(Session::has('errors_reset'))
				@foreach(Session::get('errors_reset') as $error)
				<div class="error"><p>{{ $error[0] }}</p></div>
				@endforeach
			@endif
			
			<div class="modal-login__form">
				<form action="{{ url('change_password') }}" method="POST">
					<input type="email" name="email" class="input-email" id="contra-mail" placeholder="Email">
					<input type="password" name="password" class="input-password" id="contra-password" placeholder="Nueva contraseña">
					<input type="password" name="password_confirmation" class="input-password" id="contra-new-password" placeholder="Repite la contraseña">
					<input type="hidden" name="token" value="{{ Session::get('token_cambia') }}">
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}">

					<input type="submit" class="button" value="Restablecer contraseña">

				</form>
			</div>

		</div>
	</div>

	<div id="contrasena-confirmación" class="modal-login__container pt-80  {{ (Session::has('enviado_reset')) ? 'show' : '' }}">
		<i class="icon-close"></i> <!-- ##AÑADIDO: para cerrar -->
		<div class="login round  modal-login">
			
			<div class="text-center">
				<h3>Correo enviado</h3>
				<p class="p-small  pb-30">Te hemos enviado un correo con el link para resetear tu contraseña. Revisa tu correo para seguir los pasos.</p>
			</div>
			
			<div class="text-center"><a href="" class="js-login-close  text-center">Cerrar</a></div>

		</div>
	</div>
	
	@else
	<div id="contrasena-confirmación" class="modal-login__container pt-80  {{ (Session::has('cambia_pass_ok')) ? 'show' : '' }}">
		<i class="icon-close"></i> <!-- ##AÑADIDO: para cerrar -->
		<div class="login round  modal-login">
			
			<div class="text-center">
				<h3>Contraseña cambiada</h3>
				<p class="p-small  pb-30">¡Enhorabuena! Has cambiado la contraseña correctamente.</p>
			</div>
			
			<div class="text-center"><a href="" class="js-login-close  text-center">Cerrar</a></div>

		</div>
	</div>
	<div id="confirmar-datos" class="modal-login__container {{ (Session::has('open_confirm')) ? 'show' : '' }} 	">
		<i class="icon-close"></i>
		<div class="login round  modal-login">
			
			<div class="text-center">
				<h3>¡Hola!</h3>
				<p class="p-small  pb-30">Ya eres miembro de Children Friendly. Si deseas comentar los hoteles y disfrutar de otras ventajas necesitas rellenar los siguientes datos:</p>
			</div>
			
			<div class="modal-login__form">
				<form action="{{ url(LaravelLocalization::transRoute('routes.user/profile_home'))}}" method="post" class="checkbox-custom">
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<?php
						if (Auth::check()){
							$name = "";
							if(!is_null($currentUser->profile->name)){
								$name = $currentUser->profile->name;
							}

							$lastname = "";
							if(!is_null($currentUser->profile->lastname)){
								$lastname = $currentUser->profile->lastname;
							}

							$phone = "";
							if(!is_null($currentUser->profile->phone)){
								$phone = $currentUser->profile->phone;
							}

							$address = "";
							if(!is_null($currentUser->profile->address)){
								$address = $currentUser->profile->address;
							}
						} else {
							$name = "";
							$lastname = "";
							$phone = "";
							$address = "";
						}
					?>
					<input type="text" name="name" class="input-person" id="confirm-name" placeholder="Nombre" value="{{$name}}">
					<input type="text" name="lastname" class="input-person" id="confirm-surname" placeholder="Apellidos" value="{{$lastname}}">
					<input type="text" name="phone" class="input-phone mb-0 hide" id="phone" placeholder="Teléfono" value="{{$phone}}">
					<p><i class="icon-reservas mr-10"></i> Fecha de Nacimiento:</p>
					<?php
						$date =  new \Jenssegers\Date\Date;
						if (Auth::check()){
							if($currentUser->profile->birthday != null){
								$fecha = $date::createFromFormat('Y-m-d', $currentUser->profile->birthday);
								$dia = $fecha->format('d');
								$mes = $fecha->format('n');
								$year = $fecha->format('Y');
							} else {
								$dia = 0;
								$mes = 0;
								$year = 0;
							}
						} else {
							$dia = 0;
							$mes = 0;
							$year = 0;
						}	
					?>
					<div class="input-third__parent">	
						<select name="day" id="day" class="input-third input__select">
							<option value="0">Dia</option>
							@for ($i = 1; $i <= 31; $i++)
							<option value="{{str_pad($i, 2, '0', STR_PAD_LEFT)}}" {{($dia == $i) ? 'selected' : ''}}>{{str_pad($i, 2, '0', STR_PAD_LEFT)}}</option>
							@endfor
						</select>
						<?php setlocale(LC_ALL, 'es_ES'); ?>
						<select style="text-transform: capitalize;" class="input-third input__select" name="month">
							<option value="0" {{ ($mes == 0) ? 'selected="selected"' : '' }}>Mes</option>
							<option value="1" {{ ($mes == 1) ? 'selected="selected"' : '' }}>enero</option>
							<option value="2" {{ ($mes == 2) ? 'selected="selected"' : '' }}>febrero</option>
							<option value="3" {{ ($mes == 3) ? 'selected="selected"' : '' }}>marzo</option>
							<option value="4" {{ ($mes == 4) ? 'selected="selected"' : '' }}>abril</option>
							<option value="5" {{ ($mes == 5) ? 'selected="selected"' : '' }}>mayo</option>
							<option value="6" {{ ($mes == 6) ? 'selected="selected"' : '' }}>junio</option>
							<option value="7" {{ ($mes == 7) ? 'selected="selected"' : '' }}>julio</option>
							<option value="8" {{ ($mes == 8) ? 'selected="selected"' : '' }}>agosto</option>
							<option value="9" {{ ($mes == 9) ? 'selected="selected"' : '' }}>septiembre</option>
							<option value="10" {{ ($mes == 10) ? 'selected="selected"' : '' }}>octubre</option>
							<option value="11" {{ ($mes == 11) ? 'selected="selected"' : '' }}>noviembre</option>
							<option value="12" {{ ($mes == 12) ? 'selected="selected"' : '' }}>diciembre</option>
						</select>
						{!! Form::selectYear('year', 1930, date('Y') - 12, $year,['class' => 'input-third input__select', 'prepend' => 'Año']) !!}
					</div>

					<input type="text" name="address" class="input-marker" id="address" placeholder="Ciudad" value="{{$address}}">
					<input type="hidden" name="city" id="city" >
					<input type="submit" class="button" value="Confirmar datos">
				</form>
			</div>

			<div class="separacion">
				<span>0</span>
			</div>

			<div class="text-center">
				<p>Rellenaré los datos más adelante</p>
				<a href="#" class="js-login-close">Continuar a Children Friendly</a>
			</div>





		</div>
	</div>
	@endif

	<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	@if(Session::has('maps_en'))
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places&language=en"></script>
	@else
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>	
	@endif
	
	<script src="{{ asset('/js/main.min.js') }}" rel="stylesheet"></script>
	<script src="{{ asset('/js/home_profile.js') }}" rel="stylesheet"></script>
	<script>

		// $(".checkbox-custom").submit(function(e){
		// 	e.preventDefault();

		// 	var datos = {
	 //            email : $("#login").find("#email").val(),
	 //            password : $("#login").find("#password").val(),
	 //        }
	 //        $.ajax({
	 //            url: "{{ url(LaravelLocalization::transRoute('routes.auth/access')) }}",
	 //            beforeSend: function (request){
	 //                request.setRequestHeader("X-CSRF-TOKEN", $('[name="csrf_token"]').val());
	 //            },
	 //            type: 'post',
	 //            cache: false,
	 //            dataType: 'json',
	 //            data: datos,
	 //            success: function(data) {
	 //                console.log(data);
	 //            },
	 //            error: function(data){
	 //            	console.log(data);
	 //                var errors = data.responseJSON;
  //       			console.log('server errors',errors);
	 //            }
	 //        });

		// 	return false;
		// })




    </script>
	<script>
	 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	 m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	 })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	 ga('create', 'UA-65324775-1', 'auto');
	 ga('send', 'pageview');

	</script>
	<script type="text/javascript">
	/* <![CDATA[ */
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('6 7(a,b){n{4(2.9){3 c=2.9("o");c.p(b,f,f);a.q(c)}g{3 c=2.r();a.s(\'t\'+b,c)}}u(e){}}6 h(a){4(a.8)a=a.8;4(a==\'\')v;3 b=a.w(\'|\')[1];3 c;3 d=2.x(\'y\');z(3 i=0;i<d.5;i++)4(d[i].A==\'B-C-D\')c=d[i];4(2.j(\'k\')==E||2.j(\'k\').l.5==0||c.5==0||c.l.5==0){F(6(){h(a)},G)}g{c.8=b;7(c,\'m\');7(c,\'m\')}}',43,43,'||document|var|if|length|function|GTranslateFireEvent|value|createEvent||||||true|else|doGTranslate||getElementById|google_translate_element2|innerHTML|change|try|HTMLEvents|initEvent|dispatchEvent|createEventObject|fireEvent|on|catch|return|split|getElementsByTagName|select|for|className|goog|te|combo|null|setTimeout|500'.split('|'),0,{}))
	/* ]]> */
	</script>
	<div id="google_translate_element2"></div>
	<script type="text/javascript">function googleTranslateElementInit2() {new google.translate.TranslateElement({pageLanguage: 'es', autoDisplay: false}, 'google_translate_element2');}</script>
	<script type="text/javascript" src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2"></script>
	
	@yield('custom_js')
  </body>
</html>
