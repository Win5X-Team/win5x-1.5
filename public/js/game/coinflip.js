var game = null, progress = false, cancelText = false;

function coinflip() {
    if(game != null || progress === true) return;
    $.get('/game/coinflip/' + $('#bet').val() + (isDemo ? '?demo' : ''), function(data) {
        var json = JSON.parse(data);

        if(json.error != null) {
            if(json.error === '$') load('games');
            if(json.error === -1) $('#b_si').click();
            if(json.error === 0) iziToast.error({message: 'Не удалось найти игру.', icon: 'fa fa-times'});
            if(json.error === 1) iziToast.error({message: 'Минимальная ставка: 0.01 руб.', icon: 'fa fa-times'});
            if(json.error === 2) $('#_payin').click();
            return;
        }

        $('.cf_status').fadeOut('fast');

        $('.coin').toggleClass('game-disabled', false).fadeIn('fast');

        $('#play').fadeOut('fast').attr('onclick', 'take()');
        updateBalance();

        game = json;
        _disableDemo = true;
    });
}

function flip(side) {
    if(game == null || progress === true) return;
    $.get('/game/coinflip/flip/' + game.id + '/' + side + (isDemo ? '?demo' : ''), function(data) {
        var json = JSON.parse(data);

        if(json.error != null) {
            if(json.error === -1) iziToast.error({message: 'Игра не найдена.', icon: 'fa fa-times'});
            if(json.error === 0) console.log('Server cancelled input');
            return;
        }

        progress = true;

        $('#coin').attr('class', '');
        setTimeout(function() {
            $('#coin').toggleClass('heads', json.side === 'red' && !isQuick);
            $('#coin').toggleClass('tails', json.side === 'black' && !isQuick);
            $('#coin').toggleClass('quick-heads', json.side === 'red' && isQuick);
            $('#coin').toggleClass('quick-tails', json.side === 'black' && isQuick);

            var mul = parseFloat(json.multiplier).toFixed(2);
            var decimal = [
                ((mul > 0) ? Math.floor(mul) : Math.ceil(mul)).toFixed(2).split('.')[0],
                (mul % 1).toFixed(2).split('.')[1]
            ];

            setTimeout(function() {
                progress = false;
                if(json.status === 'lose') {
                    $('#cf_status_text').html('Вы проиграли ' + game.bet + ' руб.');
                    $('.ribbon-wide').toggleClass('win-ribbon', false).toggleClass('lose-ribbon', true);
                    $('.cf_status').fadeIn('fast');

                    if(!isDemo) {
                        sendDrop(game.id);
                        validateTask(game.id);
                    }
                    clear();
                    updateBalance();
                } else {
                    $('#games').prop('number', p_n('#games')).animateNumber({ number: json.games });
                    $('#mul').prop('number', p_n('#mul')).animateNumber({ number: decimal[0] });
                    $('#mul_m').prop('number', p_n('#mul_m')).animateNumber({ number: decimal[1] });

                    $('.cf_history').prepend('<div class="cf cf_'+json.side+'"></div>');

                    let v = (parseFloat(game.bet) * parseFloat(json.multiplier)).toFixed(2);
                    if(!cancelText) {
                        setBetText('Забрать<br><span id="cf_profit">'+v+'</span> руб.');
                        cancelText = true;
                    } else $('#cf_profit').html(v);
                }
            }, isQuick ? 300 : 3000);
        }, 100);
    });
}

function take() {
    if(game == null || progress === true) return;
    $.get('/game/coinflip/take/' + game.id + (isDemo ? '?demo' : ''), function(data) {
        var json = JSON.parse(data);

        if(json.error != null) {
            if(json.error === -1)
                iziToast.error({message: 'Требуется авторизация.', icon: 'fa fa-times'});
            if(json.error === 0) console.log('Server cancelled input');
            if(json.error === 1)
                iziToast.error({message: 'Игра не найдена.', icon: 'fa fa-times'});
            return;
        }

        if(!isDemo) {
            sendDrop(game.id);
            validateTask(game.id);
        }
        if(isDemo && isGuest()) showDemoTooltip();

        clear();
        updateBalance();

        if(parseFloat(json.profit) > 0) {
            $('#cf_status_text').html('Вы выиграли ' + parseFloat(json.profit).toFixed(2) + ' руб.');
            $('.ribbon-wide').toggleClass('win-ribbon', true).toggleClass('lose-ribbon', false);
            $('.cf_status').fadeIn('fast');
        }
    });
}

function clear() {
    game = null;
    progress = false;
    cancelText = false;
    _disableDemo = false;

    setBetText('Играть');
    $('#play').attr('onclick', 'coinflip()');
    $('.coin').toggleClass('game-disabled', true).fadeOut('fast');

    $('#games').animateNumber({ number: 0 });
    $('#mul').animateNumber({ number: 0 });
    $('#mul_m').animateNumber({ number: 0 });
    $('.cf_history').html('');
}

$(document).ready(clear);