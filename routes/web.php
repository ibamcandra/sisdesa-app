<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\JobList;
use App\Livewire\JobDetail;
use App\Livewire\PostList;
use App\Livewire\PostDetail;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Profile;
use App\Livewire\ApplicationHistory;
use App\Livewire\OrganizationView;
use App\Livewire\AboutView;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

Route::get('/', Home::class)->name('home');
Route::get('/job', JobList::class)->name('job.index');
Route::get('/organization', OrganizationView::class)->name('organization');
Route::get('/about', AboutView::class)->name('about');
Route::get('/job/{slug}', JobDetail::class)->name('job.show');
Route::get('/kabar', PostList::class)->name('post.index');
Route::get('/kabar/{slug}', PostDetail::class)->name('post.show');

// Auth Routes
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::get('/forgot-password', ForgotPassword::class)->middleware('guest')->name('password.request');
Route::get('/reset-password/{token}', ResetPassword::class)->middleware('guest')->name('password.reset');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Rute Verifikasi yang lebih fleksibel
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    // Validasi Hash (Keamanan)
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return redirect()->route('login')->with('error', 'Link verifikasi tidak valid.');
    }

    // Tandai sebagai terverifikasi jika belum
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));
    }

    // Login-kan user secara otomatis agar langsung bisa buka profil
    Auth::login($user);

    return redirect()->route('profile')->with('verified', true);
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Protected Routes
Route::middleware(['auth', 'applicant'])->group(function () {
    Route::get('/profile', Profile::class)->middleware(['verified'])->name('profile');
    Route::get('/history', ApplicationHistory::class)->name('history');
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

// Admin Custom Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/applicants/{applicant}/cv', [App\Http\Controllers\Admin\ApplicantDetailController::class, 'show'])
        ->name('admin.applicant.cv');
});

Route::get('/welcome', function () {
    return view('welcome');
});
