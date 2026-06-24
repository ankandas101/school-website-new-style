<?php
function load_env_file(string $path): void
{
    if (!is_file($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        [$name, $value] = array_map('trim', explode('=', $line, 2));
        $name = trim($name);
        $value = trim($value);

        if ($value !== '' && ((str_starts_with($value, '"') && str_ends_with($value, '"')) || (str_starts_with($value, "'") && str_ends_with($value, "'")))) {
            $value = substr($value, 1, -1);
        }

        if (getenv($name) === false || getenv($name) === '') {
            putenv($name . '=' . $value);
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

function env($key, $default = null)
{
    $value = getenv($key);
    if ($value === false || $value === '') {
        return $default;
    }
    return $value;
}

load_env_file(dirname(__DIR__) . '/.env');

function is_rate_limited(string $key, int $limit = 5, int $windowSeconds = 300): bool
{
    $cacheFile = dirname(__DIR__) . '/assets/.rate_limit_' . md5($key) . '.json';
    $now = time();
    $data = ['attempts' => 0, 'first_attempt' => $now];

    if (is_file($cacheFile)) {
        $data = json_decode((string)file_get_contents($cacheFile), true);
        if (!is_array($data)) {
            $data = ['attempts' => 0, 'first_attempt' => $now];
        }
        if (($now - (int)($data['first_attempt'] ?? $now)) > $windowSeconds) {
            $data = ['attempts' => 0, 'first_attempt' => $now];
        }
    }

    if (($data['attempts'] ?? 0) >= $limit) {
        return true;
    }

    $data['attempts'] = (int)($data['attempts'] ?? 0) + 1;
    $data['first_attempt'] = (int)($data['first_attempt'] ?? $now);
    file_put_contents($cacheFile, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
    return false;
}

function reset_rate_limit(string $key): void
{
    $cacheFile = dirname(__DIR__) . '/assets/.rate_limit_' . md5($key) . '.json';
    if (is_file($cacheFile)) {
        unlink($cacheFile);
    }
}


