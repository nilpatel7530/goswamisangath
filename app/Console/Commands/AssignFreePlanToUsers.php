<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\MembershipService;
use Illuminate\Console\Command;

class AssignFreePlanToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'memberships:assign-free-plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign free plan to all users who do not have an active membership';

    /**
     * Execute the console command.
     */
    public function handle(MembershipService $membershipService): int
    {
        $this->info('Assigning free plan to users without active memberships...');

        $count = $membershipService->assignFreePlanToAllUsers();

        $this->info("Successfully assigned free plan to {$count} user(s).");

        return Command::SUCCESS;
    }
}
