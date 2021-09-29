<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class StaffController extends Controller
{
    public function book(Staff $staff)
    {
        try {
            Artisan::call('lunch:book', ['--user' => $staff->username]);
        } catch (\Exception $exception) {
            return redirect()->route('voyager.staff.index')->with([
                'message'    => "Error when book for staff $staff->full_name",
                'alert-type' => 'error',
            ]);
        }
        return redirect()->route('voyager.staff.index')->with([
            'message'    => "Successfully booked for staff $staff->full_name",
            'alert-type' => 'success',
        ]);
    }
}
