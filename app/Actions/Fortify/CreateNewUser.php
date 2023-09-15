<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username'  => ['required', 'string','min:8' ,'max:255', 'unique:users'],
            'phone'     => ['required', 'regex:/(0)8[1-9][0-9]{6,9}$/', 'unique:users'],
            'alamat'     => ['required', 'string', 'min:5', 'max:255'],
            'tanggal_lahir'     => ['required', 'date'],
            'gender'     => ['required'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'username'  => $input['username'],
            'phone'     => $input['phone'],
            'alamat'     => $input['alamat'],
            'tanggal_lahir'     => $input['tanggal_lahir'],
            'gender'     => $input['gender'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
