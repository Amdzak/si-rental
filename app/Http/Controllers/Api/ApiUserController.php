<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use App\Services\FirebaseService;

class ApiUserController extends Controller
{
    protected $firebaseService;

    // public function __construct(FirebaseService $firebaseService)
    // {
        // $this->firebaseService = $firebaseService;
    // } 

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return new GlobalResource(true, 'List Data Users', $users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request['password'] = Hash::make($request['password']);
        $user = User::create($request->all());

        // $userId = $user->user_id;
        // $database = $this->firebaseService->getDatabase();
        // $database->getReference('users/' . $userId)->set($request->all());

        return new GlobalResource(true, 'User created successfully', $user);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        return new GlobalResource(true, 'User details', $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request['password'] = Hash::make($request['password']);
        $user = User::find($id);

        $user->update($request->all());

        // $userId = $user->user_id;
        // $database = $this->firebaseService->getDatabase();
        // $database->getReference('users/' . $userId)->set($request->all());

        return new GlobalResource(true, 'User updated successfully', $user);  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        // $database = $this->firebaseService->getDatabase();
        // $database->getReference('users/' . $user->userId)->remove();

        return new GlobalResource(true, 'User deleted successfully', null); 
    }
}
