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

if (!defined("_ECRIRE_INC_VERSION")) return;

function formulaires_recup_snimages_charger_dist(){
	return [];
}

function formulaires_recup_snimages_verifier_dist(){
	return [];
}

function formulaires_recup_snimages_traiter_dist(){
	include_spip('inc/snimages');
	$res = snimages_recup_installation();
	if($res === true){
		$retour = ['message_ok' => _T('snimage:config_snimages_recup_message_ok')];
	} else if(is_array($res)) {
		$retour = ['message_erreur' => _T('snimage:config_snimages_recup_message_erreurs').'<br>'.implode('<br>',$res)];
	} else {
		$retour = ['message_erreur' => _T('snimage:config_snimages_recup_message_erreurs')];
	}
	return $retour;
}
