function xGetElementById(e) {
    if(typeof(e)!='string') return e;
    if(document.getElementById) e=document.getElementById(e);
    else if(document.all) e=document.all[e];
    else e=null;
    return e;
}
function isVisible(e) {
    if (typeof e == "string") {
        e = xGetElementById(e);
    }
    while (e.nodeName.toLowerCase() != 'body' && e.style.display.toLowerCase() != 'none' && e.style.visibility.toLowerCase() != 'hidden') {
        e = e.parentNode;
    }
    if (e.nodeName.toLowerCase() == 'body') {
        return true;
    } else{
        return false;
    }
}

(function ($) {
	$(window).load(function() {
		$('img.not-loaded-image').removeClass('not-loaded-image');
	});

	$(document).ready(function() {
		$('img').not($('#logo img')).each(function() {
			if(this.complete) return true;

			$(this).addClass('not-loaded-image');

			$(this).load(function() {
				var ths = $(this);
				setTimeout(function() {
					ths.removeClass('not-loaded-image');
				}, 100);
			});
		});

		$(document).on('click', '#commentform button[name="submit"]', function(e) {
            e.preventDefault();

            var f = $('#commentform');
            var formType = f.data('type');
            var fields = {};
            var $this = $(this);

            var invalidField = '';

            $this.blur();

            f.find('input[required], select[required], textarea[required]').each(function( index ) {
                if($.trim($(this).val()).length === 0 && ['radio', 'checkbox'].indexOf($(this).attr('type')) === -1) {
                    invalidField = $(this);
                }
                if(['radio', 'checkbox'].indexOf($(this).attr('type')) !== -1) {
                    if(!f.find('input[name="' + $(this).attr('name') + '"]').is(':checked')) {
                        invalidField = $(this);
                    }
                }
            });

            if(invalidField !== '') {
                invalidField.focus();

                return false;
            }

            var a = f.serializeArray();
            $.each(a, function() {
                if (fields[this.name] !== undefined) {
                    if (!fields[this.name].push) {
                        fields[this.name] = [fields[this.name]];
                    }
                    fields[this.name].push(this.value || '');
                } else {
                    fields[this.name] = this.value || '';
                }
            });

            $.ajax({
                cache: false,
                url: "/ajax",
                method: "POST",
                //contentType: 'application/json',
                //dataType: "json",
                data: {
                    handler: 'Page',
                    type: formType,
                    '_token': f.find('input[name="_token"]').val(),
                    formFields: fields
                },
                success: function(data) {
                    console.log(data);
                    f.find('textarea').val('');
                }
            });
        });

		//menu hover effects
		var t=null;

		$("#nav li").each(function(){
			if($(this).find('ul').length>0){
				$(this).addClass('HasChild');
			}
		});
		$("#nav li ul").hide();
		$("#nav li").mouseenter(function(){
			clearTimeout(t);
			var el = this;
			t=setTimeout(function() {
				$(el).addClass('sfhover').find(">ul").fadeIn(500);
			}, 200);
		}).mouseleave(function(){
			clearTimeout(t);
			var el = this;
			$(el).removeClass('sfhover').find("ul").fadeOut(100);
		});

		//header search effects
		if($('#header .searchform .searchbox').val().length && $('#header .searchform .searchbox').val()!=='Keresés') {
			$('#header .searchform .reset').fadeTo(250, 0.8);
		}
		$('#header .searchform .searchbox').bind('focus', function() {
			$('#header .searchform .submitbutton').attr('src', '/templates/2014newbrand/images/search.png');
			$('#header .searchform').animate({'background-color': 'rgba(255, 255, 255, 1)'}, 500).find('.reset').fadeIn(250).parent().find('.searchbox').css('color', '#000000');
		}).bind('blur', function() {
			if(!$('#header .searchform .searchbox').val().length) {
				$('#header .searchform').animate({'background-color': 'rgba(255, 255, 255, 0.1)'}, 500).find('.submitbutton').attr('src', '/templates/2014newbrand/images/search_inactive.png').parent().find('.searchbox').css('color', '#cccccc').parent().find('.reset').fadeOut(250);
			} else {
				$('#header .searchform').animate({'background-color': 'rgba(255, 255, 255, 0.1)'}, 500).find('.submitbutton').attr('src', '/templates/2014newbrand/images/search_inactive.png').parent().find('.searchbox').css('color', '#cccccc');
			}
		});

		$('#header .searchform .reset').click(function() {
			$('#header .searchform .searchbox').val('Keresés').parent().find('.reset').fadeOut(250);
		});
		$(".sortableTable").tablesorter();

		var isMobileCSS = '';

		$('.switch-mobile').click(function(e) {
			e.preventDefault();
			e.stopPropagation();

			// location.reload();
		});

		if(isMobileCSS!=="" && isMobileCSS!=="yes") {
			$('.switch-mobile').html('<i class="fa fa-mobile"></i> Mobil n&eacute;zet');
		}
		else {
			$('.switch-mobile').html('<i class="fa fa-desktop"></i> Asztali n&eacute;zet');
		}

		$(document).on('click', '#nav_mobile_toggle', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var debug = false;

			if(debug) console.log('1');
			$('#contentwrapper, #contentwrapper2, .featuredArticle, #sidebars').css({'position': 'absolute'});
			if($('#contentwrapper').is(':visible') || $('#contentwrapper2').is(':visible')) {
				$('#contentwrapper, #contentwrapper2, .featuredArticle, .postImage').css({'display': 'none', 'opacity': '0'});
				$('#sidebars').css({'display': 'block', 'opacity': '1'});
				if(debug) console.log('2.a');
			}
			else {
				$('#contentwrapper, #contentwrapper2, .featuredArticle, .postImage').css({'display': 'block', 'opacity': '1'});
				$('#sidebars').css({'display': 'none', 'opacity': '0'});
				if(debug) console.log('2.b');
			}

			if(debug) console.log('3');
			$('#contentwrapper, #contentwrapper2, .featuredArticle, #sidebars').css({'position': 'relative'});
			if(debug) console.log('4');
		});

		$(document).on('mouseover mouseenter click touchstart mousedown', '.socialButtons .buttonHolder', function(e) {
			var ths = $(this);
			$('.socialButtons .buttonHolder').removeClass('active');
			ths.addClass('active');
		});

		$(document).on('mouseleave mouseout', '.socialButtons', function(e) {
			var ths = $(this);
			ths.find('.buttonHolder').removeClass('active');
		});
	});
})(jQuery);
