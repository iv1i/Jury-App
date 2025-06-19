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
                <svg width="30px" height="30px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"
                     xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"
                     fill="#000000">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                    <g id="SVGRepo_iconCarrier"><title>plus-square</title>
                        <desc>Created with Sketch Beta.</desc>
                        <defs></defs>
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                           sketch:type="MSPage">
                            <g id="Icon-Set-Filled" sketch:type="MSLayerGroup"
                               transform="translate(-102.000000, -1037.000000)" fill="var(--app-bg-inv)">
                                <path
                                    d="M124,1054 L119,1054 L119,1059 C119,1059.55 118.552,1060 118,1060 C117.448,1060 117,1059.55 117,1059 L117,1054 L112,1054 C111.448,1054 111,1053.55 111,1053 C111,1052.45 111.448,1052 112,1052 L117,1052 L117,1047 C117,1046.45 117.448,1046 118,1046 C118.552,1046 119,1046.45 119,1047 L119,1052 L124,1052 C124.552,1052 125,1052.45 125,1053 C125,1053.55 124.552,1054 124,1054 L124,1054 Z M130,1037 L106,1037 C103.791,1037 102,1038.79 102,1041 L102,1065 C102,1067.21 103.791,1069 106,1069 L130,1069 C132.209,1069 134,1067.21 134,1065 L134,1041 C134,1038.79 132.209,1037 130,1037 L130,1037 Z"
                                    id="plus-square" sketch:type="MSShapeGroup"></path>
                            </g>
                        </g>
                    </g>
                </svg>
            </button>
            <button id="button-minus" class="button-minus">
                <svg width="30px" height="30px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"
                     xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"
                     fill="#000000">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                    <g id="SVGRepo_iconCarrier"><title>minus-square</title>
                        <desc>Created with Sketch Beta.</desc>
                        <defs></defs>
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                           sketch:type="MSPage">
                            <g id="Icon-Set-Filled" sketch:type="MSLayerGroup"
                               transform="translate(-154.000000, -1037.000000)" fill="var(--app-bg-inv)">
                                <path
                                    d="M176,1054 L164,1054 C163.448,1054 163,1053.55 163,1053 C163,1052.45 163.448,1052 164,1052 L176,1052 C176.552,1052 177,1052.45 177,1053 C177,1053.55 176.552,1054 176,1054 L176,1054 Z M182,1037 L158,1037 C155.791,1037 154,1038.79 154,1041 L154,1065 C154,1067.21 155.791,1069 158,1069 L182,1069 C184.209,1069 186,1067.21 186,1065 L186,1041 C186,1038.79 184.209,1037 182,1037 L182,1037 Z"
                                    id="minus-square" sketch:type="MSShapeGroup"></path>
                            </g>
                        </g>
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
                <div class="product-cell image">
                    {{ __('Name')}}
                </div>
                <div class="product-cell category">{{ __('Players') }}</div>
                <div class="product-cell status-cell">{{ __('Where-From') }}</div>
                <div class="product-cell sales">{{ __('ID') }}</div>
                <div class="product-cell category">{{ __('Guest') }}</div>
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
                <div>Токен</div>
                <input type="text" name="token" placeholder="минимум 6 символов" class="" required="" autocomplete="off">
            </div>
            <div class="form_item">
                <div>Лого</div>
                <div><img id="teamlogoimg" style="height: 7vw; display: none" src=""></div>
                <label for="images" class="drop-container" id="dropcontainer">
                    <span class="drop-title">{{ __('Drop files here') }}</span>
                    <input type="file" name="file" id="images" accept="image/jpeg, image/png" multiple>
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
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script type="text/javascript">
        const CloseTeamBanner = document.querySelector('.CloseTeamBanner');

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
        function createTeamsForm(team) {
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
                <div class="form_item"><div>Токен</div>
                    <input type="text" name="token" placeholder="{{ __('New Token') }}" class="">
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
                submitButton.innerHTML = 'Обновление...';

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

                    // Обновляем данные на странице без перезагрузки
                    updateTeamDataOnPage(teamId, formValues);

                    // Не закрываем форму автоматически
                    // window[`Teamid${teamId}close`]();

                } else {
                    // Сохраняем данные формы даже при ошибке
                    const formValues = Object.fromEntries(formData.entries());

                    showToast('error', 'Ошибка', data.message || 'Произошла ошибка');

                    // Обновляем данные на странице даже при ошибке
                    updateTeamDataOnPage(teamId, formValues);
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

        // Функция для обновления данных на странице
        function updateTeamDataOnPage(teamId, newData) {
            // Находим элемент команды в списке
            const teamElement = document.querySelector(`.teamlink[onclick="Teamid${teamId}()"]`);
        }

        // Инициализация существующих форм
        @foreach ($Teams as $T)
        createTeamsForm({!! $T !!});
        @endforeach

        // Обработчик событий для новых задач
        Echo.private(`channel-admin-teams`).listen('AdminTeamsEvent', (e) => {
            const teams = e.teams;
            console.log(teams);
            localStorage.setItem('DataAdmin', JSON.stringify(teams));

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
                    createTeamsForm(team);
                }
            });
        });

        let toastTimer1, toastTimer2;

        function showToast(type, title, message, actions = null) {
            const toast = document.querySelector(".toast");
            const toastContent = toast.querySelector(".toast-content");
            const checkIcon = toast.querySelector(".check");
            const messageText1 = toast.querySelector(".text-1");
            const messageText2 = toast.querySelector(".text-2");
            const messageText3 = toast.querySelector(".text-3");
            const progress = toast.querySelector(".progress");

            // Очищаем предыдущие таймеры
            clearTimeout(toastTimer1);
            clearTimeout(toastTimer2);

            // Сбрасываем анимацию прогресс-бара
            progress.classList.remove("active");
            // Принудительный рефлоу для сброса анимации
            void progress.offsetWidth;

            // Удаляем предыдущие добавленные стили
            const existingStyles = document.querySelectorAll('style[data-toast-style]');
            existingStyles.forEach(style => style.remove());

            toast.style.display = "flex";

            // Set icon and colors based on type
            if (type === 'success') {
                checkIcon.className = "fas fa-solid fa-check check";
                checkIcon.style.backgroundColor = "#40f443";
                const style = document.createElement('style');
                style.innerHTML = '.toast .progress:before { background-color: #40f443 !important; }';
                style.setAttribute('data-toast-style', 'true');
                document.head.appendChild(style);
            } else {
                checkIcon.className = "fas fa-solid fa-times check";
                checkIcon.style.backgroundColor = "#f4406a";
                const style = document.createElement('style');
                style.innerHTML = '.toast .progress:before { background-color: #f4406a !important; }';
                style.setAttribute('data-toast-style', 'true');
                document.head.appendChild(style);
            }

            messageText1.textContent = title;
            messageText2.textContent = message;

            // Очищаем предыдущие действия
            messageText3.innerHTML = '';

            // Добавляем действия с новой строки для каждого
            if (actions && actions.length > 0) {
                actions.forEach(action => {
                    const actionElement = document.createElement('div');
                    actionElement.textContent = `• ${action}`;
                    messageText3.appendChild(actionElement);
                });
            }

            toast.classList.add("active");

            // Запускаем анимацию прогресс-бара снова
            setTimeout(() => {
                progress.classList.add("active");
            }, 10);

            const closeIcon = document.querySelector('.close');

            if (toast) {
                toastTimer1 = setTimeout(() => {
                    toast.classList.remove('active');
                }, 5000);

                toastTimer2 = setTimeout(() => {
                    progress.classList.remove('active');
                }, 5300);

                if (closeIcon) {
                    closeIcon.removeEventListener('click', closeToast);
                    closeIcon.addEventListener('click', closeToast);
                }
            }
        }

        function closeToast() {
            const toast = document.querySelector(".toast");
            const progress = toast.querySelector(".progress");

            toast.classList.remove('active');

            setTimeout(() => {
                progress.classList.remove('active');
            }, 300);

            clearTimeout(toastTimer1);
            clearTimeout(toastTimer2);
        }

        document.getElementById('MyFormPlus').addEventListener('submit', async function (event) {
            event.preventDefault(); // предотвращаем стандартное поведение формы

            const formData = new FormData(this); // создаем объект FormData из формы
            const responseplus = await fetch('/Admin/Teams/Add', {
                method: 'POST',
                body: formData,
            })

            const data = await responseplus.json();

            if (responseplus.ok && data.success) {
                showToast('success', 'Успех', data.message || 'Операция выполнена успешно');
                return data;
            } else {
                showToast('error', 'Ошибка', data.message || 'Произошла ошибка');
                return data;
            }
        });

        document.getElementById('MyFormMinus').addEventListener('submit', async function (event) {
            event.preventDefault(); // предотвращаем стандартное поведение формы

            const formData = new FormData(this); // создаем объект FormData из формы

            const responseminus = await fetch('/Admin/Teams/Delete', {
                method: 'POST',
                body: formData,
            })


            const data = await responseminus.json();

            if (responseminus.ok && data.success) {
                showToast('success', 'Успех', data.message || 'Операция выполнена успешно');
                return data;
            } else {
                showToast('error', 'Ошибка', data.message || 'Произошла ошибка');
                return data;
            }
        });

        const blurButton = document.getElementById('CloseBtnPlus');
        blurButton.addEventListener('click', function() {
            const TopmostDiv = document.querySelector('.topmost-div-task-plus');
            const appContent = document.querySelector('.app-content');
            const minusButton = document.querySelector('.button-minus');
            const plusButton = document.querySelector('.button-plus');
            plusButton.style.display = 'block';
            minusButton.style.display = 'block';
            appContent.style.filter = 'none';
            TopmostDiv.style.display = 'none';
            CloseTeamBanner.style.display = 'none';
        });

        const blurButton2 = document.getElementById('CloseBtnMinus');
        blurButton2.addEventListener('click', function() {
            const TopmostDiv = document.querySelector('.topmost-div-task-minus');
            const appContent = document.querySelector('.app-content');
            const plusButton = document.querySelector('.button-plus');
            const minusButton = document.querySelector('.button-minus');
            minusButton.style.display = 'block';
            plusButton.style.display = 'block';
            appContent.style.filter = 'none';
            TopmostDiv.style.display = 'none';
            CloseTeamBanner.style.display = 'none';

        });

        const plusButton = document.getElementById('button-plus');
        plusButton.addEventListener('click', function() {
            const TopmostDiv = document.querySelector('.topmost-div-task-plus');
            const appContent = document.querySelector('.app-content');
            const minusButton = document.querySelector('.button-minus');
            const plusButton = document.querySelector('.button-plus');
            plusButton.style.display = 'none';
            minusButton.style.display = 'none';
            appContent.style.filter = 'blur(4px)';
            TopmostDiv.style.display = 'block';
            CloseTeamBanner.style.display = 'block';
        });

        const minusButton = document.getElementById('button-minus');
        minusButton.addEventListener('click', function() {
            const TopmostDiv = document.querySelector('.topmost-div-task-minus');
            const appContent = document.querySelector('.app-content');
            const plusButton = document.querySelector('.button-plus');
            const minusButton = document.querySelector('.button-minus');
            minusButton.style.display = 'none';
            plusButton.style.display = 'none';
            appContent.style.filter = 'blur(4px)';
            TopmostDiv.style.display = 'block';
            CloseTeamBanner.style.display = 'block';
        });

        const data = {!! json_encode($Teams) !!};

        //const sortedData = data.sort((a, b) => b.score - a.score);
        //console.log(sortedData);

        const divElement = document.querySelector('.Product-body');
        MakeHTML(data, divElement);

        Echo.private(`channel-admin-teams`).listen('AdminTeamsEvent', (e) => {
            const valueToDisplay = e.teams;
            console.log('Принято!');
            //const sortedData = valueToDisplay.sort((a, b) => b.score - a.score);
            //console.log(sortedData);
           MakeHTML(valueToDisplay, divElement);

        });

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
                <div class="product-cell image">
                <img src="${url + item.teamlogo}" alt="product">
                    <span>${item.name} ${item.guest !== 'No' ? '<div class="guest-badge">{{ __('GUEST') }}</div>': ''}</span>
                </div>
                <div class="product-cell category"><span class="cell-label">{{__('Players')}}:</span>${item.players}</div>
                <div class="product-cell status-cell">
                    <span class="cell-label">{{ __('Where-From') }}:</span>
                    <span class="status">${item.wherefrom}</span>
                </div>
                <div class="product-cell sales"><span class="cell-label">{{ __('ID') }}:</span>${item.id}</div>
                <div class="product-cell category"><span class="cell-label">{{ __('Guest') }}:</span>${item.guest}</div>
            </div>
        `).join("");
            const HTML = htmlbody;
            Element.innerHTML = HTML;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Обработчик нажатия клавиш
            document.addEventListener('keydown', function (event) {
                // Проверяем, нажата ли клавиша Esc (код 27)
                if (event.key === 'Escape' || event.keyCode === 27) {
                    closeAllTeams();
                }
            });
        });
    </script>
@endsection

