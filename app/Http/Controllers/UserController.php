<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::simplePaginate(1);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if (! $user) {
            throw ValidationException::withMessages([
                'user' => ['Unable to create user.'],
            ]);
        }
        //event(new Registered($user)); //nepouyivam notif
        //return $user;
        return $this->login($request); //todo mailuer
    }

    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', Rules\Password::defaults()],
        ]);
 
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if($user->email_verified_at === null AND FALSE){ //todo mailzer
            throw ValidationException::withMessages([
                'email' => ['The email is not verified.'],
            ]);
        }

        Auth::login($user);

        $token = $user->tokens()->delete();

        $token = $user->createToken('api')->plainTextToken;

        $user['token'] = $token; //hit 

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, int $id)
    {
        //
        //return $id;
        return $user->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, int $id )
    {
        //
        //return "test" . $request->user()->id;
        //return "test" . var_dump( $id );
        if($request->user()->id !== $id){
            return abort(403,'You tried to update other user.');
        }
        //return abort(400,'Not updated');
        $status = $user->findOrFail($id)->update($request->all());

        if($status){
            return $user->findOrFail($id);
        }

        return abort(304,'Not updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user, int $id)
    {
        //return $id;
        //return $request->user()->id;
        if($request->user()->id !== $id){
           return abort(403,'You tried to delete other user.');
        }

        $status = $user->findOrFail($id)->delete();

        if($status){
            return reponse("Removed",204);
        }

        return abort(400,"Not deleted");
    }
}
