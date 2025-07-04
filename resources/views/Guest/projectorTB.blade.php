@extends('layouts.noneslidebar')

@section('css')
    <script>
        {!! Vite::content('resources/js/app.js') !!}
    </script>
@endsection

@section('title', 'AltayCTF-School')

@section('appcontent')
    <div
        style="display: none; text-align: center; width: 100%; height: 2.2vh; color: var(--app-bg-inv);background-color: var(--app-bg-2); position: fixed; top: 0%; opacity: 0.5;font-family: cursive; font-size: 12px">
        <b>By SharLike</b>
    </div>
    <div class="app-content">
        <div class="app-content-header">
            <h1 class="app-content-headerText"></h1>
            <a href="/" class="app-icon">
                <svg xmlns="http://www.w3.org/2000/svg"
                     style="width:105px; height:45px;" viewBox="0 0 887.000000 449.000000"
                     preserveAspectRatio="xMidYMid meet">
                    <g transform="translate(0.000000,549.000000) scale(0.100000,-0.100000)"
                       fill="var(--app-bg-inv)" stroke="none">
                        <path d="M3411 5425 c-15 -24 -31 -41 -35 -37 -4 4 -6 2 -4 -5 3 -13 -43 -93
-54 -93 -5 0 -8 -6 -8 -13 0 -7 -8 -22 -19 -33 -10 -11 -22 -30 -25 -42 -4
-13 -12 -19 -18 -16 -6 4 -8 3 -5 -3 8 -13 -27 -65 -38 -56 -4 5 -6 3 -2 -2 7
-14 -58 -117 -68 -107 -5 4 -5 2 -2 -4 7 -12 -26 -67 -36 -59 -4 3 -5 2 -2 -2
3 -3 -2 -18 -11 -32 -20 -30 -37 -17 189 -154 89 -54 167 -95 175 -92 8 3 87
50 177 104 90 55 165 98 167 96 2 -2 10 6 17 18 8 12 10 19 6 14 -10 -8 -66
73 -59 85 3 4 1 8 -4 8 -11 0 -65 80 -65 97 0 7 -3 10 -7 8 -8 -5 -35 46 -31
60 2 5 -1 7 -4 3 -8 -8 -88 110 -88 129 0 7 -3 10 -7 8 -8 -5 -35 45 -32 58 1
4 -2 7 -7 7 -13 0 -62 79 -56 90 3 6 1 10 -5 10 -6 0 -23 -20 -39 -45z"/>
                        <path d="M4307 4793 c4 -10 2 -14 -4 -10 -5 3 -65 -25 -131 -63 -67 -37 -122
-65 -122 -62 0 4 -4 2 -8 -4 -12 -18 -98 -65 -105 -57 -4 3 -7 1 -7 -5 0 -13
-71 -52 -95 -52 -9 0 -14 -4 -11 -9 3 -5 -9 -15 -27 -22 -17 -7 -38 -19 -45
-26 -8 -7 -18 -11 -23 -8 -5 4 -9 2 -9 -2 0 -5 -13 -17 -30 -26 -16 -10 -30
-15 -30 -12 0 4 -7 -1 -16 -9 -8 -9 -20 -13 -26 -10 -6 4 -8 3 -5 -3 3 -5 -9
-17 -28 -26 l-35 -17 0 -1522 0 -1523 90 3 90 3 0 279 0 279 124 75 c68 41
127 72 130 69 3 -4 6 -2 6 4 0 6 64 49 143 96 162 97 218 149 271 254 57 114
66 166 66 382 l0 192 -37 -25 c-20 -14 -40 -23 -45 -20 -4 3 -8 1 -8 -4 0 -12
-69 -55 -87 -55 -7 0 -13 -4 -13 -8 0 -11 -55 -40 -64 -34 -3 3 -4 2 -1 -2 7
-9 -46 -43 -57 -37 -4 3 -8 1 -8 -5 0 -14 -55 -42 -65 -33 -5 4 -6 3 -3 -3 3
-5 -70 -56 -162 -112 -123 -74 -171 -98 -179 -90 -14 14 -15 347 -1 347 6 0
13 0 16 -1 4 0 10 3 13 9 11 17 373 202 384 195 5 -3 7 -2 4 4 -3 5 10 17 29
28 43 23 96 86 131 157 14 29 29 55 32 58 3 3 7 16 9 30 1 13 7 27 13 30 5 4
9 11 8 16 -5 30 2 94 11 89 11 -7 15 347 4 357 -3 3 -52 -20 -110 -52 -57 -32
-104 -56 -104 -54 0 3 -7 -2 -16 -10 -8 -9 -20 -14 -25 -11 -5 4 -9 2 -9 -2 0
-5 -13 -17 -30 -26 -16 -10 -30 -15 -30 -12 0 4 -7 -1 -16 -9 -8 -9 -20 -14
-25 -11 -5 4 -9 1 -9 -4 0 -13 -95 -62 -104 -54 -3 4 -6 1 -6 -4 0 -16 -129
-85 -142 -77 -6 4 -8 2 -5 -3 7 -12 -17 -25 -27 -16 -3 4 -6 84 -6 179 l0 172
182 94 c135 69 196 106 235 144 29 28 55 49 56 47 2 -2 4 3 4 10 0 6 13 35 30
62 16 28 29 52 29 55 -1 15 28 109 40 132 17 31 21 408 4 408 -5 0 -6 -7 -3
-17z m0 -340 c-3 -10 -5 -4 -5 12 0 17 2 24 5 18 2 -7 2 -21 0 -30z"/>
                        <path d="M2614 4548 c3 -112 7 -213 7 -225 1 -13 4 -23 8 -23 8 0 35 -88 34
-107 0 -7 3 -11 7 -8 4 2 16 -14 26 -37 39 -87 102 -134 337 -252 119 -60 217
-113 217 -118 0 -5 4 -7 8 -4 5 3 23 -4 40 -14 l32 -20 0 310 c0 288 -1 311
-18 328 -10 9 -25 18 -33 20 -7 2 -17 9 -21 15 -4 7 -8 8 -8 4 0 -11 -100 45
-100 56 0 5 -4 6 -9 2 -5 -3 -17 2 -25 11 -9 8 -16 13 -16 9 0 -3 -22 6 -50
22 -27 15 -50 32 -50 36 0 5 -4 6 -9 2 -5 -3 -16 2 -25 10 -8 9 -19 16 -23 16
-15 2 -88 42 -95 53 -4 6 -8 7 -8 2 0 -5 -7 -3 -15 4 -9 7 -37 24 -63 37 -26
13 -67 35 -92 48 -25 13 -49 24 -54 24 -4 1 -6 -89 -2 -201z"/>
                        <path d="M1294 4365 c2 -6 -2 -19 -10 -30 -9 -13 -11 -22 -3 -31 7 -8 5 -11
-6 -10 -11 0 -50 -50 -111 -144 -53 -80 -101 -157 -109 -171 -14 -25 -14 -25
121 -108 l134 -82 43 26 c24 14 47 22 52 17 4 -4 6 -2 2 3 -3 6 35 35 84 64
88 52 99 63 78 76 -6 4 -8 14 -4 23 3 9 3 13 -1 9 -11 -10 -116 151 -108 166
4 7 4 9 -1 5 -12 -11 -97 120 -89 135 4 7 3 9 -1 4 -9 -7 -47 42 -40 53 2 3
-5 6 -16 6 -11 0 -18 -5 -15 -11z"/>
                        <path d="M5430 4165 l-136 -211 137 -83 136 -82 136 83 135 82 -24 42 c-13 22
-23 43 -21 45 1 2 0 3 -2 1 -10 -5 -201 300 -195 310 4 6 2 8 -4 4 -6 -3 -14
0 -19 7 -6 10 -49 -51 -143 -198z"/>
                        <path d="M1917 3864 c-15 -8 -25 -19 -22 -24 3 -6 0 -7 -8 -4 -7 2 -116 -54
-241 -126 -126 -71 -233 -127 -239 -123 -6 3 -7 1 -3 -5 4 -7 2 -12 -3 -12 -8
0 -11 -338 -11 -1120 l0 -1120 65 0 65 0 0 204 0 203 88 53 c290 177 342 212
370 250 16 22 33 36 37 32 5 -4 5 -2 2 4 -4 7 5 38 20 70 25 55 28 71 31 217
2 86 1 157 -2 157 -3 0 -121 -70 -263 -156 -141 -86 -263 -158 -270 -161 -10
-4 -13 22 -13 128 l0 134 183 96 c209 109 237 136 280 265 13 41 28 70 32 66
4 -4 5 4 2 18 -4 14 -7 82 -7 151 l0 127 -227 -125 c-125 -69 -235 -126 -245
-128 -17 -4 -18 4 -16 127 l3 130 130 68 c154 81 205 122 239 193 44 90 57
162 54 300 l-3 125 -28 -14z"/>
                        <path d="M5923 3723 l-273 -155 0 -1119 0 -1119 65 0 65 0 0 204 0 204 143 87
c78 48 165 101 192 118 158 96 215 216 215 449 0 110 -2 129 -15 125 -8 -4
-129 -76 -270 -162 -140 -85 -257 -155 -260 -155 -3 0 -5 60 -5 133 l0 133 48
25 c26 14 104 56 174 93 135 71 186 116 214 186 9 23 21 37 27 33 6 -3 7 -1 3
6 -9 13 2 73 12 67 4 -2 9 68 10 156 l4 160 -24 -13 c-37 -20 -379 -209 -425
-235 l-43 -25 0 138 0 138 103 52 c177 91 204 110 242 166 59 86 77 161 81
325 2 78 0 142 -4 142 -4 -1 -130 -71 -279 -157z"/>
                        <path d="M2491 3630 c1 -121 5 -217 9 -215 10 6 23 -42 15 -55 -3 -4 -2 -10 2
-12 5 -1 24 -34 43 -72 57 -113 97 -146 354 -286 126 -69 235 -131 243 -138 7
-7 13 -11 13 -7 0 5 42 -17 128 -67 l32 -20 0 315 0 315 -30 12 c-16 7 -27 17
-23 23 3 5 1 7 -4 4 -13 -8 -103 40 -103 54 0 6 -4 8 -9 4 -5 -3 -17 2 -25 11
-9 8 -16 13 -16 9 0 -3 -13 2 -29 11 -17 10 -28 21 -25 25 2 4 -4 7 -13 7 -23
-1 -105 46 -96 55 3 4 -3 7 -15 7 -12 0 -22 5 -22 12 0 6 -3 9 -6 5 -9 -8 -64
21 -64 34 0 6 -4 8 -9 4 -5 -3 -17 3 -27 12 -10 10 -20 17 -23 17 -10 -2 -51
19 -51 26 0 4 -6 7 -12 7 -21 1 -99 49 -91 56 3 4 -1 7 -11 7 -9 0 -41 14 -71
30 -30 17 -57 30 -60 30 -3 0 -5 -99 -4 -220z"/>
                        <path d="M4960 3721 c0 -145 11 -211 47 -288 44 -91 74 -115 274 -218 103 -52
189 -95 193 -95 3 0 7 42 8 93 l2 92 6 -90 c5 -66 7 -33 8 128 1 143 -1 216
-8 212 -5 -3 -10 -2 -10 4 0 5 -98 62 -217 126 -120 65 -237 128 -260 142
l-43 24 0 -130z m527 -203 c-3 -8 -6 -5 -6 6 -1 11 2 17 5 13 3 -3 4 -12 1
-19z m0 -50 c-3 -7 -5 -2 -5 12 0 14 2 19 5 13 2 -7 2 -19 0 -25z"/>
                        <path d="M709 3793 c-3 -83 1 -206 7 -252 8 -60 57 -161 97 -201 18 -18 116
-75 225 -130 l192 -98 0 229 c0 154 -3 229 -11 229 -5 0 -7 5 -3 12 5 7 3 8
-6 3 -8 -5 -18 -2 -26 9 -7 10 -16 17 -21 16 -19 -3 -102 44 -96 54 3 6 3 8
-2 4 -10 -9 -145 59 -145 74 0 6 -3 8 -6 5 -4 -4 -49 16 -100 43 -52 28 -96
50 -99 50 -3 0 -6 -21 -6 -47z"/>
                        <path d="M608 3055 c-2 -74 1 -135 5 -135 5 0 7 -8 6 -17 -2 -19 0 -27 37
-119 42 -104 77 -129 472 -341 l102 -55 0 226 0 225 -40 22 c-21 12 -36 27
-33 32 3 6 1 7 -5 3 -14 -9 -115 48 -104 59 4 4 2 5 -4 2 -6 -4 -81 33 -165
81 -85 48 -180 102 -211 120 l-57 32 -3 -135z"/>
                        <path d="M4854 3070 c0 -69 1 -97 3 -62 2 34 2 90 0 125 -2 34 -3 6 -3 -63z"/>
                        <path d="M4870 3051 c0 -197 36 -313 120 -383 47 -40 484 -278 488 -267 2 5 8
7 13 3 6 -3 8 79 7 212 l-3 218 -100 56 c-55 31 -196 111 -312 177 l-213 121
0 -137z"/>
                        <path d="M2403 2763 c5 -218 14 -262 71 -378 51 -104 109 -156 289 -264 86
-52 157 -99 157 -104 0 -6 3 -8 6 -4 6 6 10 3 244 -138 80 -48 148 -90 153
-92 4 -2 7 135 7 305 l0 310 -65 38 c-36 21 -65 42 -65 47 0 4 -4 6 -9 3 -5
-3 -22 7 -39 22 -16 15 -34 26 -39 25 -5 -2 -22 9 -38 24 -16 15 -25 22 -21
15 9 -15 -156 84 -165 100 -3 5 -8 9 -10 8 -12 -5 -57 25 -52 34 3 6 3 8 -2 4
-9 -9 -65 19 -65 32 0 6 -3 9 -7 8 -13 -3 -63 24 -58 32 2 4 -2 7 -8 7 -20 0
-157 84 -157 96 0 6 -3 8 -6 4 -3 -3 -23 4 -43 17 -20 13 -47 29 -60 35 l-23
13 5 -199z"/>
                        <path d="M4484 2785 c0 -99 2 -138 3 -87 2 51 2 132 0 180 -1 48 -3 6 -3 -93z"/>
                        <path d="M4803 2381 c6 -154 17 -207 63 -291 39 -72 72 -98 269 -218 99 -59
214 -129 256 -155 42 -26 84 -47 93 -47 14 0 16 23 16 210 0 187 -2 213 -17
229 -10 10 -161 104 -335 210 -175 106 -318 196 -318 200 0 4 -7 8 -16 8 -15
0 -16 -16 -11 -146z"/>
                        <path d="M550 2388 c0 -141 11 -202 50 -278 45 -90 73 -113 292 -245 117 -71
240 -146 273 -167 l60 -38 3 223 c2 211 2 223 -17 238 -11 9 -18 19 -16 21 3
3 -1 5 -8 5 -8 0 -151 84 -319 186 -168 103 -309 187 -312 187 -3 0 -6 -60 -6
-132z"/>
                    </g>
                </svg>
            </a>
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
            <div class="filter-button-wrapper" style="display: none">
                <button class="action-button filter jsFilter"><span>Filter</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-filter">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="products-area-wrapper tableView">
            <div class="products-header">
                <div class="product-cell image projector">{{ __('Teams') }}</div>
                <div class="product-cell sales projector">{{ __('Scores') }}</div>
                <div class="product-cell price projector">{{ __('Tasks') }}</div>
            </div>
            <div class="Product-body">

            </div>
        </div>
    </div>
    <style>
        .tableView .product-cell img {
            width: 64px;
            height: 64px;
            border-radius: 6px;
            margin-right: 6px;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('js/Slidebars/SlidebarGuest.js') }}"></script>
    <script type="text/javascript">
        const data = {!! json_encode($M) !!};
        const checktasks = {!! json_encode(\App\Models\CheckTasks::all()) !!};
        const desidedteams = {!! json_encode(\App\Models\CompletedTaskTeams::all()) !!};
        const svg = `{!! view('SVG.GuestSVG') !!}`;
        // console.log(desidedteams);
        // console.log(data);
        // console.log(checktasks);
        for (let i = 0; i < data.length; i++) {
            if (data[i].guest == 'Yes') {
                data[i].GuestLogo = svg;
            }
        }
        for (let i = 0; i < data.length; i++) {
            data[i].style = '';
        }

        const sortedData = data.sort((a, b) => b.scores - a.scores);
        //console.log(sortedData);

        const divElement = document.querySelector('.Product-body');

        let count = 0;
        let check = false;
        const userStyles = {};
        desidedteams.forEach(team => {
            if (!userStyles[team.user_id]) {
                userStyles[team.user_id] = [];
            }
            userStyles[team.user_id].push(updateStyles(team.StyleTask));
        });

        // Обрабатываем массив Teams
        data.forEach(team => {
            const user_id = team.id; // Предполагается, что id команды совпадает с id пользователя
            if (userStyles[user_id]) {
                team.style = userStyles[user_id].join(''); // Присваиваем стили команде
            }
        });

        //console.log(data);
        MakeHTML(data, divElement);

        Echo.channel(`channel-projector`).listen('ProjectorEvent', (e) => {
            const valueToDisplay = e.projector;
            const data = valueToDisplay.Teams;
            const desidedteams = valueToDisplay.DesidedT;


            console.log('Принято!');
            for (let i = 0; i < data.length; i++) {
                if (data[i].guest == 'Yes') {
                    data[i].GuestLogo = svg;
                }
            }
            for (let i = 0; i < data.length; i++) {
                data[i].style = '';
            }

            count = 0;
            const userStyles = {};
            desidedteams.forEach(team => {
                if (!userStyles[team.user_id]) {
                    userStyles[team.user_id] = [];
                }
                userStyles[team.user_id].push(updateStyles(team.StyleTask));
            });

            // Обрабатываем массив Teams
            data.forEach(team => {
                const user_id = team.id; // Предполагается, что id команды совпадает с id пользователя
                if (userStyles[user_id]) {
                    team.style = userStyles[user_id].join(''); // Присваиваем стили команде
                }
            });
            const sortedData = data.sort((a, b) => b.scores - a.scores);

            console.log(sortedData);
            MakeHTML(sortedData, divElement);
            //console.log(e.test);
        });

        function MakeHTML(Data, Element) {
            const host = window.location.hostname;
            const protocol = window.location.protocol;
            const port = window.location.port;
            const url = protocol + '//' + host + ':' + port + '/storage/teamlogo/';
            const html0 = `<div class="products-header">
                <div class="product-cell image projector">{{ __('Teams') }}<button class="sort-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
                <div class="product-cell sales projector">{{ __('Scores') }}<button class="sort-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
                <div class="product-cell price projector">{{ __('Tasks') }}<button class="sort-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                    </button></div>
            </div>`;
            const html1 = Data.map(item => `
            <div class="products-row" ${item.BorderStyle}>
                <div class="product-cell image projector">
                    <img class="logo_sc" src="${url + item.teamlogo}" alt="product">
                    <span>${item.name} ${item.guest !== 'No' ? '<div class="guest-badge">{{ __('GUEST') }}</div>' : ''}</span>
                </div>
                <div class="product-cell sales projector" style="font-size: 25px;font-weight: 999;"><span class="cell-label">{{ __('Scores') }}:</span>${item.scores}</div>
                <div class="product-cell price projector" style="display: flex; flex-wrap: wrap;"><span class="cell-label">{{ __('Tasks') }}:</span>
                    ${item.style}
                </div>
            </div>
        `).join("");
            const HTML = html1;
            Element.innerHTML = HTML;
        }

        function updateStyles(inputStr) {
            return inputStr.replace(/style="([^"]*)"/, (match, styles) => {
                // Разбиваем стили на массив пар "ключ: значение"
                let stylesArray = styles.split(';').map(style => style.trim()).filter(style => style);

                // Изменяем значения стилей
                let newStylesArray = stylesArray.map(style => {
                    let [property, value] = style.split(':').map(s => s.trim());

                    // Меняем значения по вашему усмотрению
                    switch (property.toLowerCase()) {
                        case 'border-radius':
                            value = '10px'; // Новый радиус границы
                            break;
                        case 'text-align':
                            value = 'left'; // Новое выравнивание текста
                            break;
                        case 'width':
                            value = '16px'; // Новая ширина
                            break;
                        case 'height':
                            value = '40px'; // Новая высота
                            break;
                        case'margin-right':
                            value = '4px'; // Новый отступ справа
                            break;
                        default:
                            break;
                    }

                    return `${property}: ${value}`;
                });

                // Объединяем обновленные стили в строку
                return `style="${newStylesArray.join('; ')}"`;
            });
        }

        switchgrid.addEventListener('click', () => {
            const images = document.querySelectorAll('.gridView .product-cell img');

            images.forEach(img => {
                img.style.width = ''; // Новая ширина
                img.style.height = ''; // Новая высота
            });
        });

        switchlist.addEventListener('click', () => {
            const images = document.querySelectorAll('.tableView .product-cell img');

            // Изменяем стили для каждого изображения
            const TableS = localStorage.getItem('TableStyle');
            if (TableS !== 'gridView') {
                images.forEach(img => {
                    img.style.width = '60px'; // Новая ширина
                    img.style.height = '60px'; // Новая высота
                });
            }
        });
        const images = document.querySelectorAll('.tableView .product-cell img');

        // Изменяем стили для каждого изображения
        const TableS = localStorage.getItem('TableStyle');
        if (TableS !== 'gridView') {
            images.forEach(img => {
                img.style.width = '60px'; // Новая ширина
                img.style.height = '60px'; // Новая высота
            });
        }
    </script>
    <script>
        const sidebar = document.querySelector('.sidebar');
        sidebar.style.display = 'none';

        function reloadPage() {
            location.reload();
        }

        // Вызов функции reloadPage каждые 5 секунд
        setInterval(reloadPage, 5000);
    </script>
@endsection
