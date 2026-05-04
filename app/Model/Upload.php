<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Upload extends Model
{
    protected $table = 'upload';
    public $timestamps = false;
    protected $casts = ['id' => 'integer', 'user_id' => 'integer'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }
}