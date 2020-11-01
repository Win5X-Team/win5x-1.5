<div id="__ajax_title" style="display: none">Журнал действий</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-grid kt-grid--desktop kt-grid--ver-desktop  kt-inbox" id="kt_inbox">
        <div class="kt-grid__item kt-grid__item--fluid    kt-portlet    kt-inbox__list kt-inbox__list--shown" id="kt_inbox_list">
            <div class="kt-portlet__head">
                <div class="kt-inbox__toolbar kt-inbox__toolbar--extended">
                    <div class="kt-inbox__actions kt-inbox__actions--expanded">
                        <div class="kt-inbox__panel">
                            <button class="kt-inbox__icon" data-toggle="kt-tooltip" title="" data-original-title="Очистить" onclick="send('#e', '/admin/action/clear', function() {window.location.reload()})">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path>
                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>
                                    </g>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body kt-portlet__body--fit-x">
                <div class="kt-inbox__items" data-type="inbox" id="e">
                    @foreach(json_decode(file_get_contents(storage_path().'/actions.json'), true) as $message)
                        @php
                            $user = \App\User::where('id', $message['id'])->first();
                            if(isset($message['data']['id'])) $data_user = \App\User::where('id', $message['data']['id'])->first();

                            $type = 'Неизвестно';
                            $description = '';

                            if($message['type'] == 1) {
                                $type = 'Создание промокода';
                                $description = $message['data']['code']. ', '.$message['data']['usages'].' использований, '.$message['data']['sum'].' руб.';
                            }

                            if($message['type'] == 2) {
                                $type = 'Удаление промокода';
                                $description = $message['data']['code'];
                            }

                            if($message['type'] == 3) {
                                $type = 'Изменение промокода';
                                $description = $message['data']['code']. ', '.$message['data']['usages'].' использований, '.$message['data']['sum'].' руб.
                                    <br>Было: '.$message['data']['prev']['usages'].' использований, '.$message['data']['prev']['sum'].' руб';
                            }

                            if($message['type'] == 4) {
                                $type = 'Блокировка чата';
                                $description = $data_user->username.' (id: '.$data_user->id.')';
                            }

                            if($message['type'] == 5) {
                                $type = ($message['data']['type'] === 'ban' ? 'Блокировка' : 'Разблокировка').' доступа к сайту';
                                $description = $data_user->username.' (id: '.$data_user->id.')';
                            }

                            if($message['type'] == 6) {
                                $type = 'Временная блокировка чата';
                                $description = $data_user->username.' (id: '.$data_user->id.') - '.$message['data']['minutes'].' мин.';
                            }

                            if($message['type'] == 7) {
                                $type = 'Создание группы временных промокодов';
                                $description = $message['data']['num'] . ' шт.';
                            }

                            if($message['type'] == 8) {
                                $type = 'Изменение уровня доступа';
                                $description = $data_user->username.' (было: '.$message['data']['old'].', стало: '.$message['data']['new'].')';
                            }

                            if($message['type'] == 9) $type = 'Техническое обслуживание';
                            if($message['type'] == 10) $type = 'Окончание технического обслуживания';

                            $date = new \DateTime('now', new \DateTimeZone('Etc/GMT-3'));
                            $date->setTimestamp($message['time']);
                            $time = $date->format('d.m.Y H:i:s');
                        @endphp

                        <div class="kt-inbox__item kt-inbox__item--unread" data-type="inbox">
                            <div class="kt-inbox__info">
                                <div class="kt-inbox__actions"></div>
                                <div class="kt-inbox__sender" data-toggle="view">
                                    <span class="kt-media kt-media--sm kt-media--danger" style="background-image: url('{{$user->avatar}}')"><span></span></span>
                                    <a href="#" class="kt-inbox__author">{{$user->username}}</a>
                                </div>
                            </div>
                            <div class="kt-inbox__details" data-toggle="view">
                                <div class="kt-inbox__message">
                                    <span class="kt-inbox__subject">{{$type}} {{strlen($description) > 0 ? '-' : ''}} </span>
                                    <span class="kt-inbox__summary">{{$description}}</span>
                                </div>
                                <!--<div class="kt-inbox__labels">
                                    <span class="kt-inbox__label kt-badge kt-badge--unified-brand kt-badge--bold kt-badge--inline">inbox</span>
                                </div>-->
                            </div>
                            <div class="kt-inbox__datetime" data-toggle="view">
                                {{$time}}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>