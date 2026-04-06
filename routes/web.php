<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Middleware\AdminMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Language Switcher Route ---
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'hi', 'gu'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');

// --- AJAX Language Switcher Route ---
Route::post('language/{locale}/switch', function ($locale) {
    if (in_array($locale, ['en', 'hi', 'gu'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);
        
        // Load translations directly from file
        $translationPath = resource_path('lang/' . $locale . '/common.php');
        $translations = [];
        
        if (file_exists($translationPath)) {
            $translations = require $translationPath;
        }
        
        return response()->json([
            'success' => true,
            'locale' => $locale,
            'translations' => $translations
        ]);
    }
    return response()->json(['success' => false, 'message' => 'Invalid locale'], 400);
})->name('language.switch.ajax');

// --- Translations API Route ---
Route::get('translations', function () {
    // Get locale from query parameter, session, or default to 'en'
    $locale = request()->query('locale', Session::get('locale', 'en'));
    
    // Ensure locale is valid
    if (!in_array($locale, ['en', 'hi', 'gu'])) {
        $locale = 'en';
    }
    
    // Set the locale for this request
    App::setLocale($locale);
    Session::put('locale', $locale);
    
    // Load translations directly from file for reliability
    $translationPath = resource_path('lang/' . $locale . '/common.php');
    
    if (file_exists($translationPath)) {
        $translations = require $translationPath;
    } else {
        // Fallback to English if locale file doesn't exist
        $translationPath = resource_path('lang/en/common.php');
        if (file_exists($translationPath)) {
            $translations = require $translationPath;
        } else {
            $translations = [];
        }
    }
    
    // Ensure we return an array
    if (!is_array($translations)) {
        $translations = [];
    }
    
    return response()->json($translations);
})->name('translations.get');


// --- Public & Guest Routes ---
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/success-stories', [PageController::class, 'successStories'])->name('success-stories');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/thank-you', [PageController::class, 'thankYou'])->name('thank-you');

Route::get('/membership', [PageController::class, 'membership'])->name('membership');


Route::middleware('guest')->group(function () {
    Route::get('/signup', [PageController::class, 'signup'])->name('signup');
    Route::post('/signup', [RegisterController::class, 'store'])->name('signup.store');
    Route::get('/login', [PageController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.store');
    
    // OTP Routes
    Route::post('/otp/send', [\App\Http\Controllers\Auth\OtpController::class, 'sendOtp'])->name('otp.send');
    Route::post('/otp/verify', [\App\Http\Controllers\Auth\OtpController::class, 'verifyOtp'])->name('otp.verify');

    // Password Reset Routes
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset'])->name('password.update');
});

// --- Google Auth Routes ---
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// --- Authenticated User Routes ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    // Route::get('/matches', [PageController::class, 'matches'])->name('matches'); // Old static route
    Route::get('/matches', \App\Livewire\MatchList::class)->name('matches'); // New Livewire route
    Route::get('/requests', [PageController::class, 'requests'])->name('requests');
    Route::post('/requests/{id}/accept', [PageController::class, 'acceptRequest'])->name('requests.accept');
    Route::post('/requests/{id}/decline', [PageController::class, 'declineRequest'])->name('requests.decline');

    // --- Profile Completion Routes ---
    Route::get('/complete-profile', [\App\Http\Controllers\ProfileCompletionController::class, 'show'])->name('profile.complete');
    Route::post('/complete-profile', [\App\Http\Controllers\ProfileCompletionController::class, 'store'])->name('profile.complete.store');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/verify-id', [ProfileController::class, 'verifyId'])->name('profile.verify-id');
    Route::post('/profile/verify-id', [ProfileController::class, 'storeVerifyId'])->name('profile.verify-id.store');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{user}', [PageController::class, 'viewProfile'])->name('profile.view');
    Route::post('/profile/{user}/send-interest', [PageController::class, 'sendInterest'])->name('profile.send-interest');
    Route::post('/profile/{user}/toggle-shortlist', [PageController::class, 'toggleShortlist'])->name('profile.toggle-shortlist');
    Route::get('/shortlist', [PageController::class, 'shortlist'])->name('shortlist');
    Route::get('/messages', [\App\Http\Controllers\MessagesController::class, 'index'])->name('messages');
    Route::get('/messages/chat/{user}', [\App\Http\Controllers\MessagesController::class, 'getChat'])->name('messages.chat');
    Route::post('/messages/send/{user}', [\App\Http\Controllers\MessagesController::class, 'sendMessage'])->name('messages.send');
    Route::get('/messages/new/{user}', [\App\Http\Controllers\MessagesController::class, 'getNewMessages'])->name('messages.new');
    Route::get('/report/{user}', [\App\Http\Controllers\ReportController::class, 'create'])->name('report.create');
    Route::post('/report/{user}', [\App\Http\Controllers\ReportController::class, 'store'])->name('report.store');
    
    // Block Routes
    Route::post('/block/{user}', [\App\Http\Controllers\BlockController::class, 'block'])->name('block.block');
    Route::delete('/block/{user}', [\App\Http\Controllers\BlockController::class, 'unblock'])->name('block.unblock');
    
    Route::post('/subscribe/{membership}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
    
    // Payment routes
    Route::get('/payment/create/{membership}', [\App\Http\Controllers\PaymentController::class, 'createOrder'])->name('payment.create');
    Route::post('/payment/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::post('/payment/failure', [\App\Http\Controllers\PaymentController::class, 'failure'])->name('payment.failure');
    
    // Notifications routes
    Route::get('/notifications', [\App\Http\Controllers\NotificationsController::class, 'index'])->name('notifications');
    Route::get('/notifications/get', [\App\Http\Controllers\NotificationsController::class, 'getNotifications'])->name('notifications.get');
    Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\NotificationsController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationsController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationsController::class, 'delete'])->name('notifications.delete');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationsController::class, 'getUnreadCount'])->name('notifications.unread-count');
});

// --- ADMIN PANEL ROUTES ---
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', AdminUserController::class)->except(['create', 'store']);
    Route::resource('memberships', MembershipController::class);
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'show'])->name('reports.show');
    Route::patch('/reports/{report}/status', [\App\Http\Controllers\Admin\ReportController::class, 'updateStatus'])->name('reports.update-status');
    Route::post('/reports/bulk-update', [\App\Http\Controllers\Admin\ReportController::class, 'bulkUpdate'])->name('reports.bulk-update');
    
    // Interest History
    Route::get('/interests', [\App\Http\Controllers\Admin\InterestHistoryController::class, 'index'])->name('interests.index');
    Route::get('/interests/user/{user}', [\App\Http\Controllers\Admin\InterestHistoryController::class, 'userHistory'])->name('interests.user');
    
    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
        Route::post('/update', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('update');
        
        // Language Management
        Route::get('/language', [\App\Http\Controllers\Admin\SettingsController::class, 'language'])->name('language');
        Route::post('/language', [\App\Http\Controllers\Admin\SettingsController::class, 'storeLanguage'])->name('language.store');
        Route::put('/language/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'updateLanguage'])->name('language.update');
        Route::delete('/language/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'deleteLanguage'])->name('language.delete');
        
        // Highest Education Management
        Route::get('/highest-education', [\App\Http\Controllers\Admin\SettingsController::class, 'highestEducation'])->name('highest-education');
        Route::post('/highest-education', [\App\Http\Controllers\Admin\SettingsController::class, 'storeHighestEducation'])->name('highest-education.store');
        Route::put('/highest-education/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'updateHighestEducation'])->name('highest-education.update');
        Route::delete('/highest-education/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'deleteHighestEducation'])->name('highest-education.delete');
        
        // Education Details Management
        Route::get('/education-details', [\App\Http\Controllers\Admin\SettingsController::class, 'educationDetails'])->name('education-details');
        Route::post('/education-details', [\App\Http\Controllers\Admin\SettingsController::class, 'storeEducationDetails'])->name('education-details.store');
        Route::put('/education-details/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'updateEducationDetails'])->name('education-details.update');
        Route::delete('/education-details/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'deleteEducationDetails'])->name('education-details.delete');
        
        // AJAX route for fetching education details by highest qualification
        Route::get('/education-details-by-qualification/{qualificationId}', [\App\Http\Controllers\Admin\SettingsController::class, 'getEducationDetailsByQualification'])->name('education-details.by-qualification');
        
        // Occupation Management
        Route::get('/occupation', [\App\Http\Controllers\Admin\SettingsController::class, 'occupation'])->name('occupation');
        Route::post('/occupation', [\App\Http\Controllers\Admin\SettingsController::class, 'storeOccupation'])->name('occupation.store');
        Route::put('/occupation/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'updateOccupation'])->name('occupation.update');
        Route::delete('/occupation/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'deleteOccupation'])->name('occupation.delete');
        
        // Country Management
        Route::get('/country', [\App\Http\Controllers\Admin\SettingsController::class, 'country'])->name('country');
        Route::post('/country', [\App\Http\Controllers\Admin\SettingsController::class, 'storeCountry'])->name('country.store');
        Route::put('/country/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'updateCountry'])->name('country.update');
        Route::delete('/country/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'deleteCountry'])->name('country.delete');
        
        // State Management
        Route::get('/state', [\App\Http\Controllers\Admin\SettingsController::class, 'state'])->name('state');
        Route::post('/state', [\App\Http\Controllers\Admin\SettingsController::class, 'storeState'])->name('state.store');
        Route::put('/state/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'updateState'])->name('state.update');
        Route::delete('/state/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'deleteState'])->name('state.delete');
        
        // AJAX route for fetching states by country
        Route::get('/states-by-country/{countryId}', [\App\Http\Controllers\Admin\SettingsController::class, 'getStatesByCountry'])->name('states.by-country');
        
        // City Management
        Route::get('/city', [\App\Http\Controllers\Admin\SettingsController::class, 'city'])->name('city');
        Route::post('/city', [\App\Http\Controllers\Admin\SettingsController::class, 'storeCity'])->name('city.store');
        Route::put('/city/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'updateCity'])->name('city.update');
        Route::delete('/city/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'deleteCity'])->name('city.delete');
        
        // AJAX route for fetching cities by state
        Route::get('/cities-by-state/{stateId}', [\App\Http\Controllers\Admin\SettingsController::class, 'getCitiesByState'])->name('cities.by-state');
        
        // Payment Settings
        Route::get('/payment', [\App\Http\Controllers\Admin\PaymentSettingsController::class, 'index'])->name('payment');
        Route::post('/payment', [\App\Http\Controllers\Admin\PaymentSettingsController::class, 'store'])->name('payment.store');

        // OTP Settings (SMS / Email switch; email uses .env MAIL_*)
        Route::get('/otp', [\App\Http\Controllers\Admin\OtpSettingsController::class, 'index'])->name('otp');
        Route::post('/otp/method', [\App\Http\Controllers\Admin\OtpSettingsController::class, 'updateMethod'])->name('otp.update-method');

        // Hobby Management
        Route::get('/hobby', [\App\Http\Controllers\Admin\SettingsController::class, 'hobby'])->name('hobby');
        Route::post('/hobby', [\App\Http\Controllers\Admin\SettingsController::class, 'storeHobby'])->name('hobby.store');
        Route::put('/hobby/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'updateHobby'])->name('hobby.update');
        Route::delete('/hobby/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'deleteHobby'])->name('hobby.delete');

        // Dependent Dropdowns
        Route::get('/get-states/{country_id}', [\App\Http\Controllers\Admin\SettingsController::class, 'getStates'])->name('get-states');
        Route::get('/get-cities/{state_id}', [\App\Http\Controllers\Admin\SettingsController::class, 'getCities'])->name('get-cities');
        Route::get('/get-educations/{qualification_id}', [\App\Http\Controllers\Admin\SettingsController::class, 'getEducations'])->name('get-educations');
    });
});

// --- Dynamic Location Routes ---
Route::post('/get-countries', [PageController::class, 'getCountries'])->name('getCountries');
Route::post('/get-states', [PageController::class, 'getStates'])->name('getStates');
Route::post('/get-cities', [PageController::class, 'getCities'])->name('getCities');

// --- TEMPORARY: Add missing columns to users table ---
// TODO: Remove this route after running once
Route::get('/fix-users-table', function () {
    try {
        $existingColumns = [];
        $columns = DB::select("SHOW COLUMNS FROM users");
        foreach ($columns as $column) {
            $existingColumns[] = $column->Field;
        }

        $output = "<h2>Current columns in users table:</h2><ul>";
        foreach ($existingColumns as $col) {
            $output .= "<li>{$col}</li>";
        }
        $output .= "</ul><hr>";

        $added = [];
        $skipped = [];
        $errors = [];

        // Add basic columns
        $columnsToAdd = [
            'full_name' => "ALTER TABLE `users` ADD COLUMN `full_name` VARCHAR(255) NULL AFTER `name`",
            'profile_image' => "ALTER TABLE `users` ADD COLUMN `profile_image` VARCHAR(255) NULL",
            'gender' => "ALTER TABLE `users` ADD COLUMN `gender` VARCHAR(255) NULL",
            'height' => "ALTER TABLE `users` ADD COLUMN `height` VARCHAR(255) NULL",
            'weight' => "ALTER TABLE `users` ADD COLUMN `weight` VARCHAR(255) NULL",
            'dob' => "ALTER TABLE `users` ADD COLUMN `dob` DATE NULL",
            'birth_time' => "ALTER TABLE `users` ADD COLUMN `birth_time` VARCHAR(255) NULL",
            'birth_place' => "ALTER TABLE `users` ADD COLUMN `birth_place` VARCHAR(255) NULL",
            'nakshtra' => "ALTER TABLE `users` ADD COLUMN `nakshtra` VARCHAR(255) NULL",
            'naadi' => "ALTER TABLE `users` ADD COLUMN `naadi` VARCHAR(255) NULL",
            'marital_status' => "ALTER TABLE `users` ADD COLUMN `marital_status` VARCHAR(255) NULL",
            'mother_tongue' => "ALTER TABLE `users` ADD COLUMN `mother_tongue` VARCHAR(255) NULL",
            'physically_handicap' => "ALTER TABLE `users` ADD COLUMN `physically_handicap` VARCHAR(255) NULL",
            'diet' => "ALTER TABLE `users` ADD COLUMN `diet` VARCHAR(255) NULL",
            'languages_known' => "ALTER TABLE `users` ADD COLUMN `languages_known` TEXT NULL",
            'employed_in' => "ALTER TABLE `users` ADD COLUMN `employed_in` VARCHAR(255) NULL",
            'annual_income' => "ALTER TABLE `users` ADD COLUMN `annual_income` VARCHAR(255) NULL",
            'mobile_number' => "ALTER TABLE `users` ADD COLUMN `mobile_number` VARCHAR(255) NULL",
            'google_id' => "ALTER TABLE `users` ADD COLUMN `google_id` VARCHAR(255) NULL",
            'role' => "ALTER TABLE `users` ADD COLUMN `role` ENUM('user', 'admin') DEFAULT 'user'",
        ];

        foreach ($columnsToAdd as $columnName => $sql) {
            if (in_array($columnName, $existingColumns)) {
                $skipped[] = $columnName;
                continue;
            }
            try {
                DB::statement($sql);
                $added[] = $columnName;
            } catch (\Exception $e) {
                $errorMsg = $e->getMessage();
                if (strpos($errorMsg, 'Duplicate column name') !== false) {
                    $skipped[] = $columnName;
                } else {
                    $errors[] = "{$columnName}: {$errorMsg}";
                }
            }
        }

        // Add unique constraint for mobile_number if column was just added
        if (in_array('mobile_number', $added)) {
            try {
                DB::statement("ALTER TABLE `users` ADD UNIQUE INDEX `users_mobile_number_unique` (`mobile_number`)");
            } catch (\Exception $e) {
                // Ignore if constraint already exists
            }
        }

        // Add unique constraint for google_id if column was just added
        if (in_array('google_id', $added)) {
            try {
                DB::statement("ALTER TABLE `users` ADD UNIQUE INDEX `users_google_id_unique` (`google_id`)");
            } catch (\Exception $e) {
                // Ignore if constraint already exists
            }
        }

        // Add foreign key columns if missing
        $foreignKeys = [
            'highest_education_id' => 'highest_qualification_master',
            'occupation_id' => 'occupation_master',
            'country_id' => 'country_manage',
            'state_id' => 'state_master',
            'city_id' => 'city_master',
        ];

        foreach ($foreignKeys as $columnName => $referencedTable) {
            if (in_array($columnName, $existingColumns)) {
                $skipped[] = $columnName;
                continue;
            }
            try {
                // Check if referenced table exists
                $tableExists = DB::select("SHOW TABLES LIKE '{$referencedTable}'");
                if (empty($tableExists)) {
                    $errors[] = "{$columnName}: Referenced table '{$referencedTable}' does not exist";
                    continue;
                }

                Schema::table('users', function ($table) use ($columnName, $referencedTable) {
                    $table->foreignId($columnName)->nullable()->constrained($referencedTable)->onDelete('set null');
                });
                $added[] = $columnName;
            } catch (\Exception $e) {
                $errorMsg = $e->getMessage();
                if (strpos($errorMsg, 'Duplicate column name') !== false || 
                    strpos($errorMsg, 'already exists') !== false) {
                    $skipped[] = $columnName;
                } else {
                    $errors[] = "{$columnName}: {$errorMsg}";
                }
            }
        }

        $output .= "<h2>Results:</h2>";
        $output .= "<p><strong>Added:</strong> " . (empty($added) ? 'None' : implode(', ', $added)) . "</p>";
        $output .= "<p><strong>Skipped (already exist):</strong> " . (empty($skipped) ? 'None' : implode(', ', $skipped)) . "</p>";
        if (!empty($errors)) {
            $output .= "<p><strong>Errors:</strong></p><ul>";
            foreach ($errors as $error) {
                $output .= "<li>{$error}</li>";
            }
            $output .= "</ul>";
        }

        return response($output, 200)->header('Content-Type', 'text/html');
    } catch (\Exception $e) {
        return response("Error: " . $e->getMessage() . "<br><pre>" . $e->getTraceAsString() . "</pre>", 500)
            ->header('Content-Type', 'text/html');
    }
})->name('fix.users.table');

// --- TEMPORARY: Check and fix user role ---
Route::get('/check-role/{email}', function ($email) {
    $user = \App\Models\User::where('email', $email)->first();
    if (!$user) {
        return response("User not found", 404);
    }
    
    // Get raw database value
    $rawRole = DB::table('users')->where('email', $email)->value('role');
    $currentRole = $user->role;
    
    // Force update directly in database
    DB::table('users')->where('email', $email)->update(['role' => 'user']);
    
    // Refresh and verify
    $user->refresh();
    $newRawRole = DB::table('users')->where('email', $email)->value('role');
    
    // Clear any cached sessions for this user
    DB::table('sessions')->where('user_id', $user->id)->delete();
    
    return response("User: {$email}<br>" .
        "Raw DB role (before): " . ($rawRole ?? 'NULL') . "<br>" .
        "Model role (before): " . ($currentRole ?? 'NULL') . "<br>" .
        "Raw DB role (after): " . ($newRawRole ?? 'NULL') . "<br>" .
        "Model role (after): " . $user->role . "<br><br>" .
        "Sessions cleared. Please log out and log back in.", 200)
        ->header('Content-Type', 'text/html');
})->name('check.role');

// --- TEMPORARY: Generate test user credentials for chat testing ---
Route::get('/generate-chat-users', function () {
    $user1 = \App\Models\User::firstOrCreate(
        ['email' => 'alice@test.com'],
        [
            'full_name' => 'Alice Johnson',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
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
    $user2 = \App\Models\User::firstOrCreate(
        ['email' => 'bob@test.com'],
        [
            'full_name' => 'Bob Smith',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
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
        $hasInterest = \Illuminate\Support\Facades\DB::table('user_interests')
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
            \Illuminate\Support\Facades\DB::table('user_interests')->insert([
                'sender_id' => $user1->id,
                'receiver_id' => $user2->id,
                'status' => 'accepted',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    } catch (\Exception $e) {
        // Table might not exist
    }
    
    $output = "<h2>Test User Credentials for Chat Testing</h2>";
    $output .= "<div style='font-family: monospace; padding: 20px; background: #f5f5f5; margin: 20px 0;'>";
    $output .= "<h3>User 1:</h3>";
    $output .= "<p><strong>Email:</strong> alice@test.com</p>";
    $output .= "<p><strong>Password:</strong> password123</p>";
    $output .= "<p><strong>Name:</strong> Alice Johnson</p>";
    $output .= "<p><strong>ID:</strong> {$user1->id}</p>";
    $output .= "</div>";
    
    $output .= "<div style='font-family: monospace; padding: 20px; background: #f5f5f5; margin: 20px 0;'>";
    $output .= "<h3>User 2:</h3>";
    $output .= "<p><strong>Email:</strong> bob@test.com</p>";
    $output .= "<p><strong>Password:</strong> password123</p>";
    $output .= "<p><strong>Name:</strong> Bob Smith</p>";
    $output .= "<p><strong>ID:</strong> {$user2->id}</p>";
    $output .= "</div>";
    
    $output .= "<p style='color: green;'><strong>✓ Mutual interest created - Users can now chat!</strong></p>";
    $output .= "<p><strong>Note:</strong> Both users have accepted interest status, so they can send messages to each other.</p>";
    
    return response($output)->header('Content-Type', 'text/html');
})->name('generate.chat.users');

// --- TEMPORARY: Reset user password ---
Route::get('/reset-password/{email}', function ($email) {
    $user = \App\Models\User::where('email', $email)->first();
    if (!$user) {
        return response("User not found", 404);
    }
    
    // Get old password hash for comparison
    $oldHash = $user->getAttributes()['password'] ?? 'N/A';
    
    // Set a new password (will be auto-hashed by Laravel's 'hashed' cast)
    $newPassword = 'Password123'; // Change this to the desired password
    
    // Set password directly - Laravel's 'hashed' cast will handle it
    $user->password = $newPassword;
    $user->save();
    
    // Refresh to get the new hash
    $user->refresh();
    $newHash = $user->getAttributes()['password'];
    
    // Verify the password was hashed correctly
    $isHashed = \Illuminate\Support\Facades\Hash::check($newPassword, $newHash);
    
    // Test Auth::attempt (logout first to clear any existing session)
    \Illuminate\Support\Facades\Auth::logout();
    $authTest = \Illuminate\Support\Facades\Auth::attempt(['email' => $email, 'password' => $newPassword]);
    \Illuminate\Support\Facades\Auth::logout(); // Logout after test
    
    return response("Password reset for {$email}.<br><br>" .
        "Old hash: " . substr($oldHash, 0, 30) . "...<br>" .
        "New password: {$newPassword}<br>" .
        "New hash: " . substr($newHash, 0, 30) . "...<br>" .
        "Hash check: " . ($isHashed ? 'PASSED' : 'FAILED') . "<br>" .
        "Auth::attempt test: " . ($authTest ? 'PASSED' : 'FAILED') . "<br><br>" .
        "You can now log in with:<br>Email: {$email}<br>Password: {$newPassword}", 200)
        ->header('Content-Type', 'text/html');
})->name('reset.password');

// --- TEMPORARY: Generate test user credentials ---
Route::get('/generate-user', function () {
    $email = 'testuser@example.com';
    $password = 'Test123456';
    
    // Get existing columns from users table
    $existingColumns = [];
    try {
        $columns = DB::select("SHOW COLUMNS FROM users");
        foreach ($columns as $column) {
            $existingColumns[] = $column->Field;
        }
    } catch (\Exception $e) {
        return response("Error checking database columns: " . $e->getMessage(), 500)
            ->header('Content-Type', 'text/html');
    }
    
    // Check if user already exists
    $existingUser = \App\Models\User::where('email', $email)->first();
    
    if ($existingUser) {
        // Update password if user exists
        $existingUser->password = $password;
        if (in_array('role', $existingColumns)) {
            // Force update role to 'user' directly in database
            DB::table('users')->where('email', $email)->update(['role' => 'user']);
            $existingUser->refresh();
        }
        $existingUser->save();
        $user = $existingUser;
    } else {
        // Prepare user data with only existing columns
        $userData = [
            'email' => $email,
            'password' => $password, // Will be auto-hashed by Laravel's 'hashed' cast
        ];
        
        // Add fields only if columns exist
        if (in_array('full_name', $existingColumns)) {
            $userData['full_name'] = 'Test User';
        }
        if (in_array('role', $existingColumns)) {
            $userData['role'] = 'user';
        }
        if (in_array('mobile_number', $existingColumns)) {
            $userData['mobile_number'] = '1234567890';
        }
        
        // Create new user
        $user = \App\Models\User::create($userData);
    }
    
    // Verify password was hashed
    $user->refresh();
    $hashCheck = \Illuminate\Support\Facades\Hash::check($password, $user->getAttributes()['password']);
    
    // Test Auth::attempt
    \Illuminate\Support\Facades\Auth::logout();
    $authTest = \Illuminate\Support\Facades\Auth::attempt(['email' => $email, 'password' => $password]);
    \Illuminate\Support\Facades\Auth::logout();
    
    // Verify role in database
    $dbRole = DB::table('users')->where('email', $email)->value('role');
    $modelRole = $user->role;
    
    return response("Test user created/updated successfully!<br><br>" .
        "<strong>Credentials:</strong><br>" .
        "Email: {$email}<br>" .
        "Password: {$password}<br><br>" .
        "<strong>Role Verification:</strong><br>" .
        "Database role: " . ($dbRole ?? 'NULL') . "<br>" .
        "Model role: " . ($modelRole ?? 'NULL') . "<br><br>" .
        "<strong>Verification:</strong><br>" .
        "Password hash check: " . ($hashCheck ? 'PASSED' : 'FAILED') . "<br>" .
        "Auth::attempt test: " . ($authTest ? 'PASSED' : 'FAILED') . "<br><br>" .
        "You can now log in with these credentials.", 200)
        ->header('Content-Type', 'text/html');
})->name('generate.user');

// --- Dynamic Routes ---
Route::get('/get-educations/{id}', [PageController::class, 'getEducations'])->name('get-educations');


