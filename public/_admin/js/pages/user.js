var p = 0;

function load_actions(id, page) {
    let gameName = function(gid) {
        if(gid === 1) return 'Dice';
        else if(gid === 2) return 'Wheel';
        else if(gid === 3) return 'Crash';
        else if(gid === 4) return 'Coinflip';
        else if(gid === 5) return 'Mines';
        else if(gid === 6) return 'Battlegrounds';
        else if(gid === 7) return 'HiLo';
        else if(gid === 8) return 'Blackjack';
        else if(gid === 9) return 'Tower';
        else if(gid === 10) return 'Roulette';
        else if(gid === 11) return 'Stairs';
        else if(gid === 12) return 'Plinko';
        else if(gid === 13) return 'Keno';
        else return 'Игра под идентификатором '+gid;
    };

    send('#actions', '/admin/user/actions/'+id+'/'+page, function(response) {
        let json = JSON.parse(response);
        for(let i = 0; i < json.length; i++) {
            let t = json[i];
            let type = 'Неизвестное действие';
            let prefix = null;
            if(t['type'] === 0 || t['type'] === 1) {
                type = 'Активация '+(t['type'] === 0 ? 'промокода' : 'реферального кода');
                prefix = 'Код';
            }
            if(t['type'] === 2) {
                type = gameName(parseInt(t['data']));
                t['data'] = null;
            }
            if(t['type'] === 3) type = 'Разблокировка чата';
            if(t['type'] === 4) type = 'Получение бонуса';
            if(t['type'] === 5) type = 'Получение бонуса за 10 активных рефералов';
            if(t['type'] === 6) type = 'Получение бонуса за вступление в группу ВКонтакте';
            if(t['type'] === 7) type = 'Получение бонуса за активного реферала';
            if(t['type'] === 8) {
                type = 'Заявка на выплату';
                prefix = 'Кошелек';
            }
            if(t['type'] === 9) type = 'Депозит';
            if(t['type'] === 10) {
                type = 'Покупка попыток на выполнение задания';
                prefix = 'Условие';
            }
            if(t['type'] === 11) {
                type = 'Выполнение задания';
                prefix = 'Условие';
            }
            if(t['type'] === 12) type = 'Получение бонуса за регистрацию по реферальной ссылке';
            if(t['type'] === 13) type = 'Отмена выплаты';
            if(t['type'] === 14) type = 'Викторина';
            if(t['type'] === 15) type = 'Кейс';
            if(t['type'] === 16) type = 'Ошибка депозита';

            $('#actions').append(`
                <div class="kt-notification__item">
                    <div class="kt-notification__item-details">
                        <div class="kt-notification__item-title" style="font-family: 'Open Sans'; font-size: 14px">
                            `+type+` <strong>`+(t['sum'] > 0 ? '+' : '')+t['sum']+` руб.</strong>
                        </div>
                        <div class="kt-notification__item-time" style="font-family: 'Open Sans'; font-size: 13px;">
                            `+(t['data'] == null ? '' : (prefix == null ? 'Данные: ' : prefix+": ")+t['data']+' | ')+'Баланс: '+t['current']+' руб.'+`
                        </div>
                    </div>
                </div>
            `);
        }
    });
}

$(document).ready(function() {
    p = 0;
    load_actions($.urlParam('id'), 0);

    $(window).scroll(function () {
        if($('#actions').length === 0) return;
        if ($(window).scrollTop() >= $(document).height() - $(window).height() - 100) {
            p += 1;
            load_actions($.urlParam('id'), p);
        }
    });
});