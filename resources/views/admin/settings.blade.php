<div id="__ajax_title" style="display: none">Настройки</div>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    @if(Auth::user()->chat_role < 3)
        Вам недоступна эта информация
    @else
        <div class="row">
            <div class="col-lg-4">
                <div class="kt-portlet" id="paysys">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Платежная система <small>freekassa</small>
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="form-group">
                                <label>ID:</label>
                                <input id="paysys_id" value="{{$settings->ap_id}}" type="text" class="form-control" placeholder="ID"
                                    oninput="delayedv('#paysys_id', function(v) { send('#paysys', '/admin/setting/ap_id/'+v) })">
                            </div>
                            <div class="form-group">
                                <label>Секретное слово #1:</label>
                                <input id="paysys_secret" value="{{$settings->ap_secret}}" type="text" class="form-control" placeholder="Секретный ключ"
                                    oninput="delayedv('#paysys_secret', function(v) { send('#paysys', '/admin/setting/ap_secret/'+v) })">
                            </div>
                            <div class="form-group">
                                <label>Секретное слово #2:</label>
                                <input id="paysys_api" value="{{$settings->ap_api_key}}" type="text" class="form-control" placeholder="API ключ"
                                   oninput="delayedv('#paysys_api', function(v) { send('#paysys', '/admin/setting/ap_api_key/'+v) })">
                            </div>
                            <div class="form-group">
                                <label>Минимальная сумма для пополнения:</label>
                                <input id="paysys_minin" value="{{$settings->min_in}}" type="text" class="form-control" placeholder="Минимальная сумма для пополнения"
                                       oninput="delayedv('#paysys_minin', function(v) { send('#paysys', '/admin/setting/min_in/'+v) })">
                            </div>
                            <div class="form-group">
                                <label>Минимальная сумма для вывода:</label>
                                <input id="paysys_minwith" value="{{$settings->min_with}}" type="text" class="form-control" placeholder="Минимальная сумма для вывода"
                                    oninput="delayedv('#paysys_minwith', function(v) { send('#paysys', '/admin/setting/min_with/'+v) })">
                            </div>
                            <div class="form-group">
                                <label class="kt-checkbox">
                                    <input id="paysys_enabled" @if($settings['payment_disabled'] == 1) checked @endif type="checkbox"
                                           onclick="send('#paysys', '/admin/setting/payment_disabled/'+($('#paysys_enabled').is(':checked') ? '1' : '0'))"> Отключить принятие платежей от пользователей
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="kt-portlet" id="ref">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Партнерская программа
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="form-group">
                                <label>Сумма за активного реферала/регистрацию по реферальной ссылке:</label>
                                <input id="ref_sum" type="text" value="{{$settings->promo_sum}}" class="form-control" placeholder="Сумма за реферальный код"
                                    oninput="delayedv('#ref_sum', function(v) { send('#ref', '/admin/setting/promo_sum/'+v) })">
                            </div>
                            <div class="form-group">
                                <label>Сумма за временный промокод:</label>
                                <input id="temp_sum" type="text" value="{{$settings->temp_promo_sum}}" class="form-control" placeholder="Сумма за реферальный код"
                                    oninput="delayedv('#temp_sum', function(v) { send('#ref', '/admin/setting/temp_promo_sum/'+v) })">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="kt-portlet" id="warn">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Настройки
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <h5>Подкрутка</h5>
                            <div class="form-group">
                                <label>Подкрутить, если достигнут % от ставки</label>
                                <input id="max_bet_increase" value="{{$settings->max_bet_increase}}" type="text" class="form-control" placeholder="Максимальный % от ставки"
                                       oninput="delayedv('#max_bet_increase', function(v) { send('#warn', '/admin/setting/max_bet_increase/'+v) })">
                            </div>
                            <h5>ВКонтакте</h5>
                            <div class="form-group">
                                <label>Сообщения - секретный ключ <strong>группы</strong></label>
                                <input id="messages_secret" value="{{$settings->messages_secret}}" type="text" class="form-control" placeholder="Секретный ключ"
                                       oninput="delayedv('#messages_secret', function(v) { send('#warn', '/admin/setting/messages_secret/'+v) })">
                            </div>
                            <div class="form-group">
                                <label>Информация - сервисный ключ <strong>приложения</strong></label>
                                <input id="service" value="{{$settings->vk_service}}" type="text" class="form-control" placeholder="Сервисный ключ"
                                       oninput="delayedv('#service', function(v) { send('#warn', '/admin/setting/vk_service/'+v) })">
                            </div>
                            <h5>Уведомление на главной странице</h5>
                            <div class="form-group">
                                <label>Заголовок:</label>
                                <input id="warn_title" value="{{$settings['warn_title']}}" type="text" class="form-control" placeholder="Заголовок"
                                    oninput="delayedv('#warn_title', function(v) { send('#warn', '/admin/setting/warn_title/'+v) })">
                            </div>
                            <div class="form-group">
                                <label>Текст:</label>
                                <input id="warn_text" value="{{$settings['warn_text']}}" type="text" class="form-control" placeholder="Текст"
                                    oninput="delayedv('#warn_text', function(v) { send('#warn', '/admin/setting/warn_text/'+v) })">
                            </div>
                            <div class="form-group">
                                <label class="kt-checkbox">
                                    <input id="warn_enabled" @if($settings['warn_enabled'] == 1) checked @endif type="checkbox"
                                        onclick="send('#warn', '/admin/setting/warn_enabled/'+($('#warn_enabled').is(':checked') ? '1' : '0'))"> Включить
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>