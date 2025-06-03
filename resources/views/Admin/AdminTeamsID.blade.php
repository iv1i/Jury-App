@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('style/css/Notif.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/AdminTasks.css') }}">
    <link rel="stylesheet" href="{{ asset('style/scss/buttoncheckflag.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/InputFile.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/Statistic.css') }}">
    <link rel="stylesheet" href="{{ asset('style/scss/ChekFlag.css') }}">
    <style>
        :root {
            --app-content-secondary-color: none;
            --filter-shadow: none;
        }
    </style>
@endsection

@section('title', 'AltayCTF-School')

@section('appcontent')
    <div class="app-content">
        <div id="FormSwitchTheme" class="app-content-header">
            <h1 class="app-content-headerText">{{ \App\Models\User::find($data['id'])->name }} #{{ $data['id'] }}</h1>
            <button id="switchTheme" class="mode-switch" title="Switch Theme">
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
        <div class="topmost-div">
            <div style="text-align: center; color: white; height: 3vw;">
                <h1 class="TaskH1">{{ __('Change Teams') }} #{{ $data['id'] }}</h1>
                <a href="/Admin/Teams/" id="CloseBtn" class="btnclose"><img class="closeicontask" src="{{ asset('media/icon/close.png') }}"></a>
            </div>
            <form action="/Admin/Teams/Change" method="POST" id="MyFormPlus" class="form" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form_item"><div>Название</div>
                    <input type="text" name="name" class="" required="" value="{{ $data['team']->name }}">
                </div>
                <div class="form_item"><div>Кол-во игроков</div>
                    <input type="text" name="players" class="" value="{{ $data['team']->players }}">
                </div>
                <div class="form_item"><div>Учебное заведение</div>
                    <input type="text" name="WhereFrom" class="" value="{{ $data['team']->wherefrom }}">
                </div>
                <div class="form_item"><div>Токен</div>
                    <input type="text" name="token" placeholder="{{ __('New Token') }}" class="" >
                </div>
                <div class="form_item">
                    <div>Лого</div><div><img id="teamlogoimg" style="height: 7vw;" src="{{ asset('storage/teamlogo/' . $data['team']->teamlogo) }}"></div>
                    <label for="images" class="drop-container" id="dropcontainer">
                        <span class="drop-title">{{ __('Drop files here') }}</span>
                        <input type="file" name="file" id="images" >
                    </label>
                </div>
                <div class="form_item">Гостевая
                    <input type="checkbox" name="IsGuest" class="is_guest">
                </div>
                <div class="form_item">Стандартный логотип
                    <input type="checkbox" name="standartlogo" class="is_guest">
                </div>
                <input type="hidden" name="id" value="{{ $data['id'] }}">
                <div class="form_item">
                    <button style="width: 90%;" class="btnchk" onClick={console.log("click")} type="submit">
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
        <div class="products-area-wrapper tableView" style="filter: blur(4px);"></div>
        <a href="{{ route('AdminTeams') }}" style="width: 100%; height: 100%; background-color: white; position:absolute; opacity: .0"></a>
    </div>
    @if ($errors->any())
        <div class="notifications">
            <div class="toast active">

                <div  class="toast-content">
                    <i style="background-color: #f4406a" class="fas fa-solid fa-check check"></i>

                    <div class="message">
                        <span class="text text-1">{{__('Error')}}</span>
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
        const checkbox = document.querySelector('input[type="checkbox"]');
        const check = '{{ $data['team']->guest }}';

        if (check === "Yes") {
            checkbox.checked = true;
        }

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
            reader.onload = function(e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        })

        const input = document.querySelector('input[type="file"]');
        const img = document.querySelector('#teamlogoimg');

        input.addEventListener('change', function() {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
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
            <button class="sort-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
            </button>
        </div>
        <div class="product-cell category">{{ __('Players') }}<button class="sort-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
                <div class="product-cell status-cell">{{ __('Where-From') }}<button class="sort-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
                <div class="product-cell sales">{{ __('ID') }}<button class="sort-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
                <div class="product-cell stock">{{ __('Guest') }}<button class="sort-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
            </div>`;
            const html1 = Data.map(item => `
            <a class="products-row teamlink">
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
                <div class="product-cell stock"><span class="cell-label">{{ __('Guest') }}:</span>${item.guest}</div>
            </a>
        `).join("");
            const HTML = html0 + html1;
            Element.innerHTML = HTML;
        }
    </script>
@endsection
