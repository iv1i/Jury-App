@extends('layouts.guest')

@section('css')
    <link rel="stylesheet" href="{{ asset('style/scss/auth.css') }}">
    <style>
        /* Rules Container Styles */
        .rules-container {
            background-color: var(--app-bg-2);
            border-radius: 12px;
            padding: 24px;
            margin: 0px 85px 0px 20px;
            box-shadow: var(--filter-shadow);
            border: 1px solid var(--app-border-color);
            color: var(--app-content-main-color);
            position: absolute;
        }

        .rules-header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--table-border);
        }

        .rules-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--app-content-main-color);
            margin: 0;
        }

        .rules-content {
            line-height: 1.6;
            font-size: 15px;
        }

        .rules-content h1,
        .rules-content h2,
        .rules-content h3,
        .rules-content h4,
        .rules-content h5,
        .rules-content h6 {
            color: var(--app-content-main-color);
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .rules-content h1 {
            font-size: 20px;
            border-bottom: 1px solid var(--table-border);
            padding-bottom: 8px;
        }

        .rules-content h2 {
            font-size: 18px;
        }

        .rules-content p {
            margin-bottom: 16px;
        }

        .rules-content ul,
        .rules-content ol {
            margin-bottom: 16px;
            padding-left: 24px;
        }

        .rules-content li {
            margin-bottom: 8px;
        }

        .rules-content a {
            color: var(--action-color);
            text-decoration: none;
            transition: color 0.2s;
        }

        .rules-content a:hover {
            color: var(--action-color-hover);
            text-decoration: underline;
        }

        .rules-content code {
            background-color: var(--app-bg-tasks);
            padding: 2px 4px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 14px;
        }

        .rules-content pre {
            background-color: var(--app-bg-tasks);
            padding: 12px;
            border-radius: 6px;
            overflow-x: auto;
            margin-bottom: 16px;
        }

        .rules-content blockquote {
            border-left: 4px solid var(--action-color);
            padding-left: 16px;
            margin-left: 0;
            color: var(--app-content-main-color);
            opacity: 0.8;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .rules-container {
                margin: 10px;
                padding: 16px;
            }

            .rules-title {
                font-size: 20px;
            }

            .rules-content {
                font-size: 14px;
            }
        }
    </style>
    <script>
        {!! Vite::content('resources/js/app.js') !!}
    </script>
@endsection

@section('title', 'AltayCTF-School')


@section('appcontent')
    <div class="app-content">
        <div class="app-content-header">
            <h1 class="app-content-headerText"></h1>
            <button id="switchTheme" class="mode-switch" title="Switch Theme">
                <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                     stroke-width="2" width="24" height="24" viewBox="0 0 24 24">
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
        <div class="products-area-wrapper tableView">
        </div>
        <div class="rules-container">
            <div class="rules-header">
                <h1 class="rules-title">{{ __('Rules') }}</h1>
            </div>
            <div class="rules-content">
                {!! $sett !!}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function MakeHTML(Data, Element) {
        const HTML = `${Data}`;
        Element.innerHTML = HTML;
    }
    Echo.channel(`channel-updaterules-guest`).listen('UpdateRulesEvent', (e) => {
        const valueToDisplay = e.data;

        const divElement = document.querySelector('.rules-content');

        MakeHTML(e.data, divElement)
    });
</script>
@endsection

