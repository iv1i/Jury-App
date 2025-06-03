#!/bin/bash

# Проверка поддержки цветов
if [ -t 1 ]; then
    export TERM=xterm-256color
    HAS_COLOR=true
else
    HAS_COLOR=false
fi

# Цвета для вывода
RED='\033[1;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[1;34m'
CYAN='\033[1;36m'
MAGENTA='\033[1;35m'
LIGHTGRAY='\033[1;37m'
NC='\033[0m' # Без цвета (reset)

# Функция для вывода сообщений с эффектом печати
print_msg() {
    local color=$1
    local prefix=$2
    local message=$3
    local delay=${4:-0.02}

    printf "${color}${prefix}${NC}"
    for ((i=0; i<${#message}; i++)); do
        printf "${message:$i:1}"
        sleep $delay
    done
    printf "\n"
}

# Функция для вывода статусов
print_status() {
    local status=$1
    local message=$2

    case $status in
        "success")
            echo -e "${GREEN}✔ ${message}${NC}"
            ;;
        "warning")
            echo -e "${YELLOW}⚠ ${message}${NC}"
            ;;
        "error")
            echo -e "${RED}✖ ${message}${NC}"
            ;;
        "info")
            echo -e "${CYAN}ℹ ${message}${NC}"
            ;;
        *)
            echo -e "${message}"
            ;;
    esac
}

# Анимация спиннера
spinner() {
    local pid=$1
    local delay=0.1
    local spinstr='|/-\'
    local temp

    # Скрываем курсор
    tput civis

    while ps -p $pid > /dev/null; do
        temp=${spinstr#?}
        printf " [%c]  " "$spinstr"
        spinstr=$temp${spinstr%"$temp"}
        sleep $delay
        printf "\b\b\b\b\b\b"
    done

    # Восстанавливаем курсор
    tput cnorm

    # Очищаем строку со спиннером
    printf "\r\033[K"
}

# Проверка и запуск Docker команд (сначала через sail, потом через docker compose)
run_docker() {
    local command=$1
    local args=${@:2}

    # Временный файл для вывода
    local temp_file=$(mktemp)

    # Проверяем доступность sail
    if [ -f "./vendor/bin/sail" ]; then
        # Выводим сообщение без спиннера
        printf "${CYAN}⛵ Выполнение через Sail: ${command} ${args}${NC}\n"
        ./vendor/bin/sail ${command} ${args} > "$temp_file" 2>&1 &
    else
        printf "${YELLOW}🐳 Sail не найден, выполнение через Docker Compose: ${command} ${args}${NC}\n"
        docker compose ${command} ${args} > "$temp_file" 2>&1 &
    fi

    local pid=$!
    spinner $pid

    # Проверяем результат выполнения
    if wait $pid; then
        return 0
    else
        # Выводим ошибку, если команда завершилась с ошибкой
        cat "$temp_file"
        rm -f "$temp_file"
        return 1
    fi
}

# Получение IP адреса
get_ip_address() {
    local ip
    ip=$(hostname -I | awk '{print $1}')
    if [ -z "$ip" ]; then
        ip="127.0.0.1"
    fi
    echo "$ip"
}

IP_ADDRESS=$(get_ip_address)

# Функция установки пакетов
install_package() {
    local package=$1
    local name=$2

    print_msg $YELLOW "🔄 " "Попытка установки ${name}..."

    case $package in
        "php")
            sudo apt install -y php php-cli php-mbstring php-xml php-mysql php-dom php-zip php-curl php-gd > /dev/null 2>&1
            ;;
        "composer")
            EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
            php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
            ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

            if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
                >&2 print_status "error" "Неверная контрольная сумма composer установщика"
                return 1
            fi

            php composer-setup.php --quiet
            RESULT=$?
            rm composer-setup.php
            if [ $RESULT -ne 0 ]; then
                return $RESULT
            fi

            sudo mv composer.phar /usr/local/bin/composer
            ;;
        "docker")
            # Установка зависимостей
            sudo apt update
            sudo apt install -y apt-transport-https ca-certificates curl gnupg2 software-properties-common

            # Добавление репозитория Docker
            curl -fsSL https://download.docker.com/linux/debian/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
            echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/debian $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

            # Установка Docker
            sudo apt update
            sudo apt install -y docker-ce docker-ce-cli containerd.io

            # Добавление пользователя в группу docker
            sudo usermod -aG docker $USER
            newgrp docker
            ;;
        "npm")
            # Установка Node.js через nvm
            if [ ! -d "$HOME/.nvm" ]; then
                curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.5/install.sh | bash
                export NVM_DIR="$HOME/.nvm"
                [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
                [ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"
            fi

            # Установка последней LTS версии Node.js
            nvm install --lts
            ;;
        *)
            sudo apt install -y $package
            ;;
    esac

    return $?
}

# Проверка и установка команд
check_command() {
    local cmd=$1
    local name=$2
    local package=${3:-$cmd}

    print_msg $CYAN "🔍 " "Проверка установки ${name}..."

    if ! command -v $cmd &> /dev/null; then
        print_status "warning" "${name} не установлен"

        # Попытка установки
        if install_package "$package" "$name"; then
            if command -v $cmd &> /dev/null; then
                print_status "success" "${name} успешно установлен"
                return 0
            else
                print_status "error" "Не удалось установить ${name} автоматически"
                return 1
            fi
        else
            print_status "error" "Ошибка при установке ${name}"
            return 1
        fi
    else
        print_status "success" "${name} уже установлен"
        return 0
    fi
}

# Обработка ошибок
handle_error() {
    local error_msg=$1
    local cmd=$2
    local retry=${3:-true}

    print_status "error" "Ошибка: ${error_msg}"

    # Попытки автоматического исправления распространенных ошибок
    case $error_msg in
        *"permission denied"*)
            print_msg $YELLOW "🛠 " "Исправление проблем с правами..."
            sudo chmod -R 775 storage
            sudo chmod -R 775 bootstrap/cache
            sudo chown -R $USER:www-data storage
            sudo chown -R $USER:www-data bootstrap/cache
            sudo chown -R $USER:www-data public
            ;;
        *"port is already allocated"*)
            print_msg $YELLOW "🛠 " "Остановка контейнеров..."
            run_docker down
            ;;
        *"No such file or directory"*)
            print_msg $YELLOW "🛠 " "Создание отсутствующих директорий..."
            mkdir -p storage/framework/{sessions,views,cache}
            mkdir -p storage/app/{public,private,private/TasksFiles,public/teamlogo}
            sudo chmod -R 775 storage
            sudo chown -R $USER:www-data storage
            ;;
        *"docker daemon"*)
            print_msg $YELLOW "🛠 " "Запуск Docker демона..."
            sudo systemctl start docker
            sleep 5
            ;;
        *"Could not open input file: artisan"*)
            print_msg $YELLOW "🛠 " "Попытка запуска artisan внутри контейнера..."
            cmd="docker-compose exec app php ${cmd#*artisan }"
            ;;
    esac

    # Повторный запуск команды после попытки исправления
    if [ "$retry" = true ] && [ -n "$cmd" ]; then
        print_msg $YELLOW "🔄 " "Повторный запуск команды..."
        if eval "$cmd"; then
            print_status "success" "Проблема успешно исправлена"
            return 0
        else
            print_status "error" "Не удалось исправить проблему автоматически"
            return 1
        fi
    fi

    return 1
}

# Проверка и установка bc для расчетов
if ! command -v bc &> /dev/null; then
    print_msg $YELLOW "📦 " "Установка bc для расчетов..."
    sudo apt install -y bc > /dev/null 2>&1
fi

# Заголовок скрипта
clear
echo -e "${MAGENTA}"
echo "╔════════════════════════════════════════════════╗"
echo "║                                                ║"
echo "║           УСТАНОВОЧНЫЙ СКРИПТ v2.3             ║"
echo "║                  (Sail/Docker)                 ║"
echo "║                                                ║"
echo "╚════════════════════════════════════════════════╝"
echo -e "${NC}"
print_msg $CYAN "🖥  " "IP адрес сервера: ${IP_ADDRESS}"
echo

# Основной процесс установки
main() {
    # Проверка файла .env
    if [ -f ".env.example" ] && [ ! -f ".env" ]; then
        print_msg $CYAN "🔧 " "Создание конфигурационного файла..."
        if cp .env.example .env; then
            print_status "success" ".env создан из .env.example"
        else
            handle_error "Не удалось создать .env" "cp .env.example .env" false
            exit 1
        fi
    elif [ ! -f ".env" ]; then
        handle_error "Не найден .env или .env.example" "" false
        exit 1
    fi

    # Проверка зависимостей
    print_msg $CYAN "🔎 " "Проверка системных зависимостей..."
    echo

    check_command php "PHP" || exit 1
    check_command composer "Composer" || exit 1
    check_command docker "Docker" || exit 1
    check_command npm "npm" || exit 1
    check_command docker "Docker Compose" "docker-compose-plugin" || exit 1
    check_command sed "sed" || exit 1
    check_command awk "awk" || exit 1

    # Обновление системы
    print_msg $CYAN "🔄 " "Обновление системы..."
    sudo apt update > /dev/null 2>&1 &
    spinner $!
    echo

    # Установка composer зависимостей
    print_msg $CYAN "📦 " "Установка composer зависимостей..."
    (composer install --ignore-platform-req=ext-dom --ignore-platform-req=ext-xml --ignore-platform-req=ext-xmlwriter > /dev/null 2>&1) &
    spinner $!
    echo

    # Создание директорий и настройка прав
    print_msg $CYAN "📂 " "Создание необходимых директорий и настройка прав..."
    (mkdir -p storage/framework/{sessions,views,cache} \
        storage/app/{private,public,private/TasksFiles,public/teamlogo} \
        && sudo chmod -R 775 storage \
        && sudo chown -R $USER:www-data storage \
        && cp -n public/media/img/StandartLogo.png storage/app/public/teamlogo/ > /dev/null 2>&1) &
    spinner $!
    echo

    # Установка npm зависимостей
    print_msg $CYAN "📦 " "Установка npm зависимостей..."
    (npm install > /dev/null 2>&1) &
    spinner $!
    echo

    # Обновление .env
    print_msg $CYAN "⚙ " "Обновление .env файла..."
    if grep -q "REVERB_HOST=" ".env"; then
        sed -i "s/^REVERB_HOST=.*/REVERB_HOST=${IP_ADDRESS}/" .env
    else
        echo "REVERB_HOST=${IP_ADDRESS}" >> .env
    fi
    print_status "success" "REVERB_HOST установлен в ${IP_ADDRESS}"
    echo

    # Сборка приложения
    print_msg $CYAN "🏗 " "Сборка приложения..."
    (npm run build > build.log 2>&1) &
    spinner $!
    print_status "success" "Сборка завершена"
    echo

    # Запуск Docker
    print_msg $CYAN "🐳 " "Запуск Docker контейнеров..."
    if run_docker ps | grep -q "Up"; then
        print_status "info" "Контейнеры уже запущены, выполняется перезапуск..."
        run_docker down
    fi

    # Дополнительная проверка прав перед запуском
    print_msg $CYAN "🔧 " "Проверка и настройка прав доступа..."
    (sudo chown -R $USER:www-data storage \
     && sudo chown -R $USER:www-data bootstrap/cache \
     && sudo chmod -R 775 storage \
     && sudo chmod -R 775 bootstrap/cache \
     && sudo chmod -R 775 public \
     && sudo chown -R $USER:www-data .env) &
    spinner $!
    print_status "success" "Права доступа проверены и настроены"
    echo

    if ! run_docker up -d --build; then
        handle_error "Ошибка при запуске контейнеров" "run_docker up -d --build"
    fi
    print_status "success" "Контейнеры успешно запущены"
    echo

    # Настройка прав внутри контейнера
    print_msg $CYAN "🔧 " "Настройка прав внутри контейнера..."
    (run_docker exec app chown -R www-data:www-data /var/www/html/storage \
     && run_docker exec app chown -R www-data:www-data /var/www/html/bootstrap/cache \
     && run_docker exec app chmod -R 775 /var/www/html/storage \
     && run_docker exec app chmod -R 775 /var/www/html/bootstrap/cache) &
    spinner $!
    print_status "success" "Права внутри контейнера настроены"
    echo

    # Миграции и сидирование
    print_msg $CYAN "🔄 " "Запуск миграций и сидирования..."
    print_msg $YELLOW "⏳ " "Ожидание инициализации БД (30 сек)..."
    sleep 30

    print_msg $CYAN "🔄 " "Выполнение миграций..."
    (run_docker artisan migrate --force > /dev/null 2>&1) &
    spinner $!

    print_msg $CYAN "🌱 " "Заполнение базы данных..."
    (run_docker artisan migrate:fresh --seed --force > /dev/null 2>&1) &
    spinner $!

    print_msg $CYAN "🔗 " "Создание симлинков..."
    (run_docker exec app bash -c "mkdir -p /var/www/html/storage/app/public \
        && rm -rf /var/www/html/public/storage \
        && php /var/www/html/artisan storage:link \
        && chown -R sail:www-data /var/www/html/storage \
        && chown -R sail:www-data /var/www/html/public/storage \
        && chmod -R 775 /var/www/html/storage \
        && chmod -R 775 /var/www/html/public/storage" > /dev/null 2>&1) &
    spinner $!
    print_status "success" "Симлинк storage создан"
    echo
    spinner $!

    print_msg $YELLOW "⏳ " "Финальный перезапуск (10 сек)..."
    run_docker restart
    sleep 10
    echo

    # Финальная проверка прав
    print_msg $CYAN "🔍 " "Проверка прав доступа..."
    (sudo chown -R $USER:www-data . \
     && sudo chmod -R 775 storage \
     && sudo chmod -R 775 bootstrap/cache \) &
    spinner $!
    print_status "success" "Финальная проверка прав завершена"
    echo

    # Финальное сообщение
    echo -e "${MAGENTA}"
    echo "╔════════════════════════════════════════════════╗"
    echo "║                                                ║"
    echo "║          УСТАНОВКА УСПЕШНО ЗАВЕРШЕНА!          ║"
    echo "║                                                ║"
    echo "╚════════════════════════════════════════════════╝"
    echo -e "${NC}"
    print_msg $BLUE "🌐 " "Сайт доступен по ссылке: http://${IP_ADDRESS}:80"
    print_msg $BLUE "🔑 " "Авторизация:"
    print_msg $GREEN "    👤 " "Логин: Team A"
    print_msg $GREEN "    🔒 " "Пароль: 1111"
    echo
    print_msg $GREEN "🚀 " "Приложение готово к использованию!"
    chmod gu+w -R storage
    chmod guo+w -R storage
    ./vendor/bin/sail artisan cache:clear > /dev/null 2>&1
}

# Запуск основного процесса
main
