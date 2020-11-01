$(document).ready(function() {
    addToolbar(`<div class="kt-subheader__group">
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#send">Отправить браузерное уведомление</button>
    </div></div>`);

    $('#fa').on('click', function() {
        send('#send', '/admin/notification/browser/'
                + $('#text').val().replaceAll(' ', '[SPACE]').replaceAll('\n', '[LINEBREAK]'), function() {
            $('#ca').click();
            iziToast.success({
                'icon': 'fal fa-check',
                'message': 'Уведомление успешно отправлено',
                'position': 'bottomCenter'
            });
        });
    });
});