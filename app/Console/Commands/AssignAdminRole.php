<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignAdminRole extends Command
{
    protected $signature = 'assign:admin {userId}';
    protected $description = 'Assign the admin role to a user';

    public function handle()
    {
        $userId = $this->argument('userId');
        $user = User::find($userId);

        if ($user) {
            $adminRole = Role::where('name', 'admin')->where('guard_name', 'api')->first();
            if ($adminRole) {
                $user->assignRole($adminRole);
                $this->info("Role 'admin' assigned to user with ID {$userId}");
            } else {
                $this->error("Role 'admin' for guard 'api' does not exist.");
            }
        } else {
            $this->error("User with ID {$userId} not found.");
        }
    }
}
