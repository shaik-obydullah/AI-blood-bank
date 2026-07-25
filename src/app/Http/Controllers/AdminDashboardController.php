<?php

namespace App\Http\Controllers;

use App\Models\BloodDistribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get pending blood distributions with related data
        $pendingDistributions = BloodDistribution::pending()
            ->with(['patient', 'bloodGroup'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Return view with data
        return view('dashboard.content', [
            'distributions' => $pendingDistributions,
            'pendingCount' => $pendingDistributions->count()
        ]);
    }
    public function profile()
    {
        $admin = [
            'id' => session('admin_id'),
            'name' => session('admin_name'),
            'username' => session('admin_username'),
            'logged_in' => session('admin_logged_in')
        ];

        $admin = Admin::find(session('admin_id'));
        return view('dashboard.profile', compact('admin'));
    }

    public function update_admin(Request $request)
    {
        $adminId = session('admin_id');

        $admin = Admin::find($adminId);

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found!');
        }

        $request->validate([
            'username' => 'required|string|max:50|unique:admins,username,' . $admin->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $admin->username = $request->username;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        session(['admin_username' => $admin->username]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function logout()
    {
        Session::flush();
        return redirect('/admin-login');
    }
}