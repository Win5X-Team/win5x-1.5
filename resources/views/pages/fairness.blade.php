@if(Auth::guest())
    <script type="text/javascript">load('games', function() { $('#b_si').click(); })</script>
@else
<div class="game__wrapper bonus_wrapper">
    <div class="bonus_header">
        <p>Честная игра <i class="fas fa-info-circle fn_btn_info" onclick="info('provably_fair')"></i></p>
        <span>Проверка системы работы честной игры</span>
    </div>
    <div class="fn_container">
        <div class="fn_form">
            <div class="fn_form_block">
                <p>Игра</p>
                <div class="fn_games">
                    <div class="fn_games_container">
                        <div class="fn_game fn_game_selected tooltip" title="Dice" data-game="dice">
                            <i class="fa fa-dice"></i>
                        </div>
                        <div class="fn_game tooltip" title="Wheel" data-game="wheel">
                            <i class="fad fa-circle"></i>
                        </div>
                        <div class="fn_game tooltip" data-game="crash" title="Crash">
                            <i class="fa fa-chart-line"></i>
                        </div>
                        <div class="fn_game tooltip" title="Roulette" data-game="roulette">
                            <i class="fad fa-badge"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fn_form_block">
                <p>Клиентский хэш</p>
                <input value="{{\App\User::where('id', Auth::user()->id)->first()->client_seed}}" disabled id="_client">
                <a class="ll cs_change" onclick="client_seed_change_prompt()">Изменить</a>
            </div>
            <div class="fn_form_block">
                <p>Серверный хэш</p>
                <input placeholder="Серверный хэш" oninput="hash()" value="example" id="_server">
            </div>
        </div>
        <div class="fn_info">
            <div class="fn_block">
                <p>РЕЗУЛЬТАТ</p>
                <div class="fn_sub">ХЭШ:</div>
                <div id="hash"></div>
                <div class="fn_sub" id="number_sub">ЧИСЛО:</div>
                <div id="number"></div>
            </div>
            <div class="fn_block">
                <p>ФОРМУЛА</p>
                <div id="f"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ $asset('/provablyfair.js', 'js') }}"></script>
@endif