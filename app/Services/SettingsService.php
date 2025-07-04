<?php

namespace App\Services;

class SettingsService
{
    protected $settings;
    protected $path;
    protected array $defaultSettings = [
        'site' => [
            'name' => 'Jury-App',
            'description' => 'Журийка для соревнований TaskBased для AltayCTF'
        ],

        'complexity' => ['easy', 'medium', 'hard'],
        'categories' => ['admin','recon','crypto','stegano','ppc','pwn','web','forensic','joy','misc','osint','reverse'],
        'sidebar' => [
            'Rules' => true,
            'Projector' => true,
            'Home' => true,
            'Scoreboard' => true,
            'Statistics' => true,
            'Logout' => true,
        ],
        'auth' => 'base',
        'AppRulesTB' => '<div class="dec">
    <p style="text-indent: 1vw; padding-left: 20px; padding-right: 20px">
        1. Длительность соревнований - 6 часов + час перерыва на обед.
        Соревнования могут быть продлены по решению жюри.
    </p>
    <p style="text-indent: 1vw; padding-left: 20px; padding-right: 20px">
        2. Участникам предоставляется набор заданий, к которым требуется найти ответ «флаг» и отправить его
        в проверяющую систему.
        Каждое такое задание оценивается различным количеством очков, в зависимости от количества
        решивших это задание.
        В случае проблем с доступностью заданий или проверяющей системы можно написать боту в
        https://t.me/alt_school_ctfbot
    </p>
    <p style="text-indent: 1vw; padding-left: 20px; padding-right: 20px">
        3. В данных Соревнованиях присутствуют задания следующих категорий:
    <ul style="text-indent: 1vw;">
        <p>
            > admin — задачи на администрирование;
        </p>
        <p>
            > recon — поиск информации в Интернете;
        </p>
        <p>
            > crypto — криптография;
        </p>
        <p>
            > stegano — стеганография;
        </p>
        <p>
            > ppc — задачи на программирование;
        </p>
        <p>
            > pwn — задачи на эксплуатацию бинарных уязвимостей.
        </p>
        <p>
            > web — задачи на веб-уязвимости, такие как SQL injection, XSS и другие;
        </p>
        <p>
            > forensic — задания на исследование цифровых доказательств, их поиск и получение;
        </p>
        <p>
            > joy — различные развлекательные задачи;
        </p>
        <p>
            > misc — задачи, сочетающие в себе несколько вышеописанных категорий.
        </p>
    </ul>
    </p>
    <p style="text-indent: 1vw; padding-left: 20px; padding-right: 20px">
        4. Результатом правильного решения задания является «флаг» - правильный ответ в формате:
        school{[A-Za-z0-9_]+} или flag{[A-Za-z0-9_]+}
        например school{g00D_j0b_mY_fr13nd}. За отправку корректного «флага» команда получает баллы от
        этого задания.
    </p>
    <p style="text-indent: 1vw; padding-left: 20px; padding-right: 20px">
        5. Во время проведения Соревнования участники могут общаться только с членами своей команды и с
        представителями жюри.
    </p>
    <p style="text-indent: 1vw; padding-left: 20px; padding-right: 20px">
        6. При выполнении заданий участникам Соревнования запрещается выполнять следующие
        действия:
    <ul style="text-indent: 2vw;">
        <p>
            - проводить атаки на серверы жюри и автоматизированную систему поддержки сервисов;
        </p>
        <p>
            - генерировать неоправданно большой объем трафика;
        </p>
        <p>
            - мешать всевозможными способами получению флага другим командам;
        </p>
        <p>
            - сообщать условия задач, а также значения флагов, кому-либо, за исключением членов своей
            команды.
        </p>
    </ul>
    </p>
    <p style="text-indent: 1vw; padding-left: 20px; padding-right: 20px">
        7. За нарушение требований Правил или нарушение хода Соревнований (например, неподобающее
        поведение)
        команда может быть дисквалифицирована по решению жюри.
    </p>
    <p style="text-indent: 1vw; padding-left: 20px; padding-right: 20px">
        8. Если по ходу проведения Соревнования были отмечены нарушения требований Правил,
        участники должны обратить на них внимание представителей жюри с целью устранения причин
        нарушения.
    </p>
    <p style="text-indent: 1vw; padding-left: 20px; padding-right: 20px">
        9. В случае несогласия с предварительными результатами Соревнования участники могут
        подать апелляцию в жюри до оглашения ими результатов Соревнования.
    </p>
    <p style="text-indent: 1vw; padding-left: 20px; padding-right: 20px">
        10. Если по результатам апелляции изменятся результаты Соревнования, список победителей и
        призеров также может измениться.
    </p>
    <p style="text-indent: 1vw; padding-left: 20px; padding-right: 20px">
        11. Жюри в обязательном порядке оглашает результаты рассмотрения поступивших апелляций
        до оглашения итоговых результатов Соревнования.
    </p>
    <p style="text-indent: 1vw; padding-left: 20px; padding-right: 20px">
        12. Удачи в решении заданий ;)
    </p>
</div>
            <ol class="dec" style="display: none">
    <li>
        Длительность соревнований - 6 часов + час перерыва на обед.
        Соревнования могут быть продлены по решению жюри.
    </li>
    <li>
        Участникам предоставляется набор заданий, к которым требуется найти ответ «флаг» и отправить его
        в проверяющую систему.
        Каждое такое задание оценивается различным количеством очков, в зависимости от количества
        решивших это задание.
        В случае проблем с доступностью заданий или проверяющей системы можно написать боту в
        https://t.me/alt_school_ctfbot
    </li>
    <li>
        В данных Соревнованиях присутствуют задания следующих категорий:
        <ul>
            <li>
                admin — задачи на администрирование;
            </li>
            <li>
                recon — поиск информации в Интернете;
            </li>
            <li>
                crypto — криптография;
            </li>
            <li>
                stegano — стеганография;
            </li>
            <li>
                ppc — задачи на программирование (professional programming and coding);
            </li>
            <li>
                pwn — задачи на эксплуатацию бинарных уязвимостей.
            </li>
            <li>
                web — задачи на веб-уязвимости, такие как SQL injection, XSS и другие;
            </li>
            <li>
                forensic — задания на исследование цифровых доказательств, их поиск и получение,
                например, анализ дампов оперативной памяти компьютера;
            </li>
            <li>
                joy — различные развлекательные задачи;
            </li>
            <li>
                misc — задачи, сочетающие в себе несколько вышеописанных категорий.
            </li>
        </ul>
    </li>
    <li>
        Результатом правильного решения задания является «флаг» - правильный ответ в формате:
        school{[A-Za-z0-9_]+} или flag{[A-Za-z0-9_]+}
        например school{g00D_j0b_mY_fr13nd}. За отправку корректного «флага» команда получает баллы от
        этого задания.
    </li>
    <li>
        Во время проведения Соревнования участники могут общаться только с членами своей команды и с
        представителями жюри.
    </li>
    <li>
        При выполнении заданий участникам Соревнования запрещается выполнять следующие
        действия:
        <ul>
            <li>
                проводить атаки на серверы жюри и автоматизированную систему поддержки сервисов и
                проверки решений;
            </li>
            <li>
                генерировать неоправданно большой объем трафика;
            </li>
            <li>
                мешать всевозможными способами получению флага другим командам;
            </li>
            <li>
                сообщать условия задач, а также значения флагов, кому-либо, за исключением членов своей
                команды.
            </li>
        </ul>
    </li>
    <li>
        За нарушение требований Правил или нарушение хода Соревнований (например, неподобающее
        поведение)
        команда может быть дисквалифицирована по решению жюри.
    </li>
    <li>
        Если по ходу проведения Соревнования были отмечены нарушения требований Правил,
        участники должны обратить на них внимание представителей жюри с целью устранения причин
        нарушения.
    </li>
    <li>
        В случае несогласия с предварительными результатами Соревнования участники могут
        подать апелляцию в жюри до оглашения ими результатов Соревнования.
    </li>
    <li>
        Если по результатам апелляции изменятся результаты Соревнования, список победителей и
        призеров также может измениться.
    </li>
    <li>
        Жюри в обязательном порядке оглашает результаты рассмотрения поступивших апелляций
        до оглашения итоговых результатов Соревнования.
    </li>
    <li>
        Удачи в решении заданий  ;)
    </li>

</ol >'

    ];

    public function __construct()
    {
        $this->path = storage_path('app/private/settings.json');
        $this->initializeSettings();
        $this->loadSettings();
    }
    protected function initializeSettings(): void
    {
        if (!file_exists($this->path)) {
            if (!is_writable(dirname($this->path))) {
                throw new \RuntimeException("Директория для настроек недоступна для записи");
            }
            $this->settings = $this->defaultSettings;
            $this->save();
        }
    }
    protected function loadSettings()
    {
        $fileContents = file_get_contents($this->path);
        $decoded = json_decode($fileContents, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->settings = $this->defaultSettings;
            $this->save();
            return;
        }

        $this->settings = array_replace_recursive($this->defaultSettings, $decoded);
    }
    public function get(string $key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }
    public function set(string $key, $value): void
    {
        data_set($this->settings, $key, $value);
        $this->save();
    }
    public function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            data_set($this->settings, $key, $value);
        }
        $this->save();
    }
    public function remove(string $key): void
    {
        data_forget($this->settings, $key);
        $this->save();
    }
    public function removeMany(array $keys): void
    {
        foreach ($keys as $key) {
            data_forget($this->settings, $key);
        }
        $this->save();
    }
    public function all(): array
    {
        return $this->settings;
    }
    public function exists(string $key): bool
    {
        return data_get($this->settings, $key) !== null;
    }
    protected function save(): void
    {
        $result = file_put_contents(
            $this->path,
            json_encode($this->settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
        );

        if ($result === false) {
            throw new \RuntimeException("Не удалось сохранить настройки");
        }
    }
}
