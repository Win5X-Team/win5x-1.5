@if(!Auth::guest())
    @php($tasks = \App\Task::where('game_id', $game_id)->get())
    @foreach($tasks as $key => $task)
        @php($u = \App\User::whereRaw('JSON_CONTAINS(`tasks_completed`, \''.$task->id.'\', \'$\')')->where('id', Auth::user()->id)->count())
        @if($u != 0) @unset($tasks[$key]) @endif
    @endforeach

    @if(sizeof($tasks) > 0)
        @php
            $user = \App\User::where('id', Auth::user()->id)->first();
            $arr = json_decode($user->task_tries, true);
            $tries = 0;
            foreach ($arr as $key => $v) {
                if (isset($v[$task->id]) && $v[$task->id] != null) {
                    if ($v[$task->id] > 0) {
                        $tries = $v[$task->id];
                        break;
                    }
                }
            }
        @endphp

        <div class="col-xs-12">
            <div class="game-task-container" @if($tries > 0) data-task-id="{{$task->id}}" @endif>
                @if($tries == 0)
                    <p>Доступно задание<br><small>Для участия в задании необходимо приобрести попытки</small></p>
                    <a href="javascript:void(0)" class="ll" onclick="load('tasks')">Перейти на страницу</a>
                @else
                    <p>
                        Задание:<br>
                        <small>{{file_get_contents(url('/').'/task/description/'.$task->id)}}</small>
                    </p>
                    @php
                        function declOfNum($num, $titles) {
                            $cases = array(2, 0, 1, 1, 1, 2);
                            return $num . " " . $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
                        }
                        $t = declOfNum($tries, array('попытка', 'попытки', 'попыток'));
                    @endphp
                    <a>{{$t}}</a>
                @endif
            </div>
        </div>
    @endif
@endif