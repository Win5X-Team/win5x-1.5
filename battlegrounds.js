const app = require('express')();
const requestify = require('requestify');
var fs = require('fs');

let __LOCALHOST = true, http = require('http').createServer(app);
/*if(__LOCALHOST) http = require('http').createServer(app); else {
    http = require('https').createServer({
        key: fs.readFileSync('/etc/letsencrypt/live/win5x.com/privkey.pem', 'utf8'),
        cert: fs.readFileSync('/etc/letsencrypt/live/win5x.com/cert.pem', 'utf8')
    }, app);
}*/
const io = require('socket.io')(http), domain = __LOCALHOST ? 'http://localhost' : 'http://win5x.com';

var games = [];
var liveGame = null;

http.listen(2087, function(){
    console.log('battlegrounds.js listening on *:2087');
    var system_key = "wwuu881x";

    setInterval(function() {
        if(liveGame == null) io.emit('live', '-1');
        else io.emit('live', JSON.stringify(liveGame));

        for(let i = 0; i < games.length; i++) {
            let game = games[i];

            if(liveGame == null && (game.state === 'game_start' || game.state === 'bets')) liveGame = game;

            if(game.state === 'waiting' && game.players.length >= 3) {
                game.timer -= 1;
                if(game.timer <= 0 || game.players.length >= 5) {
                    game.timer = 60;
                    game.state = 'bets';
                    io.to(game.id).emit('redirect', JSON.stringify(game));
                } else io.to(game.id).emit('update', JSON.stringify(game));
            } else if(game.state === 'bets') {
                game.timer -= 1;
                if(game.timer <= 0 || Object.keys(game.bets).length === game.players.length) {
                    let reward = 0;
                    for(let j = 0; j < game.players.length; j++) {
                        if(game.bets[game.players[j]] === undefined) {
                            game.bets[game.players[j]] = 0.01;
                        }

                        requestify.get(domain+'/remove_balance/'+game.players[j]+'/'+game.bets[game.players[j]]+'/'+system_key);

                        reward += parseFloat(game.bets[game.players[j]]);
                    }
                    game.reward = parseFloat(reward.toFixed(2));

                    for(let j = 0; j < game.players.length; j++) {
                        game.p[game.players[j]] = parseFloat(game.bets[game.players[j]]) / game.reward * 100;
                    }

                    game.state = 'game_start';
                    game.timer = 15;
                }
                io.to(game.id).emit('update', JSON.stringify(game));
            } else if(game.state === 'game_start') {
                if(game.timer > 0) game.timer -= 1;
                if(game.timer === 0) {
                    let recursivePick = function() {
                        let pick = function() {
                            try {
                                let randomize = function (a, b) {
                                    return Math.random() - 0.5;
                                };

                                let winner = Math.random() * 100;
                                let threshold = 0;
                                game.players.sort(randomize);
                                for (let i = 0; i < game.players.length; i++) {
                                    threshold += parseFloat(game.p[game.players[i]]);
                                    if ((100 - threshold) > winner) return game.players[i];
                                }
                                return game.players[game.players.length * Math.random() | 0];
                            } catch(e) {
                                return pick();
                            }
                        };
                        let res = pick();
                        if(game.dead.includes(res)) return recursivePick();
                        return res;
                    };
                    let eliminated = recursivePick();

                    game.dead.push(eliminated);
                    game.timer = -1;
                    io.to(game.id).emit('spin', eliminated);
                    if(game.id === liveGame.id) io.emit('live spin', eliminated);

                    setTimeout(function() {
                        game.timer = 15;
                    }, 11000);
                }
                if(game.dead.length === game.players.length - 1) {
                    game.state = 'finished';
                    let winner_id = null;
                    for(let i = 0; i < game.players.length; i++) {
                        if(game.dead.includes(game.players[i])) continue;
                        winner_id = game.players[i];
                        break;
                    }

                    requestify.get(domain+'/game/battlegrounds/'+winner_id+'/'+game.reward+'/'+JSON.stringify(game.players)+'/'+system_key)
                        .then(function() {
                            game.winner = winner_id;
                            setTimeout(function() {
                                if(liveGame.id === game.id) {
                                    liveGame = null;
                                    io.emit('live over');
                                }

                                io.to(game.id).emit('finished', JSON.stringify(game));
                            }, 11000);
                        });
                }

                io.to(game.id).emit('update', JSON.stringify(game));
            } else game.timer = 10;
        }
    }, 1000);
});

function getOrCreateRoom() {
    for(let i = 0; i < games.length; i++) {
        let game = games[i];
        if(game.state === 'waiting' && game.players.length < 5) return game;
    }
    let game = {
        id: (games.length + 1).toString(),
        players: [],
        dead: [],
        state: 'waiting',
        timer: 10,
        bets: {},
        p: {},
        reward: -1,
        winner: -1
    };
    games.push(game);
    return game;
}

process.on('uncaughtException', function (exception) {
    console.log(exception);
});

io.on('connection', function(socket) {
    var game = null, user_id = -1;

    let leave = function() {
        if(game == null || game.state !== 'waiting') return;
        let index = game.players.indexOf(user_id);
        if(index > -1) game.players.splice(index, 1);

        io.to(game.id).emit('update', JSON.stringify(game));
        socket.emit('leave');
        socket.leave(game.id);
        game = null;
        user_id = -1;
    };

    socket.on('connect to game', function(msg) {
        var json = JSON.parse(msg);
        user_id = json.user_id;

        requestify.get(domain+'/game/battlegrounds/check/'+user_id+'/0.01')
            .then(function(response) {
                response = parseInt(response.body);
                if(response !== 1) {
                    socket.emit('connection error');
                    return;
                }

                game = getOrCreateRoom();
                if(game.players.includes(json.user_id)) {
                    let index = game.players.indexOf(json.user_id);
                    if(index > -1) game.players.splice(index, 1);
                    game = getOrCreateRoom();
                }

                game.players.push(user_id);
                socket.join(game.id);

                io.to(game.id).emit('update', JSON.stringify(game));
            });
    });
    socket.on('bet', function(msg) {
        var sum = parseFloat(msg);

        if(game.state !== 'bets') {
            socket.emit('bet confirm', -2);
            return;
        }
        if(isNaN(sum)) {
            socket.emit('bet confirm', -1);
            return;
        }
        if(sum < 0.01) {
            socket.emit('bet confirm', 0);
            return;
        }
        if(game.bets[user_id] !== undefined) {
            socket.emit('bet confirm', 2);
            return;
        }

        requestify.get(domain+'/game/battlegrounds/check/'+user_id+'/'+sum)
            .then(function(response) {
                response = parseInt(response.body);
                if(response !== 1) {
                    socket.emit('bet confirm', 4);
                    return;
                }

                game.bets[user_id] = sum.toFixed(2);
                socket.emit('bet confirm', 3);
                io.to(game.id).emit('update', JSON.stringify(game));
            });
    });
    socket.on('leave', function() {
        leave();
    });
    socket.on('disconnect', function() {
        leave();
    });
});
