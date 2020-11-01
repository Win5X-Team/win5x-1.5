@php
    if(Auth::guest()) return '';
    $bets = \App\SportBet::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
@endphp
@foreach($bets as $bet)
    <div class="sport-coupon">
        <div class="sport-coupon-header">
            {{ $bet['description_header'] }}
        </div>
        <div class="sport-coupon-content">
            <div class="sport-coupon-column">
                <div class="sport-coupon-category">{{ $bet['description_title'] }}</div>
                <div class="sport-coupon-result">{{ $bet['description_subtitle'] }}</div>
                <div class="sport-coupon-bet">Ставка:</div>
                <div class="sport-coupon-wager">{{ $bet['wager'] }} руб.</div>
            </div>
            <div class="sport-coupon-column sport-coupon-outcome-column">
                <div>{{ $bet['multiplier'] }}</div>
                <div>Выплата</div>
                <div>{{ $bet['wager'] * $bet['multiplier'] }} руб.</div>
            </div>
            <div class="sport-coupon-status">
                @if($bet['status'] == 0)
                    Ожидание
                @elseif($bet['status'] == 1)
                    Выиграно
                @elseif($bet['status'] == 2)
                    Проиграно
                @endif
            </div>
        </div>
    </div>
@endforeach