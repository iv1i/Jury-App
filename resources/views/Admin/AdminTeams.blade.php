@extends('layouts.admin')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('style/css/AdminTasks.css') }}">
    <link rel="stylesheet" href="{{ asset('style/scss/buttoncheckflag.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/InputFile.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/Statistic.css') }}">
    <script>
        {!! Vite::content('resources/js/app.js') !!}
    </script>
@endsection

@section('title', 'AltayCTF-Admin')

@section('appcontent')
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
                    Name
                </div>
                <div class="product-cell category">Players
                </div>
                <div class="product-cell status-cell">Where-from
                </div>
                <div class="product-cell sales">ID
                </div>
                <div class="product-cell stock">Guest
                </div>
            </div>
            <div class="products-row">
                <button class="cell-more-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-more-vertical">
                        <circle cx="12" cy="12" r="1"/>
                        <circle cx="12" cy="5" r="1"/>
                        <circle cx="12" cy="19" r="1"/>
                    </svg>
                </button>
                <div class="product-cell image">
                    <span>Жуколовы</span>
                </div>
                <div class="product-cell category"><span class="cell-label">Category:</span>7</div>
                <div class="product-cell status-cell">
                    <span class="cell-label">Status:</span>
                    <span class="status">АГТУ</span>
                </div>
                <div class="product-cell sales"><span class="cell-label">ID:</span>11</div>
                <div class="product-cell stock"><span class="cell-label">Guest:</span>No</div>
            </div>
        </div>
    </div>
    <div class="topmost-div-task-minus">
        <div style="text-align: center; color: white; height: 3vw;">
            <h1 class="TaskH1">{{ __('Delete Teams') }}</h1>
            <button id="CloseBtn2" class="btnclose"><img class="closeicontask" src="{{ asset('media/icon/close.png') }}">
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
    <div class="topmost-div-task-plus">
        <div style="text-align: center; color: white; height: 3vw;">
            <h1 class="TaskH1">{{ __('Add Teams') }}</h1>
            <button id="CloseBtn" class="btnclose"><img class="closeicontask" src="{{ asset('media/icon/close.png') }}">
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
                <button style="width: 90%;" class="btnchk" onClick={console.log("click")} type="submit">
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
    <script src="{{ asset('js/Admin/AdminTasks.js') }}"></script>
    <script id="FormaPlussPluss">
        document.getElementById('MyFormPlus').addEventListener('submit', function (event) {
            event.preventDefault(); // предотвращаем стандартное поведение формы

            const formData = new FormData(this); // создаем объект FormData из формы

            fetch('/Admin/Teams/Add', {
                method: 'POST',
                body: formData,
            })
        });
    </script>
    <script id="FormaMinusMinus">
        document.getElementById('MyFormMinus').addEventListener('submit', function (event) {
            event.preventDefault(); // предотвращаем стандартное поведение формы

            const formData = new FormData(this); // создаем объект FormData из формы

            fetch('/Admin/Teams/Delete', {
                method: 'POST',
                body: formData,
            })
        });
    </script>
    <script type="text/javascript">
        const data = {!! json_encode(\App\Models\User::all()) !!};
        const svg = `{!! view('SVG.GuestSVG') !!}`;

        for (let i = 0; i < data.length; i++) {
            if (data[i].guest == 'Yes') {
                data[i].GuestLogo = svg;
            }
        }

        //const sortedData = data.sort((a, b) => b.score - a.score);
        //console.log(sortedData);

        const divElement = document.querySelector('.tableView');
        MakeHTML(data, divElement);

        Echo.private(`channel-admin-teams`).listen('AdminTeamsEvent', (e) => {
            const valueToDisplay = e.teams;
            console.log('Принято!');
            for (let i = 0; i < valueToDisplay.length; i++) {
                if (valueToDisplay[i].guest == 'Yes') {
                    valueToDisplay[i].GuestLogo = svg;
                }
            }
            //const sortedData = valueToDisplay.sort((a, b) => b.score - a.score);
            //console.log(sortedData);
           MakeHTML(valueToDisplay, divElement);

        });

        function MakeHTML(Data, Element) {
            const host = window.location.hostname;
            const protocol = window.location.protocol;
            const port = window.location.port;
            const url = protocol + '//' + host + ':' + port + '/storage/teamlogo/';
            const html0 = `<div class="products-header">
                <div class="product-cell image">
                    {{ __('Name')}}
                </div>
                <div class="product-cell category">{{ __('Players') }}</div>
                <div class="product-cell status-cell">{{ __('Where-From') }}</div>
                <div class="product-cell sales">{{ __('ID') }}</div>
                <div class="product-cell category">{{ __('Guest') }}</div>
            </div>`;
            const html1 = Data.map(item => `
            <a href="/Admin/Teams/${item.id}" class="products-row teamlink">
                <button class="cell-more-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                </button>
                <div class="product-cell image">
                <img src="${url + item.teamlogo}" alt="product">
                    <span>${item.name} ${item.GuestLogo}</span>
                </div>
                <div class="product-cell category"><span class="cell-label">{{__('Players')}}:</span>${item.players}</div>
                <div class="product-cell status-cell">
                    <span class="cell-label">{{ __('Where-From') }}:</span>
                    <span class="status">${item.wherefrom}</span>
                </div>
                <div class="product-cell sales"><span class="cell-label">{{ __('ID') }}:</span>${item.id}</div>
                <div class="product-cell category"><span class="cell-label">{{ __('Guest') }}:</span>${item.guest}</div>
            </a>
        `).join("");
            const HTML = html0 + html1;
            Element.innerHTML = HTML;
        }
    </script>
    <script type="text/javascript">
        const dropContainer = document.getElementById("dropcontainer")
        const fileInput = document.getElementById("images")

        dropContainer.addEventListener("dragover", (e) => {
            // prevent default to allow drop
            e.preventDefault()
        }, false)

        dropContainer.addEventListener("dragenter", () => {
            dropContainer.classList.add("drag-active")
        })

        dropContainer.addEventListener("dragleave", () => {
            dropContainer.classList.remove("drag-active")
        })

        dropContainer.addEventListener("drop", (e) => {
            e.preventDefault()
            dropContainer.classList.remove("drag-active")
            fileInput.files = e.dataTransfer.files
            const input = document.querySelector('input[type="file"]');
            const img = document.querySelector('#teamlogoimg');
            const reader = new FileReader();
            reader.onload = function (e) {
                img.src = e.target.result;
                img.style.display = '';
            };
            reader.readAsDataURL(input.files[0]);
        })

        const input = document.querySelector('input[type="file"]');
        const img = document.querySelector('#teamlogoimg');

        input.addEventListener('change', function () {
            const reader = new FileReader();
            reader.onload = function (e) {
                img.src = e.target.result;
                img.style.display = '';
            };
            reader.readAsDataURL(input.files[0]);
        });
    </script>
@endsection

