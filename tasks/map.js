// JavaScript Document

$(function() {

$.fn.maphilight.defaults = {
	fill: true,
	fillColor: 'a7b8e9',
	fillOpacity: 1,
	stroke: true,
	strokeColor: 'a7b8e9',
	strokeOpacity: 1,
	strokeWidth: 1,
	fade: true,
	alwaysOn: false,
	neverOn: false,
	groupBy: false
}	
	
$('.map').maphilight({fade: false});
});

	