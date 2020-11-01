<div data-watch-id="{{$id}}" data-watch-game="soccer">
    <div class="col-lg-9">
        <div class="match-over" data-watch="isFinished" data-watch-replace-type="visibility" data-watch-visibility-visible-trigger="true">
            Матч завершен. Доступна статистика игры
        </div>
        <div class="sport-game-header sport-soccer">
            <div class="sport-game-header-overlay">
                <div class="sport-game-header-overlay-date">
                    <span data-watch="score|title" data-watch-or="date"></span>

                    <div class="sport-weather">
                        <div data-watch-replace-type="title" data-watch="weather|weather|title"><i class="fad fa-sun"></i> <span data-watch="weather|weather|value"></span></div>
                        <div data-watch-replace-type="title" data-watch="weather|wind|title"><i class="fad fa-wind"></i> <span data-watch="weather|wind|value"></span></div>
                        <div data-watch-replace-type="title" data-watch="weather|t|title"><i class="fad fa-tachometer-alt-fast"></i> <span data-watch="weather|t|value"></span></div>
                        <div data-watch-replace-type="title" data-watch="weather|rain|title"><i class="fad fa-raindrops"></i> <span data-watch="weather|rain|value"></span></div>
                    </div>
                </div>
                <div class="sport-game-header-overlay-teams">
                    <div><p data-watch="first"></p> <span data-watch="score|first"></span></div>
                    <div><p data-watch="second"></p> <span data-watch="score|second"></span></div>
                </div>
                <div class="sport-game-header-overlay-info">
                    <i class="la la-info-circle"></i> <span data-watch="info"></span>
                </div>
            </div>

            <div class="sport-game-header-overlay-expand-button">
                <i class="fal fa-angle-down"></i>
            </div>
        </div>
        <div class="sport-world-place">
            <i class="fad fa-globe-europe"></i> <span data-watch="location"></span>
        </div>
        <div class="sport-bet-tabs line-tabs">
            <div class="sport-bet-tab sport-bet-tab-active">
                <span>Основная игра</span>
                <div class="sport-bet-tab-indicator"></div>
            </div>
            <div class="sport-bet-tab">
                <span>Голы</span>
                <div class="sport-bet-tab-indicator"></div>
            </div>
            <div class="sport-bet-tab">
                <span>Тайм</span>
                <div class="sport-bet-tab-indicator"></div>
            </div>
            <div class="sport-bet-tab">
                <span>Азиатские линии</span>
                <div class="sport-bet-tab-indicator"></div>
            </div>
            <div class="sport-bet-tab">
                <span>Угловые</span>
                <div class="sport-bet-tab-indicator"></div>
            </div>
            <div class="sport-bet-tab">
                <span>Карточки</span>
                <div class="sport-bet-tab-indicator"></div>
            </div>
        </div>

        <div data-watch-fragment="/sport/fragment/bets.soccer/{{ $id }}"></div>
    </div>
    <div class="hidden-xs hidden-sm hidden-md col-lg-3 sport_info">
        <div class="sport_info_header">
            Информация
        </div>
        <div data-expand-content="1">
            <div class="sport_info_live">
                <div class="sport_info_block">
                    <i class="fad fa-flag-alt tooltip" title="Угловые"></i>
                    <div class="sport_info_team" data-watch="first"></div>
                    <div data-watch="stats|first|corner"></div>
                    <div class="sport_info_team" data-watch="second"></div>
                    <div data-watch="stats|second|corner"></div>
                </div>
                <div class="sport_info_block">
                    <i class="fas fa-rectangle-portrait redCard tooltip" title="Красные карточки"></i>
                    <div data-watch="stats|first|redCard"></div>
                    <div data-watch="stats|second|redCard"></div>
                </div>
                <div class="sport_info_block">
                    <i class="fas fa-rectangle-portrait yellowCard tooltip" title="Желтые карточки"></i>
                    <div data-watch="stats|first|yellowCard"></div>
                    <div data-watch="stats|second|yellowCard"></div>
                </div>
                <div class="sport_info_block">
                    <i class="fad fa-futbol tooltip" title="Пенальти"></i>
                    <div data-watch="stats|first|penalty"></div>
                    <div data-watch="stats|second|penalty"></div>
                </div>
            </div>
        </div>
        <div data-expand-content="2">
            <div class="sport-live-soccer-info">
                <div class="sport-live-soccer-data">
                    <div>Атаки</div>
                    <div>
                        <span data-watch="stats|first|attacks"></span>
                        /
                        <span data-watch="stats|second|attacks"></span>
                    </div>
                </div>
                <div class="sport-live-soccer-data">
                    <div>Опасные атаки</div>
                    <div>
                        <span data-watch="stats|first|dangerAttacks"></span>
                        /
                        <span data-watch="stats|second|dangerAttacks"></span>
                    </div>
                </div>
                <div class="sport-live-soccer-data">
                    <div>Удары в створ</div>
                    <div>
                        <span data-watch="stats|first|shotsOn"></span>
                        /
                        <span data-watch="stats|second|shotsOn"></span>
                    </div>
                </div>
                <div class="sport-live-soccer-data">
                    <div>Удары в сторону ворот</div>
                    <div>
                        <span data-watch="stats|first|shotsOff"></span>
                        /
                        <span data-watch="stats|second|shotsOff"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('/js/vendor/jquery.mousewheel.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/vendor/mwheelIntent.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/vendor/jquery.jscrollpage.min.js') }}"></script>
<script type="text/javascript" src="{{ $asset('/game/sport.js', 'js') }}"></script>