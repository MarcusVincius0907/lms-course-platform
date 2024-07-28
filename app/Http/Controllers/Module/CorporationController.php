<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Alert;
use App\Model\Certificate;
use App\Model\Corporation;
use App\Model\Coupon;
use App\Model\Enrollment;
use App\Model\Student;
use App\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class CorporationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //show all category and search here
    public function index(Request $request)
    {
        $corporation = [];
        
        if ($request->get('search')) {
            $search = $request->search;
            $corporation = Corporation::where('name', 'like', '%' . $search . '%')
                ->paginate(10);
        } else {
            $corporation = Corporation::paginate(10);
        }

        return view('module.corporation.index', compact('corporation'));
    }

    //create category model
    public function create()
    {
        $courses = Course::all();
        return view('module.corporation.create', compact('courses'));
    }

    //store the category
    public function store(Request $request)
    {

        $error_message = 'Não foi possível criar a corporação.';

        try{
            if (env('DEMO') === "YES") {
                Alert::warning('warning', 'This is demo purpose only');
                return back();
            }
    
            $request->validate([
                'logo' => 'required',
                'name' => 'required',
                'email' => ['required', 'email'],
                'path' => ['required', 'unique:corporations,path'],
                'colors' => 'required',
            ], [
                'logo.required' => 'Um logo deve ser fornecido',
                'name' => 'O nome deve ser preenchido',
                'email.required' => 'O campo Email deve ser preenchido',
                'path.required' => 'O campo URL deve ser preenchido',
                'path.unique' => 'Já existe essa URL em outra comporação',
                'colors.required' => 'O campo Cor deve ser preenchido',
            ]);
    
            
    
            $corp = new Corporation();
            $corp->logo = fileUpload($request->logo, 'corporation');
            $corp->name = $request->name;
            $corp->email = $request->email;
            $corp->path = $request->path;
            $corp->colors = $request->colors;
            $corp->courses = $request->courses ? $request->courses : '[]';

            $corp->save();

            
    
            try{
                $this->createStudents($request->students, $corp);
            }catch(Exception $e){
                Corporation::where('id', $corp->id)->delete();
                $error_message = 'Erro ao tentar inserir alunos. Verifique se eles já não existem ou se o formato inserido obedece os padrões.';
                return redirect()->back()->with('status-error', $error_message);
            }
            
            return redirect()->back()->with('status-success', 'Corporação criada com sucesso!');
        }catch(Exception $e){
            return redirect()->back()->with('status-error', $error_message);
        }

    }

    //edit category model
    public function edit($id)
    {
        
        $corporation = Corporation::findOrFail($id);
        $courses = Course::all();
        return view('module.corporation.edit', compact('corporation', 'courses'));
    }

    //update the category
    public function update(Request $request)
    {

        $error_message = 'Não foi possível editar a corporação.';
        
        try{

            if (env('DEMO') === "YES") {
                Alert::warning('warning', 'This is demo purpose only');
                return back();
            }
    
            $request->validate([
                'name' => 'required',
                'email' => ['required', 'email'],
                'path' => ['required', 'unique:corporations,path,'. $request->id],
                'colors' => 'required',
                ], [
                    'name' => 'O nome deve ser preenchido',
                    'email.required' => 'O campo Email deve ser preenchido',
                    'path.required' => 'O campo URL deve ser preenchido',
                    'path.unique' => 'Já existe essa URL em outra comporação',
                    'colors.required' => 'O campo Cor deve ser preenchido',
            ]);
    
    
            $corp =  Corporation::where('id', $request->id)->first();
    
            if($request->logoChanged  && $request->logo)
                $corp->logo = fileUpload($request->logo, 'corporation');
                
            $corp->name = $request->name;
            $corp->path = $request->path;
            $corp->colors = $request->colors;
            $corp->courses = $request->courses ? $request->courses : '[]' ;

            $corp->save();

            
            if($request->students){
                try{
                    $this->createStudents($request->students, $corp);
                }catch(Exception $e){
                    $error_message = 'Erro ao tentar inserir alunos. Verifique se eles já não existem ou se o formato inserido obedece os padrões.';
                    return redirect()->back()->with('status-error', $error_message);
                }
            }else{
                $this->checkEnrollWhenUpdateCourses($corp);
            }
    
            
            return redirect()->back()->with('status-success', 'Corporação editada com sucesso!');

        }catch(Exception $e){

            return redirect()->back()->with('status-error', $error_message);

        }

        
    }

    //soft delete the category
    public function destroy($id)
    {

        if (env('DEMO') === "YES") {
            Alert::warning('warning', 'This is demo purpose only');
            return back();
        }

        try{
            $corporation = Corporation::where('id', $id)->first();
            $this->deleteCorporationStudents($corporation->id);
            $corporation->delete();
            return redirect()->back()->with('status-success', 'Corporação deletada com sucesso!');
        }catch(Exception $e){
            return redirect()->back()->with('status-error', 'Não foi possível deletar');

        }

    }

    //published
    public function published(Request $request)
    {

        if (env('DEMO') === "YES") {
        Alert::warning('warning', 'This is demo purpose only');
        return back();
      }

        // don't use this type of variable naming, use $category instead of $cat1
        $corp = Corporation::where('id', $request->id)->first();
        if ($corp->is_published == 1) {
            $corp->is_published = 0;
            $corp->save();
        } else {
            $corp->is_published = 1;
            $corp->save();
        }
        return response(['message' => 'Status da corporação mudou'], 200);
    }

    public function students($corporation_id){
        $corporation = Corporation::where('id', $corporation_id)->first();
        $students = Student::where('corporation_id', $corporation_id)->paginate(10);
        return view('module.corporation.students', compact('corporation', 'students'));
    }

    public function courses($corporation_id){
        $corporation = Corporation::where('id', $corporation_id)->first();
        $courses = coursesJsonToArray($corporation->courses);
        
        return view('module.corporation.courses', compact('corporation', 'courses'));
    }

    private function createStudents($json, $corp){

        $students = json_decode($json);
        
        if(is_array($students)){
            

            $this->deleteCorporationStudents($corp->id);


            foreach ($students as $s) {
                //create user for login
                $user = new User();
                $user->name = $s->name;
                $user->email = $s->email;
                $user->password = Hash::make('12345678');
                $user->user_type = 'Student';
                $user->save();
    
                //create student
                $student = new Student();
                $student->name = $s->name;
                $student->email = $s->email;
                $student->cpf = $s->cpf;
                $student->user_id = $user->id;
                $student->corporation_id = $corp->id;
                $student->save();

                //enroll students in the courses
                $courses = coursesJsonToArray($corp->courses);
                foreach($courses as $course){
                    $enrollment = new Enrollment();
                    $enrollment->user_id = $student->user_id; //this is student id
                    $enrollment->course_id = $course->id;
                    $enrollment->save();
                }
            }

        }


        
    }

    private function deleteCorporationStudents($corporation_id){

        //find existent students from this corporation and delete all
        $corp_students = Student::where('corporation_id', $corporation_id)->get();
        

        foreach ($corp_students as $s) {

            $enrolls = Enrollment::where('user_id', $s->user_id )->get();   
            $certs = Certificate::where('user_id', $s->user_id)->get();


            //may not be possible delete student if he has a certificate
            try{

                $user_id = $s->user_id;
                $s->delete();
                $enrolls->each->delete();
                $certs->each->delete();
                $user = User::where('id', $user_id)->first();
                $user->delete();

                
            }catch(Exception $e){
            }
        }
    }

    private function checkEnrollWhenUpdateCourses($corp){
        
        $corp_students = Student::where('corporation_id', $corp->id)->get();

        if($corp_students && count($corp_students) > 0){

            foreach($corp_students as $cp){
                
                $enroll = Enrollment::where('user_id', $cp->user_id)->get();

                $enroll->each->delete();

                $courses = coursesJsonToArray($corp->courses);

                foreach($courses as $course){
                    
                    $enrollment = new Enrollment();
                    $enrollment->user_id = $cp->user_id; //this is student id
                    $enrollment->course_id = $course->id;
                    $enrollment->save();

                }

            }

        }
    }

    

}
