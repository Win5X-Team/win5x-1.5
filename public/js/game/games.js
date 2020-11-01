$(document).ready(function() {
    $('.battle_container').addClass('start');

    $.each($('.i_game'), function(i, e) {
        $(e).on('mouseover', function() {
            $(e).find('.i_game-name').toggleClass('i_game-active', true);
        });
        $(e).on('mouseleave', function() {
            $(e).find('.i_game-name').toggleClass('i_game-active', false);
        });
    });
});

var prev = null;
function liveRoulette(json) {
    if($('.battlegrounds_info').length === 0) return;

    if(prev != null && prev.length === json.length) {
        if(json.state === 'bets') $('.bg_last_players_title').html('Ожидание ставок... <i class="fas fa-clock"></i> '+fmtMSS(json.timer));
        if(json.state === 'game_start') $('.bg_last_players_title').html('Следующий раунд: <i class="fas fa-clock"></i> '+(json.timer <= 0 ? '...'  : fmtMSS(json.timer)));
        return;
    }
    prev = json;

    let urls = [];
    for (let i = 0; i < json.players.length; i++) {
        let player_id = json.players[i];
        urls.push('/api/user/' + player_id);
    }

    $('#players_wheel_').html('');
    promise(urls).then(function(result) {
        for (let i = 0; i < result.length; i++) {
            let response = JSON.parse(result[i]);
            $('#players_wheel_').append('<div data-bg-wheel-player-id="' + response.id + '" class="wheel-item wheel-live-item wheel-black" style="background: url(' + response.avatar + ')"></div>');
        }
        clone();
    }).catch(function(error) {
        console.log(error);
    });
}

let re_disabled = false;
function resend_email() {
    if(re_disabled) {
        iziToast.error({icon: 'fa fa-times', position: 'bottomCenter', message: 'Подождите некоторое время перед отправкой следующего сообщения.'});
        return;
    }
    re_disabled = true;
    setTimeout(function() {
        re_disabled = false;
    }, 120000);

    $.get('/email_resend', function(response) {
        if(response === 'reload') {
            window.location.reload();
            return;
        }

        iziToast.success({icon: 'fas fa-info-circle', position: 'bottomCenter', message: 'Сообщение успешно отправлено.' +
                '<br>Если Вы не можете найти письмо, то проверьте вкладку Спам в вашем почтовом ящике.' +
                '<br>Если у Вас возникли проблемы с активацией аккаунта, то свяжитесь с <a href="https://vk.com/playintm" target="_blank" class="ll">поддержкой</a> для ручной активации.',
            'theme': 'dark', backgroundColor: '#211f1f', timeout: false});
    });
}
