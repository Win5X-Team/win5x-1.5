<style>{{ file_get_contents(asset('css/email/confirm.css')) }}</style>

<div class="header">
    <a href="https://win5x.com">Win5X</a>
</div>
<div class="block-main">
    <div class="container">
        <p>Подтверждение аккаунта Win5X</p>
        <div class="desc">Вы получили это письмо, так как кто-то зарегистрировался на <a href="https://win5x.com" class="li">win5x.com</a>, указав ваш адрес.<br>Если это были не вы, то проигнорируйте это письмо.</div>
        <div class="s"><a href="https://win5x.com/email_confirm/{{$hash}}" class="btn">Подтвердить аккаунт</a></div>
        <div class="s">Или перейдите по ссылке самостоятельно: <span style="user-select: all">https://win5x.com/email_confirm/{{$hash}}</span></div>
    </div>
</div>