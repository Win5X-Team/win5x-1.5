var options = ["green", "red", "black", "red", "black", "red", "black", "red", "black", "red", "black", "red", "black", "red", "black"];

var disabled;
var selected_color = null;
var __json;

var ctx;

$(document).ready(function() {
    disabled = false;
    ctx = new Winwheel({
        'canvasId': 'canvas',
        'lineWidth': 0,
        'numSegments': options.length,
        'segments': [
            {'fillStyle': '#37b546', 'strokeStyle': '#070707'},
            {'fillStyle': '#e86376', 'strokeStyle': '#070707'},
            {'fillStyle': '#1c2028', 'strokeStyle': '#070707'},
            {'fillStyle': '#e86376', 'strokeStyle': '#070707'},
            {'fillStyle': '#1c2028', 'strokeStyle': '#070707'},
            {'fillStyle': '#e86376', 'strokeStyle': '#070707'},
            {'fillStyle': '#1c2028', 'strokeStyle': '#070707'},
            {'fillStyle': '#e86376', 'strokeStyle': '#070707'},
            {'fillStyle': '#1c2028', 'strokeStyle': '#070707'},
            {'fillStyle': '#e86376', 'strokeStyle': '#070707'},
            {'fillStyle': '#1c2028', 'strokeStyle': '#070707'},
            {'fillStyle': '#e86376', 'strokeStyle': '#070707'},
            {'fillStyle': '#1c2028', 'strokeStyle': '#070707'},
            {'fillStyle': '#e86376', 'strokeStyle': '#070707'},
            {'fillStyle': '#1c2028', 'strokeStyle': '#070707'}
        ],
        'animation': {
            'type': 'spinToStop',
            'duration': 5,
            'spins': options.length,
            'callbackFinished': 'stop()'
        },
        'innerRadius': 270
    });
});

function stop() {
    disabled = false;

    var header = __json.result === false ? "0.00x" : (__json.result === 'green' ? "14.00x" : "2.00x");
    var text = __json.result === false ? "Вы проиграли" : "Вы выиграли";
    text += " " + __json.profit.substr(1) + " руб.";

    if(__json.id !== -1 && !isDemo) sendDrop(__json.id);
    if(__json.result === true && isDemo && isGuest()) showDemoTooltip();

    setTimeout(function() {
        if(__json.result === true) $('.wheel_game_result').toggleClass('wg_win', true);
        else $('.wheel_game_result').toggleClass('wg_lose', true);
    }, 200);
    $('.wheel_game_result .mul').html(header);
    $('.wheel_game_result .te').html(text);
    $('.wheel_game_result').fadeIn('fast');

    updateBalance();
}

function wheel() {
    if(selected_color == null) {
        iziToast.error({message: 'Выберите цвет.', icon: 'fa fa-times'});
        return;
    }
    if(disabled === true) return;

    $.get('/game/wheel/' + $('#bet').val() + '/' + selected_color + (isDemo ? '?demo' : ''), function(data) {
        let json = JSON.parse(data);
        __json = json;

        if(json.error != null) {
            if(json.error === '$') load('games');
            if(json.error === -1) $('#b_si').click();
            if(json.error === 0) iziToast.error({message: 'Выберите цвет.', icon: 'fa fa-times'});
            if(json.error === 1) iziToast.error({message: 'Минимальная ставка: 0.01 руб.', icon: 'fa fa-times'});
            if(json.error === 2) $('#_payin').click();
            return;
        }

        if(!isDemo) updateBalance(undefined, -parseFloat($('#bet').val()));

        $('.wheel_game_result').fadeOut('fast', function() {
            $(this).toggleClass('wg_win', false).toggleClass('wg_lose', false);
        });
        disabled = true;

        ctx.stopAnimation(false);
        ctx.rotationAngle = 0;
        ctx.draw();

        ctx.animation.duration = isQuick ? 0.05 : 5;
        ctx.animation.stopAngle = ctx.segments[json.segment + 1].startAngle + ((ctx.segments[json.segment + 1].endAngle - ctx.segments[json.segment + 1].startAngle) / 2);
        ctx.startAnimation();
    });
}

function pickColor(col) {
    selected_color = col;
    __profit();

    $('#w_color').removeClass('bet_profit-error');
    $('#w_color').html(col === 'green' ? 'Зеленый' : (col === 'red' ? 'Красный' : 'Черный'));
    $('*[data-wheel-color]').toggleClass('w_b_active', false);
    $('*[data-wheel-color="'+col+'"]').toggleClass('w_b_active', true);
}