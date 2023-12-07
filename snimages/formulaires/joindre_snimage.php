<?php

/***************************************************************************\
 *  SN Suite, suite de plugins pour SPIP                                   *
 *  Copyright © depuis 2014                                                *
 *  Simon N                                                            *
 *                                                                         *
 *  Ce programme est un logiciel libre distribué sous licence GNU/GPL.     *
 *  Pour plus de détails voir l'aide en ligne.                             *
 *  https://www.snsuite.net                                                *
\**************************************************************************/

/**
 * Gestion du formulaire de téléversement de snvignettes
 *
 * @package SN\snimages\Formulaires
 */
 
if (!defined("_ECRIRE_INC_VERSION")) {	return; }

/**
 * Chargement du formulaire
 *
 * @param string $snimage_format
 *    Le format de la vignette (par défaut ecran|mobile|miniature)
 * @param int|string $id_snimage
 *    L'identidiant numérique de l'image parente
 * @param int|string $id_snvignette
 *    L'identidiant numérique du document s'il est à remplacer, sinon "new"
 * @return array $valeurs
 *    Les valeurs chargées dans le formulaire
 */
function formulaires_joindre_snimage_charger_dist(
	$snimage_format,
	$snimage_def,
	$id_document = 'new',
	$id_snimage = 'new'
) {
	$valeurs = [];
	
	$valeurs['snimage_format'] = $snimage_format;
	$valeurs['snimage_def'] = $snimage_def;
	$valeurs['id_document'] = $id_document;
	$valeurs['id_snimage'] = $id_snimage;
	$valeurs['id'] = $id_snimage;

	$valeurs['url'] = 'https://';
	$valeurs['fichier_upload'] = $valeurs['_options_upload_ftp'] = $valeurs['_dir_upload_ftp'] = '';
	$valeurs['joindre_upload'] = $valeurs['joindre_distant'] = $valeurs['joindre_ftp'] = $valeurs['joindre_mediatheque'] = '';

	$valeurs['editable'] = ' ';
	if (intval($id_snimage)) {
		$valeurs['remplacement'] = 'oui';
		$req_img = sql_fetsel("id_snimage,extension", "spip_snimages", "id_snimage=$id_snimage");
		$valeurs['extension'] = $req_img['extension'];
		$valeurs['editable'] = autoriser('modifier', 'snimage', $id_snimage) ? ' ' : '';
	}

	return $valeurs;
}

/**
 * Vérification du formulaire
 *
 * @param string $snimage_format
 *    Le format de la vignette (par défaut ecran|mobile|miniature)
 * @param int|string $id_snimage
 *    L'identidiant numérique de l'image parente
 * @param int|string $id_snvignette
 *    L'identidiant numérique de la vignette si elle est à remplacer, sinon "new"
 * @return array $erreurs
 *    Les erreurs éventuelles dans un tableau
 */
function formulaires_joindre_snimage_verifier_dist(
	$snimage_format,
	$snimage_def,
	$id_document,
	$id_snimage = 'new'
) {
	include_spip('inc/joindre_document');

	$erreurs = [];
	$files = joindre_trouver_fichier_envoye();
	if (is_string($files)) {
		$erreurs['message_erreur'] = $files;
	} elseif (is_array($files)) {
		// erreur si on a pas trouve de fichier
		if (!count($files)) {
			$erreurs['message_erreur'] = _T('snimage:erreur_upload_aucun_fichier');
		} else {
			// regarder si on a eu une erreur sur l'upload d'un fichier
			foreach ($files as $file) {
				if (isset($file['error'])
					and $test = joindre_upload_error($file['error'])
				) {
					if (is_string($test)) {
						$erreurs['message_erreur'] = $test;
					} else {
						$erreurs['message_erreur'] = _T('snimage:erreur_upload_aucun_fichier');
					}
				}
			}
		}
	}

	if (count($erreurs) and defined('_tmp_dir')) {
		effacer_repertoire_temporaire(_tmp_dir);
	}

	return $erreurs;
}

/**
 * Traitement du formulaire
 *
 * @param string $snimage_format
 *    Le format de la vignette (par défaut ecran|mobile|miniature)
 * @param int|string $id_snimage
 *    L'identidiant numérique de l'image parente
 * @param int|string $id_snvignette
 *    L'identidiant numérique du document s'il est à remplacer, sinon "new"
 * @return array $res
 *    Le tableau renvoyé par les CVT avec le message et editable
 */
function formulaires_joindre_snimage_traiter_dist(
	$snimage_format,
	$snimage_def,
	$id_document,
	$id_snimage = 'new'
) {
	$res = ['editable' => true];
	$ancre = '';

	$ajouter_documents = charger_fonction('ajouter_snimages', 'action');

	include_spip('inc/joindre_document');
	$files = joindre_trouver_fichier_envoye();
	
	$nouveaux_doc = $ajouter_documents($snimage_format, $snimage_def, $id_document, $id_snimage, $files);

	if (defined('_tmp_zip')) {
		unlink(_tmp_zip);
	}
	if (defined('_tmp_dir')) {
		effacer_repertoire_temporaire(_tmp_dir);
	}
	
	$check_erreur = false;
	$sel = [];
	foreach ($nouveaux_doc as $doc) {
		if(is_numeric($doc) && (intval($doc)>0)){
			$sel[] = $doc;
		}
	}
	if(count($nouveaux_doc) === count($sel)){
		$check_erreur = true;
	}
	
	if ($check_erreur == true) {
		$res['message_ok'] = singulier_ou_pluriel(count($sel), 'snimage:info_fichier_installe_succes',
			'snimage:info_nb_fichiers_installes_succes');
	} else {
		$res['message_erreur'] = implode($nouveaux_doc,' <br/> ');
	}
	
	return $res;
}
