@extends('layouts.admin')

@section('css')
    <script>
        {!! Vite::content('resources/js/app.js') !!}
    </script>
@endsection

@section('title', 'AltayCTF-Admin')


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
            <input class="search-bar HiddenBlock" placeholder="Search..." type="text" >
            <div class="app-content-actions-wrapper">
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
                </div >
            </div>
        </div>
        <div class="products-area-wrapper tableView">
            <div class="products-header">
                <div class="product-cell image">Teams
                </div>
                <div class="product-cell category">Scores
                </div>
                <div class="product-cell price">Tasks
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        //--------------------------------Init-Of-Data
        const data = {!! json_encode($Users) !!};
        const desidedteams = {!! json_encode($DesidedT) !!};
        const divElement = document.querySelector('.products-area-wrapper');

        //--------------------------------Functions
        // Создает массив
        function MakeMassive(data2, DesidedTeams){
            const svg = `{!! view('SVG.GuestSVG') !!}`;
            const desidedteams = DesidedTeams;
            for (let i = 0; i < data2.length; i++) {
                if (data2[i].guest === 'Yes') {
                    data2[i].GuestLogo = svg;
                }
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
        // Формирование данных на странице
        function MakeHTML(Data, Element) {
            const host = window.location.hostname;
            const protocol = window.location.protocol;
            const port = window.location.port;
            const url = protocol + '//' + host + ':' + port + '/storage/teamlogo/';
            const html0 = `<div class="products-header">
                <div class="product-cell image">{{ __('Teams') }}</div>
                <div class="product-cell sales">{{ __('Scores') }}</div>
                <div class="product-cell price">{{ __('Tasks') }}</div>
            </div>`;
            const html1 = Data.map(item => `
            <div class="products-row" ${item.BorderStyle}>
                <div class="product-cell image">
                    <img src="${url + item.teamlogo}" alt="product">
                    <span>${item.name} ${item.guest !== 'No' ? '<div class="guest-badge">{{ __('GUEST') }}</div>': ''}</span>
                </div>
                <div class="product-cell sales"><span class="cell-label">{{ __('Scores') }}:</span>${item.scores}</div>
                <div class="product-cell price" style="display: flex; flex-wrap: wrap;"><span class="cell-label">{{ __('Tasks') }}:</span>${item.style}</div>
            </div>
        `).join("");
            const HTML = html0 + html1;
            Element.innerHTML = HTML;
        }

        //--------------------------------Start-Functions
        MakeHTML(MakeMassive(data, desidedteams), divElement);

        //--------------------------------WebSocket
        Echo.private(`channel-admin-scoreboard`).listen('AdminScoreboardEvent', (e) => {
            const valueToDisplay = e.scoreboard;
            const data = valueToDisplay.Teams;
            const desidedteams = valueToDisplay.DesidedT;

            console.log('Принято!');
            MakeHTML(MakeMassive(data, desidedteams), divElement);
        });
    </script>
@endsection

