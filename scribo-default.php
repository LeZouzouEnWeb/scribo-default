<?php
/*
Plugin Name: scribo default
Description: Plugin to choose image sizes and convert uploaded images to multiple formats.
Version: 1.0
Author: Eric
*/

// Fonction pour ajouter une page de sous-menu dans l'interface d'administration
function administration_add_admin_page()
{
    // Ajoute une page de sous-menu sous le menu principal des options
    add_submenu_page(
        'options-general.php', // Le slug de la page parent (Options générales)
        'Mes options', // Le titre de la page dans le menu
        'Mes réglages', // Le texte à afficher dans le sous-menu
        'manage_options', // La capacité requise pour accéder à cette page
        'administration', // Le slug de la page (pour les URL)
        'administration_page' // La fonction qui affichera le contenu de la page
    );
}


// Fonction pour afficher le contenu de la page d'administration
function administration_page()
{
    // Tableau associatif contenant les couleurs disponibles pour le fond du site
    $couleurs_disponibles = array(
        'ffffff' => 'Blanc', // Code couleur pour le blanc
        '000000' => 'Noir', // Code couleur pour le noir
        'ff0000' => 'Rouge', // Code couleur pour le rouge
        '00ff00' => 'Vert', // Code couleur pour le vert
        '0000ff' => 'Bleu' // Code couleur pour le bleu
    );

    // Vérifie si le formulaire a été soumis
    if (isset($_POST['submit'])) {
        // Met à jour l'option 'couleur_fond_site' avec la couleur choisie
        update_option('couleur_fond_site', $_POST['fond_couleur']);
    }

    // Récupère la couleur actuelle du fond du site
    $couleur_actuelle = get_option('couleur_fond_site');
?>
    <div class="wrap">
        <h1>Mes options</h1>
        <form method="post" action="">
            <label for="fond_couleur">Choisissez une couleur : </label>
            <select id="fond_couleur" name="fond_couleur">
                <?php
                // Parcourt les couleurs disponibles pour générer les options du menu déroulant
                foreach ($couleurs_disponibles as $valeur => $libelle) { ?>
                    <option value="<?php echo $valeur; ?>" <?php selected($couleur_actuelle, $valeur); ?>>
                        <?php echo $libelle; // Affiche le libellé de la couleur
                        ?>
                    </option>
                <?php } ?>
            </select>
            <input type="submit" name="submit" class="button button-primary" value="Enregistrer" />
        </form>
    </div>
    <?php
}

// Action pour ajouter le menu d'administration lors du chargement de l'administration
add_action('admin_menu', 'administration_add_admin_page');


function cookinfamily_add_admin_pages()
{
    add_menu_page(__('Paramètres du thème CookInFamily', 'cookinfamily'), __('CookInFamily', 'cookinfamily'), 'manage_options', 'cookinfamily-settings', 'cookinfamily_theme_settings', 'dashicons-admin-settings', 60);
}


function cookinfamily_settings_register()
{
    // register_setting('cookinfamily_settings_fields', 'cookinfamily_settings_field', 'cookinfamily_settings_fields_validate');
    register_setting('cookinfamily_settings_fields', 'cookinfamily_settings_field', '');
    add_settings_section('cookinfamily_settings_section', __('Paramètres', 'cookinfamily'), 'cookinfamily_settings_section_introduction', 'cookinfamily_settings_section');
    add_settings_field('cookinfamily_settings_field_introduction', __('Introduction', 'cookinfamily'), 'cookinfamily_settings_field_introduction_output', 'cookinfamily_settings_section', 'cookinfamily_settings_section');
}

function cookinfamily_settings_section_introduction()
{
    echo __('Paramétrez les différentes options de votre thème CookInFamily.', 'cookinfamily');
}

function cookinfamily_settings_field_introduction_output()
{
    $values = get_option('cookinfamily_settings_field', ["Valeur 1", "Valeur 2"]);

    // Affichage des champs pour chaque taille
    $i = 0;
    foreach ($values as $index => $value) {
    ?>
        <div class="image-size-row" data-index="<?php echo $index; ?>">
            <input name="cookinfamily_settings_field[]" type="text" value="<?php echo esc_attr($value); ?>" />
            <button type="button" class="remove-size-button">Supprimer</button>
        </div>
<?php
    }
}
add_action('admin_init', 'cookinfamily_settings_register');


function cookinfamily_settings_fields_validate($inputs)
{
    if (!empty($_POST)) {
        if (!empty($_POST['cookinfamily_settings_field'])) {
            update_option('cookinfamily_settings_field', $_POST['cookinfamily_settings_field']);
        }
        if (!empty($_POST['cookinfamily_settings_field_phone_number'])) {
            update_option('cookinfamily_settings_field_phone_number', $_POST['cookinfamily_settings_field_phone_number']);
        }
        if (!empty($_POST['cookinfamily_settings_field_email'])) {
            update_option('cookinfamily_settings_field_email', $_POST['cookinfamily_settings_field_email']);
        }
    }
    return $inputs;
}
add_action('admin_init', 'cookinfamily_settings_register');


function cookinfamily_theme_settings()
{

    echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';

    echo '<form action="options.php" method="post" name="cookinfamily_settings">';

    echo '<div>';

    settings_fields('cookinfamily_settings_fields');

    do_settings_sections('cookinfamily_settings_section');

    submit_button();

    echo '</div>';

    echo '</form>';
}
add_action('admin_menu', 'cookinfamily_add_admin_pages', 10);
