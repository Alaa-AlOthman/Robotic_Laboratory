<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public static function CreateAdmin()
    {
        $Pass = Hash::make('12345678');
        $user = User::create([
            'name' => 'admin',
            'password' => 12345678
        ]);
        FacadesAuth::login($user);

        dump('DONE CREATING ADMIN ( USERNAME:admin | PASSWORD: 12345678 ) ✔✔ ');
    }

    public function getUsers()
    {
        $users = User::with('team')->get();
        return response()->json($users, 200);
    }

    public function changePassword(Request $request)
    {
        $user = JWTAuth::user();
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6'
        ]);
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['error' => 'كلمة المرور القديمة غير صحيحة'], 403);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return response()->json(['message' => 'تم تغيير كلمة المرور بنجاح']);
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);
        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);
        if ($request->team_id) {
            $team = Team::findOrFail($request->team_id);
            if ($team) {
                $team->user_id = $user->id;
                $team->save();
            }
        }
        return response()->json(['message' => 'تمت إضافة  المستخدم', 'user' => $user], 201);
    }

    public function editUser(Request $request)
    {
           $user = User::findOrFail($request->id);
        $request->validate([
            'name' => ['required','string','max:255',Rule::unique('users')->ignore($user->id)],
            'password' => 'required|string|min:6'
        ]);
     
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->team_id) {
            $team = Team::findOrFail($request->team_id);
            if ($team) {
                $team->user_id = $user->id;
                $team->save();
            }
        }
        return response()->json(['message' => '  تمت تعديل المستخدم بنجاح ', 'user' => $user], 201);
    }

    public function deleteUser(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->delete();

        return response()->json(['message' => '  تم حذف المستخدم بنجاح ', 'user' => $user], 201);
    }
}
