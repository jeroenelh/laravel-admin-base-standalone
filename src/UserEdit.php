<?php

namespace Microit\LaravelAdminBaseStandalone;

use App\User;
use DCN\RBAC\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UserEdit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:edit-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edit a user';

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
     * @todo translate to English
     * @return mixed
     */
    public function handle()
    {
        $email = $this->ask('Email?');

        $user = User::where('email', $email)->first();
        if (is_null($user)) {
            $this->info("Gebruiker niet gevonden...");
            return;
        }

        $this->info("==============");
        $this->info("= Actielijst =");
        $this->info("==============");
        $this->info(" [1] Wachtwoord wijzigen");
        $action = $this->ask("Actie");

        if ($action == 1) {
            $this->info("=======================");
            $this->info("= Wachtwoord wijzigen =");
            $this->info("=======================");

            $pass= $this->secret('Nieuw wachtwoord');
            $user->update([
                'password' => Hash::make($pass),
            ]);

            $this->info("Wachtwoord gewijzigd");

        } else {
            $this->info("Actie niet gevonden...");
            return;
        }
        return;
    }
}
