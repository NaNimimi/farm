<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
    * Display the admin dashboard.
    *
    * @return \Illuminate\View\View
    */
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    /**
    * Display the user dashboard.
    *
    * @return \Illuminate\View\View
    */
    public function userDashboard()
    {
        return view('user.dashboard');
    }

    /**
    * Display the public page.
    *
    * @return \Illuminate\View\View
    */
    public function publicPage()
    {
        return view('public.page');
    }
}
