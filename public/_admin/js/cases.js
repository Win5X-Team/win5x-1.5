var cid;

$(document).ready(function() {
    addToolbar('<div class="kt-subheader__group">' +
        '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#new">Создать</button>' +
        '</div>');

    $('#create').on('click', function() {
        send('#new', '/admin/case/create'
                +'/'+$('#name').val()
                +'/'+$('#price').val(), function() {
            window.location.reload();
        });
    });

    ///case/add/{id}/{type}/{value}/{chance}/{rarity}
    $('#i_create').on('click', function() {
        send('#new', '/admin/case/add'
            +'/'+cid
            +'/'+$('#type').val()
            +'/'+$('#value').val()
            +'/'+$('#chance').val()
            +'/'+$('#rarity').val(), function() {
            window.location.reload();
        });
    });
});