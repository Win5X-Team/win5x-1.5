var token = 0.1;

var rotationsTime = 8, wheelSpinTime = 6, ballSpinTime = 5;
var numorder = [0, 32, 15, 19, 4, 21, 2, 25, 17, 34, 6, 27, 13, 36, 11, 30, 8, 23, 10, 5, 24, 16, 33, 1, 20, 14, 31, 9, 22, 18, 29, 7, 28, 12, 35, 3, 26];
var numred = [32, 19, 21, 25, 34, 27, 36, 30, 23, 5, 16, 1, 14, 9, 18, 7, 12, 3];
var numblack = [15, 4, 2, 17, 6, 13, 11, 8, 10, 24, 33, 20, 31, 22, 29, 28, 35, 26];
var numgreen = [0];
var numberLoc = [];

var bet = {};
var roulette_history = [];
var json = null;

function createWheel() {
    var temparc = 360 / numorder.length;
    for (var i = 0; i < numorder.length; i++) {
        numberLoc[numorder[i]] = [];
        numberLoc[numorder[i]][0] = i * temparc;
        numberLoc[numorder[i]][1] = i * temparc + temparc;

        newSlice = document.createElement("div");
        $(newSlice).addClass("r-hold");
        newHold = document.createElement("div");
        $(newHold).addClass("r-pie");
        newNumber = document.createElement("div");
        $(newNumber).addClass("r-num");

        newNumber.innerHTML = numorder[i];
        $(newSlice).attr("id", "rSlice" + i);
        $(newSlice).css(
            "transform",
            "rotate(" + numberLoc[numorder[i]][0] + "deg)"
        );

        $(newHold).css("transform", "rotate(9.73deg)");
        $(newHold).css("-webkit-transform", "rotate(9.73deg)");

        if ($.inArray(numorder[i], numgreen) > -1) {
            $(newHold).addClass("r-greenbg");
        } else if ($.inArray(numorder[i], numred) > -1) {
            $(newHold).addClass("r-redbg");
        } else if ($.inArray(numorder[i], numblack) > -1) {
            $(newHold).addClass("r-greybg");
        }

        $(newNumber).appendTo(newSlice);
        $(newHold).appendTo(newSlice);
        $(newSlice).appendTo($("#rcircle"));
    }
}

function resetAni() {
    let pfx = $.keyframe.getVendorPrefix();

    animationPlayState = "animation-play-state";
    playStateRunning = "running";

    $(".r-ball")
        .css(pfx + animationPlayState, playStateRunning)
        .css(pfx + "animation", "none");

    $(".r-pieContainer")
        .css(pfx + animationPlayState, playStateRunning)
        .css(pfx + "animation", "none");
    $("#toppart")
        .css(pfx + animationPlayState, playStateRunning)
        .css(pfx + "animation", "none");

    $("#rotate2").html("");
    $("#rotate").html("");
}

function spinTo(num) {
    var temp = numberLoc[num][0] + 4;
    var rndSpace = Math.floor(Math.random() * 360 + 1);

    resetAni();
    setTimeout(function() {
        bgrotateTo(rndSpace);
        ballrotateTo(rndSpace + temp);
    }, 300);
}

function ballrotateTo(deg) {
    var temptime = rotationsTime + 's';
    var dest = -360 * ballSpinTime - (360 - deg);
    $.keyframe.define({
        name: "rotate2",
        from: {
            transform: "rotate(0deg)"
        },
        to: {
            transform: "rotate(" + dest + "deg)"
        }
    });

    $(".r-ball").playKeyframe({
        name: "rotate2",
        duration: temptime,
        timingFunction: "ease-in-out",
        complete: function() {
            finishSpin();
        }
    });
}

function bgrotateTo(deg) {
    var dest = 360 * wheelSpinTime + deg;
    var temptime = (rotationsTime * 1000 - 1000) / 1000 + 's';

    $.keyframe.define({
        name: "rotate",
        from: {
            transform: "rotate(0deg)"
        },
        to: {
            transform: "rotate(" + dest + "deg)"
        }
    });

    $(".r-pieContainer").playKeyframe({
        name: "rotate",
        duration: temptime,
        timingFunction: "ease-in-out",
        complete: function() {}
    });

    $("#toppart").playKeyframe({
        name: "rotate",
        duration: temptime,
        timingFunction: "ease-in-out",
        complete: function() {}
    });
}

function roulette() {
    if(json != null) return;
    $.get('/game/roulette/' + JSON.stringify(bet) + (isDemo ? '?demo' : ''), function(data) {
        let j = JSON.parse(data);

        if(j.error != null) {
            if(j.error === '$') load('games');
            if(j.error === -1) $('#b_si').click();
            if(j.error === 1) iziToast.error({message: 'Минимальная ставка: 0.01 руб.', icon: 'fa fa-times'});
            if(j.error === 2) $('#_payin').click();
            return;
        }

        $('.roulette-result').fadeOut(250, function() {
            $(this).delay(250).toggleClass('roulette-result-lose', false).toggleClass('roulette-result-win', false);
            $('#toppart').fadeIn(250, function() {
                json = j;
                spinTo(parseInt(json.response.number));
            });
        });
    });
}

function finishSpin() {
    $('#toppart').fadeOut('fast', function() {
        $('.roulette-result').html(json.response.number).fadeIn('fast')
            .toggleClass(json.response.win === false ? 'roulette-result-lose' : 'roulette-result-win', true);

        if(json.response.win && isDemo && isGuest()) showDemoTooltip();
        if(json.response.id !== -1 && !isDemo) {
            sendDrop(json.response.id);
            validateTask(json.response.id);
        }

        json = null;
        updateBalance();
    });
}

$(document).ready(function() {
    $('.token').on('click', function () {
        token = parseFloat($(this).attr('data-value'));

        $('.token-active').removeClass('token-active');
        $(this).addClass('token-active');
    });

    $('.tokens').slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 4,
        arrows: true,
        variableWidth: true,

        responsive: [
            {
                breakpoint: 991,
                settings: {
                    slidesToScroll: 13,
                    slidesToShow: 13,
                    infinite: true
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToScroll: 4,
                    slidesToShow: 4,
                    infinite: false
                }
            }
        ],

        slidesToScroll: 4,
    });

    let disableChipsFor = function (elementId, chips) {
        $(elementId).on('mouseover', function () {
            $.each($('.chip'), function (i, e) {
                if (chips.includes($(this).attr('data-chip')))
                    $(this).addClass('chip-disabled');
            });
        });
        $(elementId).on('mouseleave', function () {
            $('.chip').removeClass('chip-disabled');
        });
    };

    let rows = {
        first: ['3', '6', '9', '12', '15', '18', '21', '24', '27', '30', '33', '36'],
        second: ['2', '5', '8', '11', '14', '17', '20', '23', '26', '29', '32', '35'],
        third: ['1', '4', '7', '10', '13', '16', '19', '22', '25', '28', '31', '34'],
        red: ['3', '9', '12', '18', '21', '27', '30', '36', '5', '14', '23', '32', '1', '7', '16', '19', '25', '34'],
        black: ['6', '15', '24', '33', '2', '8', '11', '17', '20', '26', '29', '35', '4', '10', '13', '22', '28', '31'],
        numeric: {
            first: ['3', '6', '9', '12', '2', '5', '8', '11', '1', '4', '7', '10'],
            second: ['15', '18', '21', '24', '14', '17', '20', '23', '13', '16', '19', '22'],
            third: ['27', '30', '33', '36', '26', '29', '32', '35', '25', '28', '31', '34']
        },
        half: {
            first: ['3', '6', '9', '12', '15', '18', '2', '5', '8', '11', '14', '17', '1', '4', '7', '10', '13', '16'],
            second: ['21', '24', '27', '30', '33', '36', '20', '23', '26', '29', '32', '35', '19', '22', '25', '28', '31', '34']
        },
        e: {
            even: ['6', '12', '18', '24', '30', '36', '2', '8', '14', '20', '26', '32', '4', '10', '16', '22', '28', '34'],
            opposite: ['3', '9', '15', '21', '27', '33', '5', '11', '17', '23', '29', '35', '1', '7', '13', '19', '25', '31']
        }
    };

    disableChipsFor('#row1', rows.second.concat(rows.third));
    disableChipsFor('#row2', rows.first.concat(rows.third));
    disableChipsFor('#row3', rows.first.concat(rows.second));
    disableChipsFor('#red', rows.black);
    disableChipsFor('#black', rows.red);
    disableChipsFor('#1-12', rows.numeric.second.concat(rows.numeric.third));
    disableChipsFor('#13-24', rows.numeric.first.concat(rows.numeric.third));
    disableChipsFor('#25-36', rows.numeric.first.concat(rows.numeric.second));
    disableChipsFor('#1-18', rows.half.second);
    disableChipsFor('#19-36', rows.half.first);
    disableChipsFor('#e', rows.e.opposite);
    disableChipsFor('#eo', rows.e.even);

    $('.chip').on('click', function () {
        let stack = $(this).find('.bet-stack');
        if (stack.length === 0) {
            stack = $('<div class="bet-stack"></div>');
            stack.hide().fadeIn('fast');
            $(this).append(stack);
        }

        let t = $('<div class="token bet-token" data-token-value="' + token + '" style="margin-top: -' + (stack.children().length * 2) + 'px">' + abbreviateNumber(token) + '</div>');
        stack.append(t);
        roulette_history.push(t);

        let b = $(this).attr('data-chip');
        setBetFor(b, getBetFor(b) + token);
    });

    createWheel();

    setTimeout(function() {
        $.getScript('/js/vendor/jquery.keyframes.min.js');
    }, 1000);
});

function getBetFor(chip) {
    if(bet[chip] == null) return 0;
    return bet[chip];
}

function setBetFor(chip, value) {
    bet[chip] = value;

    let total = 0;
    for(let i = 0; i < Object.keys(bet).length; i++) {
        total += bet[Object.keys(bet)[i]];
    }
    $('#token_bet').html(total.toFixed(2) + ' руб.');
}

function r_history_back() {
    if(roulette_history.length === 0) return;
    let latest = roulette_history[roulette_history.length-1];

    setBetFor(latest.parent().parent().attr('data-chip'), getBetFor(latest.parent().parent().attr('data-chip')) - parseFloat(latest.attr('data-token-value')));

    if(latest.parent().children().length === 1) latest.parent().fadeOut('fast', function() { $(this).remove() });
    else latest.remove();
    roulette_history.splice(roulette_history.length - 1, 1);
}

function r_history_clear() {
    roulette_history = [];
    bet = {};
    $('.bet-stack').fadeOut('fast', function() {
        $(this).remove();
    });
    $('#token_bet').html('0.00 руб.');
}