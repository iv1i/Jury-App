@inject('settings', 'App\Services\SettingsService')
@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('style/css/Notif.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/Settings.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('title', 'AltayCTF-Admin')

@section('appcontent')
    <div class="notifications">
        <div  class="toast ">
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
        <!-- Инструкция-оверлей (показывается только при первом посещении) -->
        <div id="instructionOverlay" class="instruction-overlay" style="display: none;">
            <div class="instruction-box">
                <h1>Инструкция по управлению настройками</h1>
                <ul>
                    <li><strong>Раздел {!! __('Guest') !!}:</strong> Управление видимостью разделов гостя</li>
                    <ul>
                    <li><strong>{{ __('Rules') }}:</strong> Включение/отключение отображения правил</li>
                    <li><strong>{{ __('Projector') }}:</strong> Включение/отключение отображения проектора</li>
                    </ul>


                    <li><strong>Раздел {!! __('App') !!}:</strong> Управление видимостью разделов приложения</li>
                    <ul>
                        <li><strong>{{ __('Home') }}/{{ __('Scoreboard') }}/{{ __('Statistics') }}:</strong> Показывать или скрывать соответствующие разделы</li>
                        <li><strong>{{ __('Logout') }}:</strong> Показывать или скрывать кнопку выхода</li>
                    </ul>
                    <li><strong>Раздел {!! __('Auth') !!}:</strong> Управление типом авторизации приложения</li>
                    <ul>
                        <li><strong>{{ __('Token Authorization') }}:</strong> Включение/отключение авторизации по токену</li>
                    </ul>


                    <li><strong>Раздел {!! __('Tools') !!}:</strong> Опасные операции</li>
                    <ul>
                        <li><strong>{{ __('Reset') }}:</strong> Сброс итогов соревнований</li>
                        <li><strong>{{ __('Delete All') }}:</strong> Удаление всех данных (используйте с осторожностью!)</li>
                        <li><strong>{{ __('Change the rules') }}:</strong> Редактирование текста правил</li>
                        <li><strong>{{ __('Add/Delete Category') }}:</strong> Добавление/удаление категорий</li>
                    </ul>

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
            <!-- Настройки гостей -->
            <div class="settings-card">
                <h2 class="settings-card-title">{!! __('Guest') !!} /sidebar</h2>

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
                <h2 class="settings-card-title">{!! __('App') !!} /sidebar</h2>

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

            <!-- Настройки авторизации -->
            <div class="settings-card">
                <h2 class="settings-card-title">{!! __('Auth') !!}</h2>
                <div class="settings-item">
                    <span class="settings-label">{{ __('Token Authorization') }}</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="TokenAuth" class="TokenAuth CHECKBOX" name="TokenAuth">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Инструменты -->
            <div class="settings-card">
                <h2 class="settings-card-title">{!! __('Tools') !!}</h2>

                <form method="post" id="MyFormReset" action="{{ route('Admin-Settings-Reset') }}">
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

                <form method="post" id="MyFormDeleteAll" action="{{ route('Admin-Settings-DeleteAll') }}">
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

                <button id="addCategoryButton" class="settings-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    {{ __('Add/Delete Category') }}
                </button>
            </div>
        </div>
    </div>
    <!-- Модальное окно для редактирования правил -->
    <div id="rulesModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('Edit Rules') }}</h3>
                <button class="modal-close">&times;</button>
            </div>

            <form method="post" id="MyFormChngRules" action="{{ route('Admin-Settings-Сhange-Rules') }}" enctype="multipart/form-data">
                @csrf
                <input type="checkbox" id="delivery_3" value="Yes" name="check" style="display: none;">
                <input type="hidden" value="CHNGRULL" name="ButtonChangeRull">

                <textarea name="Rull" id="TextAreaRull" class="rules-editor" spellcheck="false">{{ $Rules }}</textarea>

                <div class="modal-footer">
                    <button type="button" class="settings-button" id="cancelRulesButton">
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

    <!-- Модальное окно для добавления категории и сложности -->
    <div id="categoryModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('Add/Delete Category') }}</h3>
                <button class="modal-close">&times;</button>
            </div>

            <div class="modal-body-container">
                <!-- Existing Categories Block -->
                <div class="categories-list-block">
                    <h4 class="categories-list-title">{{ __('Existing Categories') }}</h4>
                    <div>
                        <div class="categories-list-scroll">
                            @foreach($settings->get('categories') as $category)
                                <div class="category-tag">{{ $category }}</div>
                            @endforeach
                        </div>
                        <div class="categories-list-scroll list-button-block">
                            <div class="clear-button">clear</div>
                        </div>
                    </div>
                </div>

                <!-- Form Block -->
                <form method="post" id="MyFormAddCategory" action="{{ route('Admin-Settings-Сhange-Categories') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="checkbox" id="delivery_4" value="Yes" name="check" style="display: none;">
                    <input type="hidden" value="ADDCATEGORY" name="ButtonAddCategory">

                    <div class="form-group">
                        <label for="categoryName">{{ __('Category') }}</label>
                        <input type="text" id="categoryName" name="categoryName" class="form-input" placeholder="{{ __('Enter category name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="difficultyName">{{ __('Action') }}</label>
                        <select class="form-input" name="command" id="command">
                            <option value="add" selected>add</option>
                            <option value="delete">delete</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="settings-button" id="cancelCategoryButton">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="settings-button" id="saveCategoryButton">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1H2z"/>
                            </svg>
                            {{ __('Update') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="modal-footer-info-text">
                <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                </svg>
                <p>{{ __('When you delete a category, all related tasks will also be deleted! Save the required tasks by moving them to other categories.') }}<p/>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/Other/Notifications.js') }}"></script>
    <script id="V3">
        //--------------------------------Init-Of-Data
        const token = '{!! csrf_token() !!}';

        //--------------------------------Functions
        // Функция для асинхронной отправки форм
        async function submitFormAsync(form, buttonName, buttonValue) {
            try {
                const formData = new URLSearchParams();
                formData.append('check', 'Yes');
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
                    return data;
                }
            } catch (error) {
                console.log(error);
                showToast('error', 'Ошибка', data.message || 'Произошла ошибка при отправке запроса');
            }
        }
        function listCategoriesEdit(){
            const categoryTags = document.querySelectorAll('.category-tag');
            const clearTagsbutton = document.querySelector('.clear-button');
            const categoryInput = document.getElementById('categoryName');
            const commandSelect = document.getElementById('command');

            clearTagsbutton.addEventListener('click', function() {
                // Set input value
                categoryInput.value = '';

                // Auto-select delete option
                commandSelect.value = 'add';

                // Focus the input
                categoryInput.focus();
                categoryTags.forEach(t => t.classList.remove('category-tag-selected'));
            });

            categoryTags.forEach(tag => {
                // Add pointer cursor
                tag.style.cursor = 'pointer';

                // Add click handler
                tag.addEventListener('click', function() {
                    const categoryName = this.textContent.trim();

                    // Set input value
                    categoryInput.value = categoryName;

                    // Auto-select delete option
                    commandSelect.value = 'delete';

                    // Focus the input
                    categoryInput.focus();

                    // Optional: Highlight the selected tag
                    categoryTags.forEach(t => t.classList.remove('category-tag-selected'));
                    this.classList.add('category-tag-selected');
                });
            });

            // Optional: Clear selection when input is modified manually
            categoryInput.addEventListener('input', function() {
                categoryTags.forEach(t => t.classList.remove('category-tag-selected'));
            });
        }

        //--------------------------------Other
        // Инициализация всех форм на странице
        document.addEventListener('DOMContentLoaded', function() {
            const SettingsSidebar = {!! json_encode($SettSidebar) !!};
            const TypeAuth = {!! json_encode($TypeAuth) !!};
            const checkboxes0 = document.querySelectorAll('.CHECKBOX');
            const checkboxauth = document.getElementById('TokenAuth');

            listCategoriesEdit();

            // Инициализация переключателей
            checkboxes0.forEach(checkbox => {
                let name = checkbox.id;
                if(name === 'logout') {
                    name = 'Logout'
                }
                const settingValue = SettingsSidebar[name];
                checkbox.checked = settingValue;
            });

            if (TypeAuth === 'token'){
                checkboxauth.checked = true;
                checkboxauth.setAttribute('checked', 'checked');
            }

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

                    fetch('{{ route('Admin-Settings-Sidebars') }}', {
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
                        let formData;

                        // Для форм с текстовыми полями используем FormData
                        if (form.id === 'MyFormChngRules' || form.id === 'MyFormAddCategory') {
                            formData = new FormData(form);
                            if (form.id === 'MyFormChngRules') {
                                formData.set('check', 'Yes');
                                formData.set('ButtonChangeRull', 'CHNGRULL');
                            } else if (form.id === 'MyFormAddCategory') {
                                formData.set('check', 'Yes');
                                formData.set('ButtonAddCategory', 'ADDCATEGORY');
                            }
                        } else {
                            // Для остальных форм оставляем URLSearchParams
                            formData = new URLSearchParams();
                            formData.append('check', 'Yes');

                            let buttonName, buttonValue;
                            if (form.id === 'MyFormReset') {
                                buttonName = 'ButtonReset';
                                buttonValue = 'RESET';
                            }
                            if (form.id === 'MyFormDeleteAll') {
                                buttonName = 'ButtonDeleteAll';
                                buttonValue = 'DELETEALL';
                            }
                            formData.append(buttonName, buttonValue);
                        }

                        formData.append('_token', token);

                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await response.json();

                        if (form.id === 'MyFormAddCategory') {
                            const htmlcategorylist = data.categories.map(item => `
                            <div class="category-tag">${item}</div>
                        `).join("");
                            const Element = document.querySelector('.categories-list-scroll');
                            Element.innerHTML = htmlcategorylist;
                            listCategoriesEdit();
                        }

                        if (response.ok && data.success) {
                            showToast('success', 'Успех', data.message || 'Операция выполнена успешно');
                        } else {
                            showToast('error', 'Ошибка', data.message || 'Произошла ошибка');
                        }
                    } catch (error) {
                        showToast('error', 'Ошибка', 'Произошла ошибка при отправке запроса');
                        console.log(error);
                    } finally {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    }
                });
            });

            // Управление модальным окном для правил
            const appContent = document.querySelector('.app-content');
            const rulesModal = document.getElementById('rulesModal');
            const editRulesButton = document.getElementById('editRulesButton');
            const cancelRulesButton = document.getElementById('cancelRulesButton');
            const modalClose = document.querySelector('.modal-close');

            // Открытие модального окна
            editRulesButton.addEventListener('click', function() {
                rulesModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                appContent.style.filter = 'blur(4px)';
            });

            // Закрытие модального окна
            function closeModal() {
                rulesModal.classList.remove('active');
                document.body.style.overflow = '';
                appContent.style.filter = '';

            }

            cancelRulesButton.addEventListener('click', closeModal);
            modalClose.addEventListener('click', closeModal);

            // Закрытие при клике вне модального окна
            rulesModal.addEventListener('click', function(e) {
                if (e.target === rulesModal) {
                    closeModal();
                }
            });

            // Управление модальным окном для категорий
            const categoryModal = document.getElementById('categoryModal');
            const addCategoryButton = document.getElementById('addCategoryButton');
            const cancelCategoryButton = document.getElementById('cancelCategoryButton');
            const categoryModalClose = document.querySelector('#categoryModal .modal-close');

            // Открытие модального окна
            addCategoryButton.addEventListener('click', function() {
                categoryModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                appContent.style.filter = 'blur(4px)';
            });

            // Закрытие модального окна
            function closeCategoryModal() {
                categoryModal.classList.remove('active');
                document.body.style.overflow = '';
                appContent.style.filter = '';
            }

            cancelCategoryButton.addEventListener('click', closeCategoryModal);
            categoryModalClose.addEventListener('click', closeCategoryModal);

            // Закрытие при клике вне модального окна
            categoryModal.addEventListener('click', function(e) {
                if (e.target === categoryModal) {
                    closeCategoryModal();
                }
            });

            // Закрытие по ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (rulesModal.classList.contains('active')) {
                        closeModal();
                    }
                    if (categoryModal.classList.contains('active')) {
                        closeCategoryModal();
                    }
                }
            });

            // Автоматическая высота textarea
            const textarea = document.getElementById('TextAreaRull');
            const categoryDescription = document.getElementById('categoryDescription');

            function adjustTextareaHeight(element) {
                element.style.height = 'auto';
                const maxHeight = window.innerHeight * 0.6;
                const newHeight = Math.min(element.scrollHeight, maxHeight);
                element.style.height = `${newHeight}px`;
            }

            if (textarea) {
                textarea.addEventListener('input', () => adjustTextareaHeight(textarea));
                window.addEventListener('resize', () => adjustTextareaHeight(textarea));
                adjustTextareaHeight(textarea);
            }

            if (categoryDescription) {
                categoryDescription.addEventListener('input', () => adjustTextareaHeight(categoryDescription));
                window.addEventListener('resize', () => adjustTextareaHeight(categoryDescription));
                adjustTextareaHeight(categoryDescription);
            }
        });
    </script>
@endsection
