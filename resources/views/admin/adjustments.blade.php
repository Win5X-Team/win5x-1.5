<div id="__ajax_title" style="display: none">@if(Auth::user()->chat_role < 3) Статистика регистраций пользователей @else Подкрутка @endif</div>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    @if(Auth::user()->chat_role < 3)
        Вам недоступна эта информация
    @else
    <div class="kt-portlet">
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="kt-grid  kt-wizard-v2 kt-wizard-v2--white" id="kt_wizard_v2" data-ktwizard-state="first">
                <div class="kt-grid__item kt-wizard-v2__aside">
                    <div class="kt-wizard-v2__nav">
                        <div class="kt-wizard-v2__nav-items kt-wizard-v2__nav-items--clickable">
                            <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step" data-ktwizard-state="current">
                                <div class="kt-wizard-v2__nav-body">
                                    <div class="kt-wizard-v2__nav-icon">
                                        <i class="fad fa-circle"></i>
                                    </div>
                                    <div class="kt-wizard-v2__nav-label">
                                        <div class="kt-wizard-v2__nav-label-title">
                                            Wheel
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending">
                                <div class="kt-wizard-v2__nav-body">
                                    <div class="kt-wizard-v2__nav-icon">
                                        <i class="la la-line-chart"></i>
                                    </div>
                                    <div class="kt-wizard-v2__nav-label">
                                        <div class="kt-wizard-v2__nav-label-title">
                                            Crash
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending">
                                <div class="kt-wizard-v2__nav-body">
                                    <div class="kt-wizard-v2__nav-icon">
                                        <i class="fal fa-bomb"></i>
                                    </div>
                                    <div class="kt-wizard-v2__nav-label">
                                        <div class="kt-wizard-v2__nav-label-title">
                                            Mines
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending">
                                <div class="kt-wizard-v2__nav-body">
                                    <div class="kt-wizard-v2__nav-icon">
                                        <i class="fal fa-dice-two"></i>
                                    </div>
                                    <div class="kt-wizard-v2__nav-label">
                                        <div class="kt-wizard-v2__nav-label-title">
                                            Dice
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending">
                                <div class="kt-wizard-v2__nav-body">
                                    <div class="kt-wizard-v2__nav-icon">
                                        <i class="fal fa-chess-rook-alt"></i>
                                    </div>
                                    <div class="kt-wizard-v2__nav-label">
                                        <div class="kt-wizard-v2__nav-label">
                                            <div class="kt-wizard-v2__nav-label-title">
                                                Tower
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending">
                                <div class="kt-wizard-v2__nav-body">
                                    <div class="kt-wizard-v2__nav-icon">
                                        <i class="fad fa-badge"></i>
                                    </div>
                                    <div class="kt-wizard-v2__nav-label">
                                        <div class="kt-wizard-v2__nav-label">
                                            <div class="kt-wizard-v2__nav-label-title">
                                                Roulette
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending">
                                <div class="kt-wizard-v2__nav-body">
                                    <div class="kt-wizard-v2__nav-icon">
                                        <i class="fal fa-grip-lines"></i>
                                    </div>
                                    <div class="kt-wizard-v2__nav-label">
                                        <div class="kt-wizard-v2__nav-label">
                                            <div class="kt-wizard-v2__nav-label-title">
                                                Stairs
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending">
                                <div class="kt-wizard-v2__nav-body">
                                    <div class="kt-wizard-v2__nav-icon">
                                        <i class="fal fa-coin"></i>
                                    </div>
                                    <div class="kt-wizard-v2__nav-label">
                                        <div class="kt-wizard-v2__nav-label">
                                            <div class="kt-wizard-v2__nav-label-title">
                                                Coinflip
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending">
                                <div class="kt-wizard-v2__nav-body">
                                    <div class="kt-wizard-v2__nav-icon">
                                        <i class="fal fa-clone"></i>
                                    </div>
                                    <div class="kt-wizard-v2__nav-label">
                                        <div class="kt-wizard-v2__nav-label">
                                            <div class="kt-wizard-v2__nav-label-title">
                                                HiLo
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending">
                                <div class="kt-wizard-v2__nav-body">
                                    <div class="kt-wizard-v2__nav-icon">
                                        <i class="fal fa-spade"></i>
                                    </div>
                                    <div class="kt-wizard-v2__nav-label">
                                        <div class="kt-wizard-v2__nav-label">
                                            <div class="kt-wizard-v2__nav-label-title">
                                                Blackjack
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending">
                                <div class="kt-wizard-v2__nav-body">
                                    <div class="kt-wizard-v2__nav-icon">
                                        <i class="fas fa-ball-pile"></i>
                                    </div>
                                    <div class="kt-wizard-v2__nav-label">
                                        <div class="kt-wizard-v2__nav-label">
                                            <div class="kt-wizard-v2__nav-label-title">
                                                Plinko
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending">
                                <div class="kt-wizard-v2__nav-body">
                                    <div class="kt-wizard-v2__nav-icon">
                                        <i class="fad fa-octagon"></i>
                                    </div>
                                    <div class="kt-wizard-v2__nav-label">
                                        <div class="kt-wizard-v2__nav-label">
                                            <div class="kt-wizard-v2__nav-label-title">
                                                Keno
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-dark fade show mt-3" role="alert">
                            <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                            <div class="alert-text">
                                <p><strong>Подкрутка</strong><br>
                                <span>Шансы на поражение (подмена выигрыша на проигрыш системой) зависят от баланса аккаунта пользователя.<br></span></p>
                                <p><strong>Базовый шанс</strong><br>
                                <span>Стандартный шанс на поражение.</span></p>
                                <p><strong>Максимальный шанс</strong><br>
                                <span>Шанс на поражение не сможет превысить это значение.</span></p>
                                <p><strong>Максимальный коэффициент</strong><br>
                                <span>Автоматическое поражение, если человек достигнет указаного значения коэффициента.</span></p>
                                <p><strong>Скорость</strong><br>
                                <span>Фактор, при котором алгоритм будет увеличивать шансы на проигрыш.<br>Чем он ниже, тем шанс на проигрыш для определенного порога баланса ниже.</span></p>
                                <p>Все игры, использующие такую систему поддерживают предварительный просмотр.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @php($balanceAdjustments = function($gameId) {
                    $v = \App\Http\Controllers\AdminController::getAdjustmentValues($gameId);
                    $base = $v['base'];
                    $speed = $v['speed'];
                    $max = $v['max'];
                    $mm = $v['mm'];

                    $sendFunc = "delayedv('#$gameId-base', function(v) { send('#g', '/admin/adj/$gameId/'"
                        ."+$('#$gameId-base').val()+'/'+$('#$gameId-max').val()+'/'+$('#$gameId-speed').val()+'/'+$('#$gameId-maxMul').val());"
                        ."adj($gameId, $('#$gameId-base').val(), $('#$gameId-max').val(), $('#$gameId-speed').val(), $('#$gameId-maxMul').val()) })";

                    return <<< HTML
                        <div class="form-group">
                            <label>Базовый шанс</label>
                            <div class="input-group">
                                <input id="$gameId-base" oninput="$sendFunc" value="$base" type="text" class="form-control" placeholder="Базовый шанс" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">
                                        <i class="flaticon2-percentage"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Максимальный шанс</label>
                            <div class="input-group">
                                <input id="$gameId-max" oninput="$sendFunc" value="$max" type="text" class="form-control" placeholder="Максимальный шанс" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">
                                        <i class="flaticon2-percentage"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Скорость</label>
                            <div class="input-group" id="i1">
                                <input id="$gameId-speed" oninput="$sendFunc" value="$speed" type="text" class="form-control" placeholder="Скорость" aria-describedby="basic-addon2">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Максимальный коэффициент</label>
                            <div class="input-group">
                                <input id="$gameId-maxMul" oninput="$sendFunc" value="$mm" type="text" class="form-control" placeholder="Максимальный коэффициент" aria-describedby="basic-addon2">
                            </div>
                        </div>

                        <div class="kt-widget4" style="margin-top: 20px" id="adj$gameId"></div>

                        <script type="text/javascript">$(document).ready(function() {adj($gameId, $base, $max, $speed, $mm)});</script>
HTML;
                })

                <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v2__wrapper" id="g">
                    <div class="kt-form" id="kt_form">
                        <div class="kt-wizard-v2__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
                            <div class="kt-heading kt-heading--md">Wheel</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v2__form">
                                    {!! $balanceAdjustments(2) !!}
                                </div>
                            </div>
                        </div>
                        <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">Crash</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v2__form">
                                    <div class="form-group">
                                        <label>Вероятность <strong>проиграть</strong> с коэффициентом 1.01 - 1.50</label>
                                        <div class="input-group" id="i3">
                                            <input oninput="delayedv('#i3 input', function(v) { send('#i3', '/admin/probability/crash_s/'+v) })"
                                                   value="{{$settings->crash_s}}" type="text" class="form-control" placeholder="Вероятность" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">
                                                    <i class="flaticon2-percentage"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Вероятность <strong>проиграть</strong> с коэффициентом 1.51 - 2.50</label>
                                        <div class="input-group" id="i4">
                                            <input oninput="delayedv('#i4 input', function(v) { send('#i4', '/admin/probability/crash_m/'+v) })"
                                                   value="{{$settings->crash_m}}" type="text" class="form-control" placeholder="Вероятность" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">
                                                    <i class="flaticon2-percentage"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Вероятность <strong>проиграть</strong> с коэффициентом 2.51 - 4.00</label>
                                        <div class="input-group" id="i5">
                                            <input oninput="delayedv('#i5 input', function(v) { send('#i5', '/admin/probability/crash_b/'+v) })"
                                                   value="{{$settings->crash_b}}" type="text" class="form-control" placeholder="Вероятность" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">
                                                    <i class="flaticon2-percentage"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Вероятность <strong>проиграть</strong> с коэффициентом 4.01 - 10.00</label>
                                        <div class="input-group" id="ii6">
                                            <input oninput="delayedv('#ii6 input', function(v) { send('#ii6', '/admin/probability/crash_h/'+v) })"
                                                   value="{{$settings->crash_h}}" type="text" class="form-control" placeholder="Вероятность" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">
                                                    <i class="flaticon2-percentage"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">Mines</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v2__form">
                                    {!! $balanceAdjustments(5) !!}
                                </div>
                            </div>
                        </div>
                        <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">Dice</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v2__form">
                                    {!! $balanceAdjustments(1) !!}
                                </div>
                            </div>
                        </div>
                        <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">Tower</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v2__form">
                                    {!! $balanceAdjustments(9) !!}
                                </div>
                            </div>
                        </div>
                        <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">Roulette</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v2__form">
                                    <div class="form-group">
                                        <label>Вероятность <strong>проиграть</strong> x36</label>
                                        <div class="input-group" id="r">
                                            <input oninput="delayedv('#r input', function(v) { send('#r', '/admin/probability/roulette/'+v) })"
                                                   value="{{$settings->roulette}}" type="text" class="form-control" placeholder="Вероятность" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">
                                                    <i class="flaticon2-percentage"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Вероятность <strong>проиграть</strong> x3</label>
                                        <div class="input-group" id="r1">
                                            <input oninput="delayedv('#r1 input', function(v) { send('#r1', '/admin/probability/roulette_3/'+v) })"
                                                   value="{{$settings->roulette_3}}" type="text" class="form-control" placeholder="Вероятность" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">
                                                    <i class="flaticon2-percentage"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Вероятность <strong>проиграть</strong> x2</label>
                                        <div class="input-group" id="r2">
                                            <input oninput="delayedv('#r2 input', function(v) { send('#r2', '/admin/probability/roulette_2/'+v) })"
                                                   value="{{$settings->roulette_2}}" type="text" class="form-control" placeholder="Вероятность" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">
                                                    <i class="flaticon2-percentage"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">Stairs</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v2__form">
                                    {!! $balanceAdjustments(11) !!}
                                </div>
                            </div>
                        </div>
                        <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">Coinflip</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v2__form">
                                    {!! $balanceAdjustments(4) !!}
                                </div>
                            </div>
                        </div>
                        <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">HiLo</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v2__form">
                                    {!! $balanceAdjustments(7) !!}
                                </div>
                            </div>
                        </div>
                        <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">Blackjack</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v2__form">
                                    {!! $balanceAdjustments(8) !!}
                                </div>
                            </div>
                        </div>
                        <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">Plinko</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v2__form">
                                    {!! $balanceAdjustments(13) !!}
                                </div>
                            </div>
                        </div>
                        <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">Keno</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v2__form">
                                    {!! $balanceAdjustments(14) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<script type="text/javascript" src="/_admin/js/adjustments.js?v={{$version}}"></script>