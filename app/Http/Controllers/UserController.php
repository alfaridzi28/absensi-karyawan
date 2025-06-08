<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $draw = $request->input('draw');
            $search = $request->input('search.value');
            $orderColumnIndex = $request->input('order.0.column', 0);
            $orderDirection = $request->input('order.0.dir', 'asc');

            $columns = ['username', 'role']; 

            $orderColumn = $columns[$orderColumnIndex] ?? 'username';

            $query = User::select('id', 'username', 'role');

            $totalData = $query->count();

            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
                });
            }

            $totalFiltered = $query->count();

            $users = $query->orderBy('username', 'asc')
                        ->skip($start)
                        ->take($length)
                        ->get();

            $data = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role == 1 ? 'Admin' : 'Karyawan',
                    'action' => view('users.partials.action-buttons', ['data' => $user])->render(),
                ];
            });

            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $totalData,
                'recordsFiltered' => $totalFiltered,
                'data' => $data,
            ]);
        }

        return view('users.index');
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $request->id,
            'password' => $request->id ? 'nullable|min:8' : 'required|min:8',
            'role' => 'required|in:1,2',
        ]);

        $data = [
            'username' => $request->username,
            'role' => $request->role,
        ];

        // Hanya set password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        User::updateOrCreate(['id' => $request->id], $data);

        return response()->json([
            'success' => true,
            'message' => $request->id ? 'User updated successfully' : 'User created successfully',
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted']);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }
}
