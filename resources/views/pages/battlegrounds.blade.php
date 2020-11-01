<div class="game__wrapper bg_wrapper">
    <div class="col-md-1 g_sidebar col-sm-12 col-xs-12 bg_sidebar" data-parent="#w_container">
        <div class="row">
            <div class="row m0">
                <div class="g_md_n col-md-12 bg_color bg_header">
                    <i class="fad fa-swords hidden-xs hidden-sm"></i>
                </div>
                <div class="bgp" id="players_"></div>
            </div>
        </div>
    </div>
    <div id="w_container" class="g_c bg_c col-md-11 col-sm-12 bg_container">
        <div class="bg_bets">
            <i class="bg_bet_icon fad fa-swords"></i>
            <div class="bg_bets_container">
                <div class="bg_round_container">
                    <div class="col-md-6 col-xs-12">
                        <div>Выигрыш:</div>
                        <div class="bg_reward">Ожидание</div>
                    </div>
                    <div class="col-md-6 hidden-sm hidden-xs bg_rules">
                        <p>Правила</p>
                        <p>Выбывает из игры тот,<br>на кого укажет стрелка!</p>
                        <p>Последний оставшийся в живых игрок забирает себе все ставки других игроков.</p>
                    </div>
                </div>
            </div>
            <div class="bg_round">
                <div class="bg_round_container">
                    <p>Следующий раунд через:</p>
                    <br>
                    <span><i class="fa fa-clock"></i> <strong id="round">Ожидание</strong></span>
                </div>
            </div>
        </div>
        <div class="wheel">
            <div class="wheel-wrapper" id="players_wheel_"></div>
        </div>
        <div class="wheel-pointer"></div>
    </div>
</div>

<script type="text/javascript" src="{{ $asset('/game/battlegrounds.js', 'js') }}"></script>