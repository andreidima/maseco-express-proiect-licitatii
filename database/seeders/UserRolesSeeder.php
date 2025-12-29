<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRolesSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmins();
        $this->seedOperators();
    }

    private function seedAdmins(): void
    {
        $this->upsertUser(
            ['email' => 'admin-suport@licitatii.maseco.ro'],
            [
                'name' => 'Admin Suport',
                'activ' => true,
                'role' => 'Admin',
                'telefon' => '0733001234',
            ],
            'Suport@2025',
        );

        for ($i = 1; $i <= 4; $i++) {
            $index = str_pad((string) $i, 2, '0', STR_PAD_LEFT);

            $this->upsertUser(
                ['email' => "admin{$index}@licitatii.maseco.ro"],
                [
                    'name' => "Admin {$index}",
                    'activ' => true,
                    'role' => 'Admin',
                    'telefon' => null,
                ],
                'Admin@2025',
            );
        }
    }

    private function seedOperators(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            $index = str_pad((string) $i, 2, '0', STR_PAD_LEFT);

            $this->upsertUser(
                ['email' => "operator{$index}@licitatii.maseco.ro"],
                [
                    'name' => "Operator {$index}",
                    'activ' => true,
                    'role' => 'Operator',
                    'telefon' => null,
                ],
                'Operator@2025',
            );
        }
    }

    /**
     * @param array<string, mixed> $lookup
     * @param array<string, mixed> $attributes
     */
    private function upsertUser(array $lookup, array $attributes, string $plainPassword): User
    {
        $user = User::firstOrNew($lookup);
        $user->fill($attributes);

        $currentHash = $user->getAttribute('password');
        if (! is_string($currentHash) || $currentHash === '' || ! Hash::check($plainPassword, $currentHash)) {
            $user->password = Hash::make($plainPassword);
        }

        if ($user->email_verified_at === null) {
            $user->email_verified_at = now();
        }

        $user->save();

        return $user;
    }
}

