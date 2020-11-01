var socket;

$.urlParam = function(name) {
    let results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) return null;
    return decodeURI(results[1]) || 0;
};

String.prototype.replaceAll = function(search, replace){
    return this.split(search).join(replace);
};

$(document).ready(function() {
    let ajaxTitle = function() {
        $('#ajax_title').html($('#__ajax_title').html());
    };
    registerAjaxCallback(function(page) {
        if(page !== 'admin/'
            && page !== 'admin/game_stats'
            && page !== 'admin/user_stats'
            && page !== 'admin/notification_stats') $('.kt-menu__item--open').toggleClass('kt-menu__item--open', false);
        return true;
    }, function(page) {
        $('*[data-option]').toggleClass('kt-menu__item--active', false);
        $('*[data-option="'+page+'"]').toggleClass('kt-menu__item--active', true);
        ajaxTitle();

        $('#ajax_action').fadeOut('fast', function() {
            $('#ajax_action').html('');
        });
        $('#ajax_toolbar').fadeOut('fast', function() {
            $('#ajax_toolbar').html('');
        });
    });
    ajaxTitle();

    $('*[data-option="'+document.location.pathname.substr(1)+'"]').toggleClass('kt-menu__item--active', true);

    let secure = window.location.protocol === 'https:';
    socket = io('w'+(secure ? 'ss' : 's')+'://'+window.location.hostname+':2096', {transports: ['websocket'], secure: secure, rejectUnauthorized: false});
});

function addAction(html) {
    setTimeout(function() {
        $('#ajax_action').html(html).fadeIn('fast');
    }, 250);
}

function addToolbar(html) {
    setTimeout(function() {
        $('#ajax_toolbar').html(html).fadeIn('fast');
    }, 250);
}

function send(e, url, callback) {
    KTApp.block(e,{overlayColor:"#000000",type:"v2",state:"success",message:"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..."});
    $.get(url, function(response) {
        KTApp.unblock(e);
        console.log(response);
        if(callback !== undefined) callback(response);
    }).fail(function(jqxhr, settings, exception) {
        console.log(exception);
        KTApp.unblock(e);
        iziToast.error({
            message: 'Не удалось отправить данные.',
            icon: 'fal fa-times',
            position: 'bottomCenter'
        });
    });
}

function delayedv(element, callback) {
    let v = $(element).val();
    setTimeout(function() {
        if(v === $(element).val() && v.toString().length > 0) callback($(element).val());
    }, 1000);
}