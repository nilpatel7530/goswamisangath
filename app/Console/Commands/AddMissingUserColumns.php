<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddMissingUserColumns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:add-columns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add missing columns to users table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking and adding missing columns to users table...');

        // Get existing columns
        $existingColumns = [];
        try {
            $columns = DB::select("SHOW COLUMNS FROM users");
            foreach ($columns as $column) {
                $existingColumns[] = $column->Field;
            }
        } catch (\Exception $e) {
            $this->error('Could not check existing columns: ' . $e->getMessage());
            return 1;
        }

        $columnsToAdd = [
            'full_name' => "ALTER TABLE `users` ADD COLUMN `full_name` VARCHAR(255) NULL AFTER `name`",
            'profile_image' => "ALTER TABLE `users` ADD COLUMN `profile_image` VARCHAR(255) NULL",
            'gender' => "ALTER TABLE `users` ADD COLUMN `gender` VARCHAR(255) NULL",
            'height' => "ALTER TABLE `users` ADD COLUMN `height` VARCHAR(255) NULL",
            'weight' => "ALTER TABLE `users` ADD COLUMN `weight` VARCHAR(255) NULL",
            'dob' => "ALTER TABLE `users` ADD COLUMN `dob` DATE NULL",
            'birth_time' => "ALTER TABLE `users` ADD COLUMN `birth_time` VARCHAR(255) NULL",
            'birth_place' => "ALTER TABLE `users` ADD COLUMN `birth_place` VARCHAR(255) NULL",
            'raashi' => "ALTER TABLE `users` ADD COLUMN `raashi` VARCHAR(255) NULL",
            'nakshtra' => "ALTER TABLE `users` ADD COLUMN `nakshtra` VARCHAR(255) NULL",
            'naadi' => "ALTER TABLE `users` ADD COLUMN `naadi` VARCHAR(255) NULL",
            'marital_status' => "ALTER TABLE `users` ADD COLUMN `marital_status` VARCHAR(255) NULL",
            'mother_tongue' => "ALTER TABLE `users` ADD COLUMN `mother_tongue` VARCHAR(255) NULL",
            'physically_handicap' => "ALTER TABLE `users` ADD COLUMN `physically_handicap` VARCHAR(255) NULL",
            'diet' => "ALTER TABLE `users` ADD COLUMN `diet` VARCHAR(255) NULL",
            'languages_known' => "ALTER TABLE `users` ADD COLUMN `languages_known` TEXT NULL",
            'employed_in' => "ALTER TABLE `users` ADD COLUMN `employed_in` VARCHAR(255) NULL",
            'annual_income' => "ALTER TABLE `users` ADD COLUMN `annual_income` VARCHAR(255) NULL",
            'mobile_number' => "ALTER TABLE `users` ADD COLUMN `mobile_number` VARCHAR(255) NULL UNIQUE",
            'google_id' => "ALTER TABLE `users` ADD COLUMN `google_id` VARCHAR(255) NULL UNIQUE",
            'role' => "ALTER TABLE `users` ADD COLUMN `role` ENUM('user', 'admin') DEFAULT 'user'",
        ];

        $added = 0;
        $skipped = 0;

        foreach ($columnsToAdd as $columnName => $sql) {
            if (in_array($columnName, $existingColumns)) {
                $this->line("Column '{$columnName}' already exists, skipping...");
                $skipped++;
                continue;
            }

            try {
                DB::statement($sql);
                $this->info("Added column '{$columnName}'");
                $added++;
            } catch (\Exception $e) {
                if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                    $this->line("Column '{$columnName}' already exists, skipping...");
                    $skipped++;
                } else {
                    $this->error("Failed to add column '{$columnName}': " . $e->getMessage());
                }
            }
        }

        // Add foreign key columns using Schema
        $foreignKeys = [
            'highest_education_id' => 'highest_qualification_master',
            'occupation_id' => 'occupation_master',
            'country_id' => 'country_manage',
            'state_id' => 'state_master',
            'city_id' => 'city_master',
        ];

        foreach ($foreignKeys as $columnName => $referencedTable) {
            if (in_array($columnName, $existingColumns)) {
                $this->line("Column '{$columnName}' already exists, skipping...");
                $skipped++;
                continue;
            }

            try {
                Schema::table('users', function ($table) use ($columnName, $referencedTable) {
                    $table->foreignId($columnName)->nullable()->constrained($referencedTable)->onDelete('set null');
                });
                $this->info("Added foreign key column '{$columnName}'");
                $added++;
            } catch (\Exception $e) {
                if (strpos($e->getMessage(), 'Duplicate column name') !== false || 
                    strpos($e->getMessage(), 'already exists') !== false) {
                    $this->line("Column '{$columnName}' already exists, skipping...");
                    $skipped++;
                } else {
                    $this->error("Failed to add column '{$columnName}': " . $e->getMessage());
                }
            }
        }

        $this->info("\nCompleted! Added: {$added} columns, Skipped: {$skipped} columns");
        return 0;
    }
}
