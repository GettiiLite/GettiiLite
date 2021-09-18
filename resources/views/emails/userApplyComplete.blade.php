
{{  $data['contract_name'] }} 様 <br><br>

Gettii Liteをご利用いただきありがとうございます。
<br><br>
@if($data['reviewStatus']  == 9 )
  ご登録いただいた情報をもとに会員審査が完了しましたのでお知らせいたします。
@else
  審査の結果、誠に申し訳ございませんが、Gettii Liteのご利用を停止させていただきます。
@endif
<br>
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=<br>
[ Gettii Lite ]<br>
<a href="{{ url('/') }}">{{  url('/') }}</a><br>
<br>
※本メールは配信専用です※<br>
このメールに返信いただいてもお答えできません。