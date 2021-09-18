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
    @component('components/remind', ['remind_code' => $events['data']['remind_code']])
    @endcomponent
    @if($events['statuc']['HTML_exist'])
        {!! html_entity_decode($events['data']['HTML_content']) !!}
    @else
        <div class="messages-content text-center">
            <h3>{{trans('home.S_Welcome')}}</h3>
            <h1 class="text-green text-space-6x">Gettii Lite </h1>
            <div class="mt-5">
        <a id="help-pdf" type="button" class="btn waves-effect waves-light btn-rounded btn-info-outline btn-ll m-r-10" href="assets/document/GettiiLite_guide.pdf" target="_blank"
> Gettii Liteマニュアル <i class="fas fa-file-pdf help-i-text"></i> 
</a>
        <a id="help-video" type="button" class="btn waves-effect waves-light btn-rounded btn-info-outline btn-ll"  href="assets/video/douga.mp4" target="_blank"
> イベント登録操作動画 <i class="fas fa-play-circle help-i-text"></i></a>
        </div>
<!-- notice content -->   
        </div>
    @endif
@stop
