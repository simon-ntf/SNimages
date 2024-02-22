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

function snimages_lister_image_formats(){
    include_spip('inc/sn_const');
	$sn_const_snimages_formats = sn_global_snimages_formats();
	$retour = [];
    foreach($sn_const_snimages_formats as $cle => $valeur){
        $retour[$cle] = _T('snimage:snimage_format_' . $cle);
    }
    return $retour;
}

function snimages_lister_image_habillages(){
    include_spip('inc/sn_const');
	$sn_const_snimages_habillages = sn_global_snimage_habillages();
	$retour = [];
    foreach($sn_const_snimages_habillages as $cle => $valeur){
        $retour[$cle] = _T('snimage:snimage_habillage_' . $cle);
    }
    return $retour;
}

/**
 * Renvoie la valeur d'un attribut de format/definition (ou pour $attribut=0 renvoie l'ensemble des attributs)
 *
 * @param string $snimage_format Format
 * @param string $snimage_def Definition
 * @param string $attribut Attribut cible largeur_min|largeur_max|hauteur_min|hauteur_max|poids_max|necessaire
 * @return string Valeur / array Liste des clés/valeurs
*/
function snimage_format_attribut($snimage_format,$snimage_def,$attribut=0){
	include_spip('inc/sn_const');
	$sn_const_snimages_formats = sn_global_snimages_formats();
	if($attribut === 0 && isset($sn_const_snimages_formats[$snimage_format][$snimage_def])){
		return $sn_const_snimages_formats[$snimage_format][$snimage_def];
	} else if (isset($sn_const_snimages_formats[$snimage_format][$snimage_def][$attribut])) {
		return $sn_const_snimages_formats[$snimage_format][$snimage_def][$attribut];
	}
	return false;
}
function snimage_format_libelle($snimage_format){
	$trad = _T('snimage:snimage_format_' . $snimage_format);
    return $trad;
}
function snimage_def_libelle($snimage_def){
	$trad = _T('snimage:snimage_def_' . $snimage_def);
    return $trad;
}


function snimage_prive_balise_img($id_document,$extension,$snv_format,$snv_def,$largeur=100,$hauteur=100,$class='',$alt=''){
	include_spip('inc/sn_const');
	$sn_const_dir_snimages = sn_global_dir_snimages();
	$chemin = _DIR_IMG . $sn_const_dir_snimages . '/' . $snv_format . '_' . $snv_def . '/' . $id_document . '.' . $extension;
	$balise_img = '<img class="'.$class.'" src="'.$chemin.'" width="'.$largeur.'" height="'.$hauteur.'" alt="'.$alt.'"/>';
	return $balise_img;
}

/**
 * Liste les classes standards des snimg.
 *
 * @param int $id_document
 * @param string $media
 * @param array $env
 * @param array $get
 * @return string
 */
function filtre_snimg_modele_standard_classes_dist($Pile, $id_document, $media) {
	$env = $Pile[0];
	$var = $Pile['vars'] ?? [];

	$classes = [];
	$classes[] = "sn-img_$id_document";
	$classes[] = "sn-img";
	if (!empty($var['legende'])) {
		$classes[] = "sn-img-legende";
	}
	if (!empty($env['class'])) {
		$classes[] = $env['class'];
	}
	return implode(" ", $classes);
}

/**
 * Pour créer les galeries : découpe par tranches le total des images et met en place une pagination (pour éviter de tout charger en masse quand il y a beaucoup)
 */
function snimage_galerie_liste_tranche($liste_images,$nb_limite=0){
	include_spip('inc/sn_const');
	if($nb_limite == 0){
		$limite = sn_global_snimages_galerie_limite();
	} else if(is_int($nb_limite)){
		$limite = $nb_limite;
	}
	$liste_array = sn_explose($liste_images);
	if(!is_array($liste_array)){
		return false;
	}
	$i=1; $ii=0;
	$tableaux = [];
	foreach($liste_array as $cle => $valeur){
		if($ii == $limite) {
			$i++; $ii=0;
		}
		$tableaux[$i][$ii] = $valeur;
		$ii++;
	}
	return $tableaux;
}

/**
 * Vérifier si oui ou non un répertoire de sauvegarde des snimages est présent
 */
function snimages_detecter_sauvegarde(){
	include_spip('inc/sn_const');
	$sn_const_dir_snimages = sn_global_dir_snimages();
	$res = file_exists(_DIR_IMG.'SAUVEGARDE_'.$sn_const_dir_snimages);
	return $res;
}
