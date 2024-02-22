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

function formulaires_generer_image_charger_dist($affichage, $retour=''){
	 //si modif quelconque dans les saisies penser a activer la ligne ci-dessous pour reset les valeurs en session !
    //session_set('sn_form_generer_image_contexte',NULL);
    $liste_formats = snimages_lister_image_formats();
    $liste_habillages = snimages_lister_image_habillages();
    $contexte = [
        'gi_id_document'=>'',
        'sn_image_format'=>'img',
        'sn_image_habillage'=>'sans',
        'sn_image_active'=>'on',
        'sn_image_legender'=>'on',
        'sn_image_liste_formats'=> $liste_formats,
        'sn_image_liste_habillages'=> $liste_habillages,
        'affichage' => $affichage
    ];
    if($session_data = session_get('sn_form_generer_image_contexte')){
		if(is_array($session_data)){
			foreach($session_data as $cle => $val){
				$contexte[$cle] = $val;
			}
		}
	}
   return $contexte;
}

function formulaires_generer_image_verifier_dist($affichage, $retour=''){
    include_spip('inc/sn_regexr');
    $erreurs = [];
    $liste_formats = snimages_lister_image_formats();
    $liste_habillages = snimages_lister_image_habillages();
	if(!preg_match(sn_regex_numid(),_request('gi_id_document'))){
		$erreurs['gi_id_document'] = _T('sncore:regex_numid');
	} elseif(!sql_fetsel('id_document','spip_documents','id_document=' . sql_quote(_request('gi_id_document')))){
        $erreurs['gi_id_document'] = _T('sncore:erreur_objet_inexistant');
    }
    if (_request('sn_image_format')){
    	if(!isset($liste_formats[_request('sn_image_format')])){
    		$erreurs['sn_image_format'] = _T('sncore:regex_gen');
    	}
    }
    if (_request('sn_image_habillage')){
    	if(!isset($liste_habillages[_request('sn_image_habillage')])){
    		$erreurs['sn_image_habillage'] = _T('sncore:regex_gen');
    	}
    }
	if(_request('sn_image_active')){
		if(sn_verif_bool_on(_request('sn_image_active')) === true){
		} else {
			$erreurs['sn_image_active'] = _T('sncore:regex_gen');
		}
	}
	if(_request('sn_image_legender')){
		if(sn_verif_bool_on(_request('sn_image_legender')) === true){
		} else {
			$erreurs['sn_image_legender'] = _T('sncore:regex_gen');
		}
	}
	return $erreurs;
}

function formulaires_generer_image_traiter_dist($affichage, $retour=''){

	if ($retour) {
		refuser_traiter_formulaire_ajax();
	}

   $modele_contenu = "1";
   $modele_fichier = "snimg";
   $modele_format = "img";

   if(_request('gi_id_document')){
   	$modele_contenu = _request('gi_id_document');
   }

	if(_request('sn_image_format')){
		if(_request('sn_image_format') == 'car'){
			$modele_fichier = "snvmg"; $modele_format = "car";
		}
		if(_request('sn_image_format') == 'cov'){
			$modele_fichier = "snvmg"; $modele_format = "cov";
		}
	}

	// Si image est legende et active par defaut. Si non inactive par defaut.
	if($modele_format === "img"){
		if(!_request('sn_image_legender')){
			$modele_contenu .= '|legender=non';
		}
		if(!_request('sn_image_active')){
			$modele_contenu .= '|active=non';
		}
	} else {
		$modele_contenu .= '|format=' . $modele_format;
		if(_request('sn_image_legender')){
			if(_request('sn_image_legender') === 'on'){
				$modele_contenu .= '|legender=oui';
			}
		}
		if(_request('sn_image_active')){
			if(_request('sn_image_active') === 'on'){
				$modele_contenu .= '|active=oui';
			}
		}
	}

	if(_request('sn_image_habillage')){
		if(_request('sn_image_habillage') === 'sans'){
		} else {
			$modele_contenu .= '|pos=' . _request('sn_image_habillage');
		}
	}

	$modele = '<' . $modele_fichier . $modele_contenu . '>';

	$contexte = [
		'gi_id_document' => _request('gi_id_document'),
		'sn_image_format' => _request('sn_image_format'),
		'sn_image_habillage' => _request('sn_image_habillage'),
		'sn_image_active' => _request('sn_image_active'),
		'sn_image_legender' => _request('sn_image_legender')
	];
	session_set('sn_form_generer_image_contexte',$contexte);

	$retour = ['editable' => true, 'message_ok' => _T('snimage:generer_image_message_ok') . '<span class="sncolonne-code-genere">' . htmlspecialchars($modele) . '</span>'];
    
	return $retour;
}
