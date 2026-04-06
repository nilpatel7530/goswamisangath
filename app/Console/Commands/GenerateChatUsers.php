<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GenerateChatUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:chat-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate two test users for chat testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating test users for chat testing...');
        
        // User 1
        $user1 = User::firstOrCreate(
            ['email' => 'alice@test.com'],
            [
                'full_name' => 'Alice Johnson',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'gender' => 'female',
                'dob' => '1995-05-15',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'country' => 'India',
                'email_verified_at' => now(),
            ]
        );
        
        // User 2
        $user2 = User::firstOrCreate(
            ['email' => 'bob@test.com'],
            [
                'full_name' => 'Bob Smith',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'gender' => 'male',
                'dob' => '1992-08-20',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'country' => 'India',
                'email_verified_at' => now(),
            ]
        );
        
        // Create mutual interest so they can chat
        try {
            $hasInterest = DB::table('user_interests')
                ->where(function($query) use ($user1, $user2) {
                    $query->where('sender_id', $user1->id)
                          ->where('receiver_id', $user2->id)
                          ->orWhere(function($q) use ($user1, $user2) {
                              $q->where('sender_id', $user2->id)
                                ->where('receiver_id', $user1->id);
                          });
                })
                ->exists();
                
            if (!$hasInterest) {
                DB::table('user_interests')->insert([
                    'sender_id' => $user1->id,
                    'receiver_id' => $user2->id,
                    'status' => 'accepted',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->info('✓ Mutual interest created');
            } else {
                $this->info('✓ Mutual interest already exists');
            }
        } catch (\Exception $e) {
            $this->warn('Could not create mutual interest: ' . $e->getMessage());
        }
        
        $this->newLine();
        $this->info('═══════════════════════════════════════════════════════');
        $this->info('  Test User Credentials for Chat Testing');
        $this->info('═══════════════════════════════════════════════════════');
        $this->newLine();
        
        $this->line('User 1:');
        $this->line('  Email:    alice@test.com');
        $this->line('  Password: password123');
        $this->line('  Name:     Alice Johnson');
        $this->line('  ID:       ' . $user1->id);
        $this->newLine();
        
        $this->line('User 2:');
        $this->line('  Email:    bob@test.com');
        $this->line('  Password: password123');
        $this->line('  Name:     Bob Smith');
        $this->line('  ID:       ' . $user2->id);
        $this->newLine();
        
        $this->info('✓ Both users can now chat with each other!');
        $this->newLine();
        
        return Command::SUCCESS;
    }
}
