<div class="game__wrapper">
    <div data-parent="#d_container" class="col-md-3 col-sm-12 9 g_sidebar">
        <div class="row g_follow">
            <div class="row m0">
                <div class="g_md_n col-md-12">
                    <i class="fa fa-dice"></i>
                    <span>Dice</span>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="b_label">
                        Сумма ставки
                    </div>
                </div>
                <div class="col-xs-12">
                    <script>
                        var __profit = function(val) {
                            var v = val == null ? $('#slider-range').slider('value') : val;
                            var r = getDiceProfit($('#bet').val(), cur === 'lower' ? 0 : v, cur === 'higher' ? 100 : v);
                            if(!isNaN(r)) $('#bet_profit').html(r);

                            $('.bet_profit').toggleClass('bet_profit-error', parseFloat(r) <= 0);
                        };
                    </script>
                    <input id="bet" oninput="__profit()" value="<?= Auth::guest() ? '100.00' : '0.01' ?>" type="text" class="b_input" data-number-input="true">
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
                <div class="col-xs-12">
                    <div class="bet_profit">
                        Выигрыш: <span id="bet_profit"></span> руб.
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 mt10">
                <div class="g_s g_btn" onclick="dice()">Играть</div>
            </div>
            @include('pages.game_task', ['game_id' => 1])
        </div>
        <div class="g_sidebar_footer">
            <div class="g_sidebar_footer_button" onclick="info('dice')">
                <i class="fas fa-info-circle tooltip" title="Информация о игре"></i>
            </div>
            <div class="g_sidebar_footer_button" onclick="load('fairness')">
                <i class="fad fa-shield-alt tooltip" title="Честная игра"></i>
            </div>
        </div>
    </div>
    <div id="d_container" class="col-md-9 col-sm-12 g_c g_container">
        <div class="d_icon hidden-sm hidden-xs">
            <i class="fa fa-dice"></i>
        </div>
        <div class="d_slider-border row mt15" style="padding: 11px 0;">
            <div class="col-xs-6">
                <div class="b_label" id="sw_text">Меньше</div>
                <input id="i_value" value="50" type="text" class="b_input_s mt5" data-number-input="true">
                <div class="b_input_btns">
                    <div onclick="sw()" class="b_input_btn g_s"><i class="fa fa-exchange-alt"></i></div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="b_label">Шанс</div>
                <input disabled id="i_chance" value="50%" type="text" class="b_input_s mt5" data-number-input="true">
            </div>
        </div>
        <div class="d_slider">
            <div class="d_slider-border">
                <div id="slider-range"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('/js/vendor/jquery-ui.js?v='.$version) }}"></script>
<script type="text/javascript" src="{{ asset('/js/vendor/jquery.ui.touch-punch.min.js?v='. $version) }}"></script>
<script type="text/javascript" src="{{ $asset('/game/dice.js', 'js') }}"></script>