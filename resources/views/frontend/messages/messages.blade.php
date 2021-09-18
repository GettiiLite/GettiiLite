<div class="messages-content messages-save-content">
    <h3>{{ $msn }}</h3>
    <p class="lead text-left">
        {{ trans('member.S_GetError') }}
    </p>
    <ol class="result-tab-title">
        <li>{{ trans('member.S_basic') }}
            <ul class="result-tab-subtitle">
            <!-- 若有需要標註的文字可以用 <span class="text-blue"> 文字 </span>-->
            <li><span class="text-blue">( 代碼 089578897 )</span></li>
            <li>{{ trans('member.S_Message') }}</li>
            </ul>
        </li>
    </ol>
    {{ $slot }}
</div>