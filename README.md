<p align="center"><img src="public/media/img/kolos-white.png" width="300" alt="Laravel Logo"></p><a id='links'></a>

# <p align="center">Jury-App</p>

### <p align="center">Журийная система для соревнований CTF в формате Task-based</p>

## <img src="public/media/icon/link.png" width="27" align="absmiddle"> Ссылки
### [Технологии](#technologies) | [Описание](#description) | [Установка](#download) | [Инициализация](#init)
### [Использование](#using) | [Помощь](#help) | [Галерея](#galery)

## <img src="public/media/icon/technology.png" width="30" align="absmiddle"> <a id='technologies'></a> Используемые технологии [<img src="public/media/icon/up.png" width="20" align="absmiddle">](#links)

[PHP](https://www.php.net/) - Язык программироваия.

[Composer](https://getcomposer.org/) - Менеджер зависимостей для PHP.

[Docker](https://www.docker.com/) - Контейнеризация.

[Laravel](https://laravel.com/docs) - Фреймворк.

[Laravel Sail](https://laravel.su/docs/10.x/sail) - Docker-интерфейс.

[Laravel Reverb](https://laravel.su/docs/10.x/reverb) - WebSocket.

## <img src="public/media/icon/book2.png" width="35" align="absmiddle"> Описание <a id='description'></a>[<img src="public/media/icon/up.png" width="20" align="absmiddle">](#links)

#### Платформа представляет собой специализированный ресурс для организации и проведения CTF-соревнований в task-based формате. 
#### Основное функциональное назначение системы включает четыре ключевых аспекта: 
- Создание и администрирование CTF-заданий, 
- Мониторинг решенных заданий, 
- Визуализацию рейтинговой системы и статистических данных, 
- Управление пользовательскими аккаунтами.

### Основные сущности

- **Пользователи**
- **Команды**
- **Задания**
- **Решенные задания**

### Связи между сущностями

- Команды → много решенных заданий
- Решенное задание → много команд

### Технические особенности
- **Json-ответы сервера**
- **Веб-сокеты**
- **Валидация в Requests**
## <img src="public/media/icon/tools.png" width="30" align="absmiddle"> <a id='download'></a> Установка (Linux Debian) [<img src="public/media/icon/up.png" width="20" align="absmiddle">](#links)

> [!WARNING]
>  Фреймворк Laravel имеет несколько системных требований.
>  Вы должны убедиться, что ваш веб-сервер имеет следующую минимальную версию PHP и расширения:
> - PHP >= 8.2
> - Расширение PHP Ctype
> - Расширение PHP cURL
> - Расширение PHP DOM
> - Расширение PHP Fileinfo
> - Расширение PHP Filter
> - Расширение PHP Hash
> - Расширение PHP Mbstring
> - Расширение PHP OpenSSL
> - Расширение PHP PCRE
> - Расширение PHP PDO
> - Расширение PHP Session
> - Расширение PHP Tokenizer
> - Расширение PHP XML
>
> Это все нужно для установки необходимых зависимостией!

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

## <img src="public/media/icon/rocket.png" width="30" align="absmiddle"> <a id='init'></a> Инициализация [<img src="public/media/icon/up.png" width="20" align="absmiddle">](#links)
#### Jury-App(В дирректории проекта):
> [!WARNING]
> Скрипт launch.sh находится в разработке, здесь представлена Alpha версия, использовать с осторожностью!

> [!NOTE]
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
 docker compose exec app up --build -d
 docker compose exec app artisan migrate --seed
```

Генерация ключа
```
docker compose exec app php artisan key:generate
```
Чтобы узнать, что все работает, откройте браузер и перейдите к `localhost:80`,и вы должны увидеть страницу авторизации.

## <img src="public/media/icon/magic.png" width="32" align="absmiddle"> <a id='using'></a> Использование [<img src="public/media/icon/up.png" width="20" align="absmiddle">](#links)
#### Пароль от Администратора
находится в файле `.env` и хрнаится в таблице admins в хешированном виде.
> [!WARNING]
> Имя администратора в базе данных изменять нельзя! только для продвинутых пользователей!

#### Для удобного переноса данных с одного устройства на другое предусмотрено сохранение и загрузка базы данных.

``` docker compose exec app artisan dumb:db-export``` - сохранение дампа базы.

``` docker compose exec app artisan dump:db-import``` - загрузка дампа базы.

``` docker compose exec app artisan dump``` - просмотр документации.
#### Миграции
При использовании миграций laravel использовать команду:

```
 docker compose exec app artisan migrate --seed
```

## <img src="public/media/icon/quest.png" width="30" align="absmiddle"> <a id='help'></a> Помощь [<img src="public/media/icon/up.png" width="20" align="absmiddle">](#links)
- ### [Возможные решения при появлении ошибок](ISSUES.md)

## <img src="public/media/icon/flow.png" width="30" align="absmiddle"> <a id='galery'></a> Галерея [<img src="public/media/icon/up.png" width="20" align="absmiddle">](#links)

### Гости:
<img src="public/media/UI/1.png">
<img src="public/media/UI/2.png">
<img src="public/media/UI/3.png">
<img src="public/media/UI/4.png">
<img src="public/media/UI/5.png">

### Команды(авторизованные пользователи):
<img src="public/media/UI/6.png">
<img src="public/media/UI/7.png">
<img src="public/media/UI/8.png">
<img src="public/media/UI/9.png">
<img src="public/media/UI/10.png">
<img src="public/media/UI/12.png">

### Администраторы:
<img src="public/media/UI/13.png">
<img src="public/media/UI/14.png">
<img src="public/media/UI/15.png">
<img src="public/media/UI/16.png">
<img src="public/media/UI/17.png">
<img src="public/media/UI/18.png">
<img src="public/media/UI/19.png">
<img src="public/media/UI/20.png">
<img src="public/media/UI/21.png">
<img src="public/media/UI/22.png">
<img src="public/media/UI/23.png">
<img src="public/media/UI/24.png">


[<img src="public/media/icon/up.png" width="20" align="absmiddle">](#links)
