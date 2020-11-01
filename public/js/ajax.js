var _ajaxPreCallback = null, _ajaxPostCallback = null, currentPage = (location.pathname+location.search).substr(1);

function registerAjaxCallback(pre, post) {
    _ajaxPreCallback = pre;
    _ajaxPostCallback = post;
}

function load(page, callback, silent) {
    if($('*[data-disable-ajax-loading="'+page+'"]').length > 0) return;

    if(page === undefined) page = 'main';
    if(silent === undefined) silent = false;
    currentPage = page;

    if(_ajaxPreCallback != null) if(!_ajaxPreCallback(page)) return;

    let _load = function() {
        $.get("/" + page + (page.includes('?') ? '&' : '?') + 'json', function(data) {
            if(!silent) window.scrollTo({ top: 0, behavior: 'smooth' });

            $('#_ajax_content_').html(data);
            window.history.pushState({"html":data,"pageTitle":$(document).find("title").text()}, $(document).find("title").text(), "/"+page);

            if(_ajaxPostCallback != null) _ajaxPostCallback(page);
            if(callback !== undefined) callback();
            if(!silent) $('.loader').fadeOut('fast');
        }).fail(function(jqxhr, settings, exception) {
            console.log(exception);
            $('.loader').fadeOut('fast');
            iziToast.error({
                message: 'Ошибка 404: Не удалось найти данную страницу.',
                icon: 'fa fa-times'
            });
        });
    };
    if(silent) _load();
    else $('.loader').fadeIn('fast', function() {
        _load();
    });
}

function link() {
    $("a, div, img, span, p, small, strong").on('mousedown', function(e) {
        if(e.which === 2) {
            if($(this).attr('onclick') == null || !$(this).attr('onclick').replace(/\s/g, '').startsWith("load('")) return;
            const link = $(this).attr('onclick').replace(/\s/g, '').substring("load('".length, $(this).attr('onclick').replace(/\s/g, '').lastIndexOf("'"));
            window.open('/' + link, '_blank');
        }
    });
}

$(document).ready(function() {
    window.onpopstate = function() {
        load(document.location.pathname.substr(1));
    };

    link();
});