<?php

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'min:4'],
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    Auth::login($user);

    return redirect('/dashboard')->with('success', 'Registration successful.');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/dashboard')->with('success', 'Login successful.');
    }

    return back()->with('error', 'Invalid email or password.')->onlyInput('email');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login')->with('success', 'Logged out successfully.');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $userId = Auth::id();
        $present = Attendance::where('user_id', $userId)->where('status', 'Present')->count();
        $absent = Attendance::where('user_id', $userId)->where('status', 'Absent')->count();
        $late = Attendance::where('user_id', $userId)->where('status', 'Late')->count();

        return view('dashboard', [
            'userCount' => User::count(),
            'attendanceCount' => Attendance::where('user_id', $userId)->count(),
            'presentCount' => $present,
            'chartData' => [$present, $absent, $late],
        ]);
    });

    Route::get('/users', function () {
        return view('users.index', ['users' => User::latest()->get()]);
    });

    Route::post('/users', function (Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:4'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'User added successfully.');
    });

    Route::put('/users/{user}', function (Request $request, User $user) {
        $validated = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return back()->with('success', 'User updated successfully.');
    });

    Route::delete('/users/{user}', function (User $user) {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own logged-in account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    });

    Route::get('/attendance', function () {
        $records = Attendance::where('user_id', Auth::id())->latest()->get();
        return view('attendance.index', ['records' => $records]);
    });

    Route::post('/attendance', function (Request $request) {
        $validated = $request->validate([
            'student_name' => ['required', 'max:255'],
            'attendance_date' => ['required', 'date'],
            'status' => ['required', 'in:Present,Absent,Late'],
            'remarks' => ['nullable', 'max:255'],
        ]);

        $validated['user_id'] = Auth::id();
        Attendance::create($validated);

        return back()->with('success', 'Attendance record added successfully.');
    });

    Route::put('/attendance/{attendance}', function (Request $request, Attendance $attendance) {
        abort_if($attendance->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'student_name' => ['required', 'max:255'],
            'attendance_date' => ['required', 'date'],
            'status' => ['required', 'in:Present,Absent,Late'],
            'remarks' => ['nullable', 'max:255'],
        ]);

        $attendance->update($validated);

        return back()->with('success', 'Attendance record updated successfully.');
    });

    Route::delete('/attendance/{attendance}', function (Attendance $attendance) {
        abort_if($attendance->user_id !== Auth::id(), 403);
        $attendance->delete();

        return back()->with('success', 'Attendance record deleted successfully.');
    });

    Route::get('/profile', function () {
        return view('profile.index');
    });

    Route::put('/profile', function (Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . Auth::id()],
            'address' => ['nullable', 'max:255'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    });
});
