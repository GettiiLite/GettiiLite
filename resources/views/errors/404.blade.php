<!DOCTYPE html>
<html lang="en">

<head>
	<title>Gettii Lite ｜ 404 Error Page</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="{{ asset('images/icons/favicon.ico') }}" type="image/png" rel="icon"/>
    <link href="{{ asset('css/error-style.css') }}" type="text/css" rel="stylesheet"/>
</head>

<body>
	<div class="error-section">
		<!-- logo -->
		<div class="navbar">
			<div class="logo">
				<a href="#"><img src="{{ URL::to('/assets/images/error/logo.png') }}" width="150px"></a>
			</div>
		</div>
		<!-- /.logo -->
		<div class="container">
			<div class="error-wrap">
				<!-- 主視覺 -->
				<div class="error404-img js-tilt ">
					<img src="{{ URL::to('/assets/images/error/icon-404.svg') }}" alt="IMG">
				</div>
				<!--/.主視覺 -->
				<!-- 標題文字區塊 -->
				<div class="error404-txt-box">
					<h2 class="animate-reveal animate-first">404.</h2>
					<h3 class="animate-reveal animate-second">
						{{ trans('error.S_404ErrorTitle') }}
					</h3>
					<p class="animate-reveal animate-third">
						{{ trans('error.S_404ErrorMainMesseger') }}
					</p>
				</div>
				<!-- /.標題文字區塊 -->
			</div>
		</div>
		<div class="footer">
			<p>Copyright © 2019 <a href="#">Gettii Lite</a>. All rights reserved.</p>
		</div>
	</div>
	<!--===============================================================================================-->
	<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('js/tilt.jquery.min.js') }}"></script>
	<script>
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
	<!--===============================================================================================-->


</body>

</html>