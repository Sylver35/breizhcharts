/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 country_list Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

/** global: bcConfig */
(function($){  // Avoid conflicts with other libraries
	'use strict';

	$('#nav-'+bcConfig.nav).addClass('navigation_actual').find('i').toggleClass('icon-green icon-red');
	$('div.pagination a').each(function(){
		$(this).prop('href', $(this).attr('href')+'#nav');
	});
	$('a.username-coloured').each(function(){
		$(this).prop('href', $(this).attr('href')+'#nav');
	});
/*
	breizhcharts.voteMusic = async function(id, rate, sort){
		var $content = $('#bzh_result-'+id).html(),$ids = $('#list_ids').val();
		$('#bzh_result-'+id).html('<div style="text-align:center;">'+bcConfig.loader+'</div>');
		if(sort == false){
			$('#result-div').show().html('<span class="error-div">'+bcConfig.errorVote+'</span>');
			setTimeout(breizhcharts.clearMessage,4000);
			return;
		}
		try{
			const response = await $.ajax({
				url: bcConfig.voteUrl,
				method: 'POST',
				dataType: 'json',
				data: {'id='+id+'&rate='+rate+'&ids='+$ids}
			});
			handleSuccess(response);
		}catch(error){
			handleError(error);
		}
	};

	function handleSuccess(result) {
		if(result.sort === 1){
			var aTitle = result.userVote.replace('<span>','').replace('</span>','');
			$('#rating-'+id).css('width',result.newResult).removeClass('current-not-rating').addClass('current-rating') ;
			$('#stars-'+id+' a').each(function(){
				$(this).attr({'onclick': '', 'title': aTitle}).prop('onclick', null);
			});
			$('#bzh_result-'+id).html(result.totalRate+'<br>'+result.songRated+'<br><div class="rated"></div>'+result.userVote);
			$('#result-div').show().html('<span class="succes-div">'+result.message+'</span>');
		}else{
			$('#bzh_result-'+id).html($content);
			$('#result-div').show().html('<span class="error-div">'+result.message+'</span>');
		}
		setTimeout(breizhcharts.clearMessage,3500);
	}

	function handleError(error) {
		$('#bzh_result-'+id).html($content);
	}
*/
	breizhcharts.voteMusic = function(id, rate, sort){
		var $content = $('#bzh_result-'+id).html(),$ids = $('#list_ids').val();
		$('#bzh_result-'+id).html('<div style="text-align:center;">'+bcConfig.loader+'</div>');
		if(sort == false){
			$('#result-div').show().css('display', 'flex').html('<span class="error-div">'+bcConfig.errorVote+'</span>');
			setTimeout(breizhcharts.clearVote(id, $content),4000);
			setTimeout(breizhcharts.clearMessage,4000);
			return;
		}
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: bcConfig.voteUrl,
			data: 'id='+id+'&rate='+rate+'&ids='+$ids,
			cache: false,
			success: function(result){
				if(result.sort === 1){
					var aTitle = result.userVote.replace('<span>','').replace('</span>','');
					$('#rating-'+id).css('width',result.newResult).removeClass('current-not-rating').addClass('current-rating') ;
					$('#stars-'+id+' a').each(function(){
						$(this).attr({'onclick': '', 'title': aTitle}).prop('onclick', null);
					});
					$('#bzh_result-'+id).html(result.totalRate+'<br>'+result.songRated+'<br><div class="rated"></div>'+result.userVote);
					$('#result-div').show().css('display', 'flex').html('<span class="succes-div">'+result.message+'</span>');
				}else{
					$('#bzh_result-'+id).html($content);
					$('#result-div').show().css('display', 'flex').html('<span class="error-div">'+result.message+'</span>');
				}
				setTimeout(breizhcharts.clearMessage,3500);
			},
			error: function(){
				$('#bzh_result-'+id).html($content);
			}
		});
	};

	breizhcharts.clearVote = function(id, $content){
		$('#bzh_result-'+id).html($content);
	}

	breizhcharts.clearMessage = function(){
		$('#result-div').hide();
	};

	breizhcharts.checkSongArtist = function(){
		var $songName = $('#song_name').val(), $artist = $('#artist').val();
		if($songName === '' || $artist === ''){
			return;
		}
		$('#check-song').html(bcConfig.loader).show();
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: bcConfig.checkSong,
			data: 'id='+bcConfig.id+'&song='+encodeURIComponent($songName)+'&artist='+encodeURIComponent($artist),
			cache: false,
			success: function(result){
				if(result.sort === 1){
					$('#check-song').removeClass('is-invalid').addClass('is-valid').html(result.message);
					$('#button').attr({'disabled': false, 'title': bcConfig.submit});
				}else{
					$('#check-song').removeClass('is-valid').addClass('is-invalid').html(result.message);
					$('#button').attr({'disabled': true, 'title': bcConfig.error});
				}
			},
			error: function(){
				$('#check').hide();
			}
		});
	}

	breizhcharts.checkVideo = function(){
		var $url = $('#video').val();
		if($url === ''){
			return;
		}
		if(breizhcharts.urlValide($url) == false){
			$('#check-video').addClass('is-invalid').html(bcConfig.errorUrl);
			return;
		}
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: bcConfig.checkUrl,
			data: 'url='+encodeURIComponent($url),
			cache: false,
			success: function(result){
				if(result.sort === 3){
					$('#check-video').removeClass('is-valid').addClass('is-invalid').html(bcConfig.presentError+bcConfig.colon+' <span class="no-color">'+result.name+'<br>'+result.time+'<br>'+bcConfig.videoAjax+'</span><br><a href="'+result.url+'"><img src="'+result.image+'" alt="" width="80" height="60"></a>');
					$('#button').attr({'disabled': true, 'title': bcConfig.error});
				}else if(result.sort === 1){
					$('#check-video').removeClass('is-invalid').addClass('is-valid').html(result.message+'<br><img src="'+result.content+'" width="80" height="60">');
				}else{
					$('#check-video').addClass('is-invalid').html(result.message);
					$('#button').attr({'disabled': true, 'title': bcConfig.error});
				}
			}
		});
	}

	breizhcharts.reportError = function(event){
		if($('#reported').val() > 0 || event.data == 0){
			return;
		}
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: bcConfig.reportAutoUrl,
			data: 'error='+event.data,
			cache: false,
			success: function(result){
				$('#result-div').show().css('display', 'flex').html('<div style="text-align:center;">'+bcConfig.error+bcConfig.colon+' '+result.error+'<br>'+result.content+'</div>');
				$('#can-report').hide();
				$('#view-report').show();
				setTimeout(breizhcharts.clearMessage,15000);
			},
			error: function(result,statut,erreur){
				var obj = result.responseText;
				$('#result-div').show().css('display', 'flex').html(JSON.parse(JSON.stringify(obj)));
				setTimeout(breizhcharts.clearMessage,15000);
			}
		});
	}

	breizhcharts.viewSong = function(){
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: bcConfig.viewSongUrl,
			cache: false,
			success: function(result){
				$('#span_view').html(result.song_view);
				$('#a_view').attr({'title': result.title});
			},
			error: function(result,statut,erreur){
				var obj = result.responseText;
				console.log(JSON.parse(JSON.stringify(obj)));
			}
		});
	}

	breizhcharts.validate = function(){
		if($('#reason_id').val() == 'OTHER' || $('#reason_id').val() == 'DOUBLE'){
			$('#report_text').attr('required',true);
			$('#submit1').attr({'disabled': false, 'title': bcConfig.submit});
			if($('#report_text').val() == ''){
				$('#result-span').html('<span style="color:red;">'+bcConfig.errorSelectNo+'</span>').show();
			}
		}else{
			$('#report_text').attr('required',false);
			$('#submit1').attr({'disabled': false, 'title': bcConfig.submit});
			$('#result-span').html('').hide();
		}
	}

	breizhcharts.validateForm = function(form){
		if($('#reason_id').val() == 'NOT'){
			$('#result-span').html('<span style="color:red;">'+bcConfig.errorSelect+'</span>').show();
		}else if($("#reason_id").val() == 'OTHER' || $("#reason_id").val() == 'DOUBLE'){
			$('#result-span').html('<span style="color:red;">'+bcConfig.errorSelectNo+'</span>').show();
		}else{
			$('#result-span').html('').hide();
		}
	}

	breizhcharts.validaTextarea = function(){
		if(($('#report_text').val() == '') && ($('#reason_id').val() == 'OTHER' || $('#reason_id').val() == 'DOUBLE')){
			$('#result-span').html('<span style="color:red;">'+bcConfig.errorSelectNo+'</span>').show();
		}else{
			$('#result-span').html('').hide();
		}
	}

	breizhcharts.reportEdit = function(){
		if($('#postingbox').is(':visible')){
			$('#postingbox').hide();
			$('#i-icon').attr('class', 'bzh-blue icon fa fa-pencil-square-o');
			$('#span-icon').html(bcConfig.editOpen);
			$('#a-icon').attr('title', bcConfig.editOpen);
		}else{
			$('#postingbox').show();
			$('#i-icon').attr('class', 'bzh-blue icon fa fa-chevron-up');
			$('#span-icon').html(bcConfig.editClose);
			$('#a-icon').attr('title', bcConfig.editClose);
		}
	}

	breizhcharts.reportClose = function(){
		if($('#no-reason').is(':visible')){
			$('#no-reason').hide();
			$('#i-close').attr('class', 'bzh-blue icon fa fa-bell-slash');
			$('#span-close').html(bcConfig.closeOpen);
			$('#a-close').attr('title', bcConfig.closeOpen);
		}else{
			$('#no-reason').show();
			$('#i-close').attr('class', 'bzh-blue icon fa fa-chevron-up');
			$('#span-close').html(bcConfig.editClose);
			$('#a-close').attr('title', bcConfig.editClose);
		}
	}

	breizhcharts.messageClose = function(){
		if($('#messagebox').is(':visible')){
			$('#messagebox').hide();
			$('#i-message').attr('class', 'bzh-blue icon fa fa-comments');
			$('#span-message').html(bcConfig.msgOpen);
			$('#a-message').attr('title', bcConfig.msgOpen);
		}else{
			$('#messagebox').show();
			$('#i-message').attr('class', 'bzh-blue icon fa fa-chevron-up');
			$('#span-message').html(bcConfig.editClose);
			$('#a-message').attr('title', bcConfig.editClose);
		}
	}

	breizhcharts.urlValide = function(url){
		var regex = /^(https?:\/\/(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})/;
		 if(regex.test(url)){
			return true;
		}
		return false;
	}
})(jQuery);
