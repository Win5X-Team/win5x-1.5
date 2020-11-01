<div id="__ajax_title" style="display: none">Статистика</div>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    @if(Auth::user()->chat_role < 3)
        Вам недоступна эта информация
    @else
        <div class="row">
            <div class="col-lg-6 col-xl-4 order-lg-1 order-xl-1">
                <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--skin-solid kt-portlet--height-fluid">
                    <div class="kt-portlet__head kt-portlet__head--noborder kt-portlet__space-x">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Заработано
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fit">
                        <div class="kt-widget17">
                            <div class="kt-widget17__visual kt-widget17__visual--chart kt-portlet-fit--top kt-portlet-fit--sides" style="background-color: #fd397a">
                                <div class="kt-widget17__chart" style="height:320px;">
                                    <canvas id="kt_chart_activities"></canvas>
                                </div>
                            </div>
                            <script type="text/javascript">
                                var _pay_stats_days = [];
                                var _pay_stats_values = [];
                                @php
                                    $wrap = function($s) {
                                        if($s == null || $s <= 0) $s = '0';
                                        return $s;
                                    };
                                    $today = \DB::table('payments')->where('time', '>=', \Carbon\Carbon::today(new DateTimeZone("Etc/GMT-3"))->timestamp)->where('status', 1)->where('type', 0)->sum('amount');
                                    $week = \DB::table('payments')->where('time', '>=', \Carbon\Carbon::now(new DateTimeZone("Etc/GMT-3"))->subDays(7)->timestamp)->where('status', 1)->where('type', 0)->sum('amount');
                                    $month = \DB::table('payments')->where('time', '>=', \Carbon\Carbon::now(new DateTimeZone("Etc/GMT-3"))->subDays(30)->timestamp)->where('status', 1)->where('type', 0)->sum('amount');
                                    $summary = \DB::table('payments')->where('status', 1)->where('type', 0)->sum('amount');

                                    $days = ''; $values = '';
                                    for($i = 0; $i < 9; $i++) {
                                        $text = $i == 8 ? 'Сегодня' : (8 - $i) .' д. назад';
                                        echo "_pay_stats_days.push('$text');";
                                        echo "_pay_stats_values.push(".$wrap(\DB::table('payments')
                                            ->where('time', '>=', \Carbon\Carbon::now(new DateTimeZone("Etc/GMT-3"))->subDays((8-$i)+1)->timestamp)
                                            ->where('time', '<=', \Carbon\Carbon::now(new DateTimeZone("Etc/GMT-3"))->subDays(8-$i)->timestamp)->where('status', 1)->where('type', 0)->sum('amount')).");";
                                    }
                                @endphp
                            </script>

                            <div class="kt-widget17__stats">
                                <div class="kt-widget17__items">
                                    <div class="kt-widget17__item">
                                        <span class="kt-widget17__title">
                                            {{$wrap($today)}} руб.
                                        </span>
                                        <span class="kt-widget17__subtitle">
                                            Сегодня
                                        </span>
                                    </div>
                                    <div class="kt-widget17__item">
                                        <span class="kt-widget17__title">
                                            {{$wrap($week)}} руб.
                                        </span>
                                        <span class="kt-widget17__subtitle">
                                            Неделя
                                        </span>
                                    </div>
                                </div>
                                <div class="kt-widget17__items">
                                    <div class="kt-widget17__item">
                                        <span class="kt-widget17__title">
                                            {{$wrap($month)}} руб.
                                        </span>
                                        <span class="kt-widget17__subtitle">
                                            Месяц
                                        </span>
                                    </div>
                                    <div class="kt-widget17__item">
                                        <span class="kt-widget17__title">
                                            {{$wrap($summary)}} руб.
                                        </span>
                                        <span class="kt-widget17__subtitle">
                                            Все время
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-4 order-lg-1 order-xl-1">
                <div class="kt-portlet">
                    <div class="kt-portlet__head kt-portlet__head--right kt-portlet__head--noborder  kt-ribbon kt-ribbon--clip kt-ribbon--left kt-ribbon--danger">
                        <div class="kt-ribbon__target" style="top: 12px;">
                            <span class="kt-ribbon__inner"></span>Техническое обслуживание
                        </div>
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fit-top">
                        <div class="kt-space-5"></div>
                        Отключение сайта во время обновления или если что-то пойдет не так
                        <div class="kt-space-15"></div>
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border kt-section__content--fit">
                                <ul class="kt-nav" id="maintenance">
                                    @php
                                        $maintenanceActive = file_exists(storage_path().'/meta/server.down');
                                    @endphp
                                    <li id="m_normal" class="kt-nav__item @if(!$maintenanceActive) kt-nav__item--active @endif">
                                        <a href="javascript:void(0)" class="kt-nav__link"
                                            onclick="send('#maintenance', '/back/up', function() { $('#m_normal').addClass('kt-nav__item--active'); $('#m_m').removeClass('kt-nav__item--active'); })">
                                            <i class="kt-nav__link-icon flaticon2-layers-1"></i>
                                            <span class="kt-nav__link-text">Сайт функционирует в нормальном режиме</span>
                                        </a>
                                    </li>
                                    <li id="m_m" class="kt-nav__item @if($maintenanceActive) kt-nav__item--active @endif">
                                        <a href="javascript:void(0)" class="kt-nav__link"
                                            onclick="send('#maintenance', '/admin/shut/down', function() { $('#m_m').addClass('kt-nav__item--active'); $('#m_normal').removeClass('kt-nav__item--active'); })">
                                            <i class="kt-nav__link-icon flaticon2-list-3"></i>
                                            <span class="kt-nav__link-text">Техническое обслуживание</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 order-lg-3 order-xl-1">
                <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Счет
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-brand" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#kt_widget4_tab1_content" role="tab">
                                        Выплаты
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#kt_widget4_tab2_content" role="tab">
                                        Пополнения
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="kt-portlet__body" style="min-height: 540px">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget4_tab1_content">
                                <div class="kt-widget4">
                                    @php
                                        $opens = \DB::table('withdraw')->orderBy('id', 'asc')->where('status', 0)->get();
                                        foreach ($opens as $live) $live->user = \App\User::where('id', $live->user_id)->first();
                                    @endphp
                                    @if(sizeof($opens) == 0)
                                        <div class="empty_block">
                                            <i class="fas fa-clock"></i>
                                            <div>Здесь ничего нет</div>
                                        </div>
                                    @endif
                                    @foreach($opens as $live)
                                        @if($live->user == null || $live->status == 4) @continue @endif
                                        <div id="with_{{$live->id}}" class="kt-widget4__item">
                                            <div class="kt-widget4__pic kt-widget4__pic--pic">
                                                <img src="{{$live->user->avatar}}" alt="">
                                            </div>
                                            <div class="kt-widget4__info">
                                                <a href="javascript:void(0)" onclick="load('admin/user?id={{$live->user->id}}')" class="kt-widget4__username">
                                                    {{$live->user->username}} <i class="fas fa-angle-right"></i> {{$live->user->money}} руб.
                                                </a>
                                                <p class="kt-widget4__text" style="font-family: 'Open Sans', sans-serif">
                                                    {{$live->amount}} руб. <i class="fal fa-angle-right"></i> {{\App\Http\Controllers\SportController::formatDate(strtotime($live->created_at))}}
                                                    <br>
                                                    @php($s = '')
                                                    @if($live->system == 4)
                                                        QIWI
                                                        @php($s = 'Qiwi')
                                                    @elseif($live->system == 5)
                                                        ЯндексДеньги
                                                        @php($s = 'Яндекс.Деньги')
                                                    @elseif($live->system == 6)
                                                        Мегафон
                                                        @php($s = 'Мегафон')
                                                    @elseif($live->system == 7)
                                                        Tele2
                                                        @php($s = 'Tele2')
                                                    @elseif($live->system == 8)
                                                        МТС
                                                        @php($s = 'МТС')
                                                    @elseif($live->system == 9)
                                                        Билайн
                                                        @php($s = 'Билайн')
                                                    @endif
                                                    {{$live->wallet}}
                                                </p>
                                            </div>
                                            <div class="kt-widget2__actions">
                                                <a href="#" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="flaticon-more-1"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right" style="">
                                                    @php($message_send_script = "socket.emit('send payout', JSON.stringify({user_id: ".$live->user->id.",username:'".$live->user->username."',avatar:'".$live->user->avatar."',sum:".$live->amount.",pay:'".$s."'}))")
                                                    <ul class="kt-nav">
                                                        <li class="kt-nav__item">
                                                            <a href="javascript:void(0)" onclick="send('#with_{{$live->id}}', '/admin/accept_withdraw/{{$live->id}}', function() { $('#with_{{$live->id}}').fadeOut('fast'); {{$message_send_script}} })" class="kt-nav__link">
                                                                <i class="kt-nav__link-icon flaticon2-check-mark"></i>
                                                                <span class="kt-nav__link-text">Выплатить</span>
                                                            </a>
                                                        </li>
                                                        <li class="kt-nav__item">
                                                            <a href="javascript:void(0)" onclick="send('#with_{{$live->id}}', '/admin/decline_withdraw/{{$live->id}}', function() { $('#with_{{$live->id}}').fadeOut('fast'); })" class="kt-nav__link">
                                                                <i class="kt-nav__link-icon flaticon2-cross"></i>
                                                                <span class="kt-nav__link-text">Отказать</span>
                                                            </a>
                                                        </li>
                                                        <li class="kt-nav__item">
                                                            <a href="javascript:void(0)" onclick="send('#with_{{$live->id}}', '/admin/ignore_withdraw/{{$live->id}}', function() { $('#with_{{$live->id}}').fadeOut('fast'); })" class="kt-nav__link">
                                                                <i class="kt-nav__link-icon flaticon-delete"></i>
                                                                <span class="kt-nav__link-text">Игнорировать</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane" id="kt_widget4_tab2_content">
                                <div class="kt-widget4">
                                    @php
                                        $opens = \DB::table('payments')->orderBy('id', 'desc')->where('status', 1)->limit(9)->get();
                                        foreach ($opens as $live) $live->user = \App\User::where('id', $live->user)->first();
                                    @endphp
                                    @if(sizeof($opens) == 0)
                                        <div class="empty_block">
                                            <i class="fas fa-clock"></i>
                                            <div>Здесь ничего нет</div>
                                        </div>
                                    @endif
                                    @foreach($opens as $live)
                                        <div id="with_{{$live->id}}" class="kt-widget4__item">
                                            <div class="kt-widget4__pic kt-widget4__pic--pic">
                                                <img src="{{$live->user->avatar}}" alt="">
                                            </div>
                                            <div class="kt-widget4__info">
                                                <a href="javascript:void(0)" onclick="load('admin/user?id={{$live->user->id}}')" class="kt-widget4__username">
                                                    {{$live->user->username}}
                                                </a>
                                                <p class="kt-widget4__text">
                                                    +{{$live->amount}} руб.
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="/_admin/js/pages/dashboard.js?v={{$version}}" type="text/javascript"></script>