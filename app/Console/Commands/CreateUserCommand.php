<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(UserService $userService): int
    {
//        Get user info
        $user['name'] = $this->ask('Name of the user:');
        $user['username'] = $this->ask('Username of the user:');
        $user['email'] = $this->ask('Email of the user:');
        $user['password'] = $this->secret('Password of the user:');

//        Get user role
        $roleChoice = $this->choice(
            question: 'Role of the user:',
            choices: UserRole::getRoles()->values()->toArray(),
            multiple: true,
        );

        $roles = [];
        foreach ($roleChoice as $choice) {
            if ($role = UserRole::find($choice)) {
                $roles[] = $role->value;
            } else {
                $this->error("Role {$choice} not found.");

                return -1;
            }
        }

        if ($this->validate($user)) {
            $userService->createUser(
                $user['name'],
                $user['username'],
                $user['email'],
                $user['password'],
                $roles,
            );
            $this->info("User {$user['email']} created successfully.");

            return 0;
        }

        return -1;
    }

    private function validate(array $user): bool
    {
        $validator = Validator::make($user, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'max:255', 'email', 'unique:users'],
            'password' => ['required', Password::default()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return false;
        }

        return true;
    }
}
