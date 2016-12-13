<?php

namespace Microit\LaravelAdminBaseStandalone;

use App\User;
use DCN\RBAC\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name  = $this->ask('Name?');
        $email = $this->ask('Email?');
        $pass  = $this->secret('Password?');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($pass),
        ]);

        $this->info("Done creating user");

        $roles = Role::all();

        $this->info("Beschikbare rollen:");
        foreach ($roles as $role) {
            $this->info("  [".$role->id."] ".$role->name);
        }

        $role_id = $this->ask("Attach role?");

        $role = Role::whereId($role_id)->first();
        if (is_null($role)) {
            $this->info("Role not found");
        } else {
            $user->attachRole($role);
            $this->info("Role attached!");
        }
    }
}
