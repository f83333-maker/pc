<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shared extends Model
{

    protected $table = 'shared';

    public $timestamps = false;

    protected $casts = ['id' => 'integer', 'type' => 'integer', 'balance' => 'float'];
}