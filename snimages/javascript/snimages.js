// JavaScript Document
// snimages.js Nov 2023 par Simon N
// constantes requises
// SNIMAGES_ZOOM_BOUTON_TEXTE
// SNIMAGES_ZOOM_PICTO
// SNIMAGES_ZOOMER_BOUTON_PRECEDENT
// SNIMAGES_ZOOMER_BOUTON_SUIVANT
// SNIMAGES_ZOOMER_TEXTE

var snimgz_screen = {
	struc:{
		bloc: null,
		legende: null,
		img:0,
		image: 0,
		image_avant: 0,
		image_apres: 0,
		btn_prec: null,
		btn_suiv: null,
	},
    attrib:{
		visibilite:false,
		activation:false,
	}
};

(function($){
    $(window).on('load',function(){
    	var snimgz_obj;
    	snimgz_screen = snImgZoomScreenConstruire();
		$(".sn-img-active").each(function(index, element) {
			if($(this).parent().hasClass("sn-galerie-col")){} else{ snimgz_obj = snImgZActiver($(this)); }
		});
		snSuperGaleries();
		$(document).on('keyup',function(e){ e.preventDefault(); e.stopPropagation(); snImgActionClavier(e,snimgz_screen); });
    });
})(jQuery)

function snImgActionClavier(e,snimgz_screen) {
	if(snimgz_screen.attrib.visibilite === true){
		if((e.keyCode == 13)||(e.keyCode == 27)){
			snImgZoomEx(null,snimgz_screen);
		} else if(snimgz_screen.attrib.activation === true){
			if((e.keyCode == 37)||(e.keyCode == 100)){
				if(snimgz_screen.struc.image_avant == 0){} else {
					snImgZoomEx(snimgz_screen.struc.image_avant);
				}
			}
			else if((e.keyCode == 39)||(e.keyCode == 102)){
				if(snimgz_screen.struc.image_apres == 0){} else {
					snImgZoomEx(snimgz_screen.struc.image_apres);
				}
			}
		}
	}
}

function snImgZActiver($img){
	var innertextarea = document.createElement('textarea');
	innertextarea.innerHTML = SNIMAGES_ZOOM_PICTO;
	$img.on('click',function(e){ e.preventDefault(); e.stopPropagation(); snImgZoomIn($img); });
   return true;
}

function snImgZoomScreenConstruire(){
	var $snimage_zoom_bloc = $('<div id="sn-img-zoom-screen"></div>');
	var $snimage_navi_bloc = $('<div id="sn-img-zoom-screen-navi"></div>').appendTo($snimage_zoom_bloc);
	var $snimage_zoom_prec_btn = $('<button id="sn-img-zoom-screen-prec-btn" title="' + SNIMAGES_ZOOMER_BOUTON_PRECEDENT + '">&nbsp;</button>').appendTo($snimage_navi_bloc);
	var $snimage_zoom_navi_explication = $('<div id="sn-img-zoom-screen-navi-explication">' + SNIMAGES_ZOOMER_TEXTE + '</div>').appendTo($snimage_navi_bloc);
	var $snimage_zoom_suiv_btn = $('<button id="sn-img-zoom-screen-suiv-btn" title="' + SNIMAGES_ZOOMER_BOUTON_SUIVANT + '">&nbsp;</button>').appendTo($snimage_navi_bloc);
	var $snimage_zoom_legende_bloc = $('<div id="sn-img-zoom-screen-legende"></div>').appendTo($snimage_zoom_bloc);
	return {
		struc:{
        bloc: $snimage_zoom_bloc,
        legende: $snimage_zoom_legende_bloc,
        img:0,
        image: 0,
        image_avant: 0,
        image_apres: 0,
        btn_prec: $snimage_zoom_prec_btn,
        btn_suiv: $snimage_zoom_suiv_btn,
     },
     attrib:{
     	visibilite:false,
     	activation:false,
     }
	};
}
function snImgZoomIn($snimgz){
	snimgz_screen.attrib.activation = false;
	$snimgz_img = $snimgz.clone();

	$snimgz_img.css({opacity:0});
	snimgz_screen.struc.legende.css({opacity:0});

	snimgz_screen.struc.bloc.prepend($snimgz_img);
	snimgz_screen.struc.legende.html($snimgz_img.children('figcaption').html());

	$snimgz_img.children('figcaption').remove();
	$snimgz_img.children('button').remove();

	snimgz_screen.struc.image_avant = 0;
	if($snimgz.prev().hasClass('sn-img')){
		snimgz_screen.struc.image_avant = $snimgz.prev();
	}
	else if($snimgz.parent().hasClass('sn-galerie-col') && $snimgz.parent().prev().hasClass('sn-galerie-col')){
		snimgz_screen.struc.image_avant = $snimgz.parent().prev().children('.sn-img').last();
	}

	snimgz_screen.struc.image_apres = 0;
	if($snimgz.next().hasClass('sn-img')){
		snimgz_screen.struc.image_apres = $snimgz.next();
	}
	else if($snimgz.parent().hasClass('sn-galerie-col') && $snimgz.parent().next().hasClass('sn-galerie-col')){
		snimgz_screen.struc.image_apres = $snimgz.parent().next().children('.sn-img').first();
	}

	if(snimgz_screen.struc.image_avant == 0){ snimgz_screen.struc.btn_prec.css({display:"none"}); } else {
		snimgz_screen.struc.btn_prec.css({display:"inline-block"});
		snimgz_screen.struc.btn_prec.on('click',function(e){
			e.preventDefault();
			e.stopPropagation();
			if(snimgz_screen.attrib.activation === true){ snImgZoomEx(snimgz_screen.struc.image_avant); }
		});
	}
	if(snimgz_screen.struc.image_apres == 0){ snimgz_screen.struc.btn_suiv.css({display:"none"}); } else {
		snimgz_screen.struc.btn_suiv.css({display:"inline-block"});
		snimgz_screen.struc.btn_suiv.on('click',function(e){
			e.preventDefault();
			e.stopPropagation();
			if(snimgz_screen.attrib.activation === true){ snImgZoomEx(snimgz_screen.struc.image_apres); }
		});
	}

	snimgz_screen.struc.image = $snimgz;
	snimgz_screen.struc.img = $snimgz_img;

	if(snimgz_screen.attrib.visibilite == true){
		snImgZInImgAfficher();
	} else {
		snimgz_screen.struc.bloc.appendTo($('body'));
		snimgz_screen.struc.bloc.on('click',function(e){ e.preventDefault(); e.stopPropagation();
			if($(e.target).is('button')){} else{ snImgZoomEx(); }
		});
		$(snimgz_screen.struc.bloc).animate({opacity:1},300,'swing',snImgZInOnScreen);
	}
	function snImgZInOnScreen(){
		snimgz_screen.attrib.visibilite = true;
		snImgZInImgAfficher();
	}
	function snImgZInImgAfficher(){
		$(snimgz_screen.struc.img).animate({opacity:1},300,'swing',snImgZActiver);
		$(snimgz_screen.struc.legende).animate({opacity:1},300);
	}
	function snImgZActiver(){
		snimgz_screen.attrib.activation = true;
	}

	return true;
}
function snImgZoomEx($img){
	snimgz_screen.attrib.activation = false;
	snimgz_screen.struc.btn_prec.off();
	snimgz_screen.struc.btn_suiv.off();
	$(snimgz_screen.struc.img).animate({opacity:0},300,'swing',snImgZEx2);
	$(snimgz_screen.struc.legende).animate({opacity:0},300);
	function snImgZEx2(){
		snimgz_screen.struc.img.remove();
		snimgz_screen.struc.img = null;
		snimgz_screen.struc.legende.html('');
		if($img){snImgZoomIn($img);}
		else { $(snimgz_screen.struc.bloc).animate({opacity:0},300,'swing',snImgZEx3); }
 		return true;
	}
	function snImgZEx3(){
		snimgz_screen.struc.bloc.off();
		snimgz_screen.attrib.visibilite = false;
		snimgz_screen.struc.bloc.remove();
	}
}

function snSuperGaleries(){
	$('.sn-galerie').each(function () {
		if($(this).hasClass('sn-galerie-modelee')){  } else {
			creerSuperGalerie($(this));
		}
	});
	function creerSuperGalerie($grille){
		var domid = '';
		var nombre_colonnes = 3;
		if($grille.attr('id')){ domid = $grille; } else { domid = 'supergalerie' + 1000*Math.random(); $grille.attr('id',domid); }
		if($grille.data('sng-max-cols')){ nombre_colonnes = parseInt($grille.data('sng-max-cols')); }
        snGalerieCreerColonnes($grille,nombre_colonnes);
	}
}

function snGalerieCreerColonnes($grille,nombre_colonnes){
	var id_img = 0;
	var id_col = 1;
	var cols = [];
	for(var i=1; i<=nombre_colonnes; i++){
		cols[i] = $('<div class="sn-galerie-col sn-galerie-col-'+nombre_colonnes+'" data-sng-name="' + i + '" name="' + i + '"></div>');
		cols[i].appendTo($grille);
	}
	$grille.children('.sn-img').each(function(){
		$(this).attr('data-sng-name',id_img);
		$(this).attr('data-galerie-id',$grille.attr('id'));
		$(this).addClass('sn-img-active');
		$(this).appendTo(cols[id_col]);
		snImgZActiver($(this));
		id_img++;
		id_col++;
		if(id_col > nombre_colonnes){ id_col = 1; }
	});
	return true;
}



// end snimages.js
