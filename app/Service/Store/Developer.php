<?php
declare (strict_types=1);

namespace App\Service\Store;

use App\Entity\Store\Authentication;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Store\Bind\Developer::class)]
interface Developer
{

    public function pluginList(array $post, Authentication $authentication): array;

    public function createOrUpdatePlugin(array $post, Authentication $authentication): void;

    public function publishPlugin(string $name, Authentication $authentication): void;

    public function getPluginTrackedFiles(string $name): array;

    public function updatePlugin(string $name, string $content, Authentication $authentication): void;

    public function getPluginVersionList(int $pluginId, int $page, int $limit, Authentication $authentication): array;

    public function getPluginAuthorizationList(int $pluginId, array $post, Authentication $authentication): array;

    public function addPluginAuthorization(array $post, Authentication $authentication): void;

    public function removePluginAuthorization(int $authId, Authentication $authentication): void;
}