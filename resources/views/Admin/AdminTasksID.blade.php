@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('style/css/Notif.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/DeleteButton.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/InputFile.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/AdminTasks.css') }}">
    <link rel="stylesheet" href="{{ asset('style/scss/buttoncheckflag.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/HomeTask.css') }}">
    <script>
        {!! Vite::content('resources/js/app.js') !!}
    </script>
@endsection

@section('title', 'AltayCTF-School')


@section('appcontent')
    <div class="app-content">
        <div id="FormSwitchTheme" class="app-content-header">
            <h1 class="app-content-headerText">{{ \App\Models\Tasks::find($data['id'])->name }} #{{ $data['id'] }}</h1>
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
        <div class="products-area-wrapper tableView" style="filter: blur(4px);"></div>
        <a href="{{ route('AdminTasks') }}" style="width: 100%; height: 100%; background-color: white; position:absolute; opacity: .0"></a>
        <div class="topmost-div">
            <div style="text-align: center; color: white; height: 3vw;">
                <h1 class="TaskH1">{{ __('To Change Task') }} #{{ $data['id'] }}</h1>
                <a href="/Admin/Tasks" id="CloseBtn" class="btnclose"><img class="closeicontask" src="{{ asset('media/icon/close.png') }}"></a>
            </div>
            <form action="/Admin/Tasks/Change" method="POST" class="form" id="MyFormChange" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form_item"><div>Название</div>
                    <input  name="name" class="" required="" value="{{ $data['task']->name }}">
                </div>
                <div class="form_item "><div>Категория</div>
                    <select name="category" id="category">
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
                </div>
                <div class="form_item "><div>Сложность</div>
                    <select name="complexity" id="complexity">
                        <option>easy</option>
                        <option>medium</option>
                        <option>hard</option>
                    </select>
                </div>
                <div class="form_item "><div>Очки</div>
                    <input  type="text" name="points" placeholder="1000" value="{{ $data['task']->oldprice }}" class="" required="">
                </div>
                <div class="form_item"><div>Описание</div>
                    <textarea name="description" class="task_text" ></textarea>
                </div>
                <div class="form_item" style="display: flex">
                    <label for="images" class="drop-container" style="margin-top: 10px;@isset($data['task']->FILES) left: 8%; @endisset">
                        <span class="drop-title">
                            @if($data['task']->FILES)
                                {{ __('Replace files') }}
                            @else
                                {{ __('Add files') }}
                            @endif
                        </span>
                        <input type="file" name="file[]" id="images" multiple>
                    </label>
                    @isset($data['task']->FILES)
                    <div class="files-container" id="file-names" style="margin-top: 10px; left: 12%; display: block">
                        <span class="files-title">
                            {{ __('Saved files') . ':' }}
                        </span>
                        @isset($data['task']->FILES)
                            @foreach(explode(";", $data['task']->FILES) as $k => $file)
                                @if($file)
                                    <span style="font-size: 13px; color: #878b8e; display: flex">
                                        {{ $file }}
                                    </span>
                                @endif
                                @break($k > 1)
                            @endforeach
                            @if(count(explode(";", $data['task']->FILES)) > 4)
                                <span style="font-size: 13px; color: #878b8e; display: flex">
                                   И еще {{ count(explode(";", $data['task']->FILES))-4 }} ...
                                </span>
                            @endif
                        @endisset
                    </div>
                    @endisset
                </div>
                <div class="form_item "><div>Флаг</div>
                    <input  type="text" name="flag" class="" required="" placeholder="school{}" value="{{ $data['task']->flag }}">
                </div>
                <div class="form_item">
                    <input type="hidden" name="id" value="{{ $data['id'] }}">
                </div>
                <button style="width: 90%; margin-left:47px" class="btnchk" onClick={console.log("click")} type="submit">
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
            @isset($data['task']->FILES)
            <button class="DeleteFilesButton">
                <svg class="DeleteFilesButtonSvg" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
            @endisset
            <h1></h1>
        </div>
    </div>
    @if ($errors->any())
        <div class="notifications">
            <div class="toast active">

                <div  class="toast-content">
                    <i style="background-color: #f4406a" class="fas fa-solid fa-check check"></i>

                    <div class="message">
                        <span class="text text-1">Error</span>
                        @foreach ($errors->all() as $error)
                            <span class="text text-2">{{ $error }}</span>
                        @endforeach
                    </div>
                </div>
                <i class="fa-solid fa-xmark close">
                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Menu / Close_SM"> <path id="Vector" d="M16 16L12 12M12 12L8 8M12 12L16 8M12 12L8 16" stroke="#77767b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g> </g></svg>
                </i>
                <style>
                    .toast .progress:before {
                        background-color: #f4406a;
                    }
                </style>
                <!-- Remove 'active' class, this is just to show in Codepen thumbnail -->
                <div  class="progress active"></div>
            </div>
        </div>
        <script id="Notifications" src="{{ asset('js/Other/Notifications.js') }}"></script>
    @endif
@endsection

@section('scripts')
    <script>
        let textarea = document.querySelector("textarea[name='description']");
        textarea.value = `{!! $data['task']->description !!}`;
        let select = document.querySelector("select[name='complexity']");
        let select2 = document.querySelector("select[name='category']");
        let taskcomplexity1 = '{{ $data['task']->complexity }}';
        let taskcategory1 = '{{ $data['task']->category}}';

        if (taskcomplexity1 === 'easy'){
            select.selectedIndex = 0;
        }
        if (taskcomplexity1 === 'medium'){
            select.selectedIndex = 1;
        }
        if (taskcomplexity1 === 'hard'){
            select.selectedIndex = 2;
        }

        if (taskcategory1 === 'admin'){
            select2.selectedIndex = 0;
        }
        if (taskcategory1 === 'recon'){
            select2.selectedIndex = 1;
        }
        if (taskcategory1 === 'crypto'){
            select2.selectedIndex = 2;
        }
        if (taskcategory1 === 'stegano'){
            select2.selectedIndex = 3;
        }
        if (taskcategory1 === 'ppc'){
            select2.selectedIndex = 4;
        }
        if (taskcategory1 === 'pwn'){
            select2.selectedIndex = 5;
        }
        if (taskcategory1 === 'web'){
            select2.selectedIndex = 6;
        }
        if (taskcategory1 === 'forensic'){
            select2.selectedIndex = 7;
        }
        if (taskcategory1 === 'joy'){
            select2.selectedIndex = 8;
        }
        if (taskcategory1 === 'misc'){
            select2.selectedIndex = 9;
        }
        if (taskcategory1 === 'osint'){
            select2.selectedIndex = 10;
        }
        if (taskcategory1 === 'reverse'){
            select2.selectedIndex = 11;
        }
    </script>
    <script type="text/javascript">
        const data = {!! json_encode(\App\Models\Tasks::all()) !!};
        localStorage.setItem('DataAdmin', JSON.stringify(data));
        const divElement = document.querySelector('.tableView');

        let taskcomplexity = localStorage.getItem('taskcomplexityAdmin');
        let taskcategory = localStorage.getItem('taskcategoryAdmin');
        Filtereed(data, taskcomplexity, taskcategory);

        Echo.private(`channel-admin-tasks`).listen('AdminTasksEvent', (e) => {
            const valueToDisplay = e.tasks;
            localStorage.setItem('data', JSON.stringify(valueToDisplay));
            console.log('Принято!');

            let taskcomplexity = localStorage.getItem('taskcomplexityAdmin');
            let taskcategory = localStorage.getItem('taskcategoryAdmin');
            Filtereed(valueToDisplay, taskcomplexity, taskcategory);
        });
        document.getElementById('ApplyBtn').addEventListener('click', function () {
            let taskcomplexity = document.getElementById('complexity').value;
            let taskcategory = document.getElementById('category').value;

            localStorage.setItem('taskcomplexityAdmin', taskcomplexity);
            localStorage.setItem('taskcategoryAdmin', taskcategory);

            if (taskcategory !== 'All Categories' && taskcomplexity !== 'All Complexity') {
                const data = JSON.parse(localStorage.getItem('DataAdmin'));
                const newArray = data.filter(item => item.complexity === taskcomplexity && item.category === taskcategory);
                MakeHTML(newArray, divElement);
            } else {
                MakeHTML(data, divElement);
            }
            if (taskcategory !== 'All Categories' && taskcomplexity === 'All Complexity') {
                const data = JSON.parse(localStorage.getItem('DataAdmin'));
                const newArray = data.filter(item => item.category === taskcategory);
                MakeHTML(newArray, divElement);
            }
            if (taskcategory === 'All Categories' && taskcomplexity !== 'All Complexity') {
                const data = JSON.parse(localStorage.getItem('DataAdmin'));
                const newArray = data.filter(item => item.complexity === taskcomplexity);
                MakeHTML(newArray, divElement);
            }
        });
        document.getElementById('ResetBtn').addEventListener('click', function () {
            const data = JSON.parse(localStorage.getItem('DataAdmin'));
            let taskcomplexity = 'All Complexity';
            let taskcategory = 'All Categories';
            setSelection(taskcomplexity, taskcategory);
            localStorage.setItem('taskcomplexityAdmin', taskcomplexity);
            localStorage.setItem('taskcategoryAdmin', taskcategory);
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
        }
        function MakeHTML(Data, Element) {
            const html0 = `<div class="products-header">
                <div class="product-cell image">
                    {{ __('Name') }}
            <button class="sort-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
            </button>
        </div>
        <div class="product-cell category">{{ __('Category') }}<button class="sort-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
                <div class="product-cell status-cell">{{ __('Complexity') }}<button class="sort-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
                <div class="product-cell sales">{{ __('ID') }}<button class="sort-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
                <div class="product-cell price">{{ __('Price') }}<button class="sort-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
            </div>`;
            const html1 = Data.map(item => `
            <a class="products-row tasklink">
                <button class="cell-more-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                </button>
                <div class="product-cell image">
                    <span>${item.name}</span>
                </div>
                <div class="product-cell category"><span class="cell-label">{{ __('Category') }}:</span>${item.category.toUpperCase()}</div>
                <div class="product-cell status-cell">
                    <span class="cell-label">{{ __('Complexity') }}:</span>
                    <span class="status ${item.complexity}">${item.complexity.toUpperCase()}</span>
                </div>
                <div class="product-cell sales"><span class="cell-label">{{ __('ID') }}:</span>${item.id}</div>
                <div class="product-cell price"><span class="cell-label">{{ __('Price') }}:</span>${item.price}</div>
            </a>
        `).join("");
            const HTML = html0 + html1;
            Element.innerHTML = HTML;
        }
    </script>
    @isset($data['task']->FILES)
        <script type="text/javascript">
            document.querySelector('.DeleteFilesButton').addEventListener('click', function() {
                var form = document.getElementById('MyFormChange');
                var input = form.querySelector('input[type="hidden"][name="deleteFilesFromTask"]');

                if (!input) {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.value = 'DELETEALL';
                    input.name = 'deleteFilesFromTask';
                    form.appendChild(input);
                }
                // Отправляем форму без асинхронности
                form.submit();
            });
        </script>
    @endisset
@endsection
