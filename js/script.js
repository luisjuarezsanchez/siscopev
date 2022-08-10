 $(window).on('scroll', function() {

 	if ($(window).scrollTop() + $(window).height() > $('.wrapper').outerHeight()) {
 		$('.arrow').hide();
 	} else {
 		$('.arrow').show();
 	}
 });


 $('.arrow').click(function(){
 	$("html").animate({ scrollTop: $('html').prop("scrollHeight")}, 1200);
 });


function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}

/* function myFunction() {
    document.getElementById("demo").style.color = "red";
    //document.getElementById("demo").setAttribute("disabled","disabled");
}*/