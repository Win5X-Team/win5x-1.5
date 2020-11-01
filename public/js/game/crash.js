let __chart, __id, __t, __ds;

function init() {
    if(__chart != null) {
        __chart.destroy();
        __chart = null;
    }

    let ctx = document.getElementById('chart').getContext('2d');
    ctx.height = 673;

    __chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['0s', '3s'],
            datasets: [{
                label: '',
                data: [0, 0],
                backgroundColor: 'rgba(156,63,78,0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            bezierCurve: false,
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 30,
                    bottom: 20
                }
            },
            legend: {
                display: false
            },
            tooltips: {
                enabled: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        maxTicksLimit: 20,

                        precision: 0,
                        callback: function(value, index, values) {
                            return 'x' + value;
                        }
                    }
                }],
                xAxes: [{
                    ticks: {
                        beginAtZero: true,
                        display: false
                    }
                }]
            }
        }
    });
}

function take() {
    if(__id == null) {
        swap();
        return;
    }
    __t = -1;
    $.get('/game/crash/take/' + __id + (isDemo ? '?demo' : ''), function(data) {
        let json = JSON.parse(data);

        if(json.error != null) {
            if(json.error === -1 || json.error === 0) {
                swap();
                __id = null;
                updateBalance();
            }
            return;
        }

        $('.c_f').toggleClass('c_f-progress', false);
        $('.c_f').toggleClass('c_f-win', true);

        var profit = parseFloat(json.profit).toFixed(2);
        $('#game_profit').html((json.mul >= 1 ? 'Вы выиграли ' : '') + profit + ' руб.');

        var left = parseInt((json.crash - parseFloat(json.mul).toFixed(2)) / 0.9);
        for(var i = 0; i < left - 2; i++) {
            __chart.data.datasets[0].data.unshift(0);
            __chart.data.labels[__chart.data.labels.length] = __chart.data.labels.length*3 + 's';
        }

        var length = __chart.data.datasets[0].data.length;
        __chart.data.datasets[0].data[length-1] = parseFloat(json.crash).toFixed(2);

        for(var j = 0; j < length - 1; j++) {
            if(j < 1) continue;
            __chart.data.datasets[0].data[length-1-j] = parseFloat(__chart.data.datasets[0].data[length-1]) / (1.2*j);
        }

        __chart.update();

        if(!isDemo) {
            sendDrop(__id);
            validateTask(__id);
        }
        if(isDemo && isGuest()) showDemoTooltip();

        __id = null;
        swap();
        updateBalance();
        _disableDemo = false;
    });
}

function swap() {
    if(__id == null) {
        $('#play').attr('onclick', 'crash()');
        setBetText('Играть');
    }
    else $('#play').attr('onclick', 'take()');
}

function crash() {
    if(__ds) return;
    __ds = true;
    $.get('/game/crash/' + $('#bet').val() + (isDemo ? '?demo' : ''), function(data) {
        __ds = false;
        let json = JSON.parse(data);

        if(json.error != null) {
            if(json.error === '$') load('games');
            if(json.error === -1) $('#b_si').click();
            if(json.error === 1) iziToast.error({message: 'Минимальная ставка: 0.01 руб.', icon: 'fa fa-times'});
            if(json.error === 2) $('#_payin').click();
            return;
        }

        __id = json.id;
        swap();

        $('.c_f').toggleClass('c_f-win', false);
        $('.c_f').toggleClass('c_f-lost', false);
        $('.c_f').toggleClass('c_f-progress', true);
        $('.c_f').fadeIn('fast');

        __t = 0;
        init();
        _disableDemo = true;

        var tick = function() {
            $.get('/game/crash/tick/' + __id + (isDemo ? '?demo' : ''), function(data) {
                if(__t === -1) return;
                let json = JSON.parse(data);
                if(json.error != null) {
                    if(json.error === -1) iziToast.error({message: 'Игра не найдена.', icon: 'fa fa-times'});
                    else {
                        $('.c_f').toggleClass('c_f-progress', false);
                        $('.c_f').toggleClass('c_f-lost', true);
                        $('#game_multiplier').html('x' + parseFloat(json.error).toFixed(2));
                        $('#game_profit').html('Вы проиграли ' + json.bet + ' руб.');
                        if(!isDemo) {
                            sendDrop(__id);
                            validateTask(__id);
                        }

                        _disableDemo = false;
                        __id = null;
                        swap();
                        updateBalance();
                    }
                    return;
                }

                var mul = parseFloat(json.mul).toFixed(2);
                var profit = (parseFloat(json.bet).toFixed(2) * (mul-1)).toFixed(2);
                profit = mul < 1 ? parseFloat($('#bet').val()).toFixed(2) : profit;
                $('#game_multiplier').html('x' + mul);
                $('#game_profit').html((mul < 1 ? '': '+') + profit + ' руб.');
                if(!$('#bet_btn').html().startsWith('Забрать'))
                    setBetText('Забрать<br>'+profit+' руб.');
                else $('#bet_btn').html('Забрать<br>'+profit+' руб.');

                var length = __chart.data.datasets[0].data.length;
                if(__chart.data.datasets[0].data[length-1] >= 20) return;
                __chart.data.datasets[0].data[length-1] += 0.033;

                for(var i = 0; i < length - 1; i++) {
                    if(i < 1) continue;
                    __chart.data.datasets[0].data[length-1-i] = parseFloat(__chart.data.datasets[0].data[length-i]) / 2.0;
                }
                __chart.update();

                __t += 100;
                setTimeout(tick, 50);

                if(__t >= 3000 && __t % 3000 === 0) {
                    if(__chart.data.labels.length*3 > 60) return;
                    __chart.data.labels[__chart.data.labels.length] = __chart.data.labels.length*3 + 's';
                    __chart.data.datasets[0].data.unshift(0);
                    __chart.update();
                }
                if(parseFloat(mul) > 20) take();
            });
        };
        tick();
    });
}

$(document).ready(function() {
    init();
});