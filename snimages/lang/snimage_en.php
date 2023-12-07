<?php
// This is a SPIP langague file -- Ceci est un fichier de langue de SPIP
// English translation by Morgane N -- Traduction anglaise par Morgane N

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

$GLOBALS[$GLOBALS['idx_lang']] = [
	// SPIP
	'icone_creer_snimage' => 'Add SNimage',
	'icone_modifier_snimage' => 'Modify selected SNimage',
	'info_1_snimage' => '1 SNimage',
	'info_aucun_snimage' => 'No SNimage',
	'info_aucune_snimage' => 'No SNimage',
	'info_nb_snimages' => '@nb@ SNimages',
	'menu_texte_snimages' => 'SN Images',
	'texte_ajouter_snimage' => 'Affiliate SNimages',
	'titre_logo_snimage' => 'SNimage Logo',
	'titre_snimage' => 'SNimage',
	'titre_snimages' => 'SNimages',

	// C
	'colonne_gauche_titre' => 'Enriched blocks generator',
	'colonne_gauche_explication' => 'Enrich your page designs with the SN Suite\'s intuitive functionalities.',
	'config_snimages_recup_bouton' => 'Launch automatic recovery',
	'config_snimages_recup_explication' => 'Here, with one click, you can retrieve the various file format versions of your images, which could have disappeared after you reinstalled SPIP or the present module',
	'config_snimages_recup_message_ok' => 'Automatic recovery has succeed: thumbnails have been loaded and reconnected to their parent images. Please manually delete the directory /IMG/SAUVEGARDE_snimages to finalize recovery.',
	'config_snimages_recup_message_erreurs' => 'Automatic recovery has failed.',
	'config_snimages_recup_titre' => 'Recovery',
	'config_snimages_recup_texte' => 'An SN Images backup folder has been detected.',
	'config_snimages_recup_texte_sinon' => 'No SN Images backup folder has been detected. In case the module is uninstalled, or should a technical failure occur, you can restore the various file format versions of your images from here.',

	// E
	'erreur_bdd_entree_inexistante' => 'This entry does not exist in the database',
	'erreur_bdd_insertion' => 'The database input operation has failed',
	'erreur_copie_fichier' => 'Copying the file @nom@ is impossible',
	'erreur_fichier_introuvable' => '@fichier@ file not found',
	'erreur_fichier_manquant' => 'File not found',
	'erreur_format_inconnu' => 'Error: unknown thumbnail format: please pick a valid file format or add a new valid file format through an option file (spip/config/mes_options.php)',
	'erreur_id_invalide' => 'Invalid identifier: please enter the unique identification number of a preexisting SPIP object',
	'erreur_repertoire_introuvable' => '@repertoire@  directory not found',
	'erreur_snimage_enregistrer' => 'The database input operation has failed',
	'erreur_snimage_copier' => 'Your file could not be copied',
	'erreur_snimage_inexistante' => 'There\'s no image under this identifier, please choose an image from the the documents library.',
	'erreur_snimage_renommer_plante' => 'Renaming has failed. Unaltered files could become inaccessible: please rename them manually from this directory /IMG/snimages/)',
	'erreur_snimage_renommer' => 'Unknown error renaming image',
	'erreur_upload_aucun_fichier' => 'Please select a valid file to upload',
	'erreur_upload_dossier_tmp_manquant' => 'Missing "tmp" file',
	'erreur_upload_ecriture_fichier' => 'This file cannot be written to: please check directory access permissions',
	'erreur_upload_extension_interdite' => 'Uploading files in the "@ext@" format is not allowed. Please convert your image file in a valid file format: jpg, png or gif by default. You can change valid file formats from your option file by redefining the global "SNIMAGES_EXTENSIONS_AUTORISEES".',
	'erreur_upload_fichier_homonyme' => '[@nom_serveur@ file]: a file with the name "@nom_serveur@" already exists in the @table@ table.',
	'erreur_upload_nom_fichier' => '[@nom_serveur@ file]: non-valid file name.',
	'erreur_upload_poids' => '[@nom_serveur@ file]: file maximum size is @maxi@ Ko (file is currently @actuel@ Ko).',
	'erreur_upload_taille' => '[@nom_serveur@ file]: @details@.',
	'erreur_upload_largeur' => 'File width must be between @mini@px and @maxi@px (file is currently @actuel@px)',
	'erreur_upload_limite' => 'File exceeds maximum file size',
	'erreur_upload_hauteur' => 'File height must be between @mini@px and @maxi@px (file is currently @actuel@px)',
	'erreur_upload_massif_aucune' => 'No valid file was found in the "plugins/snimages/load" directory',
	'erreur_upload_massif_enregistrer' => 'One or several errors occurred: ',
	'erreur_upload_type_interdit' => 'Uploading "@nom@" type files is not allowed',

	// G
	'generer_galerie_limite' => 'Max-number of images per zone (leave empty to deactivate pagination)',
	'generer_galerie_liste_images' => 'Images (digital identifiers separated by commas)',
	'generer_galerie_nb_colonnes' => 'Number of columns',
	'generer_galerie_titre' => 'Image gallery',
	'generer_galerie_valider' => 'Generate',
	'generer_image_active' => 'Clickable/magnifiable image',
	'generer_image_habillage' => 'Behavior on a wide screen',
	'generer_image_label_image' => 'Image',
	'generer_image_legender' => 'Captioned image',
	'generer_image_titre' => 'Responsive image',
	'generer_image_valider' => 'Generate',
	'generer_image_message_ok' => 'Copy and paste this expression wherever you see fit',

	// I
	'info_fichier_installe_succes' => 'File loaded',
	'info_sngalerie_explication' => 'Activate one of the thumbnails below to switch to slideshow mode.',
	'info_utilisation_0' => 'This image is not used as a reference image yet.',
	'info_utilisation_1' => 'This image is currently used as a reference image for a page',
	'info_utilisation_nb' => 'This image is currently used as a reference image for @nb@ pages',
	'info_utilisation_liens_0' => 'This image has not been embedded in hazel yet',
	'info_utilisation_liens_1' => 'This image is currently embedded in hazel in a page',
	'info_utilisation_liens_nb' => 'This image is currently embedded in hazel in @nb@ pages',
	'info_utilisation_sous_titre' => 'As a reference image',
	'info_utilisation_liens_sous_titre' => 'Embedded in hazel',
	'info_utilisation_titre' => 'SN Image applications',

	// S
	'snimage_def_hd' => 'high res.',
	'snimage_def_bd' => 'low res.',
	'snimage_format' => 'Format',
	'snimage_format_car' => 'thumbnail',
	'snimage_format_cov' => 'cover/portrait',
	'snimage_format_img' => 'image',
	'snimage_habillage_sans' => 'cut text',
	'snimage_habillage_droite' => 'set text visual theme and skin (right)',
	'snimage_habillage_gauche' => 'set text visual theme and skin (right)',
	'snimage_label_apercu' => 'Preview',
	'snimage_label_doc_ref' => 'Reference image (file No.)',
	'snimage_label_numero' => 'No.',
	'snimage_select_alt' => 'Preview unavailable',
	'snimage_select_explication' => 'Only image files with square thumbnails will be used.',
	'snimage_select_parcourir' => 'Browse images',
	'snimage_select_titre' => 'Image selection',
	'snimage_titre_doc_ref' => 'Reference image',

	'titre_config_snimages' => 'SN Images - Recovery',

	// V
	'vignette_info_necessaire' => '(Responsive view requires a version of the image in all specified file formats)',
	'vignette_legende_joindre'=> 'Restrictions: JPG, PNG or GIF, height must be between @hauteur_min@ and @hauteur_max@ pixels ; width must be between @hauteur_min@ and @hauteur_max@ pixels ; maximum file size is @poids_max@ Ko.',
	'vignette_legende_joindre_necessaire'=> 'An image in the specified file format is required to ensure website\'s adaptability.',
	'vignette_titre_ajouter' => 'Add',
	'vignette_titre_remplacer' => 'Replace',

	// Z
	'zoomer_bouton_precedent' => 'Show previous image',
	'zoomer_bouton_suivant' => 'Show next image',
	'zoomer_texte' => 'To exit current slideshow, click anywhere on the webpage or press Return.',
	'zoom_bouton_texte' => 'Full screen',
];
