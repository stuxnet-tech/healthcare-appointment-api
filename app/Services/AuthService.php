<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);

        return [
            'user' => $user,
            'message' => 'User registered successfully.',
        ];
    }

    public function login(array $data): array
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $accessToken = $user->createToken('authToken', ['*'], now()->addDays(30));
        $plainAccessToken = $accessToken->plainTextToken;
        $accessTokenExpiresAt = $accessToken->accessToken->expires_at;

        return [
            'user' => $user,
            'token' => $plainAccessToken,
            'expires_at' => $accessTokenExpiresAt,
        ];
    }
}