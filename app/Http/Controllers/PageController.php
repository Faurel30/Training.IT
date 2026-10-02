<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Profile;

class PageController extends Controller
{
    // Menampilkan halaman utama (Landing Page)
    public function landing()
    {
        return view('landing'); 
    }

    // Menampilkan halaman daftar program
    public function programs()
    {
        return view('programs'); 
    }

    // Menampilkan halaman profile user
    public function profile()
    {
        return view('profile'); 
    }

    // Halaman About Us
    public function about()
    {
        return view('about');
    }

    // Menampilkan halaman gender
    public function gender()
    {
        if (Auth::check() && Auth::user()->gender) {
            return redirect()->route('programs');
        }

        return view('gender');
    }

    // Menyimpan pilihan gender lalu lanjut ke programs
    public function saveGender(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'required|in:male,female',
        ]);

        Session::put('gender', $validated['gender']);

        if (Auth::check()) {
            $user = Auth::user();
            $user->gender = $validated['gender'];
            $user->save();
        }

        return redirect()->route('programs');
    }

    // Menyimpan pilihan program lalu lanjut ke halaman workout yang sesuai
    public function selectProgram(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'required|integer',
            'type' => 'required|in:gym,cardio,calisthenic',
        ]);

        Session::put('program_id', $validated['program_id']);
        Session::put('program_type', $validated['type']);

        return redirect()->route('workout.detail', ['type' => $validated['type']]);
    }

    // Menampilkan halaman workout berdasarkan tipe program
    public function workout($type)
    {
        $validTypes = ['gym', 'cardio', 'calisthenic'];

        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $profileCompleted = false;
        $user = Auth::user();

        if ($user) {
            $profile = Profile::where('user_id', $user->id)->first();
            $profileCompleted = $profile
                && $profile->isCompleteFor($user);
        }

        return view('workout.' . $type, compact('profileCompleted'));
    }
}