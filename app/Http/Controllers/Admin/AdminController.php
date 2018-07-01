<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 01.07.18
 * Time: 13:05
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

class AdminController extends Controller
{

    public function index()
    {
        return view('admin.home');
    }

}