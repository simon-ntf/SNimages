<?php

/***************************************************************************\
 *  SN suite, suite de plugins pour SPIP                                   *
 *  Copyright © depuis 2014                                                *
 *  Simon N                                                            *
 *                                                                         *
 *  Ce programme est un logiciel libre distribué sous licence GNU/GPL.     *
 *  Pour plus de détails voir l'aide en ligne.                             *
 *  https://www.snsuite.net                                                *
\**************************************************************************/

if (!defined('_ECRIRE_INC_VERSION')) { return; }

if (!function_exists('snimages_afficher_contenu_objet')){ function snimages_afficher_contenu_objet($flux) {
	include_spip('inc/sn_const');
	$sn_const_snimageable = sn_global_objet_snimageable();
	if(in_array($flux['args']['type'],$sn_const_snimageable)){
		$objet = $flux['args']['type'];
		$table = 'spip_' . $objet . 's';
		$contexte = sql_fetsel("*", $table, 'id_' . $objet . '="' . sql_quote($flux['args']['contexte']['id_' . $objet]).'"');
		$flux['data'] = recuperer_fond('prive/inclure/afficher/champ_id_document',$contexte) . $flux['data'];
	}
	return $flux;
}}

if (!function_exists('snimages_affiche_gauche')){ function snimages_affiche_gauche($flux) {
	$en_cours = trouver_objet_exec($flux['args']['exec']);
	if(
		$en_cours = trouver_objet_exec($flux['args']['exec'])
		and $type = $en_cours['type']
		and $id_table_objet = $en_cours['id_table_objet']
		// id non defini sur les formulaires de nouveaux objets
		and (isset($flux['args'][$id_table_objet]) and $id = intval($flux['args'][$id_table_objet])
			// et justement dans ce cas, on met un identifiant negatif
			or $id = 0 - $GLOBALS['visiteur_session']['id_auteur'])

	) {
		if($type === 'document'){
			$flux['data'] .= recuperer_fond('prive/squelettes/inclure/colonne-snimage', ['id_document' => $id]);
		}
	}
	return $flux;
}}

if (!function_exists('snimages_editer_contenu_objet')){ function snimages_editer_contenu_objet($flux) {
	include_spip('inc/sn_const');
	$sn_const_snimageable = sn_global_objet_snimageable();
	$sn_const_snimages_formats = sn_global_snimages_formats();
	$contexte = $flux['args']['contexte'];
	if($flux['args']['type'] === 'document'){
		foreach($sn_const_snimages_formats as $sni_format => $sni_def_data){
			foreach($sni_def_data as $sni_def => $snif_data){
				$contexte['snimage_format'] = $sni_format;
				$contexte['snimage_def'] = $sni_def;
				if($snif_data['active'] === 'oui'){
					$flux['data'] =  $flux['data'] . recuperer_fond('prive/squelettes/inclure/doc-joindre-snimage',$contexte);
				}
			}
		}
	} else if(in_array($flux['args']['type'],$sn_const_snimageable)){
		if($flux['args']['type'] === 'auteur'){
			$flux['data'] = str_replace(
				'<div class="editer editer_bio',
				recuperer_fond('prive/inclure/editer/champ_id_document',$flux['args']['contexte']) . '<div class="editer editer_bio',
				$flux['data']
			);
		} else {
			$contexte['imgapi'] = _request('imgapi');
			$contexte['selimg'] = _request('selimg');
			$flux['data'] = str_replace(
				'<div class="editer editer_texte',
				recuperer_fond('prive/inclure/editer/champ_id_document',$contexte) . '<div class="editer editer_texte',
				$flux['data']
			);
		}
	}
	return $flux;
}}

if (!function_exists('snimages_header_prive')){ function snimages_header_prive($flux) {
	$flux .= '<link rel="stylesheet" type="text/css" href="'._DIR_PLUGIN_SNIMAGES.'prive/themes/spip/style.css"/>';
	$flux .= '<script type="text/javascript" src="'  . _DIR_PLUGIN_SNIMAGES . 'prive/javascript/snimage_selecteur.js"></script>';
	return $flux;
}}

if (!function_exists('snimages_insert_head')){ function snimages_insert_head($flux) {
	$chemin_picto_zoom = find_in_path('css/img/picto_image_zoom-xx.svg');
	$picto_zoom = htmlentities(recuperer_fond('inc/sn_balise_svg',['chemin' => $chemin_picto_zoom,'alt' => _T('snimage:zoomeur_bouton_texte')]));
	$flux .= '<script type="text/javascript">var SNIMAGES_ZOOMER_BOUTON_PRECEDENT = "' . _T('snimage:zoomer_bouton_precedent') . '"; var SNIMAGES_ZOOMER_BOUTON_SUIVANT = "' . _T('snimage:zoomer_bouton_suivant') . '"; var SNIMAGES_ZOOM_BOUTON_TEXTE = "' . _T('snimage:zoom_bouton_texte') . '"; var SNIMAGES_ZOOMER_TEXTE = "' . _T('snimage:zoomer_texte') . '"; var SNIMAGES_ZOOM_PICTO = "' . $picto_zoom . '";</script>'
	. '<script type="text/javascript" src="'  . _DIR_PLUGIN_SNIMAGES . 'javascript/snimages.js"></script>';
	return $flux;
}}

if (!function_exists('snimages_insert_head_css')){ function snimages_insert_head_css($flux) {
	$flux .= '<link rel="stylesheet" type="text/css" media="all" href="' . _DIR_PLUGIN_SNIMAGES . 'css/snimages.css"/>';
	return $flux;
}}

if (!function_exists('snimages_pre_edition')){ function snimages_pre_edition($flux) {

	include_spip('inc/sn_const');
	$sn_const_snimageable_champs = sn_global_objet_snimageable_champs();
	$sn_const_snimageable = sn_global_objet_snimageable();
	if(
		$type = $flux['args']['type']
		and $id_objet = $flux['args']['id_objet']
		and $table = $flux['args']['spip_table_objet']
		and in_array($flux['args']['type'],$sn_const_snimageable)
	){
		include_spip('action/editer_liens');
		objet_dissocier(['document' => '*'], ['article' => '*']);
		$champs_traites = $sn_const_snimageable_champs[$type];
		$champ_entree = '';
		$matches = [];
		$rang = 0;
		foreach($champs_traites as $i => $champ){
			$matches = [];
			if($l = preg_match_all('#sn[i|v]mg([0-9]{1,21})#', _request($champ), $matches)){
				foreach($matches[1] as $ii => $iddoc){
					objet_associer(['document' => $iddoc], [$type => $id_objet]);
					objet_qualifier_liens(['document' => $iddoc], [$type => $id_objet], ['snlien' => 'snimg', 'rang_lien' => $rang]);
					$rang++;
				}
			}
		}
	}
	return $flux;
	
}}
