<?php
// dd('api.php loading');
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\MatchesController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\MessagesController;
use App\Http\Controllers\Api\NotificationsController;
use App\Http\Controllers\Api\MembershipController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Routes
Route::prefix('v1')->group(function () {
    
    // Authentication Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/otp/send', [AuthController::class, 'sendOtp']);
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp']);
    Route::post('/google/login', [AuthController::class, 'googleLogin']);
    
    // Public Data Routes
    Route::get('/countries', [LocationController::class, 'getCountries']);
    Route::post('/states', [LocationController::class, 'getStates']);
    Route::post('/cities', [LocationController::class, 'getCities']);
    Route::get('/mother-tongues', [LocationController::class, 'getMotherTongues']);
    Route::get('/raashis', [LocationController::class, 'getRaashis']);
    Route::get('/nakshatras', [LocationController::class, 'getNakshatras']);
    Route::get('/occupations', [LocationController::class, 'getOccupations']);
    Route::get('/highest-qualifications', [LocationController::class, 'getHighestQualifications']);
    
    // Protected Routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Authentication
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);
        
        // Profile Routes
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto']);
        Route::get('/profile/{user}', [ProfileController::class, 'view']);
        
        // Matches Routes
        Route::get('/matches', [MatchesController::class, 'index']);
        Route::get('/matches/recommended', [MatchesController::class, 'recommended']);
        
        // Search Routes
        Route::get('/search', [SearchController::class, 'search']);
        
        // Interest Routes
        Route::post('/interest/{user}', [InterestController::class, 'send']);
        Route::post('/interest/{id}/accept', [InterestController::class, 'accept']);
        Route::post('/interest/{id}/decline', [InterestController::class, 'decline']);
        Route::get('/requests', [InterestController::class, 'requests']);
        Route::get('/requests/incoming', [InterestController::class, 'incoming']);
        Route::get('/requests/sent', [InterestController::class, 'sent']);
        Route::post('/shortlist/{user}', [InterestController::class, 'toggleShortlist']);
        Route::get('/shortlist', [InterestController::class, 'shortlist']);
        
        // Messages Routes
        Route::get('/messages', [MessagesController::class, 'index']);
        Route::get('/messages/chat/{user}', [MessagesController::class, 'getChat']);
        Route::post('/messages/send/{user}', [MessagesController::class, 'sendMessage']);
        Route::get('/messages/new/{user}', [MessagesController::class, 'getNewMessages']);
        Route::post('/messages/{message}/read', [MessagesController::class, 'markAsRead']);
        
        // Notifications Routes
        Route::get('/notifications', [NotificationsController::class, 'index']);
        Route::get('/notifications/unread-count', [NotificationsController::class, 'unreadCount']);
        Route::post('/notifications/{id}/read', [NotificationsController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationsController::class, 'markAllAsRead']);
        Route::put('/notifications/settings', [NotificationsController::class, 'updateSettings']);
        
        // Membership Routes
        Route::get('/memberships', [MembershipController::class, 'index']);
        Route::get('/memberships/current', [MembershipController::class, 'current']);
        Route::post('/memberships/{membership}/subscribe', [MembershipController::class, 'subscribe']);
        
        // Payment Routes
        Route::post('/payment/create/{membership}', [\App\Http\Controllers\Api\PaymentController::class, 'createOrder']);
        Route::post('/payment/verify', [\App\Http\Controllers\Api\PaymentController::class, 'verifyPayment']);
        
        // Report Routes
        Route::get('/report/{user}', [ReportController::class, 'create']);
        Route::post('/report/{user}', [ReportController::class, 'store']);
        
        // Block Routes
        Route::post('/block/{user}', [\App\Http\Controllers\Api\BlockController::class, 'block']);
        Route::delete('/block/{user}', [\App\Http\Controllers\Api\BlockController::class, 'unblock']);
        Route::get('/block/{user}/check', [\App\Http\Controllers\Api\BlockController::class, 'check']);
        Route::get('/blocks', [\App\Http\Controllers\Api\BlockController::class, 'index']);
    });
});

