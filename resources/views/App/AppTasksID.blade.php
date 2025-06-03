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

@section('title', 'AltayCTF-Sch-TaskID')

@section('appcontent')
    <div class="app-content">
        <div id="FormSwitchTheme" class="app-content-header">
            <h1 class="app-content-headerText">{{ __('Task') }} #{{ $data['id'] }}</h1>
            <button id="switchTheme" class="mode-switch" title="Switch Theme" style="z-index: 9999">
                <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" width="24" height="24" viewBox="0 0 24 24">
                    <defs></defs>
                    <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                </svg>
            </button>
        </div>
        <div class="app-content-actions">
            <div class="app-content-actions-wrapper">
                <div class="filter-button-wrapper">
                    <button class="action-button filter jsFilter" style="display: none"></button>
                </div>
                <button class="action-button list active" title="List View" style="display: none">
                </button>
                <button class="action-button grid" title="Grid View" style="display: none">
                </button>
            </div>
        </div>
        <a href="{{ route('Home') }}" style="width: 100%; height: 100%; background-color: white; position:absolute; opacity: .0"></a>
        <div class="topmost-div">
            <div style="text-align: center; height: 3em;" ><h1 class="TaskH1">{{ $data['task']->name }}</h1><a href="/Home" id="CloseBtn" class="btnclose"><img class="closeicontask" src="{{ asset('/media/icon/close.png') }}"></a>
            </div>
            <div class="description">
                <div class="{{ $data['task']->complexity }} taskID_complexity">{{ Str::of($data['task']->complexity)->upper() }}</div>
                {!! $data['task']->description !!}
            </div>
            <div class="description">
                @isset($data['task']->FILES)
                    @foreach(explode(";", $data['task']->FILES) as $k => $file)
                        @if($file)
                            <a href="{{ asset('/Download/File/' . md5($file)) }}{{ '/' . $data['task']->id }}"> {{ 'Файл#' . $k+1 }}</a>
                        @endif
                    @endforeach
                @endisset
            </div>
            <form id="MyFormPlus" class="MyFormSellFlag" action="/Home/Tasks/Check" method="post">
                @csrf
                <div class="form__group field">
                    <input type="input" class="form__field" placeholder="Name" name="flag" id='name' required autocomplete="off"/>
                    <label for="name" class="form__label">school{...}</label>
                    <input type="hidden" name="ID" value="{{ $data['id'] }}">
                    <input type="hidden" name="complexity" value="{{ $data['task']->complexity }}">
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
        <div class="notifications"></div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/App/SubmitFormTasksID.js') }}"></script>
    <script>
        const myDiv = document.querySelector('.topmost-div');
        const height = myDiv.offsetHeight; // Получаем высоту блока

        const rect = myDiv.getBoundingClientRect();
        const distanceFromTop = rect.top;

        window.addEventListener('resize', function() {
            const formContainer = document.querySelector('.MyFormSellFlag');

            // Проверяем высоту окна
            if (window.innerHeight < 400) { // Условие можно настроить под свои нужды
                formContainer.style.transform = `translateY(${-height-distanceFromTop+400}px)`;
                formContainer.style.transform = `background-color: var(--app-bg);`;
            } else {
                // Возвращаем форму на место
                formContainer.style.transform = 'translateY(0)';
                formContainer.style.transform = `background-color: var(--app-bg);`;// Можно настроить отступ
            }
        });
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
{{--    <script type="text/javascript">--}}
{{--        const teamid = {{ auth()->id() }};--}}
{{--        const data = {!! json_encode(\App\Models\Tasks::all()) !!};--}}
{{--        const solvedtasks = {!! json_encode(\App\Models\User::find(auth()->id())->solvedTasks) !!};--}}

{{--        for (let i = 0; i < data.length; i++) {--}}
{{--            data[i].decide = '';--}}
{{--        }--}}
{{--        for (let i = 0; i < data.length; i++) {--}}
{{--            //console.log(data[i].id);--}}
{{--            for (let j = 0; j < solvedtasks.length; j++) {--}}
{{--                if (solvedtasks[j].tasks_id === data[i].id) {--}}
{{--                    data[i].decide = `style="color: var(--app-bg-tasks);"`;--}}
{{--                    //console.log(data[i].decide);--}}
{{--                    //data.splice(i, 1);--}}
{{--                }--}}
{{--            }--}}
{{--        }--}}
{{--        data.sort((a, b) => {--}}
{{--            const complexityOrder = {easy: 1, medium: 2, hard: 3};--}}
{{--            return complexityOrder[a.complexity] - complexityOrder[b.complexity];--}}
{{--        });--}}
{{--        data.sort((a, b) => {--}}
{{--            //console.log(a.decide);--}}
{{--            // Если a.deside пустой, он должен быть перед b.deside--}}
{{--            if (a.decide === '' && b.decide !== '') {--}}
{{--                return -1; // a перед b--}}
{{--            }--}}
{{--            if (a.decide !== '' && b.decide === '') {--}}
{{--                return 1; // b перед a--}}
{{--            }--}}
{{--            return 0; // оставляем порядок неизменным--}}
{{--        });--}}
{{--        const divElement = document.querySelector('.products-area-wrapper');--}}

{{--        localStorage.setItem('data', JSON.stringify(data));--}}
{{--        let taskcomplexity = localStorage.getItem('taskcomplexity');--}}
{{--        let taskcategory = localStorage.getItem('taskcategory');--}}
{{--        Filtereed(data, taskcomplexity, taskcategory);--}}

{{--        Echo.private(`channel-app-home`).listen('AppHomeEvent', (e) => {--}}
{{--            const valueToDisplay = e.tasks;--}}
{{--            let Tasks = valueToDisplay.Tasks;--}}
{{--            let SolvedTaasks = valueToDisplay.SolvedTasks;--}}

{{--            let SolvedTasksOnThisAuthUser = [];--}}
{{--            for (let i = 0; i < SolvedTaasks.length; i++) {--}}
{{--                if (SolvedTaasks[i].user_id === teamid) {--}}
{{--                    SolvedTasksOnThisAuthUser.push(valueToDisplay.SolvedTasks[i]);--}}
{{--                }--}}
{{--            }--}}

{{--            //console.log(SolvedTasksOnThisAuthUser);--}}
{{--            let Solvedtasks = [];--}}
{{--            for (let i = 0; i < Tasks.length; i++) {--}}
{{--                for (let j = 0; j < SolvedTasksOnThisAuthUser.length; j++) {--}}
{{--                    if (SolvedTasksOnThisAuthUser[j].tasks_id === Tasks[i].id) {--}}
{{--                        Solvedtasks.push(Tasks[i]);--}}
{{--                    }--}}
{{--                }--}}
{{--            }--}}

{{--            for (let i = 0; i < Tasks.length; i++) {--}}
{{--                Tasks[i].decide = '';--}}
{{--            }--}}

{{--            for (let i = 0; i < Tasks.length; i++) {--}}
{{--                //console.log(data[i].id);--}}
{{--                for (let j = 0; j < SolvedTasksOnThisAuthUser.length; j++) {--}}
{{--                    if (SolvedTasksOnThisAuthUser[j].tasks_id === Tasks[i].id) {--}}
{{--                        Tasks[i].decide = `style="color: #2c394f;filter: blur(0.7px);"`;--}}
{{--                        //data.splice(i, 1);--}}
{{--                    }--}}
{{--                }--}}
{{--            }--}}

{{--            Tasks.sort((a, b) => {--}}
{{--                const complexityOrder = {easy: 1, medium: 2, hard: 3};--}}
{{--                return complexityOrder[a.complexity] - complexityOrder[b.complexity];--}}
{{--            });--}}

{{--            Tasks.sort((a, b) => {--}}
{{--                //console.log(a.decide);--}}
{{--                // Если a.deside пустой, он должен быть перед b.deside--}}
{{--                if (a.decide === '' && b.decide !== '') {--}}
{{--                    return -1; // a перед b--}}
{{--                }--}}
{{--                if (a.decide !== '' && b.decide === '') {--}}
{{--                    return 1; // b перед a--}}
{{--                }--}}
{{--                return 0; // оставляем порядок неизменным--}}
{{--            });--}}

{{--            //console.log(Tasks);--}}
{{--            //console.log(Solvedtasks);--}}

{{--            localStorage.setItem('data', JSON.stringify(Tasks));--}}
{{--            console.log('Принято!');--}}

{{--            //const sortedData = valueToDisplay.sort((a, b) => b.score - a.score);--}}
{{--            //console.log(sortedData);--}}

{{--            let taskcomplexity = localStorage.getItem('taskcomplexity');--}}
{{--            let taskcategory = localStorage.getItem('taskcategory');--}}
{{--            Filtereed(Tasks, taskcomplexity, taskcategory);--}}
{{--            //console.log(e.test);--}}
{{--        });--}}


{{--        function Filtereed(DATA, taskcomplexity, taskcategory){--}}
{{--            if (taskcategory && taskcomplexity) {--}}
{{--                setSelection(taskcomplexity, taskcategory);--}}
{{--                let Data = DATA;--}}
{{--                if (taskcategory !== 'All Categories' && taskcomplexity !== 'All Complexity') {--}}
{{--                    Data = DATA.filter(item => item.complexity === taskcomplexity && item.category === taskcategory);--}}
{{--                }--}}
{{--                if (taskcategory !== 'All Categories' && taskcomplexity === 'All Complexity') {--}}
{{--                    Data = DATA.filter(item => item.category === taskcategory);--}}
{{--                }--}}
{{--                if (taskcategory === 'All Categories' && taskcomplexity !== 'All Complexity') {--}}
{{--                    Data = DATA.filter(item => item.complexity === taskcomplexity);--}}
{{--                }--}}
{{--                MakeHTML(Data, divElement);--}}
{{--            } else {--}}
{{--                MakeHTML(DATA, divElement);--}}
{{--            }--}}
{{--        }--}}

{{--        function MakeHTML(Data, Element) {--}}
{{--            const html0 = `<div class="products-header">--}}
{{--                <div class="product-cell image">{{ __('Name') }}<button class="sort-button">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>--}}
{{--                    </button></div>--}}
{{--                <div class="product-cell category">{{ __('Category') }}<button class="sort-button">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>--}}
{{--                    </button></div>--}}
{{--                <div class="product-cell complexity">{{ __('Complexity') }}<button class="sort-button">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>--}}
{{--                    </button></div>--}}
{{--                <div class="product-cell solved">{{ __('Solved') }}<button class="sort-button">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>--}}
{{--                    </button></div>--}}
{{--                <div class="product-cell price">{{ __('Price') }}<button class="sort-button">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>--}}
{{--                    </button></div>--}}
{{--            </div>`;--}}
{{--            const html1 = Data.map(item => `--}}
{{--            <div class="products-row">--}}
{{--                <button class="cell-more-button">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>--}}
{{--                </button>--}}
{{--                <div class="product-cell image" ${item.decide}>--}}
{{--                    <span>${item.name}</span>--}}
{{--                </div>--}}
{{--                <div class="product-cell category" ${item.decide}><span class="cell-label">{{ __('Category') }}:</span>${item.category.toUpperCase()}</div>--}}
{{--                <div class="product-cell complexity" ${item.decide}>--}}
{{--                    <span class="cell-label">{{ __('Complexity') }}:</span>--}}
{{--                    <span class="status ${item.decide}${item.complexity}" >${item.complexity.toUpperCase()}</span>--}}
{{--                </div>--}}
{{--                <div class="product-cell solved" ${item.decide}><span class="cell-label">{{ __('Solved') }}:</span>${item.solved}</div>--}}
{{--                <div class="product-cell price" ${item.decide}><span class="cell-label">{{ __('Price') }}:</span>${item.price}</div>--}}
{{--            </div>--}}
{{--        `).join("");--}}
{{--            const HTML = html0 + html1;--}}
{{--            Element.innerHTML = HTML;--}}
{{--        }--}}

{{--        function getCookie(name) {--}}
{{--            const cookies = document.cookie.split(';');--}}
{{--            for (let i = 0; i < cookies.length; i++) {--}}
{{--                const [key, value] = cookies[i].trim().split('=');--}}
{{--                if (key === name) {--}}
{{--                    return decodeURIComponent(value);--}}
{{--                }--}}
{{--            }--}}
{{--            return null;--}}
{{--        }--}}

{{--        function setCookie(name, value, days, sameSite, path) {--}}
{{--            const expires = days ? `; expires=${new Date(Date.now() + days * 86400000).toUTCString()}` : '';--}}
{{--            const sameSiteAttribute = sameSite ? `; SameSite=${sameSite}` : '';--}}
{{--            const cookiePath = path ? `; path=${path}` : '';--}}
{{--            document.cookie = `${name}=${encodeURIComponent(value)}${expires}${sameSiteAttribute}${cookiePath}`;--}}
{{--        }--}}

{{--        function setSelection(complx, categ) {--}}
{{--            let select = document.querySelector("select[name='complexity']");--}}
{{--            let select2 = document.querySelector("select[name='category']");--}}

{{--            if(select && select2) {--}}
{{--                if (complx == 'All Complexity') {--}}
{{--                    select.selectedIndex = 0;--}}
{{--                }--}}
{{--                if (complx == 'easy') {--}}
{{--                    select.selectedIndex = 1;--}}
{{--                }--}}
{{--                if (complx == 'medium') {--}}
{{--                    select.selectedIndex = 2;--}}
{{--                }--}}
{{--                if (complx == 'hard') {--}}
{{--                    select.selectedIndex = 3;--}}
{{--                }--}}

{{--                if (categ == 'All Categories') {--}}
{{--                    select2.selectedIndex = 0;--}}
{{--                }--}}
{{--                if (categ == 'admin') {--}}
{{--                    select2.selectedIndex = 1;--}}
{{--                }--}}
{{--                if (categ == 'recon') {--}}
{{--                    select2.selectedIndex = 2;--}}
{{--                }--}}
{{--                if (categ == 'crypto') {--}}
{{--                    select2.selectedIndex = 3;--}}
{{--                }--}}
{{--                if (categ == 'stegano') {--}}
{{--                    select2.selectedIndex = 4;--}}
{{--                }--}}
{{--                if (categ == 'ppc') {--}}
{{--                    select2.selectedIndex = 5;--}}
{{--                }--}}
{{--                if (categ == 'pwn') {--}}
{{--                    select2.selectedIndex = 6;--}}
{{--                }--}}
{{--                if (categ == 'web') {--}}
{{--                    select2.selectedIndex = 7;--}}
{{--                }--}}
{{--                if (categ == 'forensic') {--}}
{{--                    select2.selectedIndex = 8;--}}
{{--                }--}}
{{--                if (categ == 'joy') {--}}
{{--                    select2.selectedIndex = 9;--}}
{{--                }--}}
{{--                if (categ == 'misc') {--}}
{{--                    select2.selectedIndex = 10;--}}
{{--                }--}}
{{--            }--}}
{{--        }--}}

{{--        localStorage.removeItem('DataAdmin');--}}
{{--        localStorage.removeItem('taskcategoryAdmin');--}}
{{--        localStorage.removeItem('taskcomplexityAdmin');--}}
{{--    </script>--}}
@endsection
