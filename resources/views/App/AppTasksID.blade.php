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
        <a href="{{ route('App-Home-View') }}" style="width: 100%; height: 100%; background-color: white; position:absolute; opacity: .0"></a>
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
@endsection
