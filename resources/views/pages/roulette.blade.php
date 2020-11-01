<div class="game__wrapper">
    <div class="col-md-3 col-sm-12 g_sidebar" data-parent="#w_container">
        <div class="row g_follow">
            <div class="row m0">
                <div class="g_md_n col-md-12">
                    <i class="fad fa-badge"></i>
                    <span>Roulette</span>
                </div>
                <div class="col-xs-12">
                    <div class="b_label">
                        Выберите фишку
                    </div>
                    <div class="token-container">
                        <div class="tokens">
                            <div class="tc"><div class="token token-active" data-value="0.1">0.1</div></div>
                            <div class="tc"><div class="token" data-value="1">1</div></div>
                            <div class="tc"><div class="token" data-value="5">5</div></div>
                            <div class="tc"><div class="token" data-value="10">10</div></div>
                            <div class="tc"><div class="token" data-value="50">50</div></div>
                            <div class="tc"><div class="token" data-value="100">100</div></div>
                            <div class="tc"><div class="token" data-value="250">250</div></div>
                            <div class="tc"><div class="token" data-value="500">500</div></div>
                            <div class="tc"><div class="token" data-value="1000">1K</div></div>
                            <div class="tc"><div class="token" data-value="2000">2K</div></div>
                            <div class="tc"><div class="token" data-value="2500">2.5K</div></div>
                            <div class="tc"><div class="token" data-value="5000">5K</div></div>
                            <div class="tc"><div class="token" data-value="10000">10K</div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 mt10">
                <div class="g_s g_btn" onclick="roulette()" id="play"><span id="bet_btn">Играть</span></div>
            </div>
            @include('pages.game_task', ['game_id' => 10])
        </div>
        <div class="g_sidebar_footer">
            <div class="g_sidebar_footer_button" onclick="info('roulette')">
                <i class="fas fa-info-circle tooltip" title="Информация о игре"></i>
            </div>
            <div class="g_sidebar_footer_button" onclick="load('fairness')">
                <i class="fad fa-shield-alt tooltip" title="Честная игра"></i>
            </div>
        </div>
    </div>
    <div id="w_container" class="g_c g_container roulette col-md-9 col-sm-12">
        <div class="roulette-result" style="display: none">
            -1
        </div>
        <div class="roulette-graph-container">
            <div class="r-r">
                <div class="r-spinner">
                    <div class="r-ball"><span></span></div>
                    <div class="r-platebg"></div>
                    <div id="toppart" class="r-topnodebox">
                        <div class="r-silvernode"></div>
                        <div class="r-topnode r-silverbg"></div>
                        <span class="r-top r-silverbg"></span>
                        <span class="r-right r-silverbg"></span>
                        <span class="r-down r-silverbg"></span>
                        <span class="r-left r-silverbg"></span>
                    </div>
                    <div id="rcircle" class="r-pieContainer">
                        <div class="r-pieBackground"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="roulette-container">
            <div class="roulette-header">
                <div class="roulette-bet">Ставка: <span id="token_bet">0.00 руб.</span></div>
                <div class="roulette-controls">
                    <div class="roulette-button" onclick="r_history_back();">
                        <span><i class="la la-angle-left"></i> ОТМЕНА</span>
                    </div>
                    <div class="roulette-button" onclick="r_history_clear();">
                        <span><i class="la la-remove"></i> ОЧИСТИТЬ</span>
                    </div>
                </div>
            </div>
            <div class="roulette-game-container">
                <div class="r-row-top">
                    <div class="r-row r-row-small">
                        <div class="chip chip-green chip-huge" data-chip="0"><span>0</span></div>
                    </div>
                    <div class="r-row r-row-big">
                        <div class="chip chip-red" data-chip="3"><span>3</span></div>
                        <div class="chip chip-black" data-chip="6"><span>6</span></div>
                        <div class="chip chip-red" data-chip="9"><span>9</span></div>
                        <div class="chip chip-red" data-chip="12"><span>12</span></div>
                        <div class="chip chip-black" data-chip="15"><span>15</span></div>
                        <div class="chip chip-red" data-chip="18"><span>18</span></div>
                        <div class="chip chip-red" data-chip="21"><span>21</span></div>
                        <div class="chip chip-black" data-chip="24"><span>24</span></div>
                        <div class="chip chip-red" data-chip="27"><span>27</span></div>
                        <div class="chip chip-red" data-chip="30"><span>30</span></div>
                        <div class="chip chip-black" data-chip="33"><span>33</span></div>
                        <div class="chip chip-red" data-chip="36"><span>36</span></div>

                        <div class="chip chip-black" data-chip="2"><span>2</span></div>
                        <div class="chip chip-red" data-chip="5"><span>5</span></div>
                        <div class="chip chip-black" data-chip="8"><span>8</span></div>
                        <div class="chip chip-black" data-chip="11"><span>11</span></div>
                        <div class="chip chip-red" data-chip="14"><span>14</span></div>
                        <div class="chip chip-black" data-chip="17"><span>17</span></div>
                        <div class="chip chip-black" data-chip="20"><span>20</span></div>
                        <div class="chip chip-red" data-chip="23"><span>23</span></div>
                        <div class="chip chip-black" data-chip="26"><span>26</span></div>
                        <div class="chip chip-black" data-chip="29"><span>29</span></div>
                        <div class="chip chip-red" data-chip="32"><span>32</span></div>
                        <div class="chip chip-black" data-chip="35"><span>35</span></div>

                        <div class="chip chip-red" data-chip="1"><span>1</span></div>
                        <div class="chip chip-black" data-chip="4"><span>4</span></div>
                        <div class="chip chip-red" data-chip="7"><span>7</span></div>
                        <div class="chip chip-black" data-chip="10"><span>10</span></div>
                        <div class="chip chip-black" data-chip="13"><span>13</span></div>
                        <div class="chip chip-red" data-chip="16"><span>16</span></div>
                        <div class="chip chip-red" data-chip="19"><span>19</span></div>
                        <div class="chip chip-black" data-chip="22"><span>22</span></div>
                        <div class="chip chip-red" data-chip="25"><span>25</span></div>
                        <div class="chip chip-black" data-chip="28"><span>28</span></div>
                        <div class="chip chip-black" data-chip="31"><span>31</span></div>
                        <div class="chip chip-red" data-chip="34"><span>34</span></div>
                    </div>
                    <div class="r-row r-row-small">
                        <div class="chip chip-row" id="row1" data-chip="row1"><span>2:1</span></div>
                        <div class="chip chip-row" id="row2" data-chip="row2"><span>2:1</span></div>
                        <div class="chip chip-row" id="row3" data-chip="row3"><span>2:1</span></div>
                    </div>
                </div>
                <div class="r-row-bottom">
                    <div class="r-row r-row-small"></div>
                    <div class="r-row r-row-big">
                        <div class="r-row-bottom-top">
                            <div class="chip chip-row" id="1-12" data-chip="1-12"><span>1-12</span></div>
                            <div class="chip chip-row" id="13-24" data-chip="13-24"><span>13-24</span></div>
                            <div class="chip chip-row" id="25-36" data-chip="25-36"><span>25-36</span></div>
                        </div>
                        <div class="r-row-bottom-bottom">
                            <div class="chip chip-row chip-fix" id="1-18" data-chip="1-18"><span>1 до 18</span></div>
                            <div class="chip chip-row chip-fix" id="e" data-chip="even"><span>чет</span></div>

                            <div class="chip chip-red" id="red" data-chip="red"><span></span></div>
                            <div class="chip chip-black" id="black" data-chip="black"><span></span></div>

                            <div class="chip chip-row chip-fix" id="eo" data-chip="odd"><span>нечет</span></div>
                            <div class="chip chip-row chip-fix" id="19-36" data-chip="19-36"><span>19 до 36</span></div>
                        </div>
                    </div>
                    <div class="r-row r-row-small"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ $asset('/game/roulette.js', 'js') }}"></script>