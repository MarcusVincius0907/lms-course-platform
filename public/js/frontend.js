"use strict"


$(document).ready(function () {
    // $( 'button[type="submit"]').attr('disabled', true);
    // $( 'button[type="submit"]').attr('type', 'button');
    // $( 'button[type="button"]').attr('title', 'Demo Mode');
    //
    // $( '#loginBtn').attr('disabled', false);
    // $( '#loginBtn').attr('type', 'submit');
    // $( '#loginBtn').attr('title', 'login');
    /*demo*/
    $(window).resize(function () {
        if (window.innerWidth < 1200) {
            $('#custom_toggle_bar').removeClass('d-flex');
        } else {
            $('#custom_toggle_bar').addClass('d-flex');
        }
    })


    if (window.innerWidth < 1200) {
        $('#custom_toggle_bar').removeClass('d-flex');
    }

    cartList();
    wishList();
    enrollCourse();

    $('select').on('change', function() {
        calculateAmount(this.value)
    });

    $('#inputResetPassword').on('keyup', function() {
        localStorage.setItem('resetPasswordEmail',$(this).val());
    })
    
    
    
})




$(document).ready(function() {

    $("#owl-carousel-partner").owlCarousel({
  
      autoplay: true, //Set AutoPlay to 3 seconds
      
      nav: false,
      navText : ["<",">"],
      dots:true,
      items: 3,
      loop: true,
      slideTransition: 'linear',  
      autoplayTimeout:1000,
      center: true,
      responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
    }
  
    });
  });


let totalPrice = $('.total-price');

function waitForElement(querySelector, timeout){
    return new Promise((resolve, reject)=>{
      var timer = false;
      if(document.querySelectorAll(querySelector).length) return resolve();
      const observer = new MutationObserver(()=>{
        if(document.querySelectorAll(querySelector).length){
          observer.disconnect();
          if(timer !== false) clearTimeout(timer);
          return resolve();
        }
      });
      observer.observe(document.body, {
        childList: true, 
        subtree: true
      });
      if(timeout) timer = setTimeout(()=>{
        observer.disconnect();
        reject();
      }, timeout);
    });
  }

waitForElement("#section-payment", 3000).then(function(){
    setTimeout(() => {
        getInstallments();
        
    }, 1000);
}).catch(()=>{
    console.log("element did not load in 3 seconds");
});

/*enroll course*/
function enrollCourse() {
    
    var url = $('#enrollUrl').val();
    if (url != null && url != undefined) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function (result) {
                console.log(result);
                result.data.forEach(function (item, index) {


                    $(".love-" + item.course_id).empty();

                    $(".addToCart-" + item.course_id).prop("onclick", null);
                    $(".addToCart-" + item.course_id).prop("href", item.link);
                    $(".addToCart-" + item.course_id).text(item.message);

                })
            },
            error: (e) => console.log(e)
        })
    }
}

/*this function for */
function cartList() {
    var url = $('#cartUrl').val();
    var crUrl = $('#shoppingCart').val();
    var crname = $('#textUrl').val();
    var emp = $('#emptyUrl').val();
    var img = $('#cartImg').val();
    if (url != null && url != undefined) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function (result) {
                $("#cartAppend").empty();
                if (result.data.length == 0) {
                    $('.cart-quantity').text(0);
                    $('#cartAppend').append('<img src="'+img+'" class="w-100"  alt="">');
                } else {
                    $('.cart-quantity').text(result.data.length)
                }

                var html = "";
                result.data.forEach(function (item, index) {
                    $('#cartBtn').empty();
                    $('#cartBtn').append('<a href="' + crUrl + '" class="theme-btn cart-btn cart-text">' + crname + '</a>');
                    $(".addToCart-" + item.course_id).prop("href", result.link);
                    $(".addToCart-" + item.course_id).text(result.message);
                    /*remove form wishlist*/
                    $(".love-" + item.course_id).prop("href", '#!');
                    $(".love-" + item.course_id).removeClass('primary-color-2');
                    $(".love-" + item.course_id).removeClass('icon-color');
                    $(".love-span-" + item.course_id).removeClass('la-heart-o');
                    $(".love-span-" + item.course_id).addClass('la-heart-o');

                    html += '<li><div class="cart-item"><div class="m-3"><div class="row"><div class="col-md-3 my-auto"><a href="' + item.link + '" target="_blank">' +
                        '<img src="' + item.image + '" alt="' + item.title + '" class="avatar-lg rounded-circle"></a></div>' +
                        '<div class="col-md-9 my-auto"><div class="item__info"><p class="item__info-link">' +
                        '<a href="' + item.link + '" target="_blank" class="f-18">' + item.title + '</a></p><span class="item__price theme-color">' + item.price + '</span>' +
                        '</div></div></div></div></div></li>';
                })

                $("#cartAppend").append(html)
            },
            error: function (err){console.log(err)}
        })
    }


}


function applyCoupon(data){

    let url = $('#couponUrl').val();
    let code = $('#inputCodeCoupon').val();
    let amount = $('#current-amout').val();

    let couponText = $('#couponApplied');
    

    
    if (url != null && url != undefined && code) {
        $.ajax({
            url: url,
            method: 'GET',
            data: {code: code, amount: amount},
            success: function (result) {
                console.log(result);
                if(result.status){
                    Swal.fire(
                        'Parabéns!',
                        'Cupom aplicado com sucesso',
                        'success'
                    )

                    totalPrice.html(`
                        Total: <span style="text-decoration: line-through;padding-left:4px;">R$${(result.coursePrice/100).toFixed(2).replace(".", ",")}</span> 
                        <strong> R$${(result.newAmount/100).toFixed(2).replace(".", ",")}</strong> (cupom)
                    `)

                    couponText.html(
                        `
                        Cupom aplicado! <br> <span style="text-decoration: line-through;padding-left:4px;">R$${(result.coursePrice/100).toFixed(2).replace(".", ",")}</span> 
                        <strong> R$${(result.newAmount/100).toFixed(2).replace(".", ",")}</strong>`
                    )

                    getInstallments(result.newAmount);

                }else{
                    Swal.fire(
                        'Ops!',
                        'Não foi possível aplicar o cupom',
                        'error'
                    )
                }

            },
            error: function(er){
                console.log(er);
            }
        })
    }else
        console.log('some variable is not set', url, code);
   
    
}

function getInstallments(amount = null){


    let url = $('#installmentUrl').val();

    if(!amount)
        amount = $('#current-amout').val();

    if (url != null && url != undefined) {
        $.ajax({
            url: url,
            method: 'GET',
            data: {amount: amount},
            success: function (result) {

                if(result.status){
                    localStorage.setItem('installments', JSON.stringify(result.installments))
                    renderInstallmentOptions(result.installments);
                }
                

            },
            error: function(er){
                console.log(er);
            }
        })
    }else
        console.log('some variable is not set', url, code);
}

function renderInstallmentOptions(installments){

    let prevValue = $('#select-installment').val();


    $('#select-installment')
    .find('option')
    .remove()

    $.each(installments, function(key, value) {   
        $('#select-installment')
            .append($(`<option ${prevValue? key == prevValue? ' selected ' : '' : '' } ></option>`)
            .attr("value", key)
            .text(` ${key}x - R$${(value.installment_amount/100).toFixed(2).replace(".", ",")}`)); 
   });

   if(prevValue)
        calculateAmount(prevValue, true);

}
/* 
function calculateAmount2(value){

    let inc = Number(value);
  
    let parcelaComJuros = ((result[`${(inc-1)}`][0].installment_amount)/100).toFixed(2).replace(".", ",");
    let total = ((result[`${(inc-1)}`][0].amount)/100).toFixed(2).replace(".", ",")
  
    if(inc > 1){
      totalPrice.innerHTML  = `
                <div> <span style="font-size: 1rem;"> ${inc}x de </span> R$<strong>${parcelaComJuros}</strong> </div>
                <div style="font-size: 0.7rem;"> Total: <strong> R$${total} </strong> <span class="sum-amount"> (${pagarmeTaxInc}% juros ao mês) </span> </div>`;
    }else{
      totalPrice.innerHTML  = `Total: <strong> R$${result[`${(inc-1)}`][0].amount.toFixed(2).replace(".", ",")}</strong>`
    }
  
  
  } */

function calculateAmount(value, cupomActive = false){

    let installment = localStorage.getItem('installments')? JSON.parse(localStorage.getItem('installments')) : null

    if(!installment)
        return;


    let inc = Number(value);

    let parcelaComJuros = ((installment[`${inc}`].installment_amount)/100).toFixed(2).replace(".", ",");
    let total = ((installment[`${inc}`].amount)/100).toFixed(2).replace(".", ",")

    if(inc > 1){
        totalPrice.html( `
                <div> <span style="font-size: 1rem;"> ${inc}x de </span> R$<strong>${parcelaComJuros}</strong> </div>
                <div style="font-size: 0.7rem;"> Total: <strong> R$${total} </strong> <span class="sum-amount"> (${pagarmeTaxInc}% juros ao mês) </span> </div>`);
    }else{
        if(cupomActive){
        }else{

            totalPrice.html(`Total: <strong> R$${(installment[`${inc}`].amount/100).toFixed(2).replace(".", ",")}</strong>`)
        }
    }


}



//show the modal in this function
function forModal(url, message) {
    $('#show-modal').modal('show');
    $('#title').text(message);
    $('#show-form').load(url);
    $('body').on('shown.bs.modal', '.modal', function () {
        $(this).find('select').each(function () {
            var dropdownParent = $(document.body);
            if ($(this).parents('.modal.in:first').length !== 0)
                dropdownParent = $(this).parents('.modal.in:first');
            $(this).select2({
                dropdownParent: dropdownParent
            });
        });
    });
}

/*for wishlist*/

/*this function for */
function wishList() {
    var url = $('#wishUrl').val();
    var img = $('#wishtImg').val();
    if (url != null && url != undefined) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function (result) {
                $("#wishlistAppend").empty();
                if (result.data.length == 0) {
                    $('.wishlist-quantity').text(0)
                    $("#wishlistAppend").append('<img src="'+img+'" class="w-100" alt="">');
                } else {
                    $('.wishlist-quantity').text(result.data.length)

                }

                var html = "";
                result.data.forEach(function (item, index) {
                    $(".love-" + item.course_id).addClass('primary-color-2');
                    $(".love-" + item.course_id).addClass('icon-color');
                    $(".love-span-" + item.course_id).addClass('la-heart');
                    $(".love-span-" + item.course_id).removeClass('la-heart-o');

                    /*remove from cart*/
                    $(".addToCart-" + item.course_id).prop("href", '#!');
                    $(".addToCart-" + item.course_id).text(result.message);


                    /*demo ne theme */


                    html += '<li><div class="cart-item"><div class="m-3"><div class="row"><div class="col-md-3 my-auto"><a href="' + item.link + '" target="_blank">' +
                        '<img src="' + item.image + '" alt="' + item.title + '"  class="avatar-lg rounded-circle"></a></div>' +
                        '<div class="col-md-9"><div class="item__info"><p class="">' +
                        '<a href="' + item.link + '" target="_blank" class="f-18">' + item.title + '</a></p><span class="item__price theme-color">' + item.price + '</span>' +
                        '</div></div></div></div></div></li>';
                })
                $("#wishlistAppend").append(html);
            }
        })
    }
}

/*add cart or wishlist*/
function addToCart(course_id, url) {
    if (course_id != null && url != null) {
        $.ajax({
            url: url,
            method: 'GET',
            data: {cart: course_id},
            success: function (result) {
                $.notify.defaults({
                    elementPosition: 'middle right',
                    globalPosition: 'right middle',
                })
                if (result.id_is != null) {
                    /*remove form wishlist*/
                    $.notify('Removed Successfully', 'info')
                    $(".love-" + result.id_is).prop("href", '#!');
                    $(".love-" + result.id_is).removeClass('primary-color-2');
                    $(".love-" + result.id_is).removeClass('icon-color');
                    $(".love-span-" + result.id_is).removeClass('la-heart-o');
                    $(".love-span-" + result.id_is).addClass('la-heart-o');
                } else {

                    $.notify('Added Successfully', 'success')
                }
                cartList();
                wishList();
                enrollCourse();
            }
        })
    } else {
        location.reload()
    }

}


/*remove from wishlist*/
function removeToWishlist(id, url) {
    $('#wish-' + id).remove();
    if (url != null) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function (result) {
                wishList()
            }
        })
    } else {
        location.reload()
    }
}


/*change currency*/
function submitForm() {
    $('#filter').submit();
}


$('#search').on('change', function () {
    $('#search_list').removeClass('search-scroll-hide');
    $('#search_list').addClass('search-scroll');
    if ($(this).val().length === 0) {
        $('#search_list').removeClass('search-scroll-hide');
        $('#search_list').removeClass('search-scroll');
    }
});

/*change languageChange*/
function languageChange() {
    $('#ru-lang').submit()
}

/*change currency*/
function currencyChange() {
    $('#ru-currency').submit()
}

function openNav() {
    document.getElementById("mySidebar").style.width = "425px";
}

function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
}

function notifySidebaropenNav() {
    document.getElementById("notifySidebar").style.width = "425px";
}

function notifySidebarcloseNav() {
    document.getElementById("notifySidebar").style.width = "0";
}

function wishopenNav() {
    document.getElementById("wishSidebar").style.width = "425px";
}

function wishcloseNav() {
    document.getElementById("wishSidebar").style.width = "0";
}

/*search*/
$('#search').on('keyup', function () {

    var search = $(this).val();
    console.log(search);
    var url = $('#searchUrl').val();
    $.ajax({
        url: url,
        method: 'GET',
        data: {key: search},
        success: function (result) {

            $("#appendSearchCart1").empty();
            if (result.data.length == 0) {
                $("#appendSearchCart1").empty();
                $("#appendSearchCart1").removeClass('overflowScroll');
            }

            var html = "";
            result.data.forEach(function (item, index) {
                if (item.image == null) {
                    $("#appendSearchCart1").removeClass('overflowScroll');
                    html += ' <div class="row card p-8"><h4 class="f-16 w-100 text-center">' + item.title + '</h4></div>';
                } else {
                    html += ' <div class="row">\n' +
                        '      <div class="col-12">\n' +
                        '                                                  <a href="' + item.link + '">\n' +
                        '                                                      <div class="card">\n' +
                        '                                                          <div class="card-horizontal">\n' +
                        '\n' +
                        '                                                              <div class="img-square-wrapper p-8">\n' +
                        '                                                                  <img  src="' + item.image + '" alt="" width="80" height="60">\n' +
                        '                                                              </div>\n' +
                        '\n' +
                        '                                                              <div class="card-body p-1rem">\n' +
                        '                                                                  <h4 class="card-title f-16">' + item.title + '</h4>\n' +
                        '                                                              </div>\n' +
                        '\n' +
                        '                                                          </div>\n' +
                        '                                                      </div>\n' +
                        '                                                  </div>\n' +
                        '                                                  </a>\n' +
                        '                                              </div>';
                    $('#appendSearchCart1').addClass('overflowScroll');
                }

            })
            $('#appendSearchCart1').append(html);

            if (result.data.length <= 5) {
                $("#appendSearchCart1").removeClass('overflowScroll');
            }

        }
    })


});


/*search*/
$('#slider-search').on('keyup', function () {

    var search = $(this).val();

    var url = $('#searchUrl').val();
    $.ajax({
        url: url,
        method: 'GET',
        data: {key: search},
        success: function (result) {
            console.log(result)
            $("#appendSearchCart2").empty();
            if (result.data.length == 0) {
                $("#appendSearchCart2").empty();
                $("#appendSearchCart2").removeClass('overflowScroll');
            }
            var html = "";
            result.data.forEach(function (item, index) {
                if (item.image == null) {
                    $("#appendSearchCart2").removeClass('overflowScroll');
                    html += ' <div class="row card p-8"><h4 class="f-16 w-100 text-center">' + item.title + '</h4></div>';
                } else {
                    html += ' <div class="row">\n' +
                        '      <div class="col-12">\n' +
                        '                                                  <a href="' + item.link + '">\n' +
                        '                                                      <div class="card">\n' +
                        '                                                          <div class="card-horizontal">\n' +
                        '\n' +
                        '                                                              <div class="img-square-wrapper p-8">\n' +
                        '                                                                  <img  src="' + item.image + '" alt="" width="80" height="60">\n' +
                        '                                                              </div>\n' +
                        '\n' +
                        '                                                              <div class="card-body p-1rem">\n' +
                        '                                                                  <h4 class="card-title f-16">' + item.title + '</h4>\n' +
                        '                                                              </div>\n' +
                        '\n' +
                        '                                                          </div>\n' +
                        '                                                      </div>\n' +
                        '                                                  </div>\n' +
                        '                                                  </a>\n' +
                        '                                              </div>';
                    $('#appendSearchCart2').addClass('overflowScroll');
                }

            })
            $('#appendSearchCart2').append(html);
            if (result.data.length <= 5) {
                $("#appendSearchCart2").removeClass('overflowScroll');
            }
        }
    })


});

(function ($){
    $.fn.counter = function() {
        const $this = $(this),
            numberFrom = parseInt($this.attr('data-from')),
            numberTo = parseInt($this.attr('data-to')),
            delta = numberTo - numberFrom,
            deltaPositive = delta > 0 ? true : false,
            time = parseInt($this.attr('data-time')),
            changeTime = 10;

        let currentNumber = numberFrom,
            value = delta*changeTime/time;
        var interval1;
        const changeNumber = () => {
            currentNumber += value;
            //checks if currentNumber reached numberTo
            (deltaPositive && currentNumber >= numberTo) || (!deltaPositive &&currentNumber<= numberTo) ? currentNumber=numberTo : currentNumber;
            this.text(parseInt(currentNumber));
            currentNumber == numberTo ? clearInterval(interval1) : currentNumber;
        }

        interval1 = setInterval(changeNumber,changeTime);
    }
}(jQuery));

$(document).ready(function(){

    $('.count-up').counter();
    $('.count-up1').counter();
    $('.count-up2').counter();
    $('.count-up3').counter();


    // new WOW().init();

    setTimeout(function () {
        $('.count-up').counter();
    }, 3000);


    setTimeout(function () {
        $('.count-up1').counter();
    }, 3000);


    setTimeout(function () {
        $('.count-up2').counter();
    }, 3000);


    setTimeout(function () {
        $('.count-up3').counter();
    }, 3000);

});
/*made affiliate link*/
$('#genurl').on('click',function () {
    var campaign = $('#campaign').val();
    var url = $('#url').val();
    var ref = $('#ref').val();

    if (url){
        var  custom_url = url+ref;
        if (campaign){
            custom_url+='&campaign='+campaign;
        }
    }else{
        var custom_url = $('#default_url').val();
        if (campaign){
            custom_url+='&campaign='+campaign;
        }
    }
    $('#link').val('');
    $('#link').val(custom_url);
});
