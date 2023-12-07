// JavaScript Document

/***************************************************************************\
 *  SN Images, Plugin pour SPIP / GNU-GPL                                   *
 *  Copyright (c) Simon N                                				*
\***************************************************************************/

function snimageSelecteur(id_input,txt_alt){
	$('#snimages_selecteur_api_' + id_input + ' ul li').on('mouseover',$('#' + id_input + ' ul li'),function(){
		showSnimageInfos($(this));
	});
	function showSnimageInfos($cible){
		$('#selecteur_snimages_info_snimage_' + id_input).html($cible.data('infos'));
	}
}
// end snimage_selecteur.js
