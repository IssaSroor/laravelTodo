<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.dashboard', compact('users'));
    }
}
