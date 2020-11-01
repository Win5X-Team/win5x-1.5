@php
    $games = \App\Http\Controllers\SportController::parseLive('soccer');
    $game = $games[array_key_first($games)][0];
    $game = \App\Http\Controllers\SportController::parseSoccerGame($game['I'], true);
@endphp
<div class="sport-slider-date" onclick="load('sport/line/soccer/{{$game['id']}}')">
    <div class="sport-club-match-live">Live</div>
    <i class="fal fa-futbol"></i>
    {{$game['date']}}
</div>
<div class="sport-slider-team" onclick="load('sport/line/soccer/{{$game['id']}}')">
    {{$game['first']}} vs {{$game['second']}}
</div>
<div>
    <div class="sport-match-actions">
        <div class="sport-club-match-date">1х2</div>
        <div class="sport-club-match-button" onclick="addTicket('soccer', {{$game['id']}}, '{{$game['header']}}', '1x2', '{{$game['first']}}', 'bets|main|1x2|1|value', $(this))">
            @if($game['bets']['main']['1x2'][1]['validated'] === false)
                <div class="sport-unavailable">Недоступно</div>
            @endif
            <div>{{$game['first']}}</div>
            <div data-watch="bets|main|1x2|1|value">{{$game['bets']['main']['1x2'][1]['value']}}</div>
        </div>
        <div class="sport-club-match-button" onclick="addTicket('soccer', {{$game['id']}}, '{{$game['header']}}', '1x2', 'Ничья', 'bets|main|1x2|2|value', $(this))">
            @if($game['bets']['main']['1x2'][2]['validated'] === false)
                <div class="sport-unavailable">Недоступно</div>
            @endif
            <div>Ничья</div>
            <div data-watch="bets|main|1x2|2|value">{{$game['bets']['main']['1x2'][2]['value']}}</div>
        </div>
        <div class="sport-club-match-button" onclick="addTicket('soccer', {{$game['id']}}, '{{$game['header']}}', '1x2', '{{$game['second']}}', 'bets|main|1x2|3|value', $(this))">
            @if($game['bets']['main']['1x2'][3]['validated'] === false)
                <div class="sport-unavailable">Недоступно</div>
            @endif
            <div>{{$game['second']}}</div>
            <div data-watch="bets|main|1x2|3|value">{{$game['bets']['main']['1x2'][3]['value']}}</div>
        </div>
    </div>
</div>