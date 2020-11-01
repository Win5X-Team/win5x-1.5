<div class="game__wrapper">
    <div class="col-md-3 col-sm-12 g_sidebar" data-parent="#w_container">
        <div class="row g_follow">
            <div class="row m0">
                <div class="g_md_n col-md-12">
                    <i class="fad fa-bomb"></i>
                    <span>Mines</span>
                </div>
                <div class="col-xs-12">
                    <div class="b_label">
                        Сумма ставки
                    </div>
                </div>
                <div class="col-xs-12 mt10">
                    <script>var __profit = function() { }; </script>
                    <input id="bet" data-number-input="true" value="<?= Auth::guest() ? '100.00' : '0.01' ?>" type="text" class="b_input">
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
            <div class="col-xs-12 mt5">
                <div class="b_label">
                    Количество мин:
                </div>
            </div>
            <div class="col-xs-12 mb10 mt5">
                <div class="bombs_container">
                    <div data-bomb="3" class="bc_active">3</div>
                    <div data-bomb="5">5</div>
                    <div data-bomb="10">10</div>
                    <div data-bomb="24">24</div>
                    <div id="change_bombs">
                        <span>Изменить</span>
                        <input data-number-input="true" class="bomb_input dn" value="3" placeholder="2-24">
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 mt5">
                <div class="g_s g_btn" onclick="mines()" id="play"><span id="bet_btn">Играть</span></div>
            </div>
            @include('pages.game_task', ['game_id' => 5])
        </div>
        <div class="g_sidebar_footer">
            <div class="g_sidebar_footer_button" onclick="info('mines')">
                <i class="fas fa-info-circle tooltip" title="Информация о игре"></i>
            </div>
            <div class="g_sidebar_footer_button" onclick="load('fairness')">
                <i class="fad fa-shield-alt tooltip" title="Честная игра"></i>
            </div>
        </div>
    </div>
    <div id="w_container" class="g_c g_container crash col-md-9 col-sm-12 coin_container">
        <div class="cf_info cf_info_m">
            <div class="cf_s">
                <span class="m_win_icon"><i class="fad fa-diamond"></i></span>
                <p class="mt5" id="safe">22</p>
            </div>
            <div class="mines_grid">
                @for($i = 0; $i < 5 * 5; $i++)
                    <div data-grid-id="{{$i}}" class="mine_disabled"></div>
                @endfor
            </div>
            <div class="cf_s cf_s_c">
                <span class="m_lose_icon"><i class="fas fa-bomb"></i></span>
                <p class="mt5" id="bomb">3</p>
            </div>

            <div class="cf_history">
                <div id="cf_slick"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('/js/vendor/jquery.animateNumber.min.js?v='.$version) }}"></script>
<script type="text/javascript" src="{{ $asset('/game/mines.js', 'js') }}"></script>