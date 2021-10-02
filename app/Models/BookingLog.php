<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelCleanup\CleanupConfig;
use Spatie\ModelCleanup\GetsCleanedUp;

class BookingLog extends Model implements GetsCleanedUp
{
    protected $fillable= ['status'];

    public const statusBook = [
        'SUCCESS' => 1,
        'FAIL'    => 0
    ];

    use HasFactory;

    public function cleanUp(CleanupConfig $config): void
    {
        $config->olderThanDays(1);
    }
}
