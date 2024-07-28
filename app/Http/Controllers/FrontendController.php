<?php

namespace App\Http\Controllers;


use App\Blog;
use App\Http\Middleware\Affiliate;
use App\Model\AdminEarning;
use App\Model\AffiliateHistory;
use App\Model\AffiliatePayment;
use App\Model\Cart;
use App\Model\Category;
use App\Model\ClassContent;
use App\Model\Classes;
use App\Model\Course;
use App\Model\CourseComment;
use App\Model\CoursePurchaseHistory;
use App\Model\Demo;
use App\Model\Enrollment;
use App\Model\Instructor;
use App\Model\InstructorEarning;
use App\Model\Language;
use App\Model\Massage;
use App\Model\Package;
use App\Model\PackagePurchaseHistory;
use App\Model\SeenContent;
use App\Model\Slider;
use App\Model\Student;
use App\Model\StudentAccount;
use App\Model\VerifyUser;
use App\Model\Wishlist;
use App\Notifications\AffiliateCommission;
use App\Notifications\EnrolmentCourse;
use App\Notifications\InstructorRegister;
use App\Notifications\StudentRegister;
use App\Notifications\VerifyNotifications;
use App\NotificationUser;
use App\Page;
use App\QuizScore;
use App\Subscription;
use App\SubscriptionCart;
use App\SubscriptionEnrollment;
use App\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Hash;
use Alert;
use App\Model\Affiliate as ModelAffiliate;
use App\Model\Certificate;
use App\Model\CertificateTemplates;
use App\Model\Coupon;
use App\Model\CouponHistory;
use App\Model\OrderItem;
use App\Model\PaymentPagar;
use DateTime;
use App\Model\Orders;
use chillerlan\QRCode\QRCode;

class FrontendController extends Controller
{

    private  $theme = 'frontend';
    function __construct()
    {
        $this->theme = themeManager();
        $this->middleware(['installed','verifypayment']);
    }

    function userNotify($user_id,$details)
    {
        $notify = new NotificationUser();
        $notify->user_id = $user_id;
        $notify->data = $details;
        $notify->save();
    }


    /*Search the courses*/
    public function searchCourses(Request $request)
    {

        if ($request->key == null) {
            $courses = null;
        } else {
            $courses = Course::Published()->where('title', 'LIKE', "%{$request->key}%")->get();
        }


        $search = collect();


        if ($courses == null) {
            return response(['data' => $search], 200);
        } else {
            if ($courses->count() > 0) {
                foreach ($courses as $item) {
                    $demo = new Demo();
                    $demo->title = Str::limit($item->title, 58);
                    $demo->image = filePath($item->image);
                    $demo->link = route('course.single', $item->slug);
                    $search->push($demo);
                }

            } else {
                $demo = new Demo();
                $demo->title = translate('No Course Found');
                $demo->image = null;
                $demo->link = null;
                $search->push($demo);
            }
        }
        return response(['data' => $search], 200);

    }

    /*filer courses and show all course*/
    public function courseFilter(Request $request)
    {

        $breadcrumb = null;

        $conditions = [];
        /*single instructor*/
        if ($request->input('instructor')) {
            $conditions = array_merge($conditions, ['user_id' => $request->input('instructor')]);
        }
        /*free paid check here*/
        if ($request->input('cost')) {
            $cost = $request->cost;
            if ($cost == 'paid') {
                $conditions = array_merge($conditions, ['is_free' => false]);
            } elseif ($cost == "free") {
                $conditions = array_merge($conditions, ['is_free' => true]);
            } else {

            }

        }
        /*single language*/
        if ($request->input('language')) {
            $conditions = array_merge($conditions, ['language' => $request->input('language')]);
        }

        /*level */

        if ($request->input('level')) {
            if ($request->level == "3") {
                $breadcrumb = 'Todos os níveis';
            } else {
                $conditions = array_merge($conditions, ['level' => $request->input('level')]);
                $breadcrumb = $request->input('level');
            }


        }
        /*categories*/
        if ($request->input('categories')) {
            $conditions = array_merge($conditions, ['category_id' => $request->input('categories')]);
            $breadcrumb = Category::where('id', $request->input('categories'))->first()->name;

        }
        $courses = Course::Published()->where($conditions)->latest()->paginate(8);
        $languages = Language::all();

        //check the category in parent for chide
        if ($request->slug == null) {
            $categories = Category::where('parent_category_id', 0)->Published()->get();
        } else {
            $cat = Category::where('slug', $request->slug)->Published()->first();

            if ($cat->parent_category_id == 0) {
                //this is parent category
                $categories = Category::where('parent_category_id', $cat->id)->Published()->get();

            } else {
                //this is child category
                $categories = Category::where('parent_category_id', $cat->parent_category_id)->Published()->get();
            }
        }


        return view($this->theme.'.course.course_grid',
            compact('categories', 'courses', 'languages', 'breadcrumb'));

    }

    /*this is the home page*/
    public function homepage()
    {


        //check DB table for migration and Update Column
        if (Schema::hasTable('class_contents') && Schema::hasColumn('class_contents', 'provider')) {
            DB::statement("ALTER TABLE `class_contents` CHANGE provider provider ENUM('Youtube','HTML5','Vimeo','File','Live','Quiz')");
            DB::statement("ALTER TABLE `class_contents` CHANGE content_type content_type ENUM('Video','Document','Quiz')");
        }




        //slider
        $sliders = Slider::where('is_published', 1)->get();

        //Popular Categories
        $popular_cat = Category::Published()->where('is_popular', 1)->get();


        //top courses it's depend op enroll courses
        $enroll_courser_count = DB::table('enrollments')->select('enrollments.course_id',
            DB::raw('count(enrollments.course_id) as total_course'))
            ->orderByDesc('total_course')
            ->groupBy('course_id')->get();
        $courses = collect();
        
        foreach ($enroll_courser_count as $e) {
            $co = Course::Published()->find($e->course_id);
            if($co)
                $courses->push($co);
        }
        $top_courses = $courses->take(6);
        

        //here the calculation for top category with top courses
        $course = collect();
        $cat = collect();
        if (env('ACTIVE_THEME') == 'frontend'){
            $course->push($top_courses->take(6));
        }
        $cat->push('Best Selling');

        foreach (Category::Published()->where('top', 1)->get() as $item) {
            
            $cat->push($item->name);
            $course->push($courses->where('category_id', $item->id)->take(6));

        }


        //trading course week
        $start = Carbon::today()->toDateTimeString();
        $end = Carbon::today()->subDays(7)->toDateTimeString();
        $trading_courses = $courses->whereBetween('created_at', [$end, $start])->skip(6)->take(12);

        

        //if trading_course is 0
        if ($trading_courses->count() == 0) {
            $trading_courses = $courses->shuffle()->take(12);
        }


        $packages = Package::where('is_published', true)->get();


        $latestCourses = Course::Published()->with('relationBetweenInstructorUser')->latest()->take(10)->get();

        $subscriptions = Subscription::Published()->get();


        return view($this->theme.'.homepage.index', compact('latestCourses', 'packages', 'subscriptions', 'sliders', 'popular_cat', 'course', 'cat', 'trading_courses', 'enroll_courser_count'));
    }


    /*Show category ways course*/
    public function courseCat(Request $request)
    {

        try{

            $breadcrumb = null;
            //check the category in parent for chide
            $cat = Category::where('slug', $request->slug)->first();
            $catId = array();
            $catId = array_merge($catId, [$cat->id]);
            if ($cat->parent_category_id == 0) {
                //this is parent category
                $categories = Category::where('parent_category_id', $cat->id)->Published()->get();
                //all child category id
                foreach ($categories as $item) {
                    $catId = array_merge($catId, [$item->id]);
                }
    
            } else {
                //this is child category
                $categories = Category::where('parent_category_id', $cat->parent_category_id)->Published()->get();
                $catId = array_merge($catId, [$cat->id]);
            }
    
            //category ways course
            $courses = Course::Published()->whereIn('category_id', $catId)->latest()->paginate(10);
    
            $languages = Language::all();
    
            //rating collect
            $rating = collect();
            for ($i = 1; $i <= 5; $i++) {
                $demo = new Demo();
                $demo->index = $i;
                $demo->total_course = $courses->where('rating', $i)->count();
                $rating->push($demo);
            }
    
            $insId = array();
            //instructors
            foreach ($courses as $c) {
                $insId = array_merge($insId, [$c->user_id]);
            }
    
    
            return view($this->theme.'.course.course_grid',
                compact('categories', 'courses', 'languages', 'rating', 'breadcrumb'));

        }catch(\Exception $e){
            Alert::warning('warning', 'Não foi possível acessar essa rota');
            return redirect()->back();

        }

        
    }

    /*Single course details*/
    public function singleCourse($slug)
    {
        try{

            $l_courses = Course::Published()->latest()->take(3)->get(); // single course details
            $sug_courses = Course::Published()->take(8)->get()->shuffle(); // suggession courses
            $s_course = Course::Published()->where('slug', $slug)->with('classes')->first(); // single course details
    
            return view($this->theme.'.course.course_details', compact('s_course', 'l_courses', 'sug_courses'));
        
        }catch(\Exception $e){
            Alert::warning('warning', 'Não foi possível acessar essa rota');
            return redirect()->back();

        }

    }

    /*Content preview*/
    public function contentPreview($id)
    {
        $content = ClassContent::findOrFail($id);
        return view($this->theme.'.course.preview', compact('content'));
    }


    /*currencies change*/
    public function currenciesChange(Request $request)
    {
        session(['currency' => $request->id]);
        Artisan::call('optimize:clear');
        return back();
    }

    /*languages change*/
    public function languagesChange(Request $request)
    {
        session(['locale' => $request->code]);
        Artisan::call('optimize:clear');
        return back();
    }


    //lesson_details
    public function lesson_details($slug)
    {
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

        return view($this->theme.'.course.lesson.lesson_details', compact('s_course', 'comments','enroll'));
    }


    //cart
    public function cart()
    {
        return view($this->theme.'.cart.index');
    }

    //dashboard
    public function dashboard()
    {
        $notifications = NotificationUser::latest()->where('user_id', Auth::user()->id)->get();
        return view($this->theme.'.dashboard.index', compact('notifications'));
    }

    //my_profile
    public function my_profile()
    {
        $student = User::where('id', Auth::user()->id)->with('student')->first();
        return view($this->theme.'.profile.index', compact('student'));
    }

    //enrolled_course
    public function enrolled_course()
    {
        return view($this->theme.'.enrolled.index');
    }


    //purchase_history
    public function purchase_history()
    {
        $orders = Orders::where('user_id', Auth::user()->id)->orderBy('created_at','DESC')->get();
        
        return view($this->theme.'.purchase_history.index', compact('orders'));
    }

    //purchase_history
    public function purchase_history_detail($id)
    {
        $order = Orders::where('user_id', Auth::user()->id)->where('id', $id)->first();
        return view($this->theme.'.purchase_history.show', compact('order'));
    }


    //login
    public function login()
    {
        return view($this->theme.'.auth.login');
    }

    //register
    public function signup()
    {
        return view('auth.signup');
    }

    //register
    public function create(Request $request)
    {

        if (env('DEMO') === "YES") {
        Alert::warning('warning', 'This is demo purpose only');
        return back();
      }

        // registration validation
        $request->validate(
            [
                'name' => 'required',
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['required', 'numeric'],
                'cpf' => ['required', 'numeric'],
                'password' => ['required', 'string', 'min:8'],
                'confirmed' => 'required|required_with:password|same:password',
                'street' => 'required',
                'street_number' => 'required',
                'neighborhood' => 'required',
                'city' => 'required',
                'state' => ['required', 'max:2'],
                'country' => ['required', 'max:2'],
                'zipcode' => ['required', 'max:8', 'min:8']

            ],
            [
                'name.required' => translate('Name is required'),
                'phone.required' => 'Número do celular é obrigatório ',
                'phone.numeric' => 'Número do celular deve conter apenas números ',
                'cpf.required' => 'CPF é um campo obrigatório ',
                'cpf.numeric' => 'Número do celular deve conter apenas números ',
                'email.required' => translate('Email is required'),
                'email.unique' => translate('Email is already register'),
                'password.required' => translate('Password is required'),
                'password.min' => translate('Password  must be 8 character '),
                'password.string' => translate('Password is required'),
                'confirmed.required' => translate('Please confirm your password'),
                'confirmed.same' => translate('Password did not match'),
                'street.required' => 'Rua é um campo obrigatório',
                'street_number.required' => 'Número é um campo obrigatório',
                'neighborhood.required' => 'Bairro é um campo obrigatório',
                'city.required' => 'Cidade é um campo obrigatório',
                'state.required' => 'Estado é um campo obrigatório',
                'state.max' => 'São no máximo 2 letras',
                'country.required' => 'País é um campo obrigatório',
                'country.max' => 'São no máximo 2 letras',
                'zipcode.required' => 'CEP é um campo obrigatório',
                'zipcode.max' => 'CEP é máximo 8 numeros',
                'zipcode.min' => 'CEP é mínimo 8 numeros',
            ]

        );

        //create user for login
        $user = new User();
        $user->name = $request->name;
        $user->slug = Str::slug($request->name);
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->user_type = 'Student';
        $user->save();

        //create student
        $student = new Student();
        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->cpf = $request->cpf;
        $student->user_id = $user->id;
        $address = [
            'street' => $request->street,
            'street_number' => $request->street_number,
            'neighborhood' => $request->neighborhood,
            'city' => $request->city,
            'state' => strtolower($request->state),
            'country' => strtolower($request->country),
            'zipcode' =>$request->zipcode
        ];
        $student->address = formatAddress('string', $address);
        $student->save();

        /*here is the student */
        try {
            $user->notify(new StudentRegister());

            VerifyUser::create([
                'user_id' => $user->id,
                'token' => sha1(time())
            ]);
            
            

            // send verify mail
            $user->notify(new VerifyNotifications($user));

        } catch (\Exception $exception) {
        }

        Session::flash('message', translate("Registration done successfully. Please verify your email."));
        return redirect()->route('login');


    }

    /*page with content*/
    public function page($slug)
    {
        $page = Page::with('content')->where('slug', $slug)->firstOrFail();
        return view($this->theme.'.page.index', compact('page'));
    }

    // password reset
    public function password_reset()
    {
        return view($this->theme.'.auth.email');
    }

    // student_edit
    public function student_edit()
    {
        $student = User::where('id', Auth::user()->id)->first();
        $address = formatAddress('array', $student->student->address);
        
        
        return view($this->theme.'.profile.update', compact('student', 'address'));
    }

    // update
    public function update(Request $request, $std_id)
    {

        if (env('DEMO') === "YES") {
        Alert::warning('warning', 'This is demo purpose only');
        return back();
      }
      
      

        // registration validation
        $request->validate(
            [
                'name' => 'required',
                'phone' => ['required', 'numeric'],
                'cpf' => ['required', 'numeric'],
                'street' => 'required',
                'street_number' => 'required',
                'neighborhood' => 'required',
                'city' => 'required',
                'state' => ['required', 'max:2'],
                'country' => ['required', 'max:2'],
                'zipcode' => 'required'

            ],
            [
                'name.required' => translate('Name is required'),
                'phone.required' => 'Número do celular é obrigatório ',
                'phone.numeric' => 'Número do celular deve conter apenas números ',
                'cpf.required' => 'CPF é um campo obrigatório ',
                'cpf.numeric' => 'Número do celular deve conter apenas números ',
                'street.required' => 'Rua é um campo obrigatório',
                'street_number.required' => 'Número é um campo obrigatório',
                'neighborhood.required' => 'Bairro é um campo obrigatório',
                'city.required' => 'Cidade é um campo obrigatório',
                'state.required' => 'Estado é um campo obrigatório',
                'state.max' => 'São no máximo 2 letras',
                'country.required' => 'País é um campo obrigatório',
                'country.max' => 'São no máximo 2 letras',
                'zipcode.required' => 'CEP é um campo obrigatório'

            ]
        );
        
        

        //create student
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $student->name = $request->name;
        $student->cpf = $request->cpf;

        $student->phone = $request->phone;
        $address = [
            'street' => $request->street,
            'street_number' => $request->street_number,
            'neighborhood' => $request->neighborhood,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'zipcode' =>$request->zipcode
        ];
        $student->address = formatAddress('string',$address);


        if ($request->file('image')) {
            $student->image = fileUpload($request->file('image'), 'student');
        } else {
            $student->image = $request->oldImage;
        }

        $student->save();

        //create user for login
        $user = User::where('id', Auth::id())->firstOrFail();
        $user->name = $request->name;
        $user->image = $student->image;
        $user->save();

        return back();

    }


    // mark_as_all_read
    public function mark_as_all_read()
    {

        if (env('DEMO') === "YES") {
        Alert::warning('warning', 'This is demo purpose only');
        return back();
      }

        $all_read = NotificationUser::where('user_id', Auth::user()->id)->get();

        foreach ($all_read as $read) {
            NotificationUser::where('user_id', Auth::user()->id)->update([
                'is_read' => true
            ]);
        }

        return back();
    }


    // END


    /*check out*/
    public function enrollCourses()
    {
        
        if (Auth::user()->user_type != "Student") {
            \auth()->logout();
            return response('Your credentials does not match.', 403);
        }

        $enrollCollection = collect();

        $orders = Orders::where('user_id', Auth::id())->get();

        foreach ($orders as $order) {
            if($order->payments_pagar->status == 'waiting_payment'){
                foreach ($order->items as $item_order) {
                    $demo = new Demo();
                    $demo->course_id = $item_order->course_id;
                    $demo->id = $item_order->id;
                    $demo->message = 'Aguardando a confirmação';
                    $demo->link = route('student.purchase.history');
                    $enrollCollection->push($demo);
                }
            }
        }

        $enrolls = Enrollment::where('user_id', Auth::id())->get();

        foreach ($enrolls as $item) {
            $demo = new Demo();
            $demo->course_id = $item->course_id;
            $demo->id = $item->id;
            
            if($item->order->payments_pagar->status == 'waiting_payment'){
                $demo->message = 'Aguardando a confirmação';
                $demo->link = route('student.purchase.history');
            }
            else{
                
                $demo->link = route('lesson_details', Course::find($item->course_id)->slug);
                $demo->message = 'Vá para lição'; 
            }

            $enrollCollection->push($demo);
        }
        return response(['data' => $enrollCollection], 200);
    }

    /*all wishlist*/
    public function wishList()
    {
        if (Auth::user()->user_type != "Student") {
            \auth()->logout();
            return response('Your credentials does not match.', 403);
        }
        $items = Wishlist::with('course')->where('user_id', Auth::id())->get();

        //there are create wish  list
        $wishList = collect();
        foreach ($items as $item) {
            $carts = new Demo();
            $carts->id = $item->id;
            $carts->course_id = $item->course->id;
            $carts->title = Str::limit($item->course->title, 30);
            if ($item->course->is_free == true) {
                $carts->price = formatPrice(0);
            } else {
                if ($item->course->is_discount == true) {
                    $carts->price = formatPrice($item->course->discount_price);
                } else {
                    $carts->price = formatPrice($item->course->price == null ? 0 : $item->course->price);
                }
            }
            $carts->image = filePath($item->course->image);
            $carts->link = route('course.single', $item->course->slug);
            $wishList->push($carts);
        }
        $link = route('my.courses');
        $message = translate('Add to Cart');
        return response(['data' => $wishList, 'link' => $link, 'message' => $message], 200);
    }

    /*add to wishlist*/
    public function addToWishlist(Request $request)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->where('course_id', $request->cart)->first();
        if ($wishlist != null) {
            /*remove wishlist*/
            $wishlist->delete();
            $delete = $request->cart;
        } else {
            $wishlist = new Wishlist();
            $wishlist->user_id = Auth::id();
            $wishlist->course_id = $request->cart;
            $wishlist->save();
            $delete = null;
        }
        /*remove from cart*/
        $cart = Cart::where('user_id', $wishlist->user_id)->where('course_id', $wishlist->course_id)->first();
        if ($cart != null) {
            $cart->delete();
        }
        return response(['id_is' => $delete], 200);
    }

    /*all cart list*/
    public function cartList()
    {
        if (Auth::user()->user_type != "Student") {
            \auth()->logout();
            return response('Your credentials does not match.', 403);
        }

        //there are create cart  list
        $cartList = collect();
        $items = Cart::with('course')->where('user_id', Auth::id())->get();
        foreach ($items as $cart) {
            $carts = new Demo();
            $carts->id = $cart->id;
            $carts->course_id = $cart->course->id;
            $carts->title = Str::limit($cart->course->title, 30);
            if ($cart->course->is_free == true) {
                $carts->price = formatPrice(0);
            } else {
                if ($cart->course->is_discount == true) {
                    $carts->price = formatPrice($cart->course->discount_price);
                } else {
                    $carts->price = formatPrice($cart->course->price == null ? 0 : $cart->course->price);
                }

            }
            $carts->image = filePath($cart->course->image);
            $carts->link = route('course.single', $cart->course->slug);
            $cartList->push($carts);
        }
        $message = translate('Go to Checkout');
        $link = route('shopping.cart');

        return response(['data' => $cartList, 'message' => $message, 'link' => $link], 200);
    }

    /*cart the course*/
    public function addToCart(Request $request)
    {
        $cart = null;
        if (Auth::user()->user_type != "Student") {
            \auth()->logout();
            return response('Your credentials does not match.', 403);
        }
        //get course details
        $course = Course::where('id', $request->cart)->first();

        /*check this have in cart*/
        $p = Cart::where('user_id', Auth::id())->where('course_id', $course->id)->first();

        if ($p != null) {
            /*nothing is save*/
        } else {
            //add to cart

            $orders = Orders::where('user_id', Auth::id())->get();

            $hasToAddToCart = false;

            foreach ($orders as $order) {
                if($order->payments_pagar->status != 'expired'){
                    foreach ($order->items as $item_order) {
                        if(
                            $item_order->course_id == $request->cart
                        ){
                            $hasToAddToCart = true;
                        }
                    }
                }
            }

            if(!$hasToAddToCart){
                $cart = new Cart();
                $cart->user_id = \Illuminate\Support\Facades\Auth::id();
                $cart->course_id = $course->id;
                if ($course->is_free == true) {
                    $cart->course_price = 0;
                } else {
                    if ($course->is_discount == true) {
                        $cart->course_price = $course->discount_price;
                    } else {
                        $cart->course_price = $course->price == null ? 0 : $course->price;
                    }
    
                }
                $cart->save();
            }

        }
        /*remove from wishlist*/
        $wishlist = Wishlist::where('user_id', Auth::id())->where('course_id', $course->id)->first();
        if ($wishlist != null) {
            $wishlist->delete();
        }
        return $cart;
    }

    /*add to cart remove*/
    public function removeCart($id)
    {
        $carts = Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return back();
    }

    /*Shopping car View page*/
    public function shoppingCart(Request $request)
    {

        if ($request->courses != null){
            $carts = Cart::with('course')->where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
            if ($carts->count() > 0) {
                return view($this->theme.'.cart.index', compact('carts'));
            }
            return redirect()->route('my.courses');
        }else{
            return  redirect()->route('shopping.cart',['courses'=>'ok']);
        }

    }

    public function pendingPayment(Request $request){
        
        try{

            $payment_method = $request->session()->get('payment');
    
            if($payment_method != null){
    
    
                $carts = Cart::with('course')->where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
                $payment_info = PaymentPagar::where('user_id', auth()->user()->id)->where('id', $request->id)->first();
                
                $order = new Orders();
                $order->user_id = auth()->user()->id;
                $order->payment_id = $payment_info->id;
                $order->save();
        
                foreach ($carts as $cart) {
    
    
                    $order_item = new OrderItem();
                    $order_item->order_id = $order->id;
                    $order_item->course_id = $cart->course_id;
                    $order_item->save();
        
                    $cart->delete();
                }
    
                $request->session()->forget('payment');
    
    
                if($payment_method == 'paid'){
                    $request->session()->put('checkout', 'ok');
                    return redirect()->route('checkout', $order->payment_id);
                }
        
                return redirect('/student/purchase/history/'.$order->id);
            }else{
                $request->session()->forget('payment');
                Alert::warning('Error', 'Metodo de pagamento não identificado');
                return redirect()->back();
            }
        }catch(\Exception $e){
            $request->session()->forget('payment');
            Alert::warning('Error', 'Algo deu errado.');
            return redirect()->back();
        }


            
    }


    /*remove from wishlist*/
    public function removeWishlist($id)
    {
        Wishlist::destroy($id);
        return response('', 200);
    }

    /*checkout this is common feature*/
    public function checkout(Request $request, $id)
    {

        if (env('DEMO') === "YES") {
            Alert::warning('warning', 'This is demo purpose only');
            return back();
        }

        
        $value = $request->session()->get('checkout');
        

        if (walletActive()) {
            $payment_type = $request->session()->get(walletName());
        }

        try{

            /*get data from cart and delete from cart Add in,
                    Enrollment and save purchase history*/
            if ($value != null) {  
                $request->session()->forget('checkout'); 
                $order = Orders::where('user_id', auth()->user()->id)->with('payments_pagar')->where('payment_id', $id)->first();
                

                $this->finishCheckout($order);

                

                
                /*empty the session*/
                if (walletActive()) {
                    if($payment_type)
                    {
    
                        try {
                            
                            // Paid Course Point
                            $request->session()->forget(walletName());
                            addWallet(paidPoint(), translate('Paid Course Enroll point'));
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
    
                    }
                }
    
            }else {
                $request->session()->forget('checkout');      
                \auth()->logout();
            }
    
    
            
            Session::flash('message', translate('Congratulations, Your enrollment is done successfully.'));
            return redirect()->route('my.courses');
        }catch(\Exception $e){
            Alert::warning('Error', 'Não foi possível efetuar o checkout');
            return redirect()->back();
        }



    }

    public function finishCheckout($order){
        
        $affiliate_get = 0;
        if ($order && $order->items->count() > 0) {
            foreach ($order->items as $order_item) {


                //save in enrolments table
                $enrollment = new Enrollment();
                $enrollment->user_id = $order->user_id; //this is student id
                $enrollment->order_id =  $order->id;
                $enrollment->course_id = $order_item->course->id;
                $enrollment->save();
                
                /*if this course in wishlist delete it*/
                Wishlist::where('user_id', Auth::id())->where('course_id', $order_item->course->id)->delete();

                //todo::there are calculate the Instructor balance Calculate the admin or Instructor commission
                $course = Course::findOrFail($order_item->course->id); //get course
                $instructor = Instructor::where('user_id', $course->user_id)->first(); //get instructor
                $package = Package::findOrFail($instructor->package_id);//get instructor package commission
                $admin_get = 0;
                $instructor_get = 0;
                $amount_price = course_price($course);

                $couponHistory = CouponHistory::where('user_id',  Auth::id())->where('course_id', $course->id )->latest('created_at')->first();

                if ($amount_price) {

                    if($couponHistory && $couponHistory != null){
                        $amount_price = $amount_price - $couponHistory->discount;
                    }

                    $admin_get = ($amount_price * $package->commission) / 100; //$admin commission
                    $instructor_get = ($amount_price - $admin_get); //instructor amount
                    /*todo::refer calculate*/
                    $affiliate_get += ($amount_price * commission()) / 100; //

                }


                //admin earning
                //Todo::Admin Earning calculation
                $admin = new AdminEarning();
                $admin->amount = $admin_get;
                $admin->purposes = "Comissão por inscrição";
                $admin->save();


                // student get notification
                $details = [
                    'body' => translate('You enrolled new course  ' . $course->title),
                ];
                $this->userNotify($order->user_id, $details);

                // instructor get notification
                $details = [
                    'body' => translate($course->title . ' this course enrolled by ' . Auth::user()->name),
                ];
                $this->userNotify($course->user_id, $details);

                //todo::Instructor Earning history
                //instructor Earning
                $instructorEarning = new InstructorEarning();
                $instructorEarning->enrollment_id = $enrollment->id;
                $instructorEarning->package_id = $package->id;
                $instructorEarning->user_id = $instructor->user_id; //instructor user_id
                $instructorEarning->course_price = course_price($order_item->course);
                $instructorEarning->will_get = $instructor_get;
                $instructorEarning->save();

                //todo::update the instructor balance
                $instructor->balance += $instructor_get;
                $instructor->save();

                //save in purchase history
                $history = new CoursePurchaseHistory();
                $history->enrollment_id = $enrollment->id;
                $history->amount = $amount_price;
                $history->payment_method = $order->payments_pagar->payment_method;
                $history->save();


                //todo::mail Admin, Instructor, Student
                try {
                    //teacher
                    $user = User::find($instructorEarning->user_id);
                    $user->notify(new EnrolmentCourse());
                    //student
                    $user = User::find($order->user_id);
                    $user->notify(new EnrolmentCourse());

                } catch (\Exception $exception) {
                }

                
                $req = request()->cookie('ref');
                if ($req != null && affiliateStatus()) {

                    $affiliate = ModelAffiliate::where('refer_id', $req)->first();
                    $affiliate->balance += $affiliate_get;
                    $affiliate->save();

                    $history = new AffiliateHistory();
                    $history->affiliate_id = $affiliate->id;
                    $history->user_id = \Illuminate\Support\Facades\Auth::id();
                    $history->refer_id = $req;
                    $history->amount = $affiliate_get;
                    $history->save();

                    
                    try {
                        $user = User::where('id', $affiliate->user_id)->first();
                        $user->notify(new AffiliateCommission());
                    }catch (\Exception $exception){}
                }
            }
        }    
    }



    /*affiliate this is common feature*/
    /*affiliate page view*/
    public function affiliateCreate(){

        
        /*here show affiliate history table*/
        $history =null;
        $payment =null;
        $affiliate= \App\Model\Affiliate::where('user_id',Auth::id())->first();
        if ($affiliate){
            $history = AffiliateHistory::where('refer_id',$affiliate->refer_id)->with('user')->paginate(5);//there student id is user id
            $payment =AffiliatePayment::where('status','Confirm')->where('user_id',Auth::id())->paginate(5);
        }
        return view($this->theme.'.homepage.affiliate.index',compact('affiliate','history','payment'));
    }

    /*affiliate request modal screen*/
    public function affiliateRequest(){
        $account = StudentAccount::where('user_id', Auth::id())->first();
        if ($account == null) {
            return view($this->theme.'.homepage.affiliate.request', compact('account'));
        }
        return view($this->theme.'.homepage.affiliate.request', compact('account'));
    }

    /*account save */
    public function affiliateStore(Request $request){

        if (env('DEMO') === "YES") {
        Alert::warning('warning', 'This is demo purpose only');
        return back();
      }

        if ($request->has('id')) {
            $account = StudentAccount::where('id', $request->id)->where('user_id', Auth::id())->first();
            $account->bank_name = $request->bank_name;
            $account->account_name = $request->account_name;
            $account->account_number = $request->account_number;
            $account->routing_number = $request->routing_number;
            $account->paypal_acc_name = $request->paypal_acc_name;
            $account->paypal_acc_email = $request->paypal_acc_email;
            $account->stripe_acc_name = $request->stripe_acc_name;
            $account->stripe_acc_email = $request->stripe_acc_email;
            $account->stripe_card_holder_name = $request->stripe_card_holder_name;
            $account->stripe_card_number = $request->stripe_card_number;
            $account->save();

        } else {
            $account = new StudentAccount();
            $account->bank_name = $request->bank_name;
            $account->account_name = $request->account_name;
            $account->account_number = $request->account_number;
            $account->routing_number = $request->routing_number;
            $account->paypal_acc_name = $request->paypal_acc_name;
            $account->paypal_acc_email = $request->paypal_acc_email;
            $account->stripe_acc_name = $request->stripe_acc_name;
            $account->stripe_acc_email = $request->stripe_acc_email;
            $account->stripe_card_holder_name = $request->stripe_card_holder_name;
            $account->stripe_card_number = $request->stripe_card_number;
            $account->user_id = Auth::id();
            $account->save();
            /*create affiliate details*/
            $af = new \App\Model\Affiliate();
            $af->user_id = Auth::id();
            $af->student_account_id = $account->id;
            $af->note = $request->note;
            $af->save();
        }

        alert(translate('Success'), 'Sua conta foi configurada com sucesso','success');
        return back();
    }

    /*affiliatePaymentRequest*/
    public function affiliatePaymentRequest(){

        $affiliate = \App\Model\Affiliate::where('user_id', Auth::id())->firstOrFail();
        return view($this->theme.'.homepage.affiliate.create',compact('affiliate'));
    }

    /*affiliate payment store*/
    public function affiliatePaymentStore(Request $request){

        if (env('DEMO') === "YES") {
        Alert::warning('warning', 'This is demo purpose only');
        return back();
      }


        if (!$request->has('amount')){
            alert(translate('warning'),translate('Amount must be required'),'info');
            return back();
        }

        if ($request->amount < withdrawLimit()) {
            alert(translate('warning'),translate('You minimum Withdrawal is').withdrawLimit(),'info');
            return back();
        }

        $account = StudentAccount::where('user_id', Auth::id())->first();
        if ($account == null) {
            alert(translate('warning'),translate('Please Insert the withdrawal method '),'info');
            return back();
        }
        $ins = \App\Model\Affiliate::where('user_id', Auth::id())->first();
        if ($ins->balance < $request->amount) {
            alert(translate('warning'),translate('Please insert the valid withdrawal amount '),'info');
            return back();
        }

        /*minus from */
        $ins->balance -=(int)$request->amount;
        $ins->save();

        $payment = new AffiliatePayment();
        $payment->amount = $request->amount;
        $payment->process = $request->process;
        $payment->description = $request->description;
        $payment->status = $request->status;
        $payment->status_change_date = Carbon::now();
        $payment->user_id = Auth::id();
        $payment->affiliate_id = $ins->id;
        $payment->student_account_id = $account->id;
        $payment->saveOrFail();

        $details = [
            'body' => translate('Your payment request is successfully done.'),
        ];

        /* sending instructor notification */
        $this->userNotify(Auth::id(), $details);
        \alert(translate('success'),translate('Payment request sent successfully'),'success');
        return back();
    }



    /*instructor traits*/
    // Instructor details
    public function instructorDetails($slug)
    {
        $user = User::where('slug', $slug)->where('user_type', 'Instructor')->first();

        if ($user == null) {
            Session::flash('message', translate('404 Not Found'));
            return back();
        }
        $courses = Course::Published()->where('user_id', $user->id)->paginate(9);
        $instructor = Instructor::where('user_id', $user->id)->first();
        return view($this->theme.'.instructor.index', compact('instructor', 'courses'));
    }

    /*register view*/
    public function registerView()
    {
        $packages = Package::where('is_published', true)->get();
        return view($this->theme.'.instructor.register', compact('packages'));
    }

    /*register create*/
    public function registerCreate(Request $request)
    {

        try{
            if (env('DEMO') === "YES") {
                Alert::warning('warning', 'This is demo purpose only');
                return back();
            }
    
            $request->validate([
                'package_id' => 'required',
                'name' => 'required',
                'email' => ['required', 'unique:users'],
                'password' => ['required', 'min:8'],
                'confirm_password' => 'required|required_with:password|same:password',
            ], [
                'package_id.required' => 'Por favor, selecione um pacote',
                'name.required' => translate('Name is required'),
                'email.required' => translate('Email is required'),
                'email.unique' => translate('Email is already exist.'),
                'password.required' => translate('Password is required'),
                'password.min' => translate('Password must be minimum 8 characters'),
                'confirm_password.required' => translate('Please confirm your password'),
                'confirm_password.same' => translate('Password did not match'),
            ]);
            /*get package value*/
            $package = Package::where('id', $request->package_id)->firstOrFail();
            //create user for login
    
            $slug_name = Str::slug($request->name);
            /*check the sulg */
            $users = User::where('slug', $slug_name)->get();
            if ($users->count() > 0) {
                $slug_name = $slug_name.($users->count() + 1);
            }
            $user = new User();
            $user->slug = $slug_name;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            $user->user_type = 'Instructor';
            $user->save();
    
            //save data in instructor
            $instructor = new Instructor();
            $instructor->name = $request->name;
            $instructor->email = $request->email;
            $instructor->package_id = $request->package_id;
            $instructor->user_id = $user->id;
            $instructor->save();
    
            /*get package payment*/
            if ($package->price > 0) {
    
                return redirect()->route('instructor.payment', $user->slug);
            } else {
                /**/
    
                //add purchase history
                $purchase = new PackagePurchaseHistory();
                $purchase->amount = $package->price ? $package->price : 0 ;
                $purchase->payment_method = $request->payment_method;
                $purchase->package_id = $request->package_id;
                $purchase->user_id = $user->id;
                $purchase->save();
    
    
                //todo::admin Earning calculation
                $admin = new AdminEarning();
                $admin->amount = $package->price ? $package->price : 0;
                $admin->purposes = "Sale Package";
                $admin->save();
    
                try {
    
                    $user->notify(new InstructorRegister());
    
                    VerifyUser::create([
                        'user_id' => $user->id,
                        'token' => sha1(time())
                    ]);
                    //send verify mail
                    $user->notify(new VerifyNotifications($user));
                } catch (\Exception $exception) {
    
                }
            }
    
            Session::flash('message', translate("Registration done successfully. Please verify your email before login."));
            return redirect()->route('login');
        }catch(\Exception $e){
            Alert::warning('Aviso', 'Não foi possível realizar o cadastro, tente novamente');
            return redirect()->back();
            
        }


        
    }

    /*payment screen view*/
    public function insPayment($slug)
    {
        $userI = User::where('slug', $slug)->where('user_type', 'Instructor')->first();
        if ($userI == null) {
            Session::flash('message', translate('You are wrong user'));
            return back();
        }
        $user = Instructor::with('relationBetweenPackage')->where('user_id', $userI->id)->first();

        //check package payment history
        $history = PackagePurchaseHistory::where('user_id', $user->id)->where('package_id', $user->package_id)->first();
        if ($history != null) {
            return redirect()->route('login');
        } else {
            return view($this->theme.'.instructor.payment', compact('user'));
        }
    }



    /*student trait*/
    public function my_courses()
    {
        //(new PagarController)->paymentValidation(new Request());        
        //enroll courses
        $enrolls = Enrollment::with('enrollCourse')->with('order')->where('user_id', Auth::id())->get();
        $validEnrolls = collect();
        foreach($enrolls as $e){
            if($e->order->payments_pagar->status == 'paid')
            $validEnrolls->push($e);
        }
        $enrolls = $validEnrolls;
        return view($this->theme.'.course.my_courses', compact('enrolls'));
    }

    public function my_wishlist(){
        //wishlist courses
        $wishlists = Wishlist::with('course')->where('user_id', Auth::id())->paginate(6);
        return view($this->theme.'.course.wishlist', compact( 'wishlists'));
    }

    public function test($id){
        //dd((new PagarController)->payTest($id));
        return;
        $cert = Certificate::where('user_id', 112)->where('course_id', $id)->first();
        
        $cert = new Certificate();
        $cert->user_id = 112;
        $cert->course_id = $id;
        $cert->conclusion_date = date('y-m-d' );
        $cert->auth_code = generateCertificateCode();
        $cert->save();

        if(!$cert->certificate_path){

            $student = Student::where('user_id', 112)->first();

            $total_duration = 0;
            foreach ($cert->course->classes as $item){
                $total_duration +=$item->contents->sum('duration');
            }

            $total_duration = round(($total_duration /= 60), 0, PHP_ROUND_HALF_DOWN);

            $cert_template = CertificateTemplates::where('user_id', $cert->course->user_id)->first();

            $obj = (object)[
                'name' => $student->name,
                'cpf' => formatCpf($student->cpf),
                'conclusioDate' => date('d/m/Y', strtotime($cert->conclusion_date)),
                'courseTitle' => $cert->course->title,
                'authCode' => $cert->auth_code,
                'totalDuration' => $total_duration,
                'qrCode' => (new QRCode)->render(route('homepage')),
                
            ];
            return view($this->theme.'.certificate.index', compact('cert', 'obj', 'cert_template'));

        }else{
            return redirect(assetC($cert->certificate_path));
        }
    }

    public function test2(){
        return;
        $user = auth()->user();
        $cert = (object) [
            'name' => $user->name,
            'cpf' =>  $user->cpf,
            'conclusionDate' => date('d/m/Y'),
            'courseName' => 'teste',
            'authCode' => '12345678'
        ];

        $cert_template = CertificateTemplates::where('instructor_id', $cert->course->user_id)->first();

        $obj = (object)[
            'name' => $student->name,
            'cpf' => formatCpf($student->cpf),
            'conclusioDate' => date('d/m/Y', strtotime($cert->conclusion_date)),
            'courseTitle' => $cert->course->title,
            'authCode' => $cert->auth_code,
            'totalDuration' => $total_duration,
            'qrCode' => (new QRCode)->render(route('homepage'))
        ];

        return view($this->theme.'.certificate.index', compact('cert', 'obj'));
    }

    /* CERTIFICATE */

    public function certificateEdited(Request $request){

        $cert = Certificate::where('user_id', auth()->user()->id)->where('course_id', $request->course_id)->first();

        if ($request->file('certificate')) {
            $path = fileUpload($request->file('certificate'), 'certificateNew', $cert->auth_code.'.pdf' );
            $cert->certificate_path = $path;
            $cert->save();
            return  response(['data' => 'file exists' ], 200);
        } else {
            return  response(['data' => 'file does not exist'], 200);
        }

    }

    public function updateProgressCertificateState($id, $course_id){
        $resp = (object) ['progress' => $this->seenCourse($id, $course_id)];
        return response()->json($resp);
    }

    public function issueCertificate($course_id){
        
        $cert = Certificate::where('user_id', auth()->user()->id)->where('course_id', $course_id)->first();
        
        if(!$cert->certificate_path){

            $student = Student::where('user_id', auth()->user()->id)->first();

            $total_duration = 0;
            foreach ($cert->course->classes as $item){
                $total_duration +=$item->contents->sum('duration');
            }

            $total_duration = round(($total_duration /= 60), 0, PHP_ROUND_HALF_DOWN);

            $cert_template = CertificateTemplates::where('user_id', $cert->course->user_id)->first();

            $obj = (object)[
                'name' => $student->name,
                'cpf' => formatCpf($student->cpf),
                'conclusioDate' => date('d/m/Y', strtotime($cert->conclusion_date)),
                'courseTitle' => $cert->course->title,
                'authCode' => $cert->auth_code,
                'totalDuration' => $total_duration,
                'qrCode' => (new QRCode)->render(route('homepage')),
                
            ];
            return view($this->theme.'.certificate.index', compact('cert', 'obj', 'cert_template'));

        }else{
            return redirect(assetC($cert->certificate_path));
        }

    }

    public function saveCertificate($course_id){
        $cert_exists = Certificate::where('user_id', auth()->user()->id)->where('course_id', $course_id)->exists();

        if(!$cert_exists){
            $cert = new Certificate();
            $cert->user_id = auth()->user()->id;
            $cert->course_id = $course_id;
            $cert->conclusion_date = date('y-m-d' );
            $cert->auth_code = generateCertificateCode();
            $cert->save();

            return true;
        }else{
            return false;
        }
    }

    public function my_certificates()
    {
        $certificates = Certificate::where('user_id', Auth::id())->get();
        return view($this->theme.'.certificate.my_certificates', compact('certificates'));
    }

    /* CERTIFICATE */

    /*Calculate the seen course percentage enroll course*/
    public static function seenCourse($id, $course_id)
    {
        $seen_content = SeenContent::where('user_id', Auth::id())->where('enroll_id', $id)->get()->count();
        $course = Course::where('id', $course_id)->with('classes')->first();

        $total_content = 0;
        foreach ($course->classes as $item) {
            $total_content += $item->contents->count();
        }


        // calculate the % done this enroll course
        if ($seen_content > 0 && $total_content!= 0) {
            $percentage = ($seen_content / $total_content) * 100;
            $percentage = $percentage > 100 ? 100 : $percentage;
        } else {
            $percentage = 0;
        }

        return number_format($percentage);
    }

    /*Course Commenting authenticated */
    public function comments(Request $request)
    {

        if (env('DEMO') === "YES") {
        Alert::warning('warning', 'This is demo purpose only');
        return back();
      }

        if ($request->comment_id != null) {
            $comment = new CourseComment();
            $comment->course_id = $request->course_id;
            $comment->user_id = Auth::id();
            $comment->comment = $request->comment;
            $comment->replay = $request->comment_id;
            $comment->save();
        } elseif ($request->comment != null) {
            $comment = new CourseComment();
            $comment->course_id = $request->course_id;
            $comment->user_id = Auth::id();
            $comment->comment = $request->comment;
            $comment->save();
        } else {
        }
        $c = CourseComment::where('course_id', $request->course_id)
            ->with('user')->get();

        $comments = collect();
        foreach ($c as $item) {
            $demo = new Demo();
            $demo->name = $item->user->name;
            $demo->image = $item->user->image != null ? filePath($item->user->image) : asset('uploads/user/user.png');
            $demo->comment = $item->comment;
            $demo->time = $item->created_at->diffForHumans();
            $comments->push($demo);
        }
        return response(['data' => $comments], 200);
    }


    /*message modal view this function need enroll id*/
    public function messageCreate($id)
    {
        $enroll = Enrollment::where('course_id', $id)->where('user_id', Auth::id())->first();
        return view($this->theme.'.message.create', compact('enroll'));
    }

    /*Send message to instructor inbox*/
    public function sendMessage(Request $request)
    {

        if (env('DEMO') === "YES") {
        Alert::warning('warning', 'This is demo purpose only');
        return back();
      }

        $message = new Massage();
        $message->enroll_id = $request->enroll_id;
        $message->user_id = Auth::id();
        $message->content = $request->message;
        $message->save();

        return back();
    }

    /*Enroll Course ways messages List*/
    public function inboxMessage()
    {
        $enrolls = Enrollment::where('user_id', Auth::id())->with('messages')->get();
        $ids = array();
        foreach ($enrolls as $item) {
            if ($item->messages->count() > 0) {
                $ids = array_merge($ids, [$item->id]);

            }
        }
        $messages = Enrollment::whereIn('id', $ids)->with('enrollCourse')
            ->with('messages')->get();
        return view($this->theme.'.message.index', compact('messages'));
    }


    /*single content*/
    public function singleContent($id)
    {
        $content = ClassContent::find($id);
        $demo = new Demo();
        if($content->content_type == 'Video'){
            $demo->provider = $content->provider;
            $demo->description = $content->description;
            if ($content->provider == "Youtube") {
                $demo->url = Str::after($content->video_url, 'https://youtu.be/');
            } elseif ($content->provider == "Vimeo") {
                $demo->url = Str::after($content->video_url, 'https://vimeo.com/');
            } elseif ($content->provider == "File") {
                $demo->url = asset($content->video_url);
            } elseif ($content->provider == "Live") {
                $demo->url = $content->video_url;
            } else{
                $demo->provider = "HTML5";
                $demo->url = $content->video_url;
            }
        }elseif ($content->content_type == 'Quiz'){
            /*if quiz is done then show the score*/
            $scores = QuizScore::where('quiz_id',$content->quiz_id)
                ->where('content_id',$content->id)
                ->where('user_id',Auth::id())->first();

            if ($scores != null){
                $demo->provider = $content->content_type;
                $demo->url = route('quiz.score.show',$scores->id);
            }else{
                $demo->provider = $content->content_type;
                $demo->url = route('start',[$content->quiz_id,$content->id]);
            }
        }
        else{
            $demo->provider = $content->content_type;
            $demo->description = $content->description;
            $demo->item1 = translate('Content document');
            $demo->item2 = translate('Download');
            $demo->url = filePath($content->file);
        }


        $course_id = Classes::where('id', $content->class_id)->first()->course_id;


        if(!request()->is('subscription/*')){
            $enroll_id = Enrollment::where('course_id', $course_id)->where('user_id', Auth::id())->first()->id;
        }else{
            $enroll_id = SubscriptionEnrollment::where('user_id', Auth::id())->first()->id;
        }

        $seens = SeenContent::where('class_id', $content->class_id)
            ->where('content_id', $content->id)
            ->where('course_id', $course_id)->where('enroll_id', $enroll_id)->where('user_id', Auth::id())->get();
        if ($seens->count() == 0) {
            $seen = new SeenContent();
            $seen->class_id = $content->class_id;
            $seen->content_id = $content->id;
            $seen->course_id = $course_id;
            $seen->enroll_id = $enroll_id;
            $seen->user_id = Auth::id();
            $seen->saveOrFail();
        }

        $demo->courseProgress = $this->seenCourse($enroll_id, $course_id);

        if($demo->courseProgress == 100){
            $demo->certificateWasCreated = $this->saveCertificate($course_id);
        }

        
        return response()->json($demo);
    }

    


    /*seen list*/
    public function seenList($id){
        $seen = SeenContent::where('course_id',$id)->where('user_id',Auth::id())->get();
        return response()->json($seen);
    }

    /*delete seen by content id*/
    public function seenRemove($id){
        $seen = SeenContent::where('content_id',$id)->where('user_id',Auth::id())->first();
        if ($seen){
            $seen->delete();
        }
        return response('ok done',200);
    }

    /*single blog*/
    public function singleBlog($id)
    {
        $blog = Blog::findOrFail($id);
        $blogs = Blog::where('is_active',1)->where('category_id',$blog->category_id)->get();
        $categories = Category::where('is_published', 1)->get();
        return view($this->theme . '.blog.details', compact('blog','categories','blogs'));
    }

    /*all posts*/
    public function blogPosts(Request $request)
    {
        $categories = Category::where('is_published', 1)->get();
        $blogs = null;
        if ($request->get('search')) {
            $search = $request->search;
            $blogs = Blog::where('is_active',1)->where('name', 'like', '%' . $search . '%')->get();
        } else {
            $blogs = Blog::where('is_active',1)->paginate(5);
        }

        return view($this->theme . '.blog.posts', compact('blogs', 'categories'));
    }

    /*categoryBlog*/
    public function categoryBlog($id)
    {
        $categories = Category::where('is_published', 1)->get();
        $blogs = Blog::where('is_active',1)->where('category_id', $id)->paginate(5);
        return view($this->theme . '.blog.posts', compact('blogs', 'categories'));
    }


    public function tagBlog($tag)
    {
        $categories = Category::where('is_published', 1)->get();
        $blogs = Blog::where('is_active',1)->where('tags', 'like', '%' . $tag . '%')->paginate(5);
        return view($this->theme . '.blog.posts', compact('blogs', 'categories'));
    }


    public function applyCoupon(Request $request){
        
        try{

            if($request->code && $request->amount){

                $new_amount = amountCalculatedWithCoupon($request->code);
                if($new_amount < $request->amount)
                    return response(['status' => true,'message' => 'Cupom válido', 'newAmount' => ($new_amount), 'coursePrice' => ($request->amount)]);
                else    
                    return response(['status' => false,'message' => 'Cupom não foi aplicado']);
                
            }else
                return response(['status' => false,'message' => 'Erro nesse processo']);

        }catch(\Exception $e){
            return response(['status' => false,'message' => 'Exceção: Erro nesse processo', 'error' => $e->getMessage()]);
        }
    }
}