/* 
===============================================================

	Event Calendar By EZCode
	-------
	For more details --> http://themeforest.net/user/ezcode
	
	Version: 4.1
	
===============================================================
 */
 
"use strict";
function EliminaEvento(id){

	swal({   title: "Êtes vous sûr ?",   text: "Aucune tyryryhrrécupération possible !",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Oui, supprimer",   cancelButtonText: "Non, annuler",   closeOnConfirm: false,   closeOnCancel: false }, function(isConfirm){   if (isConfirm) {    location.href='del_booking.php?id='+id;   } else {     swal("Annulé", "", "error");   } });
	
}

function EliminaTipo(id){

	swal({   title: "Êtes vous sûr ?",   text: "Valider la réservation",   type: "info",   showCancelButton: true,   confirmButtonColor: "#157447",   confirmButtonText: "Oui, valider",   cancelButtonText: "Non, annuler",   closeOnConfirm: false,   closeOnCancel: false }, function(isConfirm){   if (isConfirm) {    location.href='valide_booking.php?id='+id;   } else {     swal("Annulé", "", "error");   } });
	
}

function rejectBooking(id){
	swal({   title: "Êtes vous sûr ?",   text: "Refuser la réservation",   type: "info",   showCancelButton: true,   confirmButtonColor: "#ffca2c",   confirmButtonText: "Refuser",   cancelButtonText: "Revenir en arrière",   closeOnConfirm: false,   closeOnCancel: false }, function(isConfirm){   if (isConfirm) {    location.href='del_booking.php?id='+id;   } else {     swal("Annulé", "", "error");   } });
}

function scrollNav() {
  $('.nav a').click(function(){  
    //Toggle Class
    $(".active").removeClass("active");      
    $(this).closest('li').addClass("active");
    var theClass = $(this).attr("class");
    $('.'+theClass).parent('li').addClass('active');
    //Animate
    $('html, body').stop().animate({
        scrollTop: $( $(this).attr('href') ).offset().top - 0
    }, 800);
    return false;
  });
  $('.scrollTop a').scrollTop();
}
scrollNav();


$(document).ready(function () { 
  $(document).click(function () {
     // if($(".navbar-collapse").hasClass("in")){
       $('.navbar-collapse').collapse('hide');
     // }
  });
});



