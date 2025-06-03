@extends('layouts.noneslidebar')

@section('css')
    <link rel="stylesheet" href="{{ asset('style/css/Notif.css') }}">
    <link rel="stylesheet" href="{{ asset('style/scss/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('style/scss/CheckBox.css') }}">
@endsection

@section('title', 'AltayCTF-Admin')


@section('appcontent')
    <div class="app-content">
        <div class="app-content-header">
            <h1 class="app-content-headerText"></h1>
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
        <div class="products-area-wrapper tableView">
            <form class="form" autocomplete="off" action="/Admin/Auth" method="post">
                @csrf
                <input type="hidden" name="name" value="admin">
                <div class="control">
                    <h1 class="app-content-headerText">
                        Sign In
                    </h1>
                    <div class="container">
                        <label for="checkbox-1">
                            <input type="checkbox" id="remember" name="remember"/>
                            Запомнить меня
                        </label>
                    </div>
                </div>
                <div class="control block-cube block-input ">
                    <input style="color: #959191" name="password" type="password" placeholder="PassWord"/>
                    <div class="bg-top" style="background-color: #959191; color: #676666" >
                        <div class="bg-inner"></div>
                    </div>
                    <div class="bg-right" style="background-color: #959191; color: #676666">
                        <div class="bg-inner"></div>
                    </div>
                    <div class="bg" style="background-color: #959191; color: #676666">
                        <div class="bg-inner"></div>
                    </div>
                </div>
                <button class="btn block-cube block-cube-hover" type="submit">
                    <div class="bg-top">
                        <div class="bg-inner"></div>
                    </div>
                    <div class="bg-right">
                        <div class="bg-inner"></div>
                    </div>
                    <div class="bg">
                        <div class="bg-inner"></div>
                    </div>

                    <div class="text" >
                        Log In
                    </div>
                </button>
            </form>
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
    </div>
@endsection

@section('scripts')

@endsection


