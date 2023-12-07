<?php
// This is a SPIP langague file -- Ceci est un fichier de langue de SPIP

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

$GLOBALS[$GLOBALS['idx_lang']] = [
	// SPIP
	'icone_creer_snimage' => 'Ajouter une SNimage',
	'icone_modifier_snimage' => 'Modifier cette SNimage',
	'info_1_snimage' => '1 SNimage',
	'info_aucun_snimage' => 'Aucune SNimage',
	'info_aucune_snimage' => 'Aucune SNimage',
	'info_nb_snimages' => '@nb@ SNimages',
	'menu_texte_snimages' => 'SN Images',
	'texte_ajouter_snimage' => 'Affilier des SNimages',
	'titre_logo_snimage' => 'Logo SNimage',
	'titre_snimage' => 'SNimage',
	'titre_snimages' => 'SNimages',

	// C
	'colonne_gauche_titre' => 'Générateur de blocs enrichis',
	'colonne_gauche_explication' => 'Enrichissez vos mises en page avec les fonctionnalités intuitives de la SN suite.',
	'config_snimages_recup_bouton' => 'Lancer la récupération automatique',
	'config_snimages_recup_explication' => 'Rechargez ici en un clic tous les fichiers de déclinaisons d\'images ayant pu disparaître suite à une réinstallation du module ou de SPIP',
	'config_snimages_recup_message_ok' => 'La récupération automatique a réussi : les vignettes ont été chargées et reliées à leurs images parentes. Veuillez supprimer à la main le répertoire /IMG/SAUVEGARDE_snimages pour finaliser la récupération.',
	'config_snimages_recup_message_erreurs' => 'La récupération automatique n\'a pas abouti.',
	'config_snimages_recup_titre' => 'Récupération',
	'config_snimages_recup_texte' => 'Un dossier de sauvegarde des SN Images a été détecté.',
	'config_snimages_recup_texte_sinon' => 'Aucun dossier de sauvegarde des SN Images n\'a été détecté. En cas de désinstallation du module ou autre problème technique vous pourrez restaurer automatiquement ici toutes vos déclinaisons d\'images.',

	// E
	'erreur_bdd_entree_inexistante' => 'Entrée inexistante dans la base de données',
	'erreur_bdd_insertion' => 'L\'insertion en base de données a échoué',
	'erreur_copie_fichier' => 'Impossible de copier le fichier @nom@',
	'erreur_fichier_introuvable' => 'Fichier @fichier@ introuvable',
	'erreur_fichier_manquant' => 'Fichier introuvable',
	'erreur_format_inconnu' => 'Erreur : format de vignette inconnu : veuillez choisir un format connu ou en définir un nouveau dans un fichier d\'options (spip/config/mes_options.php)',
	'erreur_id_invalide' => 'Identifiant invalide : veuillez saisir le numéro d\'identification unique d\'un objet SPIP préexistant',
	'erreur_repertoire_introuvable' => 'Répertoire @repertoire@ introuvable',
	'erreur_snimage_enregistrer' => 'L\'insertion dans la base de données n\'a pas abouti',
	'erreur_snimage_copier' => 'La copie du fichier n\'a pas abouti',
	'erreur_snimage_inexistante' => 'Aucune image ne correspond à cet identifiant, veuillez choisir une image parmi celles de la bibliothèque de documents.',
	'erreur_snimage_renommer_plante' => 'Le renommage a planté au milieu. Les fichiers non affectés pourraient devenir inaccessibles : veuillez les renommer "à la main" dans le dossiers /IMG/snimages/)',
	'erreur_snimage_renommer' => 'Erreur inconnue de renommage d\'image',
	'erreur_upload_aucun_fichier' => 'Veuillez sélectionner un fichier valide à charger',
	'erreur_upload_dossier_tmp_manquant' => 'Dossier "tmp" manquant',
	'erreur_upload_ecriture_fichier' => 'Impossible d\'écrire ce fichier : vérifiez les droits d\'accès du répertoire',
	'erreur_upload_extension_interdite' => 'Le chargement de fichiers au format "@ext@" n\'est pas autorisé. Veuillez convertir votre image dans un format reconnu : par défaut jpg, png ou gif. Vous pouvez modifier les types autorisés depuis votre fichier d\'options en redéfinissant la globale "SNIMAGES_EXTENSIONS_AUTORISEES".',
	'erreur_upload_fichier_homonyme' => '[Fichier @nom_serveur@] : un fichier portant ce nom "@nom_serveur@" est déjà enregistré dans la table @table@.',
	'erreur_upload_nom_fichier' => '[Fichier @nom_serveur@] : nom du fichier invalide.',
	'erreur_upload_poids' => '[Fichier @nom_serveur@] : le poids doit être inférieur à @maxi@ Ko (ce fichier fait @actuel@ Ko).',
	'erreur_upload_taille' => '[Fichier @nom_serveur@] : @details@.',
	'erreur_upload_largeur' => 'la largeur du fichier doit être comprise entre @mini@px et @maxi@px (ce fichier fait @actuel@px)',
	'erreur_upload_limite' => 'poids de fichier maximal dépassé',
	'erreur_upload_hauteur' => 'la hauteur du fichier doit être comprise entre @mini@px et @maxi@px (ce fichier fait @actuel@px)',
	'erreur_upload_massif_aucune' => 'Aucun fichier valide n\'a été trouvé dans le répertoire "plugins/snimages/load"',
	'erreur_upload_massif_enregistrer' => 'Une ou plusieurs erreurs ont eu lieu : ',
	'erreur_upload_type_interdit' => 'Le téléchargement des fichiers du type de "@nom@" n’est pas autorisé',

	// G
	'generer_galerie_limite' => 'Limite d\'images par plage (laisser vide pour désactiver la pagination)',
	'generer_galerie_liste_images' => 'Images (identifiants numériques séparés par des virgules)',
	'generer_galerie_nb_colonnes' => 'Nombre de colonnes',
	'generer_galerie_titre' => 'Galerie d\'images',
	'generer_galerie_valider' => 'Générer',
	'generer_image_active' => 'Image cliquable/zoomable',
	'generer_image_habillage' => 'Comportement sur écran large',
	'generer_image_label_image' => 'Image',
	'generer_image_legender' => 'Image légendée',
	'generer_image_titre' => 'Image responsive',
	'generer_image_valider' => 'Générer',
	'generer_image_message_ok' => 'Copiez-collez cette expression où vous voulez',

	// I
	'info_fichier_installe_succes' => 'Fichier chargé',
	'info_sngalerie_explication' => 'Activez une vignette ci-dessous pour basculer en mode diaporama.',
	'info_utilisation_0' => 'Cette image n\'est pas encore utilisée comme image de référence',
	'info_utilisation_1' => 'Cette image est utilisée comme image de référence d\'une page',
	'info_utilisation_nb' => 'Cette image est utilisée comme image de référence de @nb@ pages',
	'info_utilisation_liens_0' => 'Cette image n\'est pas encore intégrée en noisette',
	'info_utilisation_liens_1' => 'Cette image est intégrée en noisette dans une page',
	'info_utilisation_liens_nb' => 'Cette image est intégrée en noisette dans @nb@ pages',
	'info_utilisation_sous_titre' => 'En image de référence',
	'info_utilisation_liens_sous_titre' => 'Intégrée en noisette',
	'info_utilisation_titre' => 'Utilisations SN Image',

	// S
	'snimage_def_hd' => 'haute def.',
	'snimage_def_bd' => 'basse def.',
	'snimage_format' => 'Format',
	'snimage_format_car' => 'miniature',
	'snimage_format_cov' => 'couv/portrait',
	'snimage_format_img' => 'image',
	'snimage_habillage_sans' => 'couper le texte',
	'snimage_habillage_droite' => 'habiller le texte (droite)',
	'snimage_habillage_gauche' => 'habiller le texte (gauche)',
	'snimage_label_apercu' => 'Aperçu',
	'snimage_label_doc_ref' => 'Image de référence (doc n°)',
	'snimage_label_numero' => 'N°',
	'snimage_select_alt' => 'Aucun aperçu disponible',
	'snimage_select_explication' => 'Seuls les documents "image" avec une vignette carrée sont pris en compte.',
	'snimage_select_parcourir' => 'Parcourir les images',
	'snimage_select_titre' => 'Sélection d\'image',
	'snimage_titre_doc_ref' => 'Image de référence',

	'titre_config_snimages' => 'SN Images - Récupération',

	// V
	'vignette_info_necessaire' => '(format de déclinaison nécessaire à l\'affichage responsif)',
	'vignette_legende_joindre'=> 'Restrictions : JPG, PNG ou GIF, hauteur comprise entre @hauteur_min@ et @hauteur_max@ pixels ; largeur comprise entre @largeur_min@ et @largeur_max@ pixels ; poids inférieur à @poids_max@ Ko.',
	'vignette_legende_joindre_necessaire'=> 'Ce format de déclinaison est nécessaire pour garantir l\'adaptativité du site.',
	'vignette_titre_ajouter' => 'Ajouter',
	'vignette_titre_remplacer' => 'Remplacer',

	// Z
	'zoomer_bouton_precedent' => 'Afficher l\'image précédente',
	'zoomer_bouton_suivant' => 'Afficher l\'image suivante',
	'zoomer_texte' => 'Pour quitter le diaporama, cliquez n\'importe où dans la page ou appuyez sur Entrée.',
	'zoom_bouton_texte' => 'Afficher en plein écran',
];
