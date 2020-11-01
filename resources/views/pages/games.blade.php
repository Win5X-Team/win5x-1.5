<div class="col-xs-12 index-carousel">
    <div class="carousel">
        <!--
        <div>
            <div class="carousel-slide">
                <div class="carousel-background" style="background-image: url(/storage/img/people.jpg);"></div>
                <div class="carousel-content">
                    <div class="slide-header">
                        Ставки на спорт - уже на Win<span class="i_y_i">5X</span>!
                    </div>
                    <div class="slide-content">
                        Благодаря непревзойденному дизайну, играйте как<br>никогда с помощью новейшего способа ставок на Win5x.
                    </div>
                </div>
                <button class="slide-button" onclick="load('sport')">Перейти</button>
            </div>
        </div>
        -->
        <div>
            <div class="carousel-slide">
                <div class="carousel-background" style="background-image: url(/storage/img/casino.jpg);"></div>
                <div class="carousel-content">
                    <div class="slide-header">
                        Блеск в простоте
                    </div>
                    <div class="slide-content">
                        Испытайте пик социальных азартных игр,<br>сочетающую атмосферу реального казино с простотой ваших любимых игр.
                    </div>
                </div>
                <button class="slide-button" @if(Auth::guest()) onclick="$('#b_si').click();" @else onclick="load('bonus')" @endif>Начать играть</button>
            </div>
        </div>
        <div>
            <div class="carousel-slide">
                <div class="carousel-background" style="background-image: url(/storage/img/noSliderImage.jpg);"></div>
                <div class="carousel-content">
                    <div class="slide-header">
                        Plinko и Keno - уже на Win<span class="i_y_i">5X</span>!
                    </div>
                    <div class="slide-content">
                        Две новые игры уже доступны на Win5X!
                    </div>
                </div>
                <button class="slide-button" onclick="load('plinko')">Перейти - Plinko</button>
                <button class="slide-button slide-button-2" onclick="load('keno')">Перейти - Keno</button>
            </div>
        </div>
    </div>
</div>
<div class="col-xs-12" style="margin-top: 10px">
    @php
        $overlay = function($game) {
            if(Auth::guest() || !\App\Game::isDisabled($game)) echo '<div class="i_game_overlay-'.$game.'"></div>';
            else echo '<div data-disable-ajax-loading="'.$game.'" class="i_game_disabled_overlay" onclick="$(\'.md-unavailable\').toggleClass(\'md-show\', true)"></div>';
        };
    @endphp
    @if($settings->warn_enabled == 1)
        <div class="col-xs-12">
            <div class="notification">
                <i class="fad fa-exclamation-triangle"></i>
                <div class="notification-content">
                    <div>{{$settings->warn_title}}</div>
                    <div>{{$settings->warn_text}}</div>
                </div>
            </div>
        </div>
    @endif
    <!--
    <div class="col-xs-12">
        <div class="i_game i_game-bottle event_container" style="background-image: url(/storage/img/game/svg/plinko.svg)" onclick="">
            <div class="i_game-name">
                <span><i class="fad fa-futbol"></i> event name</span>
                <p>event desc</p>
            </div>
        </div>
    </div>
    -->
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="i_game i_game-mines" onclick="load('mines')" style="background-image: url('/storage/img/game/svg/mines.svg')">
            {{$overlay('mines')}}
            <div class="i_game-name">
                <i class="far fa-bomb"></i>
                Mines
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="i_game i_game-crash" onclick="load('crash')" style="background-image: url('/storage/img/game/svg/crash.svg')">
            {{$overlay('crash')}}
            <div class="i_game-name">
                <i class="la la-line-chart"></i>
                Crash
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="i_game i_game-wheel" onclick="load('wheel')" style="background-image: url('/storage/img/game/svg/wheel.svg')">
            {{$overlay('wheel')}}
            <div class="i_game-name">
                <i class="fad fa-circle"></i>
                Wheel
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="i_game i_game-dice" onclick="load('dice')" style="background-image: url('/storage/img/game/svg/dice.svg')">
            {{$overlay('dice')}}
            <div class="i_game-name">
                <i class="fa fa-dice"></i>
                Dice
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="i_game i_game-coinflip" onclick="load('coinflip')" style="background-image: url('/storage/img/game/svg/coinflip.svg'); background-position-y: top; background-size: cover;">
            {{$overlay('coinflip')}}
            <div class="i_game-name">
                <i class="far fa-coin"></i>
                Coinflip
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="i_game i_game-hilo" onclick="load('hilo')" style="background-image: url('/storage/img/game/svg/hilo.svg')">
            {{$overlay('hilo')}}
            <div class="i_game-name">
                <i class="fad fa-clone"></i>
                HiLo
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="i_game i_game-blackjack" onclick="load('blackjack')" style="background-image: url('/storage/img/game/svg/blackjack2.svg')">
            {{$overlay('blackjack')}}
            <div class="i_game-name">
                <i class="fas fa-spade"></i>
                Blackjack
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="i_game i_game-tower" onclick="load('tower')" style="background-image: url('/storage/img/game/svg/tower.svg')">
            {{$overlay('tower')}}
            <div class="i_game-name">
                <i class="fad fa-chess-rook"></i>
                Tower
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="i_game i_game-plinko" onclick="load('plinko')" style="background-image: url('/storage/img/game/svg/plinko.svg')">
            {{$overlay('plinko')}}
            <div class="i_game-name">
                <i class="fas fa-ball-pile"></i>
                Plinko
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="i_game i_game-roulette" onclick="load('roulette')" style="background-image: url('/storage/img/game/svg/roulette.svg')">
            {{$overlay('roulette')}}
            <div class="i_game-name">
                <i class="fad fa-badge"></i>
                Roulette
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="i_game i_game-stairs" onclick="load('stairs')" style="background-image: url('/storage/img/game/svg/stairs.svg')">
            {{$overlay('stairs')}}
            <div class="i_game-name">
                <i class="la la-stream"></i>
                Stairs
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="i_game i_game-keno" onclick="load('keno')" style="background-image: url('/storage/img/game/svg/keno.svg')">
            {{$overlay('keno')}}
            <div class="i_game-name">
                <i class="fad fa-octagon"></i>
                Keno
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4" id="battlegrounds">
        <div class="i_game-ribbon_container">
            <span class="i_game-ribbon">
                Многопользовательская
            </span>
        </div>
        <div class="i_game i_game-battlegrounds battle_container" onclick="battlegrounds_connect()">
            {{$overlay('battlegrounds')}}
            <img class="lazyload clouds" id="clouds1" data-src="/storage/img/cloud/1.png">
            <img class="lazyload clouds" id="clouds2" data-src="/storage/img/cloud/2.png">
            <img class="lazyload clouds" id="clouds-long" data-src="/storage/img/cloud/long.png">

            @php($latestGame = \App\Game::where('game_id', 6)->orderBy('id', 'desc')->first())
            @if($latestGame != null)
                <div class="battlegrounds_info">
                    <div class="bg_in_progress" style="display: none">
                        <div class="bg_last_players_title">...</div>
                        <div class="bg_quick_roulette">
                            <div class="wheel" style="bottom: unset !important;">
                                <div class="wheel-wrapper" id="players_wheel_"></div>
                            </div>
                            <div class="wheel-live-pointer"></div>
                        </div>
                    </div>

                    <div class="bg_last_players" style="display: none">
                        <div class="bg_last_players_title">Последние игроки:</div>
                        <div class="bg_players">
                            {!! \App\Http\Controllers\GeneralController::bgFragment() !!}
                        </div>
                    </div>
                </div>
            @endif

            <div class="i_game-name" style="z-index: 20; right: 0;">
                <i class="fad fa-swords"></i>
                Battle
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4" id="cases">
        <div class="i_game i_game-cases" onclick="load('cases')">
            {{$overlay('cases')}}
            <div class="i_game-cases-float">
                <div class="floating">
                    <i class="fad fa-box-open"></i>
                </div>
                <div class="floating">
                    <i class="fad fa-box-open"></i>
                </div>
            </div>
            <div class="i_game-name">
                <i class="fad fa-box-open"></i>
                <div class="gg_sidebar-notification">1</div>
                Кейсы
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="i_game i_game-bonus" @if(!Auth::guest()) onclick="load('bonus')" @else onclick="$('#b_si').click()" @endif>
            <div class="i_game_overlay-bonus i_game_overlay-bonus_1"></div>
            <div class="i_game_overlay-bonus i_game_overlay-bonus_2"></div>
            <div class="i_game_overlay-bonus i_game_overlay-bonus_3 hidden-xs"></div>
            <div class="i_game-name bonus_banner_desc" id="bonus_banner_name">
                <i class="fad fa-coins i_y_i" style="margin-right: 5px"></i><span class="i_y_i">Бонус</span><br>
                Получи денежный бонус<br>бесплатно для начала игры на Win5x!
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-12">
        <div class="i_game i_game-vk" onclick="var win = window.open('https://vk.com/playintm', '_blank'); win.focus()" id="vk_banner">
            <i class="fab fa-vk i_game_overlay-vk"></i>
            <div class="i_game-name vk_banner_desc" id="vk_banner_name">
                <i class="fab fa-vk i_b_i"></i><span class="i_b_i">ВКонтакте</span><br>
                Присоединяйся к группе ВКонтакте<br>
                и будь вкурсе всех новостей,<br>
                а так же специальных промокодов!
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ $asset('/game/games.js', 'js') }}"></script>