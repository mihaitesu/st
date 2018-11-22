/*

//--- cookies -----------------------------------
function createCookie(name, value, days) {
	var expires = "";
	if(days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		expires = "; expires=" + date.toGMTString();
	}
	document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires;
}
function readCookie(name) {
	var nameEQ = encodeURIComponent(name) + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
	var c = ca[i];
		while (c.charAt(0) === ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
	}
	return null;
}
function deleteCookie(name) {
	createCookie(name, "", -1);
}
//-----------------------------------------------


//--- centrare verticala ------------------------
function set_vertical_center(element) {
	var elheight = $(element).outerHeight();
	var wiheight = $(window).height();
	var top = 0;
	if(wiheight > elheight) {
		//pozitionare la o treime din h
		top = (wiheight - elheight)/3;
	}
	$(element).css({
		position:'relative',
		top: top,
		marginTop: 20,
		marginBottom: 20
	});
}
function vertical_center(element) {
	set_vertical_center(element);
	$(window).on('load', function() {
		set_vertical_center(element);
	});
	$(window).on('resize', function() {
		set_vertical_center(element);
	});
}
//-----------------------------------------------


//--- center modals -----------------------------
function set_center_modals() {
	$('.modal').each(function(i){
		var $clone = $(this).clone().css('display', 'block').appendTo('body');
		var top = Math.round(($clone.height() - $clone.find('.modal-content').height()) / 2);
		top = (top > 0) ? top : 0;
		$clone.remove();
		$(this).find('.modal-content').css("margin-top", top);
	});
}
function center_modals() {
	$('.modal').on('show.bs.modal', set_center_modals);
	$(window).on('resize', set_center_modals);
}
//-----------------------------------------------


//--- tipuri mesaje -----------------------------
function set_alert_specific_class(alert_id, type) {
	var alert_class = '';
	switch(type) {
		case 1: alert_class = 'alert-danger';  break;
		case 2: alert_class = 'alert-warning'; break;
		case 3: alert_class = 'alert-success'; break;
		case 4: alert_class = 'alert-info';    break;
	}
	if(alert_class) {
		$(alert_id).removeClass("alert-danger alert-warning alert-success alert-info").addClass(alert_class);
	}
}
function set_form_group_specific_class(form_group_id, type) {
	$(form_group_id).removeClass("has-error has-warning has-success");
	var fg_class = '';
	switch(type) {
		case 1: fg_class = 'has-error';   break;
		case 2: fg_class = 'has-warning'; break;
		case 3: fg_class = 'has-success'; break;
	}
	if(fg_class) {
		$(form_group_id).addClass(fg_class);
	}
}
//-----------------------------------------------


//--- ajax post forms ---------------------------
function ajax_post_submit_form(form_id, success_callback, error_callback, warning_callback, info_callback) {
	success_callback = success_callback || 'undefined';
	error_callback   = error_callback   || 'undefined';
	warning_callback = warning_callback || 'undefined';
	info_callback    = info_callback    || 'undefined';
	
	$(document).on('submit', form_id, function(e) {
		e.preventDefault();
		
		var cform = this;
		$('button[type=submit]', cform).attr('disabled','disabled');
		$('button[type=submit]:not(:has(".fa"))', cform).prepend('<span class="fa fa-spinner fa-lg fa-pulse" style="margin-right:5px;"></span>');
		
		$.ajax({
			type:     "POST",
			url:      $(this).attr('action'),
			data:     $(this).serialize(),
			dataType: 'json',
			success:  function(data) {
				
				//--- alert
				if(data.alert.message) {
					set_alert_specific_class(form_id+"_alert", data.alert.type);
					if(data.alert.title) {
						$(form_id+"_alert "+'.alert-title').html(data.alert.title);
						$(form_id+"_alert "+'.alert-title').show();
					} else { $(form_id+"_alert "+'.alert-title').hide(); }
					$(form_id+"_alert " +'.alert-content').html(data.alert.message);
					$(form_id+"_alert").removeClass("hidden");
					$(form_id+"_alert").show();
				} else {
					$(form_id+"_alert").hide();
				}
				
				//--- fields
				$(form_id+' :input:not(:button,:submit)').each(function(index, elm) {
					var field_name = $(this).attr('name');
					set_form_group_specific_class(form_id+'_group_'+field_name, data[field_name]["type"]);
					$(form_id+'_group_'+field_name+' .help-block').html(data[field_name]["message"]);
				});
				
				//--- callbacks
				if((data.alert.type==1) && (error_callback!=='undefined')) {
					error_callback();
				}
				if((data.alert.type==2) && (warning_callback!=='undefined')) {
					warning_callback();
				}
				if((data.alert.type==3) && (success_callback!=='undefined')) {
					success_callback();
				}
				if((data.alert.type==4) && (info_callback!=='undefined')) {
					info_callback();
				}
				
				//---
				$('button[type=submit]', cform).removeAttr('disabled');
				$('button[type=submit] .fa', cform).remove();
			},
			error: function(data) {
				$('button[type=submit]', cform).removeAttr('disabled');
				$('button[type=submit] .fa', cform).remove();
				
				//window.location.reload();
			}
		});
	});
}
function fvoid() { return undefined; }
//-----------------------------------------------


//--- insereaza content pt tab activ ------------
function ajax_load_tab_content(tab_init) {
	$(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function(e) {
		//--- tab activ
		var id_tab   = $(e.target).attr('href');
		var url_ajax = $(e.target).attr('data-ajax');
		$(id_tab).html('<div class="text-center h3"><span class="fa fa-spinner fa-lg fa-pulse"></span></div>');
		$.ajax({
			type: "GET",
			url: url_ajax,
			//cache: false,
			dataType: 'html',
			async: false, //!!important
			success: function(data) {
				$(id_tab).html(data);
			},
			error: function(data) {
				window.location.reload();
			}
		});
	});
	//--- tab specificat
	var tab_init = tab_init || $('.nav-tabs a').first().attr('href');
	$('.nav-tabs a[href="' + tab_init + '"]').tab('show');
}
//-----------------------------------------------


//-----------------------------------------------
function loading_thumbnails() {
	$(".thumbnail img").on('load', function() {
		$(this).parent().children('.thumbnail-loading').hide();
	}).on('error', function() {
		//--- error 404, etc...
		$(this).parent().children('.thumbnail-loading').hide();
	}).each(function() {
		if(this.complete) {
			$(this).load();
		} else if(this.error) {
			$(this).error();
		}
	});
}
//-----------------------------------------------




//-----------------------------------------------
$(document).ready(function() {
	//--- alert close (hidden), tooltips, popovers
	$(function(){
		$("[data-hide]").on("click", function(){
			$(this).closest("." + $(this).attr("data-hide")).hide();
		});
	});
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();
	//--- ajax generated
	$(document).ajaxComplete(function() {
		$("[data-hide]").on("click", function(){
			$(this).closest("." + $(this).attr("data-hide")).hide();
		});
		//--- auto close
		var al = $(".alert-auto-close").find(".alert").first();
		if( $(al).is(":visible") ) {
			setTimeout(function() {
				$(al).fadeOut();
			}, 1700);
		}
		//---
		$('[data-toggle="tooltip"]').tooltip();
		$('[data-toggle="popover"]').popover();
	});
});
//-----------------------------------------------

*/



function captcha_refresh() {
	var timestamp = new Date().getTime();
	$("#captcha_img").click(function() {
		$(this).attr('src', $(this).attr('src')+'?'+timestamp);
	});
}


function ajax_form_submit(callback) { //--- callback[1] = "error callback", callback[3] = "success callback", etc
	
	$(document).on('submit', function(e) {
		e.preventDefault();
		
		var cform   = $(this);
		var form_id = $(cform.attr('id'));
		
		$('button[type=submit]', cform).attr('disabled','disabled');
		$('button[type=submit]:not(:has(".fa"))', cform).prepend('<span class="fa fa-spinner fa-lg fa-pulse" style="margin-right:5px;"></span>');
		
		$.ajax({
			type:     cform.attr('method'),
			url:      cform.attr('action'),
			data:     cform.serialize(),
			dataType: 'json',
			success:  function(data) {
				
				//--- alert
				if(data.alert.message) {
					set_alert_specific_class(form_id+"_alert", data.alert.type);
					if(data.alert.title) {
						$(form_id+"_alert "+'.alert-title').html(data.alert.title);
						$(form_id+"_alert "+'.alert-title').show();
					} else { $(form_id+"_alert "+'.alert-title').hide(); }
					$(form_id+"_alert " +'.alert-content').html(data.alert.message);
					$(form_id+"_alert").removeClass("hidden");
					$(form_id+"_alert").show();
				} else {
					$(form_id+"_alert").hide();
				}
				
				//--- fields
				$(form_id+' :input:not(:button,:submit)').each(function(index, elm) {
					var field_name = $(this).attr('name');
					set_form_group_specific_class(form_id+'_group_'+field_name, data[field_name]["type"]);
					$(form_id+'_group_'+field_name+' .help-block').html(data[field_name]["message"]);
				});
				
				//--- callbacks (data.alert.type -- 1,2,3 sau 4)
				if(callback[data.alert.type]!=='undefined') {
					callback[data.alert.type]();
				}
				
				
				//---
				$('button[type=submit]', cform).removeAttr('disabled');
				$('button[type=submit] .fa', cform).remove();
			},
			error: function(data) {
				$('button[type=submit]', cform).removeAttr('disabled');
				$('button[type=submit] .fa', cform).remove();
				
				//window.location.reload();
			}
		});
	});
}




$(document).ready(function() {
	//--- alert close (hidden), tooltips, popovers
	$("[data-hide]").on("click", function(){
		$(this).closest("." + $(this).attr("data-hide")).hide();
	});
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();
	
	captcha_refresh();
});

