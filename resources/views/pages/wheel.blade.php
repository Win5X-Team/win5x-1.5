<div class="game__wrapper">
    <div class="col-md-3 col-sm-12 g_sidebar" data-parent="#w_container">
        <div class="row g_follow">
            <div class="row m0">
                <div class="g_md_n col-md-12">
                    <i class="fad fa-circle"></i>
                    <span>Wheel</span>
                </div>
                <div class="col-xs-12">
                    <div class="b_label">
                        Сумма ставки
                    </div>
                </div>
                <div class="col-xs-12 mt10">
                    <script>
                        var __profit = function() {
                            var r = selected_color == null ? $('#bet').val() * 2 : (selected_color === 'green' ? $('#bet').val() * 14 : $('#bet').val() * 2);
                            if(!isNaN(r)) $('#bet_profit').html((r).toFixed(2));
                        };
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
                <div class="col-xs-12">
                    <div class="bet_profit">
                        Выигрыш: <span id="bet_profit"></span> руб.
                        <div class="hidden-xs mt5">
                            Цвет: <span id="w_color" class="bet_profit-error">Не выбран</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt5"></div>
            <div class="row m0">
                <div class="col-xs-4">
                    <div class="w_color_btn w_color_btn-red mt5" data-wheel-color="red" onclick="pickColor('red')">
                        x2
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="w_color_btn w_color_btn-green mt5" data-wheel-color="green" onclick="pickColor('green')">
                        x14
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="w_color_btn w_color_btn-black mt5" data-wheel-color="black" onclick="pickColor('black')">
                        x2
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 mt5">
                <div class="g_s g_btn" onclick="wheel()">Играть</div>
            </div>
        </div>
        <div class="g_sidebar_footer">
            <div class="g_sidebar_footer_button" onclick="info('wheel')">
                <i class="fas fa-info-circle tooltip" title="Информация о игре"></i>
            </div>
            <div class="g_sidebar_footer_button" onclick="setQuickGame(!isQuick)">
                <i class="fas fa-bolt tooltip" id="game_quick" title="Быстрая игра"></i>
            </div>
            <div class="g_sidebar_footer_button" onclick="load('fairness')">
                <i class="fad fa-shield-alt tooltip" title="Честная игра"></i>
            </div>
        </div>
    </div>
    <div id="w_container" class="g_c g_container col-md-9 col-sm-12">
        <div class="wheel_game_result" style="display: none">
            <div class="mul"></div>
            <div class="te"></div>
        </div>
        <canvas id="canvas" class="w_wheel" width="880" height="600" data-responsiveMinWidth="180"></canvas>
        <div class="wr_i hidden-xs hidden-md hidden-sm"></div>
        <div class="wr_o hidden-xs hidden-md hidden-sm"></div>
        <img id="prizePointer" class="w_pointer" src="/storage/img/pointer_white.png" alt="V" />
    </div>
</div>

<script type="text/javascript" src="{{ asset('/js/vendor/TweenMax.min.js?v='.$version) }}"></script>
<script type="text/javascript" src="{{ asset('/js/vendor/winwheel.js?v='.$version) }}"></script>
<script type="text/javascript" src="{{ $asset('/game/wheel.js', 'js') }}"></script>