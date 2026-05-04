<?php
declare (strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Manage extends Model
{

    protected $table = 'manage';

    public $timestamps = false;

    protected $casts = ['id' => 'integer', 'type' => 'integer', 'status' => 'integer'];
}