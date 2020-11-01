
/* javascript-obfuscator:disable */

var _cdisable = false;

$(document).ready(function() {
    $('.case-purchase').on('click', function() {
        if($(this).hasClass('case-btn-disabled')) return;
        $('.case-purchase').addClass('case-btn-disabled');

        let id = $(this).attr('data-case');
        let s = '#'+id+' ';

        $('.case-container:not([data-case-id="'+id+'"])').css({'opacity': '0.1'});

        $(s+'.lid-t').parent().removeClass('spinner-p').addClass('spinner');
        setTimeout(function() {
            $(s+' .t-glow-sel').addClass('t-glow');
        }, 1200);
        setTimeout(function() {
            $(s+'.lid-t').parent().addClass('spinner-p').removeClass('spinner');
            $(s+'.lid-t').addClass('lid-open');

            $('#modal-1-header').html($('*[data-case-id="'+id+'"] .case-header').html());
            $('#modal-1-content').html(`
                <div class="profile-loader" style="top: 0; left: 0">
                    <div></div>
                </div>`);
            setTimeout(function () {
                setTimeout(function() {
                    $.get('/case', function(response) {
                        $('.profile-loader').fadeOut('fast', function() {
                            $('#modal-1-content').hide();
                            $('#modal-1-content').html(response);

                            let json = JSON.parse($('*[data-data-id="'+id+'"]').attr('data-json').replace(/'/g, '"'));
                            for(let i = 0; i < json.length; i++) {
                                let item = json[i];
                                let data = {
                                    1: item.value+" руб.",
                                    2: $('#chat_send').attr('data-user-level') === '10' ? (item.value / 20) + ' руб.' : item.value+"% "+declOfNum(item.value, ['опыт', 'опыта', 'опыта'])
                                }[item.type];

                                $('.case-modal-items').append(`
                                    <div class="case-modal-item">
                                        <div class="case-modal-item-content case-`+item.rarity+`">
                                            `+data+`
                                        </div>
                                    </div>
                                `);
                                $('#caseWheel').append(`<div class="wheel-item wheel-black case-`+item.rarity+`"><div>`+data+`</div></div>`);
                            }

                            clone();

                            let price = $('*[data-price-id="'+id+'"]').html();
                            $('#price').html(price === 'Бесплатно' ? 'Открыть' : 'Открыть за '+price);
                            $('#modal-1-content').fadeIn('fast');

                            $('.case-modal-purchase').on('click', function() {
                                if($(this).hasClass('case-btn-disabled') || _cdisable) return;
                                _cdisable = true;

                                let btn = $(this);
                                $.get('/case/'+id, function(response) {
                                    let result = JSON.parse(response);
                                    if(result.error != null) {
                                        if(result.error === -1) {
                                            $('.md-close').click();
                                            $('#b_si').click();
                                        }
                                        if(result.error === 0) iziToast.error({position: 'bottomCenter', icon: 'fal fa-times', 'message': 'Произошла серверная ошибка'});
                                        if(result.error === 1) {
                                            $('.md-close').click();
                                            $('#_payin').click();
                                            iziToast.error({position: 'bottomCenter', icon: 'fal fa-times', 'message': 'Недостаточно баланса на счете.'});
                                        }
                                        if(result.error === 2) iziToast.error({position: 'bottomCenter', icon: 'fal fa-times', 'message': 'Бесплатный кейс доступен для открытия только раз в 24 часа.'});
                                        return;
                                    }

                                    if(result.send) sendDrop(result.id);

                                    let content = btn.html();

                                    $('.md-close').fadeOut('fast');
                                    btn.addClass('case-btn-disabled');
                                    btn.find('span').fadeOut('fast', function() {
                                        $(this).html('Открываем...').fadeIn('fast');
                                    });

                                    spin(parseInt(result.item), json.length);
                                    setTimeout(function() {
                                        $('.md-close').fadeIn('fast');
                                        btn.find('span').fadeOut('fast', function() {
                                            btn.html(result.free ? 'Следующее открытие будет доступно через 24 часа' : content).fadeIn('fast');
                                        });
                                        if(!result.free) btn.removeClass('case-btn-disabled');
                                        else {
                                            load('cases', undefined, true);
                                            $('.gg_sidebar-notification').fadeOut('fast');
                                        }

                                        _cdisable = false;
                                        updateBalance();
                                    }, 11000);
                                });
                            });
                        });
                    });
                }, 150);

                $('#modal-1').addClass("md-show");
            }, 300);
        }, 1400);
    });

    $('#md-close').on('click', function() {
        $('.case-purchase:not([data-disabled="true"])').removeClass('case-btn-disabled');
        $('.case-container').css({'opacity': 1});

        $('#modal-1').removeClass("md-show");

        $('.t-glow-sel').removeClass('t-glow');
        $('.lid-t').removeClass('lid-open');
    });
});

let _diff;
function countdown(diff) {
    _diff = diff * 1000;

    let i;
    function updateETime() {
        function pad(num) {
            return num > 9 ? num : '0'+num;
        }

        if($('#countdown').length === 0) {
            clearInterval(i);
            return;
        }

        let days = Math.floor(_diff / (1000*60*60*24)),
            hours = Math.floor(_diff / (1000*60*60)),
            mins = Math.floor(_diff / (1000*60)),
            secs = Math.floor(_diff / 1000),
            hh = hours - days * 24,
            mm = mins - hours * 60,
            ss = secs - mins * 60;

        if(hh === 0 && mm === 0 && ss === 0) {
            load('cases');
            clearInterval(i);
            return;
        }

        $('#countdown').html(pad(hh) + ':' + pad(mm) + ':' + pad(ss));
        _diff -= 1000;
    }
    i = setInterval(updateETime, 1000);
}