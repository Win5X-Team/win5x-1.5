<div class="game__wrapper">
    <div class="col-md-3 col-sm-12 g_sidebar g_sidebar_nm" data-parent="#w_container">
        <div class="row g_follow">
            <div class="row m0">
                <div class="g_md_n col-md-12">
                    <i class="fas fa-ball-pile"></i>
                    <span>Plinko</span>
                </div>
                <div class="col-xs-12">
                    <div class="b_label">
                        Сумма ставки
                    </div>
                </div>
                <div class="col-xs-12 mt10">
                    <script>
                        var __profit = function() { };
                    </script>
                    <input id="bet" value="<?= Auth::guest() ? '100.00' : '0.01' ?>" type="text" class="b_input" data-number-input="true">
                    <div class="b_input_btns">
                        <div id="divide" class="b_input_btn g_s"><i class="fa fa-divide"></i></div>
                        <div id="multiply" class="b_input_btn g_s"><i class="fa fa-asterisk"></i></div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="b_input_bottom" style="display: inline-block">
                        <div id="01" class="col-xs-3 g_s">+0.1</div>
                        <div id="1" class="col-xs-3 g_s">+1</div>
                        <div id="5" class="col-xs-3 g_s">+5</div>
                        <div id="10" class="col-xs-3 g_s">+10</div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="b_label">
                    Количество пинов
                </div>
            </div>
            <div class="col-xs-12 mb10 mt5">
                <div class="bombs_container plinko_container">
                    <div data-pin="8" class="bc_active">8</div>
                    <div data-pin="9">9</div>
                    <div data-pin="10">10</div>
                    <div data-pin="11">11</div>
                    <div data-pin="12">12</div>
                    <div data-pin="13">13</div>
                    <div data-pin="14">14</div>
                    <div data-pin="15">15</div>
                    <div data-pin="16">16</div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="b_label">
                    Уровень риска
                </div>
            </div>
            <div class="col-xs-12 mb10 mt5">
                <div class="buttons-3">
                    <div data-plinko-difficulty="low">Маленький</div>
                    <div data-plinko-difficulty="medium" class="buttons-3-selected">Средний</div>
                    <div data-plinko-difficulty="high">Высокий</div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 mt5">
                <div class="g_s g_btn"  onclick="plinko()"><span id="bet_btn">Играть</span></div>
            </div>
            @include('pages.game_task', ['game_id' => 13])
        </div>
        <div class="g_sidebar_footer">
            <div class="g_sidebar_footer_button" onclick="info('plinko')">
                <i class="fas fa-info-circle tooltip" title="Информация о игре"></i>
            </div>
            <div class="g_sidebar_footer_button" onclick="load('fairness')">
                <i class="fad fa-shield-alt tooltip" title="Честная игра"></i>
            </div>
        </div>
    </div>
    <div id="w_container" class="g_c g_container col-md-9 col-sm-12">
        <div class="plinko"></div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('/js/vendor/moment.min.js?v='.$version) }}"></script>
<script type="text/javascript" src="{{ $asset('/game/plinko.js', 'js') }}"></script>