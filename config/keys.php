<?php
// Configuration for external API keys (Gemini AI, etc.)

// Parse local .env file if present
if (file_exists(__DIR__ . '/../.env')) {
    $envLines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $k = trim($parts[0]);
            $v = trim($parts[1], " \t\n\r\0\x0B\"'");
            if (!getenv($k)) {
                putenv("$k=$v");
                $_ENV[$k] = $v;
            }
        }
    }
}

// Read from environment variable
$geminiApiKey = getenv('GEMINI_API_KEY') ?: '';

if (!defined('GEMINI_API_KEY')) {
    define('GEMINI_API_KEY', $geminiApiKey);
}
