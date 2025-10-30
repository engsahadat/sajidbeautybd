<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = User::query();
            if ($request->has('search') && !empty($request->input('search'))) {
                $searchTerm = $request->input('search');
                $query->where('first_name', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('email', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('phone', 'LIKE', '%' . $searchTerm . '%');
            }
            $users = $query->paginate(10);
            return view('admin.user.index', compact('users'));
        } catch (Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load users.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('admin.user.create');
        } catch (Exception $e) {
            Log::error('Error loading create form: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Failed to load create form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $uniqueFileName = time().'_'.$image->getClientOriginalName();
                $destinationPath = public_path('images/user');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                $image->move($destinationPath, $uniqueFileName);
                $data['image'] = 'images/user/'.$uniqueFileName;
            }

            // Hash password
            $data['password'] = Hash::make($data['password']);
            
            $user = User::create($data);
            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'data' => $user,
                'redirect' => route('users.index')
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return view('admin.user.view', compact('user'));
        } catch (Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'User not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return view('admin.user.edit', compact('user'));
        } catch (Exception $e) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $user = User::findOrFail($id);
            
            // Handle image removal
            if ($request->input('remove_image') == '1' && $user->image) {
                if (file_exists(public_path($user->image))) {
                    unlink(public_path($user->image));
                }
                $data['image'] = null;
            }
            
            // Handle new image upload
            if ($request->hasFile('image')) {
                if ($user->image && file_exists(public_path($user->image))) {
                    unlink(public_path($user->image));
                }
                $image = $request->file('image');
                $uniqueFileName = time().'_'.$image->getClientOriginalName();
                $destinationPath = public_path('images/user');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $uniqueFileName);
                $data['image'] = 'images/user/'.$uniqueFileName;
            }

            // Handle password update
            if ($request->filled('password')) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            
            $user->update($data);
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'data' => $user,
                'redirect' => route('users.index')
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Delete user image if exists
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
            
            $user->delete();
            return redirect()->back()->with('message', 'User deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete user.');
        }
    }
}
