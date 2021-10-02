<?php

namespace App\Console\Commands;

use App\Models\BookingLog;
use App\Models\Staff;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Nguyenhiep\BookingLunch\User;

class LunchBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lunch:book
                            {--user= : book for one staff}
                            {--test : testing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Book lunch for staff';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $userName = $this->option('user');
        $staffs   = [];
        if ($userName) {
            $staff = Staff::query()
                ->where('username', $userName)
                ->where('is_active', 1)
                ->first();
            if (is_null($staff)) {
                dump('Not found user ' . $userName);
                return 0;
            }
            $staffs[] = $staff;
        } else {
            $staffs = Staff::query()
                ->where('is_active', 1)
                ->get();
        }

        foreach ($staffs as $staff) {
            if ($staff->isBookToday() != Staff::statusBook['BOOK_SUCCESS']) {
                $this->info("Start book lunch for staff $staff->full_name");
                try {
                    $user = new User($staff->username, $staff->password, $staff->company ?: '', $staff->position ?: '', !$this->option('test'));
                    $page = $user->login();
                    if ($user->book_lunch($page)) {
                        $staff->bookingLogs()->create([
                            'status' => BookingLog::statusBook['SUCCESS']
                        ]);
                        if (App::runningInConsole()) {
                            $this->info("Successfully booked for staff $staff->full_name");
                        } else {
                            return 1;
                        }
                    } else {
                        $staff->bookingLogs()->create([
                            'status' => BookingLog::statusBook['FAIL']
                        ]);
                        Log::error("Can't book for staff $staff->full_name");
                        if (App::runningInConsole()) {
                            $this->error("Can't book for staff $staff->full_name");
                        } else {
                            return 0;
                        }
                    }
                } catch (\Exception $exception) {
                    $staff->bookingLogs()->create([
                        'status' => BookingLog::statusBook['FAIL']
                    ]);
                    Log::error($exception->getMessage());
                    if (App::runningInConsole()) {
                        $this->error("Error when book for staff $staff->full_name");
                    } else {
                        return 0;
                    }
                }
            }
        }
        return 1;
    }
}
