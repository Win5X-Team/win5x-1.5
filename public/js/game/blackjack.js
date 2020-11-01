(function () {
    var game = new Game(),
        player = new Player(),
        dealer = new Player(),
        running = false,
        blackjack = false,
        deal,
        blackjackId;

    function Player() {
        var hand  = [],
            ele   = '',
            score = '';

        this.getElements = function() {
            if(this === player) {
                ele   = '#phand';
                score = '.player';
            } else {
                ele   = '#dhand';
                score = '.dealer';
            }

            return {'ele': ele, 'score': score};
        };

        this.getHand = function() {
            return hand;
        };

        this.setHand = function(card) {
            hand.push(card);
        };

        this.resetHand = function() {
            hand = [];
        };

        this.flipCards = function(dealerSecretOptions) {
            $('.down').each(function() {
                $(this).removeClass('down').addClass('up');
                renderCard(false, false, $(this), false, dealerSecretOptions);
            });

            dealer.getScore('dealer', function(response) {
                $('.dealer').html(response);
            });
        };
    }

    function Card(card) {
        this.getIndex = function() {
            return card.index;
        };

        this.getType = function() {
            return card.type;
        };

        this.getRank = function() {
            return card.rank;
        };

        this.getSuit = function() {
            return card.suit;
        };

        this.getValue = function() {
            var rank = this.getRank(), value;

            if(rank === 'A') value = 11;
            else if(rank === 'K') value = 10;
            else if(rank === 'Q') value = 10;
            else if(rank === 'J') value = 10;
            else value = parseInt(rank, 0);

            return value;
        };
    }

    function Deal() {
        this.setCard = function(sender, card) {
            sender.setHand(card);
        };

        this.dealCard = function(obj, card, isHiddenByServer) {
            var sender = obj,
                elements = obj.getElements(),
                score = elements.score,
                ele = elements.ele,
                dhand = dealer.getHand();

            deal.setCard(sender, card);

            renderCard(ele, sender, false, isHiddenByServer);
            sender.getScore(score === '.dealer' ? 'dealer' : 'player', function(response) {
                $(score).html(response);
            });

            if (player.getHand().length < 3) {
                if (dhand.length > 0 && dhand[0].rank === 'A') setActions('insurance');

                player.getScore('player', function(response) {
                    if(response === 21) {
                        if(blackjack) return;
                        player.stand();
                        blackjack = true;
                    } else {
                        if (dhand.length > 1) setActions('run');
                    }
                });
            }
        }
    }

    function Game() {
        this.newGame = function() {
            $.get('/game/blackjack/' + $('#bet').val() + (isDemo ? '?demo' : ''), function(data) {
                let json = JSON.parse(data);
                blackjackId = json.id;

                if (json.error != null) {
                    if (json.error === '$') load('games');
                    if (json.error === -1) $('#b_si').click();
                    if (json.error === 1) iziToast.error({message: 'Минимальная ставка: 0.01 руб.', icon: 'fa fa-times'});
                    if (json.error === 2) $('#_payin').click();
                    return;
                }

                _disableDemo = true;
                updateBalance();

                $('#play').fadeOut('fast');
                resetBoard();

                deal = new Deal();
                running = true;
                blackjack = false;

                player.resetHand();
                dealer.resetHand();

                setTimeout(function() {
                    deal.dealCard(player, {
                        'index': json.player[0].index,
                        'rank' : json.player[0].value,
                        'suit' : json.player[0].type,
                        'value': json.player[0].blackjack_value,
                        'type': 'up'
                    });
                    setTimeout(function() {
                        deal.dealCard(dealer, {
                            'index': json.dealer.index,
                            'rank': json.dealer.value,
                            'suit': json.dealer.type,
                            'value': json.dealer.blackjack_value,
                            'type': 'up'
                        });
                        setTimeout(function() {
                            deal.dealCard(player, {
                                'index': json.player[1].index,
                                'rank': json.player[1].value,
                                'suit': json.player[1].type,
                                'value': json.dealer.blackjack_value,
                                'type': 'up',
                            });
                            setTimeout(function() {
                                deal.dealCard(dealer, {
                                    'index': 1,
                                    'rank': '',
                                    'suit': '',
                                    'value': 0,
                                    'type': 'down'
                                }, true);

                                if(running) $('#blackjack_controls').fadeIn('fast');
                            }, 500);
                        }, 500);
                    }, 500);
                }, 500);
            });
        };
    }

    Player.prototype.hit = function(dbl) {
        $.get('/game/blackjack/hit/'+blackjackId+(isDemo ? '?demo' : ''), function(response) {
            let json = JSON.parse(response);

            deal.dealCard(player, {
                'index': json.player.index,
                'rank': json.player.value,
                'suit': json.player.type,
                'value': json.player.blackjack_value,
                'type': 'up'
            });

            player.getScore('player', function(response) {
                if(dbl || response > 21) {
                    running = false;

                    setTimeout(function() {
                        player.stand();
                    }, 500);
                } else player.getHand();

                setActions();
                player.updateBoard();
            });
        });
    };

    Player.prototype.stand = function() {
        running = false;
        setActions();

        $.get('/game/blackjack/stand/' + blackjackId + (isDemo ? '?demo' : ''), function(response) {
            let json = JSON.parse(response);
            let responseJson = JSON.parse(json.status);

            showAlert(responseJson.type === 'error', responseJson.header, responseJson.message);

            if(!isDemo) sendDrop(blackjackId);
            _disableDemo = false;

            dealer.flipCards({
                'rank': json.dealerReveal['value'],
                'suit': '<i class="' + deck.toIcon(deck[json.dealerReveal['index']])+'"></i>',
                'value': json.dealerReveal['blackjack_value'],
                'dealerScore': json.dealerScore
            });

            for(let i = 0; i < json.dealerDraw.length; i++) {
                deal.dealCard(dealer, {
                    'index': json.dealerDraw[i].index,
                    'rank': json.dealerDraw[i].value,
                    'suit': json.dealerDraw[i].type,
                    'value': json.dealerDraw[i].blackjack_value,
                    'type': 'up'
                });
            }

            dealer.updateBoard();
            updateBalance();
        });
    };

    Player.prototype.dbl = function() {
        if($('#double').hasClass('bb_disabled')) return;

        $.get('/game/blackjack/double/'+blackjackId+(isDemo?'?demo':''), function(response) {
            let json = JSON.parse(response);
            if(json.error != null) {
                if(json.error === -1) iziToast.error({message: 'Необходима авторизация.', icon: 'fa fa-times', position: 'bottomCenter'});
                if(json.error === 1) iziToast.error({message: 'Игра не найдена.', icon: 'fa fa-times', position: 'bottomCenter'});
                if(json.error === 0) console.log('Server cancelled input');
                if(json.error === 2) iziToast.error({message: 'У вас недостаточно денег на счете для удвоения ставки.', icon: 'fa fa-times', position: 'bottomCenter'});
                return;
            }

            $('#double').toggleClass('bb_disabled', true);
            player.hit(true);
            updateBalance();
            _disableDemo = false;
        });
    };

    Player.prototype.insure = function() {
        $.get('/game/blackjack/insure/'+blackjackId+(isDemo?'?demo':''), function(response) {
            $('.insurance').fadeOut('fast');

            let json = JSON.parse(response);
            if(json.error != null) {
                if(json.error === -1) iziToast.error({message: 'Необходима авторизация.', icon: 'fa fa-times', position: 'bottomCenter'});
                if(json.error === 1) iziToast.error({message: 'Игра не найдена.', icon: 'fa fa-times', position: 'bottomCenter'});
                if(json.error === 0) console.log('Server cancelled input');
                if(json.error === 2) iziToast.error({message: 'У вас недостаточно денег на счете для покупки страховки.', icon: 'fa fa-times', position: 'bottomCenter'});
                return;
            }

            iziToast.success({message: 'Вы купили страховку.', icon: 'fas fa-info-circle', position: 'bottomCenter'});
            updateBalance();
        });
    };

    Player.prototype.getScore = function(type, callback) {
        $.get('/game/blackjack/score/'+type+'/'+blackjackId+(isDemo?'?demo':''), function(response) {
            callback(parseInt(response));
        })
    };

    Player.prototype.updateBoard = function() {
        var score = '.dealer';
        if(this === player) score = '.player';

        this.getScore(score === '.dealer' ? 'dealer' : 'player', function(response) {
            $(score).html(response);
        });
    };

    function showAlert(isRed, header, msg) {
        $('.wheel_game_result').toggleClass('wg_lose', isRed);
        $('.wheel_game_result').toggleClass('wg_win', !isRed);
        $('.mul').html(header);
        $('.te').html(msg);
        $('.wheel_game_result').fadeIn('fast');
    }

    function hideAlert() {
        $('.wheel_game_result').fadeOut('fast');
    }

    function setActions(opts) {
        var hand = player.getHand();

        if(!running) {
            $('#play').fadeIn('fast');
            $('#blackjack_controls').fadeOut('fast');

            $('#double').toggleClass('bb_disabled', true);
            $('#split') .toggleClass('bb_disabled', true);
            $('.insurance').fadeOut('fast');
        }

        if(opts === 'run') {
            $('#double').toggleClass('bb_disabled', false);
        } else if(opts === 'split') {
            $('#split').toggleClass('bb_disabled', false);
        } else if(opts === 'insurance') {
            $('.insurance').fadeIn('fast');
        } else if(hand.length > 2) {
            $('#double')   .toggleClass('bb_disabled', true);
            $('#split')    .toggleClass('bb_disabled', true);
            $('.insurance').fadeOut('fast');
        }
    }

    function renderCard(ele, sender, item, isHiddenByServer, secretRevealOptions) {
        var hand, i, card;

        if(!item) {
            hand = sender.getHand();
            i    = hand.length - 1;
            card = new Card(hand[i]);
        } else {
            hand = dealer.getHand();
            card = new Card(hand[1]);
        }

        if(secretRevealOptions !== undefined) {
            card.rank = secretRevealOptions.rank;
            card.suit = secretRevealOptions.suit;
            card.value = secretRevealOptions.value;
        }

        var	rank  = card.getRank(),
            suit  = card.getSuit(),
            color = 'red',
            posx  = 350,
            posy  = 20,
            speed = 200,
            cards = ele + ' .card-' + i,
            type = card.getType();

        if(i > 0) posx -= 50 * i;

        if(!item) {
            $(ele).append(
                '<div class="'+(isHiddenByServer !== undefined && isHiddenByServer === true ? 'dealerSecret ' : '')+'blackjack_card card-' + i + ' ' + type + '">' +
                '<span class="pos-0">' +
                '<span class="rank">&nbsp;</span>' +
                '<span class="suit">&nbsp;</span>' +
                '</span>' +
                '</div>'
            );

            if(ele === '#phand') {
                posy  = 340;
                speed = 500;
                $(ele + ' div.card-' + i).attr('id', 'pcard-' + i);

                if(hand.length < 2) {
                    setTimeout(function() {
                        player.getScore('player', function(response) {
                            $('.player').html(response).fadeIn('fast');
                        });
                    }, 500);
                }
            } else {
                $(ele + ' div.card-' + i).attr('id', 'dcard-' + i);

                if(hand.length < 2) {
                    setTimeout(function() {
                        dealer.getScore('dealer', function(response) {
                            $('.dealer').html(response).fadeIn('fast');
                        });
                    }, 100);
                }
            }

            $(ele + ' .card-' + i).css('z-index', i);

            $(ele + ' .card-' + i).animate({
                'top': posy,
                'right': posx
            }, speed);

            $(ele).queue(function() {
                $(this).animate({ 'left': '-=25.5px' }, 100);
                $(this).dequeue();
            });
        } else {
            cards = item;
        }

        if(type === 'up' || item) {
            if(suit !== 'hearts' && suit !== 'diamonds') color = 'black';

            $(cards).find('span[class*="pos"]').addClass('card_history_'+color);

            if(secretRevealOptions === undefined) $(cards).find('span.rank').html(rank);
            else {
                // TODO Set color
                $('.dealerSecret span.rank').html(secretRevealOptions.rank);
                setTimeout(function() {
                    $('.dealer').html(secretRevealOptions.dealerScore);
                }, 50);
            }

            $(cards).find('span.suit').html('<i class="'+deck.toIcon(deck[card.getIndex()])+'"></i>');
        }
    }

    function resetBoard() {
        $('#dhand').html('');
        $('#phand').html('');
        $('#phand, #dhand').css('left', 0);
        $('.dealer').fadeOut('fast');
        $('.player').fadeOut('fast');
        $('.insurance').fadeOut('fast');
    }

    $('#play').on('click', function() {
        if(running) return;
        hideAlert();

        game.newGame();
    });

    $('#hit').on('click', function() {
        player.hit();
    });

    $('#stand').on('click', function() {
        $('.insurance').fadeOut('fast');
        player.stand();
    });

    $('#double').on('click', function() {
        player.dbl();
    });

    $('#insurance_accept').on('click', function() {
        player.insure();
    });

    $('#insurance_cancel').on('click', function() {
        $('.insurance').fadeOut('fast');
    });
}());