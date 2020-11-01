"use strict";
let secure = window.location.protocol === 'https:';
let msg_socket = null;

let dialogPage = 0;

function showDate(time) {
    time = (Date.now()/1000) - time;
    if (time < 60) return 'меньше минуты назад';
    else if (time < 3600) return dimension(parseInt(time / 60), 'i');
    else if (time < 86400) return dimension(parseInt(time / 3600), 'G');
    else if (time < 2592000) return dimension(parseInt(time / 86400), 'j');
    else if (time < 31104000) return dimension(parseInt(time / 2592000), 'n');
    else if (time >= 31104000) return dimension(parseInt(time / 31104000), 'Y');
}

function dimension(time, type) {
    let dimension = {
        'n': ['месяцев', 'месяц', 'месяца', 'месяц'],
        'j': ['дней', 'день', 'дня'],
        'G': ['часов', 'час', 'часа'],
        'i': ['минут', 'минуту', 'минуты'],
        'Y': ['лет', 'год', 'года']
    };

    let n;
    if (time >= 5 && time <= 20) n = 0;
    else if (time === 1 || time % 10 === 1) n = 1;
    else if ((time <= 4 && time >= 1) || (time % 10 <= 4 && time % 10 >= 1)) n = 2;
    else n = 0;
    return time+' '+dimension[type][n]+' назад';
}

$(document).ready(function() {
    msg_socket = io('w'+(secure ? 'ss' : 's')+'://'+window.location.hostname+':3002', {transports: ['websocket'], secure: secure, rejectUnauthorized: false});

    msg_socket.on('connect', function() {
        console.log('Connected to :3002');
        dialogPage = 0;
        msg_socket.emit('dialogs', dialogPage);

        msg_socket.on('dialogs', function(msg) {
            let json = JSON.parse(msg);
            dialogPage += 1;

            for(let i = 0; i < json.length; i++) {
                let conversation = json[i];
                console.log(conversation);
                $('#dialogs').append(`
                    <div class="kt-widget__item">
                        <span class="kt-media kt-media--circle">
                            <img src="`+conversation.user.photo_100+`" alt="image">
                        </span>
                        <div class="kt-widget__info">
                            <div class="kt-widget__section">
                                <a href="javascript:void(0)" class="kt-widget__username">`+(conversation.conversation.chat_settings == null ? conversation.user.first_name + ' ' + conversation.user.last_name : conversation.conversation.chat_settings.title)+`</a>
                            </div>
    
                            <span class="kt-widget__desc">
                                `+conversation.last_message.text+`
                            </span>
                        </div>
                        <div class="kt-widget__action">
                            <span class="kt-widget__date">`+showDate(conversation.last_message.date)+`</span>
                            `+(conversation.conversation.unread_count != null ? `<span class="kt-badge kt-badge--success kt-font-bold">`+conversation.conversation.unread_count+`</span>` : '')+`
                        </div>
                    </div>`);
            }
        })
    });
});

var KTAppChat = function() {
    var t;
    return {
        init: function() {
            t = KTUtil.getByID("kt_chat_aside"),
                function() {
                    new KTOffcanvas(t, {
                        overlay: !0,
                        baseClass: "kt-app__aside",
                        closeBy: "kt_chat_aside_close",
                        toggleBy: "kt_chat_aside_mobile_toggle"
                    });
                    var i = KTUtil.find(t, ".kt-scroll");
                    i && KTUtil.scrollInit(i, {
                        mobileNativeScroll: !0,
                        desktopNativeScroll: !1,
                        resetHeightOnDestroy: !0,
                        handleWindowResize: !0,
                        rememberPosition: !0,
                        height: function() {
                            var i, s = KTUtil.find(t, ".kt-portlet > .kt-portlet__body"),
                                e = KTUtil.find(t, ".kt-widget.kt-widget--users"),
                                n = KTUtil.find(t, ".kt-searchbar");
                            return i = KTUtil.isInResponsiveRange("desktop") ? KTLayout.getContentHeight() : KTUtil.getViewPort().height, t && (i = (i = i - parseInt(KTUtil.css(t, "margin-top")) - parseInt(KTUtil.css(t, "margin-bottom"))) - parseInt(KTUtil.css(t, "padding-top")) - parseInt(KTUtil.css(t, "padding-bottom"))), e && (i = (i = i - parseInt(KTUtil.css(e, "margin-top")) - parseInt(KTUtil.css(e, "margin-bottom"))) - parseInt(KTUtil.css(e, "padding-top")) - parseInt(KTUtil.css(e, "padding-bottom"))), s && (i = (i = i - parseInt(KTUtil.css(s, "margin-top")) - parseInt(KTUtil.css(s, "margin-bottom"))) - parseInt(KTUtil.css(s, "padding-top")) - parseInt(KTUtil.css(s, "padding-bottom"))), n && (i = (i -= parseInt(KTUtil.css(n, "height"))) - parseInt(KTUtil.css(n, "margin-top")) - parseInt(KTUtil.css(n, "margin-bottom"))), i -= 5
                        }
                    })
                }(), KTChat.setup(KTUtil.getByID("kt_chat_content")), KTUtil.getByID("kt_app_chat_launch_btn") && setTimeout(function() {
                KTUtil.getByID("kt_app_chat_launch_btn").click()
            }, 1e3)
        }
    }
}();
KTUtil.ready(function() {
    KTAppChat.init()
});