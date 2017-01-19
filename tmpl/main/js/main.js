/**/
var galleries = new Array();
var carousels = new Array();

function init() {
	//start_slide($("#slider"), 3000);
	$("#slides").on("click",".play",function(){
		$(this).children().toggleClass("fa-play fa-pause");
		if ($(this).children().hasClass("fa-pause")) {
			$("#slideshow").slick('slickPlay');
		} else {
			$("#slideshow").slick('slickPause');
		}
	});
	$(".childarticles").slick({
  infinite: true,
  autoplay: true,
  dots: true,
	arrows: false
});
	$(".childarticles").removeClass("hidden");
	$("#slideshow").slick({
  infinite: true,
  autoplay: false,
  arrows: false
});
	$("#slideshow").removeClass("hidden");
	$("#links").slick({
  	infinite: true,
  	slidesToShow: 1,
	autoplay: true,
	prevArrow: '',
	nextArrow: '',
  	slidesToScroll: 1,
  	slidesPerRow: 4,
    rows: 3
});
	$("#links").removeClass("hidden");
$("#database").slick({
infinite: true,
slidesToShow: 8,
prevArrow: '<button type="button" class="slick-prev"><span class="fa fa-chevron-left"></span></button>',
nextArrow: '<button type="button" class="slick-next"><span class="fa fa-chevron-right"></span></button>',
arrows: false
});
	$("#database").removeClass("hidden");
	$(".mobile-icon").click(function(){
		$(this).next().toggleClass("show");
	});
	$(".comment-form").submit(function(event){
		event.preventDefault();
		var id = $(this).children(".id").val();
		var subject = $(this).find(".subject").val();
		var name = $(this).find(".name").val();
		var email = $(this).find(".email").val();
		var text = $(this).find(".text").val();
		var response = grecaptcha.getResponse();
		console.log(response)
		$.ajax({
			url: "/ajax/blogcomment",
			method: "POST",
			data: {id: id, subject: subject, name: name, email: email, text: text, response: response},
			context: $("#messages")
		}).done(function(data){;
			$(this).html(data);
		});
	});
	$(".additional-menu ul.menus > li > span").click(function(){
			$(this).next().toggleClass("active");
	})
	$(".search-module form").submit(function(){
		var text = $(this).children("input").val();
		load_search(text);
		event.preventDefault();
	});
	$(window).scroll(function() {

    var wh = $(window).height();
		var dh = $(document).height();
		var fh = $('.footer').height();
    var st = $(window).scrollTop();
    var el = $('.additional-menu');
		var eh = $(el).height();
		//console.log("wh: "+wh+", dh: "+dh+", fh: "+fh+", st+wh: "+(st+wh+fh)+", eh: "+(eh));
    if ( st + fh + wh + eh + 20 <= dh) {
        //fix the positon and leave the green bar in the viewport
        el.css({
            position: 'fixed',
            left: el.offset().left,
						zIndex: 100,
						width: el.width(),
            bottom: 0
        });
    }
    else {
        // return element to normal flow
        el.removeAttr("style");
    }

});
	$(".blog").on("click",".page",function(event){
		event.preventDefault();
		var id = $(this).data("post");
		var total = $(this).data("total");
		var page = $(this).data("page");
		$.ajax({
			url: "/ajax/blogcomments",
			method: "POST",
			data: {id: id, total: total, page: page},
			context: $("#comments"+id)
			
		}).done(function(data){
			$(this).html(data);
		});
	});
}
function start_slide(elem, speed) {
	var name = $(elem).attr("id");
	galleries[name] = [];
	galleries[name]['next'] = 1;
	start_rotate(elem, speed);
}

function start_rotate(elem, speed) {
	var name = $(elem).attr("id");
	var count = $(elem).children().length;
	galleries[name]['rm'] = setInterval(function () {
	var li = $(elem).children(':nth-child('+galleries[name]['next']+')');
	$(li).removeClass('active');
	galleries[name]['next']++;
	if (galleries[name]['next'] > count) galleries[name]['next'] = 1;
	li = $(elem).children(':nth-child('+galleries[name]['next']+')');
	$(li).addClass("active");
}, speed);
}
function load_search(text) {
	var url = $(".lang .active a").attr("href");
	url = url.substring(url.indexOf(".kz")+1,3);
	switch (url) {
		case '/ru','/en':window.location.href = url + "/search/"+text; break;
		default: window.location.href = "/kz/search/"+text; break;
	}
}
