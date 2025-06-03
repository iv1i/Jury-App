@extends('layouts.admin')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('style/css/AdminHomeDiagram.css') }}">
    <style>
        /* Дополнительные стили для Dashboard */
        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 20px;
            padding: 20px;
        }

        .stats-card {
            background: var(--app-bg-2);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--filter-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid var(--app-border-color);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--filter-shadow-topmostdiv);
        }

        .stats-card h3 {
            margin-top: 0;
            color: var(--app-content-main-color);
            font-size: 1rem;
            font-weight: 500;
            opacity: 0.8;
        }

        .stats-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--app-content-main-color);
            margin: 10px 0;
        }

        .card-grid-4 {
            grid-column: span 3;
        }

        .card-grid-6 {
            grid-column: span 4;
            height: 97%;
        }

        .card-grid-12 {
            grid-column: span 12;
        }

        .progress-container {
            width: 100%;
            background-color: var(--app-bg);
            border-radius: 10px;
            margin: 15px 0;
            height: 10px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 10px;
            background: linear-gradient(90deg, var(--action-color), var(--action-color-hover));
            transition: width 0.5s ease;
        }

        .task-category {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 8px;
            margin-bottom: 8px;
            background-color: var(--app-bg);
            color: var(--app-content-main-color);
            border: 1px solid var(--app-border-color);
        }

        .scrollable-table {
            max-height: 360px;
            overflow-y: auto;
            margin-top: 15px;
            border-radius: 8px;
            background-color: var(--app-bg-2);
        }

        .scrollable-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .scrollable-table th {
            position: sticky;
            top: 0;
            background: var(--app-bg-2);
            color: var(--app-content-main-color);
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--table-border);
            z-index: 10;
        }

        .scrollable-table td {
            padding: 12px 15px;
            color: var(--app-content-main-color);
            border-bottom: 1px solid var(--table-border);
        }

        .scrollable-table tr:last-child td {
            border-bottom: none;
        }

        .scrollable-table tr:hover td {
            background-color: var(--app-bg-tasks);
        }

        .chart-container {
            background-color: var(--app-bg-2);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--filter-shadow);
        }

        @media (max-width: 1200px) {
            .card-grid-4 {
                grid-column: span 6;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                grid-template-columns: 1fr;
                padding: 10px;
                gap: 15px;
            }

            .card-grid-4, .card-grid-6 {
                grid-column: span 1;
            }

            .stats-card {
                padding: 15px;
            }

            .stats-card .value {
                font-size: 1.5rem;
            }
        }

        /* Анимации */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stats-card {
            animation: fadeIn 0.5s ease forwards;
        }

        .stats-card:nth-child(1) { animation-delay: 0.1s; }
        .stats-card:nth-child(2) { animation-delay: 0.2s; }
        .stats-card:nth-child(3) { animation-delay: 0.3s; }
        .stats-card:nth-child(4) { animation-delay: 0.4s; }
        .stats-card:nth-child(5) { animation-delay: 0.5s; }
        .stats-card:nth-child(6) { animation-delay: 0.6s; }
        .stats-card:nth-child(7) { animation-delay: 0.7s; }
    </style>
    <script>
        {!! Vite::content('resources/js/app.js') !!}
    </script>
@endsection

@section('title', 'AltayCTF-Admin')

@section('appcontent')
    <div class="app-content">
        <div id="FormSwitchTheme" class="app-content-header">
            <h1 class="app-content-headerText">{{ __('Home') }}</h1>
            <button id="switchTheme" class="mode-switch" title="Switch Theme">
                <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" width="24" height="24" viewBox="0 0 24 24">
                    <defs></defs>
                    <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                </svg>
            </button>
        </div>

        <div class="dashboard-container">
            <!-- Карточка общих задач -->
            <div class="stats-card card-grid-4">
                <h3>{{ __('Total Tasks') }}</h3>
                <div class="value" id="total-tasks">0</div>
                <div class="progress-container">
                    <div class="progress-bar" id="total-progress" style="width: 0%"></div>
                </div>
            </div>

            <!-- Карточка легких задач -->
            <div class="stats-card card-grid-4">
                <h3>{{ __('Easy Tasks') }}</h3>
                <div class="value" style="color: #2ba972;" id="easy-tasks">0</div>
                <div class="progress-container">
                    <div class="progress-bar" style="background: #2ba972; width: 0%" id="easy-progress"></div>
                </div>
            </div>

            <!-- Карточка средних задач -->
            <div class="stats-card card-grid-4">
                <h3>{{ __('Medium Tasks') }}</h3>
                <div class="value" style="color: #59719d;" id="medium-tasks">0</div>
                <div class="progress-container">
                    <div class="progress-bar" style="background: #59719d; width: 0%" id="medium-progress"></div>
                </div>
            </div>

            <!-- Карточка сложных задач -->
            <div class="stats-card card-grid-4">
                <h3>{{ __('Hard Tasks') }}</h3>
                <div class="value" style="color: #9d5959;" id="hard-tasks">0</div>
                <div class="progress-container">
                    <div class="progress-bar" style="background: #9d5959; width: 0%" id="hard-progress"></div>
                </div>
            </div>

            <!-- Диаграмма прогресса команд -->
            <div class="stats-card card-grid-12">
                <div class="chart-header">
                    <h3>{{ __('Teams Progress') }}</h3>
                    <div class="chart-controls">
                        <div class="search-box">
                            <input type="text" id="team-search" placeholder="{{ __('Search teams...') }}"
                                   oninput="updateTeamsChart(teamsData, { searchQuery: this.value })">
                            <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div class="filters">
                            <select id="team-filter" onchange="updateTeamsChart(teamsData, { showTop: this.value ? parseInt(this.value) : null })">
                                <option value="">{{ __('All teams') }}</option>
                                <option value="10">Top 10</option>
                                <option value="20">Top 20</option>
                                <option value="50">Top 50</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="chart-container">
                    <div class="diagram">
                        <div class="simple-bar-chart" id="teams-chart">
                            <!-- Динамическое содержимое будет здесь -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Распределение по категориям -->
            <div class="stats-card card-grid-6">
                <h3>{{ __('Tasks by Category') }}</h3>
                <div id="categories-container" style="margin-top: 15px;">
                    <!-- Категории будут добавлены через JS -->
                </div>
            </div>

            <!-- Последние задачи -->
            <div class="stats-card card-grid-6">
                <h3>{{ __('Tasks') }}</h3>
                <div class="scrollable-table">
                    <table class="block-item" id="tasks-table">
                        <thead>
                        <tr class="block-item-header">
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Complexity') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Задачи будут добавлены через JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Команды -->
            <div class="stats-card card-grid-6">
                <h3>{{ __('Teams') }}</h3>
                <div class="scrollable-table">
                    <table class="block-item" id="teams-table">
                        <thead>
                        <tr class="block-item-header">
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Players') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Команды будут добавлены через JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        let Tasks = {!! json_encode(\App\Models\Tasks::all()) !!};
        let Teams = {!! json_encode(\App\Models\User::all()) !!};
        let CheckTask = {!! json_encode(\App\Models\CheckTasks::all()) !!};
        let infoTasks = {!! json_encode(\App\Models\infoTasks::all()) !!};

        // Обновляем диаграмму команд
        function updateTeamsChart(options = {}) {
            const { searchQuery = '', showTop = null, currentPage = 1, itemsPerPage = 15 } = options;
            const TotalTasks = Tasks.length > 0 ? Tasks.length : 1; // Защита от деления на ноль

            // Фильтрация и сортировка команд
            let processedTeams = Teams
                .filter(team => {
                    if (!team || !team.name) return false;
                    return team.name.toLowerCase().includes(searchQuery.toLowerCase());
                })
                .map(team => {
                    const checkTask = CheckTask.find(ct => ct.user_id === team.id) || {sumary: 0};
                    const progress = Math.round((checkTask.sumary / TotalTasks) * 100);
                    return { ...team, progress };
                })
                .sort((a, b) => b.progress - a.progress);

            // Применение фильтра "топ-N" если задан
            if (showTop) {
                processedTeams = processedTeams.slice(0, showTop);
            }

            // Пагинация
            const totalPages = Math.ceil(processedTeams.length / itemsPerPage) || 1;
            const paginatedTeams = processedTeams.slice(
                (currentPage - 1) * itemsPerPage,
                currentPage * itemsPerPage
            );

            // Группировка команд по прогрессу только если команд много
            let digramhtml = '';
            if (processedTeams.length > itemsPerPage) {
                let teamsByProgress = {};
                paginatedTeams.forEach(team => {
                    if (!teamsByProgress[team.progress]) {
                        teamsByProgress[team.progress] = [];
                    }
                    teamsByProgress[team.progress].push(team);
                });

                const progressLevels = Object.keys(teamsByProgress).sort((a,b) => b-a);
                for (let i = 0; i < progressLevels.length; i++) {
                    const progress = progressLevels[i];
                    const teams = teamsByProgress[progress];

                    digramhtml += `<div class="item" style="--clr: var(--diagram-cl); --val: ${progress}">
                    <div class="label" title="${teams.length > 1 ? teams.map(t => t.name).join(', ') : teams[0].name}">
                        ${teams.length > 1 ? `${teams.length} команд` : teams[0].name}
                    </div>
                    <div class="value">${progress}%</div>
                </div>`;
                }
            } else {
                // Показываем все команды по отдельности если их немного
                paginatedTeams.forEach(team => {
                    digramhtml += `<div class="item" style="--clr: var(--diagram-cl); --val: ${team.progress}">
                    <div class="label" title="${team.name}">${team.name}</div>
                    <div class="value">${team.progress}%</div>
                </div>`;
                });
            }

            // Добавляем пагинацию если нужно
            if (totalPages > 1) {
                digramhtml += `<div class="diagram-pagination">
                ${Array.from({length: totalPages}, (_, i) => {
                    const pageNum = i + 1;
                    return `<button class="page-btn ${pageNum === currentPage ? 'active' : ''}"
                              onclick="updateTeamsChart({
                                searchQuery: '${searchQuery.replace(/'/g, "\\'")}',
                                showTop: ${showTop || 'null'},
                                currentPage: ${pageNum}
                              })">
                        ${pageNum}
                    </button>`;
                }).join('')}
            </div>`;
            }

            // Если команд слишком много, показываем сообщение
            if (processedTeams.length > itemsPerPage * currentPage) {
                digramhtml += `<div class="item-more">
                Показано ${paginatedTeams.length} из ${processedTeams.length} команд
            </div>`;
            }

            const chartContainer = document.getElementById('teams-chart');
            if (chartContainer) {
                chartContainer.innerHTML = digramhtml;
            }
        }

        // Обновляем данные на странице
        function updateDashboard(data) {
            const { Tasks = [], Teams = [], infoTasks = [{}], CheckTask = [] } = data;
            const maxTasks = Math.max(
                infoTasks[0]?.easy || 0,
                infoTasks[0]?.medium || 0,
                infoTasks[0]?.hard || 0,
                10
            );

            // Обновляем карточки статистики
            if (infoTasks[0]) {
                document.getElementById('total-tasks').textContent = infoTasks[0].sumary || 0;
                document.getElementById('easy-tasks').textContent = infoTasks[0].easy || 0;
                document.getElementById('medium-tasks').textContent = infoTasks[0].medium || 0;
                document.getElementById('hard-tasks').textContent = infoTasks[0].hard || 0;

                // Анимируем прогресс-бары
                animateProgress('total-progress', (infoTasks[0].sumary || 0) / maxTasks * 100);
                animateProgress('easy-progress', (infoTasks[0].easy || 0) / maxTasks * 100);
                animateProgress('medium-progress', (infoTasks[0].medium || 0) / maxTasks * 100);
                animateProgress('hard-progress', (infoTasks[0].hard || 0) / maxTasks * 100);
            }

            // Обновляем категории
            if (infoTasks[0]) {
                const categories = [
                    { name: 'admin', value: infoTasks[0].admin },
                    { name: 'recon', value: infoTasks[0].recon },
                    { name: 'crypto', value: infoTasks[0].crypto },
                    { name: 'stegano', value: infoTasks[0].stegano },
                    { name: 'ppc', value: infoTasks[0].ppc },
                    { name: 'pwn', value: infoTasks[0].pwn },
                    { name: 'web', value: infoTasks[0].web },
                    { name: 'forensic', value: infoTasks[0].forensic },
                    { name: 'joy', value: infoTasks[0].joy },
                    { name: 'misc', value: infoTasks[0].misc },
                    { name: 'osint', value: infoTasks[0].osint },
                    { name: 'reverse', value: infoTasks[0].reverse }
                ].filter(cat => cat.value > 0);

                const categoriesContainer = document.getElementById('categories-container');
                if (categoriesContainer) {
                    categoriesContainer.innerHTML = categories.map(cat =>
                        `<span class="task-category ${cat.name}">${cat.name}: ${cat.value}</span>`
                    ).join('');
                }
            }

            // Обновляем таблицу задач
            const tasksTable = document.querySelector('#tasks-table tbody');
            if (tasksTable) {
                tasksTable.innerHTML = Tasks.map(task => `
                <tr>
                    <td>${task.name || ''}</td>
                    <td>${task.category || ''}</td>
                    <td><span class="status ${(task.complexity || '').toLowerCase()}">${task.complexity || ''}</span></td>
                </tr>
            `).join('');
            }

            // Обновляем таблицу команд
            const teamsTable = document.querySelector('#teams-table tbody');
            if (teamsTable) {
                teamsTable.innerHTML = Teams.map(team => `
                <tr>
                    <td>${team.name || ''}</td>
                    <td>${team.players || 0}</td>
                </tr>
            `).join('');
            }
        }

        // Анимация прогресс-баров
        function animateProgress(id, targetPercent) {
            const element = document.getElementById(id);
            if (!element) return;

            let currentPercent = 0;
            const duration = 1000;
            const increment = targetPercent / (duration / 16);

            const animate = () => {
                if (currentPercent < targetPercent) {
                    currentPercent += increment;
                    element.style.width = `${Math.min(currentPercent, targetPercent)}%`;
                    requestAnimationFrame(animate);
                }
            };

            animate();
        }

        // Инициализация данных при загрузке
        document.addEventListener('DOMContentLoaded', function() {
            updateTeamsChart();
            updateDashboard({ Tasks, Teams, infoTasks, CheckTask });

            // Обработчики для элементов управления
            document.getElementById('team-search')?.addEventListener('input', function() {
                const showTop = document.getElementById('team-filter')?.value;
                updateTeamsChart({
                    searchQuery: this.value,
                    showTop: showTop ? parseInt(showTop) : null
                });
            });

            document.getElementById('team-filter')?.addEventListener('change', function() {
                const searchQuery = document.getElementById('team-search')?.value || '';
                updateTeamsChart({
                    searchQuery,
                    showTop: this.value ? parseInt(this.value) : null
                });
            });
        });

        // Обработка событий WebSocket
        Echo.private(`channel-admin-home`).listen('AdminHomeEvent', (e) => {
            console.log('Обновление данных через WebSocket');

            // Обновляем глобальные данные
            Tasks = e.data[0] || [];
            Teams = e.data[1] || [];
            infoTasks = e.data[2] || [{}];
            CheckTask = e.data[3] || [];

            // Получаем текущие параметры фильтрации
            const searchQuery = document.getElementById('team-search')?.value || '';
            const showTop = document.getElementById('team-filter')?.value;

            updateTeamsChart({
                searchQuery,
                showTop: showTop ? parseInt(showTop) : null
            });

            updateDashboard({
                Tasks: e.data[0] || [],
                Teams: e.data[1] || [],
                infoTasks: e.data[2] || [{}],
                CheckTask: e.data[3] || []
            });
        });
    </script>
@endsection
