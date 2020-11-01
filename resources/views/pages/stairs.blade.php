<div class="game__wrapper">
    <div class="col-md-3 col-sm-12 g_sidebar" data-parent="#w_container">
        <div class="row g_follow">
            <div class="row m0">
                <div class="g_md_n col-md-12">
                    <i class="la la-stream"></i>
                    <span>Stairs</span>
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
                    Количество камней
                </div>
            </div>
            <div class="col-xs-12 mb10 mt5">
                <div class="bombs_container stairs_container">
                    <div data-bomb="1">1</div>
                    <div data-bomb="2">2</div>
                    <div data-bomb="3">3</div>
                    <div data-bomb="4" class="bc_active">4</div>
                    <div data-bomb="5">5</div>
                    <div data-bomb="6">6</div>
                    <div data-bomb="7">7</div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12">
                <div class="g_s g_btn" onclick="stairs()" id="play"><span id="bet_btn">Играть</span></div>
            </div>
            @include('pages.game_task', ['game_id' => 11])
        </div>
        <div class="g_sidebar_footer">
            <div class="g_sidebar_footer_button" onclick="info('stairs')">
                <i class="fas fa-info-circle tooltip" title="Информация о игре"></i>
            </div>
            <div class="g_sidebar_footer_button" onclick="load('fairness')">
                <i class="fad fa-shield-alt tooltip" title="Честная игра"></i>
            </div>
        </div>
    </div>
    <div id="w_container" class="g_c g_container stairs col-md-9 col-sm-12">
        <div class="stairs-multiplier-table">
            <div class="stairs-row" data-m-row="13"></div>
            <div class="stairs-row" data-m-row="12"></div>
            <div class="stairs-row" data-m-row="11"></div>
            <div class="stairs-row" data-m-row="10"></div>
            <div class="stairs-row" data-m-row="9"></div>
            <div class="stairs-row" data-m-row="8"></div>
            <div class="stairs-row" data-m-row="7"></div>
            <div class="stairs-row" data-m-row="6"></div>
            <div class="stairs-row" data-m-row="5"></div>
            <div class="stairs-row" data-m-row="4"></div>
            <div class="stairs-row" data-m-row="3"></div>
            <div class="stairs-row" data-m-row="2"></div>
            <div class="stairs-row" data-m-row="1"></div>
        </div>
        <div class="stairs-container">
            <div class="stairs-centered" id="stairs_container">
                <div class="stairs-ladder" data-stairs-mouseover="true"></div>
                <div class="stairs-row">
                    @for($i = 0; $i < 8; $i++) <div class="stairs-block stairs-block-disabled" data-row="13" data-cell-id="{{$i}}"></div> @endfor
                </div>
                <div class="stairs-row">
                    @for($i = 0; $i < 9; $i++) <div class="stairs-block stairs-block-disabled" data-row="12" data-cell-id="{{$i}}"></div> @endfor
                </div>
                <div class="stairs-row">
                    @for($i = 0; $i < 10; $i++) <div class="stairs-block stairs-block-disabled" data-row="11" data-cell-id="{{$i}}"></div> @endfor
                </div>
                <div class="stairs-row">
                    @for($i = 0; $i < 19; $i++) <div class="stairs-block stairs-block-disabled" data-row="10" data-cell-id="{{$i}}"></div> @endfor
                </div>
                <div class="stairs-row">
                    @for($i = 0; $i < 9; $i++) <div class="stairs-block empty-stairs-block"></div> @endfor
                    @for($i = 0; $i < 11; $i++) <div class="stairs-block stairs-block-disabled" data-row="9" data-cell-id="{{$i}}"></div> @endfor
                </div>
                <div class="stairs-row">
                    @for($i = 0; $i < 8; $i++) <div class="stairs-block empty-stairs-block"></div> @endfor
                    @for($i = 0; $i < 12; $i++) <div class="stairs-block stairs-block-disabled" data-row="8" data-cell-id="{{$i}}"></div> @endfor
                </div>
                <div class="stairs-row">
                    <div class="stairs-block empty-stairs-block"></div>
                    <div class="stairs-block empty-stairs-block"></div>
                    <div class="stairs-block empty-stairs-block"></div>
                    @for($i = 0; $i < 17; $i++) <div class="stairs-block stairs-block-disabled" data-row="7" data-cell-id="{{$i}}"></div> @endfor
                </div>
                <div class="stairs-row">
                    <div class="stairs-block empty-stairs-block"></div>
                    <div class="stairs-block empty-stairs-block"></div>
                    @for($i = 0; $i < 15; $i++) <div class="stairs-block stairs-block-disabled" data-row="6" data-cell-id="{{$i}}"></div> @endfor
                    <div class="stairs-block empty-stairs-block"></div>
                    <div class="stairs-block empty-stairs-block"></div>
                    <div class="stairs-block empty-stairs-block"></div>
                </div>
                <div class="stairs-row">
                    @for($i = 0; $i < 19; $i++) <div class="stairs-block stairs-block-disabled" data-row="5" data-cell-id="{{$i}}"></div> @endfor
                </div>
                <div class="stairs-row">
                    <div class="stairs-block empty-stairs-block"></div>
                    <div class="stairs-block empty-stairs-block"></div>
                    <div class="stairs-block empty-stairs-block"></div>
                    @for($i = 0; $i < 17; $i++) <div class="stairs-block stairs-block-disabled" data-row="4" data-cell-id="{{$i}}"></div> @endfor
                </div>
                <div class="stairs-row">
                    <div class="stairs-block empty-stairs-block"></div>
                    @for($i = 0; $i < 19; $i++) <div class="stairs-block stairs-block-disabled" data-row="3" data-cell-id="{{$i}}"></div> @endfor
                </div>
                <div class="stairs-row">
                    @for($i = 0; $i < 19; $i++) <div class="stairs-block stairs-block-disabled" data-row="2" data-cell-id="{{$i}}"></div> @endfor
                </div>
                <div class="stairs-row">
                    @for($i = 0; $i < 20; $i++) <div class="stairs-block stairs-block-disabled" data-row="1" data-cell-id="{{$i}}"></div> @endfor
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ $asset('/game/stairs.js', 'js') }}"></script>