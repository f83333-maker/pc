<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{

    protected $table = "user_category";

    public $timestamps = false;

    protected $casts = ['id' => 'integer' , 'user_id' => 'integer' , 'category_id' => 'integer' , 'status' => 'integer'];
}