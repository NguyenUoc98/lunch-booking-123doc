<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Support\Facades\Artisan;

class StaffController extends Controller
{
    public function book(Staff $staff)
    {
        try {
            if (Artisan::call('lunch:book', ['--user' => $staff->username]) == 1) {
                return redirect()->route('voyager.staff.index')->with([
                    'message'    => "Successfully booked for staff $staff->full_name",
                    'alert-type' => 'success',
                ]);
            }
            return redirect()->route('voyager.staff.index')->with([
                'message'    => "Can't book for staff $staff->full_name",
                'alert-type' => 'error',
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('voyager.staff.index')->with([
                'message'    => "Error when book for staff $staff->full_name",
                'alert-type' => 'error',
            ]);
        }
    }
}
