<div class="game__wrapper">
    <div class="col-md-3 col-sm-12 g_sidebar" data-parent="#w_container">
        <div class="row g_follow">
            <div class="row m0">
                <div class="g_md_n col-md-12">
                    <i class="fad fa-chess-rook"></i>
                    <span>Tower</span>
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
            <div class="col-xs-12">
                <div class="b_label">
                    Количество мин
                </div>
            </div>
            <div class="col-xs-12 mb10 mt5">
                <div class="bombs_container tower_container">
                    <div data-bomb="1" class="bc_active">1</div>
                    <div data-bomb="2">2</div>
                    <div data-bomb="3">3</div>
                    <div data-bomb="4">4</div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12">
                <div class="g_s g_btn" onclick="tower()" id="play"><span id="bet_btn">Играть</span></div>
            </div>
            @include('pages.game_task', ['game_id' => 9])
        </div>
        <div class="g_sidebar_footer">
            <div class="g_sidebar_footer_button" onclick="info('tower')">
                <i class="fas fa-info-circle tooltip" title="Информация о игре"></i>
            </div>
            <div class="g_sidebar_footer_button" onclick="load('fairness')">
                <i class="fad fa-shield-alt tooltip" title="Честная игра"></i>
            </div>
        </div>
    </div>
    <div id="w_container" class="g_c g_container crash col-md-9 col-sm-12">
        <div class="tower_multipliers_bg"></div>
        <div class="tower">
            <div class="tower_multipliers">
                @for($i = 0; $i < 10; $i++)
                    <div data-row-id="{{$i}}"></div>
                @endfor
            </div>
        </div>
        <div class="tower">
            <div class="tower_grid tower">
                @php
                    $same = array();
                    for($i = 0; $i < 10; $i++) $same[$i] = array();
                @endphp
                @for($i = 0; $i < 5 * 10; $i++)
                    @php(array_push($same[$i % 10], 1))
                    <div data-r="{{$i % 10}}" data-grid-in-row-id="{{sizeof($same[$i % 10]) - 1}}" data-grid-id="{{$i}}" class="mine_disabled tower_select"><div></div></div>
                @endfor
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ $asset('/game/tower.js', 'js') }}"></script>