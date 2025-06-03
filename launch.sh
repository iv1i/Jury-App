#!/bin/bash

# Цвета для вывода
RED='\033[1;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[1;34m'
LIGHTGRAY='\033[1;37m'
NC='\033[0m' # Без цвета (reset)

IP_ADDRESS=$(hostname -I | awk '{print $1}')

# Функция для проверки команд
check_command() {
    if ! command -v $1 &> /dev/null; then
        echo -e "${RED}$2 не установлен. Установите его...${NC}"
        return 1
    else
        echo -e "${GREEN}$2 установлен.${NC}"
        return 0
    fi
}

# Функция для обработки ошибок
handle_error() {
    echo -e "${RED}Ошибка: $1${NC}"
    exit 1
}

# Проверяем, существует ли файл .env.example
if [ -f ".env.example" ]; then
    # Переименовываем файл
    if mv .env.example .env > /dev/null 2>&1; then
        echo -e "${GREEN}.env.example был переименован в .env${NC}"
    else
        handle_error "Не удалось переименовать .env.example в .env"
    fi
else
    echo -e "${YELLOW}Файл .env.example не найден.${NC}"
fi

if [ -f ".env" ]; then
    echo -e "${GREEN}Найден файл .env${NC}"
    echo -e "${YELLOW}Подготовка к установке.${NC}"
    echo -e "${YELLOW}Проверяем, установлены ли необходимые программы...${NC}"

    # Проверка зависимостей
    CHK=false
    check_command php PHP || CHK=true
    check_command composer Composer || CHK=true
    check_command docker Docker || CHK=true
    check_command npm npm || CHK=true
    check_command docker-compose "Docker Compose" || CHK=true
    check_command sed sed || CHK=true
    check_command awk awk || CHK=true

    if $CHK; then
        handle_error "Необходимые программы не установлены. Установите их перед продолжением."
    fi

    echo -e "${YELLOW}Обновление системы и установка зависимостей...${NC}"
    sudo apt update > /dev/null 2>&1 || echo -e "${YELLOW}Предупреждение: не удалось обновить apt${NC}"

    echo -e "${YELLOW}Установка composer зависимостей...${NC}"
    composer update --ignore-platform-req=ext-xml > /dev/null 2>&1 || echo -e "${YELLOW}Предупреждение: не удалось выполнить composer update${NC}"
    composer install --ignore-platform-req=ext-dom --ignore-platform-req=ext-xml --ignore-platform-req=ext-xmlwriter > /dev/null 2>&1 || handle_error "Ошибка при установке composer зависимостей"

    echo -e "${GREEN}Установка завершена успешно.${NC}"

    echo -e "${YELLOW}Создание необходимых директорий...${NC}"
    mkdir -p storage/framework/{sessions,views,cache} > /dev/null 2>&1
    mkdir -p storage/app/{public,sql_dump,public/teamlogo} > /dev/null 2>&1
    chmod -R 755 storage/framework > /dev/null 2>&1
    chmod -R 755 storage/app > /dev/null 2>&1
    cp public/media/img/StandartLogo.png storage/app/public/teamlogo > /dev/null 2>&1 || echo -e "${YELLOW}Предупреждение: не удалось скопировать логотип${NC}"

    echo -e "${YELLOW}Установка npm зависимостей...${NC}"
    npm install > /dev/null 2>&1 || handle_error "Ошибка при установке npm зависимостей"

    echo -e "${YELLOW}Обновление .env файла...${NC}"
    if grep -q "REVERB_HOST=" ".env"; then
        sed -i "s/^REVERB_HOST=.*/REVERB_HOST=$IP_ADDRESS/" .env || handle_error "Не удалось обновить REVERB_HOST в .env"
        echo -e "${GREEN}Обновлен существующий REVERB_HOST в .env${NC}"
    else
        echo "REVERB_HOST=$IP_ADDRESS" >> .env || handle_error "Не удалось добавить REVERB_HOST в .env"
        echo -e "${GREEN}Добавлен новый REVERB_HOST в .env${NC}"
    fi

    echo -e "${YELLOW}Сборка приложения...${NC}"
    npm run build > build.log 2>&1 || handle_error "Ошибка при сборке. Проверьте build.log для подробностей"
    echo -e "${GREEN}Сборка успешно завершена${NC}"

    echo -e "${YELLOW}Запуск Docker контейнеров...${NC}"
    if [ "$(docker ps -a -q -f name=your_app_name)" ]; then
        echo -e "${GREEN}Обнаружен существующий контейнер${NC}"
        if ./vendor/bin/sail up -d; then
            echo -e "${GREEN}Приложение успешно запущено${NC}"
        else
            handle_error "Ошибка при запуске существующего приложения"
        fi
    else
        echo -e "${YELLOW}Контейнер не найден, выполняется первая сборка...${NC}"
        if ./vendor/bin/sail up -d --build; then
            echo -e "${GREEN}Контейнер успешно собран и приложение запущено${NC}"
        else
            if grep -q "nameserver 8.8.8.8" /etc/resolv.conf && grep -q "nameserver 8.8.4.4" /etc/resolv.conf; then
                echo -e "${GREEN}Строки уже присутствуют в файле /etc/resolv.conf${NC}"
            else
                if [ "$(id -u)" != "0" ]; then
                    echo -e "${RED}Для успешной сборки потребуется обновить файл /etc/resolv.conf${NC}"
                fi

                if sudo ./updateresolvconf.sh; then
                    echo -e "${BLUE}Строки добавлены в файл /etc/resolv.conf${NC}"
                else
                    handle_error "Файл /etc/resolv.conf не был обновлен!"
                fi
            fi

            if ! ./vendor/bin/sail up -d --build; then
                handle_error "Последняя попытка сборки не увенчалась успехом"
            fi
        fi
    fi

    echo -e "${YELLOW}Запуск миграций и сидирования...${NC}"
    sleep 20
    ./vendor/bin/sail restart > /dev/null 2>&1
    sleep 20
    ./vendor/bin/sail artisan migrate > /dev/null 2>&1 || echo -e "${YELLOW}Предупреждение: не удалось выполнить миграции${NC}"
    ./vendor/bin/sail artisan migrate:fresh --seed > /dev/null 2>&1 || echo -e "${YELLOW}Предупреждение: не удалось выполнить сидирование${NC}"
    ./vendor/bin/sail artisan storage:link > /dev/null 2>&1 || echo -e "${YELLOW}Предупреждение: не удалось создать симлинк storage${NC}"
    sleep 20
    ./vendor/bin/sail restart > /dev/null 2>&1

    echo -e "${BLUE}Установка завершена!${NC}"
    echo -e "${BLUE}Сайт доступен по ссылке:${NC} http://$IP_ADDRESS:80"
    echo -e "${BLUE}Авторизация:${NC}"
    echo -e "${GREEN}    Логин: Team A${NC}"
    echo -e "${GREEN}    Пароль: 1111${NC}"
else
    handle_error "Не найден конфигурационный файл .env либо .env.example. Без одного из этих файлов установка приложения невозможна!"
fi
