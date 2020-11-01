@php($game = \App\Http\Controllers\SportController::parseSoccerGame($id))

<div id="main-game" class="sport-bet-tab-content">
    <div class="sport-club-container sport-bet-container">
        <div class="sport-club-header">1x2</div>
        <div class="sport-club-content">
            <div class="sport-club-match row">
                <div class="sport-bet-table sport-bet-table-noheader">
                    <div class="sport-bet-table-row sport-bet-table-3">
                        <div class="sport-table-entry" onclick="addTicket('soccer', {{$game['id']}}, '{{$game['header']}}', '1x2', '{{$game['first']}}', 'bets|main|1x2|1|value', $(this))">
                            @if($game['bets']['main']['1x2'][1]['validated'] === false)
                                <p class="sport-unavailable">Недоступно</p>
                            @endif
                            {{$game['first']}} <span data-watch="bets|main|1x2|1|value">{{$game['bets']['main']['1x2'][1]['value']}}</span>
                        </div>
                        <div class="sport-table-entry" onclick="addTicket('soccer', {{$game['id']}}, '{{$game['header']}}', '1x2', 'Ничья', 'bets|main|1x2|2|value', $(this))">
                            @if($game['bets']['main']['1x2'][2]['validated'] === false)
                                <p class="sport-unavailable">Недоступно</p>
                            @endif
                            Ничья <span data-watch="bets|main|1x2|2|value">{{$game['bets']['main']['1x2'][2]['value']}}</span>
                        </div>
                        <div class="sport-table-entry" onclick="addTicket('soccer', {{$game['id']}}, '{{$game['header']}}', '1x2', '{{$game['second']}}', 'bets|main|1x2|3|value', $(this))">
                            @if($game['bets']['main']['1x2'][3]['validated'] === false)
                                <p class="sport-unavailable">Недоступно</p>
                            @endif
                            {{$game['second']}} <span data-watch="bets|main|1x2|3|value">{{$game['bets']['main']['1x2'][3]['value']}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sport-club-container sport-bet-container">
        <div class="sport-club-header">Двойной шанс</div>
        <div class="sport-club-content">
            <div class="sport-club-match row">
                <div class="sport-bet-table sport-bet-table-noheader">
                    <div class="sport-bet-table-row sport-bet-table-3">
                        <div class="sport-table-entry">
                            0.5 <span>-1</span>
                        </div>
                        <div class="sport-table-entry">
                            0.5 <span>-1</span>
                        </div>
                        <div class="sport-table-entry">
                            ban <span>-1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sport-club-container sport-bet-container">
        <div class="sport-club-header">Тотал</div>
        <div class="sport-club-content">
            <div class="sport-club-match row">
                <div class="sport-bet-table">
                    <div class="sport-bet-table-row sport-bet-table-2">
                        <div class="sport-bet-header">
                            больше
                        </div>
                        <div class="sport-bet-header">
                            меньше
                        </div>
                    </div>
                    <div class="sport-bet-table-row sport-bet-table-2">
                        <div class="sport-table-entry">
                            0.5 <span>-1</span>
                        </div>
                        <div class="sport-table-entry">
                            0.5 <span>-1</span>
                        </div>
                    </div>
                    <div class="sport-bet-table-row sport-bet-table-2">
                        <div class="sport-table-entry">
                            1.5 <span>-1</span>
                        </div>
                        <div class="sport-table-entry">
                            1.5 <span>-1</span>
                        </div>
                    </div>
                    <div class="sport-bet-table-row sport-bet-table-2">
                        <div class="sport-table-entry">
                            2 <span>-1</span>
                        </div>
                        <div class="sport-table-entry">
                            2 <span>-1</span>
                        </div>
                    </div>
                    <div class="sport-bet-table-row sport-bet-table-2">
                        <div class="sport-table-entry">
                            2.25 <span>-1</span>
                        </div>
                        <div class="sport-table-entry">
                            2.25 <span>-1</span>
                        </div>
                    </div>
                    <div class="sport-bet-table-row sport-bet-table-2">
                        <div class="sport-table-entry">
                            2.5 <span>-1</span>
                        </div>
                        <div class="sport-table-entry">
                            2.5 <span>-1</span>
                        </div>
                    </div>
                    <div class="sport-bet-table-row sport-bet-table-2">
                        <div class="sport-table-entry">
                            2.75 <span>-1</span>
                        </div>
                        <div class="sport-table-entry">
                            2.75 <span>-1</span>
                        </div>
                    </div>
                    <div class="sport-bet-table-row sport-bet-table-2">
                        <div class="sport-table-entry">
                            3 <span>-1</span>
                        </div>
                        <div class="sport-table-entry">
                            3 <span>-1</span>
                        </div>
                    </div>
                    <div class="sport-bet-table-row sport-bet-table-2">
                        <div class="sport-table-entry">
                            3.5 <span>-1</span>
                        </div>
                        <div class="sport-table-entry">
                            3.5 <span>-1</span>
                        </div>
                    </div>
                    <div class="sport-bet-table-row sport-bet-table-2">
                        <div class="sport-table-entry">
                            4.5 <span>-1</span>
                        </div>
                        <div class="sport-table-entry">
                            4.5 <span>-1</span>
                        </div>
                    </div>
                    <div class="sport-bet-table-row sport-bet-table-2">
                        <div class="sport-table-entry">
                            5.5 <span>-1</span>
                        </div>
                        <div class="sport-table-entry">
                            5.5 <span>-1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sport-club-container sport-bet-container">
        <div class="sport-club-header">Гандикап</div>
        <div class="sport-club-content">
            <div class="sport-club-match row">
                <div class="sport-bet-table">
                    <div class="sport-bet-table-row sport-bet-table-2">
                        <div class="sport-bet-header">
                            TBA TEAM 1
                        </div>
                        <div class="sport-bet-header">
                            TBA TEAM 2
                        </div>
                    </div>
                    <div class="sport-bet-table-row sport-bet-table-2">
                        <div class="sport-table-entry">
                            0.5 <span>-1</span>
                        </div>
                        <div class="sport-table-entry">
                            0.5 <span>-1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>