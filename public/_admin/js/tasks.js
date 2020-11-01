var g_id = 3;
function desc(game_id) {
    g_id = game_id;
    let content = ''; switch(game_id) {
        case 3: content = 'Достигнуть значения коэффициента x$'; break;
        case 4: content = 'Угадать сторону $ раз подряд'; break;
        case 9: content = 'Открыть ячейки $ раз подряд'; break;
        case 5: content = 'Открыть ячейки $ раз подряд'; break;
        case 1: content = 'Получить число $'; break;
        case 7: content = 'Угадать карты $ раз подряд'; break;
    }
    $('#desc').html(content);
}

function v2uts(value) {
    return Math.floor(new Date(value).getTime() / 1000);
}

$(document).ready(function() {
    $.fn.datetimepicker.dates['ru'] = {
        days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье"],
        daysShort: ["Вс", "Пон", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"],
        daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"],
        months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
        monthsShort: ["Янв", "Феб", "Март", "Апр", "Май", "Июнь", "Июль", "Авг", "Сен", "Окт", "Ноя", "Дек"],
        today: "Сегодня",
        meridiem: '',
    };

    $("#start, #end").datetimepicker({
        todayHighlight: true,
        autoclose: true,
        pickerPosition: "bottom-right",
        format: "mm/dd/yyyy hh:ii",
        language: 'ru'
    });

    $("#price, #reward").TouchSpin( {
        buttondown_class: "btn btn-secondary",
        buttonup_class: "btn btn-secondary",
        postfix: 'руб.',
        min: 0, max: 10000, step: .01, decimals: 2, boostat: 5, maxboostedstep: 10
    });

    $(".kt-selectpicker").selectpicker();

    desc(3);

    $("#sel").on("changed.bs.select", function(e, clickedIndex, newValue, oldValue) {
        desc(parseInt($('*[data-index="'+clickedIndex+'"]').attr('data-game-id')));
    });

    addToolbar('<div class="kt-subheader__group">' +
        '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#new">Создать</button>' +
        '</div>');

    $('#create').on('click', function() {
        ///task/create/{start_time}/{end_time}/{game_id}/{value}/{reward}/{price}
        send('#new', '/admin/task/create/'+v2uts($('#start').val())+'/'+v2uts($('#end').val())+'/'+g_id+'/'+$('#value').val()+'/'+$('#reward').val()+'/'+$('#price').val(), function() {
            window.location.reload();
        });
    });
});