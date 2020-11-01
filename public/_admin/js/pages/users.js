var p = 0;
function admin_users_load_page(page, callback) {
    send('#users_container', '/admin/users/'+page, function(response) {
        if(callback !== undefined) callback();
        users_load(JSON.parse(response));
    });
}

function users_load(json) {
    for(let i = 0; i < json.length; i++) {
        let user = json[i];
        let e = $('<tr class="kt-datatable__row" style="left: 0px;" data-username="'+user.username+'">' +
            '<td class="kt-datatable__cell--center kt-datatable__cell kt-datatable__cell--check">' +
            '<span style="width: 50px; font-family: \'Open Sans\', sans-serif;">' +
            user.id +
            '</span>' +
            '</td>' +
            '<td class="kt-datatable__cell">' +
            '<span style="width: 200px;">' +
            '<div class="kt-user-card-v2">' +
            '<div class="kt-user-card-v2__pic">' +
            '<img alt="photo" src="'+user.avatar+'">' +
            '</div>' +
            '<div class="kt-user-card-v2__details ml-2">' +
            '<a class="kt-user-card-v2__name" href="javascript:void(0)" onclick="load(\'admin/user?id='+user.id+'\')">'+user.username+'</a>' +
            '<span class="kt-user-card-v2__desc">' +
           (user.chat_role === 2 ? 'Модератор' : (user.chat_role === 3 ? 'Администратор' : (user.chat_role === 1 ? 'YouTube' : 'Пользователь'))) +
            '</span>' +
            '</div>' +
            '</div>' +
            '</span>' +
            '</td>' +
            '<td class="kt-datatable__cell"><span style="width: 181px; font-family: \'Open Sans\', sans-serif;">'+(parseFloat(user.money).toFixed(2))+' руб.</span></td>' +
            '<td data-autohide-disabled="false" class="kt-datatable__cell">' +
            '<span style="overflow: visible; position: relative; width: 80px;">' +
            '<div class="dropdown">' +
            '<a href="javascript:void(0)" onclick="load(\'admin/user?id='+user.id+'\')" class="btn btn-sm btn-clean btn-icon btn-icon-md">' +
            '<i class="flaticon-more-1"></i>' +
            '</a>' +
            '</div>' +
            '</span>' +
            '</td>' +
            '</tr>').hide();
        $('#users').append(e);
        e.fadeIn('fast');
    }
}

function quick_load(page) {
    if(page === -1) p = -1;
    $("html, body").animate({ scrollTop: page >= 0 ? 0 : $(document).height() }, "slow");
}

function page(page) {
    if(isNaN(page)) return;
    p = parseInt(page);
    $('#users').fadeOut('fast', function() {
        $('#users').html('');
        admin_users_load_page(p);
        $('#users').fadeIn('fast');
    });
}

function search_load(json) {
    p = 0;
    $('#users').fadeOut('fast', function() {
        $('#users').html('').fadeIn('fast');
        users_load(json);
    });
}

$(document).ready(function() {
    addAction(
    '<div class="kt-subheader__group" id="kt_subheader_search">' +
            '<div class="kt-input-icon kt-input-icon--right kt-subheader__search">' +
                '<input oninput="delayedv(\'#generalSearch\', function(v) { send(\'#kt_subheader_search\', \'/admin/search/user/\'+$(\'#generalSearch\').val(), function(response) { search_load(JSON.parse(response)) }) })" type="text" class="form-control" placeholder="Найти..." id="generalSearch">' +
                '<span class="kt-input-icon__icon kt-input-icon__icon--right">' +
                    '<span>' +
                        '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">' +
                            '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">' +
                                '<rect x="0" y="0" width="24" height="24"></rect>' +
                                '<path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>' +
                                '<path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"></path>' +
                            '</g>' +
                        '</svg>' +
                    '</span>' +
                '</span>' +
            '</div>' +
        '</div>');

    addToolbar(
    '<div class="kt-subheader__group" id="kt_subheader_search">' +
            '<div class="kt-subheader__search" style="width: 85px">' +
                '<input oninput="page($(this).val())" type="text" class="form-control" placeholder="Страница">' +
            '</div>' +
            '<button onclick="quick_load(0)" type="button" class="btn btn-brand btn-icon ml-2"><i class="fal fa-chevron-double-up"></i></button>' +
            '<button onclick="quick_load(-1)" type="button" class="btn btn-success btn-icon"><i class="fal fa-chevron-double-down"></i></button>' +
        '</div>');

    admin_users_load_page(p, function() {
        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                if(p > -1) p += 1;
                if(p !== -2) {
                    admin_users_load_page(p);
                    if(p === -1) p = -2;
                }
            }
        });
    });
});