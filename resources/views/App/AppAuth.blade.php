@inject('settings', 'App\Services\SettingsService')

@extends('layouts.guest')

@section('css')
    <link rel="stylesheet" href="{{ asset('style/css/Notif.css') }}">
    <link rel="stylesheet" href="{{ asset('style/scss/auth.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('title', 'AltayCTF-Sch-Auth')

@section('appcontent')
    <div class="notifications">
        <div class="toast ">
            <div  class="toast-content">
                <i class="fas fa-solid fa-check check"></i>

                <div class="message">
                    <span class="text text-1"></span>
                    <span class="text text-2"></span>
                    <span class="text text-3"></span>
                </div>
            </div>
            <i style="color: var(--app-bg-inv)" class="fa-solid fa-xmark close">
            </i>
            <style>
                .toast .progress:before {
                    background-color: #f4406a;
                }
            </style>
            <!-- Remove 'active' class, this is just to show in Codepen thumbnail -->
            <div  class="progress"></div>
        </div>
    </div>
    <div class="app-content">
        <div id="FormSwitchTheme" class="app-content-header">
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
            <form class="form" autocomplete="off" method="post" action="/Auth">
                @csrf
                <div class="control">
                    <h1 class="app-content-headerText">
                        {{ __('Sign In') }}
                    </h1>
                </div>
                @if($settings->get('auth') ==='base')
                    <div class="control block-cube block-input ">
                        <input style="color: #959191" name="name" type="text" placeholder="{{ __('Team') }}"/>
                        <div class="bg-top" style="background-color: #959191; color: #676666">
                            <div class="bg-inner"></div>
                        </div>
                        <div class="bg-right" style="background-color: #959191; color: #676666">
                            <div class="bg-inner"></div>
                        </div>
                        <div class="bg" style="background-color: #959191; color: #676666">
                            <div class="bg-inner"></div>
                        </div>
                    </div>
                    <div class="control block-cube block-input ">
                        <input style="color: #959191" name="password" type="password" placeholder="{{ __('Password') }}"/>
                        <div class="bg-top" style="background-color: #959191; color: #676666">
                            <div class="bg-inner"></div>
                        </div>
                        <div class="bg-right" style="background-color: #959191; color: #676666">
                            <div class="bg-inner"></div>
                        </div>
                        <div class="bg" style="background-color: #959191; color: #676666">
                            <div class="bg-inner"></div>
                        </div>
                    </div>
                @endif
                @if($settings->get('auth') ==='token')
                    <div class="control block-cube block-input ">
                        <input style="color: #959191" name="token" type="password" placeholder="{{ __('Token') }}"/>
                        <div class="bg-top" style="background-color: #959191; color: #676666">
                            <div class="bg-inner"></div>
                        </div>
                        <div class="bg-right" style="background-color: #959191; color: #676666">
                            <div class="bg-inner"></div>
                        </div>
                        <div class="bg" style="background-color: #959191; color: #676666">
                            <div class="bg-inner"></div>
                        </div>
                    </div>
                @endif
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

                    <div class="text">
                        {{ __('Log In') }}
                    </div>
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/Other/Notifications.js') }}"></script>
    <script>
        async function submitAuthFormAsync(form) {
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const textBlock = submitButton.querySelector('.text');
            const originalButtonText = textBlock.innerHTML;

            try {
                submitButton.disabled = true;
                textBlock.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Проверка...';

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json', // Явно указываем, что ждём JSON
                    },
                });

                const data = await response.json();

                if (data.success) {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url; // Редирект
                        return;
                    }
                    showToast('success', 'Успех', data.message || 'Успешная авторизация');
                } else {
                    showToast('error', 'Ошибка', data.message || 'Неверные данные');
                }
            } catch (error) {
                console.error('Ошибка:', error);
                showToast('error', 'Ошибка', 'Ошибка сети или сервера');
            } finally {
                submitButton.disabled = false;
                textBlock.innerHTML = originalButtonText;
            }
        }


        document.querySelector(`form`).addEventListener('submit', async function(event) {
            event.preventDefault();
            await submitAuthFormAsync(this);
        });
</script>
@endsection

