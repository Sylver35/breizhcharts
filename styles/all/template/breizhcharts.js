/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2022 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

/** global: bcConfig */
(function($){  // Avoid conflicts with other libraries
	'use strict';
	$('#nav-'+bcConfig.nav).addClass('navigation_actual');
	$('div.pagination a').each(function(){
		$(this).prop('href', $(this).attr('href')+'#start');
	});
	$('a.username-coloured').each(function(){
		$(this).prop('href', $(this).attr('href')+'#start');
	});
	breizhcharts.voteMusic = function(id, rate){
		var $content = $('#bzh_result-'+id).html();
		$('#bzh_result-'+id).html('<div style="text-align:center;">'+bcConfig.loader+'</div>');
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: bcConfig.voteUrl,
			data: 'id='+id+'&rate='+rate,
			cache: false,
			success: function(result){
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
				setTimeout(breizhcharts.clearMessage,4000);
			},
			error: function(){
				$('#bzh_result-'+id).html($content);
			}
		});
	};
	breizhcharts.clearMessage = function(){
		$('#result-div').hide();
	};
	breizhcharts.selectWins = function(winner){
		$(location).attr('href', bcConfig.winUrl+'&winner='+winner+'#start');
	};
	breizhcharts.checkSongArtist = function(){
		var songName = $('#song_name').val(), artist = $('#artist').val();
		if(songName === '' || artist === ''){
			return;
		}
		$('#check-song').html(bcConfig.loader).show();
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: bcConfig.checkSong,
			data: 'id='+bcConfig.id+'&song='+encodeURIComponent(songName)+'&artist='+encodeURIComponent(artist),
			cache: false,
			success: function(result){
				if(result.sort === 1){
					$('#check-song').html(bcConfig.ajaxTrue+' <span style="color:green;">'+result.message+'</span>');
					$('#button').attr({'disabled': false, 'title': bcConfig.submit});
				}else{
					$('#check-song').html(bcConfig.ajaxFalse+' <span style="color:red;">'+result.message+'</span>');
					$('#button').attr({'disabled': true, 'title': bcConfig.error});
				}
				$('#check-song > span > strong').css('color','initial');
			},
			error: function(){
				$('#check').hide();
			}
		});
	}
	breizhcharts.checkVideo = function(){
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: bcConfig.checkUrl,
			data: 'url='+encodeURIComponent($('#video').val()),
			cache: false,
			success: function(result){
				if(result.sort === 1){
					$('#check-video').html(bcConfig.ajaxTrue+' <span style="color:green;">'+result.message+'</span> <img src="'+result.content+'" height="35">');
				}else{
					$('#check-video').html(bcConfig.ajaxFalse+' <span style="color:red;">'+result.message+'</span>');
					$('#button').attr({'disabled': true, 'title': bcConfig.error});
				}
				$('#check-song > span > strong').css('color','initial');
			}
		});
	}
	breizhcharts.urlValide = function(url){
		var regex = /^(https?:\/\/(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})/;
		 if(regex.test(url)){ 
			return true;
		}
		return false;
	}
})(jQuery);