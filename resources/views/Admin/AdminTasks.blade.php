@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('style/css/Notif.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/DeleteButton.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/InputFile.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/AdminTasks.css') }}">
    <link rel="stylesheet" href="{{ asset('style/scss/buttoncheckflag.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/AdminTask.css') }}">
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
    <div class="Tasks-Container"></div>
    <div class="app-content">
        <div id="FormSwitchTheme" class="app-content-header">
            <h1 class="app-content-headerText">{{ __('Tasks') }}</h1>
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
            <div class="filter-button-wrapper">
                <button class="action-button filter jsFilter"><span>{{ __('Filter') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-filter">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                    </svg>
                </button>
                <div class="filter-menu">
                    <label>{{ __('Category') }}</label>
                    <select name="filter-menu-category" id="filter-menu-category">
                        <option value="All Categories">{{ __('All Categories') }}</option>
                        @foreach($categories as $category => $count)
                            <option>{{ $category }}</option>
                        @endforeach
                    </select>
                    <label>{{ __('Complexity') }}</label>
                    <select name="filter-menu-complexity" id="filter-menu-complexity">
                        <option value="All Complexity">{{ __('All Complexity') }}</option>
                        @foreach($complexities as $complexity => $count)
                            <option>{{ $complexity }}</option>
                        @endforeach
                    </select>
                    <div class="filter-menu-buttons">
                        <button class="filter-button reset" id="ResetBtn">
                            {{ __('Reset') }}
                        </button>
                        <button class="filter-button apply" id="ApplyBtn">
                            {{ __('Apply') }}
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
                <div class="product-cell id">{{ __('ID') }}</div>
                <div class="product-cell image">{{ __('Name') }}</div>
                <div class="product-cell category">{{ __('Category') }}</div>
                <div class="product-cell status-cell">{{ __('Complexity') }}</div>
                <div class="product-cell price">{{ __('Price') }}</div>
                <div class="product-cell action">{{ __('Action') }}</div>
            </div>
            <div class="Product-body">

            </div>
        </div>
        <div class="CloseTaskBanner" style="display: none; width: 100%; height: 100%; background-color: white; position:absolute; opacity: .0;" onclick="closeAllTasks()"></div>
    </div>
    <div style="display:none;" class="topmost-div-task-plus">
        <div style="text-align: center; color: white; height: 3vw;">
            <h1 class="TaskH1">{{ __('Add Task') }}</h1>
            <button id="CloseBtnPlus" class="btnclose"><img class="closeicontask" src="{{ asset('media/icon/close.png') }}">
            </button>
        </div>
        <form action="/Admin/Tasks/Add" method="POST" class="form" id="MyFormPlus">
            @csrf
            @method('PUT')
            <div class="form_item">
                <div>Название</div>
                <input name="name" class="" required="" autocomplete="off">
            </div>
            <div class="form_item ">
                <div>Категория</div>
                <select name="category" id="category">
                    @foreach($AllCategories as $count => $category)
                        <option>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form_item ">
                <div>Сложность</div>
                <select name="complexity" id="complexity">
                    @foreach($AllComplexities as $count => $complexity)
                        <option>{{ $complexity }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form_item ">
                <div>Очки</div>
                <input type="text" name="points" placeholder="1000" value="1000" class="" required="">
            </div>
            <div class="form_item">
                <div>Описание</div>
                <textarea name="description" class="task_text" required=""></textarea>
            </div>
            <label for="DropfilesPlus" class="drop-container" style="margin-top: 10px">
                <span class="drop-title">{{ __('Drop files here') }}</span>
                <input type="file" name="file[]" id="DropfilesPlus" multiple>
            </label>
            <div class="form_item ">
                <div>Флаг</div>
                <input type="text" name="flag" class="" required="" placeholder="school{}  flag{}" autocomplete="off">
            </div>

            <!-- Кнопка для раскрытия дополнительных полей -->
            <div class="form_item">
                <button type="button" id="toggleAdditionalFiles" class="additional-files-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <line x1="9" y1="15" x2="15" y2="15"></line>
                    </svg>
                    <span>{{ __('Additional files') }}</span>
                </button>
            </div>

            <!-- Дополнительные поля (изначально скрыты) -->
            <div id="additionalFilesSection" class="additional-files-section" style="display: none;">
                <div class="form_item">
                    <div>Web Application Port</div>
                    <input type="number" name="web_port" placeholder="e.g., 8080">
                </div>
                <div class="form_item">
                    <div>Database Port (if used)</div>
                    <input type="number" name="db_port" placeholder="e.g., 3306">
                </div>
                <div class="form_item">
                    <div>Source Code Archive</div>
                    <label for="sourcecode" class="drop-container">
                        <span class="drop-title">{{ __('Upload source code archive') }}</span>
                        <input type="file" name="sourcecode" id="sourcecode" accept=".zip,.tar,.gz">
                    </label>
                </div>
            </div>

            <div class="form_item">
                <button style="position: relative;" class="btnchk" type="submit">
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
    </div>
    <div style="display:none;" class="topmost-div-task-minus">
        <div style="text-align: center; color: white; height: 3vw;">
            <h1 class="TaskH1">{{ __('Delete Tasks') }}</h1>
            <button id="CloseBtnMinus" class="btnclose"><img class="closeicontask" src="{{ asset('media/icon/close.png') }}">
            </button>
        </div>
        <form method="POST" class="form" id="MyFormMinus" action="/Admin/Tasks/Delete">
            @csrf
            @method('DELETE')
            <div class="form_item">
                <div>{{ __('ID') }}</div>
                <input type="text" name="ID" class="" required="" autocomplete="off">
            </div>
            <div class="form_item">
                <button style="width: 20%;" class="btnchk" onClick={console.log("click")}
                        type="submit">
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
@endsection

@section('scripts')
    <script src="{{ asset('js/Other/Notifications.js') }}"></script>
    <script src="{{ asset('js/Admin/AdminOpenBlock.js') }}"></script>
    <script id="Main-Script-V4">
        //--------------------------------Init-Of-Data
        const data = {!! json_encode($Tasks) !!};
        const CloseTaskBanner = document.querySelector('.CloseTaskBanner');
        const divElement = document.querySelector('.Product-body');

        let taskcomplexity = localStorage.getItem('taskcomplexityAdmin');
        let taskcategory = localStorage.getItem('taskcategoryAdmin');

        //--------------------------------Functions
        // Форма для закрытия всех форм
        function closeAllTasks() {
            const hiddenElements = document.querySelectorAll('div[class*="Task-id-"]');
            const AppContent = document.querySelector('.app-content');
            const CloseTaskBanner = document.querySelector('.CloseTaskBanner');
            const TopmostDivPluss = document.querySelector('.topmost-div-task-plus');
            const TopmostDivMinuss = document.querySelector('.topmost-div-task-minus');

            const minusButton = document.querySelector('.button-minus');
            const plusButton = document.querySelector('.button-plus');
            plusButton.style.display = 'block';
            minusButton.style.display = 'block';

            if (AppContent) {
                AppContent.style.filter = 'none';
            }
            if (CloseTaskBanner) {
                CloseTaskBanner.style.display = 'none';
            }
            hiddenElements.forEach(element => {
                element.style.display = 'none';
            });
            TopmostDivPluss.style.display = 'none';
            TopmostDivMinuss.style.display = 'none';
        }
        // Функция для создания формы задачи
        function createTaskForm(task) {

            const AllCategories = @json($AllCategories);
            const AllComplexities = @json($AllComplexities);

            // Генерируем options для категорий
            const categoryOptions = AllCategories.map(category =>
                `<option ${task.category === category ? 'selected' : ''}>${category}</option>`
            ).join('');

            // Генерируем options для сложностей
            const complexityOptions = AllComplexities.map(complexity =>
                `<option ${task.complexity === complexity ? 'selected' : ''}>${complexity}</option>`
            ).join('');

            const formHtml = `
    <div style="display: none" class="topmost-div Task-id-${task.id}">
        <div style="text-align: center; color: white; height: 3vw;">
            <h1 class="TaskH1">{{ __('To Change Task') }} #${task.id}</h1>
            <div style="text-align: center; height: 3em;">
                <div id="CloseBtn" class="btnclosetask" onclick="Taskid${task.id}close()">
                    <img class="closeicontask" src="{{ asset('/media/icon/close.png') }}">
                </div>
            </div>
        </div>
        <form action="/Admin/Tasks/Change" method="POST" class="form" id="MyFormChange${task.id}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form_item">
                <div>Название</div>
                <input name="name" class="" required value="${task.name}">
            </div>
            <div class="form_item">
                <div>Категория</div>
                <select name="category" id="category">
                    ${categoryOptions}
                </select>
            </div>
            <div class="form_item">
                <div>Сложность</div>
                <select name="complexity" id="complexity">
                    ${complexityOptions}
                </select>
            </div>
            <div class="form_item">
                <div>Очки</div>
                <input type="text" name="points" placeholder="1000" value="${task.oldprice}" class="" required>
            </div>
            <div class="form_item">
                <div>Описание</div>
                <textarea name="description" class="task_text">${task.description || ''}</textarea>
            </div>
            <div class="form_item" style="display: flex">
                <label for="Dropfiles-Task-${task.id}" class="drop-container">
                    <span class="drop-title">
                        ${task.FILES ? '{{ __('Replace files') }}' : '{{ __('Add files') }}'}
                    </span>
                    <input type="file" name="file[]" id="Dropfiles-Task-${task.id}" multiple>
                </label>
                ${task.FILES ? `
                <div class="files-container" id="file-names">
                    <span class="files-title">{{ __('Saved files') }}:</span>
                    ${task.FILES.split(";").slice(0, 3).map(file => file ? `
                        <span style="font-size: 13px; color: #878b8e; display: flex">${file}</span>
                    ` : '').join('')}
                    ${task.FILES.split(";").length > 3 ? `
                        <span style="font-size: 13px; color: #878b8e; display: flex">
                            И еще ${task.FILES.split(";").length-4} ...
                        </span>
                    ` : ''}
                    ${task.FILES ? `
            <div class="DeleteFilesButton DeleteFilesButton-${task.id}" data-task-id="${task.id}">
                <svg class="DeleteFilesButtonSvg" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
        ` : ''}
                </div>
                ` : ''}
            </div>
            <div class="form_item">
                <div>Флаг</div>
                <input type="text" name="flag" class="" placeholder="school{} flag{}" value="${task.flag}">
            </div>
            <div class="form_item">
                <input type="hidden" name="id" value="${task.id}">
            </div>
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
    </form>
    </div>
    `;

            // Остальной код функции остается без изменений
            const container = document.querySelector('.Tasks-Container');
            container.insertAdjacentHTML('beforeend', formHtml);

            // Добавляем обработчики событий для новой формы
            document.getElementById(`MyFormChange${task.id}`).addEventListener('submit', async function(event) {
                event.preventDefault();
                await submitTaskFormAsync(this, task.id);
            });

            if (task.FILES) {
                document.querySelector(`.DeleteFilesButton-${task.id}`).addEventListener('click', async function() {
                    if (confirm('Вы уверены, что хотите удалить все файлы этой задачи?')) {
                        const form = document.getElementById(`MyFormChange${task.id}`);
                        const input = form.querySelector('input[type="hidden"][name="deleteFilesFromTask"]') || document.createElement('input');

                        input.type = 'hidden';
                        input.value = 'DELETEALL';
                        input.name = 'deleteFilesFromTask';
                        form.appendChild(input);

                        await submitTaskFormAsync(form, task.id);
                    }
                });
            }

            // Добавляем функции для открытия/закрытия
            window[`Taskid${task.id}`] = function() {
                const div = document.querySelector(`.topmost-div.Task-id-${task.id}`);
                const AppContent = document.querySelector('.app-content');

                if (div) {
                    if (AppContent) AppContent.style.filter = 'blur(4px)';
                    if (CloseTaskBanner) CloseTaskBanner.style.display = 'block';
                    div.style.display = 'block';
                }
            };

            window[`Taskid${task.id}close`] = function() {
                const div = document.querySelector(`.topmost-div.Task-id-${task.id}`);
                const AppContent = document.querySelector('.app-content');
                const CloseTaskBanner = document.querySelector('.CloseTaskBanner');

                if (div) {
                    if (AppContent) AppContent.style.filter = 'none';
                    if (CloseTaskBanner) CloseTaskBanner.style.display = 'none';
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
        // Асинхронная отправка формы
        async function submitTaskFormAsync(form, taskId) {
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
                    showToast('success', 'Успех', data.message || 'Операция выполнена успешно', data.actions);

                    // Обновляем данные в localStorage
                    const adminData = JSON.parse(localStorage.getItem('DataAdmin') || '[]');
                    const taskIndex = adminData.findIndex(t => t.id == taskId);

                    if (taskIndex !== -1) {
                        // Обновляем данные задачи
                        if (form.querySelector('input[name="deleteFilesFromTask"]')) {
                            adminData[taskIndex].FILES = null;
                        } else {
                            // Обновляем другие поля из формы
                            adminData[taskIndex].name = form.querySelector('input[name="name"]').value;
                            adminData[taskIndex].category = form.querySelector('select[name="category"]').value;
                            adminData[taskIndex].complexity = form.querySelector('select[name="complexity"]').value;
                            adminData[taskIndex].oldprice = form.querySelector('input[name="points"]').value;
                            adminData[taskIndex].description = form.querySelector('textarea[name="description"]').value;
                            adminData[taskIndex].flag = form.querySelector('input[name="flag"]').value;

                            // Если были загружены новые файлы, сервер должен вернуть их список в data.files
                            if (data.files) {
                                adminData[taskIndex].FILES = data.files.join(';');
                            }
                        }

                        localStorage.setItem('DataAdmin', JSON.stringify(adminData));

                        // Обновляем форму на странице без закрытия
                        const formContainer = document.querySelector(`.Task-id-${taskId}`);
                        if (formContainer) {
                            formContainer.remove(); // Удаляем старую форму
                            createTaskForm(adminData[taskIndex]); // Создаем новую с обновленными данными
                            window[`Taskid${taskId}`](); // Открываем форму после обновления
                        }
                    }
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
        // Функция фильтрации
        function Filtereed(DATA, taskcomplexity, taskcategory){
            if (taskcategory && taskcomplexity) {
                setSelection(taskcomplexity, taskcategory);
                let Data = DATA;
                if (taskcategory !== 'All Categories' && taskcomplexity !== 'All Complexity') {
                    Data = DATA.filter(item => item.complexity === taskcomplexity && item.category === taskcategory);
                }
                if (taskcategory !== 'All Categories' && taskcomplexity === 'All Complexity') {
                    Data = DATA.filter(item => item.category === taskcategory);
                }
                if (taskcategory === 'All Categories' && taskcomplexity !== 'All Complexity') {
                    Data = DATA.filter(item => item.complexity === taskcomplexity);
                }
                MakeHTML(Data, divElement);
            } else {
                MakeHTML(DATA, divElement);
            }
        }
        // Функция для работы с фильтром
        function setSelection(complx, categ) {
            let select = document.querySelector("select[name='filter-menu-complexity']");
            let select2 = document.querySelector("select[name='filter-menu-category']");

            if (complx == 'All Complexity') {
                select.selectedIndex = 0;
            }
            if (complx == 'easy') {
                select.selectedIndex = 1;
            }
            if (complx == 'medium') {
                select.selectedIndex = 2;
            }
            if (complx == 'hard') {
                select.selectedIndex = 3;
            }

            if (categ == 'All Categories') {
                select2.selectedIndex = 0;
            }
            if (categ == 'admin') {
                select2.selectedIndex = 1;
            }
            if (categ == 'recon') {
                select2.selectedIndex = 2;
            }
            if (categ == 'crypto') {
                select2.selectedIndex = 3;
            }
            if (categ == 'stegano') {
                select2.selectedIndex = 4;
            }
            if (categ == 'ppc') {
                select2.selectedIndex = 5;
            }
            if (categ == 'pwn') {
                select2.selectedIndex = 6;
            }
            if (categ == 'web') {
                select2.selectedIndex = 7;
            }
            if (categ == 'forensic') {
                select2.selectedIndex = 8;
            }
            if (categ == 'joy') {
                select2.selectedIndex = 9;
            }
            if (categ == 'misc') {
                select2.selectedIndex = 10;
            }
            if (categ == 'osint') {
                select2.selectedIndex = 11;
            }
            if (categ == 'reverse') {
                select2.selectedIndex = 12;
            }
        }
        // Заполнение данных
        function MakeHTML(Data, Element) {
            const htmlheader = `<div class="products-header">
                <div class="product-cell image">{{ __('Name') }}</div>
                <div class="product-cell category">{{ __('Category') }}</div>
                <div class="product-cell status-cell">{{ __('Complexity') }}</div>
                <div class="product-cell sales">{{ __('ID') }}</div>
                <div class="product-cell price">{{ __('Price') }}</div>
            </div>`;
            const htmlbody = Data.map(item => `
      <div style="cursor: pointer" class="products-row tasklink" onclick="Taskid${item.id}()">
        <div class="product-cell id">
            <span class="cell-label">{{ __('ID') }}:</span><span class="cell-value">${item.id}</span>
        </div>
        <div class="product-cell image">
            <span>${item.name}</span>
        </div>
        <div class="product-cell category">
            <span class="cell-label">{{ __('Category') }}:</span>${item.category.toUpperCase()}
        </div>
        <div class="product-cell status-cell">
            <span class="cell-label">{{ __('Complexity') }}:</span>
            <span class="status ${item.complexity}">${item.complexity.toUpperCase()}</span>
        </div>
        <div class="product-cell price">
            <span class="cell-label">{{ __('Price') }}:</span>${item.price}
        </div>
        <div class="product-cell action">
        <span class="cell-label">{{ __('Action') }}:</span>
            <button class="delete-task-btn" data-task-id="${item.id}" title="{{ __('Delete task') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18"></path>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </button>
        </div>
    </div>
    `).join("");

            const HTML = htmlbody;
            Element.innerHTML = HTML;

            // Добавляем обработчики событий для кнопок удаления
            document.querySelectorAll('.delete-task-btn').forEach(button => {
                button.addEventListener('click', async function(e) {
                    e.stopPropagation();
                    const taskId = this.getAttribute('data-task-id');

                    if (confirm('Вы уверены, что хотите удалить эту задачу?')) {
                        try {
                            const response = await fetch('/Admin/Tasks/Delete', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `_token=${encodeURIComponent(document.querySelector('input[name="_token"]').value)}&_method=DELETE&ID=${taskId}`
                            });

                            const data = await response.json();

                            if (response.ok && data.success) {
                                showToast('success', 'Успех', 'Задача успешно удалена');
                                this.closest('.products-row').remove();
                            } else {
                                showToast('error', 'Ошибка', data.message || 'Ошибка при удалении задачи');
                            }
                        } catch (error) {
                            console.error('Ошибка:', error);
                            showToast('error', 'Ошибка', 'Произошла ошибка при удалении задачи');
                        }
                    }
                });
            });
        }
        // Функция для обработки нажатия ESC
        function handleKeyDown(event) {
            if (event.key === 'Escape' || event.keyCode === 27) {
                closeAllTasks();
            }
        }
        // Функция для сохранения данных в localStorage
        function saveAdminData(data) {
            localStorage.setItem('DataAdmin', JSON.stringify(data));
        }
        // Функция для применения фильтров
        function applyFilters(divElement, data) {
            let taskcomplexity = document.getElementById('filter-menu-complexity').value;
            let taskcategory = document.getElementById('filter-menu-category').value;

            localStorage.setItem('taskcomplexityAdmin', taskcomplexity);
            localStorage.setItem('taskcategoryAdmin', taskcategory);

            if (taskcategory !== 'All Categories' && taskcomplexity !== 'All Complexity') {
                const data = JSON.parse(localStorage.getItem('DataAdmin'));
                const newArray = data.filter(item => item.complexity === taskcomplexity && item.category === taskcategory);
                MakeHTML(newArray, divElement);
            } else if (taskcategory !== 'All Categories' && taskcomplexity === 'All Complexity') {
                const data = JSON.parse(localStorage.getItem('DataAdmin'));
                const newArray = data.filter(item => item.category === taskcategory);
                MakeHTML(newArray, divElement);
            } else if (taskcategory === 'All Categories' && taskcomplexity !== 'All Complexity') {
                const data = JSON.parse(localStorage.getItem('DataAdmin'));
                const newArray = data.filter(item => item.complexity === taskcomplexity);
                MakeHTML(newArray, divElement);
            } else {
                MakeHTML(data, divElement);
            }
        }
        // Функция для сброса фильтров
        function resetFilters(divElement) {
            const data = JSON.parse(localStorage.getItem('DataAdmin'));
            let taskcomplexity = 'All Complexity';
            let taskcategory = 'All Categories';
            setSelection(taskcomplexity, taskcategory);
            localStorage.setItem('taskcomplexityAdmin', taskcomplexity);
            localStorage.setItem('taskcategoryAdmin', taskcategory);
            MakeHTML(data, divElement);
        }
        // Функция для переключения видимости дополнительных файлов
        function toggleAdditionalFiles() {
            const section = document.getElementById('additionalFilesSection');
            const isVisible = section.style.display === 'block';
            const svg = document.querySelector('#toggleAdditionalFiles svg');

            section.style.display = isVisible ? 'none' : 'block';
            svg.style.transform = isVisible ? 'rotate(0deg)' : 'rotate(45deg)';

            // Анимация иконки
            svg.animate([
                { transform: isVisible ? 'rotate(45deg)' : 'rotate(0deg)' },
                { transform: isVisible ? 'rotate(0deg)' : 'rotate(45deg)' }
            ], {
                duration: 300,
                easing: 'ease-in-out'
            });
        }
        // Функция для инициализации всех обработчиков событий
        function initializeAdminEventHandlers(divElement, initialData) {
            // Сохраняем начальные данные
            saveAdminData(initialData);

            // Назначаем обработчики
            document.getElementById('ApplyBtn').addEventListener('click', function() {
                applyFilters(divElement, initialData);
            });

            document.getElementById('ResetBtn').addEventListener('click', function() {
                resetFilters(divElement);
            });

            document.getElementById('toggleAdditionalFiles').addEventListener('click', toggleAdditionalFiles);

            // Инициализируем обработчик Esc при загрузке DOM
            document.addEventListener('keydown', handleKeyDown);
        }
        // Функция для инициализации сокетов
        function initializeEchoListener() {
            Echo.private(`channel-admin-tasks`).listen('AdminTasksEvent', (e) => {
                const tasks = e.tasks;
                localStorage.setItem('DataAdmin', JSON.stringify(tasks));

                // Удаляем все существующие формы
                document.querySelectorAll('.topmost-div[class*="Task-id-"]').forEach(el => el.remove());

                // Создаем формы для всех задач
                tasks.forEach(task => {
                    createTaskForm(task);
                });

                // Обновляем список задач
                let taskcomplexity = localStorage.getItem('taskcomplexityAdmin');
                let taskcategory = localStorage.getItem('taskcategoryAdmin');

                Filtereed(tasks, taskcomplexity, taskcategory);

                console.log('Принято!');

                Filtereed(tasks, taskcomplexity, taskcategory);
            });
        }

        //--------------------------------Start-Functions
        // Инициализация существующих форм
        @foreach ($Tasks as $T)
        createTaskForm({!! $T !!});
        @endforeach
        OpenBlocks('tasks');
        Filtereed(data, taskcomplexity, taskcategory);
        initializeAdminEventHandlers(divElement, data);

        //--------------------------------Other


        //--------------------------------WebSocket
        initializeEchoListener();
    </script>
@endsection
