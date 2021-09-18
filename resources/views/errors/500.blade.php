
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="{{ asset('images/icons/favicon.ico') }}" type="image/png" rel="icon"/>
    <link href="{{ asset('css/error-style.css') }}" type="text/css" rel="stylesheet"/>
</head>
<body class="bg-main">
        <div class="container">
            <!-- logo -->
            <div class="navbar">
                <div class="logo">
                    <a href="#"><img src="{{ URL::to('/assets/images/error/logo.png') }}"  width="150px"></a>
                </div>
            </div>
            <!-- /.logo -->
            <!-- 標題文字區塊 -->
            <div class="error500-txt-box">
                <h2 class="animate-reveal animate-first">500.</h2>
                <h3 class="animate-reveal animate-second">  
                    {{ trans('error.S_errorTitle') }}
                </h3>
                <p class="animate-reveal animate-third">
                    {{ trans('error.S_errorMainMesseger') }}
                </p>
            </div>
            <!-- /.標題文字區塊 -->
            <!--主視覺 -->
            <div class="main-body">
                <img class="main-img" src="{{ URL::to('/assets/images/error/main.svg') }}"  width="800px">
            </div>
            <!--/.主視覺 -->
            <div class="objects">
                <div class="box-screw">

                    <img class="object-screw" src="{{ URL::to('/assets/images/error/screw.svg') }}" width="80px">
                </div>
                <div class="box-man">
                    <!--<img class="object-man-left" src="img/man01.svg" width="200px">-->
                    <img class="object-man-right" src="{{ URL::to('/assets/images/error/man02.svg') }}" width="300px">
                </div>
            </div>
            <div class="dot-groups">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>

            </div>
            <div class="footer">
                <p>Copyright © 2019 <a href="#">Gettii Lite</a>. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>

