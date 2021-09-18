@extends('adminlte::page')

@section('title', 'Gettii Lite')

@section('css')

@stop

@section('content_header')
<div class="container-fluid form-horizontal sell-manage-show-header">
    <div class="row">
        <div class="col-md-2">
            <a href="/sell/manage" class="btn btn-info btn-lg btn-block">
                戻
            </a>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid form-horizontal sell-manage-stop">
    <div class="row">
        <div class="col-md-12 event-inf-col">
            <div class="row">    
                <div class="col-md-2 event-stop-title">
                    <label>
                        中止
                    </label>
                </div>
                <div class="col-md-10 event-stop-content">
                    {{ $eventInf['title'] }}
                </div>
            </div>
        </div>
        <div class="col-md-12 event-inf-col">
            <div class="row">    
                <div class="col-md-2 event-stop-title">
                    <label>
                        中止
                    </label>
                </div>
                <div class="col-md-4 event-stop-content">
                    {{ $eventInf['placeTitle'] }}
                </div>
                <div class="col-md-2  event-stop-title">
                    <label>
                        中止
                    </label>
                </div>
                <div class="col-md-4 event-stop-content">
                    {{ $eventInf['date'] }}
                </div>
            </div>
        </div>
        <div class="col-md-12 table-sell-manage">
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>
                            <div class="checkbox checkbox-only">
                                <label>
                                    <input v-if="infOpenIsNull" type="checkbox" value="">
                                    <span class="cr" ><i v-show="infOpenIsNull" class="cr-icon fa fa-check"></i></span>
                                </label>
                            </div>
                        </th>
                        <th>{{ trans('sellManage.S_EventOpenDate') }}</th>
                        <th>{{ trans('sellManage.S_EventOpenTime') }}</th>
                        <th>{{ trans('sellManage.S_EventTimeSlot') }}</th>
                        <th class="width-setting">{{ trans('sellManage.S_EventSellTotal') }}</th>
                        <th class="width-setting">{{ trans('sellManage.S_EventSeatTotalOther') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr class="{{ $event['status'] == 1 ?'':'red'}}">
                            <td class="text no">{{ $loop->index +1 }}</td>
                            <td class="text">
                                <div class="checkbox checkbox-only">
                                    <label>
                                        <input v-if="infOpenIsNull" type="checkbox" value="">
                                        <span class="cr" ><i v-show="infOpenIsNull" class="cr-icon fa fa-check"></i></span>
                                    </label>
                                </div>
                            </td>
                            <td class="text">{{ $event['openDay'] }}</td>
                            <td class="text">
                                {{ $event['openTime'] }}
                                <span class="stop">
                                    {{ $event['status'] == 1 ? '' : '中止' }}
                                </span>
                            </td>
                            <td class="text">{{ $event['timeSlot'] }}</td>
                            <td>
                                <div class="row">
                                    <div class="col-md-10">
                                           
                                    </div>
                                    <div class="col-md-2">
                                        <span class="table-change-btn">
                                        <a href="/sell/manage/siteMap" class="btn btn-primary btn-xs">
                                            {{ trans('sellManage.S_EventSeatImage') }}
                                        </a>
                                    </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                            <span>
                                    llll
                                </span>
                                <span class="table-change-btn">
                                    <a href="/sell/manage/siteMap" class="btn btn-primary btn-xs">
                                        {{ trans('sellManage.S_EventSeatImage') }}
                                    </a>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
            
    </div>
</div>

@stop
