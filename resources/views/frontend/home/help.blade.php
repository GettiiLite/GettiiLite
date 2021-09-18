@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('css')
    @if($events['statuc']['CSS_exist'])
        {!! html_entity_decode($events['data']['CSS_content']) !!}
    @endif
@stop

@section('content_header')

@stop

@section('content')
    @if($events['statuc']['HTML_exist'])
        {!! html_entity_decode($events['data']['HTML_content']) !!}
    @else
        <div class="help-content">
           <!-- <h3>{{trans('home.S_Welcome')}}</h3>-->
            <h3 class="mt-0">
            ヘルプ
            </h3>
<!-- Help Content -->
<div class="container container-card">
  <div class="row">
    <div class="col-sm-4 card-row">
      <div class="card --card2">
        <a class="flex-align-two-side h-145" href="assets/document/GettiiLite_guide.pdf" target="_blank">
          <div class="course-preview"></div>
          <div class="col-sm-10 position-re">
            <div class="flex-start-center-column ml-10 pr-25">
              <h2>Gettii Liteマニュアル</h2>
              <span>Gettii Liteの基本操作マニュアルです</span>
            </div>
            <div class="m-arrows">
              <div class="m-arrow m-arrow-one"></div>
              <div class="m-arrow m-arrow-two"></div>
              <i class="fas fa-file-pdf text-white fa-3x"></i>
            </div>
          </div>
          <div class="col-sm-2 flex-contents">
            <p>Document 1</p>
            <ul>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
            </ul>
            <button>
               <i class="fas fa-file-pdf text-white"></i>
            </button>
          </div>
        </a>
      </div>
      <!--/Card-->
    </div>
    <!--/col-sm-4--->
    <!--------------------------------------------------->
        <div class="col-sm-4 card-row">
          <div class="card --card3">
            <a class="flex-align-two-side h-145" href="assets/video/douga.mp4" target="_blank">
              <div class="course-preview"></div>
              <div class="col-sm-10 position-re">
                <div class="flex-start-center-column ml-10 pr-25">
                  <h2>イベント登録操作動画</h2>
                  <span>Gettii Liteのイベント登録作業を動画にしました</span>
                </div>
                <div class="m-arrows">
                  <div class="m-arrow m-arrow-one"></div>
                  <div class="m-arrow m-arrow-two"></div>
                  <i class="fas fa-play-circle text-white fa-3x"></i>
                </div>
              </div>
              <div class="col-sm-2 flex-contents">
                <p>Document 2</p>
                <ul>
                  <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
                </ul>
                <button>
                  <i class="fas fa-play-circle text-white"></i>
                </button>
              </div>
            </a><!--/flex-align-two-side-->
          </div>
          <!--/Card-->
        </div>
        <!--/col-sm-4--->   
    <!--------------------------------------------------->
    <div class="col-sm-4 card-row">
      <div class="card --card2">
        <a class="flex-align-two-side h-145" href="assets/document/resuq_manual.pdf" target="_blank">
          <div class="course-preview"></div>
          <div class="col-sm-10 position-re">
            <div class="flex-start-center-column ml-10 pr-25">
              <h2>れすQマニュアル</h2>
              <span>電子チケット「れすQ」の読み取りアプリに関する操作マニュアルです</span>
            </div>
            <div class="m-arrows">
              <div class="m-arrow m-arrow-one"></div>
              <div class="m-arrow m-arrow-two"></div>
               <i class="fas fa-file-pdf text-white fa-3x"></i>
            </div>

          </div>
          <div class="col-sm-2 flex-contents">
            <p>Document 3</p>
            <ul>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
            </ul>
            <button>
               <i class="fas fa-file-pdf text-white"></i>
            </button>
          </div>
        </a>
      </div>
      <!--/Card-->
    </div>
    <!--/col-sm-4--->
    <!--------------------------------------------------->
  </div>
</div>
<!--/-->

        </div>
    @endif
@stop
