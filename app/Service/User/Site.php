<?php
declare (strict_types=1);

namespace App\Service\User;

use App\Entity\Site\NginxInfo;
use App\Model\User;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\Site::class)]
interface Site
{
    
    public function bind(int $themePage, string $template, array &$data): array;

    
    public function setTemplateData(array &$data): void;

    
    public function effective(): bool;

    
    public function getMainDomains(): array;

    
    public function add(User $user, int $type, string $domain, string $subdomain = "", string $pem = "", string $key = ""): void;

    
    public function modifyCertificate(string $domain, string $pem, string $key): void;

    
    public function getCertificate(string $domain): array;

    public function del(string $domain): void;

    
    public function getConfig(string $key, ?string $userId = null): array;

    
    public function getDnsRecord(string $host): array;

    
    public function getNginxInfo(string $host): NginxInfo;

    
    public function getNginxProxyConfig(NginxInfo $nginxInfo, string $proxyPass, ?string $conf = null): string;
}