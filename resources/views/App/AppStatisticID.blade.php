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
        const data = {!! json_encode(\App\Models\Tasks::all()) !!};
        let team = {!! json_encode(\App\Models\Teams::find($id)) !!};
        const teamid = {{ $id }};
        let teamlogo = '{{ asset('storage/teamlogo/' . \App\Models\Teams::find($id)['teamlogo']) }}';
        let chkt = {!! json_encode($chkT) !!};
        let solvedtasks = {!! json_encode($TeamSolvedTAsks) !!};


        //console.log(team);
        //console.log(chkt);
        //console.log(teamlogo);

        //const sortedData = data.sort((a, b) => b.score - a.score);
        //console.log(sortedData);
        const divElement2 = document.querySelector('.app-content-body-wrapper');
        const divElement = document.querySelector('.Product-body');

        const html0 = `<div class="wrapper">
                <div class="profile-card js-profile-card">
                    <div class="profile-card__img">
                        <img src="${teamlogo}" alt="product">
                    </div>

                    <div class="profile-card__cnt js-profile-cnt">
                        <div class="profile-card__name">${team.name}</div>
                        <div class="profile-card__name">${team.scores}</div>
                        <div class="profile-card__txt"><strong>${team.wherefrom}</strong></div>

                        <div class="profile-card-inf">
                            <div class="profile-card-inf__item">
                                <div class="profile-card-inf__title">${chkt.sumary}</div>
                                <div class="profile-card-inf__txt">{{ __('Solved') }}</div>
                            </div>

                            <div class="profile-card-inf__item">
                                <div class="profile-card-inf__title">${chkt.easy}</div>
                                <div class="profile-card-inf__txt easy">EASY</div>
                            </div>

                            <div class="profile-card-inf__item">
                                <div class="profile-card-inf__title">${chkt.medium}</div>
                                <div class="profile-card-inf__txt medium">MEDIUM</div>
                            </div>

                            <div class="profile-card-inf__item">
                                <div class="profile-card-inf__title">${chkt.hard}</div>
                                <div class="profile-card-inf__txt hard">HARD</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        divElement2.innerHTML = html0;
        const html22 = `
            <div class="products-header">
                <div class="product-cell image">
                    {{ __('Name') }}
        <button class="sort-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                <path fill="currentColor"
                      d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/>
            </svg>
        </button>
    </div>
    <div class="product-cell category">{{ __('Category') }}
        <button class="sort-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                <path fill="currentColor"
                      d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/>
            </svg>
        </button>
    </div>
    <div class="product-cell status-cell">{{ __('Complexity') }}
        <button class="sort-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                <path fill="currentColor"
                      d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/>
            </svg>
        </button>
    </div>
    <div class="product-cell price">{{ __('Price') }}
        <button class="sort-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                <path fill="currentColor"
                      d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/>
            </svg>
        </button>
    </div>
</div>
`;
        const html2 = solvedtasks.map(item => `
            <div href="/Home/${item.id}" class="products-row tasklink">
                <div class="product-cell image">
                    <span>${item.name}</span>
                </div>
                <div class="product-cell category"><span class="cell-label">{{ __('Category') }}:</span>${item.category.toUpperCase()}</div>
                <div class="product-cell status-cell"><span class="cell-label">{{ __('Complexity') }}:</span><span class="status ${item.complexity}">${item.complexity.toUpperCase()}</span></div>
                <div class="product-cell price"><span class="cell-label">{{ __('Price') }}:</span>${item.price}</div>
            </div>
        `).join("");

        divElement.innerHTML = html2;

        Echo.private(`channel-app-statisticID`).listen('AppStatisticIDEvent', (e) => {
            const divElementLogo = document.querySelector('.account-info-picture');
            const valueToDisplay = e.data;
            let CHkT = {};
            for (let i = 0; i < valueToDisplay.Team.length; i++) {
                if (valueToDisplay.Team[i].id === teamid) {
                    team = valueToDisplay.Team[i];
                    //console.log(team);
                    break;
                }
            }
            for (let i = 0; i < valueToDisplay.CHKT.length; i++) {
                if (teamid === valueToDisplay.CHKT[i].user_id) {
                    CHkT = valueToDisplay.CHKT[i];
                    //console.log(valueToDisplay.CHKT[i]);
                    break;
                }
            }
            teamlogo = '/storage/teamlogo/' + team.teamlogo;
            const htmllogo = `<img src="${teamlogo}" alt="Account">`;
            divElementLogo.innerHTML = htmllogo;
            let SolvedTasks = [];
            for (let i = 0; i < valueToDisplay.SolvedTasks.length; i++) {
                if (valueToDisplay.SolvedTasks[i].user_id === teamid) {
                    SolvedTasks.push(valueToDisplay.SolvedTasks[i]);
                }
            }

            let Solvedtasks = [];
            for (let i = 0; i < valueToDisplay.Tasks.length; i++) {
                for (let j = 0; j < SolvedTasks.length; j++) {
                    if (SolvedTasks[j].tasks_id === valueToDisplay.Tasks[i].id) {
                        Solvedtasks.push(valueToDisplay.Tasks[i]);
                    }
                }
            }

            console.log('Принято!');

            //const sortedData = valueToDisplay.sort((a, b) => b.score - a.score);
            //console.log(sortedData);

            const html0 = `<div class="wrapper">
                <div class="profile-card js-profile-card">
                    <div class="profile-card__img">
                        <img src="${teamlogo}" alt="product">
                    </div>

                    <div class="profile-card__cnt js-profile-cnt">
                        <div class="profile-card__name">${team.name}</div>
                        <div class="profile-card__name">${team.scores}</div>
                        <div class="profile-card__txt"><strong>${team.wherefrom}</strong></div>

                        <div class="profile-card-inf">
                            <div class="profile-card-inf__item">
                                <div class="profile-card-inf__title">${CHkT.sumary}</div>
                                <div class="profile-card-inf__txt">{{ __('Solved') }}</div>
                            </div>

                            <div class="profile-card-inf__item">
                                <div class="profile-card-inf__title">${CHkT.easy}</div>
                                <div class="profile-card-inf__txt easy">EASY</div>
                            </div>

                            <div class="profile-card-inf__item">
                                <div class="profile-card-inf__title">${CHkT.medium}</div>
                                <div class="profile-card-inf__txt medium">MEDIUM</div>
                            </div>

                            <div class="profile-card-inf__item">
                                <div class="profile-card-inf__title">${CHkT.hard}</div>
                                <div class="profile-card-inf__txt hard">HARD</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            divElement2.innerHTML = html0;

            const html2 = Solvedtasks.map(item => `
            <div class="products-row">
                <button class="cell-more-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                </button>
                <div class="product-cell image">
                    <span>${item.name}</span>
                </div>
                <div class="product-cell category"><span class="cell-label">Category:</span>${item.category.toUpperCase()}</div>
                <div class="product-cell status-cell">
                    <span class="cell-label">Complexity:</span>
                    <span class="status ${item.complexity}">${item.complexity.toUpperCase()}</span>
                </div>
                <div class="product-cell price"><span class="cell-label">Price:</span>${item.price}</div>
            </div>
        `).join("");

            divElement.innerHTML = html22 + html2;
            //console.log(e.test);
        });
    </script>
@endsection


