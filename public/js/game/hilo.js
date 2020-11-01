var game = null, progress = false, cancelText = false, startingCardIndex = 1;

function hilo() {
    if(game != null || progress === true) return;
    $.get('/game/hilo/' + $('#bet').val() + '/' + startingCardIndex + (isDemo ? '?demo' : ''), function(data) {
        var json = JSON.parse(data);

        if(json.error != null) {
            if(json.error === '$') load('games');
            if(json.error === -1) $('#b_si').click();
            if(json.error === 1) iziToast.error({message: 'Минимальная ставка: 0.01 руб.', icon: 'fa fa-times'});
            if(json.error === 2) $('#_payin').click();
            if(json.error === 3) {
                replace();
                hilo();
            }
            return;
        }

        calculateProbability(startingCardIndex);

        $('.hilo-replace').fadeOut('fast');
        $('.cf_status').fadeOut('fast');
        $('.hilo-select').fadeIn('fast');

        $('#play').fadeOut('fast').attr('onclick', 'take()');
        updateBalance();

        game = json;
        _disableDemo = true;
    });
}

function flip(type) {
    if(game == null || progress === true) return;
    $.get('/game/hilo/flip/' + game.id + '/' + type + (isDemo ? '?demo' : ''), function(data) {
        var json = JSON.parse(data);

        if(json.error != null) {
            if(json.error === -1) iziToast.error({message: 'Игра не найдена.', icon: 'fa fa-times'});
            if(json.error === 0) console.log('Server cancelled input');
            return;
        }

        progress = true;

        let mul = parseFloat(json.multiplier).toFixed(2);
        let decimal = splitDecimal(mul);

        let deck_index = parseFloat(json.deck_index);
        startingCardIndex = deck_index;
        setCard(deck[deck_index]);

        setTimeout(function() {
            progress = false;
            if(json.win === false) {
                setTimeout(function() {
                    $('#cf_status_text').html('Вы проиграли ' + game.bet + ' руб.');
                    $('.ribbon-wide').toggleClass('win-ribbon', false).toggleClass('lose-ribbon', true);
                    $('.cf_status').fadeIn('fast');

                    if(!isDemo) {
                        sendDrop(game.id);
                        validateTask(game.id);
                    }
                    clear();
                    updateBalance();
                    clearHistory();
                }, 400);
            } else {
                calculateProbability(deck_index);

                $('#games').prop('number', p_n('#games')).animateNumber({ number: json.games });
                $('#mul').prop('number', p_n('#mul')).animateNumber({ number: decimal[0] });
                $('#mul_m').prop('number', p_n('#mul_m')).animateNumber({ number: decimal[1] });

                let v = (parseFloat(game.bet) * parseFloat(json.multiplier)).toFixed(2);
                if(v < 0) v = '0.00';
                if(!cancelText) {
                    setBetText('Забрать<br><span id="cf_profit">'+v+'</span> руб.');

                    $('#cf_profit').toggleClass('bet_profit-error', parseFloat(v) <= 0);

                    cancelText = true;
                } else $('#cf_profit').html(v);
            }
        }, 600);
    });
}

function take() {
    if(game == null || progress === true) return;
    $.get('/game/hilo/take/' + game.id + (isDemo ? '?demo' : ''), function(data) {
        var json = JSON.parse(data);

        if(json.error != null) {
            if(json.error === -1) iziToast.error({message: 'Требуется авторизация.', icon: 'fa fa-times'});
            if(json.error === 0) console.log('Server cancelled input');
            if(json.error === 1) iziToast.error({message: 'Игра не найдена.', icon: 'fa fa-times'});
            return;
        }

        if(!isDemo) {
            sendDrop(game.id);
            validateTask(game.id);
        }
        if(isDemo && isGuest()) showDemoTooltip();

        clear();
        updateBalance();
        clearHistory();

        if(parseFloat(json.profit) > 0) {
            $('#cf_status_text').html('Вы выиграли ' + parseFloat(json.profit).toFixed(2) + ' руб.');
            $('.ribbon-wide').toggleClass('win-ribbon', true).toggleClass('lose-ribbon', false);
            $('.cf_status').fadeIn('fast');
        }
    });
}

function calculateProbability(cardIndex) {
    let higherProbability = (deck[cardIndex].slot/14) * 100;
    let decimal = splitDecimal(higherProbability);
    let lowerProbability = (100 - higherProbability);
    let lowerDecimal = splitDecimal(lowerProbability);

    let calculateMultiplier = function(isHigher) {
        return !isHigher ? (12.350 / (13 - (deck[cardIndex].slot - 1))) : (12.350 / (deck[cardIndex].slot));
    };
    let higherMultiplier = calculateMultiplier(true), lowerMultiplier = calculateMultiplier(false);
    let higherMultiplierDecimal = splitDecimal(higherMultiplier), lowerMultiplierDecimal = splitDecimal(lowerMultiplier);

    $('#chance-h_ma').animateNumber({number: lowerDecimal[0]});
    $('#chance-h_mi').animateNumber({number: lowerDecimal[1]});
    $('#chance-l_ma').animateNumber({number: decimal[0]});
    $('#chance-l_mi').animateNumber({number: decimal[1]});

    $('#mul-h_ma').animateNumber({number: lowerMultiplierDecimal[0]});
    $('#mul-h_mi').animateNumber({number: lowerMultiplierDecimal[1]});
    $('#mul-l_ma').animateNumber({number: higherMultiplierDecimal[0]});
    $('#mul-l_mi').animateNumber({number: higherMultiplierDecimal[1]});
}

function clear() {
    game = null;
    progress = false;
    cancelText = false;
    _disableDemo = false;

    $('.hilo-replace').fadeIn('fast');
    setBetText('Играть');
    $('#play').attr('onclick', 'hilo()');
    $('.hilo-select').fadeOut('fast');

    $('#games').animateNumber({ number: 0 });
    $('#mul').animateNumber({ number: 0 });
    $('#mul_m').animateNumber({ number: 0 });

    $('#chance-h_ma').animateNumber({ number: 0 });
    $('#chance-h_mi').animateNumber({ number: 0 });
    $('#chance-l_ma').animateNumber({ number: 0 });
    $('#chance-l_mi').animateNumber({ number: 0 });
    $('#mul-h_ma').animateNumber({ number: 0 });
    $('#mul-h_mi').animateNumber({ number: 0 });
    $('#mul-l_ma').animateNumber({ number: 0 });
    $('#mul_l-mi').animateNumber({ number: 0 });
}

function addToHistory(card) {
    let isRed = card.type === 'hearts' || card.type === 'diamonds';
    let $e = $('<div class="card_history '+(isRed ? 'card_history_red' : 'card_history_black')+'">' +
        '<div>'+card.value+'</div>' +
        '<i class="'+deck.toIcon(card)+'"></i>' +
        '</div>').hide();
    $('.cf_history').prepend($e);
    $e.fadeIn('fast');
}

function setCard(card) {
    if(card === undefined) card = deck[1];

    $('.hilo-card-value').fadeOut('fast', function() {
        $(this).html(card.value);
        $(this).fadeIn('fast');
    });

    $('#card_icon').fadeOut('fast', function() {
        $('#card_icon').attr('class', deck.toIcon(card));
        $('#card_icon').fadeIn('fast');

        let isRed = card.type === 'hearts' || card.type === 'diamonds';
        $('.hilo_card').toggleClass('card_history_red', isRed);
        $('.hilo_card').toggleClass('card_history_black', !isRed);
    });

    addToHistory(card);

    $('#higher').fadeOut('fast', function() {
        let same = (startingCardIndex % 13) + 1 === 1;
        $('#higher').html(same ? 'Та же' : 'Выше или та же');
        $('#higher').fadeIn('fast');
    });
    $('#lower').fadeOut('fast', function() {
        let same = (startingCardIndex % 13) + 1 === 2;
        $('#lower').html(same ? 'Та же' : 'Ниже или та же');
        $('#lower').fadeIn('fast');
    });
}

function clearHistory() {
    $.each($('.cf_history .card_history'), function(i, e) {
        $(e).fadeOut('fast', function() {
            $(e).remove();
        });
    });
}

function replace() {
    if(game != null) return;
    $('.cf_status').fadeOut('fast');
    clearHistory();

    let req = function() {
        let rng = Math.floor(Math.random() * (Object.keys(deck).length - 1)) + 1;
        let card = deck[rng];
        if(card === undefined || card.slot === 1 || card.slot === 13) {
            req();
            return;
        }

        startingCardIndex = rng;
        setCard(card);
    };

    req();
}

$(document).ready(function() {
    clear();
    replace();
});