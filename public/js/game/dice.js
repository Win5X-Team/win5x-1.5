var __slider;
var cur = 'lower';
var timer;

$(document).ready(function() {
	let tooltip = $('<div class="d_slider-tooltip_container"><div class="d_slider-tooltip"><span id="tooltip-value">'+(isDemo ? '50':'44')+'</span></div></div>').hide();
	let stop = function(left, css) {
		var px = ((left).toString().length === 2 ? 7 : 11);
		return $('<div>'+left+'</div>').css({
			position: 'absolute',
			top: -30,
			color: '#565656',
			'text-align': 'center',
			'font-size': '13px',
			left: left === 0 ? '-3px' : 'calc('+css+' - '+px+'px)'
		});
	};

    __slider = $("#slider-range").slider({
		range: 'min',
		min: 0,
		max: 100,
		value: 50,
		slide: function(event, ui) {
			if(ui.value < 1 || ui.value > 99) return false;
			if(cur === 'lower' && ui.value > 94) return false;
			if(cur === 'higher' && ui.value < 6) return false;

			__profit(ui.value);
			$('#tooltip-value').text(ui.value);
			updateHeader(ui.value);
		}
	});
	__slider.append($('<div id="circle" class="d_slider-circle" style="display: none" />'))
		.append($('<div id="result" class="d_slider-result" style="opacity: 0">0</div>'))

		.append(stop(100, '100%'))
		.append(stop(75, '75%'))
		.append(stop(50, '50%'))
		.append(stop(25, '25%'))
		.append(stop(0, '0'));
    __slider.find(".ui-slider-handle").append(tooltip)
		.hover(function() {
			tooltip.stop(true).fadeIn('fast');
		}, function() {
			tooltip.stop(true).fadeOut('fast');
		});

    $('#i_value').on('input', function() {
    	if($(this).val() < 6 && cur === 'higher') $(this).val(94);
		if($(this).val() < 94 && cur === 'lower') $(this).val(6);
    	$('#slider-range').slider('value', $('#i_value').val());
		$('#i_chance').val((cur === 'higher' ? 100 - $('#i_value').val() : $('#i_value').val()) + '%');
    });
});

function sw() {
    cur = cur === 'lower' ? 'higher' : 'lower';

	$('#slider-range').slider('option', {
		range: cur === 'lower' ? 'min' : 'max'
	});

	$('#sw_text').html(cur === 'lower' ? 'Меньше' : 'Больше');

	var v = 100 - $('#slider-range').slider('value');
	if(cur === 'higher' && v < 6) v = 6;
	if(cur === 'lower' && v > 94) v = 94;
	if(v < 1) v = 1;
	if(v > 99) v = 99;
	$('#tooltip-value').html(v);
	$('#slider-range').slider('value', v);

	updateHeader(v);
}

function updateHeader(val) {
	var v = val == null ? $('#slider-range').slider('value') : val;
	$('#i_value').val(v);
	$('#i_chance').val((cur === 'lower' ? v : 100 - v) + '%');
}

function getDiceProfit(wager, min, max) {
	var payout;
	if(min === max) payout = 100.0;
	else {
		range = max - min;
		payout = ((100.0 - range) / range);
	}

	return (payout * wager).toFixed(2);
}

function dice() {
	if(parseFloat($('#bet_profit').html()) <= 0) {
		iziToast.error({message: 'Сумма выигрыша должна быть выше 0.<br>Подкорректируйте ставку и шанс.', icon: 'fa fa-times', position: 'bottomCenter'});
		return;
	}

	$.get('/game/dice/' + $('#bet').val() + '/' + cur + '/' + $('#slider-range').slider('value') + (isDemo ? '?demo' : ''), function(data) {
		var json = JSON.parse(data);

		if(json.error != null) {
			if(json.error === '$') load('games');
			if(json.error === -1) $('#b_si').click();
			if(json.error === 0) iziToast.error({message: 'Допустимое значение: 1% - 94%', icon: 'fa fa-times'});
			if(json.error === 1) iziToast.error({message: 'Минимальная ставка: 0.01 руб.', icon: 'fa fa-times'});
			if(json.error === 2) $('#_payin').click();
			return;
		}

		var win = json.response.result !== false;

		$('#circle').fadeIn('fast');
		$('#circle').css({
            left: 'calc(' + json.response.number + '% - 3px)',
            color: win ? 'green' : 'red'
        });

		$('#result').toggleClass('lose', !win);
		$('#result').toggleClass('win', win);

		$('#result').text(json.response.number);
		$('#result').css({opacity:1});
		$('#result').css({
			left: 'calc(' + json.response.number + '% - 16px)'
		});

		clearTimeout(timer);
		timer = setTimeout(function() {
		    $('#result').css({opacity:0});
		    $('#circle').fadeOut('fast');
        }, 7000);

		if(win && isDemo && isGuest()) showDemoTooltip();
		if(json.response.id !== -1 && !isDemo) {
			sendDrop(json.response.id);
			validateTask(json.response.id);
		}

		updateBalance();
	});
}