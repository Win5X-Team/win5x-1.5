$(document).ready(function() {
    addToolbar(`
        <div class="kt-subheader__group">
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#new_group">Создать временную группу</button>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#new">Создать</button>
        </div>
    `);

    $('#create').on('click', function() {
        send('#new', '/admin/promo/create/'+$('#code').val()+'/'+$('#usages').val()+'/'+$('#sum').val(), function() {
            window.location.reload();
        });
    });
    $('#edit').on('click', function() {
        send('#pedit', '/admin/promo/edit/'+$('#edit_id').val()+'/'+$('#edit_usages').val()+'/'+$('#edit_sum').val(), function() {
            window.location.reload();
        });
    });
    $('#g_create').on('click', function() {
        send('#new_group', '/admin/promo/group/create/'+$('#g_num').val(), function() {
            window.location.reload();
        });
    });
    $('#g_edit').on('click', function() {
        send('#gedit', '/admin/promo/group/edit/'+$('#g_edit_id').val()+'/'+$('#g_edit_name').val(), function() {
            window.location.reload();
        });
    });
});