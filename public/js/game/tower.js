var bombs = 1, currentRow = 0;
var disabled = true, play_disabled = false, cancelText = false;
var game_id = null;

function row(id, status) {
    $('*[data-r]').toggleClass('mine_disabled', true);
    $('*[data-r="'+id+'"]').toggleClass('mine_disabled', status === false).toggleClass('tower_active', status === true);
}

function tower() {
    if(!disabled || play_disabled) return;

    $('*[data-grid-id]').toggleClass('tower_active', false).toggleClass('mine_disabled', true)
        .toggleClass('tower_bomb', false).toggleClass('tower_safe', false).toggleClass('tower_safe_picked', false);
    $.get('/game/tower/' + $('#bet').val() + '/' + bombs + (isDemo ? '?demo' : ''), function(response) {
        let json = JSON.parse(response);
        console.log(json);

        if(json.error != null) {
            if(json.error === '$') load('games');
            if(json.error === -1) $('#b_si').click();
            if(json.error === 0) iziToast.error({message: 'Не удалось найти игру.', icon: 'fa fa-times'});
            if(json.error === 1) iziToast.error({message: 'Минимальная ставка: 0.01 руб.', icon: 'fa fa-times'});
            if(json.error === 2) $('#_payin').click();
            if(json.error === 3) iziToast.error({message: 'Количество мин: от 1 до 4', icon: 'fa fa-times'});
            return;
        }

        clear();
        $('#play').fadeOut('fast').attr('onclick', 'take()');
        updateBalance();

        swap(false);
        game_id = json.id;

        currentRow = 0;
        _disableDemo = true;
        row(0, true);
        $('*[data-row-id="0"]').toggleClass('tower_mul_active', true);
    });
}

function take() {
    if(disabled) return;

    $.get('/game/tower/take/' + game_id + (isDemo ? '?demo' : ''), function(data) {
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
    $('*[data-grid-id]').toggleClass('tower_active', false).toggleClass('mine_disabled', true);
    $('*[data-row-id]').toggleClass('tower_mul_active', false);

    setBetText('Играть');
    $('#play').attr('onclick', 'tower()');
    game_id = null;
    cancelText = false;
    currentRow = 0;
    _disableDemo = false;
}

function swap(disable) {
    disabled = disable;
    $('*[data-grid-id]').toggleClass('mine_disabled', disable);
}

function clear_c() {
    $.get('/game/tower/mul/' + bombs, function(msg) {
        let json = JSON.parse(msg);
        for(let i = 0; i < Object.keys(json).length; i++) {
            $('*[data-row-id="'+i+'"]').html('x'+json[i+1]);
        }
    });
}

function displayGrid(g) {
    let grid = Array.from(g);
    for(let row = 0; row < 10; row++) {
        for(let cell = 0; cell < 5; cell++) {
            $('*[data-r="'+row+'"][data-grid-in-row-id="'+cell+'"]').toggleClass('mine_disabled', true)
                .toggleClass(grid[row][cell] === 1 ? 'tower_bomb' : 'tower_safe', true);
        }
    }
}

function displayRow(rowId, r) {
    let row = Array.from(r);
    $.each($('*[data-r="'+rowId+'"]'), function(i, e) {
        $(e).toggleClass(row[i] === 1 ? 'tower_bomb' : 'tower_safe', true).toggleClass('mine_disabled', true);
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
    $('*[data-r]').on('click', function() {
        if($(this).hasClass('mine_disabled') || disabled || $(this).hasClass('tower_safe') || $(this).hasClass('tower_bomb')) return;
        let clicked_row_id = parseInt($(this).attr('data-grid-in-row-id'));
        let current = $(this);

        row(currentRow, false);

        $.get('/game/tower/open/' + game_id + '/' + clicked_row_id + (isDemo ? '?demo' : ''), function(response) {
            let json = JSON.parse(response);
            console.log(json);

            if(json.error != null) {
                if(json.error === -1) iziToast.error({message: 'Игра не найдена.', icon: 'fa fa-times'});
                if(json.error === 0) console.log('Server cancelled input');
                return;
            }

            if(json.status === 'continue') {
                displayRow(currentRow, json.row);

                current.toggleClass('tower_safe_picked', true);
                let v = parseFloat(json.profit).toFixed(2);
                if(!cancelText) {
                    setBetText('Забрать<br><span id="cf_profit">'+v+'</span> руб.');
                    cancelText = true;
                } else $('#cf_profit').html(v);
                setTimeout(function() {
                    $('#cf_profit').toggleClass('cf_profit-error', parseFloat(v) <= 0);
                }, 200);

                if(currentRow === 9) take();
                else {
                    currentRow += 1;
                    row(currentRow, true);

                    $('*[data-row-id]').toggleClass('tower_mul_active', false);
                    $('*[data-row-id="'+currentRow+'"]').toggleClass('tower_mul_active', true);
                }
            } else {
                if(!isDemo) {
                    sendDrop(game_id);
                    validateTask(game_id);
                }

                current.toggleClass('mine_disabled', true).toggleClass('tower_bomb', true);
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