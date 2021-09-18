var mapSelectPoint = []
var Theatreseat = function(form, xcnt, ycnt, limit, zoomautoscl, asizeajust, actual_img_widthpx, _resizemb, disp_img_widthpx){
	this.mst_default_zoompx = disp_img_widthpx ? disp_img_widthpx : Math.round(actual_img_widthpx * 0.44);
	this.mst_zoompx = this.mst_default_zoompx;
	this.mst_min_zoompx = 1;
	this.mst_max_zoompx = actual_img_widthpx;
	
	this.formobj = form;
	this.seat_xcnt = xcnt;
	this.seat_ycnt = ycnt;
	this.selectlimit = limit;
	this.selectcount = 0;
	this.button_zoomin_enable = true;
	this.button_zoomout_enable = false;
	this.button_zoomreset_enable = false;
	
	this.default_zoompx = this.mst_default_zoompx;
	this.zoompx = this.mst_zoompx;
	this.min_zoompx = this.mst_min_zoompx;
	this.max_zoompx = this.mst_max_zoompx;
	
	this.navitop = 0;
	this.navibuttom = 0;
	this.windowx = $(window).width();
	this.windowy = $(window).height();
	
	this.zoomautoscroll = zoomautoscl;
	this.areasizeajust = asizeajust;
	
	this.resizeevent = 'orientationchange resize';
	this.tmfunc0 = false;
	this.tmorientation = ($(window).height() > $(window).width());
	this.resizemb = _resizemb;
	
	this.tmfunc1 = false;
	
	this.doZoom = function(zm) {
		if(zm){
			this.zoompx += zm;
			this.zoompx = Math.max(this.zoompx, this.min_zoompx);
			this.zoompx = Math.min(this.zoompx, this.max_zoompx);
			this.zoomProc(this.zoompx);
		}else{
			this.zoompx = this.default_zoompx;
			this.zoomProc(this.default_zoompx);
			$('html,body').animate({scrollTop : $('#seatarea').offset().top}, 300);
		}
		if($("#navi_area").length){
			this.navibuttom = $("#seat_detail").offset().top;
		}
	};
	
	this.zoomProc = function(px) {
		$(window).off(this.resizeevent);
		$(".cliseat").css("width",px+"px");
		$(".nonseat").css("width",px+"px");
		$(".cliseat").css("height",px+"px");
		$(".nonseat").css("height",px+"px");

		if (Math.round(px) < Math.round(this.max_zoompx)) {
			this.button_zoomin_enable = true;
			$("#zoombtn_in").prop("disabled", false);
		} else {
			this.button_zoomin_enable = false;
			$("#zoombtn_in").prop("disabled", true);
		}
		if (Math.round(px) > Math.round(this.default_zoompx)) {
			this.button_zoomout_enable = true;
			this.button_zoomreset_enable = true;
			$("#zoombtn_out").prop("disabled", false);
			$("#zoombtn_reset").prop("disabled", false);
		} else {
			this.button_zoomout_enable = false;
			this.button_zoomreset_enable = false;
			$("#zoombtn_out").prop("disabled", true);
			$("#zoombtn_reset").prop("disabled", true);
		}
		
		if(this.button_zoomin_enable && this.button_zoomout_enable){
			$("#zoomtxt").text("x2").css("display","block");
		}else if(this.button_zoomin_enable){
			$("#zoomtxt").text("").css("display","none"); //x1
		}else if(this.button_zoomout_enable){
			$("#zoomtxt").text("x3").css("display","block");
		}else{
			$("#zoomtxt").text("").css("display","none");
		}
		
		if(this.resizeevent){
			var __this__ = this;
			$(window).on(this.resizeevent, {self: __this__}, function(e) {
				if(e.data.self.resizemb) if(($(window).height() > $(window).width()) === e.data.self.tmorientation) return;
				if(e.data.self.tmfunc0 !== false) clearTimeout(e.data.self.tmfunc0);
				e.data.self.tmfunc0 = setTimeout(function(){
					var barwidth = scrollbarWidth()+2;
					if(Math.abs(e.data.self.windowx-$(window).width())>barwidth || Math.abs(e.data.self.windowy-$(window).height())>barwidth){
						e.data.self.windowx = $(window).width();
						e.data.self.windowy = $(window).height();
						e.data.self.zoomAdjust();
						e.data.self.tmorientation = ($(window).height() > $(window).width());
					}
					e.data.self.tmfunc0 = false;
				}, 100);
			});
			
		}
	};
	
	this.zoomScrollact = function(nt, nb) {
		var box = $("#seat_scroll");
		var scrltop = $(window).scrollTop();
		if (scrltop >= nt - 20 && scrltop <= nb - 130) {
			box.addClass("fixed");
			box.css('top', '');
		} else {
			box.css('top', (nt - scrltop) + 'px');
			box.removeClass("fixed");
		}
	};

	this.timer = false;
	this.zoomInit = function(){
		$("#zoombtn_in").off('click');
		$("#zoombtn_out").off('click');
		$("#zoombtn_reset").off('click');
		$("#zoombtn_in").on('click', {self: this}, function(e) {
			if (e.data.self.button_zoomin_enable){
				e.data.self.doZoom((e.data.self.max_zoompx - e.data.self.min_zoompx) / 2);
				if(e.data.self.zoomautoscroll) $('html,body').animate({scrollTop : $(this).offset().top-$('.header_logo').height() - 10}, 300);
			}
		});
		$("#zoombtn_out").on('click', {self: this}, function(e) {
			if (e.data.self.button_zoomout_enable){
				e.data.self.doZoom(0 - (e.data.self.max_zoompx - e.data.self.min_zoompx) / 2);
				if(e.data.self.zoomautoscroll) $('html,body').animate({scrollTop : $(this).offset().top-$('.header_logo').height() - 10}, 300);
			}
		});
		$("#zoombtn_reset").on('click', {self: this}, function(e) {
			if (e.data.self.button_zoomreset_enable) e.data.self.doZoom();
			if(e.data.self.zoomautoscroll) $('html,body').animate({scrollTop : $(this).offset().top-$('.header_logo').height() - 10}, 300);
		});

		if($("#navi_area").length){
			$("#menu,#floorbtn").on('click', {self: this}, function(e) {
				$("#seat_scroll").css('position', 'static');
				if (e.data.self.timer !== false) clearTimeout(e.data.self.timer);
				e.data.self.timer = setTimeout(function() {
					e.data.self.navitop = $("#navi_area").offset().top;
					e.data.self.navibuttom = $("#seat_detail").offset().top;
					e.data.self.zoomScrollact(e.data.self.navitop, e.data.self.navibuttom);
					if ($("#seat_scroll").css('position') == 'static') {
						$("#seat_scroll").css('position', 'fixed');
					}
				}, 400);
			});
			$(window).on('scroll', {self: this}, function(e) {
				if(e.data.self.tmfunc1 !== false) clearTimeout(e.data.self.tmfunc1);
				e.data.self.tmfunc1 = setTimeout(function(){
					e.data.self.navitop = $("#navi_area").offset().top;
					e.data.self.navibuttom = $("#seat_detail").offset().top;
					if ($("#seat_scroll").css('position') == 'static') {
						$("#seat_scroll").css('position', 'fixed');
					}
					e.data.self.zoomScrollact(e.data.self.navitop, e.data.self.navibuttom);
					e.data.self.tmfunc1 = false;
				}, 0);
			});
		}
		
		this.zoomAdjust();
		
		if(this.resizeevent){
			$(window).on(this.resizeevent, {self: this}, function(e) {
				if(e.data.self.resizemb) if(($(window).height() > $(window).width()) === e.data.self.tmorientation) return;
				if(e.data.self.tmfunc0 !== false) clearTimeout(e.data.self.tmfunc0);
				e.data.self.tmfunc0 = setTimeout(function(){
					var barwidth = scrollbarWidth()+2;
					if(Math.abs(e.data.self.windowx-$(window).width())>barwidth || Math.abs(e.data.self.windowy-$(window).height())>barwidth){
						e.data.self.windowx = $(window).width();
						e.data.self.windowy = $(window).height();
						e.data.self.zoomAdjust();
						e.data.self.tmorientation = ($(window).height() > $(window).width());
					}
					e.data.self.tmfunc0 = false;
				}, 100);
			});
		}
		
		$('.cliseat').on('click', {self: this}, function(e){
			if(mapSelectPoint.length ==  0){
				e.data.self.selectcount = 0
			}

			if($("#seatarea").data('moved') != true){
				let prevsrc = $(this).attr('data-status')

				if(prevsrc == 'unSelect'){
					let nowId = $(".cliseat").index(this)
					
					if(e.data.self.selectcount+1 > e.data.self.selectlimit){
						let firstPoint = mapSelectPoint[0].nowId
						$(".cliseat").eq(firstPoint).attr('data-status', 'unSelect')
						$(".cliseat").eq(firstPoint).removeClass( "active" )
						mapSelectPoint.splice(0, 1)
					}
					$(this).attr('data-status', 'Select')
					$(this).addClass( "active" );
					mapSelectPoint.push({nowId})
					
					if(e.data.self.selectcount < 2){
						e.data.self.selectcount++;
					}
					seatSetting.settingBtnSel = false
				}else if(prevsrc == 'Select'){
					let nowId = $(".cliseat").index(this)
					let num = ''
					let max = mapSelectPoint.length

					if(e.data.self.selectcount-1 < 0) return
						
					for(let n=0; n<max; n++){
						if(mapSelectPoint[n].nowId == nowId){
							num = n
						}
					}
					mapSelectPoint.splice(num, 1)
					
					$(this).attr('data-status', 'unSelect')
					$(this).removeClass( "active" );

					if(mapSelectPoint.length == 0){
						seatSetting.settingBtnSel = true
					}

					e.data.self.selectcount--

				}
				if(e.data.self.selectcount<=0){
					$("#procsend").removeClass("is-type1");
					$("#procsendMobile").addClass("deactive");
				}else{
					$("#procsend").addClass("is-type1");
					$("#procsendMobile").removeClass("deactive");
				}					
//				$("#procsend").prop("disabled", e.data.self.selectcount<=0);
				
			}
		});
		
		var _isSubmitted = false;
		$(this.formobj).on('submit', {f: this.formobj}, function(e){
			$('._request_seat_').remove();
			var index = 0;
			$('img.cliseat').each(function(){
				var src = $(this).attr('src');
				if(src.match(/seat\-org[1-4]{1}\.gif/)){
					var hidden = $('<input />').attr('type','hidden').attr('name','oseat_'+(index++)).attr('value',$(this).attr('name')).addClass('_request_seat_');
					hidden.appendTo(e.data.f);
				}
			});
			if(index<=0){return false;}
			if (!_isSubmitted) {
				_isSubmitted = true;
				return true;
			} else {
				e.preventDefault();
				return false;
			}
		});
	};
	
	this.zoomAdjust = function() {
		this.button_zoomin_enable = true;
		this.button_zoomout_enable = false;
		this.button_zoomreset_enable = false;
		
		this.default_zoompx = this.mst_default_zoompx;
		this.zoompx = this.mst_zoompx;
		this.min_zoompx = this.mst_min_zoompx;
		this.max_zoompx = this.mst_max_zoompx;
		
		var areawidth = $("#reserve_block_caution2").width()+"px";
		$(".seatSelectImageArea").css("max-width",areawidth);
		$(".seatSelectImageArea").css("width",areawidth);
				
		var areaheight;
		if(this.areasizeajust){
			var ajustcd = parseFloat(this.areasizeajust);
			if(isNaN(ajustcd) || ajustcd < 0.1 || ajustcd > 1) ajustcd = 0.75;
			areaheight = $(window).height()*ajustcd;
			$(".seatSelectImageArea").css("max-height", areaheight+"px");
		}else{
			areaheight = parseInt($(".seatSelectImageArea").css("max-height"), 10);
			if(isNaN(areaheight) || areaheight < 0.1 || areaheight > 1000) areaheight = 550;
		}
		
		var seat_dispw = $("#seatarea").width()-(100+scrollbarWidth());
		var seat_disph = areaheight-(100+scrollbarWidth());
		
		this.zoompx=Math.min(Math.min(Math.floor(seat_dispw/this.seat_xcnt),Math.floor(seat_disph/this.seat_ycnt)),this.zoompx);
		this.zoompx = Math.max(this.zoompx, this.min_zoompx);
		this.zoompx = Math.min(this.zoompx, this.max_zoompx);
		this.min_zoompx = this.default_zoompx = this.zoompx;
		this.zoomProc(this.zoompx);

		if($("#navi_area").length){
			this.navitop = $("#navi_area").offset().top;
			this.navibuttom = $("#seat_detail").offset().top;
			this.zoomScrollact(this.navitop, this.navibuttom);
			$("#seat_scroll").width($("#navi_area").width());
		}
		
	};
	
	
	this.zoomInit();
	
};

var _scrollbarsize = false;
function scrollbarWidth(){
	if(_scrollbarsize === false){
		var body = document.body;
		var outer = document.createElement("div");
		var style = outer.style;
		style.width = "100px";
		style.height = "100px";
		style.overflow = "scroll";
		style.border = "none";
		style.visibility = "hidden";
		var inner = outer.cloneNode(false);
		outer.appendChild(inner);
		document.body.appendChild(outer);
		outer.scrollTop = 200;
		var barWidth = outer.scrollTop;
		body.removeChild(outer);
		_scrollbarsize = barWidth;
	}
	return _scrollbarsize;
}
