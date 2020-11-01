let pf_selected_game = 'dice';

function hash() {
    $.get('/provably_fair/'+$('#_server').val()+'/'+$('#_client').val(), function(response) {
        let json = JSON.parse(response);

        let hide_main_result = pf_selected_game === 'coinflip' || pf_selected_game === 'hilo'
            || pf_selected_game === 'blackjack';
        if(hide_main_result) {
            $('#number').fadeOut('fast');
            $('#number_sub').fadeOut('fast');
        } else {
            $('#number').fadeIn('fast');
            $('#number_sub').fadeIn('fast');
        }

        $('#number').html(json.result);
        $('#hash').html('');
        $('#f').html('');

        let c = chunk(json.hash, 2);
        for(let i = 0; i < c.length; i++) {
            $('#hash').append("<span style='color: "+(i < 8 ? 'white' : 'gray')+"'>"+c[i]+"</span> ");
        }
        $('#hash').append('<br>');

        if(pf_selected_game === 'dice') {
            $('#f').html(json.result + ' <i class="fal fa-percent tooltip" title="mod"></i> 100 = ' + (parseInt(json.result) % 100));
            $('#number').append(' <i class="fal fa-arrow-right"></i> ' + (parseInt(json.result) % 100));
        } else if(pf_selected_game === 'roulette') {
            $('#f').html(json.result + ' <i class="fal fa-percent tooltip" title="mod"></i> 37 = ' + (parseInt(json.result) % 37));
        } else if(pf_selected_game === 'crash') {
            let result = (parseInt(json.result) / 100);
            let p = result + ' <i class="fal fa-arrow-right"></i> ';
            if(result === 0) result = p + '1.00 (минимальное значение)';
            if(result >= 20) result = p + '20.00 (максимальное значение)';
            $('#f').html(json.result + ' <i class="fal fa-divide"></i> 100 = ' + result);
            $('#number').append(' <i class="fal fa-arrow-right"></i> ' + result);
        } else if(pf_selected_game === 'wheel') {
            let options = ["Зеленый", "Красный", "Черный", "Красный", "Черный", "Красный", "Черный", "Красный", "Черный", "Красный", "Черный", "Красный", "Черный", "Красный", "Черный"];
            let result = (parseInt(json.result) % options.length);
            result = result + ' (' + options[result] + ')';
            $('#f').html(json.result + ' <i class="fal fa-percent" title="mod"></i> '+options.length+' = ' + result);
            $('#number').html(json.result + ' <i class="fal fa-arrow-right"></i> ' + result);
        }
    });
}

$(document).ready(function() {
    hash();

    $('.fn_game').click(function() {
        $('.fn_game').removeClass('fn_game_selected');
        $(this).addClass('fn_game_selected');
        pf_selected_game = $(this).attr('data-game');
        hash();
    });
});