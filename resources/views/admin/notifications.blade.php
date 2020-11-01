<div id="__ajax_title" style="display: none">Уведомления</div>
@if(Auth::user()->chat_role < 3)
    Вам недоступна эта информация
@else
    <div class="modal fade" id="send" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Отправить браузерное уведомление</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label">Сообщение:</label>
                        <textarea class="form-control" id="text"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="ca">Отменить</button>
                    <button type="button" class="btn btn-primary" id="fa">Отправить</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/_admin/js/pages/notifications.js?v={{$version}}"></script>
@endif