<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class StaffController extends Controller
{
    public function book(Staff $staff)
    {
        Artisan::call('lunch:book', ['--user' => $staff->username]);
        return redirect()->route('voyager.staff.index');
    }
}
