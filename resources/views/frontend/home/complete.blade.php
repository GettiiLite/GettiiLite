@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
@stop

@section('body')
<div class="container form-horizontal">
    <div class="row">
        <div class="col-md-8 col-md-offset-4">
            <img class="nonseat wspace" src="{{ URL::to('/assets/images/logo/logo.png') }}">
        </div>
        <div class="col-md-3 col-md-offset-5">
            <h2>
                {{trans('home.S_ApplicationCompleted')}}
            </h2>
        </div>
    </div>
</div>
<div class="container-fluid form-horizontal">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <!-- adjusted by translation s
            <table class="table  table-bordered">
                <tbody>
                <tr>
                    <td>申請作業説明</td>
                    <td>申請作業説明</td>
                </tr>
                <tr>
                    <td>申請作業説明</td>
                    <td>申請作業説明申請作業説明申請作業説明</td>
                </tr>
                <tr>
                    <td>申請作業説明</td>
                    <td>申請作業説明申請作業説明申請作業説明申請作業説明申請作業説明申請作業説明</td>
                </tr>
                </tbody>
            </table>
            !-->
            <p class="text-center text-green">
	        <span>
		    {!!trans('home.S_ApplicationMessage')!!}
		</span>
            </p>
            <!-- adjusted by translation e !-->
        </div>
    </div>
</div>
<br>
<div class="container form-horizontal">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <a href="/" class="btn btn-primary btn-lg btn-block" >{{trans('home.S_BackToHome')}}</a>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')

@stop
