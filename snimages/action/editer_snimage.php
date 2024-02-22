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
 * Gestion des actions d'édition des snimages
 *
 * @plugin SnImages
 **/
 
if (!defined("_ECRIRE_INC_VERSION")) {
	return;
}

/**
 * Action editer_snimage
 *
 * @param int $arg
 * @return array
 */
function action_editer_snimage_dist($arg = null) {

	if (is_null($arg)) {
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$arg = $securiser_action();
	}

	// Envoi depuis le formulaire de creation d'un document
	if (!$id_snimage = intval($arg)) {
		$id_snimage = snimage_inserer();
	}

	if (!$id_snimage) {
		return [0, ''];
	} // erreur

	$err = snimage_modifier($id_snimage);

	return [$id_snimage, $err];
}

/**
 * Creer un nouveau document
 *
 * @param int $id_parent
 *     inutilise, pas de parent pour les documents
 * @param array|null $set
 * @return int
 */
function snimage_inserer($id_parent = null, $set = null) {

	$champs = [
		'statut' => 'prop',
		'date' => 'NOW()',
	];

	if ($set) {
		$champs = array_merge($champs, $set);
	}

	// Envoyer aux plugins
	$champs = pipeline('pre_insertion',
		[
			'args' => [
				'table' => 'spip_snimages',
			],
			'data' => $champs
		]
	);
	$id_snimage = sql_insertq("spip_snimages", $champs);
	pipeline('post_insertion',
		[
			'args' => [
				'table' => 'spip_snimages',
				'id_objet' => $id_snimage
			],
			'data' => $champs
		]
	);

	return $id_snimage;
}

/**
 * Enregistre une revision de document.
 * $set est un contenu (par defaut on prend le contenu via _request())
 *
 * @param int $id_snimage
 * @param array|null $set
 * @return string|null
 */
function snimage_modifier($id_snimage, $set = null) {

	include_spip('inc/modifier');
	include_spip('inc/filtres');

	// champs normaux
	$champs = collecter_requests(
	// white list
		objet_info('snimage', 'champs_editables'),
		// black list
		['parents', 'ajout_parents'],
		// donnees eventuellement fournies
		$set
	);
	
	$invalideur = "";
	$indexation = false;

	// Si le document est publie, invalider les caches et demander sa reindexation
	$t = sql_getfetsel("statut", "spip_snimages", 'id_snimage=' . intval($id_snimage));
	if ($t == 'publie') {
		$invalideur = "id='id_snimage/$id_snimage'";
		$indexation = true;
	}
	
	if ($err = objet_modifier_champs('snimage', $id_snimage,
		array(
			'data' => $set,
			'invalideur' => $invalideur,
			'indexation' => $indexation
		),
		$champs)
	) {
		return $err;
	}
	// Changer le statut du document ?
	// le statut n'est jamais fixe manuellement mais decoule de celui des objets lies
	$champs = collecter_requests(['parents', 'ajouts_parents'], [], $set);
	if (snimage_instituer($id_snimage, $champs)) {

		//
		// Post-modifications
		//

		// Invalider les caches
		include_spip('inc/invalideur');
		suivre_invalideur("id='id_snimage/$id_snimage'");
	}

}

/**
 * determiner le statut d'une snimage : prepa/publie
 * si on trouve un element joint sans champ statut ou avec un statut='publie' alors le doc est publie aussi
 *
 * @param int $id_document
 * @param array $champs
 * @return bool
 */
function snimage_instituer($id_snimage, $champs = array()) {

	$statut = isset($champs['statut']) ? $champs['statut'] : null;
	$date_publication = isset($champs['date']) ? $champs['date'] : null;
	
	$row = sql_fetsel("statut,date", "spip_snimages", "id_snimage=$id_snimage");
	$statut_ancien = $row['statut'];
	$date_publication_ancienne = $row['date_publication'];

	/* Auto-publication par défaut */
	if (is_null($statut)) {
		$statut = 'publie';
	}
	if ($statut !== $statut_ancien
		or $date_publication != $date_publication_ancienne
	) {
		sql_updateq('spip_snimages', ['statut' => $statut, 'date' => $date_publication],
			'id_snimage=' . intval($id_snimage));

		return true;
	}

	return false;
}

/**
 * Supprimer une snimage
 *
 * @pipeline_appel trig_supprimer_objets_lies
 *
 * @param int $id_snimage
 *     Identifiant de l'image à supprimer
 * @return void
 */
function snimage_supprimer($id_snimage) {
	sql_delete("spip_snimages", "id_snimage=" . intval($id_snimage));
	snimage_dissocier($id_snimage, '*');
	pipeline('trig_supprimer_objets_lies',
		[
			['type' => 'snimage', 'id' => $id_snimage]
		]
	);
}

/**
 * Associer une snimage à des objets listés sous forme
 * `array($objet=>$id_objets,...)`
 *
 * $id_objets peut lui-même être un scalaire ou un tableau pour une
 * liste d'objets du même type
 *
 * @param int $id_snimage
 *     Identifiant de l'image à faire associer
 * @param array $objets
 *     Description des associations à faire
 * @param array $qualif
 *     Couples (colonne => valeur) de qualifications à faire appliquer
 * @return int|bool
 *     Nombre de modifications, false si erreur
 */
function snimage_associer($id_snimage, $objets, $qualif = null) {
	include_spip('action/editer_liens');
	
	return objet_associer(['snimage' => $id_snimage], $objets, $qualif);
}

/**
 * Dissocier une snimage des objets listés sous forme
 * `array($objet=>$id_objets,...)`
 *
 * $id_objets peut lui-même être un scalaire ou un tableau pour une
 * liste d'objets du même type
 *
 * un * pour $id_mot,$objet,$id_objet permet de traiter par lot
 *
 * @param int $id_snimage
 *     Identifiant de l'image à faire dissocier
 * @param array $objets
 *     Description des dissociations à faire
 * @return int|bool
 *     Nombre de modifications, false si erreur
 */
function snimage_dissocier($id_snimage, $objets) {
	include_spip('action/editer_liens');

	return objet_dissocier(['snimage' => $id_snimage], $objets);
}

// Fonctions Dépréciées
// --------------------

/**
 * Insertion d'un document
 *
 * @deprecated Utiliser snimage_inserer()
 * @see snimage_inserer()
 * @return int Identifiant du nouveau snimage
 */
function insert_snimage() {
	return snimage_inserer();
}

/**
 * Modification d'une snimage
 *
 * @deprecated Utiliser snimage_modifier()
 * @see snimage_modifier()
 * @param int $id_snimage Identifiant du snimage
 * @param array|bool $set
 */
function snimages_set($id_snimage, $set = false) {
	return snimage_modifier($id_snimage, $set);
}

/**
 * Insituer une snimage
 *
 * @deprecated Utiliser snimage_instituer()
 * @see snimage_instituer()
 * @param int $id_snimage Identifiant du snimage
 * @param array $champs
 */
function instituer_snimage($id_snimage, $champs = []) {
	return snimage_instituer($id_snimage, $champs);
}

/**
 * Réviser une snimage
 *
 * @deprecated Utiliser snimage_modifier()
 * @see snimage_modifier()
 * @param int $id_snimage Identifiant du snimage
 * @param array $c
 */
function revision_snimage($id_snimage, $c = false) {
	return snimage_modifier($id_snimage, $c);
}
