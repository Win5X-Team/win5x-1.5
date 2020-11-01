$(document).ready(function() {
    $("#obf_tree").jstree({
        core: {
            themes: {
                responsive: !1
            }
        }, types: {
            default: {
                icon: "fa fa-file kt-font-warning"
            }, file: {
                icon: "fa fa-file kt-font-warning"
            }, folder: {
                icon: "fa fa-folder kt-font-warning"
            }
        }, plugins:["types"]
    });

    let secure = window.location.protocol === 'https:';
    if(secure) return;

    let socket = io('ws://'+window.location.hostname+':3001', {transports: ['websocket'], secure: false, rejectUnauthorized: false});

    socket.on('connect', function() {
        $('#obf_status').html('Обфусификация возможна');
        $('#obfuscate').removeAttr('disabled');
    });
    socket.on('disconnect', function() {
        $('#obf_status').html('Отсутствует соединение с сервером');
        $('#obfuscate').attr('disabled', 'disabled');
    });
    socket.on('finished', function() {
        iziToast.success({
            message: 'Обфусификация завершена!',
            icon: 'fal fa-check',
            position: 'bottomCenter'
        });
        KTApp.unblock('#obfuscate');
    });

    $('#obfuscate').on('click', function() {
        if(!socket.connected) return;
        KTApp.block('#obfuscate',{overlayColor:"#000000",type:"v2",state:"success",message:"Обфусификация..."});

        socket.emit('length', __obfuscate_this.length);
        for(let i = 0; i < __obfuscate_this.length; i++)
            socket.emit('obfuscate', __obfuscate_this[i]);
    });
});