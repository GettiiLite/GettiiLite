<!-- password再設定時 -->
{{  $data['contract_name'] }} 様 <br><br>

Gettii Liteをご利用いただきありがとうございます。<br><br>

@if($data['addFlg'])
  <!-- ユーザー追加 -->
  ユーザーを追加しましたのでお知らせ致します。<br>
  ユーザーID : {{  $data['account_code'] }}<br>
  パスワード : {{  $data['password'] }}<br>
@else
  パスワードを再設定しましたのでお知らせ致します。<br>
  新パスワード : {{  $data['password'] }}<br>
@endif
<br>
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=<br>
[ Gettii Lite ]<br>
<a href="{{ url('/') }}">{{  url('/') }}</a><br>
<br>
※本メールは配信専用です※<br>
このメールに返信いただいてもお答えできません。