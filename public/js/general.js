var isDemo = false, isQuick = false;
var socket = null;
var watcherInstance = null, sBets = [];

/* javascript-obfuscator:disable */

var deck = {
    1: {type: 'spades', value: 'A', slot: 1},
    2: {type: 'spades', value: '2', slot: 2},
    3: {type: 'spades', value: '3', slot: 3},
    4: {type: 'spades', value: '4', slot: 4},
    5: {type: 'spades', value: '5', slot: 5},
    6: {type: 'spades', value: '6', slot: 6},
    7: {type: 'spades', value: '7', slot: 7},
    8: {type: 'spades', value: '8', slot: 8},
    9: {type: 'spades', value: '9', slot: 9},
    10: {type: 'spades', value: '10', slot: 10},
    11: {type: 'spades', value: 'J', slot: 11},
    12: {type: 'spades', value: 'Q', slot: 12},
    13: {type: 'spades', value: 'K', slot: 13},
    14: {type: 'hearts', value: 'A', slot: 1},
    15: {type: 'hearts', value: '2', slot: 2},
    16: {type: 'hearts', value: '3', slot: 3},
    17: {type: 'hearts', value: '4', slot: 4},
    18: {type: 'hearts', value: '5', slot: 5},
    19: {type: 'hearts', value: '6', slot: 6},
    20: {type: 'hearts', value: '7', slot: 7},
    21: {type: 'hearts', value: '8', slot: 8},
    22: {type: 'hearts', value: '9', slot: 9},
    23: {type: 'hearts', value: '10', slot: 10},
    24: {type: 'hearts', value: 'J', slot: 11},
    25: {type: 'hearts', value: 'Q', slot: 12},
    26: {type: 'hearts', value: 'K', slot: 13},
    27: {type: 'clubs', value: 'A', slot: 1},
    28: {type: 'clubs', value: '2', slot: 2},
    29: {type: 'clubs', value: '3', slot: 3},
    30: {type: 'clubs', value: '4', slot: 4},
    31: {type: 'clubs', value: '5', slot: 5},
    32: {type: 'clubs', value: '6', slot: 6},
    33: {type: 'clubs', value: '7', slot: 7},
    34: {type: 'clubs', value: '8', slot: 8},
    35: {type: 'clubs', value: '9', slot: 9},
    36: {type: 'clubs', value: '10', slot: 10},
    37: {type: 'clubs', value: 'J', slot: 11},
    38: {type: 'clubs', value: 'Q', slot: 12},
    39: {type: 'clubs', value: 'K', slot: 13},
    40: {type: 'diamonds', value: 'A', slot: 1},
    41: {type: 'diamonds', value: '2', slot: 2},
    42: {type: 'diamonds', value: '3', slot: 3},
    43: {type: 'diamonds', value: '4', slot: 4},
    44: {type: 'diamonds', value: '5', slot: 5},
    45: {type: 'diamonds', value: '6', slot: 6},
    46: {type: 'diamonds', value: '7', slot: 7},
    47: {type: 'diamonds', value: '8', slot: 8},
    48: {type: 'diamonds', value: '9', slot: 9},
    49: {type: 'diamonds', value: '10', slot: 10},
    50: {type: 'diamonds', value: 'J', slot: 11},
    51: {type: 'diamonds', value: 'Q', slot: 12},
    52: {type: 'diamonds', value: 'K', slot: 13},
    toIcon: function(card) {
        let icons = {
            'spades': 'fas fa-spade',
            'hearts': 'fas fa-heart',
            'clubs': 'fas fa-club',
            'diamonds': 'fas fa-diamond'
        };
        return icons[card.type];
    },
    toString: function(card) {
        return card.value + ' <i class="' + deck.toIcon(card) + '"></i>';
    }
};

/* javascript-obfuscator:enable */

function splitDecimal(n) {
    let s = parseFloat(n).toFixed(2).split('.');
    return [
        parseInt(s[0]),
        s[1]
    ];
}

function promise(urls) {
    return Promise.all(urls.map(function(url) {
        return $.ajax({url: url});
    }))
}

function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name+'=; Max-Age=-99999999;';
}

function fmtMSS(s) {
    return (s-(s%=60))/60+(9<s?':':':0') + s;
}

function isGuest() {
    return parseInt($('.chat').attr('data-role')) === -1;
}

let demoMsgShown; function showDemoTooltip() {
    if(demoMsgShown) return;
    demoMsgShown = true;
    iziToast.success({
        title: false,
        timeout: false,
        message: 'Вы выиграли в демо-режиме!<br><a class="ll" href="javascript:void(0)" onclick="$(\'#b_si\').click()">Войдите на сайт</a> для игры на настоящий баланс,<br>а так же получения бесплатных бонусов!',
        position: 'bottomLeft',
        icon: 'fa fa-coins',
        theme: 'dark',
        backgroundColor: '#222120'
    });
}

function socialAuth(type) {
    $('.modal-ui-block').fadeIn('fast');
    window.location.href = '/login/'+type;
}

function chunk(str, n) {
    var ret = [], i, len;
    for(i = 0, len = str.length; i < len; i += n) ret.push(str.substr(i, n));
    return ret;
}

function provablyfair() {
    if(isGuest()) {
        $('#b_si').click();
        return;
    }

    load('fairness');
}

function newDrop() {
    socket.emit('send drop');
}

function newSpecial() {
    if(socket == null || !socket.connected) {
        iziToast.error({message: 'Не удалось подключиться к серверу.', icon: 'fa fa-times'});
        return;
    }
    iziToast.question({
        rtl: false,
        layout: 1,
        class: 'mm pf',
        theme: 'dark',
        backgroundColor: '#211f1f',
        drag: false,
        timeout: false,
        close: true,
        overlay: true,
        displayMode: 1,
        progressBar: false,
        icon: false,
        title: false,
        message: '<div class="auth_dlg" style="height: 262px">' +
            '<div class="auth_header mm_header">' +
            '<i class="fad fa-microphone-stand"></i> Викторина' +
            '</div>' +
            '<div class="auth_content">' +
            '<div class="login">' +
            '<div class="login_title" style="height: 57px">' +
            '<span id="l_a" class="pf_title">Создание викторины</span>' +
            '</div>' +
            '<div class="login_fields pf_fields">' +
            '<div class="login_fields__user">' +
            '<div class="icon">' +
            '<img class="pf_hi" src="/storage/img/hash-key.png">' +
            '</div>' +
            '<input id="_mc_question" placeholder="Вопрос" type="text">' +
            '<div class="validation">' +
            '<img src="/storage/img/tick.png">' +
            '</div>' +
            '</input>' +
            '</div>' +

            '<div class="login_fields__user">' +
            '<div class="icon">' +
            '<img class="pf_hi" src="/storage/img/hash-key.png">' +
            '</div>' +
            '<input id="_mc_answer" placeholder="Ответ" type="text">' +
            '<div class="validation">' +
            '<img src="/storage/img/tick.png">' +
            '</div>' +
            '</input>' +
            '</div>' +

            '<div class="validation">' +
            '<img src="/storage/img/tick.png">' +
            '</div>' +
            '</div>' +
            '<div class="login_fields__submit pf_submit" style="top: 180.5px!important">' +
            '<input id="_mc_submit" type="submit" value="Отправить">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>',
        position: 'center'
    });

    $('#_mc_submit').on('click', function() {
        if($('#_mc_question').val().length < 1 || $('#_mc_answer').length < 1) {
            iziToast.error({message: 'Заполните поля!', position: 'bottomCenter', icon: 'fa fa-times'});
            return;
        }
        $('.pf .iziToast-close').click();

        socket.emit('create custom event', JSON.stringify({
            question: $('#_mc_question').val(),
            answer: $('#_mc_answer').val()
        }));
    });
}

function client_seed_change_prompt() {
    $('.pf .iziToast-close').click();
    iziToast.show({
        backgroundColor: '#222120',
        progressBar: false,
        theme: 'dark',
        overlay: true,
        drag: false,
        displayMode: 1,
        pauseOnHover: false,
        timeout: false,
        message: "После изменения клиентского хэша все результаты предыдущих игр станут недействительными!",
        class: 'csp',
        position: 'center',
        buttons: [
            ['<button><b>Продолжить</b></button>', function(instance, toast) {
                $('.csp .iziToast-close').click();

                iziToast.question({
                    rtl: false,
                    layout: 1,
                    class: 'mm pf',
                    theme: 'dark',
                    backgroundColor: '#211f1f',
                    drag: false,
                    timeout: false,
                    close: true,
                    overlay: true,
                    displayMode: 1,
                    progressBar: false,
                    icon: false,
                    title: false,
                    message: '<div class="auth_dlg" style="height: 262px">' +
                        '<div class="auth_header mm_header">' +
                        '<i class="fad fa-shield-alt"></i> Честная игра' +
                        '</div>' +
                        '<div class="auth_content">' +
                        '<div class="login">' +
                        '<div class="login_title" style="height: 57px">' +
                        '<span id="l_a" class="pf_title">Изменение хэша клиента</span>' +
                        '</div>' +
                        '<div class="login_fields pf_fields">' +
                        '<div class="login_fields__user">' +
                        '<div class="icon">' +
                        '<img class="pf_hi" src="/storage/img/hash-key.png">' +
                        '</div>' +
                        '<input id="nch" placeholder="Хэш" type="text">' +
                        '<div class="validation">' +
                        '<img src="/storage/img/tick.png">' +
                        '</div>' +
                        '</input>' +
                        '</div>' +
                        '<div class="validation">' +
                        '<img src="/storage/img/tick.png">' +
                        '</div>' +
                        '</div>' +
                        '<div class="login_fields__submit pf_submit" style="top: 162px!important;">' +
                        '<input id="cc" type="submit" value="Изменить">' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>',
                    position: 'center'
                });

                $('#cc').on('click', function() {
                    if($('#nch').val().length < 5) {
                        iziToast.error({message: 'Хэш должен содержать минимум 5 символов.', position: 'bottomCenter', icon: 'fa fa-times'});
                        return;
                    }
                    $.get('/change_client_seed/'+$('#nch').val(), function() {
                        window.location.reload();
                    });
                });
            }],
            ['<button><b>Отменить</b></button>', function (instance, toast) {
                $('.csp .iziToast-close').click();
            }]
        ]
    });
}

function withdrawOkDialog() {
    $('.md-wallet').removeClass('md-show');
    iziToast.question({
        rtl: false,
        layout: 1,
        class: 'mm walletDlg',
        theme: 'dark',
        backgroundColor: '#211f1f',
        drag: true,
        timeout: false,
        close: true,
        overlay: true,
        displayMode: 1,
        progressBar: false,
        icon: false,
        title: false,
        message: `
            <div class="mm_dlg" style="height: 230px;">
                <div class="mm_header">
                    Выплата
                </div>
                <div class="mm_general_info" style="height: 100%">
                    <div class="animation-ctn">
                        <div class="icon icon--order-success svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="154px" height="154px">  
                                <g fill="none" stroke="#22AE73" stroke-width="2"> 
                                    <circle cx="77" cy="77" r="72" style="stroke-dasharray:480px, 480px; stroke-dashoffset: 960px;"></circle>
                                    <circle id="colored" fill="#22AE73" cx="77" cy="77" r="72" style="stroke-dasharray:480px, 480px; stroke-dashoffset: 960px;"></circle>
                                    <polyline class="st0" stroke="#fff" stroke-width="10" points="43.5,77.8 63.7,97.9 112.2,49.4 " style="stroke-dasharray:100px, 100px; stroke-dashoffset: 200px;"/>   
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div class="withdraw-ok-content">
                        <p>Выплата успешно заказана!</p>
                        <span>Деньги скоро будут переведены на указанный кошелек.</span>
                        <br>
                        <span>Обычно это занимает от 1 минуты<br>до 3 дней.</span>
                    </div>
                </div>
            </div>
        `,
        position: 'center'
    });
}

function user_game_info(game_id, showSendToChatButton) {
    showSendToChatButton = showSendToChatButton === undefined ? true : showSendToChatButton;
    $.get('/api/drop/' + game_id, function(data) {
        var json = JSON.parse(data);

        let col = json.user_id < 0 ? 12 : 4;
        let col_m = json.user_id < 0 ? 12 : 6;
        iziToast.question({
            rtl: false,
            layout: 1,
            class: 'mm pfa',
            theme: 'dark',
            backgroundColor: '#211f1f',
            drag: false,
            timeout: false,
            close: true,
            overlay: true,
            displayMode: 1,
            progressBar: false,
            icon: false,
            title: false,
            message: '<div class="mm_dlg" style="height: 200px">' +
                '<div class="mm_header">' +
                '<i class="'+json.icon+'"></i> '+json.name+' - ' + json.id +
                '</div>' +
                '<div class="mm_general_info">' +
                '<span class="hidden-xs">' +
                (json.user_id < 0 ? 'Несколько игроков' :
                'Игрок: <a href="javascript:void(0)" onclick="load(\'user?id='+json.user_id+'\')" class="ll_user">' + json.username + '</a>') +'</span>' +
                (showSendToChatButton ? '<div class="mm_general_info_btn" onclick="if(!$(this).hasClass(\'csb_disabled\')) { sendGameToChat('+game_id+'); $(this).toggleClass(\'csb_disabled\', true) }"><i class="fad fa-comments"></i> Отправить в чат</div>' : '') +
                '<div class="row mm_game_info" '+(json.user_id === -2 ? 'style="margin-top:28px!important"' : '')+'>' +
                (json.user_id === -2 ? '' : ('<div class="hidden-xs col-sm-'+col+'">' +
                    '<p>Ставка</p>' +
                    '<span>'+json.bet+' руб.</span>' +
                    '</div>')) +
                (json.user_id === -2 ? '' : ('<div class="col-xs-'+col_m+' col-sm-'+col+'">' +
                    '<p>Коэфф.</p>' +
                    '<span>x'+json.mul+'</span>' +
                    '</div>')) +
                '<div class="col-xs-'+col_m+' col-sm-'+col+'">' +
                '<p>Выигрыш</p>' +
                '<span>'+(json.status === 1 ? '+'+json.amount : '0.00')+' руб.</span>' +
                '</div>' +
                '</div>' +
                '</div>' +
                (json.server_seed != null ? '<div class="pfd"><span>Серверный хэш: </span><strong>'+json.server_seed+'</strong></div>' +
                    '<div class="ss_check" onclick="provablyfair()"><i class="fad fa-shield-alt"></i> Проверить</div>' : '') +
                '</div>',
            position: 'center'
        });
    });
}

function info(about) {
    $.get('/info.' + about, function(data) {
        let content = $(data);
        content.append('<button class="info_button">Далее</button>');

        iziToast.question({
            rtl: false,
            layout: 1,
            class: 'mm',
            theme: 'dark',
            backgroundColor: '#211f1f',
            drag: false,
            timeout: false,
            close: true,
            overlay: true,
            displayMode: 1,
            progressBar: false,
            icon: false,
            title: false,
            message: '<div class="mm_dlg mm_dlg-small">' +
                '<div class="mm_header mm_header-center" id="title"></div>' +
                '<div class="mm_general_info" style="height: 92%">' +
                content.html() +
                '</div></div>',
            position: 'center'
        });
        $('#title').html($('#__info_title').html());
        $('.info-container').slick({
            dots: true,
            infinite: false,
            speed: 300,
            slidesToShow: 1,
            arrows: false
        });
        $('.info-container').on('afterChange', function(event, slick, currentSlide, nextSlide){
            if($('.info-container').slick('slickCurrentSlide') === $('.info-block').length - 1)
                $('.info_button').html('Закрыть');
            else $('.info_button').html('Далее');
        });
        $('.info_button').on('click', function() {
            if($('.info-container').slick('slickCurrentSlide') === $('.info-block').length - 1)
                $('.mm .iziToast-close').click();
            else $('.info-container').slick('slickNext');
        })
    });
}

function declOfNum(number, titles) {
    cases = [2, 0, 1, 1, 1, 2];
    return titles[(number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5]];
}

let taskTimeout = null;
function setTaskStatus(isCompleted, reward) {
    if(taskTimeout != null) clearTimeout(taskTimeout);
    let fade = function(callback) {
        $('.game-task-container').stop(true).fadeOut('fast', function () {
            callback();
            $('.game-task-container').fadeIn('fast');
        });
    };

    fade(function() {
        if(isCompleted) {
            $('*[data-task-id]').removeAttr('data-task-id');
            $('.game-task-container').toggleClass('wg_lose', false).toggleClass('wg_win', true);
            $('.game-task-container').html('<p>Задание выполнено!</p><a>+'+reward+' руб.</a>');
            updateBalance();
        } else {
            $('.game-task-container').toggleClass('wg_lose', true).toggleClass('wg_win', false);
            $('.game-task-container').html('<p>Задание провалено!</p><a>Вы потратили 1 попытку.</a>');
            taskTimeout = setTimeout(resetTask, 2500);
        }
    });
}

function validateTask(game_id) {
    if($('*[data-task-id]').length === 0) return;
    $.get('/task/validate/'+$('*[data-task-id]').attr('data-task-id')+'/'+game_id, function(response) {
        let json = JSON.parse(response);
        if(json.error != null) {
            console.error(json);
            return;
        }
        setTaskStatus(json.completed === true, parseFloat(json.reward).toFixed(2));
    });
}

function resetTask() {
    if($('*[data-task-id]').length === 0) return;
    $.get('/task/tries/'+$('*[data-task-id]').attr('data-task-id'), function(response) {
        let value = parseInt(response);
        if(value === 0) {
            $('*[data-task-id]').fadeOut('fast', function() {
                $('.game-task-container').toggleClass('wg_lose', false).toggleClass('wg_win', false);
                $('*[data-task-id]').removeAttr('data-task-id').fadeIn('fast')
                    .html('<p>Доступно задание<br><small>Для участия в задании необходимо приобрести попытки</small></p><a href="javascript:void(0)" class="ll" onclick="load(\'tasks\')">Перейти на страницу</a>');
            });
        } else {
            $('*[data-task-id]').fadeOut('fast', function() {
                $('.game-task-container').toggleClass('wg_lose', false).toggleClass('wg_win', false);
                $.get('/task/description/'+$('*[data-task-id]').attr('data-task-id'), function(desc) {
                    $('*[data-task-id]').html('<p>Задание:<br><small>'+desc+'</small></p><a>'+value+' '+declOfNum(value, ['попытка', 'попытки', 'попыток'])+'</a>').fadeIn('fast');
                });
            });
        }
    });
}

function task(id) {
    $.get('/task_info/'+id, function(response) {
        let json = JSON.parse(response);
        iziToast.question({
            rtl: false,
            layout: 1,
            class: 'mm tt',
            theme: 'dark',
            backgroundColor: '#211f1f',
            drag: false,
            timeout: false,
            close: true,
            overlay: true,
            displayMode: 1,
            progressBar: false,
            icon: false,
            title: false,
            message: '<div class="mm_dlg mm_dlg-small">' +
                '<div class="mm_header mm_header-center">Задание</div>' +
                '<div class="mm_general_info" style="height: 92%">' +
                '<div class="info-container">' +
                '<div class="info-block-title">Стоимость одной попытки: '+json.price+' руб.</div>' +
                '<div class="info-block-content">' +
                'Сколько попыток Вы желаете приобрести?' +
                '<div class="tries">' +
                '<input data-number-input="true" id="tr_input" class="b_input_s" type="text" placeholder="Кол-во попыток" value="1">' +
                '<small><span id="tr_price">1 попытка за '+json.price+' руб.</span></small>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<button class="info_button">Приобрести</button>' +
                '</div></div>',
            position: 'center'
        });
        general_init();
        $('#tr_input').on('input', function() {
            let v = parseInt($('#tr_input').val());
            if(isNaN(v) || v < 1) {
                $('#tr_price').html('Введите количество попыток!');
                $('.info_button').addClass('ib_disabled');
                return;
            } else $('.info_button').removeClass('ib_disabled');
            $('#tr_price').html(v + ' ' + declOfNum(v, ['попытка', 'попытки', 'попыток']) + ' за ' + (parseFloat(json.price) * v).toFixed(2) + ' руб.');
        });
        $('.info_button').on('click', function() {
            if($(this).hasClass('ib_disabled')) return;
            let v = parseInt($('#tr_input').val());
            if(isNaN(v) || v < 1) {
                $('#tr_price').html('Введите количество попыток!');
                return;
            }

            $.get('/task/purchase/'+id+'/'+v, function(response) {
                let j = JSON.parse(response);
                if(j.error != null) {
                    if(j.error === -1) $('#b_si').click();
                    if(j.error === 0)
                        iziToast.error({message: 'Этого задания не существует.', icon: 'fal fa-times', position: 'bottomCenter'});
                    if(j.error === 1)
                        iziToast.error({message: 'Вы уже приобрели попытки для этого задания - потратьте их.', icon: 'fal fa-times', position: 'bottomCenter'});
                    if(j.error === 2)
                        iziToast.error({message: 'У вас недостаточно баланса на счете.', icon: 'fal fa-times', position: 'bottomCenter'});
                    return;
                }

                $.get('/game_info/'+json.game_id, function(response) {
                    let json = JSON.parse(response);

                    $('.tt .iziToast-close').click();
                    load(json.name.toLowerCase(), function() {
                        updateBalance();
                    });
                });
            });
        });
    });
}

var prev = -1;
function updateBalance(callback, specificValue) {
    if($('#g_balance').length > 0) {
        let u = function(data) {
            if((prev !== -1 && prev !== data) || specificValue !== undefined) {
                let c = '', val = '', lesser = specificValue === undefined ? parseFloat(prev) < parseFloat(data) : specificValue >= 0;
                data = specificValue === undefined ? data : (parseFloat($('#g_balance').html()) + specificValue).toFixed(2);

                if(specificValue !== undefined) val = specificValue;
                else if(lesser) val = (parseFloat(data) - parseFloat(prev)).toFixed(2);
                else val = (parseFloat(prev) - parseFloat(data)).toFixed(2);
                c = '<span class="'+(lesser ? 'win' : 'lose')+'">'+ (lesser ? '+' : '-') + Math.abs(val) + ' <i class="fa fa-ruble-sign"></i></span>';

                if(!isNaN(parseFloat(val)) && parseFloat(val) !== 0) {
                    let a = $('<span class="balance-animated" style="display: none;">' + c + '</span>');
                    $('#g_balance').html(data).append(a);
                    a.fadeIn('fast', function () {
                        a.animate({'top': '80%'}, 800);
                        if(callback !== undefined) callback(val);
                        setTimeout(function () {
                            a.fadeOut('slow');
                        }, 600);
                    });
                }
            }
            prev = data;
        };
        if(specificValue === undefined) $.get('/api/money', function(data) {
            u(data);
        });
        else u(prev);
    }
}

var _disableDemo = false;
function setDemo(demo) {
    if(_disableDemo) return;

    isDemo = demo;
    $('#game_demo').toggleClass('demo_active', isDemo);

    $('.wallet-icon i:last-child').toggleClass('fa-rotate-180', isDemo);
}

function setQuickGame(quick) {
    isQuick = quick;
    $('#game_quick').toggleClass('demo_active', isQuick);
}

var chat = false;
function swapChat() {
    chat = !chat;
    if(chat) {
        $('.message').fadeIn();
        $('.chat').removeAttr('style');
        $('.chat_status').css({'opacity': 1});
        $('.chat_input').css({'opacity': 1});
        $('.chat_event_timer').fadeIn('fast');

        $("#chat_nano").nanoScroller();
        $("#chat_nano").nanoScroller({ scroll: 'bottom' });
    } else {
        $('.chat_event_timer').fadeOut('fast');
        $('.chat').css({'min-width': '0', 'width': '0'});
        $('.chat_status').css({'opacity': 0});
        $('.chat_input').css({'opacity': 0});
        $('.message').fadeOut('fast');
    }
}

function rdp() {
    $.each($('*[data-parent]'), function(i, e) {
        if(document.body.clientWidth < 996) $(e).removeAttr('style');
        else $(e).css("height", $($(e).attr('data-parent')).height());
    });

    if($('.g_container').length > 0) {
        if (document.body.clientWidth < 996) $('.g_container').insertBefore('.g_sidebar');
        else $('.g_sidebar').insertBefore('.g_container');
    }
}

function tabScroller() {
    if(typeof $('.sport-game-tabs').jScrollPane === 'function')
        $('.sport-bet-tabs').jScrollPane({
            autoReinitialise: false
        });
}

$(window).resize(function() {
    rdp();
    tabScroller();
});

$(window).load(function() {
    tabScroller();
});

function loadChatHistory() {
    if(socket == null || socket.disconnected) {
        setTimeout(loadChatHistory, 5000);
        return;
    }

    socket.emit('chat history', $('#chat_send').attr('data-user-id'));
}

function unban_chat() {
    $.get('/chat/unban', function(response) {
        var json = JSON.parse(response);

        if(json.error != null) {
            if(json.error === -2) iziToast.error({'message': 'Требуется авторизация.', 'icon': 'fa fa-times'});
            if(json.error === -1) window.location.reload();
            if(json.error === 0) $('#_payin').click();
            if(json.error === 1) iziToast.error({'message': 'Ваш аккаунт достиг максимального количества блокировок.', 'icon': 'fa fa-times'});
            return;
        }

        updateBalance();
        window.location.reload();
    });
}

function sendDrop(game_id) {
    if(socket == null || socket.disconnected) {
        console.log('Failed to send drop info: user is not connected to the server');
        return;
    }
    $.get('/api/drop/' + game_id, function(data) {
        socket.emit('live_drop', data);
    });
}

function setBetText(txt) {
    $('#play').fadeIn('fast');
    if(txt !== $('#bet_btn').html()) $('#bet_btn').fadeOut('fast', function() {
        $('#bet_btn').html(txt);
        $('#bet_btn').fadeIn('fast');
    });
}

function updateTooltips() {
    $('.tooltip').tooltipster({
        theme: 'tooltipster-punk',
        animation: 'fade',
        position: 'bottom'
    });
}

function general_init() {
    $(".nano").nanoScroller();
    updateTooltips();

    let copyToClipboard = function(element) {
        let $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    };
    $('.copy').on('click', function() {
        copyToClipboard($(this));
        $(this).tooltipster('content', 'Скопировано в буфер обмена!');
        let e = $(this);
        setTimeout(function() {
            e.tooltipster('open');
        }, 150);
    });

    $('*[data-eng-only-input="true"]').keypress(function(event) {
        var ew = event.which;
        if(48 <= ew && ew <= 57) return true;
        if(65 <= ew && ew <= 90) return true;
        return 97 <= ew && ew <= 122;
    });
    $('*[data-number-input="true"]').keypress(function(event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    if($('.faq').length > 0) {
        $('.faq-block').on('click', function() {
            if($(this).find('.faq-header').hasClass('faq-header-active')) return;
            $('.faq-content').hide();

            $('.faq-header').removeClass('faq-header-active');
            $(this).find('.faq-header').addClass('faq-header-active');

            $(this).find('.faq-content').slideDown('fast');
        });
    }

    if($('#bet').length > 0) {
        $('#bet').on('change', __profit);
        $('#bet').on('input', __profit);
        var max = 9999999;
        var add = function(n) {
            var v = (parseFloat($('#bet').val()) + n).toFixed(2);
            if(v > max || v < 0 || isNaN(v)) return;
            $('#bet').val(v);
            __profit();
        };
        $('#divide').on('click', function() {
            var v = (parseFloat($('#bet').val()) / 2).toFixed(2);
            if(v > max || v < 0 || isNaN(v)) return;
            $('#bet').val(v);
            __profit();
        });
        $('#multiply').on('click', function() {
            var v = (parseFloat($('#bet').val()) * 2).toFixed(2);
            if(v > max || v < 0 || isNaN(v)) return;
            $('#bet').val(v);
            __profit();
        });
        $('#01').on('click', function() { add(0.1) });
        $('#1').on('click', function() { add(1.0) });
        $('#5').on('click', function() { add(5.0) });
        $('#10').on('click', function() { add(10.0) });
    }

    updateBalance();
}

function sendChatMessage(user_id, message, system) {
    if(socket == null || socket.disconnected) {
        iziToast.error({'message': 'Не удалось подключиться к серверу.', 'icon': 'fa fa-times'});
        return;
    }

    $('.emojionearea-editor').html('');
    if(message.replace(/\s/g, "").length < 1) {
        iziToast.error({'message': 'Введите сообщение.', 'icon': 'fa fa-times'});
        return;
    }

    message = message.substring(0, 126).replace(/\n/g, ' ');

    var data = {
        'message': message,
        'user_id': user_id,
        'system': system === undefined ? 'false' : 'true'
    };
    var data_hashed = {
        'message': message.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, ''),
        'user_id': user_id,
        'system': system === undefined ? 'false' : 'true'
    };
    $.get('/socket/token/' + user_id + '/' + JSON.stringify(data_hashed), function(response) {
        socket.emit('chat message', JSON.stringify({
            'data': data,
            'hash': response
        }));
    });
}

function sendGameToChat(id) {
    var data = {
        'action': 'send_game',
        'game_id': id
    };
    sendChatMessage($('#chat_send').attr('data-user-id'), JSON.stringify(data), true);
}

function chatModMenu(message_id, user_name, user_id) {
    let success = function(msg) {
        iziToast.success({message: msg === undefined ? 'Успех' : msg, icon: 'fal fa-check'});
    };
    let m = function(minutes) {
        $.get('/admin/mute/'+user_id+'/'+$('#chat_send').attr('data-user-id')+'/'+minutes);
        success();
    };

    iziToast.show({
        backgroundColor: '#5d5ab1',
        progressBar: false,
        theme: 'dark',
        overlay: true,
        displayMode: 1,
        pauseOnHover: false,
        timeout: false,
        message: user_name,
        position: 'center',
        buttons: [
            ['<button><b>Удалить это сообщение</b></button>', function(instance, toast) {
                var data = {
                    'action': 'remove_this_message',
                    'message_id': message_id
                };
                sendChatMessage($('#chat_send').attr('data-user-id'), JSON.stringify(data), true);
                success('Сообщение #' + message_id + ' от #' + user_id +' было удалено');
            }],
            ['<button><b>Удалить все сообщения от этого пользователя</b></button>', function (instance, toast) {
                var data = {
                    'action': 'remove_message',
                    'from': user_id
                };
                sendChatMessage($('#chat_send').attr('data-user-id'), JSON.stringify(data), true);
                success('Все сообщения от #' + user_id + ' были удалены');
            }],
            ['<button><b>Заблокировать чат навсегда</b></button>', function (instance, toast) {
                var data = {
                    'action': 'ban',
                    'to': user_id
                };
                sendChatMessage($('#chat_send').attr('data-user-id'), JSON.stringify(data), true);
                success('Пользователь #' + user_id + ' заблокирован');
            }],
            ['<button><b>Мут - 1м</b></button>', function() {
                m(1);
            }],
            ['<button><b>Мут - 15м</b></button>', function() {
                m(15);
            }],
            ['<button><b>Мут - 1ч</b></button>', function() {
                m(60);
            }],
            ['<button><b>Мут - 1д</b></button>', function() {
                m(60*24);
            }],
            ['<button><b>Мут - 1н</b></button>', function() {
                m(60*24*7);
            }]
        ]
    });
}

function p_n(s) {
    return parseInt($(s).html());
}

function isEmailConfirmed() {
    return $('.md-email-activation').length === 0;
}

var si = ["", "k", "M", "G", "T", "P", "E"];
function abbreviateNumber(number){
    var tier = Math.log10(number) / 3 | 0;
    if(tier === 0) return number;

    var suffix = si[tier];
    var scale = Math.pow(10, tier * 3);

    var scaled = number / scale;

    return scaled.toFixed(1) + suffix;
}

function getSeason() {
    let month = new Date().getMonth();
    if(month === 11 || month === 0 || month === 1) return 'snow';
    return 'rain';
}

$.urlParam = function(name) {
    let results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) return null;
    return decodeURI(results[1]) || 0;
};

let dropQueue = [];

$(window).on('scroll', function() {
    $('.header').toggleClass('header_fixed', $(window).scrollTop() >= 30);
    $('main').css('margin-top', $(window).scrollTop() >= 30 ? '105px' : 0);
});

function clone() {
    for (var i = 0; i <= 5; i++) {
        let giftDuplicate = $('.wheel-wrapper').children().clone(true, true);
        $('.wheel-wrapper').append(giftDuplicate);
    }
}

function spin(id, size) {
    $(".wheel-wrapper").css({left: "0"});

    let giftamount = size * 2;
    let gw = $('.wheel-item').outerWidth(true);
    let giftcenter = gw/2;
    let cycle = 7;

    let containercenter = $('.wheel-wrapper').outerWidth(true)/2;

    let randomgift = id;
    let dev = giftcenter + 1;
    let distance = giftamount * cycle * gw + (randomgift * gw) - containercenter /*- 24*/ + dev;

    $('.wheel-wrapper').animate({left: "-=" + distance}, 10000);
}

function mainSlider() {
    $('.carousel').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 5500,
        dots: true,
        centerMode: true,
        responsive: [{
            breakpoint: 991,
            settings: {
                centerPadding: '0'
            }
        }]
    });
}

function asyncCSS(path) {
    let stylesheet = document.createElement('link');
    stylesheet.href = path;
    stylesheet.rel = 'stylesheet';
    stylesheet.type = 'text/css';
    stylesheet.media = 'only x';
    stylesheet.setAttribute('data-async', 'true');
    stylesheet.onload = function() {
        stylesheet.media = 'all';
        if(path.includes('app.css')) {
            rdp();
            if(window.location.pathname === '/' || window.location.pathname === '/games') mainSlider();

            $('.loader').fadeOut('fast');
        }
        console.log(path + ' loaded');
    };
    document.getElementsByTagName('head')[0].appendChild(stylesheet);
}

function achievement(type, name, description) {
    let types = {
        bronze: {
            color: '#ffa5a5'
        },
        silver: {
            color: '#ffffff'
        },
        gold: {
            color: '#feca57'
        },
        platinum: {
            color: '#ADD8E6'
        }
    };

    iziToast.show({
        theme: 'dark',
        class: 'bg_waiting',
        icon: 'fad fa-award',
        title: 'Достижение получено - '+name+'!',
        displayMode: 2,
        message: description+'<br><a href="javascript:void(0)" onclick="load(\'user?id='+$('#chat_send').attr('data-user-id')+'\', function() { setTab(\'achievements\') })" class="ll">Узнать подробнее</a>',
        position: 'bottomRight',
        transitionIn: 'flipInX',
        progressBarColor: types[type].color,
        imageWidth: 70,
        layout: 2,
        timeout: 7500,
        drag: false,
        iconColor: types[type].color
    });
}

function watcher() {
    let urls = [], elements = [];
    $.each($('[data-watch-fragment]'), function(index, element) {
        urls.push($(element).attr('data-watch-fragment'));
        elements.push(element);

        if($(element).html().length === 0 && $(element).attr('data-watch-disable-loader') !== 'true') {
            let isSlider = $(element).attr('data-slide-fragment') === 'true';
            $(element).append(`<div class="profile-loader sport-` + (isSlider ? 'slide-' : '') + `content-loader"><div></div></div>`);
        }
    });

    let jsonWatcher = function(use, callback) {
        let doc = $(use === null ? document : use);

        let urls = [], elements = [];
        $.each(doc.find('[data-watch-id]'), function(index, element) {
            let e = $(element);
            let isTotal = e.attr('data-watch-game') === 'total';
            urls.push('/sport_api/game/'+(isTotal ? 'live/' : '')+e.attr('data-watch-game')+(!isTotal ? '/'+e.attr('data-watch-id') : '')+(e.attr('data-watch-live') === 'true' ? '/live' : ''));
            elements.push(e);
        });

        promise(urls).then(function(result) {
            for(let i = 0; i < result.length; i++) {
                let response = result[i], e = elements[i];

                try {
                    let json = JSON.parse(response);
                    $.each(e.find('[data-watch]'), function(index, element) {
                        let watchingElement = $(element);
                        let getValue = function(by) {
                            let value = json;

                            let s = watchingElement.attr(by).split('|');
                            for (let i = 0; i < s.length; i++)
                                value = value[s[i]];

                            if (value === null || value === undefined) {
                                console.error('Invalid watcher: ' + watchingElement.attr(by));
                                value = '-';
                            }
                            return value;
                        };

                        let value = getValue('data-watch');

                        if(value === '-' && watchingElement.attr('data-watch-or')) {
                            value = getValue('data-watch-or');
                        }

                        let type = watchingElement.attr('data-watch-replace-type') ? watchingElement.attr('data-watch-replace-type') : 'html';
                        if(type === 'html') watchingElement.html(value);
                        if(type === 'title') {
                            watchingElement.addClass('tooltip');
                            watchingElement.attr('title', value);
                            updateTooltips();
                        }
                        if(type === 'visibility') {
                            let visibilityValue = watchingElement.attr('data-watch-visibility-visible-trigger') ? watchingElement.attr('data-watch-visibility-visible-trigger') : '-';
                            if(value.toString() === visibilityValue) watchingElement.css({display: 'flex'}); else watchingElement.hide();
                        }

                        if(watchingElement.attr('data-watch-game') === 'total')
                            watchingElement.fadeIn('fast');
                    });
                } catch(e) {
                    console.error('Failed to watch game info');
                    console.error(e);
                }
            }
            if(callback !== null) callback(doc);
            link();
        });
    };

    jsonWatcher(null, null);
    if(urls.length > 0) {
        promise(urls).then(function(result) {
            for(let i = 0; i < result.length; i++) {
                jsonWatcher(result[i], function(doc) {
                    $(elements[i]).html(doc);
                });
            }
        }).catch(function(error) {
            console.log(error);
        });
    }
}

function reloadCSS(callback) {
    $('[data-async]').remove();
    if(callback !== undefined) callback();
    for(let i = 0; i < _css.length; i++) asyncCSS(_css[i]);
}

$(document).ready(function() {
    $.get('/asyncBonus', function() {
        updateBalance();
        watcher();
    });

    if(watcherInstance != null) clearInterval(watcherInstance);
    watcherInstance = setInterval(watcher, 15000);

    reloadCSS();

    general_init();
    if(typeof __profit === 'function') __profit();

    $('*[data-game]').toggleClass('m-game-selection-item-active', false);
    $('*[data-game="'+window.location.pathname.substring(1)+'"]').toggleClass('m-game-selection-item-active', true);

    $('#_payin').on('click', function() {
        $('*[data-tab="#pay"]').click();
    });

    $('.wallet-icon').on('click', function() {
        if(_disableDemo) return;
        setDemo(!isDemo);

        iziToast.success({
            'icon': 'fal fa-check',
            'message': isDemo ? 'Вы включили демо-режим.<br>В этом режиме деньги не списываются и не начисляются, давая возможность попробовать поиграть во все игры, не опасяясь за свой баланс.<br>Для отключения нажмите на иконку <i class="fad fa-coins game_info-icon_info"></i>, которая находится рядом с балансом аккаунта.'
                : 'Вы отключили демо-режим.',
            'position': 'bottomCenter',
            'timeout': 15000,
            'backgroundColor': 'rgb(166,239,184)'
        })
    });

    $('#notifications, .header_notifications_close').on('click', function() {
        if($('.header_notifications_window').attr('data-visible') === 'false') {
            $('.header_notifications_window').show().attr('data-visible', 'true');
            $('.notifications_icon').fadeOut('fast');
            $.get('/readNotifications');
        }
        else $('.header_notifications_window').hide().attr('data-visible', 'false');
    });

    // TODO: Remove when this is done
    $('.md-unavailable').toggleClass('md-show', window.location.pathname.includes('sport'));

    registerAjaxCallback(function(page) {
        if(!isEmailConfirmed() && page !== 'games' && page) return false;

        $('*[data-game]').toggleClass('m-game-selection-item-active', false);
        $('*[data-game="'+page+'"]').toggleClass('m-game-selection-item-active', true);

        return true;
    }, function(page) {
        // TODO: Remove when this is done
        $('.md-unavailable').toggleClass('md-show', window.location.pathname.includes('sport'));

        if(!page.includes('sport')) {
            $('.gg_sidebar_main').fadeIn('fast');
            $('.sport_sidebar').fadeOut('fast');
            $('body').toggleClass('sport-page', false);
        }
        watcher();

        if(typeof __profit === 'function') __profit();

        general_init();
        rdp();
        tabScroller();

        if(window.location.pathname === '/' || window.location.pathname === '/games') mainSlider();
    });

    if(document.body.clientWidth > 1650) swapChat();

    let d = false;
    $('#l_b').on('click', function() {
        let current = $('.auth-tab-active').attr('data-auth-action');
        console.log(current);

        let email = $('#_email').val(), login = $('#_login').val(), password = $('#_password').val();

        if((current === 'register' && email.length < 1) || login.length < 1 || password.length < 1) {
            iziToast.error({message: 'Заполните данные!', icon: 'fa fa-times', position: 'bottomCenter'});
            return;
        }

        $('.modal-ui-block').fadeIn('fast');
        if(current === 'auth') {
            $.get('/auth/'+login+'/'+password, function(response) {
                let json = JSON.parse(response);
                if(json.error != null) {
                    $('.modal-ui-block').fadeOut('fast');
                    if(json.error === 0) iziToast.error({message: 'Такого пользователя не существует.', icon: 'fa fa-times', position: 'bottomCenter'});
                    if(json.error === 1) iziToast.error({message: 'Указан неверный пароль.', icon: 'fa fa-times', position: 'bottomCenter'});
                    return;
                }
                window.location.reload();
            }).fail(function() {
                $('.modal-ui-block').fadeOut('fast');
                iziToast.error({message: 'Произошла ошибка. Попробуйте снова.', icon: 'fa fa-times', position: 'bottomCenter'});
            });
        } else {
            if(d) return; d = true;
            $.get('/register/'+login+'/'+email+'/'+password, function(response) {
                let json = JSON.parse(response);
                d = false;
                if(json.error != null) {
                    $('.modal-ui-block').fadeOut('fast');
                    if(json.error === 0)
                        iziToast.error({message: 'Произошла серверная ошибка.', icon: 'fa fa-times', position: 'bottomCenter'});
                    if(json.error === 1)
                        iziToast.error({message: 'Не удалось найти указанный сервис Email.<br>Проверьте указанные данные на наличие ошибок.', position: 'bottomCenter', icon: 'fa fa-times'});
                    if(json.error === 2)
                        iziToast.error({message: 'Логин должен иметь длину от 5 до 24 символов, а так же не может содержать специальные и русские символы.', icon: 'fa fa-times', position: 'bottomCenter'});
                    if(json.error === 3)
                        iziToast.error({message: 'Пользователь с таким логином уже существует.', icon: 'fa fa-times', position: 'bottomCenter'});
                    if(json.error === 4)
                        iziToast.error({message: 'Пользователь с таким email уже существует.', icon: 'fa fa-times', position: 'bottomCenter'});
                    if(json.error === 5)
                        iziToast.error({message: email + ' был обнаружен зарегистрированным через сервис временных Email.', position: 'bottomCenter', icon: 'fa fa-times'});
                    return;
                }
                window.location.reload();
            }).fail(function() {
                $('.modal-ui-block').fadeOut('fast');
                iziToast.error({message: 'Произошла ошибка. Попробуйте снова.', icon: 'fa fa-times', position: 'bottomCenter'});
                d = false;
            });
        }
    });

    $('.auth-tab').on('click', function() {
        if($(this).hasClass('auth-tab-active')) return;
        if($('.auth-tab-active').attr('data-auth-action') === 'auth') {
            $('.auth-tab-active').removeClass('sport-bet-tab-active').removeClass('auth-tab-active');
            $('.auth-tab[data-auth-action="register"]').addClass('sport-bet-tab-active').addClass('auth-tab-active');

            $('#vk_auth_label').html('Регистрация через ВКонтакте');
            $('#email').fadeIn(200);
            $('#l_b').val('Зарегистрироваться');
        } else if($('.auth-tab-active').attr('data-auth-action') === 'register') {
            $('.auth-tab-active').removeClass('sport-bet-tab-active').removeClass('auth-tab-active');
            $('.auth-tab[data-auth-action="auth"]').addClass('sport-bet-tab-active').addClass('auth-tab-active');

            $('#vk_auth_label').html('Войти через ВКонтакте');
            $('#email').fadeOut(200);
            $('#l_b').val('Войти');
        }
    });

    $('input[type="text"],input[type="password"]').focus(function(){
        $(this).prev().animate({'opacity':'1'}, 200);
    });
    $('input[type="text"],input[type="password"]').blur(function(){
        $(this).prev().animate({'opacity':'.5'}, 200);
    });
    $('input[type="text"],input[type="password"]').keyup(function(){
        if($(this).val() !== ''){
            $(this).next().animate({'opacity':'1','right' : '30'}, 200);
        } else {
            $(this).next().animate({'opacity':'0','right' : '20'}, 200);
        }
    });

    $('*[data-tab]').on('click', function() {
        if($(this).hasClass('mm_general_tab_active')) return;

        let tab = $(this).attr('data-tab');
        $('*[data-tab]').removeClass('mm_header_tab_active');
        $('*[data-tab="'+tab+'"]').addClass('mm_header_tab_active');
        $('.mm_general_tab_active').hide();

        $(this).removeClass('mm_general_tab_active');
        $(tab).addClass('mm_general_tab_active').fadeIn('fast');

        $('.p2').toggleClass('db', false);
        $('.p1').toggleClass('p1dn', false);
    });

    let currency = 63, provider = 'qiwi';
    $('*[data-wallet-type]').on('click', function() {
        $('*[data-wallet-type]').removeClass('payment-method_active');
        $(this).toggleClass('payment-method_active', true);
        $('#wallet_name').html($(this).find('.payment-method-name').html());
        $('#wallet_icon').attr('src', $(this).find('img').attr('src'));

        currency = $(this).attr('data-currency');
        provider = $(this).attr('data-provider');

        $('.p2').toggleClass('db', true);
        $('.p1').toggleClass('p1dn', true);
    });

    let withdrawType = 4;
    $('*[data-withdraw-type]').on('click', function() {
        $('*[data-withdraw-type]').removeClass('payment-method_active');
        $(this).toggleClass('payment-method_active', true);
        $('#withdraw_name').html($(this).find('.payment-method-name').html());
        $('#withdraw_icon').attr('src', $(this).find('img').attr('src'));

        withdrawType = $(this).attr('data-withdraw-type');

        $('.p2').toggleClass('db', true);
        $('.p1').toggleClass('p1dn', true);
    });

    $('#payin').on('click', function() {
        if(isNaN($('#payment').val()) || parseFloat($('#payment').val()) < 30) {
            iziToast.error({
                message: 'Сумма пополнения: от 30 до 15000 руб.',
                icon: 'fal fa-times',
                position: 'bottomCenter'
            });
            return;
        }

        $('.modal-ui-block').fadeIn('fast');
        window.location.href = '/invoice/'+$('#payment').val()+'/'+provider;
    });

    $('#payout').on('click', function() {
        if(isNaN($('#withv').val()) || parseFloat($('#withv').val()) < 1) {
            iziToast.error({
                message: 'Сумма вывода: от 1 до 15000 руб.',
                icon: 'fal fa-times',
                position: 'bottomCenter'
            });
            return;
        }

        $('.modal-ui-block').fadeIn('fast');
        $.ajax({
            type: 'POST',
            url: '/payout',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                amount: $('#withv').val(),
                provider: 3,
                currency: withdrawType,
                purse: $('#purse').val()
            },
            success: function(response) {
                let json = JSON.parse(response);
                if(json.error != null) {
                    if(json.error === -2) iziToast.error({message: 'Произошла серверная ошибка.', icon: 'fal fa-times', 'position': 'bottomCenter'});
                    if(json.error === -1) iziToast.error({message: 'Необходимо авторизоваться!', icon: 'fal fa-times', 'position': 'bottomCenter'});
                    if(json.error === 0) iziToast.error({message: 'Минимальная сумма для вывода: ' + json.value + ' руб.', icon: 'fal fa-times', 'position': 'bottomCenter'});
                    if(json.error === 1) iziToast.error({message: 'Недостаточно денег на счете!', icon: 'fal fa-times', 'position': 'bottomCenter'});
                    if(json.error === 2) iziToast.error({message: 'Введите номер кошелька!', icon: 'fal fa-times', 'position': 'bottomCenter'});
                    if(json.error === 3) iziToast.error({message: 'Дождитесь обработки прошлой заявки или отмените вывод в своем профиле.', icon: 'fal fa-times', 'position': 'bottomCenter'});
                    $('.modal-ui-block').fadeOut('fast');
                    return;
                }

                $('.walletDlg .iziToast-close').click();
                withdrawOkDialog();
                updateBalance();
            }
        });
    });

    $('.sport_sidebar_footer_purchase_button').on('click', function() {
        if($(this).hasClass('footer_purchase_button_disabled')) return;

        if(sBets.length === 0) {
            iziToast.error({'message': 'Вы не делали ставки.', 'icon': 'fal fa-times', 'position': 'bottomCenter'});
            return;
        }

        let urls = [];
        for(let i = 0; i < sBets.length; i++) {
            let errHeader = sBets[i].description.header + ' - ' + sBets[i].description.title + ', ' + sBets[i].description.subtitle + '<br>';
            if(isNaN(sBets[i].wager)) {
                iziToast.error({'message': errHeader + 'Ставка не указана. Минимальная ставка - 0.01 руб.', 'icon': 'fal fa-times', 'position': 'bottomCenter'});
                return;
            }
            if(sBets[i].wager < 0.01) {
                iziToast.error({'message': errHeader + 'Минимальная ставка - 0.01 руб.', 'icon': 'fal fa-times', 'position': 'bottomCenter'});
                return;
            }
            if(sBets[i].blocked === true) {
                iziToast.error({'message': errHeader + 'Подтвердите изменение коэффициента для этой ставки.', 'icon': 'fal fa-times', 'position': 'bottomCenter'});
                return;
            }

            let s = sBets[i].watcher.split('|'),
                betCategory = s[1], category = s[2], index = s[3];

            let description = sBets[i].description;
            urls.push('/sport/bet/'+sBets[i].game+'/'+sBets[i].id+'/'+betCategory+'/'+category+'/'+index+'/'+sBets[i].wager+'/'+JSON.stringify(description));
        }

        $(this).addClass('footer_purchase_button_disabled');

        $(this).html(`<div class="sm-loader">
          <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
          <path fill="#000" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
            <animateTransform attributeType="xml"
              attributeName="transform"
              type="rotate"
              from="0 25 25"
              to="360 25 25"
              dur="0.6s"
              repeatCount="indefinite"/>
            </path>
          </svg>
        </div>`);

        promise(urls).then(function(result) {
            for(let i = 0; i < result.length; i++) {
                let json = JSON.parse(result[i]);
                console.log('Bet ' + i + ' -> ');
                console.log(json);

                if(json.error != null) {
                    if(json.error === -1) $('#b_si').click();
                    if(json.error === 0) iziToast.error({'message': 'unknown game id', 'icon': 'fal fa-times', 'position': 'bottomCenter'});
                    if(json.error === 1) iziToast.error({'message': 'Прием ставок в данный момент недоступен для этого матча.', 'icon': 'fal fa-times', 'position': 'bottomCenter'});
                    if(json.error === 2) {
                        $('.md-wallet').toggleClass('md-show', true);
                        iziToast.error({'message': 'Недостаточно баланса на счете.', 'icon': 'fal fa-times', 'position': 'bottomCenter'});
                        break;
                    }
                    if(json.error === 3) iziToast.error({'message': 'Эта ставка в данный момент недоступна.', 'icon': 'fal fa-times', 'position': 'bottomCenter'});
                    continue;
                }

                sBets[i].remove();
                recalculateTotalBetSum();
            }

            updateBalance();
            $('.sport_sidebar_footer_purchase_button').removeClass('footer_purchase_button_disabled').html('Приобрести');
        });
    });

    setInterval(function() {
        if(dropQueue.length === 0) return;
        let drop = JSON.parse(dropQueue[0]);
        dropQueue.shift();
        if(parseFloat(drop.amount).toFixed(2) < 0) {
            drop.amount = '0.00';
            drop.status = 0;
        }
        let $e = $('<tr class="live_table-game" style="display: none">' +
            '<th>' +
            '<div class="live_table-animated">' +
            '<div class="ll_icon hidden-xs" onclick="' + (drop.user_id === -2 ? 'battlegrounds_connect()' : 'load(\''+drop.name.toLowerCase()+'\')') + '">' +
            '<i class="'+drop.icon+'"></i>' +
            '</div>' +
            '<div class="ll_game">' +
            '<span '+(drop.user_id === -2 ? 'onclick="user_game_info('+drop.id+')"' : drop.game_id === 12 ? 'load(\'cases\')' : 'onclick="load(\''+drop.name.toLowerCase()+'\')"')+'>'+drop.name+'</span>' +
            (drop.game_id === 12 ? '<p onclick="load(\'cases\')">Перейти</p>' : '<p onclick="user_game_info('+drop.id+')">Просмотр</p>') +
            '</div>' +
            '</div>' +
            '</th>' +
            '<th>' +
            '<div class="live_table-animated">' +
            '<a class="ll_user" '+(drop.user_id === -2 ? 'onclick="user_game_info('+drop.id+')"' : 'onclick="load(\'user?id='+drop.user_id+'\')"')+' href="javascript:void(0)">'+(drop.user_id === -2 ? 'Несколько' : drop.username)+'</a>' +
            '</div>' +
            '</th>' +
            '<th class="hidden-xs">' +
            '<div class="live_table-animated">' +
            (drop.time == null ? '' : drop.time) +
            '</div>' +
            '</th>' +
            '<th class="hidden-xs">' +
            '<div class="live_table-animated">' +
            (drop.user_id === -2 ? '' : drop.bet+' руб.') +
            '</div>' +
            '</th>' +
            '<th class="hidden-xs">' +
            '<div class="live_table-animated">' +
            (drop.user_id === -2 || drop.game_id === 12 ? '' : 'x'+drop.mul) +
            '</div>' +
            '</th>' +
            '<th>' +
            '<div class="live_table-animated">' +
            (drop.status === 1 ? '+'+drop.amount : '0.00') + ' руб.' +
            '</div>' +
            '</th>' +
            '</tr>');
        $('#ll tbody').prepend($e);

        $('#ll tr').last().fadeOut(400, function() {
            $(this).delay(400).remove();
            $($e).fadeIn(400);
        });
    }, 1000);

    let sendDropMessage = function(msg) {
        let json = JSON.parse(msg);

        let content = '';
        if(json.users.length === 0) content = 'Никто не попал под '+(getSeason() === 'snow' ? 'снег' : 'дождь')+'.';
        else for(let i = 0; i < json.users.length; i++) {
            content += '<a class="event-link" href="javascript:void(0)" onclick="load(\'user?id='+json.users[i].id+'\')">'+json.users[i].name+'</a>';
            if(i !== json.users.length - 1) content += ', ';
        }

        addMessage({
            message: `
                <div class="cs_header">
                    <i class="`+(getSeason() === 'snow' ? 'fas fa-snowflake' : 'fad fa-clouds')+`" style="margin-right: `+(getSeason() === 'snow' ? '5px' : '3px')+`"></i>
                    `+(getSeason() === 'rain' ? 'Дождь' : 'Снег')+`
                </div>
                <span class="special-reward">
                    <i class="fa fa-coins"></i> `+json.reward+` руб.
                </span>
                <div class="chat-bottom">
                    <div class="cs_b">
                        <span>
                            Под `+(getSeason() === 'snow' ? 'снег' : 'дождь')+` попали <i class="fas fa-question-circle fqc tooltip" title="Это событие происходит каждые 3 часа и выдает приз только тем, кто пополнял счет на 30 руб. или больше за последние 24 часа."></i>:
                            <br>
                            `+content+`
                        </span>
                    </div>
                </div>
            `,
            type: 'drop'
        });
        updateBalance();
    };

    var addMessage = function(json) {
        let season = getSeason();
        let message = $('<div class="message message-'+json.type+' '+(json.type === 'drop' ? 'chat-drop '+season : '')+' '+(json.type === 'special' ? 'message-event' : '')+'" data-message-id="'+json.id+'" data-message-from="'+json.user_id+'">' +
            (json.type === 'special' ? '<div class="special-container">' : (json.type === 'drop' ? '<div class="special-container '+season+'-container">' : '')) +
            (json.type !== 'special' && json.type !== 'drop' ? '<div class="user">' +
                '<div class="image">' +
                '<img onclick="load(\'user?id='+json.user_id+'\')" src="'+json.avatar+'" alt="">' +
                '</div>' +
                '<div class="name">' +
                '<a onclick="load(\'user?id='+json.user_id+'\')" href="javascript:void(0)">'+json.name+'</a>' +
                '<div class="badge tooltip">' +
                ((json.type === 'admin' || json.type === 'moderator') && json.type !== 'payout' ? '<i class="fad fa-shield-alt tooltip" title="Модератор"></i>' : '') +
                ($('.chat').attr('data-role') >= 2 && json.type !== 'payout' ? '<i class="fas fa-cog mod_icon" onclick="chatModMenu('+json.id+', \''+json.name+'\', '+json.user_id+')"></i>' : '') +
                ($('.chat').attr('data-role') < 2 && json.type !== 'admin' && json.type !== 'moderator' && json.type !== 'payout' ?
                    '<div class="chat-level chat-level'+json.level+'">'+json.level+'</div>' : '') +
                '</div>' +
                '</div>' : '') +
            '<div class="message_content">' +
            emojione.toImage(json.message) +
            '</div>' +
            (json.type === 'special' || json.type === 'drop' ? '</div>' : '') +
            '</div>');

        $('#chat').append(message);

        $("#chat_nano").nanoScroller();
        $("#chat_nano").nanoScroller({ scroll: 'bottom' });

        updateTooltips();
    };

    var invalidateEvents = function(image) {
        $('.message-special .cs_header').toggleClass('special_disabled', true);
        $('.message-special').find('.fa-question-circle').toggleClass('fa-question-circle', false).toggleClass('fa-check-circle', true);
        if(image !== undefined) {
            $('.message-special').last().find('img').attr('src', 'data:image/png;base64,' + image);
        }
    };

    if($('#chat_message').length > 0) {
        var s = $('#chat_message').emojioneArea({
            pickerPosition: "top",
            filtersPosition: "bottom",
            search: false,
            tones: false,
            autocomplete: false,
            hidePickerOnBlur: true,
            buttonTitle: '',
            filters: {
                recent: {
                    icon: "clock3",
                    title: "Недавние",
                },
                smileys_people: {
                    icon: "yum",
                    title: "Люди"
                },
                animals_nature: {
                    icon: "hamster",
                    title: "Природа"
                },
                food_drink: {
                    icon: "pizza",
                    title: "Еда и напитки"
                },
                activity: {
                    icon: "basketball",
                    title: "Активность"
                },
                travel_places: {
                    icon: "rocket",
                    title: "Путешествие"
                },
                objects: {
                    icon: "bulb",
                    title: "Объекты"
                },
                symbols: {
                    icon: "heartpulse",
                    title: "Символы"
                },
                flags: {
                    icon: "flag_ru",
                    title: "Флаги"
                },
            }
        });
        s[0].emojioneArea.on('keypress', function(el, e) {
            if(e.which === 13) {
                $('#chat_send').click();
                e.preventDefault();
            }
        });
        $('#chat_send').on('click', function() {
            sendChatMessage($('#chat_send').attr('data-user-id'), s[0].emojioneArea.getText());
        });
    }

    let secure = window.location.protocol === 'https:';
    socket = io('w'+(secure ? 'ss' : 's')+'://'+window.location.hostname+':2096', { transports: ['websocket'], secure: secure, rejectUnauthorized: false, query: "user_id="+$('#chat_send').attr('data-user-id') });
    socket.on('connect', function() {
        loadChatHistory();
        $('.chat_status').fadeOut('fast');
        $('#chat').fadeIn('fast');
    });

    socket.on('drop', function(msg) {
        sendDropMessage(msg);
    });

    socket.on('event', function(msg) {
        let json = JSON.parse(msg);
        invalidateEvents();

        addMessage({
            message: "<div class='cs_header'>" +
                "<i class='fas fa-question-circle' style='margin-right: 5px'></i>" +
                "Викторина" +
                "<span class='special-reward'>" +
                "<i class='fa fa-coins'></i> "+json.reward+" руб.</span>" +
                "</div>" +
                "<div class='chat-bottom'>" +
                "<div class='cs_b'>" +
                (json.image == null ? '<p>'+json.text+'</p>' : "<img alt='Произошла серверная ошибка' src='data:image/png;base64,"+json.image+"'>") +
                "</div>" +
                "</div>", type: "special"});
    });
    socket.on('event over', function(msg) {
        var json = JSON.parse(msg);

        invalidateEvents(json.answer);
        addMessage({message:
                "<div class='spec-rew'>" +
                "<div class='user'>" +
                "<div class='image'>" +
                "<img onclick=\"load('user?id="+json.user_id+"')\" src=\""+json.avatar+"\" alt=\"\">" +
                "</div>" +
                "<div class='name' style='width: unset !important'>" +
                "<a onclick=\"load('user?id="+json.user_id+"')\">"+json.name+"</a>" +
                "</div>" +
                "<br>" +
                "<span>отвечает правильно на вопрос<br>и зарабатывает</span><br><i class='fa fa-coins i_y_i'></i> "+json.reward+" руб." +
                "</div>" +
                "</div>", type: "special"});
        if(parseInt(json.user_id) === parseInt($('#chat_send').attr('data-user-id'))) updateBalance();
    });
    socket.on('event timer', function() {
        if(chat) $('.chat_event_timer').fadeIn('fast');
        if($('#online').length > 0) socket.emit('online');
    });

    socket.on('disconnect', function() {
        $('.chat_status').fadeIn('fast');
        $('.chat_event_timer').fadeOut('fast');
        $('#chat').fadeOut('fast', function() {
            $('#chat').html('');
        });
    });
    socket.on('online', function(msg) {
        $('#online').html(msg);
    });
    socket.on('remove message', function(msg) {
        $('*[data-message-from='+parseInt(msg)+']').remove();
    });
    socket.on('remove single message', function(msg) {
        $('*[data-message-id='+parseInt(msg)+']').remove();
    });
    socket.on('achievement', function(msg) {
        if(msg.id !== parseInt($('#chat_send').attr('data-user-id'))) return;
        achievement(msg.badge, msg.name, msg.description);
        updateBalance();
    });
    socket.on('event error', function(msg) {
        if(parseInt(msg) === parseInt($('#chat_send').attr('data-user-id')))
            iziToast.error({
                'icon': 'fal fa-times',
                'message': 'Отвечать на викторины можно только раз в 60 минут.',
                'position': 'bottomCenter',
                'timeout': 10000
            });
    });
    socket.on('ban', function(msg) {
        if(parseInt(msg) === parseInt($('#chat_send').attr('data-user-id'))) {
            socket.disconnect();
            window.location.reload();
        }
    });
    socket.on('chat history', function(msg) {
        msg = JSON.parse(msg);
        msg = msg.reverse();

        for(let i = 0; i < msg.length; i++) {
            let message = msg[i];
            if(message.users != null) sendDropMessage(JSON.stringify(message));
            else if(message.skip === false) addMessage(message);
        }
    });
    socket.on('chat message', function(msg) {
        let json = JSON.parse(msg);
        addMessage(json);
    });
    socket.on('live_drop', function(msg) {
        dropQueue.push(msg);
    });
});