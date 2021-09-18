@extends('adminlte::page')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    <link href="{{ asset('css/adminLTE_Customc.css') }}" rel="stylesheet"/>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/vee-validate.js') }}"></script>
    @yield('css')
@stop

@section('body')

<div style="padding: 20px 0 40px 0;">
    <div class="container form-horizontal">
        <div class="row">
            <div class="col-md-8 col-md-offset-4">
                <img class="nonseat wspace" src="{{ URL::to('/assets/images/logo/logo.png') }}">
            </div>
            <div >
                <h1>
                    {{ trans('common.S_PayFailed_1') }}
                    {{ trans('common.S_PayFailed_2') }}
                    {{ trans('common.S_PayFailed_3') }}
                </h1>
            </div>
        </div>
    </div>
</div>
<br>
<style>
.fade-enter-active {
  transition: all .5s ease-in;
}
.fade-enter {
  transform: translateY(-20px);
  opacity: 0
}
</style>
@stop

@section('adminlte_js')

@stop
