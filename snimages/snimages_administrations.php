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

function snimages_upgrade($nom_meta_base_version, $version_cible){
	$maj = [];
	$maj['create'] = [
		['maj_tables', [
			"spip_snimages",
		]],
		['sql_alter', "table spip_articles ADD id_document bigint(21) DEFAULT '0' NOT NULL"],
		['sql_alter', "table spip_auteurs ADD id_document bigint(21) DEFAULT '0' NOT NULL"],
		['sql_alter', "table spip_groupes_mots ADD id_document bigint(21) DEFAULT '0' NOT NULL"],
		['sql_alter', "table spip_mots ADD id_document bigint(21) DEFAULT '0' NOT NULL"],
		['sql_alter', "table spip_rubriques ADD id_document bigint(21) DEFAULT '0' NOT NULL"],
		['sql_alter', "table spip_documents_liens ADD snlien varchar(10) NOT NULL"],
	];
	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}
function snimages_vider_tables($nom_meta_base_version) {
	sql_alter("table spip_articles DROP id_document");
	sql_alter("table spip_auteurs DROP id_document");
	sql_alter("table spip_groupes_mots DROP id_document");
	sql_alter("table spip_mots DROP id_document");
	sql_alter("table spip_rubriques DROP id_document");
	sql_alter("table spip_documents_liens DROP snlien");
	sql_drop_table("spip_snimages");
	include_spip('inc/snimages');
	snimages_recup_desinstallation();
	effacer_meta($nom_meta_base_version);
}
