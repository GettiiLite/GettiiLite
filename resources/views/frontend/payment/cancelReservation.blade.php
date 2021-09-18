<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Gettii Lite | Transfer -Success</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/font-awesome/css/all.css') }}">
	<!-- Theme style -->
	<link rel="stylesheet" href="{{ asset('css/animate.css') }}">
	<link rel="stylesheet" href="{{ asset('css/transfer.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/Main.min.css') }}">


	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

	<!-- Google Font -->
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

{{-- <body onload="document.all.execute.click();" > --}}
<form action="{{config('app.gsdomain')}}/cart/confirm" method="get" id="ret_confirm"> 
    <input type="submit" value="submit" name="execute" style="display:none;"><br>
 </body>
</form>

<body>
	<section class="transfer-section">
		<div class="transfer-container">
			<div class="transfer-wrap">
				<div class="logo-wrap logo-wrap-fix">
					<div class="logo-wrap-image"><img src="{{ URL::to('/assets/images/logo/logo.svg') }}" alt="IMG">
					</div>
					<div class="result-txt"></div>
				</div>
				<!-- Tip -->
				<div class="callout callout-transfer text-center">
					<!-- 成功 -->
					<div class="animate-alert">
							<div class="animate-alert-icon animate-alert-error animate">
								<span class="animate-alert-x-mark">
									<span class="animate-alert-line animate-alert-left animateXLeft"></span>
									<span class="animate-alert-line animate-alert-right animateXRight"></span>
								</span>
								<div class="animate-alert-placeholder"></div>
								<div class="animate-alert-fix"></div>
							</div>
					</div>
					<!-- /.成功 -->

					<h5>{{ trans('common.S_PayCancel') }}</h5>
                    <p>{{ trans('common.S_PayCancel_1') }}</p>
                    <p>{{ trans('common.S_PayCancel_2') }}</p>
                    <p>{{ trans('common.S_PayCancel_3') }}</p>
					<!-- 返回按鈕 -->
					<div class="transfer-container-btn">
						<button class="btn waves-effect waves-light btn-block btn-rounded btn-inverse" type="submit" form="ret_confirm" value="submit">
							<i class="fas fa-reply"></i> {{ trans('common.S_Back') }}
						</button>
					</div>
					<!-- /.返回按鈕 -->
				</div>
				<!-- /.Tip -->

			</div>
		</div>
	</section>
	<!-- Font Awesome -->
	<script defer src="{{ asset('css/font-awesome/js/all.js') }}"></script>

</body>

</html>