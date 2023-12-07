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
 * Gestion des snimages et de leur emplacement sur le serveur
 *
 * @plugin SnImages
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Créer un sous-répertoire IMG/snimages/$format tel que IMG/snimages/ecran
 *
 * @uses sous_repertoire()
 * @uses _DIR_IMG
 * @uses verifier_htaccess()
 *
 * @param string $ext
 * @param string $vformat (snimage|img_hd|min_m...)
 * @return string
 */
function creer_repertoire_snimages($vformat,$vdef) {
	include_spip('inc/sn_const');
	$sn_const_dir_snimages = sn_global_dir_snimages();
	$suprep = sous_repertoire(_DIR_IMG, $sn_const_dir_snimages);
	$rep_nom = $vformat.'_'.$vdef;
	$rep = sous_repertoire($suprep, $rep_nom);
	if (!$rep) {
		spip_log("creer_repertoire_snimages '$rep' interdit");
		exit;
	}
	// Cette variable de configuration peut etre posee par un plugin
	// par exemple acces_restreint
	if (isset($GLOBALS['meta']["creer_htaccess"]) and $GLOBALS['meta']["creer_htaccess"] == 'oui') {
		include_spip('inc/acces');
		verifier_htaccess($rep);
	}
	return $rep;
}

/**
 * Copier une snimage `$source` vers un dossier `snimages/$vformat_$vdef/$orig.$ext`
 * ET ECRASER si un fichier de même nom existe déjà
 *
 * @param string $ext
 * @param string $orig
 * @param string $source
 * @param string $vformat (img|img_hd|ban... par defaut img)
 * @return bool|mixed|string
 */
function copier_snimage($ext, $orig, $source, $vformat, $vdef) {
	$orig = preg_replace(',\.\.+,', '.', $orig); // pas de .. dans le nom du doc
	$rep = creer_repertoire_snimages($vformat, $vdef);
	$dest = preg_replace("/[^.=\w-]+/", "_",
		translitteration(preg_replace("/\.([^.]+)$/", "",
			preg_replace("/<[^>]*>/", '', basename($orig)))));

	// ne pas accepter de noms de la forme -r90.jpg qui sont reserves
	// pour les images transformees par rotation (action/documenter)
	$dest = preg_replace(',-r(90|180|270)$,', '', $dest);
	
	$newFile = $rep . $dest . '.' . $ext;
	
	if(file_exists($newFile)){
		copy($newFile, creer_repertoire_snimages('archives','') . $vformat . '_' . $vdef . '_' . $dest . '.' . $ext);
	}
	
	return deplacer_fichier_upload($source, $newFile);
}

/**
 * Renvoie le chemin d'une vignette snimage IMG/snimages/$vformat_$vdef
 *
 * @uses _DIR_IMG
 *
 * @param string $vformat (img|img_hd|ban... par defaut img)
 * @return string
 */
function repertoire_snimage($vformat,$vdef) {
	include_spip('inc/sn_const');
	$sn_const_dir_snimages = sn_global_dir_snimages();
	$chemin = _DIR_IMG.$sn_const_dir_snimages.'/'.$vformat.'_'.$vdef.'/';
	return $chemin;
}

/**
 * Transforme un nom de fichier "mon_super_fichier" en titre "mon super fichier"
 *
 * @param string $fichier nom de fichier a transformer
 * @return string
 */
function sn_trans_fichier_en_titre($fichier){
	$mots = explode('_',$fichier);
	$titre = implode(' ',$mots);
	return $titre;
}

function snimages_recup_installation(){
	include_spip('inc/sn_const');
	$sn_const_dir_snimages = sn_global_dir_snimages();
	if(dir(_DIR_IMG.'SAUVEGARDE_'.$sn_const_dir_snimages)){
		$res = snimages_recup_snimages();
		return $res;
	}
	return false;
}

function snimages_recup_desinstallation(){
	include_spip('inc/sn_const');
	$sn_const_dir_snimages = sn_global_dir_snimages();
	if(dir(_DIR_IMG.'SAUVEGARDE_'.$sn_const_dir_snimages)){
		$nom_random = _DIR_IMG.'SAUVEGARDE_'.$sn_const_dir_snimages . rand(0,9999);
		rename(_DIR_IMG.'SAUVEGARDE_'.$sn_const_dir_snimages, $nom_random);
	}
	if(dir(_DIR_IMG.$sn_const_dir_snimages)){
		rename(_DIR_IMG.$sn_const_dir_snimages, _DIR_IMG.'SAUVEGARDE_'.$sn_const_dir_snimages);
		return true;
	}
	return false;
}

function snimages_recup_snimages(){
	include_spip('inc/sn_const');
	$sn_const_dir_snimages = sn_global_dir_snimages();
	if(dir(_DIR_IMG.'SAUVEGARDE_'.$sn_const_dir_snimages)){
		include_spip('action/ajouter_snimages');
		include_spip('action/editer_snimage');
		$regex_fichier = '#^([0-9]{1,21})\.([a-z0-9_]{1,4})$#';
		$regex_repertoire = '#^([a-z0-9_]{1,3})_([a-z0-9_]{1,2})$#';
		$erreurs = [];
		$update = [];
		$vformat = '';
		$vdef = '';
		$repd = '';
		$champs = null;
		$id_snimage = 0;
		$infos = null;
		$rep = _DIR_IMG.'SAUVEGARDE_'.$sn_const_dir_snimages;
		$scan1 = scandir($rep);
		foreach($scan1 as $d1 => $f1){
			if(($f1 == '.')||($f1 == '..')||(!is_dir($rep . '/' . $f1))){
			} else {
				$vformat = '';
				$vdef = '';
				if(preg_match($regex_repertoire, $f1, $m)){
					$vformat = $m[1];
					$vdef = $m[2];
					$scan2 = scandir($rep.'/'.$f1);
					$repd = creer_repertoire_snimages($vformat,$vdef);
					foreach($scan2 as $d2 => $f2){
						if(($f2 == '.')||($f2 == '..')||($f2 == '.ok')||($f2 == '.htaccess')||(is_dir($rep . '/' . $f1 . '/' . $f2))){
						} else {
							if(preg_match($regex_fichier, $f2, $m2)){
								//echo 'ajouter image '.$vformat.' '.$vdef.' '.$m2[1].' '.$rep.'/'.$f1.'/'.$f2.'<br>';
								$champs = [
									'snv_format' => $vformat,
									'snv_def' => $vdef,
									'id_document' => $m2[1],
									'extension' => $m2[2],
								];
								$infos = renseigner_taille_dimension_image($rep . '/' . $f1 . '/' . $f2, $m2[2]);
								if (is_string($champs)) {
									$erreurs[] = _T('img size error :' . $f1 .'/' . $f2);
								} else {
									$champs['hauteur'] = $infos['hauteur'];
									$champs['largeur'] = $infos['largeur'];
									$champs['taille'] = $infos['taille'];
									$id_snimage = snimage_inserer();
									if (!$id_snimage) {
										$erreurs[] = _T('snimage:erreur_bdd_insertion', ['fichier' => "<em>" . $f1 . '/' . $f2 . "</em>"]);
									} else {
										copy($rep . '/' . $f1 . '/' . $f2, $repd . $f2);
										snimage_modifier($id_snimage, $champs);
									}
								}
							} else { $erreurs[] = 'file name error :' . $f2; }
						}
					}
				} else {
					$erreurs[] = 'dir name error :'. $f1;
				}
			}
		}
		if(count($erreurs) > 0){
			return $erreurs;
		} else {
			return true;
		}
	}
	return false;
}
