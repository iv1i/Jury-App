@extends('layouts.app')

@section('css')
    <script>
        {!! Vite::content('resources/js/app.js') !!}
    </script>
@endsection

@section('title', 'AltayCTF-Sch-Scoreboard')

@section('appcontent')
    <div class="app-content">
        <div id="FormSwitchTheme" class="app-content-header">
            <h1 class="app-content-headerText">{{ __('Scoreboard') }}</h1>
            <button id="switchTheme" class="mode-switch" title="Switch Theme">
                <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                     stroke-width="2" width="24" height="24" viewBox="0 0 24 24">
                    <defs></defs>
                    <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                </svg>
            </button>
            <div class="filter-button-wrapper HiddenBlock">
                <button class="action-button filter jsFilter"><span>Filter</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-filter">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                    </svg>
                </button>
                <div class="filter-menu">
                    <label>Category</label>
                    <select>
                        <option>All Categories</option>
                        <option>Furniture</option>
                        <option>Decoration</option>
                        <option>Kitchen</option>
                        <option>Bathroom</option>
                    </select>
                    <label>Status</label>
                    <select>
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Disabled</option>
                    </select>
                    <div class="filter-menu-buttons">
                        <button class="filter-button reset">
                            Reset
                        </button>
                        <button class="filter-button apply">
                            Apply
                        </button>
                    </div>
                </div>
            </div>
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
            <input class="search-bar HiddenBlock" placeholder="Search..." type="text">
            <div class="app-content-actions-wrapper">
            </div>
        </div>
        <div class="products-area-wrapper tableView">
            <div class="products-header">
                <div class="product-cell image">
                    {{ __('Teams') }}
                    <button class="sort-button sort-button-teams">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button>
                </div>
                <div class="product-cell sales">{{ __('Scores') }}<button class="sort-button sort-button-scores">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
                <div class="product-cell price">{{ __('Tasks') }}<button class="sort-button sort-button-tasks">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
            </div>
            <div class="Product-body">

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        const data = {!! json_encode($M) !!};
        const desidedteams = {!! json_encode(\App\Models\desided_tasks_teams::all()) !!};
        const divElement = document.querySelector('.Product-body');

        MakeHTML(MakeMassive(data, desidedteams), divElement);

        Echo.private(`channel-app-scoreboard`).listen('AppScoreboardEvent', (e) => {
            const valueToDisplay = e.scoreboard;
            const data2 = valueToDisplay.Teams;
            const desidedteams = valueToDisplay.DesidedT;

            console.log('Принято!');
            MakeHTML(MakeMassive(data2, desidedteams), divElement);
        });

        function MakeMassive(data2, DesidedTeams){
            const svg = `{!! view('SVG.GuestSVG') !!}`;
            const desidedteams = DesidedTeams;
            const divimgElement = document.querySelector('.account-info-picture');
            const authuserid = {{ Auth::user()->id }};
            for (let i = 0; i < data2.length; i++) {
                if (data2[i].guest === 'Yes') {
                    data2[i].GuestLogo = svg;
                }
                if (data2[i].id === authuserid) {
                    divimgElement.innerHTML = `<img src="/storage/teamlogo/${data2[i].teamlogo}" alt="Account">`;
                }
                if (data2[i].id === authuserid) {
                    data2[i].BorderStyle = `BorderStyle`;
                } else {
                    data2[i].BorderStyle = '';
                }
            }
            for (let i = 0; i < data2.length; i++) {
                data2[i].style = '';
            }

            count = 0;
            const userStyles = {};
            desidedteams.forEach(team => {
                if (!userStyles[team.user_id]) {
                    userStyles[team.user_id] = [];
                }
                userStyles[team.user_id].push(team.StyleTask);
            });

            // Обрабатываем массив Teams
            data2.forEach(team => {
                const user_id = team.id; // Предполагается, что id команды совпадает с id пользователя
                if (userStyles[user_id]) {
                    team.style = userStyles[user_id].join(''); // Присваиваем стили команде
                }
            });
            const sortedData = data2.sort((a, b) => b.scores - a.scores);

            return sortedData;
        }

        function MakeHTML(Data, Element) {
            const host = window.location.hostname;
            const protocol = window.location.protocol;
            const port = window.location.port;
            const url = protocol + '//' + host + ':' + port + '/storage/teamlogo/';
            const html0 = `<div class="products-header">
                <div class="product-cell image">
                    {{ __('Teams') }}
                    <button class="sort-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button>
                </div>
                <div class="product-cell sales">{{ __('Scores') }}<button class="sort-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
                <div class="product-cell price">{{ __('Tasks') }}<button class="sort-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
            </div>`;
            const html1 = Data.map(item => `
            <div class="products-row ${item.BorderStyle}">
                <div class="product-cell image">
                    <img class="logo_sc" src="${url + item.teamlogo}" alt="teamlogo">
                    <span class="span-name">${item.name} ${item.guest !== 'No' ? '<div class="guest-badge">{{ __('GUEST') }}</div>': ''}</span>
                </div>
                <div class="product-cell sales"><span class="cell-label">{{ __('Scores') }}:</span>${item.scores}</div>
                <div class="product-cell price" style="display: flex; flex-wrap: wrap;"><span class="cell-label">{{ __('Tasks') }}:</span>
                    ${item.style}

                </div>
            </div>
        `).join("");
            const HTML = html1;
            Element.innerHTML = HTML;
        }

        const divElementSort = document.querySelector('.Product-body');
        var sortButton0 = document.querySelector('.sort-button-teams');
        var sortButton1 = document.querySelector('.sort-button-scores');
        var sortButton2 = document.querySelector('.sort-button-tasks');
        let Teams = data; // Исходные данные
        let originalTeams = [...data]; // Сохраняем копию исходных данных
        let isSorted0 = 0;
        let isSorted1 = 0;
        let isSorted2 = 0;
        let isSorted3 = 0;
        let isSorted4 = 0;
        const host = window.location.hostname;
        const protocol = window.location.protocol;
        const port = window.location.port;
        const url = protocol + '//' + host + ':' + port + '/storage/teamlogo/';

        sortButton0.addEventListener('click', function () {
            isSorted0++;
            isSorted1 = 0;
            isSorted2 = 0;
            isSorted3 = 0;
            isSorted4 = 0;
            Sorting(Teams, originalTeams, divElementSort, isSorted0, 'name')
            if (isSorted0 === 2){
                isSorted0 = 0;
            }
        });
        sortButton1.addEventListener('click', function () {
            isSorted1++;
            isSorted0 = 0;
            isSorted2 = 0;
            isSorted3 = 0;
            isSorted4 = 0;
            Sorting(Teams, originalTeams, divElementSort, isSorted1, 'scores')
            if (isSorted1 >= 2){
                isSorted1 = 0;
            }
        });
        sortButton2.addEventListener('click', function () {
            isSorted2++;
            isSorted1 = 0;
            isSorted0 = 0;
            isSorted3 = 0;
            isSorted4 = 0;
            Sorting(Teams, originalTeams, divElementSort, isSorted2, 'complexity')
            if (isSorted2 >= 2){
                isSorted2 = 0;
            }
        });

        function Sorting(M, originalM, divElemSort, isSorted, column){
            console.log(M)
            if (isSorted === 1) {
                // Сортируем команды по имени
                M = SortDirect(M, column)
                // Обновляем HTML с отсортированными данными
                MakeHTML(M, divElemSort);

            } else if (isSorted === 2) {
                // Возвращаемся к исходным данным
                M = SortReverse(M, column); // Возвращаем оригинальные данные
                // Обновляем HTML с отсортированными данными
                MakeHTML(M, divElemSort);

            }
        }

        function SortDirect(M, column){
            if (column === "name"){
                M.sort(function (a, b) {
                    // Проверяем, есть ли значение в поле decide
                    const aHasDeside = a.decide && a.decide.trim() !== '';
                    const bHasDeside = b.decide && b.decide.trim() !== '';

                    // Если у a есть значение в decide, а у b нет, a должен быть после b
                    if (aHasDeside && !bHasDeside) {
                        return 1;
                    }
                    // Если у b есть значение в decide, а у a нет, b должен быть после a
                    if (!aHasDeside && bHasDeside) {
                        return -1;
                    }

                    // Если оба имеют или не имеют значение в decide, сортируем по category
                    var nameA = a.name.toLowerCase();
                    var nameB = b.name.toLowerCase();

                    if (nameA < nameB) {
                        return -1;
                    }
                    if (nameA > nameB) {
                        return 1;
                    }
                    return 0;
                });
            }
            if (column === "scores"){
                M.sort(function (a, b) {
                    // Проверяем, есть ли значение в поле decide
                    const aHasDeside = a.decide && a.decide.trim() !== '';
                    const bHasDeside = b.decide && b.decide.trim() !== '';

                    // Если у a есть значение в decide, а у b нет, a должен быть после b
                    if (aHasDeside && !bHasDeside) {
                        return 1;
                    }
                    // Если у b есть значение в decide, а у a нет, b должен быть после a
                    if (!aHasDeside && bHasDeside) {
                        return -1;
                    }

                    // Если оба имеют или не имеют значение в decide, сортируем по category
                    var nameA = a.scores;
                    var nameB = b.scores;

                    if (nameA < nameB) {
                        return -1;
                    }
                    if (nameA > nameB) {
                        return 1;
                    }
                    return 0;
                });
            }
            if (column === "complexity"){
                M.sort(function (a, b) {
                    // Проверяем, есть ли значение в поле decide
                    const aHasDeside = a.decide && a.decide.trim() !== '';
                    const bHasDeside = b.decide && b.decide.trim() !== '';

                    // Если у a есть значение в decide, а у b нет, a должен быть после b
                    if (aHasDeside && !bHasDeside) {
                        return 1;
                    }
                    // Если у b есть значение в decide, а у a нет, b должен быть после a
                    if (!aHasDeside && bHasDeside) {
                        return -1;
                    }

                    // Если оба имеют или не имеют значение в decide, сортируем по category
                    var nameA = a.scores;
                    var nameB = b.scores;

                    if (nameA < nameB) {
                        return -1;
                    }
                    if (nameA > nameB) {
                        return 1;
                    }
                    return 0;
                });
            }

            return M;
        }
        function SortReverse(M, column){
            if (column === "name"){
                M.sort(function (a, b) {
                    // Проверяем, есть ли значение в поле decide
                    const aHasDeside = a.decide && a.decide.trim() !== '';
                    const bHasDeside = b.decide && b.decide.trim() !== '';

                    // Если у a есть значение в decide, а у b нет, a должен быть после b
                    if (aHasDeside && !bHasDeside) {
                        return 1;
                    }
                    // Если у b есть значение в decide, а у a нет, b должен быть после a
                    if (!aHasDeside && bHasDeside) {
                        return -1;
                    }

                    // Если оба имеют или не имеют значение в decide, сортируем по category
                    var nameA = a.name.toLowerCase();
                    var nameB = b.name.toLowerCase();

                    if (nameA < nameB) {
                        return 1;
                    }
                    if (nameA > nameB) {
                        return -1;
                    }
                    return 0;
                });
            }
            if (column === "scores"){
                M.sort(function (a, b) {
                    // Проверяем, есть ли значение в поле decide
                    const aHasDeside = a.decide && a.decide.trim() !== '';
                    const bHasDeside = b.decide && b.decide.trim() !== '';

                    // Если у a есть значение в decide, а у b нет, a должен быть после b
                    if (aHasDeside && !bHasDeside) {
                        return 1;
                    }
                    // Если у b есть значение в decide, а у a нет, b должен быть после a
                    if (!aHasDeside && bHasDeside) {
                        return -1;
                    }

                    // Если оба имеют или не имеют значение в decide, сортируем по category
                    var nameA = a.scores;
                    var nameB = b.scores;

                    if (nameA < nameB) {
                        return 1;
                    }
                    if (nameA > nameB) {
                        return -1;
                    }
                    return 0;
                });
            }
            if (column === "complexity"){
                M.sort(function (a, b) {
                    // Проверяем, есть ли значение в поле decide
                    const aHasDeside = a.decide && a.decide.trim() !== '';
                    const bHasDeside = b.decide && b.decide.trim() !== '';

                    // Если у a есть значение в decide, а у b нет, a должен быть после b
                    if (aHasDeside && !bHasDeside) {
                        return 1;
                    }
                    // Если у b есть значение в decide, а у a нет, b должен быть после a
                    if (!aHasDeside && bHasDeside) {
                        return -1;
                    }

                    // Если оба имеют или не имеют значение в decide, сортируем по category
                    var nameA = a.scores;
                    var nameB = b.scores;

                    if (nameA < nameB) {
                        return 1;
                    }
                    if (nameA > nameB) {
                        return -1;
                    }
                    return 0;
                });
            }

            return M;
        }
    </script>
@endsection

