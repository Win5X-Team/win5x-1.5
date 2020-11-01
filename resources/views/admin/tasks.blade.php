<div id="__ajax_title" style="display: none">Задания</div>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid row">
    @foreach(\App\Task::get() as $task)
        @php($game = json_decode(file_get_contents(url('/').'/game_info/'.$task->game_id)))
        <div class="col-xl-6">
            <div class="kt-portlet kt-portlet--height-fluid" id="task{{$task->id}}">
                <div class="kt-portlet__body kt-portlet__body--fit">
                    <div class="kt-widget kt-widget--project-1">
                        <div class="kt-widget__head">
                            <div class="kt-widget__label">
                                <div class="kt-widget__media">
                                    <span class="kt-media kt-media--lg kt-media--circle task-ico">
                                        <i class="{{$game->icon}}"></i>
                                    </span>
                                </div>
                                <div class="kt-widget__info kt-margin-t-5">
                                    <a href="#" class="kt-widget__title">
                                        {{$game->name}}
                                    </a>
                                    <span class="kt-widget__desc">
                                        {{file_get_contents(url('/').'/task/description/'.$task->id)}}
                                    </span>
                                </div>
                            </div>
                            <div class="kt-portlet__head-toolbar">
                                <a href="#" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                    <i class="flaticon-more-1"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                    <ul class="kt-nav">
                                        <li class="kt-nav__item">
                                            <a href="javascript:void(0)" onclick="send('#task{{$task->id}}', '/admin/task/remove/{{$task->id}}', function() {window.location.reload()})" class="kt-nav__link">
                                                <i class="kt-nav__link-icon flaticon2-trash"></i>
                                                <span class="kt-nav__link-text">Удалить</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="kt-widget__body">
                            <div class="kt-widget__stats">
                                <div class="kt-widget__item">
                                <span class="kt-widget__date">
                                    Начало
                                </span>
                                    <div class="kt-widget__label">
                                        <span class="btn btn-label-brand btn-sm btn-bold btn-upper">
                                            {{date('d.m.Y', $task->start_time)}}
                                        </span>
                                    </div>
                                </div>
                                <div class="kt-widget__item">
                                <span class="kt-widget__date">
                                    Завершение
                                </span>
                                    <div class="kt-widget__label">
                                        <span class="btn btn-label-danger btn-sm btn-bold btn-upper">
                                            {{date('d.m.Y', $task->end_time)}}
                                        </span>
                                    </div>
                                </div>

                                <div class="kt-widget__item flex-fill">
                                    <span class="kt-widget__subtitel">До завершения</span>
                                    @php
                                        $begin = $task->start_time;
                                        $now = time();
                                        $end = $task->end_time;
                                        $percent = number_format((float) ($now-$begin) / ($end-$begin) * 100, 2, '.', '');
                                    @endphp
                                    <div class="kt-widget__progress d-flex  align-items-center">
                                        <div class="progress" style="height: 5px;width: 100%;">
                                            <div class="progress-bar kt-bg-warning" role="progressbar" style="width: {{$percent}}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="kt-widget__stat">
                                        {{$percent}}%
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-widget__content">
                                <div class="kt-widget__details">
                                    <span class="kt-widget__subtitle">Награда</span>
                                    <span class="kt-widget__value">{{$task->reward}} <span>руб.</span></span>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__subtitle">Цена за 1 попытку</span>
                                    <span class="kt-widget__value">{{$task->price}} <span>руб.</span></span>
                                </div>
                                @php($users = \App\User::whereRaw('JSON_CONTAINS(`tasks_completed`, \''.$task->id.'\', \'$\')')->get())
                                @if(sizeof($users) > 0)
                                    <div class="kt-widget__details">
                                        <span class="kt-widget__subtitle">Выполнили</span>
                                        <div class="kt-media-group">
                                            @php($i = 0)
                                            @foreach($users as $key => $value)
                                                <a href="javascript:void(0)" onclick="load('admin/user?id={{$value->id}}')" class="kt-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="{{$value->username}}">
                                                    <img src="{{$value->avatar}}" alt="image">
                                                </a>
                                                @if($i > 5)
                                                    <a href="javascript:void(0)" class="kt-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title=""
                                                       data-original-title="Выполнений: {{sizeof($users)}}">
                                                        <span>+{{sizeof($users) - 7}}</span>
                                                    </a>
                                                    @break
                                                @endif
                                                @php($i += 1)
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
</div>
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Задание</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-control-label">Начало:</label>
                    <div class="input-group date">
                        <input type="text" class="form-control" id="start" placeholder="Начало" readonly="">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar glyphicon-th"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Завершение:</label>
                    <div class="input-group date">
                        <input type="text" class="form-control" id="end" placeholder="Завершение" readonly="">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar glyphicon-th"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Цена за 1 попытку:</label>
                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                        <input id="price" type="text" class="form-control" value="0.00" placeholder="Цена за 1 попытку">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Награда:</label>
                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                        <input id="reward" type="text" class="form-control" value="0.00" placeholder="Награда">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Игра:</label>
                    <div class="dropdown bootstrap-select form-control kt-">
                        <select id="sel" class="form-control kt-selectpicker" tabindex="-98" data-live-search="true">
                            <option data-index="0" data-game-id="3">Crash</option>
                            <option data-index="1" data-game-id="4">Coinflip</option>
                            <option data-index="2" data-game-id="9">Tower</option>
                            <option data-index="3" data-game-id="5">Mines</option>
                            <option data-index="4" data-game-id="1">Dice</option>
                            <option data-index="5" data-game-id="7">HiLo</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Значение:</label>
                    <input type="text" class="form-control" id="value">
                    <span class="form-text text-muted" id="desc"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="create">Создать</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/_admin/js/tasks.js?v={{$version}}"></script>