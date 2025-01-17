<?php


use App\Helper\Helper;
use App\Model\Category;
use App\Coupon;
use App\Forum;
use App\PostReply;
use App\ForumPostView;
use App\HelpfulAnswer;
use App\Blog;
use App\Model\Cart;
use App\Model\Certificate;
use App\Model\Coupon as ModelCoupon;
use App\Model\CouponHistory;
use App\Wallet;
use App\User;
use App\RedeemCoursePoint;
use App\Model\Course;
use Carbon\Carbon;

//this function for open Json file to read language json file
function openJSONFile($code)
{
    $jsonString = [];
    if (File::exists(base_path('resources/lang/' . $code . '.json'))) {
        $jsonString = file_get_contents(base_path('resources/lang/' . $code . '.json'));
        $jsonString = json_decode($jsonString, true);
    }
    return $jsonString;
}

//save the new language json file
function saveJSONFile($code, $data)
{
    ksort($data);
    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents(base_path('resources/lang/' . $code . '.json'), stripslashes($jsonData));
}

//translate the key with json
function translate($key)
{
    $key = ucfirst(str_replace('_', ' ', $key));
    if (File::exists(base_path('resources/lang/en.json'))) {
        $jsonString = file_get_contents(base_path('resources/lang/en.json'));
        $jsonString = json_decode($jsonString, true);
        if (!isset($jsonString[$key])) {
            $jsonString[$key] = $key;
            saveJSONFile('en', $jsonString);
        }
    }
    return __($key);
}


//scan directory for load flag
function readFlag()
{
    $dir = base_path('public/uploads/lang');
    $file = scandir($dir);
    return $file;
}


//auto Rename Flag
function flagRenameAuto($name)
{
    $nameSubStr = substr($name, 8);
    $nameReplace = ucfirst(str_replace('_', ' ', $nameSubStr));
    $nameReplace2 = ucfirst(str_replace('.png', '', $nameReplace));
    return $nameReplace2;
}


function defaultCurrency()
{
    $sc = session('currency');
    if ($sc != null) {
        $id = $sc;
    } else {
        $id = (int)getSystemSetting('default_currencies')->value;
    }
    $currency = \App\Model\Currency::find($id);
    return $currency->code;
}

//format the Price
function formatPrice($price)
{
    return  str_replace(".", "," ,("R$" . number_format($price, 2)));
    
    $sc = session('currency');
    if ($sc != null) {
        $id = $sc;
    } else {
        $id = (int)getSystemSetting('default_currencies')->value;
    }

    $currency = \App\Model\Currency::find($id);
    $p = $price * $currency->rate;
    return $currency->align == 0 ? number_format($p, 2) . $currency->symbol : $currency->symbol . number_format($p, 2);
}

function formatPriceBr($price)
{
    return  str_replace(".", "," ,("R$" . number_format($price, 2)));
}

//format the Price
function noFormatPrice($huh)
{
    $x = session('currency');
    if ($x != null) {
        $ids = $x;
    } else {
        $ids = (int)getSystemSetting('default_currencies')->value;
    }

    $currency = \App\Model\Currency::find($ids);
    $p = $huh * $currency->rate;

    return $p;
}

//format the Price Code
function formatPriceCode()
{
    $priceCode = session('currency');
    $currency = \App\Model\Currency::find($priceCode);
    return $currency->code;
}

function getPriceRate($code)
{
    $getPriceCode = \App\Model\Currency::where('code', $code)->first();
    return $getPriceCode->rate ?? 0;
}

/*only price for payment*/
function onlyPrice($price)
{
    $sc = session('currency');
    if ($sc != null) {
        $id = $sc;
    } else {
        $id = (int)getSystemSetting('default_currencies')->value;
    }

    $currency = \App\Model\Currency::find($id);
    $p = $price * $currency->rate;
    return $p;

}


function PaytmPrice($price)
{

    switch (activeCurrency()) {
        case 'USD':
            return noFormatPrice($price) * getPriceRate('INR');
            break;

        case 'BDT':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;

        case 'INR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;

        case 'LKR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;

        case 'PKR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;

        case 'NPR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;

        case 'ZAR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;

        case 'JPY':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;

        case 'KRW':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;

        case 'IDR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;

        case 'AED':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;

        case 'TRY':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'BRL':
        return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('BRL');
        break;

        default:
            # code...
            break;
    }
}

function StripePrice($stripePrice)
{

    switch (activeCurrency()) {
        case 'USD':
            return noFormatPrice($stripePrice);
            break;
        case 'BDT':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'INR':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'LKR':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'PKR':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'NPR':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'ZAR':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'JPY':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'KRW':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'IDR':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'AED':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'TRY':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'BRL':
        return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
        break;

        default:
            # code...
            break;
    }
}

function PagarPrice($pagarPrice, $couponDiscont = 0)
{
    $pagarPrice = (float)$pagarPrice;
    if($couponDiscont > 0){
        return ($pagarPrice - $couponDiscont) * 100;
    }else
        return ($pagarPrice * 100);

    switch (activeCurrency()) {
        case 'USD':
            return noFormatPrice($pagarPrice) * getPriceRate('BR');
            break;

        case 'BDT':
            return noFormatPrice($pagarPrice) / getPriceRate(activeCurrency()) * getPriceRate('BR');
            break;

        case 'INR':
            return noFormatPrice($pagarPrice) / getPriceRate(activeCurrency()) * getPriceRate('BR');
            break;

        case 'LKR':
            return noFormatPrice($pagarPrice) / getPriceRate(activeCurrency()) * getPriceRate('BR');
            break;

        case 'PKR':
            return noFormatPrice($pagarPrice) / getPriceRate(activeCurrency()) * getPriceRate('BR');
            break;

        case 'NPR':
            return noFormatPrice($pagarPrice) / getPriceRate(activeCurrency()) * getPriceRate('BR');
            break;

        case 'ZAR':
            return noFormatPrice($pagarPrice) / getPriceRate(activeCurrency()) * getPriceRate('BR');
            break;

        case 'JPY':
            return noFormatPrice($pagarPrice) / getPriceRate(activeCurrency()) * getPriceRate('BR');
            break;

        case 'KRW':
            return noFormatPrice($pagarPrice) / getPriceRate(activeCurrency()) * getPriceRate('BR');
            break;

        case 'IDR':
            return noFormatPrice($pagarPrice) / getPriceRate(activeCurrency()) * getPriceRate('BR');
            break;

        case 'AED':
            return noFormatPrice($pagarPrice) / getPriceRate(activeCurrency()) * getPriceRate('BR');
            break;

        case 'TRY':
            return noFormatPrice($pagarPrice) / getPriceRate(activeCurrency()) * getPriceRate('BR');
            break;

        default:
            # code...
            break;
    }
}

function formatAmountToReal($amount){
    
}

function PaypalPrice($PaypalPrice)
{

    switch (activeCurrency()) {
        case 'USD':
            return noFormatPrice($PaypalPrice);
            break;
        case 'BDT':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'INR':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'LKR':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'PKR':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'NPR':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'ZAR':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'JPY':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'KRW':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'AED':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'IDR':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'TRY':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'BRL':
        return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
        break;

        default:
            # code...
            break;
    }
}

/*Active Currency*/
function activeCurrency()
{
    $sc = session('currency');
    if ($sc != null) {
        $id = $sc;
    } else {
        $id = (int)getSystemSetting('default_currencies')->value;
    }

    $currency = \App\Model\Currency::find($id);
    return $currency->code;
}

/*Active Currency*/
function activeCurrencySymbol()
{
    $sc = session('currency');
    if ($sc != null) {
        $id = $sc;
    } else {
        $id = (int)getSystemSetting('default_currencies')->value;
    }

    $currency = \App\Model\Currency::find($id);
    return $currency->symbol;
}

//override or add env file or key
function overWriteEnvFile($type, $val)
{
    $path = base_path('.env');
    if (file_exists($path)) {
        $val = '"' . trim($val) . '"';
        if (is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0) {
            file_put_contents($path, str_replace($type . '="' . env($type) . '"', $type . '=' . $val, file_get_contents($path)));
        } else {
            file_put_contents($path, file_get_contents($path) . "\r\n" . $type . '=' . $val);
        }
    }
}


//get system setting data
function getSystemSetting($key)
{
    return \App\Model\SystemSetting::where('type', $key)->first();
}

//get Subscription setting data
function getSubscriptionSetting($key)
{
    return \App\SubscriptionSettings::where('type', $key)->first();
}

//get Subscription setting data
function enableCourse()
{
    $enableCourse = \App\SubscriptionSettings::where('type', 'enable_all_course')->first();

    if ($enableCourse != null && $enableCourse->value == true) {
        return true;
    } else {

        return false;
    }
}

//get Subscription setting data
function enableInstructorRequest()
{
    $enableInstructorRequest = \App\SubscriptionSettings::where('type', 'enable_instructor_request')->first();
    if ($enableInstructorRequest != null && $enableInstructorRequest->value == true) {
        return true;
    } else {

        return false;
    }
}

//get Subscription setting data
function enableFreeTrial()
{
    $enableFreeTrial = \App\SubscriptionSettings::where('type', 'enable_free_trial')->first()->value;
    if ($enableFreeTrial == true) {
        return true;
    } else {

        return false;
    }
}

//Get file path
//path is storage/app/
function filePath($file)
{
    return assetC($file);
}


function course()
{
    return \App\Model\Course::Published()->get();
}

//delete file
function fileDelete($file)
{
    if ($file != null) {
        if (file_exists(public_path($file))) {
            unlink(public_path($file));
        }
    }
}

//uploads file
// uploads/folder
function fileUpload($file, $folder, $fileName = null)
{
    if($fileName){
        return $file->storeAs('uploads/' . $folder, $fileName);
    }
    return $file->store('uploads/' . $folder);
}


//get instructor
function instructorDetails($id)
{
    return \App\Model\Instructor::where('user_id', $id)->first();
}

function studentDetails($id)
{
    return \App\Model\Student::where('user_id', $id)->first();
}


/*categories*/
function categories()
{

    return Category::where('parent_category_id', 0)->with('child')->Published()->get();
}


/*duration*/
function duration($value)
{
    $init = $value;
    $hours = floor($init / 60);
    $minutes = floor($init % 60);
    $seconds = floor(($init / 60) % 60);
    $single_sec = mb_strlen((string)$seconds);
    $duration = $hours . ':' . $minutes . ':' . ($single_sec === 1 ? '0' . $seconds : $seconds);
    return date('H:i:s', strtotime($duration));
}


/*best selling tags*/
function bestSellingTags($id)
{
    $start = \Carbon\Carbon::parse(date('y-m-d'))
        ->startOfMonth()
        ->toDateTimeString();
    $end = \Carbon\Carbon::parse(date('y-m-d'))
        ->endOfMonth()
        ->toDateTimeString();

    $enroll = \App\Model\Enrollment::where('course_id', $id)->whereBetween('created_at', [$start, $end])->get();

    if ($enroll->count() > 5) {
        return true;
    }
    return false;
}


/*affiliate status*/
function affiliateStatus()
{
    try {
        $affiliate = getSystemSetting('affiliate')->value;
        if ($affiliate == 'Active') {
            return true;
        } else {
            return false;
        }
    } catch (Exception $exception) {
        return false;
    }

}

/*affiliate commission*/
function commission()
{
    $commission = (int)getSystemSetting('commission')->value;
    return $commission;
}

//cookie time day
function cookiesLimit()
{
    $days = (int)getSystemSetting('cookies_limit')->value;
    /*return day*/

    return ($days * 1440);
}

/*cashout*/
function withdrawLimit()
{
    $amount = (int)getSystemSetting('withdraw_limit')->value;
    return $amount;
}

//CHECK MEDIA MANAGER ACTIVATION
function MediaActive()
{
    if (env('MEDIA_MANAGER') === "YES") {
        return true;
    } else {
        return false;
    }
}


/*Certificate addons activations */
function certificate()
{
    if (env('CERTIFICATE_ACTIVE') === "YES") {
        return true;
    } else {
        return false;
    }
}


// check Paytm route for Mapping

function paytmRoute()
{
    if (file_exists(base_path('routes/paytm.php'))) {
        return true;
    } else {
        return false;
    }
}

// check Paytm route for blade
function paytmRouteForBlade()
{
    if (file_exists(base_path('routes/paytm.php'))) {
        return true;
    } else {
        return false;
    }
}

// check Paytm route for blade
function paytmActive()
{
    if (env('PAYTM_ACTIVE') == 'YES') {
        return true;
    }
    return false;
}

/*quiz*/
function quizActive()
{
    if (env('QUIZ_ACTIVE') == 'YES') {
        return true;
    }
    return false;
}

// check quiz route for Mapping

function quizRoute()
{
    if (file_exists(base_path('routes/quiz.php'))) {
        return true;
    } else {
        return false;
    }
}

// check quiz route for blade
function quizRouteForBlade()
{
    if (file_exists(base_path('routes/quiz.php'))) {
        return true;
    } else {
        return false;
    }
}

// check quiz route for blade
function couponRouteForBlade()
{
    if (file_exists(base_path('routes/coupon.php'))) {
        return true;
    } else {
        return false;
    }
}


// check certificate route for blade
function certificateForRoute()
{
    if (file_exists(base_path('routes/certificate.php'))) {
        return true;
    } else {
        return false;
    }
}


// check Paytm route for Mapping

function zoomRoute()
{
    if (file_exists(base_path('routes/zoom.php'))) {
        return true;
    } else {
        return false;
    }
}

// check Paytm route for blade
function zoomRouteForBlade()
{
    if (file_exists(base_path('routes/zoom.php'))) {
        return true;
    } else {
        return false;
    }
}

// check Paytm route for blade
function zoomActive()
{
    if (env('ZOOM_ACTIVE') == 'YES') {
        return true;
    }
    return false;
}

// check Paytm route for blade
function couponActive()
{
    if (env('COUPON_ACTIVE') == 'YES') {
        return true;
    }
    return false;
}


// Forum


// check forum route for Mapping

function forumRoute()
{
    if (file_exists(base_path('routes/forum.php'))) {
        return true;
    } else {
        return false;
    }
}

// check forum route for blade
function forumRouteForBlade()
{
    if (file_exists(base_path('routes/forum.php'))) {
        return true;
    } else {
        return false;
    }
}

// check forum route for blade
function forumActive()
{
    if (env('FORUM_PANEL') == 'YES') {
        return true;
    }
    return false;
}


// subscription

// check forum route for Mapping

function subscriptionRoute()
{
    if (file_exists(base_path('routes/subscription.php'))) {
        return true;
    } else {
        return false;
    }
}

// check forum route for blade
function subscriptionRouteForBlade()
{
    if (file_exists(base_path('routes/subscription.php'))) {
        return true;
    } else {
        return false;
    }
}

// check forum route for blade
function subscriptionActive()
{
    if (env('SUBSCRIPTION_ACTIVE') == 'YES') {
        return true;
    }
    return false;
}


/*active theme*/
function themeManager()
{
    try {
        if (env('THEME_MANAGER') === "YES") {
//            $t = new \App\Themes();
//            $themes = \App\Themes::all();
//            foreach ($themes as $theme) {
//                if ($theme->activated) {
//                    $t = $theme;
//                }else{
//                    $t = null;
//                }
//            }
            if (env('ACTIVE_THEME') === 'rumbok') {
                return 'rumbok';
            } else {
                return 'frontend';
            }
        } else {
            return 'frontend';
        }
    } catch (Exception $exception) {
        return 'frontend';
    }

}


function adminPower()
{
    return Auth::user()->user_type === "Admin";
}

function instructorPower()
{
    return Auth::user()->user_type === "Instructor";
}

function studentPower()
{
    return Auth::user()->user_type === "Student";
}

/**
 * EXPIRE
 */

function expire($duration)
{

    if ($duration == 'Monthly') {
        return App\SubscriptionEnrollment::where('subscription_package', $duration)->where('end_at', '>', Carbon\Carbon::now())->count();
    }


    if ($duration == 'Weekly') {
        return App\SubscriptionEnrollment::where('subscription_package', $duration)->where('end_at', '>', Carbon\Carbon::now())->count();
    }


    if ($duration == 'Daily') {
        return App\SubscriptionEnrollment::where('subscription_package', $duration)->where('end_at', '>', Carbon\Carbon::now())->count();
    }


    if ($duration == 'Yearly') {
        return App\SubscriptionEnrollment::where('subscription_package', $duration)->where('end_at', '>', Carbon\Carbon::now())->count();
    }


}


function enrolmentStare($count)
{


    switch ($count) {
        case $count > 50:
            return 5;
            break;
        case $count < 45 && $count > 35:
            return 4;
            break;
        case $count < 35 && $count > 25:
            return 3;
            break;
        default:
            return 2;
    }


}


function allBlogTags()
{
    $tags = array();
    $blogs = \App\Blog::all();

    foreach ($blogs as $blog) {
        $blogPost = json_decode($blog->tags);

        foreach ($blogPost as $tag) {
            array_push($tags, $tag);
        }

    }

    $data = array_unique($tags, false);
    return $data;

}

/**
 * couponRoute
 */

 function couponRoute()
{
    if (file_exists(base_path('routes/coupon.php'))) {
        return true;
    } else {
        return false;
    }
}

/**
 * COUPON SESSION
 */

 function couponDiscount($code)
 {
     $rate = Coupon::where('code', $code)->select('rate')->first();
     return formatPrice($rate->rate);
 }

 function couponDiscountPrice($code)
 {
     $rate = Coupon::where('code', $code)->select('rate')->first();
     return $rate->rate;
 }

 function verifyCupom($code, $instructor_id, $course_id){

    try{

        $coupon = ModelCoupon::where('code', $code)->first();
    
        if(
            $coupon &&
            $coupon->user_id == $instructor_id 
        ){
            $today = date('Y-m-d');
            $today=date('Y-m-d', strtotime($today));
            //echo $today; // echos today! 
            $start_date = date('Y-m-d', strtotime($coupon->start_date));
            $end_date = date('Y-m-d', strtotime($coupon->end_date));
            
            if($coupon->quantity > 0 && ($today >= $start_date) && ($today <= $end_date)){

                if(
                    $coupon->course_id 
                ){
        
                    if($coupon->course_id == $course_id){
                        return true;
                    }else
                        return false;
        
                }else{
                    return true;
                }
            } else return false;
        }else
            return false;
    }catch(Exception $e){
        return false;
    }

 }

 function calculateCouponDiscount($amount,$percent){
    return (($amount * $percent)/100);
 }

 function amountCalculatedWithCoupon($coupon_code, $use_coupon = false){


    try{

        $amount_total = 0;
        $cart = Cart::where('user_id', auth()->user()->id)->get();


        foreach($cart as $item){

            $course = $item->course;
            $course_price = course_price($course);
            
            $verify_coupon = verifyCupom($coupon_code, $course->user_id, $course->id);
            

            if($verify_coupon){

                $coupon = ModelCoupon::where('code', $coupon_code)->first();
                $discount = calculateCouponDiscount($course_price, $coupon->percent);
                $amount_total += PagarPrice( $course_price - $discount );

                if($use_coupon){
                    
                    $couponHistory = new CouponHistory();
                    $couponHistory->user_id = auth()->user()->id;
                    $couponHistory->coupon_id = $coupon->id;
                    $couponHistory->course_id = $course->id;
                    $couponHistory->discount = $discount;
                    $couponHistory->save();

                    $coupon->quantity--;
                    $coupon->save();
                }



            }else
                $amount_total += PagarPrice($course_price);
        }

        return $amount_total;

    }catch(Exception $e){
        throw $e;
    }

    

}

 /**
  * FORUMLY
  */

  function forumComp($blade)
  {
    return 'forum.forumly.components.' . $blade;
  }

  function forumPostCount()
  {
      return Forum::count();
  }

  function forumCategoryCount($category)
  {
      return Forum::where('category', $category)->count();
  }

  function forumCategory($category)
  {
      return Forum::where('category', $category)->get();
  }

  function forumPostReplyCount()
  {
      return PostReply::count();
  }

  function latestForumPostCount()
  {
      return Forum::whereDate('created_at', Carbon::today())->count();
  }

  function latestFostReplyCount()
  {
      return PostReply::whereDate('created_at', Carbon::today())->count();
  }

  function blogCount()
  {
      return Blog::count();
  }

  function post_replies_count($id)
  {
    return $post_replies_count = PostReply::where('post_id', $id)->count();
  }

  function post_views_count($id)
  {
    return $post_views_count = ForumPostView::where('post_id', $id)->count();
  }

  function helpful_count($id)
  {
    return $helpful_count = HelpfulAnswer::where('post_id', $id)->count();
  }

  function popularQuestion()
  {
      return ForumPostView::select('post_id')
	    ->groupBy('post_id')
	    ->orderByRaw('COUNT(*) DESC')
	    ->get();
  }

  function popularQuestions($id)
  {
        return Forum::where('id', $id)->select('id', 'title', 'user_id')->latest()->take(10)->get();
  }



  function helpfulReplyId()
  {
      return HelpfulAnswer::select('post_reply_id')
			    ->groupBy('post_reply_id')
			    ->orderByRaw('COUNT(*) DESC')
			    ->take(1)
			    ->first()->post_reply_id ?? null;
  }


  /**
   * Enroll Check
   */

   function checkStudentEnroll($id)
   {
        $check = Course::where('id', $id)->exists();

        if ($check) {
            return true;
        }else{
            return false;
        }
   }


  /**
   * WALLET
   */

   // check wallet route for Mapping

function walletRoute()
{
    if (file_exists(base_path('routes/wallet.php'))) {
        return true;
    } else {
        return false;
    }
}

// check wallet route for blade
function walletRouteForBlade()
{
    if (file_exists(base_path('routes/wallet.php'))) {
        return true;
    } else {
        return false;
    }
}

// check wallet route for blade
function walletActive()
{
    if (env('WALLET_ACTIVE') == 'YES') {
        return true;
    }
    return false;
}


//get wallet setting data
function getWalletSetting($key)
{
    return Wallet::select($key)->first()->$key ?? null;
}

/**
 * POINT LIST
 */

 function walletName()
 {
     return getWalletSetting('wallet_name') ?? 'coint';
 }

 function walletRateLimit()
 {
     return getWalletSetting('redeem_limit') ?? 1000;
 }

 function walletRate()
 {
     return getWalletSetting('wallet_rate') ?? 1000;
 }

 function registrationPoint()
 {
     return getWalletSetting('registration_point') ?? 500;
 }

 function freePoint()
 {
     return getWalletSetting('free_course_point') ?? 50;
 }

 function paidPoint()
 {
     return getWalletSetting('paid_course_point') ?? 100;
 }

 function courseCompletePoint()
 {
     return getWalletSetting('course_complete_point') ?? 200;
 }


/**
 * POINT LIST::END
 */


function addWallet($point, $message)
{
    if (env('WALLET_ACTIVE') == "YES") {
        $user = User::where('id', Auth::user()->id)->first();
        $amount = $point; // (Double) Can be a negative value
        $message = $message; //The reason for this transaction

        //Optional (if you modify the point_transaction table)
        $data = [
            'ref_id' => 'someReferId',
        ];

        $transaction = $user->addPoints($amount,$message,$data);
    }
}


function subWallet($point, $message)
{
    if (env('WALLET_ACTIVE') == "YES") {
        $user = User::where('id', Auth::user()->id)->first();
        $amount = $point; // (Double) Can be a negative value
        $message = $message; //The reason for this transaction

        //Optional (if you modify the point_transaction table)
        $data = [
            'ref_id' => 'someReferId',
        ];

        $transaction = $user->subPoints($amount,$message,$data);
    }
}


function walletBalance()
{
    $user = User::where('id', Auth::user()->id)->where('user_type', 'Student')->first();
    return $points = $user->currentPoints();
}


function checkRedeem($course_id)
{
    $checkRedeem = RedeemCoursePoint::where('course_id', $course_id)->where('user_id', Auth::user()->id)->exists();

    if ($checkRedeem) {
        return true;
    }else{
        return false;
    }
}

/**
 * POINT CONVERT
 */

 function WalletPrice($price)
{

    switch (activeCurrency()) {
        case 'USD':
            return noFormatPrice($price) * walletRate();
            break;

        case 'BDT':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * walletRate();
            break;

        case 'INR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * walletRate();
            break;

        case 'LKR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * walletRate();
            break;

        case 'PKR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * walletRate();
            break;

        case 'NPR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * walletRate();
            break;

        case 'ZAR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * walletRate();
            break;

        case 'JPY':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * walletRate();
            break;

        case 'KRW':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * walletRate();
            break;

        case 'IDR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * walletRate();
            break;

        case 'AED':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * walletRate();
            break;

        case 'TRY':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * walletRate();
            break;
        case 'BRL':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * walletRate();
            break;

        default:
            # code...
            break;
    }
}


function buyWallet($price)
{

    switch (activeCurrency()) {
        case 'USD':
            return noFormatPrice($price) / walletRate();
            break;

        case 'BDT':
            return noFormatPrice($price) * getPriceRate(activeCurrency()) / walletRate();
            break;

        case 'INR':
            return noFormatPrice($price) * getPriceRate(activeCurrency()) / walletRate();
            break;

        case 'LKR':
            return noFormatPrice($price) * getPriceRate(activeCurrency()) / walletRate();
            break;

        case 'PKR':
            return noFormatPrice($price) * getPriceRate(activeCurrency()) / walletRate();
            break;

        case 'NPR':
            return noFormatPrice($price) * getPriceRate(activeCurrency()) / walletRate();
            break;

        case 'ZAR':
            return noFormatPrice($price) * getPriceRate(activeCurrency()) / walletRate();
            break;

        case 'JPY':
            return noFormatPrice($price) * getPriceRate(activeCurrency()) / walletRate();
            break;

        case 'KRW':
            return noFormatPrice($price) * getPriceRate(activeCurrency()) / walletRate();
            break;

        case 'IDR':
            return noFormatPrice($price) * getPriceRate(activeCurrency()) / walletRate();
            break;

        case 'AED':
            return noFormatPrice($price) * getPriceRate(activeCurrency()) / walletRate();
            break;

        case 'TRY':
            return noFormatPrice($price) * getPriceRate(activeCurrency()) / walletRate();
            break;
            
        case 'BRL':
        return noFormatPrice($price) * getPriceRate(activeCurrency()) / walletRate();
        break;

        default:
            # code...
            break;
    }
}

function payWithCoin()
{
    if (walletRateLimit() <= walletBalance()) {
        return true;
    } else {
        return false;
    }
}

function checkWallerBalanceForPurchase($total_price)
{
    if ($total_price <= walletBalance()) {
        return true;
    } else {
        return false;
    }
}

function generateToken()
{
    return md5(rand(1, 10) . microtime());
}

function formatAddress($formatType = 'string', $formatValue){

    $model = Array('street', 'street_number', 'neighborhood', 'city', 'state', 'country', 'zipcode');

    if($formatType == 'string'){

        return implode(',',$formatValue);

    }else if($formatType == 'array'){

        $arr = explode(",",$formatValue);

        if(count($arr) == 7){

            $nArr = [
                $model[0] => $arr[0],
                $model[1] => $arr[1],
                $model[2] => $arr[2],
                $model[3] => $arr[3],
                $model[4] => $arr[4],
                $model[5] => $arr[5],
                $model[6] => $arr[6],
            ];
    
            return $nArr;
        }else
            return '';
    }

}

function calculateCreditCardTax($amount, $installments){
    $pagar_tax = env('PAGAR_CREDIT_CARD_TAX');
    $pagar_tax_inc = env('PAGAR_CREDIT_CARD_TAX_INCREASE');
    return (int)((((($installments - 1) * $pagar_tax_inc) + $pagar_tax)/100) * $amount) + $amount; 
}

function getDateStr(){
    $date = getdate();
    return "$date[year]-$date[mon]-$date[mday]  T$date[hours]:$date[minutes]:$date[seconds]Z";
}

function generateCertificateCode() {
    $value = mt_rand(1000000000, 9999999999); // better than rand()
    $value = (string)$value;

    // call the same function if the barcode exists already
    if (certificateCodeExists($value)) {
        return generateCertificateCode();
    }

    // otherwise, it's valid and can be used
    return $value;
}

function certificateCodeExists($value) {
    return Certificate::where('auth_code', $value)->exists();
}

function formatCpf($value)
{
  $cpf = preg_replace("/\D/", '', $value);
  return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cpf);
}

function course_price($course){
    if($course && isset($course->is_free) && isset($course->is_discount))
        if($course->is_free)
            return 0;
        else if($course->is_discount)
            return $course->discount_price;
        else    
            return $course->price;
    else return 0;        
}

function assetC($value){
    return asset('public/'.$value);
}

function course_level($value){
    if($value == '1'){
        return 'Iniciante';
    }else if($value == '2'){
        return 'Avançado';
    }else if($value == '3'){
        return 'Todos os níveis';
    }
}

function coursesJsonToArray($courses_json){
    $courses_id = json_decode($courses_json);
    $courses = [];
    if(is_array($courses_id)){
        foreach($courses_id as $c ){
            array_push($courses, Course::where('id', $c)->first());
        }

    }
    return $courses;
}