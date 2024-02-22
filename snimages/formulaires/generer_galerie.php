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

function formulaires_generer_galerie_charger_dist($affichage, $retour=''){
	 //si modif quelconque dans les saisies penser a activer la ligne ci-dessous pour reset les valeurs en session !
    session_set('sn_form_generer_galerie_contexte',NULL);
    $liste_nb_colonnes = ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'];
    $contexte = [
			'bloc_classe' => '',
			'limite' => '',
			'liste_images' => '',
			'liste_nb_colonnes' =>$liste_nb_colonnes,
			'nb_colonnes' => '3',
			'affichage' => $affichage
    ];
    if($session_data = session_get('sn_form_generer_galerie_contexte')){
		if(is_array($session_data)){
			foreach($session_data as $cle => $val){
				$contexte[$cle] = $val;
			}
		}
	}
   return $contexte;
}

function formulaires_generer_galerie_verifier_dist($affichage, $retour=''){
	include_spip('inc/sn_regexr');
	$erreurs = [];
	$liste_nb_colonnes = ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'];
	if (_request('bloc_classe')){
		if(!preg_match(sn_regex_domclasses(), _request('bloc_classe'))){
			$erreurs['bloc_classe'] = _T('sncore:regex_dom_classes');
		}
	}
	if (!_request('liste_images')){
		$erreurs['liste_images'] = _T('info_obligatoire'); }
	if (_request('limite')){
		if(!preg_match(sn_regex_int(4), _request('limite'))){
			$erreurs['limite'] = _T('sncore:regex_int_nb',['nb'=>'4']);
		}
	}
	if (_request('liste_images')){
		if(!preg_match(sn_regex_liste_numids(), _request('liste_images'))){
			$erreurs['liste_images'] = _T('sncore:regex_liste_numids');
		}
	}
	if (_request('nb_colonnes')){
		if(!isset($liste_nb_colonnes[_request('nb_colonnes')])){
			$erreurs['nb_colonnes'] = _T('sncore:regex_gen');
		}
	}
	return $erreurs;
}

function formulaires_generer_galerie_traiter_dist($affichage, $retour=''){
	if ($retour) {
		refuser_traiter_formulaire_ajax();
	}
	$modele_contenu = '1';
	if(_request('bloc_classe')){
		$modele_contenu .= '|classe=' . _request('bloc_classe');
	}
	if(_request('limite')){
		$modele_contenu .= '|limite=' . _request('limite');
	}
	if(_request('liste_images')){
		$modele_contenu .= '|liste_images=' . _request('liste_images');
	}
	if(_request('nb_colonnes')){
		$modele_contenu .= '|nb_colonnes=' . _request('nb_colonnes');
	}
	$modele = '<sngalerie' . $modele_contenu . '>';
	$contexte = [
		'bloc_classe' => _request('bloc_classe'),
		'limite' => _request('limite'),
		'liste_images' => _request('liste_images'),
		'nb_colonnes' => _request('nb_colonnes')
	];
	session_set('sn_form_generer_galerie_contexte',$contexte);
	$retour = ['editable' => true, 'message_ok' => _T('snimage:generer_image_message_ok') . '<span class="sncolonne-code-genere">' . htmlspecialchars($modele) . '</span>'];
	return $retour;
}
