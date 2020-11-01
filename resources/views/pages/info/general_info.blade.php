@if(Auth::guest())
    <div class="info-block">
        <div class="info-block-title">Авторизация</div>
        <div class="info-block-content">
            Перед началом игры на реальный баланс требуется авторизация.
        </div>
        <div class="info-block-content">
            До авторизации вы можете играть только в демо-режиме.
        </div>
        <div class="info-block-content">
            <a class="ll" href="javascript:void(0)" onclick="iziToast.destroy(); $('#b_si').click()">Авторизоваться</a>
        </div>
    </div>
@else
<div class="info-block">
    <div class="info-block-title">Демо-режим</div>
    <div class="info-block-content">
        Демо-режим можно включить, нажав на иконку <i class="fad fa-coins game_info-icon_info"></i>, которая находится рядом с балансом аккаунта.
    </div>
</div>
@endif