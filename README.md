<p align="center"><img src="public/media/img/kolos-white.png" width="300" alt="Laravel Logo"></p>

# Jury-App

WEB приложение созданное для проведения соревнований AltayCTF-School в формате Task-based;

## <img src="public/media/icon/technology.png" width="30" align="absmiddle"> Technologies Used

[PHP](https://www.php.net/) - Язык программироваия.

[Composer](https://getcomposer.org/) - Менеджер зависимостей для PHP.

[Docker](https://www.docker.com/) - Контейнеризация.

[NodeJS](https://nodejs.org/en) - NodeJS.

[Laravel](https://laravel.com/docs) - Фреймворк.

[Laravel Sail](https://laravel.su/docs/10.x/sail) - Взаимодействия со средой разработки Docker.

[Laravel Reverb](https://laravel.su/docs/10.x/reverb) - WebSocket.

## <img src="public/media/icon/tools.png" width="30" align="absmiddle"> Installation (Linux Debian)

#### Системные Требования:
```
Фреймворк Laravel имеет несколько системных требований. 
Вы должны убедиться, что ваш веб-сервер имеет следующую минимальную версию PHP и расширения:
    PHP >= 8.2
    Расширение PHP Ctype
    Расширение PHP cURL
    Расширение PHP DOM
    Расширение PHP Fileinfo
    Расширение PHP Filter
    Расширение PHP Hash
    Расширение PHP Mbstring
    Расширение PHP OpenSSL
    Расширение PHP PCRE
    Расширение PHP PDO
    Расширение PHP Session
    Расширение PHP Tokenizer
    Расширение PHP XML
    
Это все нужно для установки необходимых зависимостией!
```

#### PHP: 
```
sudo apt update
sudo apt install php
```
#### Зависимости PHP:
```
sudo apt update
sudo apt install php-ctype php-curl php-dom php-fileinfo php-filter php-hash php-mbstring php-openssl php-pcre php-pdo php-session php-tokenizer php-xml
```
#### Composer: 
```
sudo apt-get install php-curl
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
```
#### Docker:
```
sudo apt update
sudo apt install -y docker.io
sudo systemctl enable docker --now
sudo usermod -aG docker $USER
echo "deb [arch=amd64 signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/debian bookworm stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list
curl -fsSL https://download.docker.com/linux/debian/gpg |
  sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io
```

#### NodeJs:
```
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.0/install.sh | bash
nvm install 22
```

## <img src="public/media/icon/rocket.png" width="30" align="absmiddle"> Launch
#### Jury-App(В дирректории проекта):
> [!WARNING]
> Скрипт launch.sh находится в разработке, здесь представлена Alpha версия, использовать с осторожностью!

> [!INFO]
> Рекомендуется установить DNS 8.8.8.8 - чтобы не было проблем со сборкой контейнеров!

Скопируйте Production версию в docker-compose:
```bash
cp docker/production/docker-compose.prod.yml docker-compose.yml
```
Чтобы автоматически создать все необходимые таблицы базы данных, раскоментируйте строчку в docker-compose:
```
- './database/schema/init.sql:/docker-entrypoint-initdb.d/dump.sql'
```

```
sudo apt update
composer update
composer install
```
В дирректории проекта создать файл `.env` и скопировать в него содержимое `.env.example` (чтобы увидеть этот файл нажмите Ctrl + H)

Выполните сборку приложени:
```
npm install
npm run build
```

Для запуска приложения нужно открыть консоль в дирректории проекта, вставить все эти команды одновременно и дождаться их выполнения:
```
./vendor/bin/sail up --build -d
./vendor/bin/sail artisan migrate --seed
```
Чтобы узнать, что все работает, откройте браузер и перейдите к localhost:80,и вы должны увидеть страницу авторизации.

#### Далее для работы с Jury-App используйте следующие команды:

Чтобы запустить все контейнеры Docker в фоновом режиме введите команду:
```
./vendor/bin/sail up -d
```
Чтобы остановить все контейнеры Docker введите команду:
```
./vendor/bin/sail stop
```
Чтобы перезапустить все контейнеры Docker введите команду:
```
./vendor/bin/sail restart
```
## <img src="public/media/icon/book.png" width="32" align="absmiddle"> Useful Things
#### Пароль от Администратора
находится в файле `.env` и хрнаится в таблице admins в хешированном виде.
> [!WARNING]
> Имя администратора в базе данных изменять нельзя! только для продвинутых пользователей!
#### Для удобного переноса данных с одного устройства на другое предусмотрено сохранение и загрузка базы данных.

```./vendor/bin/sail artisan dumb:db-export``` - сохранение

```./vendor/bin/sail artisan dump:db-import``` - загрузка
#### Миграции
При использовании миграций laravel использовать команду:

```
./vendor/bin/sail artisan migrate --seed
```
чтобы произошла начальная загрузка данных в главные таблицы, либо используйте dump базы данных и команды приведенные выше.

## <img src="public/media/icon/tips.png" width="30" align="absmiddle"> Helper
- ### [Возможные решения при появлении ошибок](ISSUES.md)
