(function($) {
	"use strict";
	
	$(document).ready(function() {
	
		/*-----------------------------------------------------------------------------------*/
		/*  Social Counter JS
		/*-----------------------------------------------------------------------------------*/ 		
		var $URL = crestaPermalink.thePermalink;
		totalShares($URL);
		
			function ReplaceNumberWithCommas(shareNumber) {
				 if (shareNumber >= 1000000000) {
					return (shareNumber / 1000000000).toFixed(1).replace(/\.0$/, '') + 'G';
				 }
				 if (shareNumber >= 1000000) {
					return (shareNumber / 1000000).toFixed(1).replace(/\.0$/, '') + 'M';
				 }
				 if (shareNumber >= 1000) {
					return (shareNumber / 1000).toFixed(1).replace(/\.0$/, '') + 'K';
				 }
				 return shareNumber;
			}

		
			// SHARE COUNTS FUNCTIONS 
			if ( $('#googleplus-cresta').hasClass('googleplus-cresta-share') ) {

				// Google Plus Shares Count
				var googleplusShares = crestaShare.GPlusCount;
				$('#googleplus-count').text( ReplaceNumberWithCommas(googleplusShares) )
				$('#total-shares').attr('data-googleplusShares', googleplusShares)
				
			}
				
			// Facebook Shares Count
			function facebookShares($URL) {
			
				if ( $('#facebook-cresta').hasClass('facebook-cresta-share') ) {

					$.getJSON('https://graph.facebook.com/?id=' + $URL, function (fbdata) {

						$('#facebook-count').text( ReplaceNumberWithCommas(fbdata.shares || 0) )
						$('#total-shares').attr('data-facebookShares', fbdata.shares || 0)
					});
				
				}

			}
			
			
			// Twitter Shares Count
			function twitterShares($URL) {
			
				if ( $('#twitter-cresta').hasClass('twitter-cresta-share') ) {

					$.getJSON('https://cdn.api.twitter.com/1/urls/count.json?url=' + $URL + '&callback=?', function (twitdata) {
						$('#twitter-count').text( ReplaceNumberWithCommas(twitdata.count) )
						$('#total-shares').attr('data-twitterShares', twitdata.count)
					});
					
				}

			}

			// LinkedIn Shares Count
			function linkedInShares($URL) {
			
				if ( $('#linkedin-cresta').hasClass('linkedin-cresta-share') ) {

					$.getJSON('https://www.linkedin.com/countserv/count/share?url=' + $URL + '&callback=?', function (linkedindata) {
						$('#linkedin-count').text( ReplaceNumberWithCommas(linkedindata.count) )
						$('#total-shares').attr('data-linkedInShares', linkedindata.count)
					});
				
				}

			}
			
			// Pinterest Shares Count
			function pinterestShares($URL) {
			
				if ( $('#pinterest-cresta').hasClass('pinterest-cresta-share') ) {

					$.getJSON('https://api.pinterest.com/v1/urls/count.json?url=' + $URL + '&callback=?', function (pindata) {
						$('#pinterest-count').text( ReplaceNumberWithCommas(pindata.count) )
						$('#total-shares').attr('data-pinterestShares', pindata.count)
					});

				}
				
			}

			// Check if all JSON calls are finished or not

			function checkJSON_getSum() {

					if ( $('#facebook-cresta').hasClass('facebook-cresta-share') ) {
						if ($('#total-shares').attr('data-facebookShares') != undefined) {
							var fbShares = parseInt($('#total-shares').attr('data-facebookShares'));
						} else {
							setTimeout(function () {
							checkJSON_getSum();
							}, 200);
						}
					} else {
						var fbShares = 0;
					}
					if ( $('#twitter-cresta').hasClass('twitter-cresta-share') ) {
						if ($('#total-shares').attr('data-twitterShares') != undefined) {
							var twitShares = parseInt($('#total-shares').attr('data-twitterShares'));
						} else {
							setTimeout(function () {
							checkJSON_getSum();
							}, 200);
						}
					} else {
						var twitShares = 0;
					}
					if ( $('#linkedin-cresta').hasClass('linkedin-cresta-share') ) {
						if ($('#total-shares').attr('data-linkedinShares') != undefined) {
							var linkedInShares = parseInt($('#total-shares').attr('data-linkedinShares'));
						} else {
							setTimeout(function () {
							checkJSON_getSum();
							}, 200);
						}
					} else {
						var linkedInShares = 0;
					}
					if ( $('#pinterest-cresta').hasClass('pinterest-cresta-share') ) {
						if ($('#total-shares').attr('data-pinterestShares') != undefined) {
							var pinterestShares = parseInt($('#total-shares').attr('data-pinterestShares'));
						} else {
							setTimeout(function () {
							checkJSON_getSum();
							}, 200);
						}
					} else {
						var pinterestShares = 0;
					}
					if ( $('#googleplus-cresta').hasClass('googleplus-cresta-share') ) {
						if($('#total-shares').attr('data-googleplusShares') != undefined) {
							var googleplusShares = parseInt($('#total-shares').attr('data-googleplusShares'));
						} else {
							setTimeout(function () {
							checkJSON_getSum();
							}, 200);
						}
					} else {
						var googleplusShares = 0;
					}
					var totalShares = fbShares + twitShares + linkedInShares + pinterestShares + googleplusShares;
					$('#total-count').text( ReplaceNumberWithCommas(totalShares) || 0 )

			}

			function totalShares($URL) {
				if ( $('#linkedin-cresta').hasClass('linkedin-cresta-share') ) {
					linkedInShares($URL);
				}
				if ( $('#twitter-cresta').hasClass('twitter-cresta-share') ) {
					twitterShares($URL);
				}
				if ( $('#facebook-cresta').hasClass('facebook-cresta-share') ) {
					facebookShares($URL);
				}
				if ( $('#pinterest-cresta').hasClass('pinterest-cresta-share') ) {
					pinterestShares($URL);
				}
				if ( $('#googleplus-cresta').hasClass('googleplus-cresta-share') ) {
					googleplusShares;
				}
				checkJSON_getSum();
			}
		
		

	});
	
})(jQuery);