var page = 0, c = null;

function loadNextPage(id) {
    page += 1;

    $.get('/api/user_drops/' + id + '/' + page, function(data) {
        let json = JSON.parse(data);

        if(json.length === 0) $('#lm_b').hide();
        for(let i = 0; i < json.length; i++) {
            let drop = json[i];
            let e = $('<tr class="live_table-game">' +
                '<th>' +
                '<div class="ll_icon hidden-xs" onclick="load(\'' + drop.name.toLowerCase() + '\')">' +
                '<i class="' + drop.icon + '"></i>' +
                '</div>' +
                '<div class="ll_game">' +
                '<span onclick="load(\'' + drop.name.toLowerCase() + '\')">' + drop.name + '</span>' +
                '<p onclick="user_game_info(' + drop.id + ')">Просмотр</p>' +
                '</div>' +
                '</th>' +
                '<th class="hidden-xs">' + (drop.time == null ? '' : drop.time) + '</th>' +
                '<th class="hidden-xs">' + drop.bet + ' руб.</th>' +
                '<th class="hidden-xs">' + (drop.user_id > 0 ? ('x' + drop.mul) : '') + '</th>' +
                '<th>' + drop.amount + ' руб.</th>' +
                '</tr>');
            $('#user_drops tbody').append(e.fadeIn('fast').css("display", "table-row"));
        }
    })
}

function setTab(sel, forced) {
    if(sel === c && (forced === undefined || forced === false)) return;
    c = sel;

    $('*[data-tab]').toggleClass("user-info-tab-active", false);
    $('*[data-tab='+sel+']').toggleClass("user-info-tab-active", true);

    $('.user_tab').hide();
    $('#'+sel).fadeIn('fast');

    if(sel === 'achievements') {
        $('#achievements_content').html('');
        $(".nano").nanoScroller();
        loadAchievements('all');
    }
}

function cancelWithdraw(id) {
    $.get('/withdraw/cancel/'+id, function(response) {
        let status = parseInt(response);
        if(status === -1) {
            iziToast.error({
                icon: 'fal fa-times',
                message: 'Произошла серверная ошибка. Свяжитесь с службой поддержки.',
                position: 'bottomCenter'
            });
            return;
        }

        updateBalance();
        iziToast.success({
            icon: 'fal fa-check',
            message: 'Вы успешно отменили выплату.',
            position: 'bottomCenter'
        });
        load(currentPage, function() {
            setTab('out');
        });
    });
}

function filter(medal) {
    if(medal === 'all') {
        $('.achievement-block').show();
        return;
    }

    $('.achievement-block:not([data-medal="'+medal+'"])').hide();
    $('.achievement-block[data-medal="'+medal+'"]').show();
}

function loadAchievements(category) {
    $('#achievements_content').fadeOut('fast');
    $('#load').fadeIn('fast');
    $.get('/achievements/'+$.urlParam('id')+'/'+category, function(response) {
        let json = JSON.parse(response);
        console.log(json);

        test = json;
        json = json.sort(function(a, b) {
            return a.unlock === false ? 0 : -1;
        });

        $('#achievements_content').html('');
        for(let i = 0; i < json.length; i++) {
            let achievement = json[i];
            $('#achievements_content').append(`
                <div onclick="$(this).find('.achievement-info').find('.achievement-more').slideToggle('fast')" data-medal="`+achievement.badge+`" class="achievement-block `+(achievement.unlock === false ? '' : 'achievement-unlocked')+` `+(achievement.reward === null ? '' : 'with-reward')+`">
                    <div class="achievement-icon">
                        <i class="fad fa-award `+achievement.badge+`"></i>
                        `+(achievement.unlock === false ? '' : '<div class="achievement-date">'+achievement.unlock+'</div>')+`
                    </div>
                    <div class="achievement-info">
                        <div class="achievement-title">`+achievement.name+`</div>
                        <div class="achievement-desc">`+achievement.description+`</div>
                        <div class="achievement-more">
                            <span>`+achievement.progress.current+`/`+achievement.progress.max+`</span>
                            <div class="ach-progress-bar">
                                <div style="width: `+achievement.progress.percent+`%"></div>
                            </div>
                        </div>
                    </div>
                    `+(achievement.reward === null ? '' : `<div class="achievement-reward">
                        Награда: `+achievement.reward+`
                    </div>`)+`
                </div>
            `);
        }
        $('#load').fadeOut('fast', function() {
            $('#achievements_content').fadeIn('fast');
        });
    });
}

$(window).scroll(function() {
    if($('#user_drops').length > 0) {
        if ($(window).scrollTop() > ($('#user_drops').offset().top + ($('#user_drops').outerHeight() / 2)) && c === 'history')
            loadNextPage($.urlParam('id'));
    }
});

setTab('history', true);

$(document).ready(function() {
    setTab('history', true);

    $.get('/get_refs', function(response) {
        let json = JSON.parse(response);

        if(json.length === 0) $('#ref_content').html('<span style="color: lightgrey">Вы еще никого не пригласили.</span>');
        else {
            $('#ref_content').html(`
                <table class="live_table">
                    <thead>
                         <tr class="live_table-header">
                            <th style="width: 80px;">Имя</th>
                            <th>Активность <i class="fas fa-question-circle fqc tooltip" title="Активным рефералом считается тот, у кого общая сумма выигрыша всех игр достигла 5 руб."></i></th>
                         </tr>
                    </thead>
                    <tbody id="ref_body">
                    </tbody>
                </table>`);
            for(let i = 0; i < json.length; i++) {
                let u = json[i];
                $('#ref_body').append(`
                    <tr class="live_table-game">
                        <th>
                            <div class="ll_game">
                                <span onclick="load('user?id=`+u['user']+`')">`+u['username']+`</span>
                            </div>
                        </th>
                        <th>
                            `+(u['active'] ? 'Да' : 'Нет')+`
                        </th>
                    </tr>
                `);
            }
        }
    });

    window.scrollTo({ top: 0, behavior: 'smooth' });

    $('.ach-menu-element:not(.ach-submenu)').on('click', function() {
        if($(this).hasClass('ach-menu-active')) return;
        $('.ach-menu-element').removeClass('ach-menu-active');
        $(this).addClass('ach-menu-active');

        $('.ach-submenu div').slideUp('fast');
        $('#'+$(this).attr('data-submenu')+' div').slideDown('fast');
    });

    $('#avatar-form').submit(function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        var formData = new FormData($(this)[0]);

        $.ajax({
            type: "POST",
            url: '/profile/upload_avatar',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                var json = JSON.parse(data);

                if(json.error != null) {
                    if(json.error === -1) iziToast.error({message: 'Требуется авторизация.', icon: 'fa fa-times', position: 'bottomCenter'});
                    if(json.error === 1) iziToast.error({message: 'Выберите изображение.', icon: 'fa fa-times', position: 'bottomCenter'});
                    if(json.error === 2) iziToast.error({message: 'Максимальный размер изображения - 3 мб.<br>Допустимые форматы: jpeg, png', icon: 'fa fa-times', position: 'bottomCenter'});
                    return;
                }

                window.location.reload();
            }
        });

        return false;
    });
});