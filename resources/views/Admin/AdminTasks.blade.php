@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('style/css/InputFile.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/AdminTasks.css') }}">
    <link rel="stylesheet" href="{{ asset('style/scss/buttoncheckflag.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/HomeTask.css') }}">
    <script>
        {!! Vite::content('resources/js/app.js') !!}
    </script>
@endsection

@section('title', 'AltayCTF-Admin')

@section('appcontent')
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
        <div class="products-area-wrapper tableView"></div>
    </div>
    <div class="topmost-div-task-plus">
        <div style="text-align: center; color: white; height: 3vw;">
            <h1 class="TaskH1">{{ __('Add Task') }}</h1>
            <button id="CloseBtn" class="btnclose"><img class="closeicontask" src="{{ asset('media/icon/close.png') }}">
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
            <div class="form_item ">
                <div>Сложность</div>
                <select name="complexity" id="complexity">
                    <option>easy</option>
                    <option>medium</option>
                    <option>hard</option>
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
            <label for="images" class="drop-container" style="margin-top: 10px">
                <span class="drop-title">{{ __('Drop files here') }}</span>
                <input type="file" name="file[]" id="images" multiple>
            </label>
            <div class="form_item ">
                <div>Флаг</div>
                <input type="text" name="flag" class="" required="" placeholder="school{}  flag{}" autocomplete="off">
            </div>
            <div class="form_item">
                <button style="width: 90%; position: relative;" class="btnchk" onClick={console.log("click")} type="submit">
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
    <div class="topmost-div-task-minus">
        <div style="text-align: center; color: white; height: 3vw;">
            <h1 class="TaskH1">{{ __('Delete Tasks') }}</h1>
            <button id="CloseBtn2" class="btnclose"><img class="closeicontask" src="{{ asset('media/icon/close.png') }}">
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
    <script src="{{ asset('js/Admin/AdminTasks.js') }}"></script>
    <script id="FormaPlussPluss">
        document.getElementById('MyFormPlus').addEventListener('submit', function (event) {
            event.preventDefault(); // предотвращаем стандартное поведение формы

            const formData = new FormData(this); // создаем объект FormData из формы
            fetch('/Admin/Tasks/Add', {
                method: 'POST',
                body: formData,
            })
        });
    </script>
    <script id="FormaMinusMinus">
        document.getElementById('MyFormMinus').addEventListener('submit', function (event) {
            event.preventDefault(); // предотвращаем стандартное поведение формы

            const formData = new FormData(this); // создаем объект FormData из формы

            fetch('/Admin/Tasks/Delete', {
                method: 'POST',
                body: formData,
            })
        });
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
            if (categ == 'osint') {
                select2.selectedIndex = 11;
            }
            if (categ == 'reverse') {
                select2.selectedIndex = 12;
            }
        }
        function MakeHTML(Data, Element) {
            const html0 = `<div class="products-header">
                <div class="product-cell image">{{ __('Name') }}</div>
                <div class="product-cell category">{{ __('Category') }}</div>
                <div class="product-cell status-cell">{{ __('Complexity') }}</div>
                <div class="product-cell sales">{{ __('ID') }}</div>
                <div class="product-cell price">{{ __('Price') }}</div>
            </div>`;
            const html1 = Data.map(item => `
            <a href="/Admin/Tasks/${item.id}" class="products-row tasklink">
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
@endsection

