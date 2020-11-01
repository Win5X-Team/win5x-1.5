let multipliers = {
    1: [0, 3.8],
    2: [0, 1.7, 5.2],
    3: [0, 0, 2.7, 48],
    4: [0, 0, 1.7, 10, 84],
    5: [0, 0, 1.4, 4, 14, 290],
    6: [0, 0, 0, 3, 9, 160, 720],
    7: [0, 0, 0, 2, 7, 30, 280, 800],
    8: [0, 0, 0, 2, 4, 10, 50, 300, 850],
    9: [0, 0, 0, 2, 2.5, 4.5, 12, 60, 320, 900],
    10: [0, 0, 0, 1.5, 2, 4, 6, 22, 80, 400, 1000]
};

let clicked = [], kenoSlickInit = false, kenoDisabled = false;

function keno() {
    if(kenoDisabled) return;
    if(clicked.length === 0) {
        iziToast.error({message: 'Выберите от 1 до 10 клеток.', icon: 'fa fa-times', position: 'bottomCenter'});
        return;
    }

    $('.keno-picked').removeClass('keno-picked');
    $('.keno-correct').removeClass('keno-correct');

    kenoDisabled = true;

    $.get('/game/keno/'+JSON.stringify(clicked)+'/'+$('#bet').val()+(isDemo ? '?demo' : ''), function(response) {
        let json = JSON.parse(response);
        if(json.error != null) {
            if(json.error === '$') load('games');
            if(json.error === -1) $('#b_si').click();
            if(json.error === 0) iziToast.error({message: 'Максимальное количество клеток - 10', icon: 'fa fa-times', position: 'bottomCenter'});
            if(json.error === 1) iziToast.error({message: 'Минимальная ставка: 0.01 руб.', icon: 'fa fa-times', position: 'bottomCenter'});
            if(json.error === 2) $('#_payin').click();
            kenoDisabled = false;
            return;
        }

        if(!isDemo) updateBalance(undefined, -parseFloat($('#bet').val()));

        let exec = function(interval, func) {
            setTimeout(function() {
                func(1);
                setTimeout(function() {
                    func(2);
                    setTimeout(function() {
                        func(3);
                        setTimeout(function() {
                            func(4);
                            setTimeout(function() {
                                func(5);
                                setTimeout(function() {
                                    func(6);
                                    setTimeout(function() {
                                        func(7);
                                        setTimeout(function() {
                                            func(8);
                                            setTimeout(function() {
                                                func(9);
                                                setTimeout(function() {
                                                    func(10);
                                                }, interval);
                                            }, interval);
                                        }, interval);
                                    }, interval);
                                }, interval);
                            }, interval);
                        }, interval);
                    }, interval);
                }, interval);
            }, interval);
        };

        exec(100, function(index) {
            $('[data-keno-id="'+json.grid[index - 1]+'"]').addClass('keno-picked');
            if(index === 10) {
                updateBalance();
                kenoDisabled = false;

                if(!isDemo) {
                    sendDrop(json.id);
                    validateTask(json.id);
                }
            }
        });
        exec(110, function(index) {
            if(json.correct[index - 1] === undefined) return;
            $('[data-keno-id="'+json.correct[index - 1]+'"]').addClass('keno-correct');
        });
    });
}

function displayMultiplier() {
    if(kenoSlickInit) $('#cf_slick').slick("unslick");
    kenoSlickInit = true;

    $('#cf_slick').html('');
    for(let i = 0; i < multipliers[clicked.length].length; i++) {
        $('#cf_slick').append('<div data-keno-multiplier="'+(i)+'">' +
            '<p><i class="fad fa-octagon"></i> '+(i)+'</p>' +
            '<span>x' + multipliers[clicked.length][i] + '</span>' +
        '</div>');
    }

    $('#cf_slick').slick({
        infinite: false,
        slidesToShow: 6,
        slidesToScroll: 6,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 6,
                    slidesToScroll: 6
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            }
        ]
    });
}

$(document).ready(function() {
    $('[data-keno-id]').on('click', function() {
        if(kenoDisabled) return;

        $('.keno-picked').removeClass('keno-picked');
        $('.keno-correct').removeClass('keno-correct');

        let id = $(this).attr('data-keno-id');

        if(clicked.includes(id)) {
            $(this).toggleClass('keno_active', false);
            clicked = clicked.filter(e => e !== id);
        } else {
            if(clicked.length >= 10) return;

            $(this).toggleClass('keno_active', true);
            clicked.push(id);
        }

        displayMultiplier();
    })
});