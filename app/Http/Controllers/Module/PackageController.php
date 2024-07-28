<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Model\Instructor;
use App\Model\Package;
use Illuminate\Http\Request;
use Alert;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*Show all packages list*/
    public function index()
    {
        $packages = Package::latest()->get();
        return view('package.index', compact('packages'));
    }

    /*Create package */
    public function create()
    {
        return view('package.create');
    }

    /*Store the package*/
    public function store(Request $request)
    {

        if (env('DEMO') === "YES") {
        Alert::warning('warning', 'This is demo purpose only');
        return back();
      }

        $request->validate([
            'image' => 'required',
            'title' => 'required',
            'items' => 'required',
            /* 'price' => 'required', */
            'commission' => 'required'
        ], [
            'image.required' => "Imagem é um campo obrigatório",
            'title.require' => "Título é um campo obrigatório",
            'commission.require' => "Comissão é um campo obrigatório",
            'items.require' => "Itens é um campo obrigatório"
        ]);

        $package = new Package();
        if ($request->has('image')) {
            $package->image = $request->image;
        }
        /* $package->price = $request->price; */
        $package->commission = $request->commission;
        $package->title = $request->title;
        $req = explode(',',$request->items);
        $reqC = array();
        foreach ($req as $item){
            array_push($reqC,$item);
        }
        $package->items = json_encode($reqC);
        $package->is_published = true;
        $package->save();
        notify()->success(translate('Package created successfully'));
        return back();
    }

    /*Show Edit Form*/
    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view('package.edit', compact('package'));
    }

    /*Update the package*/
    public function update(Request $request)
    {

        if (env('DEMO') === "YES") {
            Alert::warning('warning', 'This is demo purpose only');
            return back();
          }
    
        $request->validate([
            'image' => 'required',
            'title' => 'required',
            'items' => 'required',
            /* 'price' => 'required', */
            'commission' => 'required'
        ], [
            'image.required' => "Imagem é um campo obrigatório",
            'title.require' => "Título é um campo obrigatório",
            'commission.require' => "Comissão é um campo obrigatório",
            'items.require' => "Itens é um campo obrigatório"
        ]);

        $package = Package::where('id', $request->id)->first();
        if ($request->has('image')) {
            $package->image = $request->image;
        }
        /* $package->price = $request->price; */
        $package->commission = $request->commission;
        $package->title = $request->title;
        $req = explode(',',$request->items);
        $reqC = array();
        foreach ($req as $item){
            array_push($reqC,$item);
        }
        $package->items = json_encode($reqC);
        $package->is_published = true;
        $package->save();
        notify()->success(translate('Package created successfully'));
        return back();
    }

    /*Destroy the package if instructor have the package*/
    public function destroy($id)
    {

        if (env('DEMO') === "YES") {
        Alert::warning('warning', 'This is demo purpose only');
        return back();
      }

        $instructors = Instructor::where('package_id', $id)->count();
        if ($instructors > 0) {
            notify()->warning(translate('Failed! This package is already in use.'));
            return back();
        }
        Package::where('id', $id)->forceDelete();
        notify()->success(translate('Package is deleted successfully'));
        return back();
    }

    public function published(Request $request)
    {

        if (env('DEMO') === "YES") {
        Alert::warning('warning', 'This is demo purpose only');
        return back();
      }

        // don't use this type of variable naming, use $category instead of $cat1
        $package = Package::where('id', $request->id)->first();
        if ($package->is_published == 1) {
            $package->is_published = 0;
            $package->save();
        } else {
            $package->is_published = 1;
            $package->save();
        }
        return response(['message' => 'Status do Plano mudou'], 200);
    }
    //END
}
