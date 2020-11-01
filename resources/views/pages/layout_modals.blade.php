<div class="md-modal-wrapper">
@if(!Auth::guest() && !Auth::user()->isActivated())
    <div class="md-modal md-email-activation md-s-height md-show md-effect-1">
        <div class="md-content">
            <div class="email-icon">
                <i class="fal fa-at"></i>
            </div>
            <div class="email-container">
                <div>Подтверждение Email</div>
                <div>На {{Auth::user()->email}} было отправлено письмо с ссылкой на подтверждение аккаунта.</div>
                <div class="email_links">
                    <a href="javascript:void(0)" onclick="resend_email()" class="ll">Отправить заного</a>
                    <a href="javascript:void(0)" onclick="window.location.href='/logout'" class="ll" style="margin-left: 5px">Выйти из аккаунта</a>
                </div>
            </div>
        </div>
    </div>
@endif

@if(Auth::guest())
    <div class="md-modal md-s-height md-auth md-effect-1">
        <div class="md-content">
            <div class="modal-ui-block" style="display: none">
                <div class="profile-loader">
                    <div></div>
                </div>
            </div>

            <i class="fal fa-times md-close" onclick="$('.md-auth').toggleClass('md-show', false)"></i>
            <div class="sport-bet-tabs tabs-ignore-scroll auth-tabs">
                <div class="sport-bet-tab sport-bet-tab-active auth-tab-active auth-tab" data-auth-action="auth">
                    <span>Авторизация</span>
                    <div class="sport-bet-tab-indicator"></div>
                </div>
                <div class="sport-bet-tab auth-tab" data-auth-action="register">
                    <span>Регистрация</span>
                    <div class="sport-bet-tab-indicator"></div>
                </div>
            </div>

            <div class="login_fields">
                <div class="login_fields__user" id="email" style="display: none">
                    <div class="icon email-l-icon">
                        <i class="fal fa-at"></i>
                    </div>
                    <input id="_email" placeholder="Email" type="text">
                    <div class="validation">
                        <img src="/storage/img/tick.png" alt="">
                    </div>
                    <i class="fas fa-info-circle register-email-info tooltip" title="Используйте настоящий Email, так как на него будет отправлено письмо с ссылкой на подтверждение."></i>
                </div>
                <div class="login_fields__user">
                    <div class="icon user-icon">
                        <img src="/storage/img/user_icon_copy.png" alt="">
                    </div>
                    <input id="_login" placeholder="Логин" type="text">
                    <div class="validation">
                        <img src="/storage/img/tick.png" alt="">
                    </div>
                </div>
                <div class="login_fields__password">
                    <div class="icon password-icon">
                        <img src="/storage/img/lock_icon_copy.png" alt="">
                    </div>
                    <input id="_password" placeholder="Пароль" type="password">
                    <div class="validation">
                        <img src="/storage/img/tick.png" alt="">
                    </div>
                </div>
                <div class="login_fields__submit">
                    <input type="submit" id="l_b" value="Войти">
                </div>
            </div>

            <div class="social_auth_desc">Войти через социальную сеть:</div>
            <div class="social_auth_block">
                <div class="through_social through_vk" onclick="socialAuth('vk')">
                    <i class="fab fa-vk"></i> <span class="hidden-xs">ВКонтакте</span>
                </div>
                <div class="through_social through_google" onclick="socialAuth('google')">
                    <i class="fab fa-google"></i> <span class="hidden-xs">Google</span>
                </div>
                <div class="through_social through_facebook" onclick="socialAuth('facebook')">
                    <i class="fab fa-facebook"></i> <span class="hidden-xs">Facebook</span>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="md-modal md-s-height md-wallet md-effect-1">
        <div class="md-content">
            <div class="modal-ui-block" style="display: none">
                <div class="profile-loader">
                    <div></div>
                </div>
            </div>

            <i class="fal fa-times md-close" onclick="$('.md-wallet').toggleClass('md-show', false)"></i>

            <div class="mm_header_tab mm_header_tab_active" data-tab="#pay">
                Пополнение
                <div></div>
            </div>
            <div class="mm_header_tab" data-tab="#with">
                Вывод
                <div></div>
            </div>

            <div class="wallet-tab-content">
                <div class="mm_general_tab mm_general_tab_active" id="pay">
                    <div class="col-xs-12 col-sm-4 payment-method-table p1">
                        <div class="nano">
                            <div class="nano-content">
                                <div class="payment-method payment-method_active" data-wallet-type="1" data-provider="63">
                                    <div class="payment-method-icon"><img data-src="/storage/img/qiwi_color.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">Qiwi</div>
                                </div>
                                <div class="payment-method" data-wallet-type="2" data-provider="45">
                                    <div class="payment-method-icon"><img data-src="/storage/img/ym_color.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">Яндекс.Деньги</div>
                                </div>
                                <div class="payment-method" data-wallet-type="3" data-provider="160">
                                    <div class="payment-method-icon"><img data-src="/storage/img/visa-mc_color.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">Банк. карта</div>
                                </div>
                                <div class="payment-method" data-wallet-type="4" data-provider="82">
                                    <div class="payment-method-icon"><img data-src="/storage/img/mf_color.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">Мегафон</div>
                                </div>
                                <div class="payment-method" data-wallet-type="5" data-provider="84">
                                    <div class="payment-method-icon"><img data-src="/storage/img/mts_color.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">МТС</div>
                                </div>
                                <div class="payment-method" data-wallet-type="6" data-provider="83">
                                    <div class="payment-method-icon"><img data-src="/storage/img/beeline_color.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">Билайн</div>
                                </div>
                                <div class="payment-method" data-wallet-type="7" data-provider="132">
                                    <div class="payment-method-icon"><img data-src="/storage/img/tele2_color.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">Tele2</div>
                                </div>
                                <div class="payment-method" data-wallet-type="8" data-provider="114">
                                    <div class="payment-method-icon"><img data-src="/storage/img/payeer.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">PAYEER</div>
                                </div>
                                <div class="payment-method" data-wallet-type="9" data-provider="150">
                                    <div class="payment-method-icon"><img data-src="/storage/img/adv_.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">Advcash</div>
                                </div>
                                <div class="payment-method" data-wallet-type="10" data-provider="64">
                                    <div class="payment-method-icon"><img data-src="/storage/img/perfect-money.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">PerfectMoney</div>
                                </div>
                                <div class="payment-method" data-wallet-type="11" data-provider="180">
                                    <div class="payment-method-icon"><img data-src="/storage/img/exmo.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">EXMO</div>
                                </div>
                                <div class="payment-method" data-wallet-type="12" data-provider="165">
                                    <div class="payment-method-icon"><img data-src="/storage/img/zcash.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">Zcash</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-8 payment-method-table p2">
                        @if($settings->payment_disabled == 0 || Auth::user()->chat_role == 3)
                            <div class="payment-content-title">
                                <img alt="" src="/storage/img/qiwi_color.png?v={{$version}}" id="wallet_icon">
                                <span id="wallet_name">Qiwi</span>
                            </div>
                            <div class="payment-value">
                                <label>Сумма</label>
                                <input data-number-input="true" class="b_input_s" id="payment" value="{{$settings->min_in}}" placeholder="Сумма пополнения">
                                <div class="payment-value-desc">
                                    <div>Минимальная сумма: <a href="javascript:void(0)" onclick="$('#payment').val({{$settings->min_in}})">{{$settings->min_in}} руб.</a></div>
                                    <div>Максимальная сумма: <a href="javascript:void(0)" onclick="$('#payment').val(15000)">15000 руб.</a></div>
                                </div>
                            </div>
                            <div class="payment-button" id="payin">Пополнить счет</div>
                            <div class="payment-help">
                                <div class="payment-help-title walletHelp"><i class="fad fa-headset"></i> Служба поддержки</div>
                                <div class="payment-help-desc walletHelp mb12">
                                    В случае возниковения проблем свяжитесь с <a href="https://vk.com/playintm" target="_blank" class="ll">службой поддержки</a>
                                </div>
                                <div class="payment-help-title walletFast"><i class="fad fa-bolt"></i> Моментальные пополнения</div>
                                <div class="payment-help-desc walletFast">
                                    Играть можно сразу же после пополнения счета!
                                </div>
                            </div>
                        @else
                            <div class="payment-disabled">
                                Платежи временно отключены
                            </div>
                        @endif
                    </div>
                </div>
                <div class="mm_general_tab" id="with">
                    <div class="col-xs-12 col-sm-4 payment-method-table p1">
                        <div class="nano">
                            <div class="nano-content">
                                <div class="payment-method payment-method_active" data-withdraw-type="4">
                                    <div class="payment-method-icon"><img data-src="/storage/img/qiwi_color.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">Qiwi</div>
                                </div>
                                <div class="payment-method" data-withdraw-type="5">
                                    <div class="payment-method-icon"><img data-src="/storage/img/ym_color.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">Яндекс.Деньги</div>
                                </div>
                                <div class="payment-method" data-withdraw-type="6">
                                    <div class="payment-method-icon"><img data-src="/storage/img/mf_color.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">Мегафон</div>
                                </div>
                                <div class="payment-method" data-withdraw-type="8">
                                    <div class="payment-method-icon"><img data-src="/storage/img/mts_color.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">МТС</div>
                                </div>
                                <div class="payment-method" data-withdraw-type="9">
                                    <div class="payment-method-icon"><img data-src="/storage/img/beeline_color.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">Билайн</div>
                                </div>
                                <div class="payment-method" data-withdraw-type="7">
                                    <div class="payment-method-icon"><img data-src="/storage/img/tele2_color.png?v={{$version}}" class="lazyload"></div>
                                    <div class="payment-method-name">Tele2</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-8 payment-method-table p2">
                        <div class="payment-content-title">
                            <img alt="" src="/storage/img/qiwi_color.png?v={{$version}}" id="withdraw_icon">
                            <span id="withdraw_name">Qiwi</span>
                        </div>
                        <div class="payment-value">
                            <label>Сумма</label>
                            <input data-number-input="true" class="b_input_s" id="withv" value="{{$settings->min_with}}" placeholder="Сумма вывода">
                            <div class="payment-value-desc">
                                <div>Минимальная сумма: <a href="javascript:void(0)" onclick="$('#withv').val({{$settings->min_with}})">{{$settings->min_with}} руб.</a></div>
                            </div>
                        </div>
                        <div class="payment-value">
                            <input class="b_input_s" id="purse" placeholder="Кошелек">
                        </div>
                        <div class="payment-button" id="payout">Вывести</div>
                        <div class="payment-help walletPayout">
                            <div class="payment-help-title"><i class="fad fa-clock"></i> Быстрые выплаты</div>
                            <div class="payment-help-desc">
                                Выплаты от 1 минуты до 3 дней<br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="md-modal md-bonus-wheel md-s-height md-effect-1" id="wheel">
        <div class="md-content">
            <i class="fal fa-times md-close" onclick="$('.md-bonus-wheel').toggleClass('md-show', false)"></i>
            <canvas id="canvas" style="width: 100%" width="880" height="600">
                Canvas not supported, use another browser.
            </canvas>
            <img id="prizePointer" src="/storage/img/pointer_white.png" alt="V" />
            <div class="wh_circle hidden-xs hidden-md"></div>
            <div class="wh_outside_circle hidden-sm hidden-xs"></div>
            <div class="wheel_block row" id="wheel_block">
                @if(strpos(Auth::user()->login, 'id') !== false && !\App\User::isSubscribed(Auth::user()->login2))
                    <div class="bonus-fy-overlay">
                        <div class="col-xs-12 col-sm-6 bonus_reload" style="padding-left: 25px; cursor: pointer;" onclick="window.open('https://vk.com/playintm', '_blank')">
                            <span><i class="fab fa-vk"></i> ВКонтакте</span>
                            <p style="font-size: 11px;">Вступи в группу и получай бесплатный бонус каждые 3 минуты!</p>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="spin_button" style="left: 63%;" onclick="window.open('https://vk.com/playintm', '_blank'); setTimeout($('.bonus-fy-overlay').fadeOut('fast'), 1500)">Перейти</div>
                        </div>
                    </div>
                @endif
                <div class="wb_c">
                    <div class="col-xs-12 col-sm-6 bonus_reload">
                        <span id="reload_text">3 мин</span>
                        <p id="reload_hint">перезарядка</p>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="spin_button" onclick="spin_bonus()">Крутить</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="md-modal md-bonus-wheel md-s-height md-effect-1" id="ref">
        <div class="md-content">
            <i class="fal fa-times md-close" onclick="$('.md-bonus-wheel').toggleClass('md-show', false)"></i>

            <canvas id="ref_canvas" style="width: 100%" width="880" height="600">
                Canvas not supported, use another browser.
            </canvas>
            <img id="prizePointer" src="/storage/img/pointer_white.png" alt="V" />
            <div class="wh_circle hidden-xs hidden-md"></div>
            <div class="wh_outside_circle hidden-sm hidden-xs"></div>
            <div class="wheel_block row" id="ref_block">
                <div class="wb_c">
                    <div class="col-xs-12 col-sm-6 bonus_reload">
                        <span class="ref_reload_text">.../10</span>
                        <p id="ref_reload_hint">активных рефералов</p>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                            <div class="spin_button" onclick="spin_ref()">Крутить</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="md-modal md-promo md-s-height md-effect-1">
            <div class="md-content">
                <i class="fal fa-times md-close" onclick="$('.md-promo').toggleClass('md-show', false)"></i>
                <div style="margin-top: 14px;">
                    <div class="vk-btn" onclick="window.open('https://vk.com/playintm', '_blank')"><i class="fab fa-vk"></i> Промокоды</div>
                    <input style="width: 92%;" data-number-input="true" class="b_input_s bg_bet_input" id="_promo" placeholder="Промокод">
                    <div class="bg_bet_btn" style="padding: 8px;" onclick="activatePromo($('#_promo').val())"><i class="fal fa-check"></i></div>
                </div>
            </div>
        </div>
        <div class="md-modal md-rain md-s-height md-effect-1" id="rain">
            <div class="md-content">
                <i class="fal fa-times md-close" onclick="$('.md-rain').toggleClass('md-show', false)"></i>
                <div class="nano">
                    <div class="nano-content">
                        <div style="font-size: 1.3em">Дождь</div>
                        <ul>
                            <li>Пять случайных человек каждые 3 часа выбираются системой и награждаются бонусом, попутно отправляя об этом сообщение в чат.</li>
                            <li>Для попадания под дождь необходимо пополнить счет на сумму 30 руб. или выше за последние 24 часа.</li>
                            <li style="font-size: 0.8em; color: lightgrey">* "Дождь" переименовывается в "Снег" на время зимы и меняет свою анимацию.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="md-modal md-unavailable md-s-height md-effect-1">
        <div class="md-content">
            <i class="fal fa-times md-close" onclick="$('.md-unavailable').toggleClass('md-show', false)"></i>
            <div class="md-unavailable-title">:(</div>
            <div class="md-unavailable-desc">Эта игра сейчас недоступна.</div>
        </div>
    </div>

    <div class="md-modal md-effect-1" id="modal-1">
        <div class="md-content">
            <div class="case-modal-header">
                <div id="modal-1-header"></div>
                <i class="fal fa-times md-close" id="md-close"></i>
            </div>
            <div id="modal-1-content"></div>
        </div>
    </div>

    <!--
<div class="md-modal md-stickers md-effect-1" id="modal-2">
    <div class="md-content">
        <i class="fal fa-times md-close"></i>
        <div class="stickers-content">
            <div class="sticker-header">
                <img src="/storage/img/stickers/pepe/1.png">
                <div class="sticker-header-content">
                    <div class="shc-n">Pepe</div>
                    <div class="shc-b">Добавить</div>
                </div>
            </div>
            <div class="sticker-content">
                <div class="nano">
                    <div class="nano-content">
                        for($i = 1; $i <= 24; $i++)
                            <div class="sticker">
                                <img src="/storage/img/stickers/pepe/$i.png">
                            </div>
                        endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
-->

    <div class="md-overlay"></div>
</div>