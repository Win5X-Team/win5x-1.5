var bombs = 4, currentRow = 0;
var disabled = true, play_disabled = false, cancelText = false;
var game_id = null;

function row(id, status) {
    $('*[data-row]').toggleClass('stairs-block-id', true);
    $('*[data-row="'+id+'"]').toggleClass('stairs-block-disabled', status === false);
}

function stairs() {
    if(!disabled || play_disabled) return;

    $('*[data-row]').toggleClass('stairs-block-disabled', true);
    $.get('/game/stairs/' + $('#bet').val() + '/' + bombs + (isDemo ? '?demo' : ''), function(response) {
        let json = JSON.parse(response);

        if(json.error != null) {
            if(json.error === '$') load('games');
            if(json.error === -1) $('#b_si').click();
            if(json.error === 0) iziToast.error({message: 'Не удалось найти игру.', icon: 'fa fa-times'});
            if(json.error === 1) iziToast.error({message: 'Минимальная ставка: 0.01 руб.', icon: 'fa fa-times'});
            if(json.error === 2) $('#_payin').click();
            if(json.error === 3) iziToast.error({message: 'Количество камней: от 1 до 7', icon: 'fa fa-times'});
            return;
        }

        $('.stairs-ladder:not([data-stairs-mouseover="true"])').fadeOut('fast', function() {
            $(this).remove();
        });
        $('.stairs-block').toggleClass('stairs-bad', false).toggleClass('stairs-good', false);

        clear();
        $('#play').fadeOut('fast').attr('onclick', 'take()');
        updateBalance();

        swap(true);
        game_id = json.id;

        currentRow = 1;
        _disableDemo = true;
        row(1, true);
    });
}

function take() {
    if(play_disabled) return;

    $.get('/game/stairs/take/' + game_id + (isDemo ? '?demo' : ''), function(data) {
        let json = JSON.parse(data);

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
    });
}

function clear() {
    $('*[data-row]').toggleClass('stairs-block-disabled', true);
    $('.stairs-mul-current').removeClass('stairs-mul-current');

    setBetText('Играть');
    $('#play').attr('onclick', 'stairs()');
    game_id = null;
    cancelText = false;
    currentRow = 0;
    _disableDemo = false;
}

function swap(disable) {
    disabled = disable;
    $('*[data-row]').toggleClass('stairs-bad', false).toggleClass('stairs-block-disabled', disable);
}

function clear_c() {
    $.get('/game/stairs/mul/' + bombs, function(msg) {
        let json = JSON.parse(msg);
        for(let i = 1; i <= Object.keys(json).length; i++) {
            $('*[data-m-row="'+i+'"]').html('x'+abbreviateNumber(json[i]));
        }
    });
}

function displayGrid(g) {
    for(let row = 1; row <= 13; row++) {
        let grid = Array.from(g[row]);
        for(let cell = 0; cell < grid.length; cell++) {
            $('*[data-row="'+row+'"][data-cell-id="'+cell+'"]').toggleClass('stairs-block-disabled', true)
                .toggleClass('stairs-bad', grid[cell] === 1);
        }
    }
}

function displayRow(rowId, r) {
    let row = Array.from(r);
    $.each($('*[data-row="'+rowId+'"]'), function(i, e) {
        $(e).toggleClass('stairs-bad', row[i] === 1).toggleClass('stairs-block-disabled', true);
    });
}

$(document).ready(function() {
    clear_c();

    $('*[data-bomb]').on('click', function(e) {
        if(!disabled) {
            e.preventDefault();
            return;
        }

        $('*[data-bomb]').toggleClass('bc_active', false);
        $(this).toggleClass('bc_active', true);
        bombs = parseInt($(this).attr('data-bomb'));
        clear_c();
    });

    $('*[data-row]').mouseover(function() {
        if($(this).hasClass('stairs-block-disabled')) {
            $('.stairs-ladder[data-stairs-mouseover="true"]').fadeOut('fast');
            return;
        }
        $('.stairs-ladder[data-stairs-mouseover="true"]').stop().fadeIn('fast').css({
            'width': $('.stairs-block').width(),
            'height': $('.stairs-block').width() + 1,
            'top': $(this).position().top,
            'left': $(this).offset().left - $(this).parent().offset().left
        });
    });

    $('*[data-row]').on('click', function() {
        if($(this).hasClass('stairs-block-disabled')) return;
        let clicked_row_id = parseInt($(this).attr('data-cell-id'));
        let current = $(this);

        row(currentRow, false);

        let stairs = $('<div class="stairs-ladder"></div>');
        $('#stairs_container').append(stairs);
        stairs.fadeIn('fast').css({
            'width': $('.stairs-block').width(),
            'height': $('.stairs-block').width() + 1,
            'top': $(this).position().top,
            'left': $(this).offset().left - $(this).parent().offset().left
        });

        $.get('/game/stairs/open/' + game_id + '/' + clicked_row_id + (isDemo ? '?demo' : ''), function(response) {
            let json = JSON.parse(response);
            console.log(json);

            if(json.error != null) {
                if(json.error === -1) iziToast.error({message: 'Игра не найдена.', icon: 'fa fa-times'});
                if(json.error === 0) console.log('Server cancelled input');
                return;
            }

            if(json.status === 'continue') {
                displayRow(currentRow, json.row);
                $('*[data-m-row]').removeClass('stairs-mul-current');
                $('*[data-m-row="'+currentRow+'"]').toggleClass('stairs-mul-current', true);

                current.toggleClass('stairs-good', true);
                let v = parseFloat(json.profit).toFixed(2);
                if(!cancelText) {
                    setBetText('Забрать<br><span id="cf_profit">'+v+'</span> руб.');
                    cancelText = true;
                } else $('#cf_profit').html(v);
                setTimeout(function() {
                    $('#cf_profit').toggleClass('cf_profit-error', parseFloat(v) <= 0);
                }, 200);

                if(currentRow === 13) take();
                else {
                    currentRow += 1;
                    row(currentRow - 1, false);
                    row(currentRow, true);
                }
            } else {
                if(!isDemo) {
                    sendDrop(game_id);
                    validateTask(game_id);
                }

                console.log(json);
                current.toggleClass('stairs-bad', true).toggleClass('stairs-block-disabled', true);
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