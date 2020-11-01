@foreach(\App\Http\Controllers\SportController::parseSoccer() as $key => $club)
    <div class="sport-club-container">
        <div class="sport-club-header">{{$key}}</div>
        <div class="sport-club-content">
            <div class="sport-club-match row">
                @foreach($club as $match)
                    @php($game = \App\Http\Controllers\SportController::formatSoccerGame($match['CI'], $match, true))
                    <div class="sport-match" data-watch-id="{{$match['CI']}}" data-watch-game="soccer">
                        <div class="col-xs-12 col-sm-3">
                            <div class="sport-club-match-date" data-watch="date"></div>
                            <div class="sport-club-match-names" onclick="load('sport/line/soccer/{{$match['CI']}}')">
                                <div data-watch="first"></div>
                                <div data-watch="second"></div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-9 sport-match-actions">
                            <div class="sport-club-match-date">1х2</div>
                            <div class="sport-club-match-button" onclick="addTicket('soccer', {{$game['id']}}, '{{$game['header']}}', '1x2', '{{$game['first']}}', 'bets|main|1x2|1|value', $(this))">
                                <p class="sport-unavailable" data-watch="bets|main|1x2|1|validated" data-watch-replace-type="visibility" data-watch-visibility-visible-trigger="false">Недоступно</p>
                                <div data-watch="first"></div>
                                <div data-watch="bets|main|1x2|1|value"></div>
                            </div>
                            <div class="sport-club-match-button" onclick="addTicket('soccer', {{$game['id']}}, '{{$game['header']}}', '1x2', 'Ничья', 'bets|main|1x2|2|value', $(this))">
                                <p class="sport-unavailable" data-watch="bets|main|1x2|2|validated" data-watch-replace-type="visibility" data-watch-visibility-visible-trigger="false">Недоступно</p>
                                <div>Ничья</div>
                                <div data-watch="bets|main|1x2|2|value"></div>
                            </div>
                            <div class="sport-club-match-button" onclick="addTicket('soccer', {{$game['id']}}, '{{$game['header']}}', '1x2', '{{$game['second']}}', 'bets|main|1x2|3|value', $(this))">
                                <p class="sport-unavailable" data-watch="bets|main|1x2|3|validated" data-watch-replace-type="visibility" data-watch-visibility-visible-trigger="false">Недоступно</p>
                                <div data-watch="second"></div>
                                <div data-watch="bets|main|1x2|3|value"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endforeach