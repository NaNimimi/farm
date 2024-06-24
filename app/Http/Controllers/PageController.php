<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
    * Get the admin dashboard page.
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function adminDashboard()
    {
        return response()->json(['message' => 'Admin dashboard page']);
    }

    /**
    * Get the user dashboard page.
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function userDashboard()
    {
        return response()->json(['message' => 'User dashboard page']);
    }

    /**
    * Get the public page.
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function publicPage()
    {
        return response()->json(['message' => 'Public page']);
    }
}
