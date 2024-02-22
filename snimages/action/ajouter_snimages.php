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
 * Gestion de l'action ajouter_snimages
 *
 * @package SnImages\Action
 **/

if (!defined("_ECRIRE_INC_VERSION")) {
	return;
}

include_spip('inc/getdocument');
include_spip('inc/documents');
include_spip('inc/choisir_mode_document'); // compat core
include_spip('inc/renseigner_document');

include_spip('inc/snimages');

/**
 * Ajouter des snimages
 *
 * @param string $snv_format
 *    Le format de la vignette
 * @param int|string $id_document
 *    L'identidiant numérique de l'image parente
 * @param int $id_snimage
 *   Documents à remplacer
 * @param array $files
 *   Tableau de tableau de propriété pour chaque document à insérer
 *
 * @return array
 *   Liste des id_documents inserés
 */
function action_ajouter_snimages_dist($snv_format, $snv_def, $id_document, $id_snimage, $files) {
	$ajouter_une_snimage = charger_fonction('ajouter_une_snimage', 'action');
	$ajoutes = [];

	// on ne peut mettre qu'un seul document a la place d'un autre ou en vignette d'un autre
	if (intval($id_snimage)) {
		$ajoutes[] = $ajouter_une_snimage($snv_format, $snv_def, $id_document, $id_snimage, reset($files));
	} else {
		foreach ($files as $file) {
			$ajoutes[] = $ajouter_une_snimage($snv_format, $snv_def, $id_document, 'new', $file);
		}
	}

	return $ajoutes;
}

/**
 * Ajouter une snimage (au format $_FILES)
 *
 * @param string $snv_format
 *    Le format de la vignette
 * @param int|string $id_document
 *    L'identidiant numérique de l'image parente
 * @param int $id_snimage
 *   Document à remplacer
 *   0 ou 'new' pour une insertion
 * @param array $file
 *   Propriétes au format $_FILE étendu :
 *
 *   - string tmp_name : source sur le serveur
 *   - string name : nom du fichier envoye
 *   - bool titrer : donner ou non un titre a partir du nom du fichier
 *   - bool distant : pour utiliser une source distante sur internet
 *   - string mode : vignette|image|documents|choix
  *
 * @return array|bool|int|mixed|string|unknown
 *
 *   - int : l'id_document ajouté (opération réussie)
 *   - string : une erreur s'est produit, la chaine est le message d'erreur
 *
 */
function action_ajouter_une_snimage_dist($snv_format, $snv_def, $id_document, $id_snimage, $file) {

	include_spip('inc/sn_const');
	$sn_const_snimages_extensions = sn_global_snimages_extensions();
	
	$remplacement = false;
	$source = $file['tmp_name'];
	$nom_envoye = htmlspecialchars($file['name']);
	$file['name'] = strtolower(translitteration(htmlspecialchars($file['name'])));

	include_spip('inc/modifier');
	
	$source_extension = substr($nom_envoye,(strpos($nom_envoye,'.')+1));
	$source_fichier = substr($nom_envoye,0,strpos($nom_envoye,'.'));
	if(!in_array($source_extension,$sn_const_snimages_extensions)){
		return _T('snimage:erreur_upload_extension_interdite', ['ext' => $source_extension]);
	}
	
	$doc_parent = sql_fetsel('fichier,extension','spip_documents','id_document='.sql_quote($id_document));
	if (count($doc_parent)<1){
		return _T("snimage:erreur_bdd_entree_inexistante") . '(spip_documents - ' . $id_document . ')';
	}

	if( ($id_snimage==0) || ($id_snimage=='0') || ($id_snimage=='new')){
	} else {
		$remplacement = true;
		$imgdata = sql_fetsel('extension', 'spip_snimages', 'id_snimage=' . intval($id_snimage));
		if(!is_array($imgdata)){
			return _T('snimage:erreur_bdd_entree_inexistante') . '(spip_snimages - ' . $id_snimage . ')';
		}
	}
	
	if (!is_array($fichier = snimage_fixer_fichier_upload($file,$id_document,$snv_format,$snv_def))) {
		return is_string($fichier) ? $fichier : _T("snimage:erreur_upload_type_interdit", ['nom' => $file['name']]);
	}
	/**
	 * Récupère les informations du fichier
	 * -* largeur
	 * -* hauteur
	 * -* type_image
	 * -* taille
	 * -* ses metadonnées si une fonction de metadatas/ est présente
	 */
	$infos = renseigner_taille_dimension_image($fichier['fichier'], $fichier['extension']);
	if (is_string($infos)) {
		return $infos;
	} // c'est un message d'erreur !

	$champs = [
		'snv_format' => $snv_format,
		'snv_def' => $snv_def,
		'id_document' => $id_document,
		'extension' => $fichier['extension'],
	];
	$champs = array_merge($champs, $infos);

	if (($test = verifier_taille_snimage_acceptable($champs,$snv_format,$snv_def)) !== true) {
		spip_unlink(repertoire_snimage($snv_format,$snv_def) . $id_document . '.' . $fichier['extension']);
		// Si remplacement restaure l'archive
		if($remplacement === true){
			$emplacement_archive = repertoire_snimage('archives') . $snv_format . '_' . $snv_def . '_' . $id_document . '.' . $imgdata['extension'];
			if(file_exists($emplacement_archive)){
				copy($emplacement_archive, repertoire_snimage($snv_format,$snv_def) . $id_document . '.' . $imgdata['extension']);
				spip_unlink($emplacement_archive);
			}
		}
		return $test; // erreur sur les dimensions du fichier
	}

	unset($champs['type_image']);
	unset($champs['inclus']);
	
	// "mettre a jour" si on lui passe un id_snimage
	if ($id_snimage = intval($id_snimage)) {
		unset($champs['date']); // garder la date d'origine
	}
	
	include_spip('action/editer_snimage');
	// Installer le document dans la base
	if (!$id_snimage) {
		if ($id_snimage = snimage_inserer()) {
			spip_log("ajout de la snimage " . $file['tmp_name'] . " " . $file['name'] . "  (D '$id_snimage')",
				'snimages');
		} else {
			spip_log("Echec insert_snimage() de la snimage " . $file['tmp_name'] . " " . $file['name'] . "  (D '$id_snimage')",
				'snimages' . _LOG_ERREUR);
		}
	}
	if (!$id_snimage) {
		return _T('snimage:erreur_bdd_insertion', ['fichier' => "<em>" . $file['name'] . "</em>"]);
	}
	
	snimage_modifier($id_snimage, $champs);

	// permettre aux plugins de faire des modifs a l'ajout initial
	pipeline('post_edition',
		[
			'args' => [
				'table' => 'spip_snimages', // compatibilite
				'table_objet' => 'snimages',
				'spip_table_objet' => 'spip_snimages',
				'type' => 'snimage',
				'id_objet' => $id_snimage,
				'champs' => array_keys($champs),
				'serveur' => '', // serveur par defaut, on ne sait pas faire mieux pour le moment
				'action' => 'ajouter_snimage',
				'operation' => 'ajouter_snimage', // compat <= v2.0
			],
			'data' => $champs
		]
	);

	return $id_snimage;
}

/**
 * Verifier la validite du nom et de l'extension
 * Tenter la copie, tester l'existence du fichier et renvoyer ok ou erreur
 *
 * @param array $file donnees du fichier au format $_FILES
 * @param string $nouveau_nom_fichier nom a donner au fichier (sans son extension)
 * @param string $snv_format
 * @param string $snv_def
 * @return array
 */
function snimage_fixer_fichier_upload($file, $nouveau_nom_fichier, $snv_format, $snv_def) {
	if (preg_match(",\.([a-z0-9]+)(\?.*)?$,i", $file['name'], $match)) {
		$ext = @$match[1];
		$nouveau_nom_fichier .= '.' . $ext;
		$fichier = copier_snimage($ext, $nouveau_nom_fichier, $file['tmp_name'], $snv_format, $snv_def);
		// Copie ratee
		if ($fichier && (!$taille = @intval(filesize(get_spip_doc($fichier))))) {
			spip_log("Echec copie du fichier " . $file['tmp_name'] . " (taille de fichier indéfinie)");
			spip_unlink(get_spip_doc($fichier));
			return _T('snimage:erreur_copie_fichier', ['nom' => $file['tmp_name']]);
		}
		// Ok
		return ['fichier' => $fichier, 'extension' => $ext];
	}
	// Nom ou extension du fichier source invalide(s)
	return _T('snimage:erreur_upload_nom_fichier', ['fichier' => $file['tmp_name']]);
}

/**
 * Verifier si le fichier respecte les contraintes de tailles
 *
 * @param  array $infos
 * @param string $snv_format
 * @param string $snv_def
 * @return bool|mixed|string
 */
function verifier_taille_snimage_acceptable(&$infos, $snv_format, $snv_def) {
	include_spip('inc/sn_const');
	$sn_const_snimages_formats = sn_global_snimages_formats();
	$erreurs = [];
	$vformat_data = NULL;
	$vformat_data = $sn_const_snimages_formats[$snv_format][$snv_def];
	if(!$vformat_data){
		return _T('snimage:erreur_format_inconnu').' (missing) '.$snv_format.'_'.$snv_def;
	}
	if (($infos['largeur'] > $vformat_data['largeur_max']) || ($infos['largeur'] < $vformat_data['largeur_min'])) {
		$erreurs[] = _T('snimage:erreur_upload_taille',
			[
				'details' => _T('snimage:erreur_upload_largeur',
						[
							'mini' => $vformat_data['largeur_min'],
							'maxi' => $vformat_data['largeur_max']
						]),
				'actuel' => $infos['largeur'],
            'nom_serveur' => $infos['fichier']
			]);
	}
	if (($infos['hauteur'] > $vformat_data['hauteur_max']) || ($infos['hauteur'] < $vformat_data['hauteur_min'])) {
		$erreurs[] = _T('snimage:erreur_upload_taille',
			[
				'details' => _T('snimage:erreur_upload_hauteur',
						[
							'mini' => $vformat_data['hauteur_min'],
							'maxi' => $vformat_data['hauteur_max']
						]),
				'actuel' => $infos['hauteur'],
            'nom_serveur' => $infos['fichier']
			]);
	}
	if ($infos['taille'] > $vformat_data['poids_max'] * 1024) {
		$erreurs[] = _T('snimage:erreur_upload_poids',
			[
				'maxi' => $vformat_data['poids_max'],
				'actuel' => (round($infos['taille'] / 1024)),
            'nom_serveur' => $infos['fichier']
			]);
	}
	if(count($erreurs)>0){
		return implode($erreurs,' <br/> ');
	}
	return true;
}
