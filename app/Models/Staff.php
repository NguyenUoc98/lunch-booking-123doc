<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Integer;

class Staff extends Model
{
    use HasFactory;

    public const statusBook = [
        'BOOK_SUCCESS' => 1,
        'BOOK_FAIL'    => 2,
        'NOT_YET'      => 3
    ];

    public function bookingLogs()
    {
        return $this->hasMany(BookingLog::class);
    }

    /**
     * Check staff is booked today
     *
     * @return int
     */
    public function isBookToday(): int
    {
        $history = $this->bookingLogs()->whereDate('time', Carbon::today()->toDateString())->orderByDesc('time')->first();
        if (is_null($history)) {
            return self::statusBook['NOT_YET'];
        } elseif ($history->status == BookingLog::statusBook['FAIL']) {
            return self::statusBook['BOOK_FAIL'];
        }
        return self::statusBook['BOOK_SUCCESS'];
    }
}
