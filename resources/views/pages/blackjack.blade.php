<div class="game__wrapper">
    <div class="col-md-3 col-sm-12 g_sidebar" data-parent="#w_container">
        <div class="row g_follow">
            <div class="row m0">
                <div class="g_md_n col-md-12">
                    <i class="fas fa-spade"></i>
                    <span>Blackjack</span>
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
            <div style="display: none" id="blackjack_controls">
                <div class="insurance" style="display: none">
                    <div class="insurance-container">
                        <div><i class="fas fa-question-circle"></i> Желаете страховку?</div>
                        <div class="insurance-desc">
                            <p>Она позволяет застраховаться от возможной руки дилера с блэкджеком.</p>
                            <p>Страховка стоит половину вашей ставки.</p>
                        </div>

                        <div class="col-xs-12 coin-select">
                            <div class="blackjack_button" id="insurance_accept">Принять</div>
                            <div class="blackjack_button" id="insurance_cancel">Отказаться</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 coin-select">
                    <div class="hilo-select" id="stand">
                        <i class="fas fa-hand-paper"></i>
                        <div class="hilo-mul">Стоп</div>
                    </div>
                    <div class="hilo-select" id="hit">
                        <i class="fas fa-spade"></i>
                        <div class="hilo-mul">Взять еще</div>
                    </div>
                </div>
                <div class="col-xs-12 coin-select">
                    <div class="blackjack_button bb_disabled" id="double">Удвоить</div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 mt5">
                <div class="g_s g_btn" id="play"><span id="bet_btn">Играть</span></div>
            </div>
        </div>
        <div class="g_sidebar_footer">
            <div class="g_sidebar_footer_button" onclick="info('blackjack')">
                <i class="fas fa-info-circle tooltip" title="Информация о игре"></i>
            </div>
            <div class="g_sidebar_footer_button" onclick="load('fairness')">
                <i class="fad fa-shield-alt tooltip" title="Честная игра"></i>
            </div>
        </div>
    </div>
    <div id="w_container" class="g_c g_container blackjack col-md-9 col-sm-12">
        <div class="deck">
            <div><div></div></div>
            <div><div></div></div>
            <div><div></div></div>
            <div><div></div></div>
        </div>
        <img alt="" draggable="false" class="blackjack_container_split" src="/storage/img/game/svg/blackjack_container_split.svg">

        <div class="wheel_game_result blackjack_game_result" style="background: rgba(0,0,0,0.95); display: none">
            <div class="mul"></div>
            <div class="te"></div>
        </div>

        <div class="blackjack_score dealer" style="display: none">21</div>
        <div class="blackjack_score player" style="display: none">21</div>

        <div class="blackjack_container">
            <div id="alert" class="alert alert-error hide"><span></span></div>
            <div id="dealer">
                <div id="dhand"></div>
            </div>
            <div id="player">
                <div id="phand"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ $asset('/game/blackjack.js', 'js') }}"></script>