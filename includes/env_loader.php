<?php
/**
 * Simple and robust .env loader for Rigel Career Portal
 * Searches for .env traversing upwards from the current script directory.
 */

function loadEnv() {
    static $loaded = false;
    if ($loaded) {
        return;
    }

    // Traverse up to find .env file
    $envPath = null;
    $dir = __DIR__;
    while ($dir && dirname($dir) !== $dir) {
        if (file_exists($dir . DIRECTORY_SEPARATOR . '.env')) {
            $envPath = $dir . DIRECTORY_SEPARATOR . '.env';
            break;
        }
        $dir = dirname($dir);
    }

    if (!$envPath && isset($_SERVER['DOCUMENT_ROOT']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/.env')) {
        $envPath = $_SERVER['DOCUMENT_ROOT'] . '/.env';
    }

    if ($envPath && file_exists($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $name = trim($parts[0]);
                $val = trim($parts[1]);

                // Remove surrounding quotes if present
                if (preg_match('/^([\'"])(.*)\1$/', $val, $matches)) {
                    $val = $matches[2];
                }

                // Put into putenv, $_ENV, and $_SERVER if not already set by OS
                if (getenv($name) === false) {
                    putenv("$name=$val");
                }
                if (!isset($_ENV[$name])) {
                    $_ENV[$name] = $val;
                }
                if (!isset($_SERVER[$name])) {
                    $_SERVER[$name] = $val;
                }
            }
        }
    }
    $loaded = true;
}

// Automatically load the environment when this helper is included
loadEnv();

/**
 * Get all configured Groq API keys and rotate/select one.
 */
function getGroqApiKey() {
    $keys = [];

    // Check system environment variables / $_ENV / $_SERVER
    $sources = [$_ENV ?? [], $_SERVER ?? []];
    foreach ($sources as $source) {
        foreach ($source as $name => $val) {
            if (strpos($name, 'GROQ_KEY_') === 0 && !empty($val) && strpos($val, 'REPLACE_WITH_YOUR_GROQ_KEY_') === false) {
                $keys[] = $val;
            }
        }
    }

    // Fallback search using getenv for keys GROQ_KEY_1 to GROQ_KEY_10
    for ($i = 1; $i <= 10; $i++) {
        $keyName = 'GROQ_KEY_' . $i;
        $val = getenv($keyName);
        if ($val && !empty($val) && strpos($val, 'REPLACE_WITH_YOUR_GROQ_KEY_') === false) {
            if (!in_array($val, $keys)) {
                $keys[] = $val;
            }
        }
    }

    if (empty($keys)) {
        // Fallback to empty string if no keys are found
        return "";
    }

    // Pick a random key for rotation
    $index = rand(0, count($keys) - 1);
    error_log("Selected Groq API key rotation index: " . ($index + 1) . " of " . count($keys));
    return $keys[$index];
}
