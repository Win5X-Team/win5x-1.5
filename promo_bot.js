const app = require('express')();
const easyvk = require('easyvk');
var fs = require('fs');
var requestify = require('requestify');

let __LOCALHOST = false, http;
if(__LOCALHOST) http = require('http').createServer(app); else {
    http = require('https').createServer({
        key: fs.readFileSync('/etc/letsencrypt/live/win5x.com/privkey.pem', 'utf8'),
        cert: fs.readFileSync('/etc/letsencrypt/live/win5x.com/cert.pem', 'utf8')
    }, app);
}
const io = require('socket.io')(http), domain = __LOCALHOST ? 'http://localhost' : 'https://win5x.com';

let vk, targets = [];

let sendTo = function(id) {
    requestify.get(domain+'/n/node_gen_promo').then(function(response) {
        if(response.body.length > 10) {
            console.log('response.body.length > 10');
            console.log(response.body);
            return;
        }
        vk.call("messages.send", {
            user_id: id,
            message: `Промокод для группы -> `+response.body+`\nСумма - 2 руб., количество активаций не ограничено, будет удален через 24 часа.\nСледующий новый промокод будет отправлен автоматически через 24 часа.\nОтвечать на это сообщение не требуется.`
        }).then(({ vkr: Response }) => {
            console.log(Response);
        }).catch(error => {
            console.error(error);
        });
    });
};

function send() {
    for(let i = 0; i < targets.length; i++) sendTo(targets[i]);
}

function setTimer() {
    let now = new Date(), millisTill10 = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 11, 0, 0, 0) - now;
    if(millisTill10 < 0) millisTill10 += 86400000;
    setTimeout(function() {
        send();
        setTimer();
    }, millisTill10);
}

easyvk({
    access_token: 'VK GROUP ACCESS TOKEN' // <-- изменить на access token группы вк
}).then(_vk => {
    vk = _vk;
    console.log(vk.session.group_id);

    requestify.get(domain+'/admin/promo_list/-2')
        .then(function(response) {
            response = JSON.parse(response.body);
            for(let i = 0; i < response.length; i++) targets.push(response[i].vk_id);

            setTimer();
        });
});

http.listen(2052, function(){
    console.log('promo_bot.js listening on *:2052');
});

process.on('uncaughtException', function (exception) {
    console.log(exception);
});

io.on('connection', function(socket) {
    socket.on('new', function(msg) {
        let id = parseInt(msg.id);
        sendTo(id);
        targets.push(id);
    });
});
