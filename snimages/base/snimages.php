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
 * Bases de données du plugin SN Images
 *
 * @plugin snimages
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

function snimages_declarer_tables_interfaces($interface){
	$interface['table_des_tables']['snimages'] = 'snimages';
	return $interface;
}

function snimages_declarer_tables_objets_sql($tables) {
	$tables['spip_snimages'] = [
		'principale' => "oui",
		'field'=> [
			"id_snimage" 		=> "bigint(21) NOT NULL",
			"id_document"		=> "bigint(21) NOT NULL",
			"date" 				=> "datetime DEFAULT '0000-00-00 00:00:00' NOT NULL",
			"maj" 				=> "TIMESTAMP",
			"statut"			=> "varchar(10) DEFAULT 'publie' NOT NULL",
			"taille"			=> "bigint(20)",
			"largeur"			=> "int(11)",
			"hauteur"			=> "int(11)",
			"extension"			=> "varchar(10) DEFAULT '' NOT NULL",
			"snv_format"		=> "varchar(12) DEFAULT '' NOT NULL",
			"snv_def"			=> "varchar(12) DEFAULT '' NOT NULL",
		],
		'key' => [
			"PRIMARY KEY"		=> "id_snimage",
			"KEY id_document" 	=> "id_document",
		],
		'join' => [
			"id_document" 		=> "id_document",
			"id_snimage" 		=> "id_snimage",
			"extension" 		=> "extension"
		],
		'parent' => ['type' => 'document', 'champ' => 'id_document'],
		'champs_editables' 		=> ["date","maj","statut","taille","largeur","hauteur","extension","snv_format","snv_def"],
		'date' 					=> "date",
	];
	$tables['spip_articles']['champs_editables'][] = "id_document";
	$tables['spip_auteurs']['champs_editables'][] = "id_document";
	$tables['spip_groupes_mots']['champs_editables'][] = "id_document";
	$tables['spip_mots']['champs_editables'][] = "id_document";
	$tables['spip_rubriques']['champs_editables'][] = "id_document";
	$tables['spip_documents']['tables_jointures'][] = "snimages";
	return $tables;
}
