<?php

namespace App\Http\Controllers;

use App\Model\Corporation;
use App\Model\Course;
use App\Model\CourseComment;
use App\Model\Enrollment;
use App\Model\SeenContent;
use App\Model\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CorporationStudentController extends Controller
{

    function __construct()
    {
        $this->middleware(['installed']);
    }

    public function login($corporation_path, Request $request){
        $corp = Corporation::where('path', $corporation_path)->where('is_published', 1)->first();
        if($corp)
            return view('corporationStudent.login', compact('corp'));
        else
            return view('corporationStudent.notFound');   
    } 

    public function auth($corporation_path, Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (
            Auth::attempt($credentials) && 
            Student::where('email', $request->email)->whereNotNull('corporation_id')->exists() 
        ) {
            return redirect()->route('corporationStudent.home',['corporation_path'=>$corporation_path]);
        }
 
        return back()->withErrors([
            'email' => 'Email não encontrado.',
        ])->onlyInput('email');
    }

    public function password_reset($corporation_path, Request $request)
    {
        $corp = Corporation::where('path', $corporation_path)->first();
        return view('corporationStudent.password.email',['corporation_path'=>$corporation_path], compact('corp'));
    }

    public function home($corporation_path, Request $request){
        $corp = Corporation::where('path', $corporation_path)->first();
        $enrolls = Enrollment::with('enrollCourse')->where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
        
        return view('corporationStudent.home',['corporation_path'=>$corporation_path], compact('corp', 'enrolls'));
    }

    //lesson_details
    public function lesson_details($corporation_path, $slug)
    {
        $corp = Corporation::where('path', $corporation_path)->first();
        $s_course = null;
        if (zoomActive()){
            $s_course = Course::Published()->where('slug', $slug)->with('classes')->with('meeting')->first(); // single course details
        }else{
            $s_course = Course::Published()->where('slug', $slug)->with('classes')->first(); // single course details
        }
        /*check enroll this course*/
        $enroll = Enrollment::where('course_id', $s_course->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
        if ($enroll->count() == 0) {
            return back();
        }
        $comments = CourseComment::latest()->with('user')->get();

        return view('corporationStudent.lesson_details', ['corporation_path'=>$corporation_path],compact('s_course', 'comments','enroll', 'corp'));
    }

    
}