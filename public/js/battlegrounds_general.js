let secure = window.location.protocol === 'https:';
var bg_socket = null, _accept = false, _destroy_once = false, _players = 5;

$(document).ready(function() {
    bg_socket = io('w'+(secure ? 'ss' : 's')+'://'+window.location.hostname+':2087', {transports: ['websocket'], secure: secure, rejectUnauthorized: false});

    bg_socket.on('connect', function() {
        console.log('Connected to :3001');
    });
    bg_socket.on('connection error', function() {
        iziToast.error({message: 'На вашем счете недостаточно баланса для игры в этом режиме.', icon: 'fa fa-times'});
    });

    bg_socket.on('update', function(msg) {
        var game = JSON.parse(msg);

        if(game.state === 'waiting') {
            waitingDialog(game, function() {
                $('.bg_waiting_online').html(game.players.length + '/3')
            });
            if(game.players.length >= 3) waitingDialog(game, function() {
                $('#bg_state').html('Ожидание игроков...: ' + fmtMSS(game.timer) + ' до начала игры');
            }); else waitingDialog(game, function() {
                $('#bg_state').html('Ожидание игроков...');
            });
        } else if(game.state === 'bets') {
            $('#bg_timer').html(fmtMSS(game.timer));
            $('#bg_players').html(Object.keys(game.bets).length + '/' + game.players.length);
        } else if(game.state === 'game_start') {
            if(!_destroy_once) {
                iziToast.destroy();
                _players = game.players.length;
                updateBalance();
            }
            _destroy_once = true;
            $('.bg_reward').html(game.reward + ' руб.');
            $('#round').html(game.timer <= 0 ? '...' : fmtMSS(game.timer));
        } else if(game.state === 'finished') {
            $('#round').html('...');
            setTimeout(function() {
                $('#round').html('Завершено');
                updateBalance();
            }, 11000);
        }
    });
    bg_socket.on('spin', function(msg) {
        let eliminated = parseInt(msg);
        spin($($('*[data-bg-wheel-player-id="'+eliminated+'"]')[1]).index(), _players);
        _players -= 1;

        setTimeout(function() {
            $('*[data-bg-user-id="'+eliminated+'"]').addClass('bgp_dead');
            $('*[data-bg-wheel-player-id="'+eliminated+'"]').fadeOut('fast', function() {
                $(this).remove();
                $(this).addClass('bgp_dead');

                if(eliminated === parseInt($('#chat_send').attr('data-user-id'))) loseDialog();
            });
        }, 11000);
    });
    bg_socket.on('live spin', function(msg) {
        if($('.battlegrounds_info').length === 0 || prev == null) return;

        let eliminated = parseInt(msg);
        spin($($('*[data-bg-wheel-player-id="'+eliminated+'"]')[1]).index(), prev.players.length - prev.dead.length);

        setTimeout(function() {
            $('*[data-bg-wheel-player-id="'+eliminated+'"]').fadeOut('fast', function() {
                $(this).remove();
                $(this).addClass('bgp_dead');
            });
        }, 11000);
    });
    bg_socket.on('leave', function() {
        $('#waiting_dialog .iziToast-close').click();
    });
    bg_socket.on('live over', function() {
        prev = null;
        $.get('/bgFragment', function(response) {
            $('.bg_players').html(response);
        });
    });
    bg_socket.on('live', function(msg) {
        if($('.battlegrounds_info').length === 0) return;

        if(msg === '-1') {
            $('.bg_in_progress').fadeOut('fast', function() {
                $('.battlegrounds_info').toggleClass('battlegrounds_info_live', false);
                $('.bg_last_players_title').html('Последние игроки:');
                $('.bg_last_players').fadeIn('fast');
            });
        } else {
            let game = JSON.parse(msg);
            if(typeof liveRoulette === 'function') liveRoulette(game);

            $('.bg_last_players').fadeOut('fast', function() {
                $('.battlegrounds_info').toggleClass('battlegrounds_info_live', true);
                $('.bg_in_progress').fadeIn('fast');
            });
        }
    });
    bg_socket.on('redirect', function(msg) {
        iziToast.destroy();
        _accept = true;

        var json = JSON.parse(msg);
        load('battlegrounds', function() {
            let urls = [];
            for (let i = 0; i < json.players.length; i++) {
                let player_id = json.players[i];
                urls.push('/api/user/' + player_id);
            }

            promise(urls).then(function(result) {
                for (let i = 0; i < result.length; i++) {
                    let response = JSON.parse(result[i]);
                    $('#players_wheel_').append('<div data-bg-wheel-player-id="' + response.id + '" class="wheel-item wheel-black" style="background: url(' + response.avatar + ')"></div>');
                    $('#players_').append('<div data-bg-user-id="' + response.id + '" class="bgp_user" style="background: url(' + response.avatar + ')"></div>');
                }
                clone();
            }).catch(function(error) {
                console.log(error);
            });

            betDialog(json);
        });
    });
    bg_socket.on('bet confirm', function(msg) {
        let result = parseInt(msg);
        if(result === -2) iziToast.error({message: 'Время для ставок вышло.', icon: 'fa fa-times', position: 'bottomCenter'});
        else if(result === -1) iziToast.error({message: 'Введите число!', icon: 'fa fa-times', position: 'bottomCenter'});
        else if(result === 0) iziToast.error({message: 'Введенная сумма меньше требуемой минимальной ставки.', icon: 'fa fa-times', position: 'bottomCenter'});
        else if(result === 1) iziToast.error({message: 'Введенная сумма больше требуемой максимальной ставки.', icon: 'fa fa-times', position: 'bottomCenter'});
        else if(result === 2) iziToast.error({message: 'Вы уже сделали ставку.', icon: 'fa fa-times', position: 'bottomCenter'});
        else if(result === 3) $('#_bet').attr('disabled', 'disabled');
        else if(result === 4) iziToast.error({message: 'На вашем счету недостаточно баланса.', icon: 'fa fa-times', position: 'bottomCenter'});
    });
    bg_socket.on('finished', function(msg) {
        let game = JSON.parse(msg);

        iziToast.destroy();
        setTimeout(function() {
            winDialog(game.winner, game.reward);
        }, 300);
    });
});

function betDialog(json) {
    // TODO Make other bets visible.
    iziToast.question({
        id: 'rd',
        rtl: false,
        layout: 1,
        class: 'mm',
        theme: 'dark',
        backgroundColor: '#211f1f',
        drag: false,
        timeout: false,
        close: false,
        overlay: true,
        displayMode: 1,
        progressBar: false,
        icon: false,
        title: false,
        message: '<div class="mm_dlg bg_b_dlg">' +
            '<div class="mm_header bg_dialog_header bgr_dialog_content">Вносите свои ставки!</div>' +
                '<div class="bg_dialog_content bgr_dialog_content">' +
                    '<div class="bg_bb">' +
                        '<input data-number-input="true" class="b_input_s bg_bet_input" id="_bet" placeholder="Ставка">' +
                        '<div class="bg_bet_btn" onclick="bg_socket.emit(\'bet\', $(\'#_bet\').val());"><i class="fal fa-check"></i></div>' +
                    '</div>' +
                    '<div class="bg_bet_cooldown">' +
                        '<div class="bg_bc_content">' +
                            '<div class="bg_timer">' +
                                '<i class="fal fa-clock" title="Если вы не сделаете ставку в течении этого времени, то с вашего баланса спишется минимальная сумма."></i>' +
                                '<span id="bg_timer">'+fmtMSS(json.timer)+'</span>' +
                            '</div>' +
                            '<p class="hidden-xs"><span id="bg_players">0/'+json.players.length+'</span> игроков</p>' +
                        '</div>' +
                    '</div> ' +
                    '<i class="fal fa-dollar-sign bg_swords bg_d_dollar"></i>' +
                '</div>' +
            '</div></div>',
        position: 'center'
    });
}

function winDialog(winner_id, won) {
    $.get('/api/user/' + winner_id, function(msg) {
        var json = JSON.parse(msg);

        iziToast.question({
            rtl: false,
            layout: 1,
            class: 'bgWin mm',
            theme: 'dark',
            backgroundColor: '#211f1f',
            drag: false,
            timeout: false,
            close: false,
            overlay: true,
            displayMode: 1,
            progressBar: false,
            icon: false,
            title: false,
            message: '<div class="mm_dlg" style="height: 350px !important;">' +
                '<div class="mm_header bg_dialog_header bgr_dialog_content">Battlegrounds</div>' +
                '<div class="bg_dialog_content bgr_dialog_content">' +
                '<div class="bg_winner">' +
                '<img src="'+json.avatar+'" onclick="iziToast.destroy(); load(\'user?id='+winner_id+'\')" alt="">' +
                '<i class="fas fa-crown"></i>' +
                '</div>' +
                '<div class="bg_winner_info"><h1 class="ribbon-wide win-ribbon">' +
                '<strong class="ribbon-wide-content">' +
                '<a class="bg_winner_link" href="javascript:void(0)" onclick="iziToast.destroy(); load(\'user?id='+winner_id+'\')">'+json.name+'</a> побеждает<br><span>+'+won+' руб.</span>' +
                '</strong>' +
                '</h1></div>' +
                '<div class="bg_ready_btn_group" style="bottom: 0">' +
                '<div style="width:100%!important" class="bg_ready_btn" onclick="bg_socket.emit(\'leave\'); iziToast.destroy(); load(\'games\')">Покинуть игру</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>',
            position: 'center'
        });
    });
}

function loseDialog() {
    iziToast.question({
        rtl: false,
        layout: 1,
        class: 'bgLose mm',
        theme: 'dark',
        backgroundColor: '#211f1f',
        drag: true,
        timeout: false,
        close: false,
        overlay: true,
        displayMode: 1,
        progressBar: false,
        icon: false,
        title: false,
        message: '<div class="mm_dlg" style="height: 200px !important;">' +
            '<div class="mm_header bg_dialog_header bgr_dialog_content">Вы проиграли!</div>' +
            '<div class="bg_dialog_content bgr_dialog_content">' +
            '<div class="lose-icons">' +
            '<i class="fad fa-flag bg_swords"></i>' +
            '<i class="fad fa-flag bg_swords"></i>' +
            '</div>' +
            '<div class="bg_ready_btn_group" style="bottom: 0">' +
            '<div class="bg_ready_btn" onclick="iziToast.destroy();">Следить за игрой</div>' +
            '<div class="bg_ready_btn" onclick="bg_socket.emit(\'leave\'); iziToast.destroy(); load(\'games\')">Покинуть игру</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>',
        position: 'center'
    });
}

function waitingDialog(game, callback) {
    if($('.bg_waiting_online').length === 0) {
        iziToast.show({
            id: 'waiting_dialog',
            theme: 'dark',
            class: 'waitingRoom bg_waiting',
            icon: 'fad fa-swords',
            title: 'Battlegrounds',
            displayMode: 2,
            message: '<span id="bg_state">Ожидание игроков...</span> <span class="bg_waiting_online">' + game.players.length + '/3</span>',
            position: 'topCenter',
            transitionIn: 'flipInX',
            progressBarColor: '#ffa5a5',
            imageWidth: 70,
            layout: 2,
            timeout: false,
            drag: false,
            onClosed: function (instance, toast, closedBy) {
                if(game.state === 'waiting') bg_socket.emit('leave');
            },
            iconColor: '#ffa5a5'
        });
    } else callback();
}

function isConnected() {
    return bg_socket !== null && bg_socket.connected;
}

function battlegrounds_connect() {
    if(!isEmailConfirmed()) return;
    if(isGuest()) {
        $('#b_si').click();
        return;
    }
    if(!isConnected()) {
        iziToast.error({message: 'Не удалось подключиться к серверу.', icon: 'fa fa-times'});
        return;
    }
    if($('.waitingRoom').length > 0) return;
    if(window.location.pathname === '/battlegrounds') return;

    bg_socket.emit('connect to game', JSON.stringify({user_id: $('#chat_send').attr('data-user-id')}));
}
