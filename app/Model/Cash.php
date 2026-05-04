<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cash extends Model
{
    
    protected $table = "cash";

    public $timestamps = false;

    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'amount' => 'float',  'cost' => 'float', 'type' => 'integer', 'card' => 'integer', 'status' => 'integer'];

    public function user(): ?HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

}