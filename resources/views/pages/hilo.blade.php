<div class="game__wrapper">
    <div class="col-md-3 col-sm-12 g_sidebar g_sidebar_nm" data-parent="#w_container">
        <div class="row g_follow">
            <div class="row m0">
                <div class="g_md_n col-md-12">
                    <i class="fad fa-clone"></i>
                    <span>HiLo</span>
                </div>
                <div class="col-xs-12">
                    <div class="b_label">
                        Сумма ставки
                    </div>
                </div>
                <div class="col-xs-12 mt10">
                    <script>var __profit = function() { }; </script>
                    <input id="bet" data-number-input="true"  value="<?= Auth::guest() ? '100.00' : '0.01' ?>" type="text" class="b_input">
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
            <div class="col-xs-12 coin-select">
                <div class="hilo-select" onclick="flip('lower')" style="display: none">
                    <i class="far fa-chevron-down"></i>
                    <div class="hilo-chance"><span id="chance-l_ma">0</span>.<span id="chance-l_mi">0</span>%</div>
                    <div class="hilo-chance-desc" id="lower">Ниже или та же</div>
                    <div class="hilo-mul"><span id="mul-l_ma">0</span>.<span id="mul-l_mi">0</span>x</div>
                </div>
                <div class="hilo-select" onclick="flip('higher')" style="display: none">
                    <i class="far fa-chevron-up"></i>
                    <div class="hilo-chance"><span id="chance-h_ma">0</span>.<span id="chance-h_mi">0</span>%</div>
                    <div class="hilo-chance-desc" id="higher">Выше или та же</div>
                    <div class="hilo-mul"><span id="mul-h_ma">0</span>.<span id="mul-h_mi">0</span>x</div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 mt5">
                <div class="g_s g_btn" onclick="" id="play"><span id="bet_btn">Играть</span></div>
            </div>
            @include('pages.game_task', ['game_id' => 7])
        </div>
        <div class="g_sidebar_footer">
            <div class="g_sidebar_footer_button" onclick="info('hilo')">
                <i class="fas fa-info-circle tooltip" title="Информация о игре"></i>
            </div>
            <div class="g_sidebar_footer_button" onclick="load('fairness')">
                <i class="fad fa-shield-alt tooltip" title="Честная игра"></i>
            </div>
        </div>
    </div>
    <div id="w_container" class="g_c g_container crash col-md-9 col-sm-12 coin_container">
        <div class="cf_status" style="display: none; top: 335px !important;">
            <h1 class="ribbon-wide">
                <strong class="ribbon-wide-content" id="cf_status_text"></strong>
            </h1>
        </div>
        <div class="cf_info">
            <div class="cf_s">
                <span id="games">0</span>
                <p>карт</p>
            </div>

            <div class="hilo_card">
                <div class="hilo-card-value"></div>
                <i id="card_icon"></i>
                <div class="hilo-replace" onclick="replace()">
                    <i class="far fa-redo"></i>
                </div>
            </div>

            <div class="cf_s cf_s_c">
                <span>x</span><span id="mul">0</span><span>.</span><span id="mul_m">0</span>
                <p>коэфф.</p>
            </div>
        </div>
        <div class="cf_history" style="height: 115px"></div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('/js/vendor/jquery.animateNumber.min.js?v='.$version) }}"></script>
<script type="text/javascript" src="{{ $asset('/game/hilo.js', 'js') }}"></script>