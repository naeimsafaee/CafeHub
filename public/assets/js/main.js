if($(window).width() < 1200){
    $(window).on("load", function () {
        $(".show").modal("show");
    });
}

/*if($(window).width() < 1200){
  $(window).on("load", function () {
    $(".show").modal("show");
  });
}*/

/*$(document).find('.child-modal').on('hidden.bs.modal', function () {
  console.log('hiding child modal');
  $('body').addClass('modal-open');
});
*/



/*

$(".plus").click(function(){
  var reqCount = $(this).parent().children(":first").text();
  reqCount = toEnglishNumber(reqCount.toString())
  reqCount < 9 ? reqCount ++ : reqCount = 10;
  $(this).parent().children(":first").text(e2p(reqCount.toString()));
});

$(".delete").click(function(){
  var reqCount = $(this).parent().children(":first").text();
  reqCount = toEnglishNumber(reqCount.toString())
  reqCount < 2 ? reqCount = 1 : reqCount --;
  $(this).parent().children(":first").text(e2p(reqCount.toString()));
});
*/

const e2p = s => s.replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);
function toEnglishNumber(strNum) {
  var pn = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];
  var en = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];

  var cache = strNum;
  for (var i = 0; i < 10; i++) {
      var regex_fa = new RegExp(pn[i], 'g');
      cache = cache.replace(regex_fa, en[i]);
  }
  return cache;
}

$(document).ready(function() {
    $('.nav_sample_test').bind('click', function(e) {
        e.preventDefault();
        var target = $(this).attr("href");

        $('html, body').stop().animate({
            scrollTop: $(target).offset().top
        }, 600, function() {
            location.hash = target;
        });

        return false;
    });
});

$(window).scroll(function() {
    var scrollDistance = $(window).scrollTop();
    $('.page-section').each(function(i) {
        if ($(this).position().top <= scrollDistance) {
            $('.categorymenu a.active').removeClass('active');
            $('.categorymenu a').eq(i).addClass('active');
        }
    });
}).scroll();
