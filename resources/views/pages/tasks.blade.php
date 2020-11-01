@if(Auth::guest()) <script type="text/javascript">load('games');</script> @else
    <div class="game__wrapper tasks_page">
        <div class="bonus_header">
            <p>Задания <i class="fas fa-info-circle fn_btn_info" onclick="info('tasks')"></i></p>
            <span>Выполняй еженедельные задания и заработай деньги!</span>
        </div>

        @php($tasks = \App\Task::where('start_time', '<=', time())->where('end_time', '>=', time())->get())
        @foreach($tasks as $key => $task)
            @php($u = \App\User::whereRaw('JSON_CONTAINS(`tasks_completed`, \''.$task->id.'\', \'$\')')->where('id', Auth::user()->id)->count())
            @if($u != 0) @unset($tasks[$key]) @endif
        @endforeach

        @if(sizeof($tasks) == 0)
            <div class="tasks_empty_wrapper">
                <div class="tasks_empty-header">Сейчас нет заданий</div>
                <div class="tasks_empty-text">Загляните сюда позже - они обязательно появятся!</div>
            </div>
        @else
            <div class="tasks_wrapper">
            @foreach($tasks as $key => $task)
                @php($game = json_decode(file_get_contents(url('/').'/game_info/'.$task->game_id)))
                <div class="task_content">
                    <div class="task">
                        <div class="task-header">
                            <i class="{{$game->icon}}"></i>
                            <div class="task-header-content">
                                <div class="task-header-title">
                                    <a href="/{{strtolower($game->name)}}" target="_blank">{{$game->name}}</a>
                                </div>
                                <div class="task-header-text">
                                    {{file_get_contents(url('/').'/task/description/'.$task->id)}}
                                </div>
                            </div>
                        </div>
                        <div class="task-content hidden-xs">
                            <div class="task-footer-item task-progress-item">
                                <div>Начало</div>
                                <div>{{date('d.m.Y', $task->start_time)}}</div>
                            </div>
                            <div class="task-footer-item task-progress-item">
                                <div>Завершение</div>
                                <div>{{date('d.m.Y', $task->end_time)}}</div>
                            </div>
                            <div class="task-progress-container">
                                <div class="task-progress">
                                    @php
                                        $begin = $task->start_time;
                                        $now = time();
                                        $end = $task->end_time;
                                        $percent = number_format((float) ($now-$begin) / ($end-$begin) * 100, 2, '.', '');
                                    @endphp
                                    <div style="width: {{$percent}}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="task-footer">
                            <div class="task-footer-item">
                                <div class="task-footer-item-header">Награда</div>
                                <div>{{$task->reward}} руб.</div>
                            </div>
                            <div class="task-footer-item hidden-xs">
                                <div class="task-footer-item-header">Цена за 1 попытку</div>
                                <div>{{$task->price}} руб.</div>
                            </div>
                            @php($users = \App\User::whereRaw('JSON_CONTAINS(`tasks_completed`, \''.$task->id.'\', \'$\')')->get())
                            @if(sizeof($users) > 0)
                                <div class="task-footer-item hidden-xs">
                                    <div class="task-footer-item-header">Выполнили</div>
                                    <div>
                                        <div class="avatar-group">
                                            @php($i = 0)
                                            @foreach($users as $key => $value)
                                                <a href="javascript:void(0)" onclick="load('user?id={{$value->id}}')" class="avatar-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="{{$value->username}}">
                                                    <img src="{{$value->avatar}}" alt="image">
                                                </a>
                                                @if($i > 5)
                                                    <a href="javascript:void(0)" class="avatar-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title=""
                                                       data-original-title="Выполнений: {{sizeof($users)}}">
                                                        <span>+{{sizeof($users) - 7}}</span>
                                                    </a>
                                                    @break
                                                @endif
                                                @php($i += 1)
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @php($any_tries = file_get_contents(url('/').'/task/has/'.Auth::user()->id.'/'.$task->id) == '1')
                        <div class="task-purchase-btn"
                             onclick="@if($any_tries) load('{{strtolower($game->name)}}'); @else task({{$task->id}}) @endif">
                            @if($any_tries) Играть @else Начать задание @endif
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        @endif
    </div>
@endif