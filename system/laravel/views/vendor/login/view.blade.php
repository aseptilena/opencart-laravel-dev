<div class="content">
  <ul class="list-unstyled">
  	@if ($status == 5)
    <li>正在審核中</li>
    @else
    <li>請使用你當時註冊的開店帳號密碼來登入，若忘記密碼，請連絡站方<a href="{{ $admin_url }}">登入開店後台</a></li>
    @endif
  </ul>
</div>