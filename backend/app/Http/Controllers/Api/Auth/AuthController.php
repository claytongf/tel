<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Rules\Cpf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->only('email', 'password');
        $data['cpf'] = $data['email'];
        $data['pis'] = $data['email'];
        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']]) &&
            !Auth::attempt(['cpf' => $data['cpf'], 'password' => $data['password']]) &&
            !Auth::attempt(['pis' => $data['pis'], 'password' => $data['password']])) {
            throw ValidationException::withMessages([
                'email' => 'Credenciais Inválidas'
            ]);
        }
        $request->session()->regenerate();
        return response()->json($this->me(), 201);
    }

    public function tokenLogin(Request $request)
    {
        $request->session()->regenerate();

        return response()->json(null, 201);
    }

    public function logout(Request $request)
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(null, 200);
    }

    public function me()
    {
        $user = User::findOrFail(auth()->user()->id);
        return new UserResource($user);
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $validatedData = $this->validate($request, $this->rulesUpdate());
        $user->update($validatedData);
        return new UserResource($user);
    }

    private function rulesUpdate()
    {
        return [
            'image' => ['nullable', 'image'],
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'min:3', 'max:255', 'unique:users,email,' . auth()->user()->id . ',id'],
            'rg' => ['nullable'],
            'cpf' => ['nullable', new Cpf],
            'passport' => ['nullable'],
            'passport_expiry' => ['nullable', 'date:Y-m-d'],
            'phone' => ['nullable', 'string'],
            'cellphone' => ['nullable', 'string'],
            'ext' => ['nullable', 'string'],
            'mother_name' => ['nullable'],
            'father_name' => ['nullable'],
        ];
    }

    public function updatePassword(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $validatedData = $this->validate($request, $this->rulesUpdatePassword());
        $user->update(['password' => bcrypt($validatedData['password'])]);
        return response()->json('Senha atualizada com sucesso!', 200);
    }

    private function rulesUpdatePassword()
    {
        return ['password' => ['required', 'string', 'confirmed', 'min:6', 'max:20']];
    }
}
