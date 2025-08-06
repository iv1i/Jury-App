@extends('layouts.admin')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('style/css/Notif.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/AdminTasks.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/AdminTeams.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/AdminTask.css') }}">
    <link rel="stylesheet" href="{{ asset('style/scss/buttoncheckflag.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/InputFile.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/Statistic.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        {!! Vite::content('resources/js/app.js') !!}
    </script>
@endsection

@section('title', 'AltayCTF-Admin')

@section('appcontent')
    <div class="notifications">
        <div class="toast ">
            <div  class="toast-content">
                <i class="fas fa-solid fa-check check"></i>

                <div class="message">
                    <span class="text text-1"></span>
                    <span class="text text-2"></span>
                    <span class="text text-3"></span>
                </div>
            </div>
            <i style="color: var(--app-bg-inv)" class="fa-solid fa-xmark close">
            </i>
            <style>
                .toast .progress:before {
                    background-color: #f4406a;
                }
            </style>
            <!-- Remove 'active' class, this is just to show in Codepen thumbnail -->
            <div  class="progress"></div>
        </div>
    </div>
    <div class="Teams-Container"></div>
    <div class="app-content">
        <div id="FormSwitchTheme" class="app-content-header">
            <h1 class="app-content-headerText">{{ __('Teams') }}</h1>
            <button id="button-plus" class="button-plus">
                <svg id="button-plus-svg" version="1.0" xmlns="http://www.w3.org/2000/svg"
                     width="30px" height="30px" viewBox="0 0 512.000000 512.000000"
                     preserveAspectRatio="xMidYMid meet">

                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                       fill="var(--app-bg-inv)" stroke="none">
                        <path d="M1696 5104 c-258 -47 -493 -170 -681 -359 -193 -193 -314 -425 -361
-695 -24 -140 -15 -381 19 -510 116 -441 422 -775 837 -914 142 -47 258 -66
410 -66 350 0 659 128 905 375 151 151 244 301 310 498 46 139 65 256 65 407
0 350 -127 657 -375 905 -192 192 -425 314 -689 360 -110 19 -332 18 -440 -1z
m369 -429 c323 -57 574 -281 672 -602 23 -77 26 -106 27 -228 0 -115 -4 -154
-22 -218 -88 -308 -301 -521 -609 -609 -64 -18 -103 -22 -218 -22 -122 1 -151
4 -228 27 -49 15 -112 39 -140 54 -522 264 -637 953 -227 1363 197 197 469
283 745 235z"/>
                        <path d="M4180 3318 c-52 -28 -97 -87 -110 -144 -5 -21 -10 -137 -10 -256 l0
-218 -238 0 c-230 0 -240 -1 -283 -23 -54 -28 -104 -99 -113 -159 -9 -63 15
-135 61 -180 66 -64 85 -68 343 -68 l230 0 0 -218 c0 -254 8 -297 67 -354 93
-91 213 -91 306 0 59 57 67 100 67 354 l0 218 233 0 c264 0 283 5 350 78 l37
42 0 94 c0 87 -2 96 -29 133 -16 22 -49 50 -73 62 -41 20 -60 21 -280 21
l-236 0 -4 243 -3 242 -28 48 c-41 69 -94 101 -176 105 -55 3 -74 -1 -111 -20z"/>
                        <path d="M1745 2129 c-903 -85 -1623 -785 -1735 -1689 -16 -130 -13 -275 6
-313 40 -79 113 -127 194 -127 84 0 147 35 192 108 16 26 22 60 29 160 20 292
95 530 235 750 208 326 556 567 947 653 144 32 398 37 552 11 307 -53 580
-192 799 -409 121 -120 205 -235 281 -385 101 -199 147 -372 164 -620 9 -133
20 -165 73 -215 81 -76 222 -69 299 14 49 52 59 84 59 190 0 506 -251 1050
-644 1394 -401 353 -930 527 -1451 478z" />
                    </g>
                </svg>
            </button>
            <button id="button-minus" class="button-minus">
                <svg id="button-minus-svg" version="1.0" xmlns="http://www.w3.org/2000/svg"
                     width="30px" height="30px" viewBox="0 0 512.000000 512.000000"
                     preserveAspectRatio="xMidYMid meet">

                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                       fill="var(--app-bg-inv)" stroke="none">
                        <path d="M1696 5104 c-258 -47 -493 -170 -681 -359 -193 -193 -314 -425 -361
-695 -24 -140 -15 -381 19 -510 116 -441 422 -775 837 -914 142 -47 258 -66
410 -66 350 0 659 128 905 375 151 151 244 301 310 498 46 139 65 256 65 407
0 350 -127 657 -375 905 -192 192 -425 314 -689 360 -110 19 -332 18 -440 -1z
m369 -429 c323 -57 574 -281 672 -602 23 -77 26 -106 27 -228 0 -115 -4 -154
-22 -218 -88 -308 -301 -521 -609 -609 -64 -18 -103 -22 -218 -22 -122 1 -151
4 -228 27 -49 15 -112 39 -140 54 -522 264 -637 953 -227 1363 197 197 469
283 745 235z"/>
                        <path d="M3555 2686 c-59 -27 -77 -46 -104 -106 -35 -77 -24 -157 31 -224 65
-79 26 -76 806 -76 666 0 694 1 731 19 21 11 52 36 69 56 32 35 32 37 32 142
l0 106 -45 43 c-24 24 -61 48 -82 53 -25 7 -271 11 -713 11 l-675 0 -50 -24z"/>
                        <path d="M1745 2129 c-903 -85 -1623 -785 -1735 -1689 -16 -130 -13 -275 6
-313 40 -79 113 -127 194 -127 84 0 147 35 192 108 16 26 22 60 29 160 20 292
95 530 235 750 208 326 556 567 947 653 144 32 398 37 552 11 307 -53 580
-192 799 -409 121 -120 205 -235 281 -385 101 -199 147 -372 164 -620 9 -133
20 -165 73 -215 81 -76 222 -69 299 14 49 52 59 84 59 190 0 506 -251 1050
-644 1394 -401 353 -930 527 -1451 478z"/>
                    </g>
                </svg>

            </button>
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
            <input class="search-bar HiddenBlock" placeholder="Search..." type="text">
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
                            <option>WEB</option>
                            <option>JOY</option>
                            <option>CRYPT</option>
                            <option>PWN</option>
                        </select>
                        <label>Status</label>
                        <select>
                            <option>All Status</option>
                            <option>Easy</option>
                            <option>Medium</option>
                            <option>Hard</option>
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
            </div>
        </div>
        <div class="products-area-wrapper tableView">
            <div class="products-header">
                <div class="product-cell id">{{ __('ID') }}</div>
                <div class="product-cell image">{{ __('Name')}}</div>
                <div class="product-cell category">{{ __('Players') }}</div>
                <div class="product-cell status-cell">{{ __('Where-From') }}</div>
                <div class="product-cell category">{{ __('Token') }}</div>
            </div>
            <div class="Product-body">

            </div>
        </div>
        <div class="CloseTeamBanner" style="display: none; width: 100%; height: 100%; background-color: white; position:absolute; opacity: .0;" onclick="closeAllTeams()"></div>
    </div>
    <div style="display:none;" class="topmost-div-task-minus">
        <div style="text-align: center; color: white; height: 3vw;">
            <h1 class="TaskH1">{{ __('Delete Teams') }}</h1>
            <button id="CloseBtnMinus" class="btnclose"><img class="closeicontask" src="{{ asset('media/icon/close.png') }}">
            </button>
        </div>
        <form method="POST" class="form" id="MyFormMinus" action="/Admin/Teams/Delete">
            @csrf
            @method('DELETE')
            <div class="form_item">
                <div>{{ __('ID') }}</div>
                <input type="text" name="ID" class="" required="" autocomplete="off">
            </div>
            <div class="form_item">
                <button type="submit" style="width: 20%;" class="btnchk"
                        onClick={console.log("click")}>
                    {{ __('Delete') }}
                    <svg width="79" height="46" viewBox="0 0 79 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#filter0_f_618_1123)">
                            <path d="M42.9 2H76.5L34.5 44H2L42.9 2Z" fill="url(#paint0_linear_618_1123)"/>
                        </g>
                        <defs>
                            <filter id="filter0_f_618_1123" x="0" y="0" width="78.5" height="46"
                                    filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                                <feGaussianBlur stdDeviation="1" result="effect1_foregroundBlur_618_1123"/>
                            </filter>
                            <linearGradient id="paint0_linear_618_1123" x1="76.5" y1="2.00002" x2="34.5" y2="44"
                                            gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.6"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.05"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </button>
            </div>
        </form>
        <h1></h1>
    </div>
    <div style="display:none;" class="topmost-div-task-plus">
        <div style="text-align: center; color: white; height: 3vw;">
            <h1 class="TaskH1">{{ __('Add Teams') }}</h1>
            <button id="CloseBtnPlus" class="btnclose"><img class="closeicontask" src="{{ asset('media/icon/close.png') }}">
            </button>
        </div>
        <form action="/Admin/Teams/Add" method="POST" id="MyFormPlus" class="form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form_item">
                <div>Название</div>
                <input type="text" name="name" class="" required="" autocomplete="off">
            </div>
            <div class="form_item">
                <div>Кол-во игроков</div>
                <input type="text" name="players" class="" autocomplete="off" placeholder="минимум 1">
            </div>
            <div class="form_item">
                <div>Учебное заведение</div>
                <input type="text" name="WhereFrom" class="" autocomplete="off">
            </div>
            <div class="form_item">
                <div>Пароль</div>
                <input type="text" name="password" placeholder="минимум 6 символов" class="" required="" autocomplete="off">
            </div>
            <div class="form_item">
                <div>Лого</div>
                <div><img id="teamlogoimg0" class="teamlogoimg" style="height: 7vw; display: none" src=""></div>
                <label for="images0" class="drop-container team-id0" id="dropcontainer">
                    <span class="drop-title">{{ __('Drop files here') }}</span>
                    <input type="file" name="file" id="images0" accept="image/jpeg, image/png" multiple>
                </label>
                <div class="form_item">Гостевая
                    <input type="checkbox" name="IsGuest" class="is_guest">
                </div>
                <div class="form_item">Стандартный логотип
                    <input type="checkbox" name="standartlogo" class="standartlogo">
                </div>
                <button class="btnchk" onClick={console.log("click")} type="submit">
                    {{ __('Add') }}
                    <svg width="79" height="46" viewBox="0 0 79 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#filter0_f_618_1123)">
                            <path d="M42.9 2H76.5L34.5 44H2L42.9 2Z" fill="url(#paint0_linear_618_1123)"/>
                        </g>
                        <defs>
                            <filter id="filter0_f_618_1123" x="0" y="0" width="78.5" height="46"
                                    filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                                <feGaussianBlur stdDeviation="1" result="effect1_foregroundBlur_618_1123"/>
                            </filter>
                            <linearGradient id="paint0_linear_618_1123" x1="76.5" y1="2.00002" x2="34.5" y2="44"
                                            gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.6"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.05"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </button>
            </div>
        </form>
        <h1></h1>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/Other/Notifications.js') }}"></script>
    <script src="{{ asset('js/Admin/AdminOpenBlock.js') }}"></script>
    <script type="text/javascript" id="V2">
        //--------------------------------Init-Of-Data
        const data = {!! json_encode($Teams) !!};
        const CloseTeamBanner = document.querySelector('.CloseTeamBanner');
        const divElement = document.querySelector('.Product-body');

        //--------------------------------Functions
        // Форма для закрытия всех форм
        function closeAllTeams() {
            const hiddenElements = document.querySelectorAll('.topmost-div[class*="Teams-id-"]');
            const AppContent = document.querySelector('.app-content');
            const CloseTeamBanner = document.querySelector('.CloseTeamBanner');
            const TopmostDivPluss = document.querySelector('.topmost-div-task-plus');
            const TopmostDivMinuss = document.querySelector('.topmost-div-task-minus');

            const minusButton = document.querySelector('.button-minus');
            const plusButton = document.querySelector('.button-plus');
            plusButton.style.display = 'block';
            minusButton.style.display = 'block';

            if (AppContent) {
                AppContent.style.filter = 'none';
            }
            if (CloseTeamBanner) {
                CloseTeamBanner.style.display = 'none';
            }
            hiddenElements.forEach(element => {
                element.style.display = 'none';
            });
            TopmostDivPluss.style.display = 'none';
            TopmostDivMinuss.style.display = 'none';
        }
        // Функция для создания формы команды
        function createTeamForm(team) {
            const formId = `Teams-id-${team.id}`;
            const formHtml = `
        <div class="topmost-div ${formId}" style="display: none;">
            <div style="text-align: center; color: white; height: 3vw;">
                <h1 class="TaskH1">{{ __('Change Teams') }} #${team.id}</h1>
                <div id="CloseBtn" class="btnclose" onclick="Teamid${team.id}close(); return false;">
                    <img class="closeicontask" src="{{ asset('media/icon/close.png') }}">
                </div>
            </div>
            <form action="/Admin/Teams/Change" method="POST" id="MyFormChange${team.id}" class="form" enctype="multipart/form-data">
                @csrf
            @method('PATCH')
            <div class="form_item"><div>Название</div>
                <input type="text" name="name" class="" required="" value="${team.name}">
                </div>
                <div class="form_item"><div>Кол-во игроков</div>
                    <input type="text" name="players" class="" value="${team.players}">
                </div>
                <div class="form_item"><div>Учебное заведение</div>
                    <input type="text" name="WhereFrom" class="" value="${team.wherefrom}">
                </div>
                <div class="form_item"><div>Пароль</div>
                    <input type="text" name="password" placeholder="Новый Пароль" class="">
                </div>
                <div class="form_item">
                    <div>Лого</div><div><img id="teamlogoimg${team.id}" class="teamlogoimg" style="height: 7vw;" src="/storage/teamlogo/${team.teamlogo}"></div>
                    <label for="images${team.id}" class="drop-container team-id${team.id}" id="dropcontainer">
                        <span class="drop-title">{{ __('Drop files here') }}</span>
                        <input type="file" name="file" id="images${team.id}">
                    </label>
                </div>
                <div class="form_item">Гостевая
                    <input type="checkbox" name="IsGuest" class="is_guest" ${team.guest === 'Yes' ? 'checked' : ''}>
                </div>
                <div class="form_item">Стандартный логотип
                    <input type="checkbox" name="standartlogo" class="is_guest" ${team.teamlogo}>
                </div>
                <input type="hidden" name="id" value="${team.id}">
                <div class="form_item">
                    <button class="btnchk" type="submit">
                        {{ __('Update') }}
            <svg width="79" height="46" viewBox="0 0 79 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#filter0_f_618_1123)">
                    <path d="M42.9 2H76.5L34.5 44H2L42.9 2Z" fill="url(#paint0_linear_618_1123)"/>
                </g>
                <defs>
                    <filter id="filter0_f_618_1123" x="0" y="0" width="78.5" height="46" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                        <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                        <feGaussianBlur stdDeviation="1" result="effect1_foregroundBlur_618_1123"/>
                    </filter>
                    <linearGradient id="paint0_linear_618_1123" x1="76.5" y1="2.00002" x2="34.5" y2="44" gradientUnits="userSpaceOnUse">
                        <stop stop-color="white" stop-opacity="0.6"/>
                        <stop offset="1" stop-color="white" stop-opacity="0.05"/>
                    </linearGradient>
                </defs>
            </svg>
        </button>
    </div>
</form>
<h1></h1>
</div>
`;

            const container = document.querySelector('.Teams-Container');
            container.insertAdjacentHTML('beforeend', formHtml);

            // Добавляем обработчики событий для новой формы
            document.getElementById(`MyFormChange${team.id}`).addEventListener('submit', async function(event) {
                event.preventDefault();
                await submitTeamFormAsync(this, team.id);
            });

            // Создаем функции для открытия/закрытия
            window[`Teamid${team.id}`] = function() {
                closeAllTeams(); // Закрываем все другие формы
                const div = document.querySelector(`.topmost-div.Teams-id-${team.id}`);
                const AppContent = document.querySelector('.app-content');
                const CloseTeamBanner = document.querySelector('.CloseTeamBanner');

                if (div) {
                    if (AppContent) AppContent.style.filter = 'blur(4px)';
                    if (CloseTeamBanner) CloseTeamBanner.style.display = 'block';
                    div.style.display = 'block';
                }
            };

            window[`Teamid${team.id}close`] = function() {
                const div = document.querySelector(`.topmost-div.Teams-id-${team.id}`);
                const AppContent = document.querySelector('.app-content');
                const CloseTeamBanner = document.querySelector('.CloseTeamBanner');

                if (div) {
                    if (AppContent) AppContent.style.filter = 'none';
                    if (CloseTeamBanner) CloseTeamBanner.style.display = 'none';
                    div.style.display = 'none';
                }
            };
        }
        // Функция для экранирования HTML
        function escapeHtml(unsafe) {
            return unsafe
                ? unsafe.toString()
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;")
                : '';
        }
        // Асинхронная отправка формы команды
        async function submitTeamFormAsync(form, teamId) {
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;

            try {
                // Показываем индикатор загрузки
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Обновление...';

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error('Сетевая ошибка: ' + response.statusText);
                }

                const data = await response.json();

                if (data.success) {
                    // Сохраняем данные формы перед любыми действиями
                    const formValues = Object.fromEntries(formData.entries());

                    showToast('success', 'Успех', data.message || 'Операция выполнена успешно', data.actions);
                } else {
                    showToast('error', 'Ошибка', data.message || 'Произошла ошибка');
                }

                return data;
            } catch (error) {
                console.error('Ошибка:', error);
                showToast('error', 'Ошибка', 'Произошла ошибка при отправке формы');
                throw error;
            } finally {
                // Восстанавливаем кнопку в исходное состояние
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        }
        // Функция копирования
        function copyToClipboard(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed'; // Чтобы не было видно мигания
            document.body.appendChild(textarea);
            textarea.select();

            try {
                document.execCommand('copy');
                showToast('info', 'Скопировано:', text);

                //console.log('Скопировано: ', text);
            } catch (err) {
                console.error();
                showToast('info', 'Успех', 'Не удалось скопировать: ' + err);
            } finally {
                document.body.removeChild(textarea);
            }
        }
        // Заполнение данных
        function MakeHTML(Data, Element) {
            const host = window.location.hostname;
            const protocol = window.location.protocol;
            const port = window.location.port;
            const url = protocol + '//' + host + ':' + port + '/storage/teamlogo/';
            const htmlheader = `<div class="products-header">
                <div class="product-cell image">
                    {{ __('Name')}}
            </div>
            <div class="product-cell category">{{ __('Players') }}</div>
                <div class="product-cell status-cell">{{ __('Where-From') }}</div>
                <div class="product-cell sales">{{ __('ID') }}</div>
                <div class="product-cell category">{{ __('Guest') }}</div>
            </div>`;
            const htmlbody = Data.map(item => `
           <div style="cursor: pointer" class="products-row teamlink" onclick="Teamid${item.id}()">
           <div class="product-cell id"><span class="cell-label">{{ __('ID') }}:</span>${item.id}</div>
    <div class="product-cell image">
        <img src="${url + item.teamlogo}" alt="product">
        <span>${item.name} ${item.guest !== 'No' ? '<div class="guest-badge">{{ __('GUEST') }}</div>': ''}</span>
    </div>
    <div class="product-cell category"><span class="cell-label">{{__('Players')}}:</span>${item.players}</div>
    <div class="product-cell status-cell">
        <span class="cell-label">{{ __('Where-From') }}:</span>
        <span class="status">${item.wherefrom}</span>
    </div>
    <div class="product-cell category token-cell">
        <span class="cell-label">{{ __('Token') }}:</span>
        <i class="fa fa-clone" aria-hidden="true"></i>
        <span class="token-value">${item.token}</span>
    </div>
</div>
        `).join("");
            const HTML = htmlbody;
            Element.innerHTML = HTML;
        }

        //--------------------------------Start-Functions
        // Инициализация существующих форм
        @foreach ($Teams as $T)
        createTeamForm({!! $T !!});
        @endforeach
        // Включение блоков
        OpenBlocks('teams');
        // Формирование данных на странице
        MakeHTML(data, divElement);

        //--------------------------------Other
        document.addEventListener('DOMContentLoaded', function() {
            // Обработчик нажатия клавиш
            document.addEventListener('keydown', function (event) {
                // Проверяем, нажата ли клавиша Esc (код 27)
                if (event.key === 'Escape' || event.keyCode === 27) {
                    closeAllTeams();
                }
            });
        });
        // Вешаем обработчик на все ячейки с токенами
        document.addEventListener('DOMContentLoaded', function() {
            const tokenCells = document.querySelectorAll('.token-cell');

            tokenCells.forEach(cell => {
                cell.addEventListener('click', function(event) {
                    event.stopPropagation(); // Останавливаем всплытие, чтобы не открылось модальное окно

                    const tokenSpan = cell.querySelector('.token-value');
                    const token = tokenSpan.textContent;

                    copyToClipboard(token);
                });
            });
        });

        //--------------------------------WebSocket
        Echo.private(`channel-admin-teams`).listen('AdminTeamsEvent', (e) => {
            const teams = e.teams;
            console.log(teams);

            // Находим текущую открытую форму (если есть)
            const openForm = document.querySelector('.topmost-div[class*="Teams-id-"][style*="display: block"]');
            let openFormId = null;
            if (openForm) {
                const classList = Array.from(openForm.classList);
                const teamClass = classList.find(cls => cls.startsWith('Teams-id-'));
                openFormId = teamClass ? teamClass.split('Teams-id-')[1] : null;
            }

            // Удаляем все существующие формы, кроме открытой (если есть)
            document.querySelectorAll('.topmost-div[class*="Teams-id-"]').forEach(el => {
                if (!openFormId || !el.classList.contains(`Teams-id-${openFormId}`)) {
                    el.remove();
                }
            });

            // Создаем формы для всех задач
            teams.forEach(team => {
                // Не создаем форму заново, если она уже есть и открыта
                if (!openFormId || team.id !== openFormId) {
                    createTeamForm(team);
                }
            });

            console.log('Принято!');
            MakeHTML(teams, divElement);
        });
    </script>
    <script>
        // Обработчики для всех дроп-контейнеров и изображений
        document.addEventListener('DOMContentLoaded', function() {
            // Делегирование событий для динамически созданных элементов
            document.addEventListener('dragover', function(e) {
                if (e.target.closest('.drop-container')) {
                    e.preventDefault();
                }
            }, false);

            document.addEventListener('dragenter', function(e) {
                const dropContainer = e.target.closest('.drop-container');
                if (dropContainer) {
                    dropContainer.classList.add("drag-active");
                }
            });

            document.addEventListener('dragleave', function(e) {
                const dropContainer = e.target.closest('.drop-container');
                if (dropContainer && !dropContainer.contains(e.relatedTarget)) {
                    dropContainer.classList.remove("drag-active");
                }
            });

            document.addEventListener('drop', function(e) {
                const dropContainer = e.target.closest('.drop-container');
                if (dropContainer) {
                    e.preventDefault();
                    dropContainer.classList.remove("drag-active");

                    // Получаем ID команды из класса контейнера
                    const teamId = dropContainer.className.match(/team-id(\d+)/)?.[1];
                    if (!teamId) return;

                    const fileInput = dropContainer.querySelector('input[type="file"]');
                    const img = document.getElementById(`teamlogoimg${teamId}`);

                    if (fileInput && img && e.dataTransfer.files.length) {
                        fileInput.files = e.dataTransfer.files;
                        previewImage(fileInput.files[0], img);
                    }
                }
            });

            // Обработчик изменения файла для всех инпутов
            document.addEventListener('change', function(e) {
                if (e.target.matches('input[type="file"]')) {
                    const teamId = e.target.closest('.drop-container').className.match(/team-id(\d+)/)?.[1];
                    if (!teamId) return;

                    const img = document.getElementById(`teamlogoimg${teamId}`);
                    if (img && e.target.files.length) {
                        previewImage(e.target.files[0], img);
                    }
                }
            });

            // Функция для предпросмотра изображения
            function previewImage(file, imgElement) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgElement.src = e.target.result;
                    imgElement.style = 'display: unset';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection

