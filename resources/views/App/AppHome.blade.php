@inject('settings', 'App\Services\SettingsService')
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('style/css/Notif.css') }}">
    <link rel="stylesheet" href="{{ asset('style/scss/ChekFlag.css') }}">
    <link rel="stylesheet" href="{{ asset('style/scss/buttoncheckflag.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/HomeTask.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.19.0/js/md5.min.js"></script>
    <script>
        {!! Vite::content('resources/js/app.js') !!}
    </script>
    <style>
        /* Pagination Styles */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 20px 0;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pagination li {
            margin: 0;
        }

        .pagination li a,
        .pagination li span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 8px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            border: 1px solid #e5e7eb;
            background-color: white;
            color: #4b5563;
        }

        .pagination li a:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }

        .pagination li.active span {
            background-color: #6366f1;
            color: white;
            border-color: #6366f1;
        }

        .pagination li.disabled span {
            color: #9ca3af;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }

        /* Dark Mode Styles */
        .dark .pagination li a,
        .dark .pagination li span {
            background-color: #1f2937;
            border-color: #374151;
            color: #f3f4f6;
        }

        .dark .pagination li a:hover {
            background-color: #374151;
        }

        .dark .pagination li.active span {
            background-color: #818cf8;
            border-color: #818cf8;
        }

        .dark .pagination li.disabled span {
            background-color: #111827;
            border-color: #374151;
            color: #6b7280;
        }

        /* Responsive Adjustments */
        @media (max-width: 640px) {
            .pagination {
                gap: 4px;
            }

            .pagination li a,
            .pagination li span {
                min-width: 32px;
                height: 32px;
                font-size: 13px;
                padding: 0 6px;
            }
        }
    </style>
@endsection

@section('title', 'AltayCTF-Sch-Home')

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
    <div class="Tasks-Container">

    </div>
    <div class="app-content" style="filter: none;">
        <div class="app-content-header">
            <h1 class="app-content-headerText">{{ __('Home') }}</h1>
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
                        @foreach($categories as $category => $count)
                            <option>{{ $category }}</option>
                        @endforeach
                    </select>
                    <label>{{ __('Complexity') }}</label>
                    <select name="complexity" id="complexity">
                        <option value="All Complexity">{{ __('All Complexity') }}</option>
                        @foreach($complexities as $complexity => $count)
                            <option>{{ $complexity }}</option>
                        @endforeach
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
        <div class="products-area-wrapper tableView">
            <div class="products-header">
                    <div class="product-cell image">{{ __('Name') }}<button class="sort-button sort-button-Name">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                        </button></div>
                    <div class="product-cell category">{{ __('Category') }}<button class="sort-button sort-button-Category">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                        </button></div>
                    <div class="product-cell complexity">{{ __('Complexity') }}<button class="sort-button sort-button-Complexity">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                        </button></div>
                    <div class="product-cell solved">{{ __('Solved') }}<button class="sort-button sort-button-Solved">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                        </button></div>
                    <div class="product-cell price">{{ __('Price') }}<button class="sort-button sort-button-Price">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="currentColor" d="M496.1 138.3L375.7 17.9c-7.9-7.9-20.6-7.9-28.5 0L226.9 138.3c-7.9 7.9-7.9 20.6 0 28.5 7.9 7.9 20.6 7.9 28.5 0l85.7-85.7v352.8c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4V81.1l85.7 85.7c7.9 7.9 20.6 7.9 28.5 0 7.9-7.8 7.9-20.6 0-28.5zM287.1 347.2c-7.9-7.9-20.6-7.9-28.5 0l-85.7 85.7V80.1c0-11.3-9.1-20.4-20.4-20.4-11.3 0-20.4 9.1-20.4 20.4v352.8l-85.7-85.7c-7.9-7.9-20.6-7.9-28.5 0-7.9 7.9-7.9 20.6 0 28.5l120.4 120.4c7.9 7.9 20.6 7.9 28.5 0l120.4-120.4c7.8-7.9 7.8-20.7-.1-28.5z"/></svg>
                        </button></div>
            </div>
            <div class="Product-body"></div>
        </div>
        <div class="CloseTaskBanner" style="display: none; width: 100%; height: 100%; background-color: white; position:absolute; opacity: .0;" onclick="closeAllTasks()"></div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/Other/Notifications.js') }}"></script>
    <script id="TasksBlock-Script-V3">
        // Constants and DOM elements
        const divElement = document.querySelector('.Product-body');
        const CloseTaskBanner = document.querySelector('.CloseTaskBanner');
        const teamid = {{ auth()->id() }};
        const solvedtasks = {!! json_encode($SolvedTasks) !!};
        const complexityOrder = { easy: 1, medium: 2, hard: 3 };
        const sortButtons = {
            'name': '.sort-button-Name',
            'category': '.sort-button-Category',
            'complexity': '.sort-button-Complexity',
            'solved': '.sort-button-Solved',
            'price': '.sort-button-Price'
        };

        // State variables
        let data = {!! json_encode($Tasks) !!};
        console.log(data);
        let sortStates = { name: 0, category: 0, complexity: 0, solved: 0, price: 0 };
        let currentSort = { column: null, direction: 0 };
        let currentlyOpenTaskId = null;

        // Initialize data
        data = initialFilter(data, solvedtasks);
        let TASKS = JSON.parse(localStorage.getItem('data'));
        const taskcomplexity = localStorage.getItem('taskcomplexity');
        const taskcategory = localStorage.getItem('taskcategory');
        const SortedTasksCol = JSON.parse(localStorage.getItem('SortingTasksColumn'));

        // Apply sorting if exists in localStorage
        if (SortedTasksCol) {
            applySorting(data, SortedTasksCol.N, SortedTasksCol.isSort);
        }

        // Initial render
        renderFilteredTasks(data, taskcomplexity, taskcategory);

        // Event listeners
        document.getElementById('ApplyBtn').addEventListener('click', applyFilters);
        document.getElementById('ResetBtn').addEventListener('click', resetFilters);
        document.addEventListener('keydown', handleKeyDown);
        Object.entries(sortButtons).forEach(([column, selector]) => {
            document.querySelector(selector).addEventListener('click', () => handleSort(column));
        });

        // Initialize Echo listener
        initializeEchoListener();

        // Initialize existing task forms
        @foreach ($Tasks as $T)
        createTaskForm({!! $T !!});
        @endforeach

        // Core functions
        function initialFilter(data, solvedtasks) {
            // Mark solved tasks
            data.forEach(task => {
                task.decide = solvedtasks.some(solved => solved.tasks_id === task.id)
                    ? 'style="color: var(--app-bg-tasks);"'
                    : '';
            });

            // Sort by complexity and solved status
            data.sort((a, b) => {
                // First by solved status
                if (a.decide !== b.decide) return a.decide ? 1 : -1;
                // Then by complexity
                return complexityOrder[a.complexity] - complexityOrder[b.complexity];
            });

            localStorage.setItem('data', JSON.stringify(data));
            return data;
        }

        function createComparator(field, order) {
            const isReverseBase = field === 'solved';
            const baseDirection = isReverseBase ? -1 : 1;
            const direction = baseDirection * (order === 1 ? 1 : -1);

            return (a, b) => {
                const aHasDecide = a.decide?.trim() !== '';
                const bHasDecide = b.decide?.trim() !== '';

                if (aHasDecide !== bHasDecide) return aHasDecide ? 1 : -1;

                const getValue = (obj) => {
                    const value = obj[field];
                    return ['solved', 'price'].includes(field) ? Number(value) : value?.toLowerCase();
                };

                const aValue = getValue(a);
                const bValue = getValue(b);

                return aValue < bValue ? -direction : aValue > bValue ? direction : 0;
            };
        }

        function renderFilteredTasks(data, complexityFilter, categoryFilter) {
            let filteredData = [...data];

            if (complexityFilter && complexityFilter !== 'All Complexity') {
                filteredData = filteredData.filter(item => item.complexity === complexityFilter);
            }

            if (categoryFilter && categoryFilter !== 'All Categories') {
                filteredData = filteredData.filter(item => item.category === categoryFilter);
            }

            MakeHTML(filteredData);
            setSelection(complexityFilter, categoryFilter);
        }

        function MakeHTML(tasks) {
            const html = tasks.map(task => `
            <div style="cursor: pointer" class="products-row tasklink" onclick="Taskid${task.id}()">
                <div class="product-cell image" ${task.decide}>
                    <span>${task.name}</span>
                </div>
                <div class="product-cell category" ${task.decide}>
                    <span class="cell-label">{{ __('Category') }}:</span>${task.category.toUpperCase()}
                </div>
                <div class="product-cell complexity" ${task.decide}>
                    <span class="cell-label">{{ __('Complexity') }}:</span>
                    <span class="status ${task.decide}${task.complexity}">${task.complexity.toUpperCase()}</span>
                </div>
                <div class="product-cell solved" ${task.decide}>
                    <span class="cell-label">{{ __('Solved') }}:</span>${task.solved}
                </div>
                <div class="product-cell price" ${task.decide}>
                    <span class="cell-label">{{ __('Price') }}:</span>${task.price}
                </div>
            </div>
        `).join('');

            divElement.innerHTML = html;
        }

        function createTaskForm(task) {
            const existingForm = document.querySelector(`.Task-id-${task.id}`);
            if (existingForm) {
                updateExistingForm(existingForm, task);
                return;
            }

            const formHtml = `
            <div style="display: none" class="topmost-div Task-id-${task.id}">
                <div style="text-align: center; height: 3em;">
                    <h1 class="TaskH1">${task.name}</h1>
                    <div id="CloseBtn" class="btnclosetask" onclick="Taskid${task.id}close()">
                        <img class="closeicontask" src="{{ asset('/media/icon/close.png') }}">
                    </div>
                </div>
                <div class="${task.complexity} taskID_complexity">${task.complexity.toUpperCase()}</div>
                <div class="description">${task.description}</div>
                <div class="description">
                    ${task.FILES ? task.FILES.split(";").map((file, k) =>
                file ? `<a href="{{ asset('/Download/File/') }}${md5(file)}/${task.id}">Файл#${k+1}</a>` : ''
            ).join('') : ''}
                </div>
                <form id="MyFormChange${task.id}" class="MyFormSellFlag" action="/Home/Tasks/Check" method="post">
                    @csrf
            <div class="form__group field">
                <input type="input" class="form__field" placeholder="Name" name="flag" id='name${task.id}' required autocomplete="off"/>
                        <label for="name${task.id}" class="form__label">school{...}</label>
                        <input type="hidden" name="ID" value="${task.id}">
                        <input type="hidden" name="complexity" value="${task.complexity}">
                    </div>
                    <div style="position: relative; left: 5%">
                        <button type="submit" class="btnchk">
                            {{ __('Check') }}
            <svg width="79" height="46" viewBox="0 0 79 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- SVG content -->
            </svg>
        </button>
    </div>
</form>
</div>
`;

            document.querySelector('.Tasks-Container').insertAdjacentHTML('beforeend', formHtml);
            initFormHandlers(task);
        }

        function updateExistingForm(formElement, task) {
            formElement.querySelector('.TaskH1').textContent = task.name;
            formElement.querySelector('.taskID_complexity').className = `${task.complexity} taskID_complexity`;
            formElement.querySelector('.taskID_complexity').textContent = task.complexity.toUpperCase();
            formElement.querySelector('.description').innerHTML = task.description;

            const filesHtml = task.FILES ? task.FILES.split(";").map((file, k) =>
                file ? `<a href="{{ asset('/Download/File/') }}${md5(file)}/${task.id}">Файл#${k+1}</a>` : ''
            ).join('') : '';
            formElement.querySelectorAll('.description')[1].innerHTML = filesHtml;

            const form = formElement.querySelector('form');
            form.querySelector('input[name="complexity"]').value = task.complexity;
        }

        function initFormHandlers(task) {
            const form = document.getElementById(`MyFormChange${task.id}`);
            if (form) {
                form.addEventListener('submit', async function(event) {
                    event.preventDefault();
                    await submitFormAsync(this, task.id);
                });
            }

            window[`Taskid${task.id}`] = () => openTask(task.id);
            window[`Taskid${task.id}close`] = () => closeTask(task.id);
        }

        async function submitFormAsync(form, taskId) {
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            const formInput = form.querySelector(`input[id="name${taskId}"]`);

            try {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Проверка...';

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                });

                const data = await response.json();
                formInput.value = '';
                callShowToast(data);
                return data;
            } catch (error) {
                console.error('Ошибка:', error);
                showToast('error', 'Ошибка', 'Произошла ошибка при отправке формы');
                throw error;
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        }

        function openTask(taskId) {
            const div = document.querySelector(`.topmost-div.Task-id-${taskId}`);
            const AppContent = document.querySelector('.app-content');

            if (div) {
                if (AppContent) AppContent.style.filter = 'blur(4px)';
                if (CloseTaskBanner) CloseTaskBanner.style.display = 'block';
                div.style.display = 'block';
                currentlyOpenTaskId = taskId;
            }
        }

        function closeTask(taskId) {
            const div = document.querySelector(`.topmost-div.Task-id-${taskId}`);
            const AppContent = document.querySelector('.app-content');

            if (div) {
                if (AppContent) AppContent.style.filter = 'none';
                if (CloseTaskBanner) CloseTaskBanner.style.display = 'none';
                div.style.display = 'none';
                currentlyOpenTaskId = null;
            }
        }

        function closeAllTasks() {
            document.querySelectorAll('div[class*="Task-id-"]').forEach(el => {
                el.style.display = 'none';
            });

            const AppContent = document.querySelector('.app-content');
            if (AppContent) AppContent.style.filter = 'none';

            if (CloseTaskBanner) CloseTaskBanner.style.display = 'none';
            currentlyOpenTaskId = null;
        }

        function handleKeyDown(event) {
            if (event.key === 'Escape' || event.keyCode === 27) {
                closeAllTasks();
            }
        }

        function setSelection(complexity, category) {
            const complexitySelect = document.querySelector("select[name='complexity']");
            const categorySelect = document.querySelector("select[name='category']");

            // Устанавливаем сложность
            if (complexitySelect) {
                // Ищем option, значение которого равно complexity (All Complexity, easy, medium, hard)
                const complexityOptions = Array.from(complexitySelect.options);
                const complexityIndex = complexityOptions.findIndex(option =>
                    option.value === complexity || option.text === complexity
                );

                complexitySelect.selectedIndex = complexityIndex !== -1 ? complexityIndex : 0;
            }

            // Устанавливаем категорию
            if (categorySelect) {
                // Ищем option, текст или значение которого равно category
                const categoryOptions = Array.from(categorySelect.options);
                const categoryIndex = categoryOptions.findIndex(option =>
                    option.value === category || option.text === category
                );

                categorySelect.selectedIndex = categoryIndex !== -1 ? categoryIndex : 0;
            }
        }

        function applyFilters() {
            const complexity = document.getElementById('complexity').value;
            const category = document.getElementById('category').value;

            localStorage.setItem('taskcomplexity', complexity);
            localStorage.setItem('taskcategory', category);

            renderFilteredTasks(data, complexity, category);
        }

        function resetFilters() {
            const complexity = 'All Complexity';
            const category = 'All Categories';

            localStorage.setItem('taskcomplexity', complexity);
            localStorage.setItem('taskcategory', category);

            renderFilteredTasks(data, complexity, category);
        }

        function applySorting(data, field, order) {
            // Сначала сортируем по полю decide (решенные задачи внизу)
            data.sort((a, b) => {
                const aHasDecide = a.decide?.trim() !== '';
                const bHasDecide = b.decide?.trim() !== '';
                return aHasDecide === bHasDecide ? 0 : aHasDecide ? 1 : -1;
            });

            // Затем применяем основную сортировку
            if (field === 'complexity') {
                const customOrder = order === 1
                    ? { easy: 1, medium: 2, hard: 3 }
                    : { easy: 3, medium: 2, hard: 1 };

                data.sort((a, b) => {
                    // Учитываем decide при сортировке по сложности
                    const aHasDecide = a.decide?.trim() !== '';
                    const bHasDecide = b.decide?.trim() !== '';
                    if (aHasDecide !== bHasDecide) return aHasDecide ? 1 : -1;

                    return customOrder[a.complexity] - customOrder[b.complexity];
                });
            } else {
                const direction = order === 1 ? 1 : -1;

                data.sort((a, b) => {
                    // Учитываем decide при основной сортировке
                    const aHasDecide = a.decide?.trim() !== '';
                    const bHasDecide = b.decide?.trim() !== '';
                    if (aHasDecide !== bHasDecide) return aHasDecide ? 1 : -1;

                    const getValue = (obj) => {
                        const value = obj[field];
                        return ['solved', 'price'].includes(field) ? Number(value) : value?.toLowerCase();
                    };

                    const aValue = getValue(a);
                    const bValue = getValue(b);

                    return aValue < bValue ? -direction : aValue > bValue ? direction : 0;
                });
            }
        }

        function handleSort(column) {
            if (column === 'complexity') {
                currentSort.direction = currentSort.column === column
                    ? (currentSort.direction === 1 ? 2 : 1)
                    : 1;
            } else {
                currentSort.direction = currentSort.column === column
                    ? (currentSort.direction + 1) % 3
                    : 1;
            }

            currentSort.column = column;
            Object.keys(sortStates).forEach(k => sortStates[k] = k === column ? currentSort.direction : 0);

            const sortedData = getSortedData(column, currentSort.direction);
            MakeHTML(sortedData);

            if (currentSort.direction !== 0) {
                localStorage.setItem('SortingTasksColumn', JSON.stringify({
                    N: column,
                    isSort: currentSort.direction
                }));
            }

            if (column !== 'complexity' && currentSort.direction === 0) {
                localStorage.removeItem('SortingTasksColumn');
            }
        }

        function getSortedData(column, direction) {
            if (column !== 'complexity' && direction === 0) {
                return JSON.parse(localStorage.getItem('data'));
            }

            const sorted = [...TASKS].sort((a, b) => {
                if (a.decide && !b.decide) return 1;
                if (!a.decide && b.decide) return -1;

                const modifier = direction === 1 ? 1 : -1;
                const valA = getSortValue(a, column);
                const valB = getSortValue(b, column);

                return (valA > valB ? 1 : -1) * modifier;
            });

            return sorted;
        }

        function getSortValue(item, column) {
            switch(column) {
                case 'complexity': return complexityOrder[item.complexity];
                case 'solved': return Number(item.solved);
                case 'price': return Number(item.price);
                default: return item[column].toLowerCase();
            }
        }

        function initializeEchoListener() {
            Echo.private(`channel-app-home`).listen('AppHomeEvent', (e) => {
                const valueToDisplay = e.tasks;
                let Tasks = valueToDisplay.Tasks;
                let SolvedTaasks = valueToDisplay.SolvedTasks;

                // Filter solved tasks for current user
                let SolvedTasksOnThisAuthUser = SolvedTaasks.filter(
                    solved => solved.user_id === teamid
                );

                Tasks = initialFilter(Tasks, SolvedTaasks);
                localStorage.setItem('data', JSON.stringify(Tasks));

                const SortedTasksColEcho = JSON.parse(localStorage.getItem('SortingTasksColumn'));
                if (SortedTasksColEcho) {
                    applySorting(Tasks, SortedTasksColEcho.N, SortedTasksColEcho.isSort);
                }

                // Update existing forms
                const existingTasks = Array.from(document.querySelectorAll('.topmost-div[class*="Task-id-"]'));
                Tasks.forEach(task => {
                    const existingForm = existingTasks.find(form =>
                        form.classList.contains(`Task-id-${task.id}`)
                    );
                    existingForm
                        ? updateExistingForm(existingForm, task)
                        : createTaskForm(task);
                });

                // Remove forms for deleted tasks
                existingTasks.forEach(form => {
                    const taskId = Array.from(form.classList)
                        .find(c => c.startsWith('Task-id-'))
                        .split('-')[2];
                    if (!Tasks.some(task => task.id == taskId)) {
                        form.remove();
                    }
                });

                // Re-render tasks list
                renderFilteredTasks(
                    Tasks,
                    localStorage.getItem('taskcomplexity'),
                    localStorage.getItem('taskcategory')
                );
            });
        }
    </script>
@endsection

