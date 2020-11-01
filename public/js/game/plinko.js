var pins = 8, plinkoDifficulty = 'medium';
let addPlinko, reset;

let plinkoMultipliers = [], plinkoHistory = [];
let textRender = [];

let plinkoInProgress = 0;

let buckets = {
    8: {
        1: [5, -66],
        2: [-44, -24],
        3: [-11],
        4: [-3, -100],
        5: [0.1, -7.4],
        6: [10],
        7: [0, 0.11, -0.11, 20, -77, -97],
        8: [-19, -33],
        9: [30]
    },
    9: {
        1: [-5],
        2: [1.3],
        3: [4.1, 28],
        4: [1.1],
        5: [1.8, 4],
        6: [-4],
        7: [-1.4],
        8: [2.2],
        9: [-2],
        10: [-25]
    },
    10: {
        1: [0, 1, -1.3],
        2: [-2.22],
        3: [3],
        4: [5, -70],
        5: [0.4, -9.6],
        6: [2],
        7: [0.2, 4],
        8: [-29],
        9: [6.5, -3.5],
        10: [0.7, 2.5],
        11: [66, -1]
    },
    11: {
        1: [6.66],
        2: [1.4, 22, 112, -52],
        3: [-57],
        4: [-160],
        5: [7, -1.444, 7],
        6: [1.2, -4, -50],
        7: [5, -2],
        8: [-7, -60],
        9: [3.33, 25],
        10: [0, 1.8, -2.2, 3.52, -85],
        11: [-22],
        12: [0.55, -29.3, -1.3]
    },
    12: {
        1: [0, -1.5, -1.66],
        2: [22],
        3: [-2, -2.22],
        4: [0.6, 0.21, 5.42, 77],
        5: [5.5, 27],
        6: [7, 5, 5.2, 0.051, -4.1, 55.555],
        7: [25, 55],
        8: [-7, 29.3],
        9: [0.1],
        10: [3],
        11: [128],
        12: [33],
        13: [-1, -4.42, 2.424]
    },
    13: {
        1: [0.555],
        2: [0.5, 5, -2.7],
        3: [0.7],
        4: [0.9],
        5: [0.99, -99.3],
        6: [-55],
        7: [0.12, -0.6],
        8: [0.6, -99.4],
        9: [0, -1.76, -0.2, -0.7312],
        10: [-99.2],
        11: [-3.131, -3, -99],
        12: [100],
        13: [-2.22, -121],
        14: [3.33, 77, -1]
    },
    14: {
        1: [3.7],
        2: [0.9, -3],
        3: [0.4, 0.8],
        4: [1.3],
        5: [-0.213],
        6: [-0.1],
        7: [0.6],
        8: [2.9],
        9: [0, 3.5],
        10: [0.1, 0.7],
        11: [-24, 28],
        12: [0.5, 2.8, -5, -20],
        13: [-0.8, 44],
        14: [3],
        15: [-66]
    },
    15: {
        1: [1.3, 3.1],
        2: [-68.9],
        3: [3, 5],
        4: [2.9, 5.5],
        5: [5.2],
        6: [0.4, 0.7],
        7: [1.5, -2],
        8: [0.5, 3.33],
        9: [-21.2],
        10: [0.9],
        11: [0.3, 3.2, 5.6],
        12: [0.2, 0.6],
        13: [20],
        14: [20.1],
        15: [20.2],
        16: [0, -3.1]
    },
    16: {
        1: [-150],
        2: [1.4, -3, -66],
        3: [-3.1],
        4: [1.5],
        5: [1.9],
        6: [1.2, -88],
        7: [0.8, 1.1, 1.6],
        8: [0],
        9: [1.3, 2.8],
        10: [0.9, 2.1],
        11: [2.2],
        12: [2.3, -1.2],
        13: [1.8],
        14: [3.6],
        15: [3.1, -45],
        16: [3],
        17: [2.7]
    }
};

function initPlinko(rows) {
    const {Engine, Render, World, Bodies, Events} = Matter;
    const engine = Engine.create();
    const render = Render.create({
        element: document.getElementsByClassName('plinko')[0],
        engine,
        options: {
            wireframes: false,
            background: 'rgb(8, 8, 8)',
            width: 800,
            height: 600,
            pixelRatio: 1
        }
    });

    const width = $('.plinko canvas').width(), height = $('.plinko canvas').height();
    const margin = height / 15;

    reset = function(rows) {
        World.clear(engine.world);
        textRender = [];

        const maxColumns = rows + 2;
        const padding = width / maxColumns / 2;
        const pegRadius = ((rows - 3) / ((rows - 7) / 2)) * (width / 700);
        const randomBetween = (min, max) => Math.floor(Math.random() * (max - min)) + min;

        const {world} = engine;
        const multipliers = plinkoMultipliers[plinkoDifficulty][pins];

        const makePlinko = (offset, id, profit) => {
            const r = pegRadius * 1.1;
            const center = width / 2;

            const x = center + offset;

            const fillStyle = `hsl(${randomBetween(0, 360)}, 90%, 60%)`;

            plinkoHistory[id] = profit;

            return Bodies.circle(x, 0, r, {
                restitution: 0.8,
                render: { fillStyle },
                label: 'plinko-'+id
            });
        };

        const makePeg = (x, y) => {
            return Bodies.circle(x, y, pegRadius, {
                isStatic: true,
                render: { fillStyle: '#66abf5' },
                label: 'peg'
            });
        };

        const hex = {
            0: ['#ffc000', '#997300'],
            1: ['#ffa808', '#a16800'],
            2: ['#ffa808', '#a95b00'],
            3: ['#ff9010', '#a95b00'],
            4: ['#ff7818', '#914209'],
            5: ['#ff6020', '#b93500'],
            6: ['#ff4827', '#c01d00'],
            7: ['#ff302f', '#c80100'],
            8: ['#ff1837', '#91071c'],
            9: ['#ff003f', '#990026']
        };

        const colors = {
            8: [
                hex[9], hex[7], hex[4], hex[2], hex[0], hex[2], hex[4], hex[7], hex[9]
            ],
            9: [
                hex[9], hex[7], hex[6], hex[5], hex[2], hex[2], hex[5], hex[6], hex[7], hex[9]
            ],
            10: [
                hex[9], hex[8], hex[7], hex[5], hex[4], hex[1], hex[4], hex[5], hex[7], hex[8], hex[9]
            ],
            11: [
                hex[9], hex[8], hex[7], hex[5], hex[4], hex[2], hex[2], hex[4], hex[5], hex[7], hex[8], hex[9]
            ],
            12: [
                hex[9], hex[8], hex[7], hex[6], hex[5], hex[4], hex[1], hex[4], hex[5], hex[6], hex[7], hex[8], hex[9]
            ],
            13: [
                hex[9], hex[8], hex[7], hex[6], hex[5], hex[4], hex[2], hex[2], hex[4], hex[5], hex[6], hex[7], hex[8], hex[9]
            ],
            14: [
                hex[9], hex[8], hex[7], hex[6], hex[5], hex[4], hex[3], hex[2], hex[3], hex[4], hex[5], hex[6], hex[7], hex[8], hex[9]
            ],
            15: [
                hex[9], hex[8], hex[7], hex[6], hex[5], hex[4], hex[3], hex[2], hex[2], hex[3], hex[4], hex[5], hex[6], hex[7], hex[8], hex[9]
            ],
            16: [
                hex[9], hex[8], hex[7], hex[6], hex[5], hex[4], hex[3], hex[2], hex[1], hex[2], hex[3], hex[4], hex[5], hex[6], hex[7], hex[8], hex[9]
            ]
        };

        const bucketColumnSize = (width - padding * 2) / (maxColumns - 1);
        const makeBucket = x => {
            const w = bucketColumnSize / 1.08;
            const h = height / 25;
            const y = height - h / 2;

            let index = textRender.length;
            let color = colors[pins][index];

            World.add(world, Bodies.rectangle(x, y, w, h, {
                isStatic: true,
                render: { fillStyle: color[0] },
                chamfer: { radius: 3 },
                label: 'bucket-'+index
            }));

            let text = 'x'+multipliers[index];

            textRender.push({
                text: text,
                x: x,
                y: y + ((h/3.2) / 2)
            });

            return Bodies.rectangle(x, y + (h / 2), w, h/3.2, {
                isStatic: true,
                render: { fillStyle: color[1] },
            });
        };

        const contourSize = 50;
        const contourBottom = Bodies.rectangle(width / 2, height + contourSize / 2, width, contourSize, {
            isStatic: true,
            render: { fillStyle: 'transparent' }
        });

        const contourLeft = Bodies.rectangle(0, 0, padding / 2, height * 2, {
            isStatic: true,
            render: { fillStyle: 'transparent' }
        });
        const contourRight = Bodies.rectangle(width, 0, padding / 2, height * 2, {
            isStatic: true,
            render: { fillStyle: 'transparent' }
        });

        const contours = [contourBottom, contourLeft, contourRight];
        const columnSize = (width - padding * 2) / maxColumns;

        const rowSize = (height - margin) / rows;
        const grid = Array(rows)
            .fill()
            .map((rowItem, row) => {
                const cols = row + 3;
                const dx = (columnSize * (rows - row - 1)) / 2;
                return Array(cols)
                    .fill()
                    .map((columnItem, column) => {
                        const x = padding + columnSize * column + columnSize / 2 + dx;
                        const y = rowSize * row + rowSize / 2;
                        return makePeg(x, y);
                    });
            });

        const pegs = grid.reduce((acc, curr) => [...acc, ...curr], []);
        const buckets = Array(maxColumns - 1)
            .fill()
            .map((columnItem, column) => {
                const x = bucketColumnSize * column + bucketColumnSize;
                return makeBucket(x);
            });

        World.add(world, [...contours, ...pegs, ...buckets]);

        addPlinko = function(offset, id, profit) {
            const plinko = makePlinko(offset, id, profit);
            World.add(world, plinko);
        };
    };

    $.get('/game/plinko/multipliers', function(response) {
        plinkoMultipliers = JSON.parse(response);
        reset(rows);
    });

    function handleCollision(event) {
        const {pairs} = event;

        pairs.forEach(pair => {
            const {bodyA, bodyB} = pair;
            const {label: labelA} = bodyA;
            const {label: labelB} = bodyB;

            if(labelA.includes('plinko') && labelB.includes('plinko'))
                pair.isActive = false;
            if(labelB.includes('plinko') && labelA.includes('bucket')) {
                let index = labelB.split('plinko-')[1];
                console.log('Collide - ' + index + ' (profit: ' + plinkoHistory[index] + ')');
                if(plinkoHistory[index] === undefined) return;

                World.remove(engine.world, bodyB);
                plinkoInProgress--;
                if(plinkoInProgress <= 0) {
                    setBetText('Играть');
                    plinkoInProgress = 0;
                }

                updateBalance(undefined, parseFloat(plinkoHistory[index]));
                delete plinkoHistory[index];
            }
        });
    }

    Events.on(engine, 'collisionStart', handleCollision);
    Events.on(render, 'afterRender', () => {
        if($('.plinko canvas').length === 0) return;
        const ctx = $('.plinko canvas')[0].getContext('2d');
        ctx.font = "14px Open Sans";
        ctx.fillStyle = "black";
        ctx.textAlign = "center";

        for(let i = 0; i < textRender.length; i++) {
            let text = textRender[i];
            ctx.fillText(text.text, text.x, text.y);
        }
    });

    Engine.run(engine);
    Render.run(render);
}

$(document).ready(function() {
    reloadCSS(function() {
        initPlinko(8);
    });

    $('*[data-pin]').on('click', function(e) {
        if(plinkoInProgress > 0) return;
        $('*[data-pin]').toggleClass('bc_active', false);
        $(this).toggleClass('bc_active', true);

        pins = parseInt($(this).attr('data-pin'));
        reset(pins);
    });
    $('*[data-plinko-difficulty]').on('click', function() {
        if(plinkoInProgress > 0) return;
        plinkoDifficulty = $(this).attr('data-plinko-difficulty');
        $('.buttons-3-selected').removeClass('buttons-3-selected');
        $(this).addClass('buttons-3-selected');

        reset(pins);
    });
});

function drop(bucket, id, profit) {
    let randomPosition = buckets[pins][bucket][Math.floor(Math.random() * buckets[pins][bucket].length)];
    addPlinko(randomPosition, id, profit);
    console.log('Dropping game id ' + id +' in ' + bucket + ' - ' + randomPosition);
}

function debug(offset) {
    addPlinko(offset, Math.random(), 0);
}

function plinko() {
    if(parseFloat($('#g_balance').html()) <= 0) return;
    plinkoInProgress++;
    $.get('/game/plinko/'+plinkoDifficulty+'/'+pins+'/'+$('#bet').val()+(isDemo ? '?demo' : ''), function(response) {
        let json = JSON.parse(response);
        console.log(json);
        if(json.error != null) {
            if(json.error === '$') load('games');
            if(json.error === -1) $('#b_si').click();
            if(json.error === 0) iziToast.error({message: 'Количество пинов - от 8 до 16', icon: 'fa fa-times', position: 'bottomCenter'});
            if(json.error === 1) iziToast.error({message: 'Минимальная ставка: 0.01 руб.', icon: 'fa fa-times', position: 'bottomCenter'});
            if(json.error === 2) $('#_payin').click();
            if(json.error === 3) iziToast.error({message: 'Invalid risk level', icon: 'fa fa-times', position: 'bottomCenter'});
            plinkoInProgress--;
            return;
        }

        if(!isDemo)
            setTimeout(function() {
                sendDrop(json.id);
                validateTask(13);
            }, 5000);

        setBetText('Бросить еще');
        updateBalance(undefined, -parseFloat($('#bet').val()));
        drop(json.bucket, json.id, json.profit.toFixed(2));
    });
}