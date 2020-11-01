// javascript-obfuscator:disable
var ctx, refCtx, b_disabled;
var segment;
var options = {
    1: { sum: 0.01, color: '#070707' },
    2: { sum: 0.05, color: '#070707' },
    3: { sum: 0.10, color: '#070707' },
    4: { sum: 0.15, color: '#070707' },
    5: { sum: 0.01, color: '#070707' },
    6: { sum: 0.05, color: '#070707' },
    7: { sum: 0.10, color: '#070707' },
    8: { sum: 0.15, color: '#070707' },
    9: { sum: 0.01, color: '#070707' },
    10: { sum: 0.05, color: '#070707' },
    11: { sum: 0.10, color: '#070707' },
    12: { sum: 0.15, color: '#070707' },
    13: { sum: 0.01, color: '#070707' },
    14: { sum: 0.05, color: '#070707' },
    15: { sum: 0.10, color: '#070707' },

    toString: function(option, a) {
        return parseFloat(option.sum + a).toFixed(2) + ' руб.';
    }
};
var referralOptions = {
    1: { sum: 5.00, color: '#070707' },
    2: { sum: 10.00, color: '#070707' },
    3: { sum: 15.50, color: '#070707' },
    4: { sum: 30.00, color: '#070707' },
    5: { sum: 5.00, color: '#070707' },
    6: { sum: 10.00, color: '#070707' },
    7: { sum: 15.50, color: '#070707' },
    8: { sum: 30.00, color: '#070707' },
    9: { sum: 5.00, color: '#070707' },
    10: { sum: 10.00, color: '#070707' },
    11: { sum: 15.50, color: '#070707' },
    12: { sum: 30.00, color: '#070707' },
    13: { sum: 5.00, color: '#070707' },
    14: { sum: 10.00, color: '#070707' },
    15: { sum: 15.50, color: '#070707' },

    toString: function(option) {
        return option.sum + ' руб.';
    }
};
var bonusTimer;
var c = null;
// javascript-obfuscator:enable

function spin_ref() {
    if(b_disabled) return;
    $.get('/api/ref_bonus', function(response) {
        let json = JSON.parse(response);

        if(parseInt(response) === 0) {
            iziToast.error({message: 'Для прокрутки этого колеса требуется 10 активных рефералов.', icon: 'fa fa-times', position: 'bottomCenter'});
            return;
        }

        b_disabled = true;
        $('#ref_block').fadeOut('fast', function() {
            refCtx.stopAnimation(false);
            refCtx.rotationAngle = 0;
            refCtx.draw();

            segment = json.segment;

            refCtx.animation.stopAngle = refCtx.segments[segment].startAngle + ((refCtx.segments[segment].endAngle - refCtx.segments[segment].startAngle) / 2);
            refCtx.startAnimation();
        });
    });
}

function spin_bonus() {
    if(b_disabled) return;
    $.get('/api/bonus', function(response) {
        let json = JSON.parse(response);

        if(parseInt(response) === -1) {
            iziToast.error({
                message: 'Вступите в группу ВКонтакте для получения бонуса.<br><a href="javascript:void(0)" onclick="window.open(\'https://vk.com/playintm\', \'_blank\')" class="ll">Перейти</a>',
                timeout: false,
                icon: 'fa fa-times',
                position: 'bottomCenter',
                theme: 'dark',
                backgroundColor: '#222120'
            });
            return;
        }
        if(parseInt(response) === 0) {
            iziToast.error({message: 'Бонус доступен только при нулевом балансе!', icon: 'fa fa-times', position: 'bottomCenter'});
            return;
        }
        if(json.time != null) {
            var left = parseInt(json.time)-1;
            if(bonusTimer == null) {
                bonusTimer = setInterval(function () {
                    left -= 1;
                    $.each($('.iziToast-message'), function (i, e) {
                        if ($(e).html().indexOf('До') !== 0) return;
                        $(e).html('До следующего бонуса: ' + fmtMSS(left));
                    });
                    if (left <= 0) {
                        clearInterval(bonusTimer);
                        bonusTimer = null;
                        b_disabled = false;
                    }
                }, 1000);
                iziToast.info({
                    message: 'До следующего бонуса: ' + fmtMSS(left),
                    timeout: left * 1000,
                    resetOnHover: false,
                    icon: "fa fa-info",
                    onClosing: function(){
                        clearInterval(bonusTimer);
                        bonusTimer = null;
                        b_disabled = false;
                    }
                });
            }
            return;
        }

        b_disabled = true;
        $('#wheel_block').fadeOut('fast', function() {
            ctx.stopAnimation(false);
            ctx.rotationAngle = 0;
            ctx.draw();

            segment = json.segment;
            ctx.animation.stopAngle = ctx.segments[segment].startAngle + ((ctx.segments[segment].endAngle - ctx.segments[segment].startAngle) / 2);
            ctx.startAnimation();
        });
    });
}

function resetReloadText(text) {
    $('#reload_text').fadeOut(125, function() {
        $(this).delay(125).removeAttr('style').html(text).fadeIn(125);
        $('#reload_hint').delay(125*2).fadeIn(125);
    });
}

function resetRefReloadText(text) {
    $('.ref_reload_text').fadeOut(125, function() {
        $(this).delay(125).removeAttr('style').html(text).fadeIn(125);
        $('#ref_reload_hint').delay(125*2).fadeIn(125);
    });
}

function stop() {
    b_disabled = false;

    updateBalance(function(data) {
        $('#reload_hint').hide();
        $('#reload_text').html('+' + data + ' руб.');
        $('#reload_text').css({color: '#3dd343'});
        $('#wheel_block').fadeIn('fast');

        setTimeout(function() {
            resetReloadText('3 мин');
        }, 2000);
    });
}

function referralStop() {
    b_disabled = false;

    updateBalance(function(data) {
        $('#ref_reload_hint').hide();
        $('.ref_reload_text').html('+' + data + ' руб.');
        $('.ref_reload_text').css({color: '#3dd343'});
        $('#ref_block').fadeIn('fast');

        setTimeout(function() {
            $.get('/get_active_refs', function(response) {
                let num = parseInt(response);
                if(num > 10) num = 10;
                if(num < 0) num = 0;
                resetRefReloadText(num+'/10');
            });
        }, 2000);
    });
}

function setTab(sel) {
    if(sel === c) return;
    c = sel;

    $('*[data-tab]').toggleClass("reward_active", false);
    $('*[data-tab='+sel+']').toggleClass("reward_active", true);

    $('*[data-s-tab]').toggleClass("m-game-selection-item-active", false);
    $('*[data-s-tab='+sel+']').toggleClass("m-game-selection-item-active", true);

    $('.bonus_tab').hide();
    $('#'+sel).fadeIn('fast');
}

function activatePromo(code) {
    if(code === null || code === undefined || code.length < 1) {
        iziToast.error({message: 'Введите промокод.', icon: 'fa fa-times', position: 'bottomCenter'});
        return;
    }
    $.get('/promo/' + code, function(data) {
        let json = JSON.parse(data);

        if(json.error != null) {
            if(json.error === -1) $('#b_si').click();
            if(json.error === 0) iziToast.error({message: 'Данный промокод больше не действителен.', icon: 'fa fa-times', position: 'bottomCenter'});
            if(json.error === 1) iziToast.error({message: 'Вы уже использовали данный промокод.', icon: 'fa fa-times', position: 'bottomCenter'});
            if(json.error === 2) iziToast.error({message: 'Вы уже использовали реферальный код!', icon: 'fa fa-times', position: 'bottomCenter'});
            if(json.error === 3) iziToast.error({message: 'Вы не можете использовать свой промокод.', icon: 'fa fa-times', position: 'bottomCenter'});
            if(json.error === 4) iziToast.error({message: 'Такого промокода не существует.', icon: 'fa fa-times', position: 'bottomCenter'});
            if(json.error === 5) iziToast.error({message: 'Вы можете активировать только 2 промокода от партнеров Win5x в день.', icon: 'fa fa-times', position: 'bottomCenter'});
            if(json.error === 6) iziToast.error({message: 'Для активации промокода на балансе должно быть менее 15 руб.', icon: 'fa fa-times', position: 'bottomCenter'});
            return;
        }

        iziToast.success({message: 'Вы успешно активировали промокод на сумму ' + json.result + ' руб.', icon: false, position: 'bottomCenter'});
        $('.promoDlg .iziToast-close').click();
        updateBalance();
    });
}

$(document).ready(function() {
    const check = () => {
        if (!('serviceWorker' in navigator)) {
            console.error('No Service Worker support!');
            return;
        }
        if (!('PushManager' in window)) {
            console.error('No Push API Support!');
            return;
        }
        $('#notificationOption').fadeIn('fast');
    };

    const registerServiceWorker = async () => {
        return await navigator.serviceWorker.register('js/service.js');
    };

    const requestNotificationPermission = async () => {
        let overlay = $('<div class="notificationOverlay" style="display: none"></div>');
        $('body').prepend(overlay);

        if(Notification.permission !== 'denied' && Notification.permission !== 'granted') overlay.fadeIn('fast');

        const permission = await window.Notification.requestPermission();
        // value of permission can be 'granted', 'default', 'denied'
        // granted: user has accepted the request
        // default: user has dismissed the notification permission popup by clicking on x
        // denied: user has denied the request.
        overlay.fadeOut('fast', function() {
            $(this).remove();
        });
        if(permission !== 'granted') {
            console.error('Permission not granted for Notification');
            iziToast.error({
                'icon': 'fas fa-times',
                'message': 'Вы отказались от принятия уведомлений.<br>Эту настройку можно изменить в настройках уведомлений сайта браузера.',
                'timeout': 7500,
                'position': 'bottomCenter'
            })
        } else {
            if($('#notificationOption .bonus_option_ok').length !== 0) return;
            $.get('/notifyBonus', function(response) {
                updateBalance();
                console.log(response);
            });
            $('#notificationOption').prepend(`
                <div class="bonus_option_ok">
                    <i class="fal fa-check"></i>
                    <span>Вы выполнили это задание.</span>
                </div>`);
        }
    };

    const main = async () => {
        check();
        await registerServiceWorker();
        $('#notificationOption').on('click', async () => {
            await requestNotificationPermission();
        });
    };

    main();

    $.get('/get_additional_bonus', function(response) {
        let a = parseFloat(response);
        
        ctx = new Winwheel({
            'canvasId': 'canvas',
            'lineWidth': 6,
            'numSegments': 15,
            'segments': [
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[1], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[1].color},
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[2], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[2].color},
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[3], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[3].color},
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[4], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[4].color},
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[5], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[5].color},
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[6], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[6].color},
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[7], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[7].color},
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[8], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[8].color},
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[9], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[9].color},
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[10], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[10].color},
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[11], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[11].color},
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[12], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[12].color},
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[13], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[13].color},
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[14], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[14].color},
                {'fillStyle': '#0d0d0d', 'text': options.toString(options[15], a), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': options[15].color}
            ],
            'innerRadius': 100,
            'animation': {
                'type': 'spinToStop',
                'duration': 5,
                'spins': 15,
                'callbackFinished': 'stop()'
            },
            'responsive': false
        });
        refCtx = new Winwheel({
            'canvasId': 'ref_canvas',
            'lineWidth': 6,
            'numSegments': 15,
            'segments': [
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[1]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[1].color},
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[2]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[2].color},
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[3]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[3].color},
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[4]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[4].color},
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[5]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[5].color},
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[6]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[6].color},
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[7]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[7].color},
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[8]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[8].color},
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[9]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[9].color},
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[10]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[10].color},
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[11]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[11].color},
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[12]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[12].color},
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[13]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[13].color},
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[14]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[14].color},
                {'fillStyle': '#0d0d0d', 'text': referralOptions.toString(referralOptions[15]), 'textStrokeStyle' : '#FFFFFF', 'strokeStyle': referralOptions[15].color}
            ],
            'innerRadius': 100,
            'animation': {
                'type': 'spinToStop',
                'duration': 5,
                'spins': 15,
                'callbackFinished': 'referralStop()'
            },
            'responsive': false
        });
        $.get('/get_active_refs', function(response) {
            let num = parseInt(response);
            if(num > 10) num = 10;
            if(num < 0) num = 0;
            resetRefReloadText(num+'/10');
        });

        setTab('wheel');
    });
});