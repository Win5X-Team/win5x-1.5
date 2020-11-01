<div id="__ajax_title" style="display: none">Промокоды - Бот</div>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet kt-portlet--mobile" id="users_container">
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="kt-datatable kt-datatable--default kt-datatable--brand kt-datatable--loaded" id="kt_apps_user_list_datatable" style="">
                <table class="kt-datatable__table" style="display: block;">
                    <thead class="kt-datatable__head">
                        <tr class="kt-datatable__row" style="left: 0px;">
                            <th class="kt-datatable__cell kt-datatable__cell--sort"><span style="width: 200px;">Пользователь</span></th>
                         </tr>
                    </thead>
                    <tbody class="kt-datatable__body" id="users"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавить</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-control-label">vk id:</label>
                    <input type="text" class="form-control" id="vkid">
                </div>
                <div class="form-group">
                    <label class="form-control-label">Заметка:</label>
                    <input type="text" class="form-control" value="Без заметки" id="group">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="create" onclick="send('#new', '/admin/promo_list/add/'+$('#vkid').val()+'/'+$('#group').val(), function() { window.location.reload(); })">Добавить</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/_admin/js/pages/promo_list.js?v={{$version}}"></script>