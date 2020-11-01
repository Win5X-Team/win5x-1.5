var bombs = 3;
var disabled = true, play_disabled = false, cancelText = false;

var game_safe_left = 25 - bombs, game_step = 0, slickInit = false, game_id = null;

function mines() {
    if(!disabled || play_disabled) return;

    $.get('/game/mines/' + $('#bet').val() + '/' + bombs + (isDemo ? '?demo' : ''), function(response) {
        var json = JSON.parse(response);

        if(json.error != null) {
            if(json.error === '$') load('games');
            if(json.error === -1) $('#b_si').click();
            if(json.error === 0) iziToast.error({message: 'Не удалось найти игру.', icon: 'fa fa-times'});
            if(json.error === 1) iziToast.error({message: 'Минимальная ставка: 0.01 руб.', icon: 'fa fa-times'});
            if(json.error === 2) $('#_payin').click();
            if(json.error === 3) iziToast.error({message: 'Количество мин: от 2 до 24', icon: 'fa fa-times'});
            return;
        }

        clear();
        $('#play').fadeOut('fast').attr('onclick', 'take()');
        updateBalance();

        $('*[data-diamond]').toggleClass('cf_active', false);
        
        swap(false);
        game_id = json.id;
        _disableDemo = true;
    });
}

function take() {
    if(disabled) return;

    $.get('/game/mines/take/' + game_id + (isDemo ? '?demo' : ''), function(data) {
        var json = JSON.parse(data);

        if(json.error != null) {
            if(json.error === -1) iziToast.error({message: 'Требуется авторизация.', icon: 'fa fa-times'});
            if(json.error === 0) console.log('Server cancelled input');
            if(json.error === 1) iziToast.error({message: 'Игра не найдена.', icon: 'fa fa-times'});
            return;
        }

        if(!isDemo) {
            sendDrop(game_id);
            validateTask(game_id);
        }
        if(isDemo && isGuest()) showDemoTooltip();

        swap(true);
        clear();
        updateBalance();
        displayGrid(json.grid);
        _disableDemo = false;
    });
}

function setStatus(num) {
    if(num === undefined) num = 25;
    $('#bomb').prop('number', p_n('#bomb')).animateNumber({ number: bombs });
    $('#safe').prop('number', p_n('#safe')).animateNumber({ number: num - bombs });
}

function clear() {
    setStatus();
    game_safe_left = 25 - bombs;
    game_step = 0;
    $('*[data-grid-id]').removeAttr('class');
    $('*[data-grid-id]').toggleClass('mine_disabled', true);

    setBetText('Играть');
    $('#play').attr('onclick', 'mines()');
    game_id = null;
    cancelText = false;
}

function swap(disable) {
    disabled = disable;
    $('*[data-grid-id]').toggleClass('mine_disabled', disable);
}

function clear_c() {
    $.get('/game/mines/mul/' + bombs, function(msg) {
        var json = JSON.parse(msg);

        if(slickInit) $('#cf_slick').slick("unslick");
        slickInit = true;

        $('#cf_slick').html('');

        for(let i = 0; i < 25 - bombs; i++) {
            $('#cf_slick').append('<div data-diamond="'+(i+1)+'">' +
                '<p><i class="fad fa-diamond"></i> '+(i+1)+'</p>' +
                '<span>x'+abbreviateNumber(json[(i+1).toString()]) + '</span>' +
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
    });
}

function displayGrid(g) {
    var grid = Array.from(g);
    for(var j = 0; j < 5 * 5; j++) {
        $('*[data-grid-id="'+j+'"]').toggleClass(grid[j] === 1 ? 'mine_bomb' : 'mine_safe', true);
    }
}

$(document).ready(function() {
    clear_c();

    var changed = false;
    $('#change_bombs').on('click', function() {
        if(changed) return;
        changed = true;
        $('#change_bombs span').toggleClass('dn', true);
        $('#change_bombs input').toggleClass('dn', false);
        $('.bomb_input').on('input', function() {
            var v = parseInt($(this).val());
            if(isNaN(v) || v < 2 || v > 24) {
                $(this).toggleClass('bad', true);
                play_disabled = true;
                return;
            } else {
                $(this).toggleClass('bad', false);
                play_disabled = false;
            }

            bombs = v;
            $('*[data-bomb]').toggleClass('bc_active', false);
            $('*[data-bomb="'+bombs+'"]').toggleClass('bc_active', true);
            clear_c();
            setStatus();
        }).focus();
    });
    $('*[data-bomb]').on('click', function(e) {
        if(!disabled) {
            e.preventDefault();
            return;
        }

        $('*[data-bomb]').toggleClass('bc_active', false);
        $(this).toggleClass('bc_active', true);
        bombs = parseInt($(this).attr('data-bomb'));
        $('.bomb_input').val(bombs);
        setStatus();
        clear_c();
    });
    $('*[data-grid-id]').on('click', function() {
        if($(this).hasClass('mine_disabled') || disabled || $(this).hasClass('mine_safe')) return;
        var clicked_id = parseInt($(this).attr('data-grid-id'));

        $.get('/game/mines/mine/' + game_id + '/' + clicked_id + (isDemo ? '?demo' : ''), function(response) {
            var json = JSON.parse(response);

            if(json.error != null) {
                if(json.error === -1) iziToast.error({message: 'Игра не найдена.', icon: 'fa fa-times'});
                if(json.error === 0) console.log('Server cancelled input');
                return;
            }

            if(json.status === 'continue') {
                game_safe_left -= 1;
                game_step += 1;
                setStatus(game_safe_left + bombs);
                $(this).toggleClass('mine_disabled', true);

                $('.slick-slide').toggleClass('cf_active', false);
                $('*[data-diamond='+game_step+']').toggleClass('cf_active', true);
                $('#cf_slick').slick('slickGoTo', game_step - 1);

                $('*[data-grid-id='+clicked_id+']').toggleClass('mine_safe', true);
                let v = parseFloat(json.profit).toFixed(2);
                if(!cancelText) {
                    setBetText('Забрать<br><span id="cf_profit">'+v+'</span> руб.');
                    cancelText = true;
                } else $('#cf_profit').html(v);
                setTimeout(function() {
                    $('#cf_profit').toggleClass('cf_profit-error', parseFloat(v) <= 0);
                }, 200);
                if(game_safe_left === 0) take();
            } else {
                if(!isDemo) {
                    sendDrop(game_id);
                    validateTask(game_id)
                }

                $('*[data-grid-id='+clicked_id+']').toggleClass('mine_bomb', true);
                $('*[data-diamond='+game_step+']').toggleClass('cf_active', false);

                setTimeout(function() {
                    updateBalance();
                    swap(true);
                    clear();
                    displayGrid(json.grid);
                }, 1000);
            }
        });
    });
});