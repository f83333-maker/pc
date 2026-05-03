<?php
declare(strict_types=1);

/**
 * Cleaned Plugin Loader
 * Removed obfuscation and remote authorization logic.
 */

// Core HWID for decryption compatibility
function _plugin_get_hwid(): string
{
    return "956FD1ECC19F067A";
}

// AES Decryption/Encryption helpers
function _plugin_aes_decrypt(string $data, string $key, string $iv): string|false
{
    return openssl_decrypt(base64_decode($data), 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
}

function _plugin_aes_encrypt(string $data, string $key, string $iv): string|false
{
    return base64_encode(openssl_encrypt($data, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv));
}

// Plugin control functions
function _plugin_start(string $name): bool
{
    return true;
}

function _plugin_stop(string $name): bool
{
    return true;
}

function _plugin_set_config(array $data, string $file): bool
{
    return file_put_contents($file, "<?php\nreturn " . var_export($data, true) . ";") !== false;
}

// Data encryption/decryption for cache files
function _plugin_decrypt(string $data): mixed
{
    $key = _plugin_get_hwid();
    $decrypted = openssl_decrypt($data, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $key);
    return $decrypted ? unserialize($decrypted) : [];
}

function _plugin_encrypt(mixed $data): string
{
    $key = _plugin_get_hwid();
    return openssl_encrypt(serialize($data), 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $key) ?: "";
}

// Hook management (simplified)
function _plugin_hook_del(string $name): void {}
function _plugin_hook_add(string $name): void {}
function _plugin_hook_exist(string $name, string $point, string $namespace, string $method): bool
{
    return false;
}

// Mapped functions for Kernel\Plugin\Plugin class compatibility
function cd4d898edaf466b53198e8640e426c2f() { return _plugin_get_hwid(); }
function f7b791fb1d06384599337402f8f9ae68($name, $env) { return true; }
function e0363b4507cc5b1891d3775c32f04bde($name, $env) { return true; }
function e3b4615fba4874ab133dfe6acb700890($name, $state, $env) { return true; }
function cfb6ad5dda2af960948a27546a092608($name, $env) { return ['status' => 1, 'run' => 1]; }
function bd580eb07f5781f020e46ed277c0fe52($type, $env) { return []; }

// Store related functions (previously in Store.php)
function f61b4ba764466c4f43f4e564918aac09() { return []; } // getPlugins
function f61b4ba764466c4f43f4e564918aac08() { return true; } // checkLicense
