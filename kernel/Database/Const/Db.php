<?php
declare (strict_types=1);

namespace Kernel\Database\Const;

interface Db
{
    
    const ISOLATION_READ_UNCOMMITTED = "READ UNCOMMITTED";
    
    const ISOLATION_READ_COMMITTED = "READ COMMITTED";
    
    const ISOLATION_REPEATABLE_READ = "REPEATABLE READ";
    
    const ISOLATION_SERIALIZABLE = "SERIALIZABLE";
}