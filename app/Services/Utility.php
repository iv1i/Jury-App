<?php

namespace App\Services;

use App\Models\Tasks;
use App\Models\Teams;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Utility
{
    public function makeId($q)
    {
        $table = [];
        foreach ($q as $item) {
            $table[] = $item->id;
        }
        $id = 1;

        foreach ($table as $value) {
            if ($value != $id) {
                break;
            }
            $id++;
        }
        return $id;
    }
    public function cacheClear()
    {
        Cache::tags('ModelList')->flush();
    }
    public function formatToLegacyUniversal($universalResult) {
        // Сначала создаем массив только с sumary
        $legacy = [
            'sumary' => $universalResult['sumary'] ?? 0
        ];

        // Стандартные категории из legacy-формата (для обратной совместимости)
        $legacyCategories = [
            'admin', 'recon', 'crypto', 'stegano', 'ppc', 'pwn',
            'web', 'forensic', 'joy', 'misc', 'osint', 'reverse',
            'easy', 'medium', 'hard' // Добавляем сложности в категории для сортировки
        ];

        // Собираем все возможные категории
        $allCategories = array_unique(array_merge(
            $legacyCategories,
            array_keys($universalResult['categories'] ?? [])
        ));

        // Сортируем категории в алфавитном порядке
        sort($allCategories);

        // Добавляем категории в отсортированном порядке
        foreach ($allCategories as $category) {
            // Для сложностей берем из difficulty
            if (in_array($category, ['easy', 'medium', 'hard'])) {
                $legacy[$category] = $universalResult['difficulty'][$category] ?? 0;
            }
            // Для остальных категорий берем из categories
            else {
                $legacy[$category] = $universalResult['categories'][$category] ?? 0;
            }
        }

        return $legacy;
    }
    public function processTasksUniversal($tasks) {
        $result = [
            'sumary' => 0,
            'difficulty' => [],
            'categories' => []
        ];

        foreach ($tasks as $task) {
            $result['sumary']++;

            // Обработка сложности
            $difficulty = strtolower($task['complexity'] ?? 'unknown');
            if (!isset($result['difficulty'][$difficulty])) {
                $result['difficulty'][$difficulty] = 0;
            }
            $result['difficulty'][$difficulty]++;

            // Обработка категорий (поддержка задач с несколькими категориями)
            $categories = $task['category'] ?? 'unknown';

            // Если категория передана как строка (одна категория)
            if (is_string($categories)) {
                $categories = array_map('trim', explode(',', $categories));
            }

            // Если категория передана как массив
            if (is_array($categories)) {
                foreach ($categories as $category) {
                    $category = strtolower(trim($category));
                    if (!isset($result['categories'][$category])) {
                        $result['categories'][$category] = 0;
                    }
                    $result['categories'][$category]++;
                }
            }
        }

        // Сортируем категории и сложности для удобства
        ksort($result['difficulty']);
        ksort($result['categories']);

        return $result;
    }

    public function startDockerContainer($directory)
    {
        $path = storage_path('app/private/'.$directory);

        // 1. Проверяем существование docker-compose.yml
        if (!file_exists($path.'/docker-compose.yml')) {
            throw new \Exception("docker-compose.yml not found in directory: {$path}");
        }

        // 2. Выполняем с таймаутом и перенаправлением stderr в stdout
        $command = "cd {$path} && docker-compose up -d --build 2>&1";
        exec($command, $output, $returnCode);

        // 3. Анализируем результат
        $outputString = implode("\n", $output);

        if ($returnCode !== 0) {
            // Логируем полную информацию для диагностики
            Log::error("Docker command failed", [
                'command' => $command,
                'return_code' => $returnCode,
                'output' => $outputString,
                'directory' => $directory,
                'path' => $path
            ]);

            // Проверяем распространенные проблемы
            if (str_contains($outputString, 'permission denied')) {
                throw new \Exception("Permission denied. Try running with sudo or check directory permissions.");
            }

            if (str_contains($outputString, 'no such file or directory')) {
                throw new \Exception("Required files not found. Check your docker-compose.yml configuration.");
            }

            if (str_contains($outputString, 'port is already allocated')) {
                throw new \Exception("Port conflict. The required port is already in use.");
            }

            throw new \Exception("Failed to start container. Docker output: ".$outputString);
        }

        // 4. Дополнительная проверка что контейнеры действительно запущены
        $running = false;
        $attempts = 0;
        $maxAttempts = 5;

        while (!$running && $attempts < $maxAttempts) {
            sleep(2); // Даем время на запуск
            exec("cd {$path} && docker-compose ps --services", $services, $servicesReturn);

            if ($servicesReturn === 0 && !empty($services)) {
                $running = true;
                foreach ($services as $service) {
                    exec("cd {$path} && docker-compose ps -q {$service} | xargs docker inspect -f '{{.State.Status}}'", $status, $statusReturn);
                    if ($statusReturn !== 0 || (isset($status[0]) && $status[0] !== 'running')) {
                        $running = false;
                        break;
                    }
                }
            }

            $attempts++;
        }

        if (!$running) {
            // Получаем подробную информацию о состоянии
            exec("cd {$path} && docker-compose ps", $psOutput, $psReturn);
            $psInfo = implode("\n", $psOutput);

            Log::error("Containers failed to reach running state", [
                'ps_output' => $psInfo,
                'attempts' => $attempts
            ]);

            throw new \Exception("Containers failed to start properly. Current state:\n".$psInfo);
        }

        return true;
    }
    public function restartDockerContainer($directory)
    {
        $path = storage_path('app/private/'.$directory);
        exec("cd {$path} && docker-compose down && docker-compose up -d --build 2>&1", $output, $return);

        if ($return !== 0) {
            Log::error("Docker error: ".implode("\n", $output));
            throw new \Exception("Failed to restart container");
        }
    }
    public function stopDockerContainer($directory)
    {
        $path = storage_path('app/private/'.$directory);
        exec("cd {$path} && docker-compose down 2>&1", $output, $return);

        if ($return !== 0) {
            Log::error("Docker error: ".implode("\n", $output));
            throw new \Exception("Failed to stop container");
        }
    }
    public function smartReplaceDockerPorts($directory, $webPort, $dbPort)
    {
        $composePath = 'private/'.$directory.'/docker-compose.yml';

        // Проверяем существование файла
        if (!Storage::exists($composePath)) {
            return false;
        }

        // Проверяем валидность портов (должны быть либо null, либо числовые)
        $hasValidWebPort = is_numeric($webPort);
        $hasValidDbPort = is_numeric($dbPort);

        // Если оба порта невалидны - выходим
        if (!$hasValidWebPort && !$hasValidDbPort) {
            return false;
        }

        try {
            $content = Storage::get($composePath);
            $yaml = Yaml::parse($content);

            $dbPatterns = [
                '/mysql/i', '/postgres/i', '/mariadb/i', '/redis/i', '/mongo/i',
                '/^db[\W_]/i', '/[\W_]db[\W_]/i', '/[\W_]db$/i',
                '/database/i', '/_sql/i', '/_data/i'
            ];

            $changesMade = false;

            foreach ($yaml['services'] ?? [] as $serviceName => $serviceConfig) {
                if (empty($serviceConfig['ports'])) continue;

                // Определяем тип сервиса (БД или приложение)
                $isDatabase = false;

                // Проверка имени сервиса
                foreach ($dbPatterns as $pattern) {
                    if (preg_match($pattern, $serviceName)) {
                        $isDatabase = true;
                        break;
                    }
                }

                // Проверка образа
                if (!$isDatabase && isset($serviceConfig['image'])) {
                    foreach ($dbPatterns as $pattern) {
                        if (preg_match($pattern, $serviceConfig['image'])) {
                            $isDatabase = true;
                            break;
                        }
                    }
                }

                // Проверка переменных окружения
                if (!$isDatabase && isset($serviceConfig['environment'])) {
                    $env = is_array($serviceConfig['environment'])
                        ? implode(' ', $serviceConfig['environment'])
                        : $serviceConfig['environment'];

                    if (preg_match('/DB_|DATABASE|MYSQL_|POSTGRES_|REDIS_|MONGO_/i', $env)) {
                        $isDatabase = true;
                    }
                }

                // Формируем новые порты
                $newPorts = [];
                foreach ((array)$serviceConfig['ports'] as $portMapping) {
                    if (preg_match('/^"?(\d+):(\d+)/', $portMapping, $matches)) {
                        $externalPort = $matches[1];
                        $internalPort = $matches[2];

                        // Заменяем только если есть валидный порт для этого типа сервиса
                        if ($isDatabase && $hasValidDbPort) {
                            $newPorts[] = "\"{$dbPort}:{$internalPort}\"";
                            $changesMade = true;
                        } elseif (!$isDatabase && $hasValidWebPort) {
                            $newPorts[] = "\"{$webPort}:{$internalPort}\"";
                            $changesMade = true;
                        } else {
                            // Оставляем порт как есть
                            $newPorts[] = "\"{$externalPort}:{$internalPort}\"";
                        }
                    }
                }

                // Заменяем в исходном содержимом
                if (!empty($newPorts)) {
                    $serviceBlock = preg_quote($serviceName, '/');
                    $content = preg_replace(
                        "/({$serviceBlock}:[\s\S]*?ports:)([\s\S]*?)(\n\s+[a-z]|$)/i",
                        "$1\n      - " . implode("\n      - ", $newPorts) . "$3",
                        $content,
                        1
                    );
                }
            }

            // Сохраняем изменения только если они были
            if ($changesMade) {
                Storage::put($composePath, $content);
                return true;
            }

            return false;

        } catch (ParseException $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function generateDockerCompose($directory, $SourseZipname, $webPort, $dbPort = null)
    {
        $composePath = storage_path('app/private/'.$directory.'/docker-compose.yml');

        try {
            $q = Tasks::all();
            $id = $this->makeId($q);
            if (file_exists($composePath)) {
                $config = Yaml::parseFile($composePath);
            } else {
                $config = [
                    'version' => '3',
                    'services' => [
                        "web-{$id}" => [
                            "container_name"=> "web-task-{$id}",
                            'build' => "'./{$SourseZipname}'",
                            'ports' => ["{$webPort}:80"]
                        ]
                    ]
                ];
            }

            // Обновляем порты
            //$config['services']['web']['ports'][0] = "{$webPort}:80";

            if ($dbPort) {
                $config['services']['db'] = [
                    'image' => 'mysql:5.7',
                    'environment' => [
                        'MYSQL_ROOT_PASSWORD' => 'example',
                        'MYSQL_DATABASE' => 'taskdb'
                    ],
                    'ports' => ["{$dbPort}:3306"]
                ];
                $config['services']['web']['depends_on'] = ['db'];
            } elseif (isset($config['services']['db'])) {
                unset($config['services']['db']);
            }

            file_put_contents($composePath, Yaml::dump($config, 4, 2));

        } catch (ParseException $e) {
            Log::error("YAML parse error: ".$e->getMessage());
            throw new \Exception("Invalid docker-compose.yml format");
        }
    }
}
