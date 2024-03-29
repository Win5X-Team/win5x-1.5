# Win5X 1.5

# Warning

Seems like this repository caused some confusion over the past year. This is NOT RELATED to https://new.playin.team in any way.

This version was never intended for public use or selling. We used it for our own project, that's it. After the leak on russian forums multiple scammers are trying to use our name, selling this version "way cheaper than we propose".

Even through source code is there, we don't recommend using it. It's not polished, made for Russian audience, and you will have to guess what our developer was thinking when he coded this outdated thing. Latest versions are completely rewritten using modern technologies.

If you need a casino website, feel free to contact us: https://new.playin.team. We have released sport betting update recently!

--

If you are still interested in Win5x/1.5 for some weird reason, you can read our Russian wall of text:

# Зачем мы это делаем?

Доброго времени суток, Вас приветствует команда Win5X.

Мы намеренно опубликовываем одну из наших первых версий онлайн казино, которую вы можете запустить и убедиться в её подлинности.

В официальной версии вас ждёт многопользовательская игра Battlegrounds и Plinko, а так же создание заданий для пользователей.

Эта версия не имеет багов, дыр и прочего мусора, который был залит на подобные форумы. Без мусора в котором вы так активно копаетесь.

Многие пытаются выдать себя за разработчиков данного скрипта, но у проекта только два настоящих разработчика - Adam and Klaus.

Смешно со стороны наблюдать за людьми, которые пытаются обмануть кого-то на слитый скрипт с приблизительной стоимостью в 3000-5000 рублей.

Нам это точно не нужно. Мы работаем с зарубежной аудиторией, а минимальная цена нашего скрипта составляет 1250$.

Демо доступно по ссылкам: [win5x.com](https://win5x.com) (заблокировано в России, вход только по vpn) [playin.team](https://playin.team) и [demo.playin.team](https://demo.playin.team)

Если вы хотите открыть действительно хороший проект - ждём вас.

Данный скрипт для нас не имеет никакой ценности, вы можете использовать его в своих целях. 

На создание данной темы нас побудило то, что во всех магазинах скриптов есть "Слитый скрипт Win5X", в котором куча дыр и покупатели страдают после приобретения, а так же многочисленные обманы и манипуляции среди продавцов скриптов, различных магазинов и администрации различных форумов.

Перестаньте доверять людям, которым безразличны вы и ваши "проекты".

##### TL;DR: мы устали смотреть на мошенников, что продают слитый скрипт. Теперь доступ к очень старой официальной версии есть у каждого, так как нам она не нужна.

#### Требования:
* Laravel 5.2
* PHP 7+
* NodeJS
* Apache/Nginx
* ionCube loader

#### NodeJS:
* battlegrounds.js - Игра Battlegrounds
* chat.js - Чат (+ сообщения о играх в реальном времени)
* obf.js - Обфусификатор JS файлов для релизных версий (/js/ > /js/dist/)
* promo_bot.js - Бот ВКонтакте для отправления промокодов группам автоматически
* webPush.js - Отправление уведомлений пользователям (не работает, так как не было закончено :) )

#### Установка

Инструкция по запуску написана для Ubuntu 18.04

Некоторые хостеры блокируют порты. Для функционирования требуются следующие открытые порты (Вероятно, что они уже открыты. На AWS это можно настроить в Security Groups):

`2082
2052
2053`

Установка скрипта на сервер

```
apt-get update
apt-get -y install software-properties-common
add-apt-repository ppa:phpmyadmin/ppa
add-apt-repository ppa:ondrej/php
apt-get --with-new-pkgs upgrade 

apt-get install -y php7.4 php7.4-bcmath php7.4-ctype php7.4-fileinfo php7.4-json php7.4-mbstring php7.4-pdo php7.4-xml php7.4-tokenizer

apt-get install -y composer apache2 mysql-server
```
Теперь нужно ввести и настроить MySQL сервер:
`mysql_secure_installation`
`apt-get install -y phpmyadmin`

Установщик phpMyAdmin спросит данные mysql и куда его устанавливать. На этапе выбора сервера нужно выбрать Apache, поставив галочку (пробел).

Важно: теперь нужно настроить Apache сервер под Laravel:

`nano /etc/apache2/sites-available/000-default.conf`

Изменяем DocumentRoot /var/www/html на DocumentRoot /var/www/html/public

После DocumentRoot пишем следующее

```
<Directory /var/www/html/public>
	Options -Indexes +FollowSymLinks
	Require all granted
	AllowOverride All
</Directory>
```

Сохраняем изменения (CTRL+O)

```
a2enmod rewrite
service apache2 restart

cd /var/www/html
nano .env
```

В файле изменить APP_DEBUG на false, APP_URL на адрес сайта.
DB_DATABASE на имя базы данных
DB_USERNAME на root (по умолчанию) или другое имя пользователя, если оно было создано
DB_PASSWORD на пароль базы данных

Сохраняем изменения

Заходим в phpMyAdmin и импортируем базу данных

На этом этапе сервер должен быть настроен.

#### Выдача админки в базе данных

Установить is_admin на 1 и поставить chat_role на 3

Все идентификаторы chat_role
0 - пользователь
1 - youtube (отключена подкрутка)
2 - модератор (в админке имеет доступ к созданию промокодов)
3 - администратор (полный доступ)

После выдачи админка станет доступна по адресу /admin

#### node.js скрипты

Установка NPM, а так же pm2, необходимые для работы:

```
apt-get install npm

npm install && composer install

npm install --save -g pm2
npm install --save -g cors easyvk express http xss-filters crypto mathjs socket.js
npm install --save -g fs
```

#### promo_bot.js (необязательно)

Отправляет промокоды всем указанным в админке людям через группу вконтакте раз в 24 часа. Предназначение - маленькие группы, которые выкладывают промокоды на различные сайты. Необязателен.

Внутри скрипта изменить:

```
easyvk({
    access_token: 'YOUR VK GROUP ACCESS TOKEN'
})
```

YOUR VK GROUP ACCESS TOKEN требуется изменить на ключ доступа группы вконтакте (с правами доступа к сообщениям)

Последний домен требуется изменить на адрес сайта:
domain = __LOCALHOST ? 'http://localhost' : 'https://win5x.com'; 

#### chat.js

Чат на сайте, а также история игр в реальном времени.

Последний домен требуется изменить на адрес сайта:
domain = __LOCALHOST ? 'http://localhost' : 'https://win5x.com'; 

Запуск скриптов:
```
pm2 start chat.js
pm2 start promo_bot.js
```
