@extends('layouts.guest')

@section('css')
    <link rel="stylesheet" href="{{ asset('style/scss/auth.css') }}">
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
        <div class="topmost-div-rul">
            <div style="text-align: center;"><h1 class="TaskH1">{{ __('Rules') }}</h1></div>
            <div class="rul">
                {!! $sett !!}
                <h1></h1>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    Echo.channel(`channel-updaterules-guest`).listen('UpdateRulesEvent', (e) => {
        const valueToDisplay = e.data;
        console.log('Принято!');
        const divElement = document.querySelector('.rul');

        let taskcomplexity = localStorage.getItem('taskcomplexityAdmin');
        let taskcategory = localStorage.getItem('taskcategoryAdmin');
        // Filtereed(valueToDisplay, taskcomplexity, taskcategory);
        MakeHTML(valueToDisplay, divElement)
        function MakeHTML(Data, Element) {
            const HTML = `${Data}`;
            Element.innerHTML = HTML;
        }
    });
</script>
@endsection

