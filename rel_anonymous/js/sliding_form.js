var slideIndex = 1;
var downloadString = "";

$(function () {
//    $(window).on("load", function() {
//      $("#loader").fadeOut(500);
//    });

//    $(document).ready(function() {
//      $("#loader").fadeOut(500);
//    });

    var interval = setInterval(function() {
      if(document.readyState === 'complete') {
        clearInterval(interval);
        $("#loader").fadeOut(500);
      }    
    }, 500);

    showSlides(slideIndex);

	$(document).on('click', '.dashboard', function(e) {
		var parentOffset = $(this).offset();
		var center = $(this).width()/2;
		var relX = e.pageX - parentOffset.left;
		//console.log(relX);
		if(relX < center) {
		  plusSlides(-1);
		} else {
		  plusSlides(1);  
		}
	});

    $('input[type="radio"]').keydown(function(e)
    {
      var arrowKeys = [37, 38, 39, 40];
      if (arrowKeys.indexOf(e.which) !== -1)
      {
        $(this).blur();
        return false;
      }
    });

	$(document).on('keyup', '', function(e) {
		console.log("key is pressed");
		if(e.keyCode == 37) {
		  plusSlides(-1);
		}
		if(e.keyCode == 39) {
		  plusSlides(1);  
		}
	});

	$('form').on('submit', function (e) {
		//console.log("sent");
		e.preventDefault();

		$.ajax({
			type: 'post',
			url: 'submit.php',
			data: $('form').serialize(),
			success: function (result) {
				if(result.startsWith("err")) {
					alert(result);
				} else {
					document.getElementsByClassName("submit_button")[0].style.display = "none";
					document.getElementsByClassName("form_finished")[0].style.display = "";
					document.getElementsByClassName("result_code")[0].innerHTML = result.replace(/\s/g, "<br/ >");
			  		//$('form_finished').hide();
					downloadString = result;
					download_file();
                }
			}
		});
	});
});

function init() {
  showSlides(slideIndex); 
}

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
	  slides[i].style.display = "none";  
  }
  if(slideIndex > 1) {
    dots[0].className = "dot done";
  }
  var slidesChecked = 0;
  for (i = 1; i < dots.length; i++) {
      if(isChecked(slides[i].getElementsByClassName("btn-group")[0])) {
        var btnGroup2 = slides[i].getElementsByClassName("btn-group2")[0];
        if(btnGroup2 == null || isChecked(btnGroup2)) {
          dots[i].className = "dot done";
          slidesChecked++;
          continue;
        }
      }
      // else mark as non active
	  dots[i].className = "dot";
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className = "dot active";
  if(slideIndex > 1 && slideIndex < dots.length) {
    var actDashboard=slides[slideIndex-1].getElementsByClassName("dashboard")[0];
    if(actDashboard != null) {
      var actHeight = actDashboard.offsetHeight;
      var btnHeight = Math.round((actHeight-20)/5);
      var lblPadding = Math.round((btnHeight-12)/2);
      //console.log(btnHeight);
      var btnGroup=slides[slideIndex-1].getElementsByClassName("btn-group2")[0];
      //console.log(btnGroup);
      if(btnGroup != null) {
        var items = btnGroup.getElementsByTagName("li");
        for(i = 0; i < items.length; i++) {
          items[i].style.height = btnHeight + "px";
          var item1 = items[i].getElementsByTagName("label")[0];
          item1.style.padding = lblPadding + "px 0 0 0";
          //console.log(item1);
        }
      }
      var btnHide=slides[slideIndex-1].getElementsByClassName("hide_btn")[0];
      if(btnHide != null) {
        btnHide.style.top = (actHeight + 20) + "px";
      }
    }
  }
  if(slides[slides.length-1].getElementsByClassName("form_finished")[0].style.display == "none") {
    if(slideIndex == dots.length) {
		if(slidesChecked == dots.length - 2) {
		  // all done
		  slides[slideIndex-1].getElementsByClassName("form_done")[0].style.display = ""
		  slides[slideIndex-1].getElementsByClassName("submit_button")[0].style.display = "";
		  slides[slideIndex-1].getElementsByClassName("form_uncomplete")[0].style.display = "none";
		} else {
		  // uncomplete
		  slides[slideIndex-1].getElementsByClassName("form_done")[0].style.display = "none";
		  slides[slideIndex-1].getElementsByClassName("submit_button")[0].style.display = "none";
		  slides[slideIndex-1].getElementsByClassName("form_uncomplete")[0].style.display = "";
		}
     }
  } else {
    if(slideIndex != dots.length) {
      dots[dots.length-1].className = "dot done";
    }
  }
}

function isChecked(btnGroup) {
  if(btnGroup != null) {
    inputs=btnGroup.getElementsByTagName("input");
    for(i = 0; i < inputs.length; i++) {
      if(inputs[i].checked) {
         return true;
      }
    }
  }
  return false;
}

function hideButtons() {
  var slides = document.getElementsByClassName("mySlides");
  //var items=slides[slideIndex-1].getElementsByClassName("radio_item");
  var items=document.getElementsByClassName("radio_item");
  //console.log(items);
  if(items != null) {
	for(i = 0; i < items.length; i++) {
	  var itemDisplay = items[i].style.display;
	  if(itemDisplay == "") {
	    items[i].style.display = "none";
	  } else {
	    items[i].style.display = "";
	  }
	}
  }
}

function download_file() {
  var fileName = document.getElementsByClassName("final_link")[0].textContent;
  if(fileName != "") {
    var pom = document.createElement('a');
    pom.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(downloadString));
    pom.setAttribute('download', fileName);
    document.body.appendChild(pom);
    pom.click();
    document.body.removeChild(pom);
  }
}

