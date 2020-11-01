const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');
const webpush = require('web-push');
const requestify = require('requestify');
const app = express();

const __LOCALHOST = false;
const domain = __LOCALHOST ? 'http://localhost' : 'https://win5x.com';

const vapidKeys = {
    publicKey: 'BL3uZFFI9282cSiGQFIqTJm9N1kAif1P7EtAXr8lx1ukaeIAi7F2pRwltOTPlkuQExxMZnbONLrSOee0E_Hqj5g',
    privateKey: 'uhomiLK0CZLuPnYnGK81HmyIWTj5Eg6Xx6gY5QJ80uI'
};

webpush.setVapidDetails(
    'mailto:support@win5x.com',
    vapidKeys.publicKey,
    vapidKeys.privateKey
);

app.use(cors());
app.use(bodyParser.json());

const port = 2053;
app.get('/', (req, res) => res.send(':)'));

const saveToDatabase = async subscription => {
    requestify.get(domain+'/admin/save_subscription/'+JSON.stringify(subscription)).then(function(response) {
        response = JSON.parse(response.body);
        // console.log(response)
    });
};

const sendNotification = (subscription, dataToSend = '') => {
    webpush.sendNotification(subscription, dataToSend)
};

app.post('/save-subscription', async (req, res) => {
    const subscription = req.body;
    await saveToDatabase(subscription);
    res.json({ message: 'success' });
});

String.prototype.replaceAll = function(search, replace){
    return this.split(search).join(replace);
};

app.get('/send', (req, res) => {
    requestify.get(domain+'/get_subscribers').then(function(response) {
        response = JSON.parse(response.body);
        const message = req.query.message.replaceAll('[SPACE]', ' ').replaceAll('[LINEBREAK]', '\n');
        for(let i = 0; i < response.length; i++) {
            const subscription = JSON.parse(response[i].json);
            sendNotification(subscription, message);
        }
    });
    res.json({ message: 'message sent' });
});

app.listen(port, () => console.log(`webPush.js listening on ${port}`));

process.on('uncaughtException', function (exception) {
    console.log(exception);
});