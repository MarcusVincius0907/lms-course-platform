<?php

/*all front end route here
 *
 * */

 Route::get('x', function () {
    return checkRedeem(9);
 });

Route::group(['middleware' => ['installed', 'demo', 'activity']], function () {
    // homepage
    Route::get('/', 'FrontendController@homepage')
        ->name('homepage');

    if (env('BLOG_ACTIVE') == "YES"){
        /*for frontend blog*/
        Route::get('blog/posts','FrontendController@blogPosts')->name('blog.all');
        Route::get('blog/details/{id}','FrontendController@singleBlog')->name('blog.details');
        Route::get('blog/category/{id}','FrontendController@categoryBlog')->name('blog.category');
        Route::get('blog/tag/{tag}','FrontendController@tagBlog')->name('blog.tag');
    }

    /*search courses*/
    Route::get('search', 'FrontendController@searchCourses')->name('search.courses');
    //password_reset
    Route::get('password/reset', 'FrontendController@password_reset')
        ->name('student.password.reset');

    /*Course Category*/
    Route::get('cursos/{slug}', 'FrontendController@courseCat')
        ->name('course.category');

    /*single course details*/
    Route::get('curso/{slug}', 'FrontendController@singleCourse')
        ->name('course.single');

    /*currencies.change*/
    Route::post('currencies/change', 'FrontendController@currenciesChange')
        ->name('frontend.currencies.change')->middleware('demo');

    /*language change*/
    Route::post('languages/change', 'FrontendController@languagesChange')
        ->name('frontend.languages.change')->middleware('demo');

    /*teacher profile*/
    Route::get('instructor/details/{slug}', 'FrontendController@instructorDetails')
        ->name('single.instructor');

    Route::get('/cursos', 'FrontendController@courseFilter')
        ->name('course.filter');

    /*Content preview*/
    Route::get('content/preview/{id}', 'FrontendController@contentPreview')
        ->name('content.video.preview');

    /*instructor register*/
    Route::get('instructor/register', 'FrontendController@registerView')
        ->name('instructor.register');

    /*instructor create*/
    Route::post('instructor/create', 'FrontendController@registerCreate')
        ->name('instructor.create')->middleware('demo');

    /*instructor payment*/
    Route::get('instructor/payment/{slug}', 'FrontendController@insPayment')
        ->name('instructor.payment');

    /*pages*/
    Route::get('page/{slug}', 'FrontendController@page')
        ->name('pages');

    /*instructor strip payment*/
    Route::post('instructor/stripe/payment', 'PaymentController@instructorStripe')
        ->name('instructor.stripe.payment')->middleware('demo')->middleware('demo');

    /*instructor strip payment*/
    Route::post('instructor/paypal/payment', 'PaymentController@instructorPaypal')
        ->name('instructor.paypal.payment')->middleware('demo');

    //login
    Route::get('student/login', 'FrontendController@login')
        ->name('student.login');

    //student_create
    Route::post('student/create', 'FrontendController@create')
        ->name('student.create')->middleware('demo');

    //signup
    Route::get('signup', 'FrontendController@signup')
        ->name('student.register');

    Route::get('test/{id}', 'FrontendController@test')
    ->name('test');

    /* mudar essas rotas */

    Route::get('test', 'FrontendController@test2')
    ->name('test2');

    Route::group(['middleware' => ['auth']], function () {
        
        /* auth */
        /*get course progress for certificate*/
        Route::get('progress/{id}/{course_id}', 'FrontendController@updateProgressCertificateState')
        ->name('progress');
    
        //commenting
        Route::post('comment', 'FrontendController@comments')
        ->name('comments')->middleware('demo');
    
        /*get content details*/
        Route::get('class/content/{id}', 'FrontendController@singleContent')
        ->name('class.content');
    
        /* save certificate */    
        Route::post('certificateEdited', 'FrontendController@certificateEdited')
        ->name('certificateEdited');
    
        /* handle issue certificate request */
        Route::get('certificate/{course_id}', 'FrontendController@issueCertificate')
        ->name('certificate');
    
        /*seen content delete*/
        Route::get('seen/content/remove/{id}','FrontendController@seenRemove')->name('seen.remove');  

        /*all seen content list*/
        Route::get('seen/list/{id}','FrontendController@seenList')->name('seen.list');

    });

    // this group for authorize user
    Route::group(['middleware' => ['auth', 'verifypayment', 'check.frontend']], function () {

        Route::get('/mark-as-all-read', 'FrontendController@mark_as_all_read')->name('mark_as_all_read');

        /*paypal payment*/
        Route::post('paypal/payment', 'PaymentController@paypalPayment')
            ->name('paypal.paymnet')->middleware('demo');

        // stripe
        Route::post('stripe', 'PaymentController@stripePost')
            ->name('stripe.post')->middleware('demo');

        /*if free amount is zero*/
        Route::get('free/payment', 'PaymentController@freePayment')
            ->name('free.payment');

        /*all user certificates*/
        Route::get('my/certificates', 'FrontendController@my_certificates')
            ->name('my.certificates');

        /*create message*/
        Route::get('message/create/{id}', 'FrontendController@messageCreate')
            ->name('message.create');
        Route::post('message/send', 'FrontendController@sendMessage')
            ->name('message.sent')->middleware('demo');

        /*all enroll courses and wishlist*/
        Route::get('my/cursos', 'FrontendController@my_courses')
            ->name('my.courses');

        Route::get('my/wishlist', 'FrontendController@my_wishlist')
            ->name('my.wishlist');

        /*all enroll course ajax*/
        Route::get('enroll/cursos', 'FrontendController@enrollCourses')
            ->name('enroll.courses');
        /*cart list*/
        Route::get('cart/list', 'FrontendController@cartList')
            ->name('cart.list');
        /*add to cart*/

        Route::get('add/to/cart', 'FrontendController@addToCart')
            ->name('add.to.cart');

        /*remove the cart*/
        Route::get('remove/cart/{id}', 'FrontendController@removeCart')
            ->name('cart.remove');

        /*wishlist*/
        Route::get('wish/list', 'FrontendController@wishList')
            ->name('wish.list');

        /*add to wishlist*/
        Route::get('add/to/wishlist', 'FrontendController@addToWishlist')
            ->name('add.to.wishlist');

        /*remove wishlist*/
        Route::get('remove/wishlist/{id}', 'FrontendController@removeWishlist')
            ->name('remove.wishlist');

        /*Shopping cart list with pages*/
        Route::get('shopping/cart', 'FrontendController@shoppingCart')
            ->name('shopping.cart');
            
        Route::get('shopping/pending/{id}', 'FrontendController@pendingPayment')
        ->name('shopping.pending');

        /*checkout*/
        Route::get('cart/checkout/{id}', 'FrontendController@checkout')
            ->name('checkout');

        

        // ============================== student route ===========================


        //dashboard
        Route::get('student/dashboard', 'FrontendController@dashboard')
            ->name('student.dashboard');

        //my_profile
        Route::get('student/profile', 'FrontendController@my_profile')
            ->name('student.profile');
        //student_edit
        Route::get('student/profile/edit', 'FrontendController@student_edit')
            ->name('student.edit');

        //student_update
        Route::post('student/profile/update/{std_id}', 'FrontendController@update')
            ->name('student.update')->middleware('demo');

        //enrolled_course
        Route::get('student/enrolled/curso', 'FrontendController@enrolled_course')
            ->name('student.enrolled.course');

        //message
        Route::get('student/message', 'FrontendController@inboxMessage')
            ->name('student.message');

        //purchase_history
        Route::get('student/purchase/history', 'FrontendController@purchase_history')
            ->name('student.purchase.history');

        //purchase_history_detail
        Route::get('student/purchase/history/{id}', 'FrontendController@purchase_history_detail')
        ->name('student.purchase.history.detail');

        //lesson_details
        Route::get('lesson/{slug}', 'FrontendController@lesson_details')
            ->name('lesson_details');

        

        

        
        /*affiliate area*/
        if (affiliateStatus()){
            Route::get('student/affiliate/area','FrontendController@affiliateCreate')->name('affiliate.area');
            Route::get('student/affiliate/request','FrontendController@affiliateRequest')->name('student.affiliate.request');
            Route::post('student/affiliate/update','FrontendController@affiliateStore')->name('student.account.update');
            Route::get('student/payment/request','FrontendController@affiliatePaymentRequest')->name('student.payment.request');
            Route::post('student/payment/store','FrontendController@affiliatePaymentStore')->name('student.payments.store');
        }

        Route::get('coupon/apply', 'FrontendController@applyCoupon')->name('coupon.apply');

    });

});
