var expandPrevious = null, expandPage = 1;

$(document).ready(function() {
    mainSlider();
    sidebarResize();

    $('.gg_sidebar_main').fadeOut('fast');
    $('.sport_sidebar').css({display: 'block'});
    $('body').toggleClass('sport-page', true);

    $('.sport-club-header').on('click', function() {
        $(this).parent().toggleClass('sport-club-header-min');
    });

    $('.ticket_tabs').on('click', function() {
        swapSportSidebar(false);
        swapTicketSidebar(false);
    });

    $('[data-ticket-tab]').on('click', function() {
        $('.ticket-tab-active').removeClass('ticket-tab-active');
        $('*[data-ticket-tab="'+$(this).attr('data-ticket-tab')+'"]').addClass('ticket-tab-active');
        $($(this).attr('data-ticket-tab')).addClass('ticket-tab-active');
    });

    $('.sport-game-header-overlay-expand-button').on('click', function() {
        $(this).find('i').toggleClass('fa-rotate-180');
        let append = function() {
            $('.sport-game-header-overlay').append(`
                <div class="overlay-prev"><i class="fas fa-angle-left"></i></div>
                <div class="overlay-next"><i class="fas fa-angle-right"></i></div>
            `);

            $('.overlay-prev').on('click', function() {
                if($('[data-expand-content="'+(expandPage - 1)+'"]').length === 0) return;
                expandPage--;
                $('.sport-game-header-overlay').html($('[data-expand-content="'+expandPage+'"]').html());
                append();
            });

            $('.overlay-next').on('click', function() {
                if($('[data-expand-content="'+(expandPage + 1)+'"]').length === 0) return;
                expandPage++;
                $('.sport-game-header-overlay').html($('[data-expand-content="'+expandPage+'"]').html());
                append();
            });
        };

        if(expandPrevious === null) {
            expandPrevious = $('.sport-game-header-overlay').html();
            expandPage = 1;
            $('.sport-game-header-overlay').html($('[data-expand-content="1"]').html());
            append();
        } else {
            $('.sport-game-header-overlay').html(expandPrevious);
            expandPrevious = null;
        }
        updateTooltips();
    });
});

function sidebarResize() {
    swapSportSidebar($(window).width() < 1600);
}

function swapSportSidebar(minimize) {
    minimize = minimize === undefined ? !$('.sport_sidebar').hasClass('sport_minimized') : minimize;
    $('.sport_sidebar').toggleClass('sport_minimized', minimize);

    $('.sport_unminimize').toggleClass('fa-angle-right', minimize);
    $('.sport_unminimize').toggleClass('fa-angle-left', !minimize);
}

function swapTicketSidebar(hide) {
    if(isGuest()) {
        $('#b_si').click();
        return;
    }

    hide = hide === undefined ? !$('.sport_sidebar_tickets').hasClass('dn') : hide;
    $('.sport_sidebar_tickets').toggleClass('dn', hide);

    if(!hide) swapSportSidebar(false);
    $('.sport_sidebar_footer .sport_sidebar_category').toggleClass('sport_sidebar_footer_active', !hide);
    $('.couponPurchaseOnly').toggleClass('dn', $('.ticket-tab-active').attr('data-ticket-tab') === '#bets' || hide);
}

function addTicket(gameName, gameId, gameHeader, gameCategory, gameResult, gameMultiplierWatcher, parent) {
    if(isGuest()) {
        $('#b_si').click();
        return;
    }

    let gameMultiplier = parseFloat(parent.find('*[data-watch="'+gameMultiplierWatcher+'"]').html());
    if(isNaN(gameMultiplier)) return;

    if($('.sport_sidebar').hasClass('sport_minimized')) swapSportSidebar(false);
    swapTicketSidebar(false);

    $('*[data-ticket-tab="#coupons"]').click();

    let jsBetId = Math.random().toString().substr(3);
    let revalidate = null;
    let bet = {
        'watcher': gameMultiplierWatcher,
        'wager': 0.01,
        'game': gameName,
        'id': gameId,
        'description': {
            'header': gameHeader,
            'title': gameCategory,
            'subtitle': gameResult,
        },
        'blocked': false,

        remove: function() {
            clearTimeout(revalidate);
            $('#'+jsBetId).slideUp('fast', function() {
                $(this).remove();
                sBets = sBets.filter(bet => bet.id !== gameId);

                recalculateTotalBetSum();
            });
        }
    };
    sBets.push(bet);

    $('.sport-empty-bets').remove();
    $('#pending_bets').prepend(`
        <div class="sport-coupon" id="`+jsBetId+`">
            <div class="sport-coupon-header">
                `+gameHeader+`
                <i class="fal fa-times" id="c-`+jsBetId+`"></i>
            </div>
            <div class="sport-coupon-content">
                <div class="sport-coupon-column">
                    <div style="display: none" class="sport-mc-confirm">
                        <div class="sport-mc-content">
                            <div>Изменение коэффициента</div>
                            <div>
                                Новый коэффициент:
                                <span id="nmc-`+jsBetId+`"></span>
                            </div>
                            <div class="sport-mc-buttons">
                                <div id="mc-accept-`+jsBetId+`">Принять</div>
                                <div id="mc-deny-`+jsBetId+`">Отменить</div>
                            </div>
                        </div>
                    </div>
                    <div class="sport-coupon-category">`+gameCategory+`</div>
                    <div class="sport-coupon-result">`+gameResult+`</div>
                    <div class="sport-coupon-bet">Ставка:</div>
                    <input id="i-`+jsBetId+`" class="sport-coupon-input" value="0.01">
                </div>
                <div class="sport-coupon-column sport-coupon-outcome-column" data-watch-id="`+gameId+`" data-watch-game="`+gameName+`">
                    <div data-watch="`+gameMultiplierWatcher+`" id="m-`+jsBetId+`">`+gameMultiplier+`</div>
                    <div>Выплата</div>
                    <div id="po-`+jsBetId+`">`+(0.01 * gameMultiplier).toFixed(2) +` руб.</div>
                </div>
            </div>
        </div>
    `);

    recalculateTotalBetSum();
    $('#c-'+jsBetId).on('click', function() {
        bet.remove();
        recalculateTotalBetSum();
    });

    $('#i-'+jsBetId).on('input', function() {
        bet.wager = parseFloat($(this).val());
        let multiplier = parseFloat($('#m-'+jsBetId).html());
        let v = bet.wager * multiplier;
        $('#po-'+jsBetId).html((isNaN(v) ? '-' : v.toFixed(2)) + ' руб.');
        recalculateTotalBetSum();
    });

    $('#mc-accept-'+jsBetId).on('click', function() {
        bet.blocked = false;
        $('#'+jsBetId+' .sport-mc-confirm').fadeOut('fast');
    });

    $('#mc-deny-'+jsBetId).on('click', function() {
        bet.remove();
    });

    let prev = null;
    revalidate = setInterval(function() {
        let multiplier = parseFloat($('#m-'+jsBetId).html());

        let v = bet.wager * multiplier;
        $('#po-'+jsBetId).html((isNaN(v) ? '-' : v.toFixed(2)) + ' руб.');

        if(prev !== null && prev !== multiplier && !$('#autoMConfirm').is(':checked')) {
            $('#nmc-'+jsBetId).html(multiplier);
            $('#' + jsBetId +' .sport-mc-confirm').fadeIn('fast');
            bet.blocked = true;
        }

        prev = multiplier;
    }, 250);
}

$(window).load(function() {
    if(getCookie('autoMConfirm') === 'true') $('#autoMConfirm').attr('checked', 'checked');
    $('#autoMConfirm').on('change', function() {
        setCookie('autoMConfirm', $(this).is(':checked').toString(), 365);
    });
});

function recalculateTotalBetSum() {
    let total = 0;
    for(let i = 0; i < sBets.length; i++)
        if(!isNaN(sBets[i].wager)) total += sBets[i].wager;
    $('#betTotal').html(total.toFixed(2) + ' руб.');
}

$(window).resize(sidebarResize);