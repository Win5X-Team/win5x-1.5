<div class="game__wrapper">
    <div class="col-md-3 col-sm-12 g_sidebar" data-parent="#w_container">
        <div class="row g_follow">
            <div class="row m0">
                <div class="g_md_n col-md-12">
                    <i class="fad fa-octagon"></i>
                    <span>Keno</span>
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
            <div class="col-xs-12 col-sm-12 mt5">
                <div class="g_s g_btn" onclick="keno()" id="play"><span id="bet_btn">Играть</span></div>
            </div>
            @include('pages.game_task', ['game_id' => 14])
        </div>
        <div class="g_sidebar_footer">
            <div class="g_sidebar_footer_button" onclick="info('keno')">
                <i class="fas fa-info-circle tooltip" title="Информация о игре"></i>
            </div>
            <div class="g_sidebar_footer_button" onclick="load('fairness')">
                <i class="fad fa-shield-alt tooltip" title="Честная игра"></i>
            </div>
        </div>
    </div>
    <div id="w_container" class="g_c g_container crash col-md-9 col-sm-12">
        <div class="keno_container">
            <div class="keno_grid">
                @for($i = 1; $i <= 40; $i++)
                    <div data-keno-id="{{$i}}"><span>{{$i}}</span></div>
                @endfor
            </div>
            <div class="cf_history keno_mul">
                <div id="cf_slick"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ $asset('/game/keno.js', 'js') }}"></script>