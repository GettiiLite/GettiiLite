@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('content_header')
    
@stop

@section('content')
<div class="messages-content text-center">
		<h3>{{ $json['title'] }}</h3>
	
		@if ($json['status'] == 1)
			<p class="text-green">{{ $json['messeger'] }}</p>
		@elseif ($json['status'] == 2)
			<p class="text-red">{{ $json['messeger'] }}</p>
		@else
			<p class="text-red">-</p>
		@endif

		<p class="lead text-center">
			<span>
			{!! $json['messeger_detail'] !!}
			</span>
		</p>
	</div>
</div>
@stop