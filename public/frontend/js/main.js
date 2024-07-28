"use strict";
/* registration form */
let inputCPF = document.querySelector(".cpf-input");
let inputCPFHidden = document.querySelector(".cpf-input-hidden");

let inputPhone = document.querySelector(".phone-input");
let inputPhoneHidden = document.querySelector(".phone-input-hidden");

if(inputCPF && inputPhone){

    
    inputPhone.onkeyup = (e) => {
      if (inputPhone.value.length <= 11)
        inputPhone.value = formatPhone(e.target.value);
      else inputPhone.value = inputPhone.value.slice(0, 14);
    
      if (inputPhoneHidden)
        inputPhoneHidden.value = inputPhone.value
          ? inputPhone.value.replace(/\D/g, "")
          : "";
    };
    
    inputCPF.onkeyup = (e) => {
      if (inputCPF.value.length <= 11) inputCPF.value = formatCPF(e.target.value);
      else inputCPF.value = inputCPF.value.slice(0, 14);
    
      if (inputCPFHidden)
        inputCPFHidden.value = inputCPF.value
          ? inputCPF.value.replace(/\D/g, "")
          : "";
    };
    
    function formatCPF(cpf) {
      //retira os caracteres indesejados...
      cpf = cpf.replace(/[^\d]/g, "");
    
      //realizar a formatação...
      return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
    }
    
    function formatPhone(phone) {
      if (phone.length == 11) {
        var x = phone.replace(/\D/g, "").match(/(\d{2})(\d{1})(\d{4})(\d{4})/);
        phone = `${x[1]} ${x[2]} ${x[3]}-${x[4]}`;
        return phone;
      } else return phone;
    }
}

/* payment shopping cart */
let inputValidade = document.querySelector('#input-validade');
let inputValidadeHidden = document.querySelector('#input-validade-hidden');

if(inputValidade){
  inputValidade.onkeyup = (e) => {
  
  if (inputValidade.value.length <= 5) inputValidade.value = formatValidade(e.target.value);
  else inputValidade.value = inputValidade.value.slice(0, 5);
  
  if (inputValidadeHidden)
      inputValidadeHidden.value = inputValidade.value
      ? inputValidade.value.replace(/\D/g, "")
      : "";
  };
}


function formatValidade(v) {
    //retira os caracteres indesejados...
    v = v.replace(/[^\d]/g, "");
    
    //realizar a formatação...
    return v.replace(/(\d{2})(\d{2})/, "$1/$2");
}


let selectInmt = document.querySelector('#select-installment');
//let totalPrice = document.querySelector('.total-price');
let currentAmountInput = document.querySelector('#current-amout')
let paymentMethod = document.querySelector('#payment-method');
let currentAmount = document.querySelector('#current-amout');
currentAmount = currentAmount? currentAmount.value : null;
let pagarmeTax = document.querySelector('#pagarme-tax');
pagarmeTax = pagarmeTax? Number(pagarmeTax.value) : null;
let pagarmeTaxInc = document.querySelector('#pagarme-tax-inc');
pagarmeTaxInc = pagarmeTaxInc ? Number(pagarmeTaxInc.value) : null; 
let installmentsHidden = document.querySelector('#installments-hidden');
installmentsHidden = installmentsHidden ? installmentsHidden.value : null;

let obj, result;
if(installmentsHidden){
  obj = JSON.parse(installmentsHidden)
  result = Object.keys(obj).map((key) => [obj[key]]);
}
let copyTextValue = document.querySelector('#copy-text-value');
/* 
let btnCredit = document.querySelector('#btn-credit');
let btnPix = document.querySelector('#btn-pix');
let btnBoleto = document.querySelector('#btn-boleto');

if(btnCredit){
  btnCredit.onclick = (event) => selectTab(event, 'credit_card');
  btnPix.onclick = (event) => selectTab(event, 'pix');
  btnBoleto.onclick = (event) => selectTab(event, 'boleto');

 
} */

currentAmount /= 100;

/* if(selectInmt){

  selectInmt.onchange = (e) => {
      let value = e.target.value
      calculateAmount(value);                                          ;
  }
} */

function copyToClipboard(text){
  navigator.clipboard.writeText(text).then(function() {
    document.querySelector('.copyClip > span').innerHTML = 'Copiado!'
  }, function(err) {
    console.error('Async: Could not copy text: ', err);
  });
}

function calcInc(amount, inc){
    return ((((inc - 1) * pagarmeTaxInc) + pagarmeTax)/100) * amount;
}

function calculateAmount(value){

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


}

function calculateAmount2(value){
    let inc = Number(value);
    if(inc > 1){
        let desc = calcInc(currentAmount, inc).toFixed(2);
        let parcelaComJuros = ((((currentAmount+Number(desc))/inc)).toFixed(2)).replace(".", ",");
        let total = ((currentAmount + Number(desc)).toFixed(2)).replace(".", ",");
        let jurosAoMes = (Number(desc)/inc).toFixed(2).replace(".",",");
        totalPrice.innerHTML  = `
            <div> <span style="font-size: 1rem;"> ${inc}x de </span> R$<strong>${parcelaComJuros}</strong> </div>
            <div style="font-size: 0.7rem;"> Total: <strong> R$${total} </strong> <span class="sum-amount"> (${pagarmeTaxInc}% juros ao mês) </span> </div>`;
    }else{
        totalPrice.innerHTML  = `Total: <strong> R$${(currentAmount.toFixed(2)).replace(".", ",")}</strong>`
    }     
}



function selectTab(evt, tab) {
    changeTabLogic(evt, tab);
}

function changeTabLogic(evt, tab){
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tab).style.display = "block";
    evt.currentTarget.className += " active";
}

!(function (s) {
  s(window).on("load", function () {
    s(".preloader").delay("500").fadeOut(2e3);
  }),
    s(document).ready(function () {
      /* s(document).on("click", ".side-menu-wrap .menu-plus-icon", function() {
            return s(this).closest(".sidenav__item").siblings().removeClass("active").find(".side-sub-menu").slideUp(200), s(this).closest(".sidenav__item").toggleClass("active").find(".side-sub-menu").slideToggle(200), !1
        }); */
      var t = s("#scroll-top"),
        i = document.querySelector(".header-menu-content"),
        o = i.offsetTop;
      s(window).on("scroll", function () {
        s(window).scrollTop() >= o
          ? ((document.body.style.paddingTop = i.offsetHeight + "px"),
            document.body.classList.add("fixed-nav"))
          : ((document.body.style.paddingTop = 0),
            document.body.classList.remove("fixed-nav")),
          300 <= s(this).scrollTop() ? t.show() : t.hide();
        var e = ".skills .skill";
        0 !== s(e).length && spy_scroll(e);
      }),
        s(document).on("click", "#scroll-top", function () {
          s("html, body").animate(
            {
              scrollTop: 0,
            },
            1e3
          );
        }),
        s(document).on(
          "click",
          ".header-category ul li .dropdown-menu-item>li>.menu-collapse",
          function () {
            return (
              s(this)
                .closest("li")
                .siblings()
                .removeClass("active")
                .find(".sub-menu")
                .slideUp(200),
              s(this)
                .closest("li")
                .toggleClass("active")
                .find(".sub-menu")
                .slideToggle(200),
              !1
            );
          }
        ),
        /* s(document).on("click", ".logo-right-button .side-menu-open", function() {
            
            s(".side-nav-container").toggleClass("active")
            console.log('click');
        }) */ s(document).on(
          "click",
          ".logo-right-button .user-menu-open",
          function () {
            s(".user-nav-container").addClass("active");
          }
        ),
        s(document).on("click", ".dashboard-nav-trigger-btn", function () {
          s(".dashboard-nav-container").addClass("active");
        }),
        /* s(document).on("click", ".humburger-menu .side-menu-close", function() {
            s(".side-nav-container, .user-nav-container, .dashboard-nav-container").removeClass("active")
        }), */ s(".homepage-slide1").owlCarousel({
          items: 1,
          nav: !0,
          dots: !0,
          autoplay: !1,
          loop: !0,
          smartSpeed: 6e3,
          animateOut: "slideOutRight",
          animateIn: "fadeIn",
          active: !0,
          navText: [
            "<i class='la la-angle-left'></i>",
            "<i class='la la-angle-right'></i>",
          ],
        }),
        s(".homepage-slide1").on("translate.owl.carousel", function () {
          s(
            ".single-slide-item .section__title, .single-slide-item .section__desc"
          )
            .removeClass("animated fadeInUp")
            .css("opacity", "0"),
            s(".single-slide-item .btn-box")
              .removeClass("animated fadeInDown")
              .css("opacity", "0");
        }),
        s(".homepage-slide1").on("translated.owl.carousel", function () {
          s(
            ".single-slide-item .section__title, .single-slide-item .section__desc"
          )
            .addClass("animated fadeInUp")
            .css("opacity", "1"),
            s(".single-slide-item .btn-box")
              .addClass("animated fadeInDown")
              .css("opacity", "1");
        }),
        s(".course-carousel").owlCarousel({
          loop: !0,
          items: 3,
          nav: !0,
          dots: !1,
          smartSpeed: 500,
          autoplay: !1,
          margin: 30,
          navText: [
            "<i class='la la-angle-left'></i>",
            "<i class='la la-angle-right'></i>",
          ],
          responsive: {
            320: {
              items: 1,
            },
            992: {
              items: 3,
            },
          },
        }),
        s(".view-more-carousel").owlCarousel({
          loop: !0,
          items: 2,
          nav: !1,
          dots: !0,
          smartSpeed: 500,
          autoplay: !0,
          margin: 15,
          responsive: {
            320: {
              items: 1,
            },
            768: {
              items: 2,
            },
          },
        }),
        s(".view-more-carousel-2").owlCarousel({
          loop: !0,
          items: 3,
          nav: !1,
          dots: !0,
          smartSpeed: 500,
          autoplay: !0,
          margin: 15,
          responsive: {
            320: {
              items: 1,
            },
            768: {
              items: 3,
            },
          },
        }),
        s(".video-play-btn").magnificPopup({
          type: "video",
        }),
        s(".testimonial-carousel").owlCarousel({
          loop: !0,
          items: 5,
          nav: !1,
          dots: !0,
          smartSpeed: 500,
          autoplay: !1,
          margin: 30,
          autoHeight: !0,
          responsive: {
            320: {
              items: 1,
            },
            767: {
              items: 2,
            },
            992: {
              items: 3,
            },
            1025: {
              items: 4,
            },
            1440: {
              items: 5,
            },
          },
        }),
        s(".testimonial-carousel-2").owlCarousel({
          loop: !0,
          items: 2,
          nav: !0,
          dots: !1,
          smartSpeed: 500,
          autoplay: !1,
          margin: 30,
          autoHeight: !0,
          navText: [
            "<i class='la la-angle-left'></i>",
            "<i class='la la-angle-right'></i>",
          ],
          responsive: {
            320: {
              items: 1,
            },
            768: {
              items: 2,
            },
          },
        }),
        s(".client-logo").owlCarousel({
          loop: !0,
          items: 5,
          nav: !1,
          dots: !1,
          smartSpeed: 500,
          autoplay: !1,
          responsive: {
            0: {
              items: 2,
            },
            481: {
              items: 3,
            },
            768: {
              items: 4,
            },
            992: {
              items: 5,
            },
          },
        }),
        s(".blog-post-carousel").owlCarousel({
          loop: !0,
          items: 3,
          nav: !1,
          dots: !0,
          smartSpeed: 500,
          autoplay: !1,
          margin: 30,
          responsive: {
            320: {
              items: 1,
            },
            992: {
              items: 3,
            },
          },
        }),
        s('[data-toggle="tooltip"]').tooltip(),
        s(".faq-body > .faq-panel.is-active").children(".faq-content").show(),
        s(".faq-body > .faq-panel").on("click", function () {
          s(this)
            .siblings(".faq-panel")
            .removeClass("is-active")
            .children(".faq-content")
            .slideUp(200),
            s(this)
              .toggleClass("is-active")
              .children(".faq-content")
              .slideToggle(200);
        }),
        s(document).on("click", ".portfolio-filter li", function () {
          var e = s(this).attr("data-filter");
          s(".portfolio-list").isotope({
            filter: e,
          }),
            s(".portfolio-filter li").removeClass("active"),
            s(this).addClass("active");
        }),
        s(".portfolio-list").isotope({
          itemSelector: ".single-portfolio-item",
          percentPosition: !0,
          masonry: {
            columnWidth: ".single-portfolio-item",
            horizontalOrder: !0,
          },
        }),
        s("[data-fancybox]").fancybox({
          buttons: [
            "zoom",
            "share",
            "slideShow",
            "fullScreen",
            "download",
            "thumbs",
            "close",
          ],
        }),
        (s.fancybox.defaults.animationEffect = "zoom"),
        s("#map").length &&
          initMap("map", 40.717499, -74.044113, "images/map-marker.png"),
        s(document).on("click", ".input-number-increment", function () {
          var e = s(this).parents(".input-number-group").find(".input-number"),
            t = parseInt(e.val(), 10);
          e.val(t + 1);
        }),
        s(document).on("click", ".input-number-decrement", function () {
          var e = s(this).parents(".input-number-group").find(".input-number"),
            t = parseInt(e.val(), 10);
          e.val(t - 1);
        }),
        s(".card-preview").tooltipster({
          contentCloning: !0,
          interactive: !0,
          side: "right",
          delay: 100,
          animation: "swing",
        }),
        s(".filer_input").filer({
          limit: 10,
          maxSize: 100,
          showThumbs: !0,
        }),
        s(".datepicker").dateTimePicker({
          format: "dd/MM/yyyy",
        }),
        s(".emoji-picker").emojioneArea({
          pickerPosition: "top",
        }),
        s(".counter").counterUp({
          delay: 10,
          time: 1e3,
        }),
        s(".course-list > .course-item-link").on("click", function () {
          s(this).addClass("active"),
            s(this).siblings().removeClass("active"),
            s(this).is(".active-resource")
              ? s(".lecture-viewer-text-wrap").addClass("active")
              : s(this).not(".active-resource") &&
                s(".lecture-viewer-text-wrap").removeClass("active");
        }),
        s(document).on("click", ".sidebar-close", function () {
          s(
            ".course-dashboard-sidebar-column, .course-dashboard-column, .sidebar-open"
          ).addClass("active");
        }),
        s(document).on("click", ".sidebar-open", function () {
          s(
            ".course-dashboard-sidebar-column, .course-dashboard-column, .sidebar-open"
          ).removeClass("active");
        }),
        s(document).on("click", ".ask-new-question-btn", function () {
          s(".new-question-wrap, .question-overview-result-wrap").addClass(
            "active"
          );
        }),
        s(document).on(
          "click",
          ".question-meta-content, .question-replay-btn",
          function () {
            s(".replay-question-wrap, .question-overview-result-wrap").addClass(
              "active"
            );
          }
        ),
        s(document).on("click", ".back-to-question-btn", function () {
          s(
            ".new-question-wrap, .question-overview-result-wrap, .replay-question-wrap"
          ).removeClass("active");
        }),
        s(document).on("click", ".swapping-btn", function () {
          s(this).siblings(".bookmark-icon").toggleClass("active");
          var e = s(this);
          e.text() == e.data("text-swap")
            ? e.text(e.data("text-original"))
            : e.text(e.data("text-swap"));
        }),
        s(document).on("click", ".search-form-btn", function () {
          s(".search-course-form").toggleClass("active");
        }),
        s(document).on("click", ".search-close-icon", function () {
          s(".search-course-form").removeClass("active");
        }),
        s(document).on("click", ".collection-link", function () {
          s(this).children(".collection-icon").toggleClass("active");
        }),
        s(".sort-ordering-select").selectpicker({
          liveSearch: !0,
          liveSearchPlaceholder: "Search",
          liveSearchStyle: "contains",
          size: 5,
        }),
        s("#teamModal").on("show.bs.modal", function (e) {
          var t = s(e.relatedTarget).data("whatever");
          s(this)
            .find(".modal-title")
            .text(t + "'s Bio");
        });

      var sidebar = document.querySelector(".side-nav-container");
      var sibarOpenBtn = document.querySelector(".side-menu-open");
      var sidebarCloseBtn = document.querySelector(".side-menu-close");

      //submenu
      var subMenu = document.querySelector(".side-sub-menu");
      var subMenuBtn = document.querySelector(".menu-plus-icon");

      var flagSubMenu = false;

      if(subMenuBtn){
        subMenuBtn.onclick = () => {
          subMenu.classList.toggle("active");
  
          if (!flagSubMenu) {
            subMenu.style.display = "block";
            flagSubMenu = true;
          } else {
            subMenu.style.display = "none";
            flagSubMenu = false;
          }
        };

      }


      sibarOpenBtn.onclick = () => {
        sidebar.classList.toggle("active");
      };

      sidebarCloseBtn.onclick = () => {
        sidebar.classList.toggle("active");
      };
    });
})(jQuery);
