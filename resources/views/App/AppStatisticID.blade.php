@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('style/scss/TeamProfile.css') }}">
    <script>
        {!! Vite::content('resources/js/app.js') !!}
    </script>
@endsection

@section('title', 'AltayCTF-Sch-StatisticID')

@section('appcontent')
    <div class="app-content">
        <div id="FormSwitchTheme" class="app-content-header">
            <h1 class="app-content-headerText"> {{ __('Statistics') }} #{{ $id }}</h1>
            <button id="switchTheme" class="mode-switch" title="Switch Theme">
                <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                     stroke-width="2" width="24" height="24" viewBox="0 0 24 24">
                    <defs></defs>
                    <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                </svg>
            </button>
            <button class="action-button list active" title="List View">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-list">
                    <line x1="8" y1="6" x2="21" y2="6"/>
                    <line x1="8" y1="12" x2="21" y2="12"/>
                    <line x1="8" y1="18" x2="21" y2="18"/>
                    <line x1="3" y1="6" x2="3.01" y2="6"/>
                    <line x1="3" y1="12" x2="3.01" y2="12"/>
                    <line x1="3" y1="18" x2="3.01" y2="18"/>
                </svg>
            </button>
            <button class="action-button grid" title="Grid View">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-grid">
                    <rect x="3" y="3" width="7" height="7"/>
                    <rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/>
                    <rect x="3" y="14" width="7" height="7"/>
                </svg>
            </button>
        </div>
        <div class="app-content-actions">
            <div class="app-content-actions-wrapper">
                <div class="filter-button-wrapper">
                    <button class="action-button filter jsFilter" style="display: none"></button>
                </div>
            </div>
        </div>
        <div class="app-content-body-wrapper">
            <div class="wrapper">
            </div>
        </div>
        <div class="products-area-wrapper tableView">
            <div class="products-header">
                <div class="product-cell image">{{ __('Name') }}
                </div>
                <div class="product-cell category">{{ __('Category') }}
                </div>
                <div class="product-cell status-cell">{{ __('Complexity') }}
                </div>
                <div class="product-cell price">{{ __('Price') }}
                </div>
            </div>
            <div class="Product-body">

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        // Константы и переменные
        const TEAM_ID = {{ $id }};
        const TASKS_DATA = @json($tasks);
        let teamData = @json($team);
        let teamLogoUrl = '{{ $teamLogoUrl }}';
        let checkTasks = @json($checkTasks);
        let solvedTasks = @json($teamSolvedTasks);

        // Кэширование DOM элементов
        const contentWrapper = document.querySelector('.app-content-body-wrapper');
        const productBody = document.querySelector('.Product-body');
        const accountLogo = document.querySelector('.account-info-picture');

        // Константы для классов и текстов
        const CLASSES = {
            easy: 'easy',
            medium: 'medium',
            hard: 'hard',
            status: 'status'
        };

        const TEXTS = {
            solved: '{{ __('Solved') }}',
            name: '{{ __('Name') }}',
            category: '{{ __('Category') }}',
            complexity: '{{ __('Complexity') }}',
            price: '{{ __('Price') }}'
        };

        // Функция для создания HTML профиля команды
        function createProfileHTML(team, logo, tasks) {
            return `
            <div class="wrapper">
                <div class="profile-card js-profile-card">
                    <div class="profile-card__img">
                        <img src="${logo}" alt="${team.name} logo">
                    </div>
                    <div class="profile-card__cnt js-profile-cnt">
                        <div class="profile-card__name">${escapeHtml(team.name)}</div>
                        <div class="profile-card__score">${team.scores}</div>
                        <div class="profile-card__txt"><strong>${escapeHtml(team.wherefrom)}</strong></div>
                        <div class="profile-card-inf">
                            <div class="profile-card-inf__item">
                                <div class="profile-card-inf__title">${tasks.sumary}</div>
                                <div class="profile-card-inf__txt">${TEXTS.solved}</div>
                            </div>
                            <div class="profile-card-inf__item">
                                <div class="profile-card-inf__title">${tasks.easy}</div>
                                <div class="profile-card-inf__txt ${CLASSES.easy}">EASY</div>
                            </div>
                            <div class="profile-card-inf__item">
                                <div class="profile-card-inf__title">${tasks.medium}</div>
                                <div class="profile-card-inf__txt ${CLASSES.medium}">MEDIUM</div>
                            </div>
                            <div class="profile-card-inf__item">
                                <div class="profile-card-inf__title">${tasks.hard}</div>
                                <div class="profile-card-inf__txt ${CLASSES.hard}">HARD</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        }

        // Функция для создания HTML заголовков таблицы
        function createTableHeaders() {
            return `
            <div class="products-header">
                <div class="product-cell image">
                    ${TEXTS.name}
                    <button class="sort-button">
                        ${sortIcon}
                    </button>
                </div>
                <div class="product-cell category">
                    ${TEXTS.category}
                    <button class="sort-button">
                        ${sortIcon}
                    </button>
                </div>
                <div class="product-cell status-cell">
                    ${TEXTS.complexity}
                    <button class="sort-button">
                        ${sortIcon}
                    </button>
                </div>
                <div class="product-cell price">
                    ${TEXTS.price}
                    <button class="sort-button">
                        ${sortIcon}
                    </button>
                </div>
            </div>`;
        }

        // Функция для создания HTML строки задачи
        function createTaskRowHTML(task) {
            const complexityClass = CLASSES[task.complexity] || '';

            return `
            <div href="/Home/${task.id}" class="products-row tasklink">
                <div class="product-cell image">
                    <span>${escapeHtml(task.name)}</span>
                </div>
                <div class="product-cell category">
                    <span class="cell-label">${TEXTS.category}:</span>
                    ${task.category.toUpperCase()}
                </div>
                <div class="product-cell status-cell">
                    <span class="cell-label">${TEXTS.complexity}:</span>
                    <span class="${CLASSES.status} ${complexityClass}">
                        ${task.complexity.toUpperCase()}
                    </span>
                </div>
                <div class="product-cell price">
                    <span class="cell-label">${TEXTS.price}:</span>
                    ${task.price}
                </div>
            </div>`;
        }

        // Утилитарные функции
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function updateTeamLogo(logoUrl) {
            if (accountLogo) {
                accountLogo.innerHTML = `<img src="${logoUrl}" alt="Account">`;
            }
        }

        function processSolvedTasks(allTasks, solvedTasksData, teamId) {
            const userSolvedTasks = solvedTasksData.filter(task => task.teams_id === teamId);
            return allTasks.filter(task =>
                userSolvedTasks.some(solved => solved.tasks_id === task.id)
            );
        }

        function findTeamById(teams, teamId) {
            return teams.find(team => team.id === teamId) || {};
        }

        function findCheckTasksByTeamId(checkTasksData, teamId) {
            return checkTasksData.find(item => item.teams_id === teamId) || {};
        }

        // Иконка для сортировки (вынесена в константу для переиспользования)
        const sortIcon = `
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
            <path fill="currentColor"
                d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/>
        </svg>`;

        // Инициализация страницы
        function initializePage() {
            if (contentWrapper) {
                contentWrapper.innerHTML = createProfileHTML(teamData, teamLogoUrl, checkTasks);
            }

            if (productBody) {
                const headersHTML = createTableHeaders();
                const tasksHTML = solvedTasks.map(createTaskRowHTML).join('');
                productBody.innerHTML = tasksHTML;
            }

            updateTeamLogo(teamLogoUrl);
        }

        // Обработчик событий WebSocket
        function handleStatisticEvent(event) {
            const eventData = event.data;

            // Обновляем данные команды
            teamData = findTeamById(eventData.teams, TEAM_ID);
            const teamCheckTasks = findCheckTasksByTeamId(eventData.checkTasks, TEAM_ID);

            // Обновляем логотип
            teamLogoUrl = '/storage/teamlogo/' + teamData.teamlogo;
            updateTeamLogo(teamLogoUrl);

            // Обрабатываем решенные задачи
            const updatedSolvedTasks = processSolvedTasks(
                eventData.tasks,
                eventData.solvedTasks,
                TEAM_ID
            );

            // Обновляем UI
            if (contentWrapper) {
                contentWrapper.innerHTML = createProfileHTML(teamData, teamLogoUrl, teamCheckTasks);
            }

            if (productBody) {
                const headersHTML = createTableHeaders();
                const tasksHTML = updatedSolvedTasks.map(createTaskRowHTML).join('');
                productBody.innerHTML = tasksHTML;
            }

            console.log('Данные статистики обновлены!');
        }

        // Инициализируем страницу при загрузке
        document.addEventListener('DOMContentLoaded', initializePage);

        // Настраиваем WebSocket соединение
        if (typeof Echo !== 'undefined') {
            Echo.private(`channel-app-statisticID`)
                .listen('AppStatisticIDEvent', handleStatisticEvent);
        }
    </script>
@endsection


