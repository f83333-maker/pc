<?php
declare(strict_types=1);

namespace App\Service;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Bind\App::class)]
interface App
{

    const APP_URL = BASE_APP_SERVER;
    const MAIN_SERVER = "https://tencent.3rd.mcycdn.com";
    const STANDBY_SERVER1 = "https://byte.3rd.mcycdn.com";
    const STANDBY_SERVER2 = "https://standby.acgshe.com";
    const GENERAL_SERVER = "https://aliyun.3rd.mcycdn.com";

    public function getVersions(): array;

    public function update(): void;

    public function upload(array $data): array;

    public function ad(): array;

    public function install(): void;

    public function captcha(string $type): array;

    public function register(string $username, string $password, string $captcha, array $cookie): array;

    public function login(string $username, string $password): array;

    public function plugins(array $data): array;

    public function purchase(int $type, int $pluginId, int $payType): array;

    public function levels(): array;

    public function bindLevel(int $authId): array;

    public function installPlugin(string $key, int $type, int $pluginId): void;

    public function updatePlugin(string $key, int $type, int $pluginId): void;

    public function uninstallPlugin(string $key, int $type): void;

    public function purchaseRecords(int $pluginId): array;

    public function unbind(int $authId): array;

    public function developerPlugins(array $data): array;

    public function developerCreatePlugin(array $data): array;

    public function developerCreateKit(array $data): array;

    public function developerDeletePlugin(array $data): array;

    public function developerUpdatePlugin(array $data): array;

    public function developerPluginPriceSet(array $data): array;

    public function service(): array;

    public function editPassword(array $data): array;
}