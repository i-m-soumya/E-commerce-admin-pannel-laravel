<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Support\Facades\Log;

class PagesController extends Controller
{
    public function signin()
    {
        return view('/signin');
    }
    public function dashboard()
    {
        if (Session::missing('user')) {
            return redirect('/');
        } else if (Session::get('user')['admin_type_id'] == 2) {
            return redirect('/order_details');
        } else {
            return view('dashboard');
        }
    }

    public function product()
    {
        if (Session::missing('user')) {
            return redirect('/');
        } else if (Session::get('user')['admin_type_id'] == 2) {
            return redirect('/order_details');
        } else {
            return view('/product');
        }

    }
    public function order_details()
    {
        if (Session::missing('user')) {
            return redirect('/');
        } else {
            return view('/order_details');
        }

    }
    public function report()
    {
        if (Session::missing('user')) {
            return redirect('/');
        } else if (Session::get('user')['admin_type_id'] == 2) {
            return redirect('/order_details');
        } else {
            return view('/report');
        }

    }
    public function users()
    {
        if (Session::missing('user')) {
            return redirect('/');
        } else if (Session::get('user')['admin_type_id'] == 2) {
            return redirect('/order_details');
        } else {
            return view('/users');
        }

    }
    public function setting()
    {
        if (Session::missing('user')) {
            return redirect('/');
        } else if (Session::get('user')['admin_type_id'] == 2) {
            return redirect('/order_details');
        } else {
            return view('/setting');
        }

    }
    public function analytics()
    {
        if (Session::missing('user')) {
            return redirect('/');
        } else if (Session::get('user')['admin_type_id'] == 2) {
            return redirect('/order_details');
        } else {
            return view('analytics');
        }
    }
    public function sales()
    {
        if (Session::missing('user')) {
            return redirect('/');
        } else if (Session::get('user')['admin_type_id'] == 2) {
            return redirect('/order_details');
        } else {
            return view('sales');
        }
    }
}
