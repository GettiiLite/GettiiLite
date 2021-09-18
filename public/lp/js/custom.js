/*------------------------------------------------------------------
Project:        Modus - HTML onepage theme by GraphBerry.com
Version:        1.0
Last change:    12/06/2017
Author:         GraphBerry
URL:            http://graphberry.com
License:        http://graphberry.com/pages/license
-------------------------------------------------------------------*/
$(function(){
	'use strict';



var slideConts = document.querySelectorAll('.slideConts'); // スライドで表示させる要素の取得
var slideContsRect = []; // 要素の位置を入れるための配列
var slideContsTop = []; // 要素の位置を入れるための配列
var windowY = window.pageYOffset; // ウィンドウのスクロール位置を取得
var windowH = window.innerHeight; // ウィンドウの高さを取得
var remainder = 100; // ちょっとはみ出させる部分
// 要素の位置を取得
for (var i = 0; i < slideConts.length; i++) {
  slideContsRect.push(slideConts[i].getBoundingClientRect());
}
for (var i = 0; i < slideContsRect.length; i++) {
  slideContsTop.push(slideContsRect[i].top + windowY);
}
// ウィンドウがリサイズされたら、ウィンドウの高さを再取得
window.addEventListener('resize', function () {
  windowH = window.innerHeight;
});
// スクロールされたら
window.addEventListener('scroll', function () {
  // スクロール位置を取得
  windowY = window.pageYOffset;
  
  for (var i = 0; i < slideConts.length; i++) {
    // 要素が画面の下端にかかったら
    if(windowY > slideContsTop[i] - windowH + remainder) {
      // .showを付与
      slideConts[i].classList.add('show');
    } else {
      // 逆に.showを削除
      slideConts[i].classList.remove('show');
    }
  }
});




	/*--------------------------------------------------
    Scrollspy Bootstrap 
    ---------------------------------------------------*/

    $('body').scrollspy({
    	target: '#header',
    	offset: 100
    });


	/*--------------------------------------------------
    Smooth Scroll 
    ---------------------------------------------------*/

	smoothScroll.init({
		selector: '.smoothScroll',
		speed: 1000,
		offset: 100,
		before: function(anchor, toggle){
			// Check if mobile navigation is active
			var query = Modernizr.mq('(max-width: 767px)');
			// Check if element is navigation eelement
			var navItem = $(toggle).parents("#main-navbar").length;
			// If mobile nav & nav item then close nav
			if (query && navItem !== 0) {
				$("button.navbar-toggle").click();
			}
		}
	});


	/*--------------------------------------------------
    Mixitup
    ---------------------------------------------------*/

    var mixer = mixitup('#portfolio-grid', {

		selectors: {
			control: '[data-mixitup-control]'
		}
		
	});


	/*--------------------------------------------------
    Slick Slider
    ---------------------------------------------------*/

	$('.slider-container').slick({
		arrows: false,
		autoplay: true,
		centerMode: true,
		centerPadding: '100px',
		variableWidth: true,
		responsive: [
			{
				breakpoint: 768,
				settings: {
					centerMode: false,
					variableWidth: false
				}
			}
		]
	});


	/*--------------------------------------------------
    Porfolio cursor
    ---------------------------------------------------*/

	$('.team-member').on('mousedown', function() {
	    $(this).removeClass('grabbable');
	    $(this).addClass('grabbing');
	});

	$('.team-member').on('mouseleave mouseup', function() {
	    $(this).removeClass('grabbing');
	    $(this).addClass('grabbable');
	});

	/*--------------------------------------------------
    Current Year
    ---------------------------------------------------*/

    // Automatically update copyright year in the footer
	var currentTime = new Date();
	var year = currentTime.getFullYear();
	$("#year").text(year);


});
