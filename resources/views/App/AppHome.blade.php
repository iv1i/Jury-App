@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('style/css/Notif.css') }}">
    <link rel="stylesheet" href="{{ asset('style/scss/ChekFlag.css') }}">
    <link rel="stylesheet" href="{{ asset('style/scss/buttoncheckflag.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/HomeTask.css') }}">

    <script>
        {!! Vite::content('resources/js/app.js') !!}
    </script>
@endsection

@section('title', 'AltayCTF-Sch-Home')

@section('appcontent')
    <div class="notifications"></div>
    <div class="Tasks-Container">
        @foreach (\App\Models\Tasks::all() as $T)
            <div style="display: none" class="topmost-div Task-id-{{ $T->id }}">
                <div style="text-align: center; height: 3em;" ><h1 class="TaskH1">{{ $T->name }}</h1><div id="CloseBtn" class="btnclosetask" onclick="Taskid{{ $T->id }}close()"><img class="closeicontask" src="{{ asset('/media/icon/close.png') }}"></div>
                </div>
                <div class="description">
                    <div class="{{ $T->complexity }} taskID_complexity">{{ Str::of($T->complexity)->upper() }}</div>
                    {!! $T->description !!}
                </div>
                <div class="description">
                    @isset($T->FILES)
                        @foreach(explode(";", $T->FILES) as $k => $file)
                            @if($file)
                                <a href="{{ asset('/Download/File/' . md5($file)) }}{{ '/' . $T->id }}"> {{ 'Файл#' . $k+1 }}</a>
                            @endif
                        @endforeach
                    @endisset
                </div>
                <form id="MyFormPlus{{ $T->id }}" class="MyFormSellFlag" action="/Home/Tasks/Check" method="post">
                    @csrf
                    <div class="form__group field">
                        <input type="input" class="form__field" placeholder="Name" name="flag" id='name{{ $T->id }}' required autocomplete="off"/>
                        <label for="name{{ $T->id }}" class="form__label">school{...}</label>
                        <input type="hidden" name="ID" value="{{ $T->id }}">
                        <input type="hidden" name="complexity" value="{{ $T->complexity }}">
                    </div>
                    <div style="position: relative; left: 5%">
                        <button class="btnchk" onClick={console.log("click")} >
                            {{ __('Check') }}
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
            </div>
        @endforeach
    </div>
    <div class="app-content" style="filter: none;">
        <div class="app-content-header">
            <h1 class="app-content-headerText">{{ __('Home') }}</h1>
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
                    <select name="category" id="category">
                        <option value="All Categories">{{ __('All Categories') }}</option>
                        <option>admin</option>
                        <option>recon</option>
                        <option>crypto</option>
                        <option>stegano</option>
                        <option>ppc</option>
                        <option>pwn</option>
                        <option>web</option>
                        <option>forensic</option>
                        <option>joy</option>
                        <option>misc</option>
                        <option>osint</option>
                        <option>reverse</option>
                    </select>
                    <label>{{ __('Complexity') }}</label>
                    <select name="complexity" id="complexity">
                        <option value="All Complexity">{{ __('All Complexity') }}</option>
                        <option>easy</option>
                        <option>medium</option>
                        <option>hard</option>
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
                    <div class="product-cell image">{{ __('Name') }}<button class="sort-button sort-button-Name">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                        </button></div>
                    <div class="product-cell category">{{ __('Category') }}<button class="sort-button sort-button-Category">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                        </button></div>
                    <div class="product-cell complexity">{{ __('Complexity') }}<button class="sort-button sort-button-Complexity">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                        </button></div>
                    <div class="product-cell solved">{{ __('Solved') }}<button class="sort-button sort-button-Solved">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                        </button></div>
                    <div class="product-cell price">{{ __('Price') }}<button class="sort-button sort-button-Price">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                        </button></div>
            </div>
            <div class="Product-body"></div>
        </div>
        <div class="CloseTaskBanner" style="display: none; width: 100%; height: 100%; background-color: white; position:absolute; opacity: .0;" onclick="closeAllTasks()"></div>
    </div>
@endsection

@section('scripts')
    <script id="TasksBlock-Script">
        function closeAllTasks(){
            // Получаем все элементы с классом, содержащим 'Task-id-'
            const hiddenElements = document.querySelectorAll('div[class*="Task-id-"]');
            const AppContent = document.querySelector(`.app-content`);
            const CloseTaskBanner = document.querySelector(`.CloseTaskBanner`);
            if (AppContent) {
                AppContent.style.filter = 'none'; // Показываем элемент
            }
            if (CloseTaskBanner) {
                CloseTaskBanner.style.display = 'none'; // Показываем элемент
            }
// Проходим по каждому элементу и изменяем стиль
            hiddenElements.forEach(element => {
                element.style.display = 'none'; // или 'flex', в зависимости от ваших нужд
            });
        }
        @foreach (\App\Models\Tasks::all() as $T)
        function Taskid{{ $T->id }}() {
            // Находим элемент с классом 'topmost-div Task-id-4'
            const div = document.querySelector('.topmost-div.Task-id-{{ $T->id }}');
            const AppContent = document.querySelector(`.app-content`);
            const CloseTaskBanner = document.querySelector(`.CloseTaskBanner`);

            // Проверяем, существует ли элемент
            if (div) {
                // Удаляем стиль display: none
                if (AppContent) {
                    AppContent.style.filter = 'blur(4px)'; // Показываем элемент
                }
                if (CloseTaskBanner) {
                    CloseTaskBanner.style.display = 'block'; // Показываем элемент
                }
                div.style.display = 'block';
            } else {
                console.log('Элемент не найден');
            }
        }

        function Taskid{{ $T->id }}close() {
            // Находим элемент с классом 'topmost-div Task-id-4'
            const div = document.querySelector('.topmost-div.Task-id-{{ $T->id }}');
            const AppContent = document.querySelector(`.app-content`);
            const CloseTaskBanner = document.querySelector(`.CloseTaskBanner`);

            // Проверяем, существует ли элемент
            if (div) {
                // Удаляем стиль display: none
                if (AppContent) {
                    AppContent.style.filter = 'none'; // Показываем элемент
                }
                if (CloseTaskBanner) {
                    CloseTaskBanner.style.display = 'none'; // Показываем элемент
                }
                div.style.display = 'none';
            } else {
                console.log('Элемент не найден');
            }
        }

        document.getElementById('MyFormPlus{{ $T->id }}').addEventListener('submit', async function(event) {
            event.preventDefault(); // предотвращаем стандартное поведение формы

            const formData = new FormData(this); // создаем объект FormData из формы

            try {
                const response = await fetch('/Home/Tasks/Check', {
                    method: 'POST',
                    body: formData,
                });

                // Проверяем, успешен ли ответ
                if (!response.ok) {
                    throw new Error('Сетевая ошибка: ' + response.statusText);
                }

                const data = await response.json(); // предполагаем, что сервер возвращает JSON
                console.log('Успех:', data); // обрабатываем успешный ответ от сервера

                // Здесь вы можете обновить интерфейс или выполнить другие действия после успешной отправки формы

            } catch (error) {
                console.error('Ошибка:', error); // обрабатываем ошибки
            }
        });
        @endforeach

    </script>
    <script id="TasksID_Notification" type="text/javascript">
        const divElement11 = document.querySelector('.notifications');
        const Id = {{ auth()->id() }};
        Echo.private(`channel-app-checktask.${Id}`).listen('AppCheckTaskEvent', (e) => {

            const Notification = e.data;
            //console.log(Notification);
            console.log('Принято!');

            const html0 = `<div class="toast active">

                <div  class="toast-content">
                    <i style="background-color: ${Notification.color}" class="fas fa-solid fa-check check"></i>

                    <div class="message">
                        <span class="text text-1">${Notification.message}</span>
                        <span class="text text-2">${Notification.text}</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close">
                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Menu / Close_SM"> <path id="Vector" d="M16 16L12 12M12 12L8 8M12 12L16 8M12 12L8 16" stroke="#77767b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g> </g></svg>
                </i>
                <style>
                    .progress.active:before {
                        background-color: ${Notification.color};
                    }
                </style>
                <!-- Remove 'active' class, this is just to show in Codepen thumbnail -->
                <div  class="progress active"></div>
            </div>`;
            const HtMl = html0;
            const userAgent = navigator.userAgent;
            if (userAgent === Notification.userAgent) {
                divElement11.style.display = "";
                divElement11.innerHTML = HtMl;
                const toast = document.querySelector(".toast");
                (closeIcon = document.querySelector(".close")),
                    (progress = document.querySelector(".progress"));

                let timer1, timer2;

                timer1 = setTimeout(() => {
                    toast.classList.remove("active");

                }, 5000); //1s = 1000 milliseconds

                timer2 = setTimeout(() => {
                    //progress.classList.remove("active");
                    //progress.style.display = "none";
                }, 5300);

                closeIcon.addEventListener("click", () => {
                    toast.classList.remove("active");

                    setTimeout(() => {
                        progress.classList.remove("active");
                        //divElement11.style.display = "none";
                    }, 300);

                    clearTimeout(timer1);
                    clearTimeout(timer2);
                });
            }
        });

    </script>
    <script id="MAIN-JS-SCRIPT" type="text/javascript">
        const teamid = {{ auth()->id() }};
        const data = {!! json_encode(\App\Models\Tasks::all()) !!};
        const solvedtasks = {!! json_encode(\App\Models\User::find(auth()->id())->solvedTasks) !!};
        var SortedItemsColumn = data;

        for (let i = 0; i < data.length; i++) {
            data[i].decide = '';
        }
        for (let i = 0; i < data.length; i++) {
            //console.log(data[i].id);
            for (let j = 0; j < solvedtasks.length; j++) {
                if (solvedtasks[j].tasks_id === data[i].id) {
                    data[i].decide = `style="color: var(--app-bg-tasks);"`;
                    //console.log(data[i].decide);
                    //data.splice(i, 1);
                }
            }
        }
        data.sort((a, b) => {
            const complexityOrder = {easy: 1, medium: 2, hard: 3};
            return complexityOrder[a.complexity] - complexityOrder[b.complexity];
        });
        data.sort((a, b) => {
            //console.log(a.decide);
            // Если a.decide пустой, он должен быть перед b.decide
            if (a.decide === '' && b.decide !== '') {
                return -1; // a перед b
            }
            if (a.decide !== '' && b.decide === '') {
                return 1; // b перед a
            }
            return 0; // оставляем порядок неизменным
        });
        const divElement = document.querySelector('.Product-body');

        localStorage.setItem('data', JSON.stringify(data));

        let taskcomplexity = localStorage.getItem('taskcomplexity');
        let taskcategory = localStorage.getItem('taskcategory');
        let SortedTasksCol = JSON.parse(localStorage.getItem('SortingTasksColumn'));
        if (SortedTasksCol) {
            const { N: field, isSort: order } = SortedTasksCol;

            const createComparator = (field, order) => {
                const isReverseBase = field === 'solved';
                const baseDirection = isReverseBase ? -1 : 1;
                const direction = baseDirection * (order === 1 ? 1 : -1);

                return (a, b) => {
                    const aHasDecide = a.decide?.trim() !== '';
                    const bHasDecide = b.decide?.trim() !== '';

                    // Если у одного из элементов есть decide, а у другого нет,
                    // элемент с decide всегда будет в конце
                    if (aHasDecide !== bHasDecide) {
                        return aHasDecide ? 1 : -1;
                    }

                    // Если оба элемента имеют или не имеют decide, сортируем по указанному полю
                    const getValue = (obj) => {
                        const value = obj[field];
                        return ['solved', 'price'].includes(field) ? Number(value) : value?.toLowerCase();
                    };

                    const aValue = getValue(a);
                    const bValue = getValue(b);

                    return aValue < bValue ? -direction : aValue > bValue ? direction : 0;
                };
            };

            const validFields = ['name', 'category', 'complexity', 'solved', 'price'];
            if (validFields.includes(field)) {
                data.sort(createComparator(field, order));
            }
            if (field === 'complexity') {
                if (order === 1)
                    data.sort((a, b) => {
                        const aHasDecide = a.decide?.trim() !== '';
                        const bHasDecide = b.decide?.trim() !== '';

                        if (aHasDecide !== bHasDecide) {
                            return aHasDecide ? 1 : -1;
                        }

                        const complexityOrder = { easy: 1, medium: 2, hard: 3 };
                        return complexityOrder[a.complexity] - complexityOrder[b.complexity];
                    });
                if (order === 2)
                    data.sort((a, b) => {
                        const aHasDecide = a.decide?.trim() !== '';
                        const bHasDecide = b.decide?.trim() !== '';

                        if (aHasDecide !== bHasDecide) {
                            return aHasDecide ? 1 : -1;
                        }

                        const complexityOrder = { easy: 3, medium: 2, hard: 1 };
                        return complexityOrder[a.complexity] - complexityOrder[b.complexity];
                    });
            }
        }

        Filtereed(data, taskcomplexity, taskcategory);
        Echo.private(`channel-app-home`).listen('AppHomeEvent', (e) => {
            const valueToDisplay = e.tasks;
            let Tasks = valueToDisplay.Tasks;
            let SolvedTaasks = valueToDisplay.SolvedTasks;

            let SolvedTasksOnThisAuthUser = [];
            for (let i = 0; i < SolvedTaasks.length; i++) {
                if (SolvedTaasks[i].user_id === teamid) {
                    SolvedTasksOnThisAuthUser.push(valueToDisplay.SolvedTasks[i]);
                }
            }

            //console.log(SolvedTasksOnThisAuthUser);
            let Solvedtasks = [];
            for (let i = 0; i < Tasks.length; i++) {
                for (let j = 0; j < SolvedTasksOnThisAuthUser.length; j++) {
                    if (SolvedTasksOnThisAuthUser[j].tasks_id === Tasks[i].id) {
                        Solvedtasks.push(Tasks[i]);
                    }
                }
            }

            for (let i = 0; i < Tasks.length; i++) {
                Tasks[i].decide = '';
            }

            for (let i = 0; i < Tasks.length; i++) {
                //console.log(data[i].id);
                for (let j = 0; j < SolvedTasksOnThisAuthUser.length; j++) {
                    if (SolvedTasksOnThisAuthUser[j].tasks_id === Tasks[i].id) {
                        Tasks[i].decide = `style="color: #2c394f;filter: blur(0.7px);"`;
                        //data.splice(i, 1);
                    }
                }
            }

            Tasks.sort((a, b) => {
                const complexityOrder = {easy: 1, medium: 2, hard: 3};
                return complexityOrder[a.complexity] - complexityOrder[b.complexity];
            });

            Tasks.sort((a, b) => {
                //console.log(a.decide);
                // Если a.decide пустой, он должен быть перед b.decide
                if (a.decide === '' && b.decide !== '') {
                    return -1; // a перед b
                }
                if (a.decide !== '' && b.decide === '') {
                    return 1; // b перед a
                }
                return 0; // оставляем порядок неизменным
            });

            //console.log(Tasks);
            //console.log(Solvedtasks);

            localStorage.setItem('data', JSON.stringify(Tasks));
            console.log('Принято!');

            //const sortedData = valueToDisplay.sort((a, b) => b.score - a.score);
            //console.log(sortedData);

            let taskcomplexity = localStorage.getItem('taskcomplexity');
            let taskcategory = localStorage.getItem('taskcategory');

            let SortedTasksColEcho = JSON.parse(localStorage.getItem('SortingTasksColumn'));
            if (SortedTasksColEcho) {
                const { N: field, isSort: order } = SortedTasksColEcho;

                const createComparator = (field, order) => {
                    const isReverseBase = field === 'solved';
                    const baseDirection = isReverseBase ? -1 : 1;
                    const direction = baseDirection * (order === 1 ? 1 : -1);

                    return (a, b) => {
                        const aHasDecide = a.decide?.trim() !== '';
                        const bHasDecide = b.decide?.trim() !== '';

                        if (aHasDecide !== bHasDecide) {
                            return aHasDecide ? 1 : -1;
                        }

                        const getValue = (obj) => {
                            const value = obj[field];
                            return ['solved', 'price'].includes(field) ? Number(value) : value?.toLowerCase();
                        };

                        const aValue = getValue(a);
                        const bValue = getValue(b);

                        return aValue < bValue ? -direction : aValue > bValue ? direction : 0;
                    };
                };

                const validFields = ['name', 'category', 'complexity', 'solved', 'price'];
                if (validFields.includes(field)) {
                    Tasks.sort(createComparator(field, order));
                }
                if(field === 'complexity'){
                    if(order === 1)
                        Tasks.sort((a, b) => {
                            const complexityOrder = {easy: 1, medium: 2, hard: 3};
                            return complexityOrder[a.complexity] - complexityOrder[b.complexity];
                        });
                    if (order === 2)
                        Tasks.sort((a, b) => {
                            const complexityOrder = {easy: 3, medium: 2, hard: 1};
                            return complexityOrder[a.complexity] - complexityOrder[b.complexity];
                        });
                }
            }

            Filtereed(Tasks, taskcomplexity, taskcategory);
            //console.log(e.test);
        });

        document.getElementById('ApplyBtn').addEventListener('click', function () {
            let taskcomplexity = document.getElementById('complexity').value;
            let taskcategory = document.getElementById('category').value;

            localStorage.setItem('taskcomplexity', taskcomplexity);
            localStorage.setItem('taskcategory', taskcategory);

            if (taskcategory !== 'All Categories' && taskcomplexity !== 'All Complexity') {
                const data = JSON.parse(localStorage.getItem('data'));
                const newArray = data.filter(item => item.complexity === taskcomplexity && item.category === taskcategory);
                MakeHTML(newArray, divElement);
            } else {
                MakeHTML(data, divElement);
            }
            if (taskcategory !== 'All Categories' && taskcomplexity === 'All Complexity') {
                const data = JSON.parse(localStorage.getItem('data'));
                const newArray = data.filter(item => item.category === taskcategory);
                MakeHTML(newArray, divElement);
            }
            if (taskcategory === 'All Categories' && taskcomplexity !== 'All Complexity') {
                const data = JSON.parse(localStorage.getItem('data'));
                const newArray = data.filter(item => item.complexity === taskcomplexity);
                MakeHTML(newArray, divElement);
            }
        });
        document.getElementById('ResetBtn').addEventListener('click', function () {
            const data = JSON.parse(localStorage.getItem('data'));
            let taskcomplexity = 'All Complexity';
            let taskcategory = 'All Categories';
            setSelection(taskcomplexity, taskcategory);
            localStorage.setItem('taskcomplexity', taskcomplexity);
            localStorage.setItem('taskcategory', taskcategory);
            MakeHTML(data, divElement);
        });

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

        function MakeHTML(Data, Element) {
            const html0 = `<div class="products-header">
                <div class="product-cell image">{{ __('Name') }}<button class="sort-button sort-button-Name">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
                <div class="product-cell category">{{ __('Category') }}<button class="sort-button sort-button-Category">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
                <div class="product-cell complexity">{{ __('Complexity') }}<button class="sort-button sort-button-Complexity">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
                <div class="product-cell solved">{{ __('Solved') }}<button class="sort-button sort-button-Solved">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
                <div class="product-cell price">{{ __('Price') }}<button class="sort-button sort-button-Price">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
            </div>`;
            const html1 = Data.map(item => `
            <div style="cursor: pointer" href="/Home/${item.id}" class="products-row tasklink" onclick="Taskid${item.id}()">
                <div class="product-cell image" ${item.decide}>
                    <span>${item.name}</span>
                </div>
                <div class="product-cell category" ${item.decide}><span class="cell-label">{{ __('Category') }}:</span>${item.category.toUpperCase()}</div>
                <div class="product-cell complexity" ${item.decide}>
                    <span class="cell-label">{{ __('Complexity') }}:</span>
                    <span class="status ${item.decide}${item.complexity}" >${item.complexity.toUpperCase()}</span>
                </div>
                <div class="product-cell solved" ${item.decide}><span class="cell-label">{{ __('Solved') }}:</span>${item.solved}</div>
                <div class="product-cell price" ${item.decide}><span class="cell-label">{{ __('Price') }}:</span>${item.price}</div>
            </div>
        `).join("");

            const htmlTasks = Data.map(item => `
           <div style="display: none" class="topmost-div Task-id-${item.id}">
                <div style="text-align: center; height: 3em;" ><h1 class="TaskH1">${item.name}</h1><div id="CloseBtn" class="btnclosetask" onclick="Taskid${item.id}close()"><img class="closeicontask" src="{{ asset('/media/icon/close.png') }}"></div>
                </div>
                <div class="description">
                    <div class="${item.complexity} taskID_complexity">${item.complexity}</div>
                    ${item.description}
            </div>
            <div class="description">
            @isset($T->FILES)
            @foreach(explode(";", $T->FILES) as $k => $file)
            @if($file)
            <a href="{{ asset('/Download/File/' . md5($file)) }}{{ '/' . $T->id }}"> {{ 'Файл#' . $k+1 }}</a>
                            @endif
            @endforeach
            @endisset
            </div>
            <form id="MyFormPlus${item.id}" class="MyFormSellFlag" action="/Home/Tasks/Check" method="post">
                    @csrf
            <div class="form__group field">
                <input type="input" class="form__field" placeholder="Name" name="flag" id='name${item.id}' required autocomplete="off"/>
                        <label for="name${item.id}" class="form__label">school{...}</label>
                        <input type="hidden" name="ID" value="${item.id}">
                        <input type="hidden" name="complexity" value="${item.complexity}">
                    </div>
                    <div style="position: relative; left: 5%">
                        <button class="btnchk" onClick={console.log("click")} >
                            {{ __('Check') }}
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
</div>
        `).join("");
            const HTML = html1;
            Element.innerHTML = HTML;
        }

        function getCookie(name) {
            const cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                const [key, value] = cookies[i].trim().split('=');
                if (key === name) {
                    return decodeURIComponent(value);
                }
            }
            return null;
        }

        function setCookie(name, value, days, sameSite, path) {
            const expires = days ? `; expires=${new Date(Date.now() + days * 86400000).toUTCString()}` : '';
            const sameSiteAttribute = sameSite ? `; SameSite=${sameSite}` : '';
            const cookiePath = path ? `; path=${path}` : '';
            document.cookie = `${name}=${encodeURIComponent(value)}${expires}${sameSiteAttribute}${cookiePath}`;
        }

        function setSelection(complx, categ) {
            let select = document.querySelector("select[name='complexity']");
            let select2 = document.querySelector("select[name='category']");

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

        localStorage.removeItem('DataAdmin');
        localStorage.removeItem('taskcategoryAdmin');
        localStorage.removeItem('taskcomplexityAdmin');
    </script>
    {{--<script id="Main-Sort">
        const divElementSort = document.querySelector('.Product-body');
        const sortButtons = {
            'name': '.sort-button-Name',
            'category': '.sort-button-Category',
            'complexity': '.sort-button-Complexity',
            'solved': '.sort-button-Solved',
            'price': '.sort-button-Price'
        };

        const complexityOrder = { easy: 1, medium: 2, hard: 3 };
        let sortStates = { name: 0, category: 0, complexity: 0, solved: 0, price: 0 };
        let originalTeams = [...data];
        let currentSort = { column: null, direction: 0 };

        // Инициализация состояния из localStorage
        const storedSort = JSON.parse(localStorage.getItem('SortingTasksColumn'));
        if (storedSort) {
            sortStates[storedSort.N] = storedSort.isSort;
            currentSort = { column: storedSort.N, direction: storedSort.isSort };
        }

        // Первоначальная сортировка
        data.sort((a, b) => complexityOrder[a.complexity] - complexityOrder[b.complexity]);
        data.sort(sortByDecide);

        // Обработчики событий
        Object.entries(sortButtons).forEach(([column, selector]) => {
            document.querySelector(selector).addEventListener('click', () => handleSort(column));
        });

        function handleSort(column) {
            if(localStorage.getItem('taskcomplexity') && localStorage.getItem('taskcategory')) {
                if (localStorage.getItem('taskcategory') !== 'All Categories' || localStorage.getItem('taskcomplexity') !== 'All Complexity') {
                    return null;
                }
            }
            currentSort.direction = currentSort.column === column ? ++currentSort.direction : 1;
            currentSort.column = column;

            // Сброс других сортировок
            Object.keys(sortStates).forEach(k => sortStates[k] = k === column ? currentSort.direction : 0);


            // Обновление данных и интерфейса
            const sortedData = getSortedData(column, currentSort.direction);
            updateUI(sortedData);

            // Сохранение состояния
            let sortState = { N: column, isSort: currentSort.direction };
            localStorage.setItem('SortingTasksColumn', JSON.stringify(sortState));

            if (currentSort.direction === 3) {
                localStorage.removeItem('SortingTasksColumn');
                currentSort.direction = 0;
            }
        }
        function getSortedData(column, direction) {
            if (direction === 3) return JSON.parse(localStorage.getItem('data'));

            return direction === 1
                ? [...data].sort((a, b) => customSort(a, b, column, 'asc'))
                : [...data].sort((a, b) => customSort(a, b, column, 'desc'));
        }
        function customSort(a, b, column, dir) {
            // Приоритет для элементов с decide
            if (a.decide && !b.decide) return 1;
            if (!a.decide && b.decide) return -1;

            // Определение направления
            const modifier = dir === 'asc' ? 1 : -1;
            const valA = getSortValue(a, column);
            const valB = getSortValue(b, column);

            return (valA > valB ? 1 : -1) * modifier;
        }

        function getSortValue(item, column) {
            switch(column) {
                case 'complexity': return complexityOrder[item.complexity];
                case 'solved': return Number(item.solved);
                case 'price': return Number(item.price);
                default: return item[column].toLowerCase();
            }
        }

        function sortByDecide(a, b) {
            if (a.decide === '' && b.decide !== '') return -1;
            if (a.decide !== '' && b.decide === '') return 1;
            return 0;
        }

        function updateUI(data) {
            divElementSort.innerHTML = data.map(item => `
            <div href="/Home/${item.id}" class="products-row tasklink" onclick="Taskid${item.id}()">
                <div class="product-cell image" ${item.decide}>
                    <span>${item.name}</span>
                </div>
                <div class="product-cell category" ${item.decide}>
                    <span class="cell-label">{{ __('Category') }}:</span>${item.category.toUpperCase()}
                </div>
                <div class="product-cell complexity" ${item.decide}>
                    <span class="cell-label">{{ __('Complexity') }}:</span>
                    <span class="status ${item.decide}${item.complexity}">${item.complexity.toUpperCase()}</span>
                </div>
                <div class="product-cell solved" ${item.decide}>
                    <span class="cell-label">{{ __('Solved') }}:</span>${item.solved}
                </div>
                <div class="product-cell price" ${item.decide}>
                    <span class="cell-label">{{ __('Price') }}:</span>${item.price}
                </div>
            </div>
        `).join('');
        }

    </script>--}}

    <script id="Main-Sort">
        let TASKS = JSON.parse(localStorage.getItem('data'));
        const divElementSort = document.querySelector('.Product-body');
        const sortButtons = {
            'name': '.sort-button-Name',
            'category': '.sort-button-Category',
            'complexity': '.sort-button-Complexity',
            'solved': '.sort-button-Solved',
            'price': '.sort-button-Price'
        };

        const complexityOrder = { easy: 1, medium: 2, hard: 3 };
        let sortStates = { name: 0, category: 0, complexity: 0, solved: 0, price: 0 };
        let originalTeams = [...data];
        let currentSort = { column: null, direction: 0 };

        // Инициализация состояния из localStorage
        const storedSort = JSON.parse(localStorage.getItem('SortingTasksColumn'));
        if (storedSort) {
            sortStates[storedSort.N] = storedSort.isSort;
            currentSort = { column: storedSort.N, direction: storedSort.isSort };
        }

        // Первоначальная сортировка
        TASKS.sort((a, b) => complexityOrder[a.complexity] - complexityOrder[b.complexity]);
        TASKS.sort(sortByDecide);

        // Обработчики событий
        Object.entries(sortButtons).forEach(([column, selector]) => {
            document.querySelector(selector).addEventListener('click', () => handleSort(column));
        });

        function handleSort(column) {
            if(localStorage.getItem('taskcomplexity') && localStorage.getItem('taskcategory')) {
                if (localStorage.getItem('taskcategory') !== 'All Categories' || localStorage.getItem('taskcomplexity') !== 'All Complexity') {
                    return null;
                }
            }

            // Особенная логика для complexity
            if (column === 'complexity') {
                if (currentSort.column === column) {
                    currentSort.direction = currentSort.direction === 1 ? 2 : 1;
                } else {
                    currentSort.direction = 1; // Начальное состояние при переключении с другой колонки
                }
            } else {
                currentSort.direction = currentSort.column === column ? (currentSort.direction + 1) % 3 : 1;
            }

            currentSort.column = column;

            // Сброс других сортировок
            Object.keys(sortStates).forEach(k => sortStates[k] = k === column ? currentSort.direction : 0);

            // Обновление данных и интерфейса
            const sortedData = getSortedData(column, currentSort.direction);
            updateUI(sortedData);

            // Сохранение состояния (не сохраняем состояние 0 для complexity)
            if (currentSort.direction !== 0) {
                let sortState = { N: column, isSort: currentSort.direction };
                localStorage.setItem('SortingTasksColumn', JSON.stringify(sortState));
            }

            // Сброс состояния при достижении 3 (только для других колонок)
            if (column !== 'complexity' && currentSort.direction === 0) {
                localStorage.removeItem('SortingTasksColumn');
            }
        }

        function getSortedData(column, direction) {
            TASKS = JSON.parse(localStorage.getItem('data'));
            // Для complexity игнорируем состояние 0
            if (column !== 'complexity' && direction === 0) return JSON.parse(localStorage.getItem('data'));

            return direction === 1
                ? [...TASKS].sort((a, b) => customSort(a, b, column, 'asc'))
                : [...TASKS].sort((a, b) => customSort(a, b, column, 'desc'));
        }

        // Остальные функции остаются без изменений
        function customSort(a, b, column, dir) {
            if (a.decide && !b.decide) return 1;
            if (!a.decide && b.decide) return -1;

            const modifier = dir === 'asc' ? 1 : -1;
            const valA = getSortValue(a, column);
            const valB = getSortValue(b, column);

            return (valA > valB ? 1 : -1) * modifier;
        }

        function getSortValue(item, column) {
            switch(column) {
                case 'complexity': return complexityOrder[item.complexity];
                case 'solved': return Number(item.solved);
                case 'price': return Number(item.price);
                default: return item[column].toLowerCase();
            }
        }

        function sortByDecide(a, b) {
            if (a.decide === '' && b.decide !== '') return -1;
            if (a.decide !== '' && b.decide === '') return 1;
            return 0;
        }

        function updateUI(data) {
            // Без изменений
            divElementSort.innerHTML = data.map(item => `
            <div style="cursor: pointer" href="/Home/${item.id}" class="products-row tasklink" onclick="Taskid${item.id}()">
                <div class="product-cell image" ${item.decide}>
                    <span>${item.name}</span>
                </div>
                <div class="product-cell category" ${item.decide}>
                    <span class="cell-label">{{ __('Category') }}:</span>${item.category.toUpperCase()}
                </div>
                <div class="product-cell complexity" ${item.decide}>
                    <span class="cell-label">{{ __('Complexity') }}:</span>
                    <span class="status ${item.decide}${item.complexity}">${item.complexity.toUpperCase()}</span>
                </div>
                <div class="product-cell solved" ${item.decide}>
                    <span class="cell-label">{{ __('Solved') }}:</span>${item.solved}
                </div>
                <div class="product-cell price" ${item.decide}>
                    <span class="cell-label">{{ __('Price') }}:</span>${item.price}
                </div>
            </div>
        `).join('');
        }
    </script>
@endsection

