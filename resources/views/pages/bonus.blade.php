@if(Auth::guest()) <script type="text/javascript">load('games', function() { $('.md-auth').toggleClass('md-show', true); });</script> @else
<div class="bonus_page_wrapper">
    <div class="bonus_page_header">Специальные предложения и бонусы</div>
    <div class="bonus_page_container">
        <div class="bonus_option" onclick="$('#wheel').toggleClass('md-show', true)">
            <div class="bonus_option_content">
                <div class="bonus_option_image bonus_option_bonus_wheel"></div>
                <div class="bonus_option_name">Колесо</div>
                <div class="bonus_option_description">Прокручивай бесплатное колесо с бонусом<br>каждые 3 минуты!</div>
                <div class="bonus_option_button">Открыть</div>
            </div>
        </div>
        <div class="bonus_option" onclick="$('#ref').toggleClass('md-show', true)">
            <div class="bonus_option_content">
                <div class="bonus_option_image bonus_option_bonus_ref"><div></div></div>
                <div class="bonus_option_name">Партнерская программа</div>
                <div class="bonus_option_description">Получай бесплатный бонус<br>за каждые 10 рефералов!</div>
                <div class="bonus_ref_option_counter"><span class="ref_reload_text">0/10</span>&nbsp;рефералов</div>
                <div class="bonus_option_button">Подробнее</div>
            </div>
        </div>

        @if(strpos(Auth::user()->login, 'id') !== false)
            <div class="bonus_option" onclick="window.open('https://vk.com/playintm')">
                @if(\App\User::isSubscribed(Auth::user()->login2))
                    <div class="bonus_option_ok">
                        <i class="fal fa-check"></i>
                        <span>Вы выполнили это задание.</span>
                    </div>
                @endif
                <div class="bonus_option_content">
                    <div class="bonus_option_image bonus_option_vk"><i class="fab fa-vk"></i></div>
                    <div class="bonus_option_name">Группа ВКонтакте</div>
                    <div class="bonus_option_description">Вступи в группу ВКонтакте и получи 0.45 руб. на счет!</div>
                    <div class="bonus_option_button">Перейти</div>
                </div>
            </div>
        @endif
        <div id="notificationOption" style="display: none" class="bonus_option">
            @if(Auth::user()->notify_bonus == 1)
                <div class="bonus_option_ok">
                    <i class="fal fa-check"></i>
                    <span>Вы выполнили это задание.</span>
                </div>
            @endif
            <div class="bonus_option_content">
                <div class="bonus_option_image bonus_option_notify"><i class="fas fa-bell"></i></div>
                <div class="bonus_option_name">Уведомления</div>
                <div class="bonus_option_description">Подпишись на уведомления с промокодами и новостями и получи 1 руб. на счет!</div>
                <div class="bonus_option_button">Подписаться</div>
            </div>
        </div>
    </div>
    <div class="bonus_page_header">События</div>
    <div class="bonus_page_container">
        <div class="bonus_option" onclick="window.open('https://vk.me/win5x')">
            <div class="bonus_option_content">
                <div class="bonus_option_image bonus_option_vk_m"><i class="fal fa-handshake"></i></div>
                <div class="bonus_option_name" style="width: 75%;">Сотрудничество с группами ВКонтакте</div>
                <div class="bonus_option_description" style="margin-bottom: 64px;">Вы - администратор группы ВКонтакте? Мы можем предложить ежедневные уникальные промокоды для Вашей группы.</div>
                <div class="bonus_option_button">Связаться с нами</div>
            </div>
        </div>
        <div class="bonus_option" onclick="$('#rain').toggleClass('md-show', true)">
            <div class="bonus_option_content">
                <div class="bonus_option_image bonus_option_rain">
                    <img src="/storage/img/game/svg/clouds.svg" alt="">
                </div>
                <div class="bonus_option_name">Дождь</div>
                <div class="bonus_option_description">
                    Каждые несколько часов<br>в чате происходит событие,<br>раздающее бонус<br>случайным людям.
                </div>
                <div class="bonus_option_button">Подробнее</div>
            </div>
        </div>
        <div class="bonus_option" onclick="$('.md-promo').toggleClass('md-show', true)">
            <div class="bonus_option_content">
                <div class="bonus_option_image bonus_option_promo">
                    <img src="data:image/png;base64,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" alt="">
                    <img src="data:image/png;base64,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" alt="">
                </div>
                <div class="bonus_option_name">Промокод</div>
                <div class="bonus_option_description">Введи промокод и получи бонус на счет!</div>
                <div class="bonus_option_button">Ввести</div>
            </div>
        </div>
    </div>
</div>
@endif

<script type="text/javascript" src="{{ asset('/js/vendor/TweenMax.min.js?v='.$version) }}"></script>
<script type="text/javascript" src="{{ asset('/js/vendor/winwheel.js?v='.$version) }}"></script>
<script type="text/javascript" src="{{ $asset('/bonus.page.js', 'js') }}"></script>