<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Alert;
use App\Model\Coupon;
use Exception;

class CouponController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //show all category and search here
    public function index(Request $request)
    {
        $coupons = null;
        
        if ($request->get('search')) {
            $search = $request->search;
            $coupons = Coupon::where('code', 'like', '%' . $search . '%')->where('user_id', auth()->user()->id)
                ->paginate(10);
        } else {
            $coupons = Coupon::where('user_id', auth()->user()->id)->paginate(10);
        }
        
        return view('module.coupon.index', compact('coupons'));
    }

    //create category model
    public function create()
    {
        $courses = Course::where('user_id', auth()->user()->id)->get();
        return view('module.coupon.create', compact('courses'));
    }

    //store the category
    public function store(Request $request)
    {


        if (env('DEMO') === "YES") {
        Alert::warning('warning', 'This is demo purpose only');
        return back();
      }

        $request->validate([
            'code' => ['required', 'unique:coupons', 'string'],
            'percent' => ['required', 'numeric', 'max:100', 'min:0'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'start_date' => 'required',
            'end_date' => 'required',
        ], [
            'code.required' => 'O campo Código deve ser preenchido',
            'code.unique' => 'O código deve ser único',
            'percent.required' => 'O campo Desconto deve ser preenchido',
            'percent.max' => 'O desconto é no máximo 100%',
            'percent.min' => 'O desconto é no minimo 0%',
            'percent.numeric' => 'O desconto deve ser numérico',
            'quantity.required' => 'O campo Quantidade deve ser preenchido',
            'quantity.numeric' => 'O campo Quantidade deve ser numérico',
            'quantity.min' => 'O desconto é no minimo 0',
            'start_date.required' => 'Algo de errado com o Data',
            'end_date.required' => 'Algo de errado com o Data',
        ]);


        $coupon = new Coupon();
        $coupon->user_id = auth()->user()->id;
        $coupon->code = $request->code;
        $coupon->percent = $request->percent;
        $coupon->quantity = $request->quantity;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;

        if($request->courses != 'all'){
            $coupon->course_id = $request->courses;
        }

        $coupon->save();

        
        notify()->success('Cupom criado com sucesso');
        return redirect()->back();
    }

    //edit category model
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $courses = Course::where('user_id', auth()->user()->id)->get();
        return view('module.coupon.edit', compact('coupon', 'courses'));
    }

    //update the category
    public function update(Request $request)
    {

        if (env('DEMO') === "YES") {
        Alert::warning('warning', 'This is demo purpose only');
        return back();
      }

      $request->validate([
            'percent' => ['required', 'numeric', 'max:100', 'min:0'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'start_date' => 'required',
            'end_date' => 'required',
        ], [
            'percent.required' => 'O campo Desconto deve ser preenchido',
            'percent.max' => 'O desconto é no máximo 100%',
            'percent.min' => 'O desconto é no minimo 0%',
            'percent.numeric' => 'O desconto deve ser numérico',
            'quantity.required' => 'O campo Quantidade deve ser preenchido',
            'quantity.numeric' => 'O campo Quantidade deve ser numérico',
            'quantity.min' => 'O desconto é no minimo 0',
            'start_date.required' => 'Algo de errado com o Data',
            'end_date.required' => 'Algo de errado com o Data',
        ]);


        $coupon = Coupon::where('id', $request->id)->first();
        $coupon->percent = $request->percent;
        $coupon->quantity = $request->quantity;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;

        if($request->courses != 'all'){
            $coupon->course_id = $request->courses;
        }else
            $coupon->course_id = null;

        $coupon->save();

        notify()->success('Cupom atualizado com sucesso');
        return back();
    }

    //soft delete the category
    public function destroy($id)
    {

        if (env('DEMO') === "YES") {
            Alert::warning('warning', 'This is demo purpose only');
            return back();
        }

        try{
            Coupon::where('id', $id)->delete();
            notify()->success('Cupom deletado!');
            return back();
        }catch(Exception $e){
            notify()->error('Não foi possível deletar');
            return back();
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
        $coupon = Coupon::where('id', $request->id)->first();
        if ($coupon->is_published == 1) {
            $coupon->is_published = 0;
            $coupon->save();
        } else {
            $coupon->is_published = 1;
            $coupon->save();
        }
        return response(['message' => 'Status do cupom mudou'], 200);
    }

    public function apply(Request $request){

        try{

            $is_valid = verifyCupom($request->code, $request->instructor_id, $request->course_id);
    
            if($is_valid)
                return response(['message' => 'Cupom válido']);
            else    
                return response(['message' => 'Cupom não é valido']);
        }catch(Exception $e){
            return response(['message' => 'Erro nesse processo']);
        }

    }

}
