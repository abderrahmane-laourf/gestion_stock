<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::updateOrCreate(
    ['username' => 'admin'],
    [
        'name'     => 'Admin',
        'email'    => [EMAIL_ADDRESS]',
        'password' => Hash::make('admin123'),
        'role'     => 'admin',
        'category' => 'user',
    ]
);

echo "✅ User ready!\n";
echo "   Username : " . $user->username . "\n";
echo "   Email    : " . $user->email . "\n";
echo "   Role     : " . $user->role . "\n";
echo "   Password : admin123\n";
