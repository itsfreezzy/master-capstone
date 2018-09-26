<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, Validator, Hash;
use App\User;

class AdminAccountController extends Controller
{
    public function index() {
        $users = User::withTrashed()->get();

        return view('admin.users')->with(compact('users'));
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'editusertype' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.users.index')
                        ->with([
                            'showEditModal' => true,
                            'id' => $id,
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = User::find($id);
        $user->usertype = $request->editusertype;
        
        if ($user->isDirty()) {
            $user->save();

            return redirect()->route('admin.users.index')->with(['success' => 'User type successfully updated.']);
        }
        
        return redirect()->route('admin.users.index')->with(['warning' => 'No changes have been made.']);
    }

    public function destroy(Request $request, $id) {
        User::find($id)->delete();
        return redirect()->route('admin.users.index')->with(['success' => 'User successfully deactivated.']);
    }

    public function restore(Request $request, $id) {
        User::withTrashed()->where('id', $id)->restore();
        
        return redirect()->route('admin.users.index')->with(['success' => 'User successfully restored.']);
    }

    public function getUserInfo(Request $request) {
        $user = User::findOrFail($request->id);
        return $user;
    }

    public function updateProfile(Request $request) {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:150|unique:users,fullname,'.Auth::guard('web')->user()->id,
            'username' => 'required|string|max:50|unique:users,username,'.Auth::guard('web')->user()->id,
            'email' => 'required|string|email|max:191|unique:users,email,'.Auth::guard('web')->user()->id,
            'password' => 'required|',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.show.profile')
                        ->withErrors($validator);
        }

        if (!password_verify($request->password, Auth::guard('web')->user()->password)) {
            return redirect()->route('admin.show.profile')->with(['error' => 'You have entered an incorrect password!']);
        }

        $user = User::find(Auth::guard('web')->user()->id);
        $user->fullname = $request->fullname;
        $user->username = $request->username;
        $user->email = $request->email;

        if($user->isDirty()) {
            $user->save();

            return redirect()->route('admin.show.profile')->with(['success' => 'Your profile has been updated!']);
        }

        return redirect()->route('admin.show.profile')->with(['warning' => 'No changes have been made.']);
    }

    public function updatePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'newpassword' => 'required|string|min:5|confirmed',
            'oldpassword' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.show.profile')
                        ->withErrors($validator);
        }
        
        if (!password_verify($request->oldpassword, Auth::guard('web')->user()->password)) {
            return redirect()->route('admin.show.profile')->with(['error' => 'You have entered an incorrect password!']);
        }

        $user = User::find(Auth::guard('web')->user()->id);
        $user->password = Hash::make($request->newpassword);
        if ($user->isDirty()) {
            $user->save();
            
            return redirect()->route('admin.show.profile')->with(['success' => 'Your password has been updated!']);
        }

        return redirect()->route('admin.show.profile')->with(['warning' => 'No changes have been made.']);
    }
}
