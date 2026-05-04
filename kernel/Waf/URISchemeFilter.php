<?php
declare (strict_types=1);

namespace Kernel\Waf;

use Kernel\Component\Make;

class URISchemeFilter extends \HTMLPurifier_URIFilter
{

    use Make;

    public $name = 'URISchemeFilter';

    
    public array $whitelist = [
    ];

    public function filter(&$uri, $config, $context): bool
    {
        
        return true;
    }
}