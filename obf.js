const app = require('express')();
const fs = require('fs');
const http = require('http').createServer(app);
const io = require('socket.io')(http);
const requestify = require('requestify');

const obfuscator = require('javascript-obfuscator');

var presets = {
    low_performance: {
        compact: true,
        controlFlowFlattening: true,
        controlFlowFlatteningThreshold: 1,
        deadCodeInjection: true,
        deadCodeInjectionThreshold: 1,
        debugProtection: true,
        debugProtectionInterval: true,
        disableConsoleOutput: true,
        identifierNamesGenerator: 'hexadecimal',
        log: false,
        renameGlobals: false,
        rotateStringArray: true,
        selfDefending: true,
        splitStrings: true,
        splitStringsChunkLength: 5,
        stringArray: true,
        stringArrayEncoding: 'rc4',
        stringArrayThreshold: 1,
        transformObjectKeys: true,
        unicodeEscapeSequence: false
    },
    optimal: {
        compact: true,
        controlFlowFlattening: true,
        controlFlowFlatteningThreshold: 0.75,
        deadCodeInjection: true,
        deadCodeInjectionThreshold: 0.4,
        debugProtection: false,
        debugProtectionInterval: false,
        disableConsoleOutput: true,
        identifierNamesGenerator: 'hexadecimal',
        log: false,
        renameGlobals: false,
        rotateStringArray: true,
        selfDefending: true,
        splitStrings: true,
        splitStringsChunkLength: 10,
        stringArray: true,
        stringArrayEncoding: 'base64',
        stringArrayThreshold: 0.75,
        transformObjectKeys: true,
        unicodeEscapeSequence: false
    },
    high_performance: {
        compact: true,
        controlFlowFlattening: false,
        deadCodeInjection: false,
        debugProtection: false,
        debugProtectionInterval: false,
        disableConsoleOutput: true,
        identifierNamesGenerator: 'hexadecimal',
        log: false,
        renameGlobals: false,
        rotateStringArray: true,
        selfDefending: true,
        splitStrings: false,
        stringArray: true,
        stringArrayEncoding: false,
        stringArrayThreshold: 0.75,
        unicodeEscapeSequence: false
    }
};

/**
 * Note: this script uses same port as battlegrounds game!
 */
http.listen(3001, function(){
    console.log('obf.js listening on *:3001');
});

process.on('uncaughtException', function (exception) {
    console.log(exception);
});

io.on('connection', function(socket) {
    let length = -1;

    socket.on('length', function(message) {
        length = parseInt(message);
    });

    socket.on('obfuscate', function(message) {
        console.log('Obfuscating ' + message + '...');

        requestify.get('http://localhost/js'+message).then(function(response) {
            let source = response.body;
            let result = obfuscator.obfuscate(source, presets.high_performance).getObfuscatedCode();
            fs.writeFile("public/js/dist"+message, result, function(err) {
                if(err) return console.log(err);
                console.log("Saved " + message);

                length--;
                if(length === 0) socket.emit('finished');
            });
        });
    });
});
