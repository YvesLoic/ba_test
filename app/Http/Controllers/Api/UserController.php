<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\UserLibrary;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use UserLibrary;

    /**
     * Display a listing of the resource.
     *
     * @param Request Objet de la requette
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $users = User::withTrashed()->forPage($request->page, 5)->get();
            $users = UserResource::collection($users);
            return $this->success($users, sizeof($users) . " users from page " . $request->page);
        }
        return $this->error(403, "Access denied!", "getUserList from method 'index'");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $validator = Validator::make(
                $request->all(),
                $this->userValidatedRules(true),
                $this->userMessagesError(false)
            );
            if (!$validator->fails()) {
                $res = User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'rule' => $request->input('rule'),
                    'password' => Hash::make($request->input('password'))
                ]);
                if (strcmp($res->rule, "admin") == 0) {
                    $res->rules()->attach([1, 2]);
                } else {
                    $res->rules()->attach(2);
                }
                return $this->success(new UserResource($res), 'User Created With ' . $res->rule . ' Privilege');
            }
            return $this->error(400, $validator->errors(), "Create user from method 'store");
        }
        return $this->error(403, "Access denied!", "Create user from method 'store'");
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $res = User::withTrashed()->with(['rules', 'products'])->find($id);
            if (!empty($res)) {
                return $this->success(new UserResource($res), "User found!");
            }
            return $this->error(404, "User not found!", "Get single user from method 'show'");
        }
        return $this->error(403, "Access denied!", "Get single user from method 'show'");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $validator = Validator::make(
                $request->all(),
                $this->userValidatedRules(false),
                $this->userMessagesError()
            );
            if (!$validator->fails()) {
                $updatedUser = User::find($id);
                if (empty($updatedUser)) {
                    return $this->error(404, "User not found!", "Update user from method 'update'");
                }
                $updatedUser->name = $request->input('name');
                $updatedUser->email = $request->input('email');
                $updatedUser->phone = $request->input('phone');
                $updatedUser->password = Hash::make($request->input('password'));
                $updatedUser->rule = $request->input('rule');
                $updatedUser->update();
                $updatedUser->rules()->detach();
                if (strcmp($updatedUser->rule, "admin") == 0) {
                    $updatedUser->rules()->attach([1, 2]);
                } else {
                    $updatedUser->rules()->attach(2);
                }
                return $this->success(new UserResource($updatedUser), 'User Updated');
            }
            return $this->error(400, $validator->errors(), "Update user from method 'update");
        }
        return $this->error(403, "Access denied!", "Update user from method 'update'");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @param  int     $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $res = User::find($id);
            if (!empty($res)) {
                $res->delete();
                return $this->success(new UserResource($res), "User deleted!");
            }
            return $this->error(404, "User not found!", "Delete user from method 'destroy'");
        }
        return $this->error(403, "Access denied!", "Delete user from method 'destroy'");
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  Request $request
     * @param  int     $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, int $id)
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $res = User::withTrashed()->find($id);
            if (!empty($res)) {
                $res->restore();
                return $this->success(new UserResource($res), "User restored!");
            }
            return $this->error(404, "User not found!", "Restore user from method 'restore'");
        }
        return $this->error(403, "Access denied!", "Restore user from method 'restore'");
    }
}
