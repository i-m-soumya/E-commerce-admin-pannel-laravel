<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Session;

class Authentication extends Controller
{
    //
    public function login(Request $req)
    {
        $status = array();
        $user = User::where(['email' => $req->email, 'is_deleted' => '0'])->first();
        if (!$user) {

            $status['success'] = 0;
            $status['message'] = "Admin not found";
            echo json_encode($status);
        } else if (!Hash::check($req->password, $user->password)) {
            $status['success'] = 0;
            $status['message'] = "Password didn't match";
            echo json_encode($status);
        } else if ($user['admin_type_id'] == 1) {
            $status['success'] = 1;
            $status['admin_type'] = 1;
            $status['message'] = "Login Successful";
            Session::put('user', $user);
            echo json_encode($status);
        } else {
            $status['success'] = 1;
            $status['admin_type'] = 2;
            $status['message'] = "Login Successful";
            Session::put('user', $user);
            echo json_encode($status);
        }
    }
    public function view_admin_profile()
    {
        $fetch_admin_profile_details = DB::table('admin')
            ->select('*')
            ->where('id', '=', Session::get('user')['id'])
            ->get();
        return $fetch_admin_profile_details;
    }

    public function logout()
    {
        Session::forget('user');
        return redirect('/');
    }
}
