@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('style/css/Notif.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/Settings.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('title', 'AltayCTF-Admin')

@section('appcontent')
    <div class="app-content settings-page">
        <div id="FormSwitchTheme" class="settings-header">
            <h1 class="app-content-headerText">{{ __('Settings') }}</h1>
            <button id="switchTheme" class="mode-switch" title="Switch Theme">
                <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                     stroke-width="2" width="24" height="24" viewBox="0 0 24 24">
                    <defs></defs>
                    <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                </svg>
            </button>
        </div>
        <div class="notifications">
                <div class="toast ">
                    <div  class="toast-content">
                        <i class="fas fa-solid fa-check check"></i>

                        <div class="message">
                            <span class="text text-1"></span>
                                <span class="text text-2"></span>
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
        <!-- Инструкция-оверлей (показывается только при первом посещении) -->
        <div id="instructionOverlay" class="instruction-overlay" style="display: none;">
            <div class="instruction-box">
                <h2>Инструкция по управлению настройками</h2>
                <ul>
                    <li><strong>Раздел {!! __('Auth') !!}:</strong> Управление видимостью разделов гостя</li>
                    <li><strong>{{ __('Rules') }}:</strong> Включение/отключение отображения правил для пользователей</li>
                    <li><strong>{{ __('Projector') }}:</strong> Включение/отключение отображения проектора</li>

                    <br />

                    <li><strong>Раздел {!! __('App') !!}:</strong> Управление видимостью разделов приложения</li>
                    <li><strong>{{ __('Home') }}/{{ __('Scoreboard') }}/{{ __('Statistics') }}:</strong> Показывать или скрывать соответствующие разделы</li>
                    <li><strong>{{ __('Logout') }}:</strong> Показывать или скрывать кнопку выхода</li>

                    <br />

                    <li><strong>Раздел {!! __('Tools') !!}:</strong> Опасные операции</li>
                    <li><strong>{{ __('Reset') }}:</strong> Сброс определенных настроек системы</li>
                    <li><strong>{{ __('Delete All') }}:</strong> Удаление всех данных (используйте с осторожностью)</li>
                    <li><strong>{{ __('Change the rules') }}:</strong> Редактирование текста правил</li>
                </ul>

                <div class="warning">
                    <b>Внимание:</b> Изменения в настройках применяются мгновенно.
                </div>

                <div class="danger">
                    <b>Осторожно:</b> Операции в разделе <i>{!! __('Tools') !!}</i> необратимы. Убедитесь в необходимости действий перед подтверждением.
                </div>

                <div class="confirm-section">
                    <div class="confirm-checkbox">
                        <input type="checkbox" id="instructionConfirmed">
                        <label for="instructionConfirmed">С правилами ознакомлен</label>
                    </div>
                    <button id="confirmButton" class="confirm-button" disabled>Продолжить</button>
                </div>
            </div>
        </div>

        <div class="settings-grid">
            <!-- Настройки авторизации -->
            <div class="settings-card">
                <h2 class="settings-card-title">{!! __('Auth') !!}</h2>

                <div class="settings-item">
                    <span class="settings-label">{{ __('Rules') }}</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="Rules" class="Rules CHECKBOX" name="Rules">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-item">
                    <span class="settings-label">{{ __('Projector') }}</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="Projector" class="Projector CHECKBOX" name="Projector">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Настройки приложения -->
            <div class="settings-card">
                <h2 class="settings-card-title">{!! __('App') !!}</h2>

                <div class="settings-item">
                    <span class="settings-label">{{ __('Home') }}</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="Home" class="Homecheckbox CHECKBOX" name="Home">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-item">
                    <span class="settings-label">{{ __('Scoreboard') }}</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="Scoreboard" class="Scoreboardcheckbox CHECKBOX" name="Scoreboard">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-item">
                    <span class="settings-label">{{ __('Statistics') }}</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="Statistics" class="Statistics CHECKBOX" name="Statistics">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-item">
                    <span class="settings-label">{{ __('Logout') }}</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="logout" class="Logout CHECKBOX" name="Logout">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Инструменты -->
            <div class="settings-card">
                <h2 class="settings-card-title">{!! __('Tools') !!}</h2>

                <form method="post" id="MyFormReset" action="{{ route('AdminSettingsReset') }}">
                    @csrf
                    <input type="checkbox" id="delivery_1" value="Yes" name="check" style="display: none;">
                    <input type="hidden" value="RESET" name="ButtonReset">
                    <button type="submit" class="settings-button danger-button" id="Reset_button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                        </svg>
                        {{ __('Reset') }}
                    </button>
                </form>

                <form method="post" id="MyFormDeleteAll" action="{{ route('AdminSettingsDeleteAll') }}">
                    @csrf
                    <input type="checkbox" id="delivery_2" value="Yes" name="check" style="display: none;">
                    <input type="hidden" value="DELETEALL" name="ButtonDeleteAll">
                    <button type="submit" class="settings-button danger-button" id="Delete_button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>
                        {{ __('Delete All') }}
                    </button>
                </form>

                <button id="editRulesButton" class="settings-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                    </svg>
                    {{ __('Edit Rules') }}
                </button>
            </div>
        </div>

        <!-- Модальное окно для редактирования правил -->
        <div id="rulesModal" class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{ __('Edit Rules') }}</h3>
                    <button class="modal-close">&times;</button>
                </div>

                <form method="post" id="MyFormChngRules" action="{{ route('AdminSettingsChngRules') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="checkbox" id="delivery_3" value="Yes" name="check" style="display: none;">
                    <input type="hidden" value="CHNGRULL" name="ButtonChangeRull">

                    <textarea name="Rull" id="TextAreaRull" class="rules-editor" spellcheck="false">{{ $Sett }}</textarea>

                    <div class="modal-footer">
                        <button type="button" class="settings-button danger-button" id="cancelRulesButton">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="settings-button" id="saveRulesButton">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1H2z"/>
                            </svg>
                            {{ __('Save Rules') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        // Выносим таймеры в область видимости функции, чтобы их можно было очищать
        let toastTimer1, toastTimer2;

        const token = '{!! csrf_token() !!}';

        function showToast(type, title, message) {
            const toast = document.querySelector(".toast");
            const toastContent = toast.querySelector(".toast-content");
            const checkIcon = toast.querySelector(".check");
            const messageText1 = toast.querySelector(".text-1");
            const messageText2 = toast.querySelector(".text-2");
            const progress = toast.querySelector(".progress");

            // Очищаем предыдущие таймеры
            clearTimeout(toastTimer1);
            clearTimeout(toastTimer2);

            // Сбрасываем анимацию прогресс-бара
            progress.classList.remove("active");
            // Принудительный рефлоу для сброса анимации
            void progress.offsetWidth;

            // Удаляем предыдущие добавленные стили
            const existingStyles = document.querySelectorAll('style[data-toast-style]');
            existingStyles.forEach(style => style.remove());

            toast.style.display = "flex";

            // Set icon and colors based on type
            if (type === 'success') {
                checkIcon.className = "fas fa-solid fa-check check";
                checkIcon.style.backgroundColor = "#40f443";
                const style = document.createElement('style');
                style.innerHTML = '.toast .progress:before { background-color: #40f443 !important; }';
                style.setAttribute('data-toast-style', 'true');
                document.head.appendChild(style);
            } else {
                checkIcon.className = "fas fa-solid fa-times check";
                checkIcon.style.backgroundColor = "#f4406a";
                const style = document.createElement('style');
                style.innerHTML = '.toast .progress:before { background-color: #f4406a !important; }';
                style.setAttribute('data-toast-style', 'true');
                document.head.appendChild(style);
            }

            messageText1.textContent = title;
            messageText2.textContent = message;

            toast.classList.add("active");

            // Запускаем анимацию прогресс-бара снова
            setTimeout(() => {
                progress.classList.add("active");
            }, 10);

            const closeIcon = document.querySelector('.close');

            if (toast) {
                toastTimer1 = setTimeout(() => {
                    toast.classList.remove('active');
                }, 5000);

                toastTimer2 = setTimeout(() => {
                    progress.classList.remove('active');
                }, 5300);

                if (closeIcon) {
                    closeIcon.removeEventListener('click', closeToast);
                    closeIcon.addEventListener('click', closeToast);
                }
            }
        }

        function closeToast() {
            const toast = document.querySelector(".toast");
            const progress = toast.querySelector(".progress");

            toast.classList.remove('active');

            setTimeout(() => {
                progress.classList.remove('active');
            }, 300);

            clearTimeout(toastTimer1);
            clearTimeout(toastTimer2);
        }

        // Функция для асинхронной отправки форм
        async function submitFormAsync(form, buttonName, buttonValue) {
            try {
                const formData = new URLSearchParams();
                formData.append('check', 'Yes');
                // formData.append('ButtonReset', 'RESET');
                formData.append(buttonName, buttonValue);
                formData.append('_token', token);
                console.log("form data - ", formData)
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showToast('success', 'Успех', data.message || 'Операция выполнена успешно');
                    return data;
                } else {
                    showToast('error', 'Ошибка', data.message || 'Произошла ошибка');
                    return Promise.reject(data);
                }
            } catch (error) {
                showToast('error', 'Ошибка', data.message || 'Произошла ошибка при отправке запроса');
                return Promise.reject(error);
            }
        }

        // Инициализация всех форм на странице
        document.addEventListener('DOMContentLoaded', function() {
            const Settings = {!! json_encode(\App\Models\Settings::find(1)) !!};
            const checkboxes0 = document.querySelectorAll('.CHECKBOX');

            // Инициализация переключателей
            checkboxes0.forEach(checkbox => {
                let name = checkbox.id;
                if(name === 'logout') {
                    name = 'Logout'
                }
                const settingValue = Settings[name];
                checkbox.checked = settingValue === 'yes';
            });

            // Показываем инструкцию только при первом посещении
            const instructionOverlay = document.getElementById('instructionOverlay');
            const confirmCheckbox = document.getElementById('instructionConfirmed');
            const confirmButton = document.getElementById('confirmButton');

            if (!localStorage.getItem('settingsInstructionSeen')) {
                instructionOverlay.style.display = 'flex';
            }

            confirmCheckbox.addEventListener('change', function() {
                confirmButton.disabled = !this.checked;
            });

            confirmButton.addEventListener('click', function() {
                instructionOverlay.style.display = 'none';
                localStorage.setItem('settingsInstructionSeen', 'true');
            });

            // Обработка переключателей
            const checkboxes = document.querySelectorAll('.CHECKBOX');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const value = checkbox.checked ? 'yes' : 'no';
                    let name = checkbox.id;
                    if(name === 'logout') name = 'Logout';

                    const formData = new URLSearchParams();
                    formData.append(name, value);
                    formData.append('_token', token);

                    fetch('{{ route('AdminSettingsSlidebars') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast('success', 'Успех', data.message || 'Настройка успешно обновлена');
                            } else {
                                showToast('error', 'Ошибка', data.message || 'Ошибка при обновлении');
                                checkbox.checked = !checkbox.checked;
                            }
                        })
                        .catch(error => {
                            showToast('error', 'Ошибка', 'Ошибка сети');
                            checkbox.checked = !checkbox.checked;
                        });
                });
            });

            // Обработка всех форм на странице
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    // Для опасных действий добавляем подтверждение
                    if (form.querySelector('.danger-button') && !confirm('Вы уверены? Это действие нельзя отменить!')) {
                        return;
                    }

                    const submitButton = form.querySelector('[type="submit"]');
                    const originalText = submitButton.innerHTML;

                    // Добавляем индикатор загрузки
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Обработка...';

                    try {
                        let buttonName, buttonValue;

                        // Определяем сообщения в зависимости от формы
                        if (form.id === 'MyFormReset') {
                            buttonName = 'ButtonReset';
                            buttonValue = 'RESET';
                        } else if (form.id === 'MyFormDeleteAll') {
                            buttonName = 'ButtonReset';
                            buttonValue = 'DELETEALL';
                        } else if (form.id === 'MyFormChngRules') {
                            buttonName = 'ButtonChangeRull';
                            buttonValue = 'CHNGRULL';
                        }

                        await submitFormAsync(form, buttonName, buttonValue);

                        // Закрываем модальное окно, если это форма редактирования правил
                        if (form.id === 'MyFormChngRules') {
                            closeModal();
                        }
                    } finally {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    }
                });
            });

            // Управление модальным окном для правил
            const rulesModal = document.getElementById('rulesModal');
            const editRulesButton = document.getElementById('editRulesButton');
            const cancelRulesButton = document.getElementById('cancelRulesButton');
            const modalClose = document.querySelector('.modal-close');

            // Открытие модального окна
            editRulesButton.addEventListener('click', function() {
                rulesModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            // Закрытие модального окна
            function closeModal() {
                rulesModal.classList.remove('active');
                document.body.style.overflow = '';
            }

            cancelRulesButton.addEventListener('click', closeModal);
            modalClose.addEventListener('click', closeModal);

            // Закрытие при клике вне модального окна
            rulesModal.addEventListener('click', function(e) {
                if (e.target === rulesModal) {
                    closeModal();
                }
            });

            // Закрытие по ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && rulesModal.classList.contains('active')) {
                    closeModal();
                }
            });

            // Автоматическая высота textarea
            const textarea = document.getElementById('TextAreaRull');

            function adjustTextareaHeight() {
                textarea.style.height = 'auto';
                const maxHeight = window.innerHeight * 0.6;
                const newHeight = Math.min(textarea.scrollHeight, maxHeight);
                textarea.style.height = `${newHeight}px`;
            }

            textarea.addEventListener('input', adjustTextareaHeight);
            window.addEventListener('resize', adjustTextareaHeight);

            // Инициализация при загрузке
            adjustTextareaHeight();
        });
    </script>
@endsection
