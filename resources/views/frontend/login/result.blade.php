@extends('adminlte::page')

@section('adminlte_css')
    <link href="{{ asset('css/Login.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/font-awesome/css/all.css') }}" rel="stylesheet"/>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/vee-validate.js') }}"></script>
    @yield('css')
@stop

@section('body')
	<section class="login-section">
		<div class="login-container">
			<div class="login-wrap">
				<div class="logo-wrap logo-wrap-fix">
					<div class="logo-wrap-image"><img src="{{ URL::to('/assets/images/logo/logo.png') }}" alt="IMG"></div>
					<div class="result-txt">{{ $result['data']['apply_title'] }}</div>
				</div>
				<!-- Tip -->
				<div class="callout callout-login">
					@if($result['status']['result'])
						<span class="i-check"><i class="fas fa-check-circle fa-3x text-green"></i></span>
					@else
						<span class="i-check"><i class="fas fa-times-circle fa-3x text-red"></i></span>
					@endif
					<h5>{{ $result['data']['title'] }}</h5>
					<p>{{ $result['data']['msn'] }}</p>
				</div>
				<!-- /.Tip -->
				<div class="login-container-form-btn">
					@if($result['status']['result'])
						<button class="btn-login btn-inverse" onclick="location.href='/login'">
							{{ trans('registered.S_BackLogin') }}
						</button>
					@else
						<button class="btn-login btn-inverse" onclick="location.href='{{ $url['back_url'] }}'">
							{{ trans('registered.S_BackAccountReminder') }}
						</button>
					@endif
				</div>

			</div>
		</div>
	</section>
@stop

