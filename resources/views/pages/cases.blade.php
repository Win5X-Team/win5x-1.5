@if(\App\Game::isDisabled('cases'))
    <script type="text/javascript">load('games')</script>
@else
    <script type="text/javascript" src="{{ $asset('/game/cases.js', 'js') }}"></script>

    <div class="game__wrapper">
        <div class="bonus_header">
            <p style="margin-bottom: unset">
                Кейсы
            </p>
        </div>
        <div class="box-container">
            @foreach(\App\Box::get()->chunk(3) as $items)
                <div class="row">
                    @foreach($items as $box)
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="case-container" data-case-id="{{$box->id}}">
                                <div class="case-header">
                                    {{$box->name}}
                                </div>
                                <div class="case" id="{{$box->id}}">
                                    <div class="scene spinner-p" style="-webkit-transform:rotateX(-388deg) rotateY(-38deg); -moz-transform:rotateX(-388deg) rotateY(-38deg); -ms-transform:rotateX(-388deg) rotateY(-38deg); transform:rotateX(-388deg) rotateY(-38deg); ">
                                        <div class="shape cuboid-1 cub-1">
                                            <div class="face ft" style="background: url({{$box->front}})"></div>
                                            <div class="face bk" style="background: url({{$box->front}})"></div>
                                            <div class="face rt" style="background: url({{$box->side}})"></div>
                                            <div class="face lt" style="background: url({{$box->side}})"></div>
                                            <div class="face bm" style="background: url({{$box->bottom}})"></div>
                                            <div class="face tp t-glow-sel" style="background: url({{$box->top}})"></div>
                                            <div class="lid"></div>
                                        </div>
                                        <div class="shape cuboid-2 cub-2 lid-t">
                                            <div class="face ft" style="background: url({{$box->lid_front}})"></div>
                                            <div class="face bk" style="background: url({{$box->lid_front}})"></div>
                                            <div class="face rt" style="background: url({{$box->lid_side}})"></div>
                                            <div class="face lt" style="background: url({{$box->lid_side}})"></div>
                                            <div class="face bm" style="background: url({{$box->lid_bottom}})"></div>
                                            <div class="face tp" style="background: url({{$box->lid_top}})"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="case-contains" data-data-id="{{$box->id}}"
                                    @php
                                        $contains = json_decode($box->contains, true);
                                        foreach($contains as $key => $item) unset($contains[$key]['chance']);
                                        echo 'data-json="'.str_replace("\"", "'", json_encode($contains)).'"';
                                    @endphp>
                                    <p>Содержит:</p>
                                    <div class="case-items">
                                        @foreach(json_decode($box->contains, true) as $item)
                                            {{$item['type']}}
                                            <div class="case-item case-{{$item['rarity']}}">
                                                {{\App\Box::getName($item['value'], $item['type'])}}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="case-footer">
                                    <div class="case-footer-price" data-price-id="{{$box->id}}">{!! $box->is_free == 1 ? (\App\Box::isFreeAvailable() || Auth::guest() ? 'Бесплатно' : ('<span id="countdown"></span><script>$(document).ready(function(){countdown('.(Auth::user()->free_case_time + 86400 - time()).')});</script>')) : $box->price.' руб.' !!}</div>
                                    <button @if($box->is_free == 1 && !\App\Box::isFreeAvailable()) data-disabled="true" @endif class="case-purchase @if($box->is_free == 1 && !\App\Box::isFreeAvailable()) case-btn-disabled @endif" data-case="{{$box->id}}">Открыть</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endif