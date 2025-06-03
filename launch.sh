#!/bin/bash

# Цвета для вывода
RED='\033[1;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[1;34m'
CYAN='\033[1;36m'
MAGENTA='\033[1;35m'
LIGHTGRAY='\033[1;37m'
NC='\033[0m' # Без цвета (reset)

# Упрощенный спиннер без bc
spinner() {
    local pid=$1
    local delay=0.1
    local spinstr='|/-\'
    while [ "$(ps a | awk '{print $1}' | grep $pid)" ]; do
        local temp=${spinstr#?}
        printf " [%c]  " "$spinstr"
        local spinstr=$temp${spinstr%"$temp"}
        sleep $delay
        printf "\b\b\b\b\b\b"
    done
    printf "    \b\b\b\b"
}

# Упрощенный прогресс-бар без bc
progress_bar() {
    local duration=$1
    local bar_length=30
    local sleep_interval=$(awk "BEGIN {print $duration/$bar_length}")

    printf "["
    for ((i=0; i<$bar_length; i++)); do
        printf "▉"
        sleep $sleep_interval
    done
    printf "]"
    echo
}

# Вывод с эффектом печати
print_with_effect() {
    local text=$1
    local color=$2
    local delay=0.02

    printf "${color}"
    for ((i=0; i<${#text}; i++)); do
        printf "${text:$i:1}"
        sleep $delay
    done
    printf "${NC}\n"
}

IP_ADDRESS=$(hostname -I | awk '{print $1}')

# Функция для проверки команд
check_command() {
    local cmd=$1
    local name=$2

    print_with_effect "🔍 Проверка установки $name..." $CYAN

    if ! command -v $cmd &> /dev/null; then
        echo -e "${RED}✖ $name не установлен.${NC}"

        # Попытка автоматической установки
        print_with_effect "🔄 Попытка установки $name..." $YELLOW

        if [[ $name == "PHP" ]]; then
            (sudo apt install php php-cli php-mbstring php-xml php-mysql -y > /dev/null 2>&1) &
            spinner $!
        elif [[ $name == "Composer" ]]; then
            (php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" > /dev/null 2>&1
            php composer-setup.php > /dev/null 2>&1
            php -r "unlink('composer-setup.php');" > /dev/null 2>&1
            sudo mv composer.phar /usr/local/bin/composer > /dev/null 2>&1) &
            spinner $!
        elif [[ $name == "Docker" ]]; then
            (sudo apt install docker.io -y > /dev/null 2>&1
            sudo systemctl enable --now docker > /dev/null 2>&1) &
            spinner $!
        elif [[ $name == "npm" ]]; then
            (sudo apt install nodejs npm -y > /dev/null 2>&1) &
            spinner $!
        elif [[ $name == "Docker Compose" ]]; then
            (sudo apt install docker-compose -y > /dev/null 2>&1) &
            spinner $!
        elif [[ $name == "sed" || $name == "awk" ]]; then
            (sudo apt install sed awk -y > /dev/null 2>&1) &
            spinner $!
        fi

        # Повторная проверка после установки
        if command -v $cmd &> /dev/null; then
            echo -e "${GREEN}✔ $name успешно установлен.${NC}"
            return 0
        else
            echo -e "${RED}✖ Не удалось установить $name автоматически.${NC}"
            return 1
        fi
    else
        echo -e "${GREEN}✔ $name установлен.${NC}"
        return 0
    fi
}

# Функция для обработки ошибок с попыткой исправления
handle_error() {
    local error_msg=$1
    local cmd=$2

    echo -e "\n${RED}⚠ Ошибка: $error_msg${NC}"

    # Попытки автоматического исправления распространенных ошибок
    if [[ $error_msg == *"permission denied"* ]]; then
        print_with_effect "🛠 Попытка исправить проблему с правами..." $YELLOW
        (sudo chmod -R 755 storage
         sudo chown -R $USER:$USER .) &
        spinner $!
    elif [[ $error_msg == *"port is already allocated"* ]]; then
        print_with_effect "🛠 Попытка освободить занятый порт..." $YELLOW
        (docker-compose down > /dev/null 2>&1) &
        spinner $!
        sleep 2
    elif [[ $error_msg == *"No such file or directory"* ]]; then
        print_with_effect "🛠 Попытка создать отсутствующие директории..." $YELLOW
        (mkdir -p storage/framework/{sessions,views,cache}
         mkdir -p storage/app/{public,private,private/TasksFiles,public/teamlogo}) &
        spinner $!
    elif [[ $error_msg == *"docker daemon"* ]]; then
        print_with_effect "🛠 Попытка запустить docker daemon..." $YELLOW
        (sudo systemctl start docker > /dev/null 2>&1) &
        spinner $!
        sleep 5
    fi

    # Повторный запуск команды после попытки исправления
    if [ -n "$cmd" ]; then
        print_with_effect "🔄 Повторный запуск команды..." $YELLOW
        if eval "$cmd"; then
            echo -e "${GREEN}✔ Проблема успешно исправлена.${NC}"
            return
        fi
    fi

    # Если исправить не удалось - выход
    echo -e "${RED}✖ Не удалось автоматически исправить проблему.${NC}"
    exit 1
}

# Проверка и установка bc при необходимости
if ! command -v bc &> /dev/null; then
    echo -e "${YELLOW}Установка bc для расчетов...${NC}"
    sudo apt install bc -y > /dev/null 2>&1
fi

# Заголовок скрипта
clear
echo -e "${MAGENTA}"
echo "╔════════════════════════════════════════════════╗"
echo "║                                                ║"
echo "║           УСТАНОВОЧНЫЙ СКРИПТ v2.1            ║"
echo "║                                                ║"
echo "╚════════════════════════════════════════════════╝"
echo -e "${NC}"
print_with_effect "🖥  IP адрес сервера: $IP_ADDRESS" $CYAN
echo

# Проверяем, существует ли файл .env.example
if [ -f ".env.example" ]; then
    print_with_effect "🔧 Подготовка конфигурационного файла..." $CYAN
    # Переименовываем файл
    if ! mv .env.example .env 2> /dev/null; then
        handle_error "Не удалось переименовать .env.example в .env" "mv .env.example .env"
    else
        echo -e "${GREEN}✔ .env.example был переименован в .env${NC}"
    fi
else
    echo -e "${YELLOW}⚠ Файл .env.example не найден.${NC}"
fi

if [ -f ".env" ]; then
    echo -e "${GREEN}✔ Найден файл .env${NC}"
    print_with_effect "🚀 Начало процесса установки..." $CYAN
    echo

    # Проверка зависимостей
    print_with_effect "🔎 Проверка системных зависимостей..." $CYAN
    echo

    CHK=false
    check_command php PHP || CHK=true
    check_command composer Composer || CHK=true
    check_command docker Docker || CHK=true
    check_command npm npm || CHK=true
    check_command docker compose "Docker Compose" || CHK=true
    check_command sed sed || CHK=true
    check_command awk awk || CHK=true

    if $CHK; then
        handle_error "Необходимые программы не установлены. Установите их перед продолжением."
    fi

    print_with_effect "🔄 Обновление системы и установка зависимостей..." $CYAN
    (sudo apt update > /dev/null 2>&1) &
    spinner $!
    echo

    print_with_effect "📦 Установка composer зависимостей..." $CYAN
    (composer update --ignore-platform-req=ext-xml > /dev/null 2>&1) &
    spinner $!

    (composer install --ignore-platform-req=ext-dom --ignore-platform-req=ext-xml --ignore-platform-req=ext-xmlwriter > /dev/null 2>&1) &
    spinner $!
    echo -e "${GREEN}✔ Установка завершена успешно.${NC}"
    echo

    print_with_effect "📂 Создание необходимых директорий..." $CYAN
    (mkdir -p storage/framework/{sessions,views,cache} > /dev/null 2>&1
     mkdir -p storage/app/{private,public,private/TasksFiles,public/teamlogo} > /dev/null 2>&1
     chmod -R 755 storage/framework > /dev/null 2>&1
     chmod -R 755 storage/app > /dev/null 2>&1
     chmod 755 public > /dev/null 2>&1
     cp public/media/img/StandartLogo.png storage/app/public/teamlogo > /dev/null 2>&1) &
    spinner $!
    echo

    print_with_effect "📦 Установка npm зависимостей..." $CYAN
    (npm install > /dev/null 2>&1) &
    spinner $!
    echo

    print_with_effect "⚙ Обновление .env файла..." $CYAN
    if grep -q "REVERB_HOST=" ".env"; then
        (sed -i "s/^REVERB_HOST=.*/REVERB_HOST=$IP_ADDRESS/" .env > /dev/null 2>&1) &
        spinner $!
        echo -e "${GREEN}✔ Обновлен существующий REVERB_HOST в .env${NC}"
    else
        (echo "REVERB_HOST=$IP_ADDRESS" >> .env > /dev/null 2>&1) &
        spinner $!
        echo -e "${GREEN}✔ Добавлен новый REVERB_HOST в .env${NC}"
    fi
    echo

    print_with_effect "🏗 Сборка приложения..." $CYAN
    (npm run build > build.log 2>&1) &
    spinner $!
    echo -e "${GREEN}✔ Сборка успешно завершена${NC}"
    echo

    print_with_effect "🐳 Запуск Docker контейнеров..." $CYAN
    if [ "$(docker ps -a -q -f name=your_app_name)" ]; then
        echo -e "${GREEN}✔ Обнаружен существующий контейнер${NC}"
        if ! ./vendor/bin/sail up -d; then
            handle_error "Ошибка при запуске существующего приложения" "./vendor/bin/sail up -d"
        else
            echo -e "${GREEN}✔ Приложение успешно запущено${NC}"
        fi
    else
        echo -e "${YELLOW}⚠ Контейнер не найден, выполняется первая сборка...${NC}"
        if ! ./vendor/bin/sail up -d --build; then
            if grep -q "nameserver 8.8.8.8" /etc/resolv.conf && grep -q "nameserver 8.8.4.4" /etc/resolv.conf; then
                echo -e "${GREEN}✔ DNS уже настроены правильно${NC}"
            else
                if [ "$(id -u)" != "0" ]; then
                    echo -e "${RED}Для успешной сборки потребуется обновить файл /etc/resolv.conf${NC}"
                fi

                if ! sudo ./updateresolvconf.sh; then
                    handle_error "Файл /etc/resolv.conf не был обновлен!" "sudo ./updateresolvconf.sh"
                else
                    echo -e "${BLUE}✔ DNS успешно обновлены${NC}"
                fi
            fi

            if ! ./vendor/bin/sail up -d --build; then
                handle_error "Последняя попытка сборки не увенчалась успехом" "./vendor/bin/sail up -d --build"
            fi
        else
            echo -e "${GREEN}✔ Контейнер успешно собран и приложение запущено${NC}"
        fi
    fi
    echo

    print_with_effect "🔄 Запуск миграций и сидирования..." $CYAN
    print_with_effect "⏳ Ожидание запуска контейнеров (20 сек)..." $YELLOW
    sleep 20

    (./vendor/bin/sail restart > /dev/null 2>&1) &
    spinner $!

    print_with_effect "⏳ Ожидание инициализации БД (20 сек)..." $YELLOW
    sleep 20

    print_with_effect "🔄 Выполнение миграций..." $CYAN
    (./vendor/bin/sail artisan migrate > /dev/null 2>&1) &
    spinner $!

    print_with_effect "🌱 Заполнение базы данных..." $CYAN
    (./vendor/bin/sail artisan migrate:fresh --seed > /dev/null 2>&1) &
    spinner $!

    print_with_effect "🔗 Создание симлинков..." $CYAN
    (./vendor/bin/sail artisan storage:link > /dev/null 2>&1) &
    spinner $!

    print_with_effect "⏳ Финальный перезапуск (20 сек)..." $YELLOW
    sleep 20
    (./vendor/bin/sail restart > /dev/null 2>&1) &
    spinner $!
    echo

    # Финальное сообщение
    echo -e "${MAGENTA}"
    echo "╔════════════════════════════════════════════════╗"
    echo "║                                                ║"
    echo "║          УСТАНОВКА УСПЕШНО ЗАВЕРШЕНА!         ║"
    echo "║                                                ║"
    echo "╚════════════════════════════════════════════════╝"
    echo -e "${NC}"
    echo -e "${BLUE}🌐 Сайт доступен по ссылке:${NC} http://$IP_ADDRESS:80"
    echo -e "${BLUE}🔑 Авторизация:${NC}"
    echo -e "${GREEN}    👤 Логин: Team A${NC}"
    echo -e "${GREEN}    🔒 Пароль: 1111${NC}"
    echo
    print_with_effect "🚀 Приложение готово к использованию!" $GREEN
    chmod -R 755 storage/framework > /dev/null 2>&1
    chmod -R 755 storage/app > /dev/null 2>&1
    chmod 755 public > /dev/null 2>&1
else
    handle_error "Не найден конфигурационный файл .env либо .env.example. Без одного из этих файлов установка приложения невозможна!"
fi
