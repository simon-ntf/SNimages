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

/**
 * Définit les autorisations du plugin SN Images
 *
 * @package SPIP\SNimages\Autorisations
 **/

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Fonction d'appel pour le pipeline
 *
 * @pipeline autoriser
 */
function snimages_autoriser() {
}

/**
 * Autorisation de voir la page SNimages
 *
 * @param  string $faire Action demandée
 * @param  string $type Type d'objet sur lequel appliquer l'action
 * @param  int $id Identifiant de l'objet
 * @param  array $qui Description de l'auteur demandant l'autorisation
 * @param  array $opt Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
 **/
function autoriser_snimages_voir_dist($faire, $type, $id, $qui, $opt) {
	return $qui['statut'] == '0minirezo';
}

/**
 * Autorisation de voir le bouton SNimages dans le menu
 *
 * @param  string $faire Action demandée
 * @param  string $type Type d'objet sur lequel appliquer l'action
 * @param  int $id Identifiant de l'objet
 * @param  array $qui Description de l'auteur demandant l'autorisation
 * @param  array $opt Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
 **/
function autoriser_snimages_menu_dist($faire, $type, $id, $qui, $opt) {
	return autoriser('voir', '_snimages', $id, $qui, $opt);
}

/**
 * Autorisation de joindre une SNimage
 *
 * Sans objet ici
 */
function autoriser_joindresnimage_dist($faire, $type, $id, $qui, $opt) {
	return false;
}


/**
 * Autorisation de modifier une SNimage
 *
 * Selon autorisation de modifier le document parent
 *
 * @staticvar <type> $m
 * @param  string $faire Action demandée
 * @param  string $type Type d'objet sur lequel appliquer l'action
 * @param  int $id Identifiant de l'objet
 * @param  array $qui Description de l'auteur demandant l'autorisation
 * @param  array $opt Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
 */
function autoriser_snimage_modifier_dist($faire, $type, $id, $qui, $opt) {
	static $m = [];

	$q = $qui['id_auteur'];
	if (isset($m[$q][$id])) {
		return $m[$q][$id];
	}

	$s = sql_getfetsel('id_document,statut', 'spip_snimages', 'id_snimage=' . intval($id));
	// les admins ont le droit de modifier toutes les snimages existantes
	if ($qui['statut'] == '0minirezo'
		and !$qui['restreint']
	) {
		return is_string($s) ? true : false;
	}
	// les autres selon autorisation de modifier le document parent
	if (!isset($m[$q][$id])) {
		$interdit = false;
		if (!autoriser('modifier', 'document', intval($s['id_document']), $qui, $opt)) {
			$interdit = true;
		}
		$m[$q][$id] = ($interdit ? false : true);
	}

	return $m[$q][$id];
}


/**
 * Autorisation de supprimer une SNimage
 *
 * Selon autorisation de modifier le document parent
 *
 * @param  string $faire Action demandée
 * @param  string $type Type d'objet sur lequel appliquer l'action
 * @param  int $id Identifiant de l'objet
 * @param  array $qui Description de l'auteur demandant l'autorisation
 * @param  array $opt Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
 */
function autoriser_snimage_supprimer_dist($faire, $type, $id, $qui, $opt) {
	if (!intval($id)
		or !$qui['id_auteur']
		or !autoriser('ecrire', '', '', $qui)
	) {
		return false;
	}
	return autoriser('modifier', 'snimage', $id, $qui, $opt);
}


/**
 * Autorisation de voir une SNimage
 *
 * Selon autorisation de voir le document parent
 *
 * @param  string $faire Action demandée
 * @param  string $type Type d'objet sur lequel appliquer l'action
 * @param  int $id Identifiant de l'objet
 * @param  array $qui Description de l'auteur demandant l'autorisation
 * @param  array $opt Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
 **/
function autoriser_snimage_voir_dist($faire, $type, $id, $qui, $opt) {
	$s = sql_getfetsel('id_document', 'spip_snimages', 'id_snimage=' . intval($id));
	if(isset($s['id_document'])){
		return autoriser('voir', 'document', intval($s['id_document']), $qui, $opt);
	}
	return false;
}
