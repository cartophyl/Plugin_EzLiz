<?php
/*
Plugin Name: EzLiz
Plugin URI: https://www.3liz.com/
Description: Permet de faciliter l'insertion d'iframe Lizmap sur Wordpress
Author: Jordan PETER
Version: 1.0.0
Author URI: https://www.linkedin.com/in/jordan-peter-224b78153/
*/



//Création du menu administrateur du plugin
add_action("admin_menu","Create_menu");

function Create_menu(){

add_menu_page("EzLiz", "EzLiz", 4, "Page1", "Page2","http://localhost/wordpress/wp-content/plugins/mon_premier_plugin/images/icon.png");
}

//page du tutoriel
function Page2(){

  echo ('<html>
<head>
<title>EzLIz</title>
</head>
<body>
<div>

	<img id="logo" src="http://localhost/wordpress/wp-content/plugins/mon_premier_plugin/images/icon2.png" alt="3liz" width="400" height="400" align="center" >



	<p id="presentation"> Bienvenue sur le widget EzLiz vous permettant d\'insérer plus facilement des cartes Lizmap sur vos sites internet conçu avec Wordpress </p>
</div>

	<h1 style="text-decoration: underline;" >Tutoriel</h1>

  <h2>Carte dans la structure </h2>

  <div>
    <p id="tuto" > Si vous souhaitez insérer une carte en bas de page ou sur une barre latérale, allez directement dans vos widgets et recherchez le widget "EzLiz" dans la liste des widgets. </p>

    <p> Une fois que cela est fait cliquez dessus pour choisir son champ d\'application ou faites le glisser dans le champ de votre choix. l\'étape suivante consiste à remplir les champs de saisies.</p>
    <p> Tout d\'abord entrez le titre de votre choix et ensuite entrez l\'iframe de votre carte Lizmap que vous pouvez retrouver en suivant les instructions suivantes: </p>

	<p><li>Menu latéral de Lizmap </li></p>
	<p><li> Permalien</li></p>
	<p><li> Onglet intégrer </li></p>

    <p>
    Une fois vos préférences complétées copier le lien donné tout en bas et collez-le dans le deuxième champ (là où c\'est écrit: "Entrez votre iframe ici").
	Sauvegardez en faisant "enregistré" puis "terminé" et voilà votre carte est visible maintenant. </p>



    <h2 id="methode2" >Carte sur la page </h2>

    <p id="tuto" > Si vous souhaitez insérer une carte dans une page, allez directement sur votre page et recherchez le bloc "HTML personnalisé" dans la liste des blocs . </p>
    <p> Une fois que cela est fait copier l\'iframe de votre carte Lizmap que vous pouvez retrouver en suivant les instructions suivantes: </p>

	<p><li>Menu latéral de Lizmap </li></p>
	<p><li> Permalien</li></p>
	<p><li> Onglet intégrer </li></p>

    <p>Une fois vos préférences complétées copier le lien donné tout en bas et collez-le dans le champ de saisi proposé. Puis cliquez sur "mise à jour" en haut à droite pour mettre à jour la page.</p>

    <p id="fin"> Pour en savoir plus sur 3Liz vous pouvez cliquer sur le lien ci-dessous </p>
    <a id="about" href="https://www.3liz.com/">À propos de 3Liz</a>

</div>







<style>
body {
  background-color: lightblue;
  line-height: 3em;
}


#logo {
margin-left: 37%;

}

#presentation {
margin-top: 5%;

}

#tuto {
margin-top: 5%;

}


h1 {
  color: white;
  text-align: center;
  margin-left: 44.5%;
  width: 13%;
  height: 10%;
  border-radius: 25px;
  background: #9E9E9E;
  margin-top: 5%;
  line-height: 2.5em;

}

p {
  font-family: verdana;
  font-size: 20px;
  text-align: center;
}

li {

  font-family: verdana;
  font-size: 20px;
  text-align: center;

}

h2{

  text-align: center;
  color: white;
  width: 15%;
  height: 4%;
  border-radius: 25px;
  background: #7CB342;
  margin-top: 7%;
  line-height: 1.5em;

}



#methode2 {

  height: 4%;

}


#about {
  margin-left: 47%;
}

#fin{
margin-top: 5%;
margin-bottom: 5%%;

}


</style>
</body>
</html>');
}


function Lizmap_register_widget() {
register_widget( 'Lizmap_widget' );
}
add_action( 'widgets_init', 'Lizmap_register_widget' );



//Création du widget

class Lizmap_widget extends WP_Widget {

//Regarde si le widget a été enregistré ou pas //protected = visible par les classes courrantes et les classes parents
protected $enregistrer = false;

// instance par défaut

protected $default_instance = array(
  'title'   => '',
  'content' => '',
);

//Constructeur du widget


public function __construct() {
  $widget_ops  = array(
    'classname'                   => 'EzLiz',
    'description'                 => __( 'Insertion de vos map Lizmap en tant que widget' ),
    'customize_selective_refresh' => true,
  );
  $control_ops = array(
    'width'  => 400,
    'height' => 350,
  );
  parent::__construct( 'Lizmap_widget_domain', __( 'EzLiz' ), $widget_ops, $control_ops );
}

//Ajoutez des hooks permettant d'avoir plus de fonctionnalité (mettre les actifs en file d'attente lors de l'enregistrement de toutes les instances de widget de cette classe de widget)

public function _register_one( $number = -1 ) {
  parent::_register_one( $number );
  if ( $this->enregistrer ) {
    return;
  }
  $this->enregistrer = true;

/* Systèmes d'ergonomiques de Wordpress basé sur le widget html custom*/

  //ajoute du code en plus au script déjà enregistré et fais appel à la base de wordpress (baser sur le widget html custom)
  wp_add_inline_script( 'custom-html-widgets', sprintf( 'wp.customHtmlWidgets.idBases.push( %s );', wp_json_encode( $this->id_base ) ) );

  //admin_print_scripts-widgets.php et la méthode WP_Customize_Widgets::print_scripts()sont des ancrages qui permettent des scripts custom provenant des plugins
  add_action( 'admin_print_scripts-widgets.php', array( $this, 'enqueue_admin_scripts' ) );

  //(pour le footer) admin_footer-widgets.php et la méthode WP_Customize_Widgets::print_footer_scripts()sont des ancrages qui permettent des scripts custom provenant des plugins
  add_action( 'admin_footer-widgets.php', array( 'WP_Widget_Custom_HTML', 'render_control_template_scripts' ) );


}


 //Filtre les attributs de shortcode de la galerie.

 /*Empêche toutes les pièces jointes d'un site d'être affichées dans une galerie qui est affichée sur un
 template non singulier ou un contexte $post qui n'est pas disponible.*/


public function _filter_gallery_shortcode_attrs( $attrs ) {
  if ( ! is_singular() && empty( $attrs['id'] ) && empty( $attrs['include'] ) ) {
    $attrs['id'] = -1;
  }
  return $attrs;
}


// mise en forme du contenu de la sortie du widget (en cours)


public function widget( $args, $instance ) {
  global $post;


  //Remplacez le global $post afin que les filtres (et les shortcodes) s'appliquent dans le contexte présent (local).
  $original_post = $post;
  if ( is_singular() ) {
    //Etre sûr que $post est toujours l'objet interrogé sur les requêtes singulières (pas d'une autre sous-requête) qui serait passer outre le global $post.
    $post = get_queried_object();
  } else {
    //Mets à null le global $post pendant le rendu du widget pour empêcher les shortcodes de s'exécuter sur les requêtes d'archive.
    $post = null;
  }

  //Empêche le vidage de toutes les pièces jointes de la bibliothèque multimédia.
  add_filter( 'shortcode_atts_gallery', array( $this, '_filter_gallery_shortcode_attrs' ) );

  $instance = array_merge( $this->default_instance, $instance );

  $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

  //Préparation des données d'instance qui est similaire à un widget Texte normal.
  $simulated_text_widget_instance = array_merge(
    $instance,
    array(
      'text'   => isset( $instance['content'] ) ? $instance['content'] : '',
      'filter' => false, // car wpautop (remplacement double saut de ligne) n'est pas appliqué
      'visual' => false, // car il n'a pas été crée dans TinyMCE
    )
  );
  unset( $simulated_text_widget_instance['content'] ); // à été déplacé vers les propriétés 'text'

  /** Voir wp-includes/widgets/class-wp-widget-text.php pour la documentation si nécéssaire */
  $content = apply_filters( 'widget_text', $instance['content'], $simulated_text_widget_instance, $this );

  //Ajoute des relations noreferrer et noopener, sans dupliquer les valeurs, à tous les éléments HTML <a> qui ont une cible (attribut target).


  //Filtre le contenu du widget EzLiz
  $content = apply_filters( 'widget_EzLiz_content', $content, $instance, $this );

  // Restaure le $post global.
  $post = $original_post;
  remove_filter( 'shortcode_atts_gallery', array( $this, '_filter_gallery_shortcode_attrs' ) );


  //Injectez le nom de la classe du conteneur du widget 'Texte' à côté du nom de la classe de ce widget pour la compatibilité de style des thèmes.
  $args['before_widget'] = preg_replace( '/(?<=\sclass=["\'])/', 'widget_text ', $args['before_widget'] );

  echo $args['before_widget'];
  if ( ! empty( $title ) ) {
    echo $args['before_title'] . $title . $args['after_title'];
  }
  echo '<div class="textwidget EzLiz-widget">'; //La classe 'textwidget' est pour la compatibilité de style des thèmes.
  echo $content;
  echo '</div>';
  echo $args['after_widget'];
}


//Récupère et gère les paramètres de mise à jour de l'instance actuelle du widget EzLiz.

public function update( $new_instance, $old_instance ) {
  $instance          = array_merge( $this->default_instance, $old_instance );
  $instance['title'] = sanitize_text_field( $new_instance['title'] );
  if ( current_user_can( 'unfiltered_html' ) ) {
    $instance['content'] = $new_instance['content'];
  } else {
    $instance['content'] = wp_kses_post( $new_instance['content'] );
  }
  return $instance;
}


//Charge les scripts et les styles requis pour le contrôle de widget (wordpress function).

public function enqueue_admin_scripts() {
  $settings = wp_enqueue_code_editor(
    array(
      'type'       => 'text/html',
      'codemirror' => array(
        'indentUnit' => 2,
        'tabSize'    => 2,
      ),
    )
  );

//mettre le script en file d'attente (enregistre le script) (voir cahier pour plus de précision)

  wp_enqueue_script( 'custom-html-widgets' );
  if ( empty( $settings ) ) {
    $settings = array(
      'disabled' => true,
    );
  }

//notifie des erreurs de saisi

  wp_add_inline_script( 'custom-html-widgets', sprintf( 'wp.customHtmlWidgets.init( %s );', wp_json_encode( $settings ) ), 'after' );

  $l10n = array(
    'errorNotice' => array(
      /* traducteur: %d: nombre d'erreurs */
      'singular' => _n( 'il y a %d d \'erreur qui doit être réparé avant de pouvoir sauvegarder.', 'il y a %d d \'erreurs qui doit être réparé avant de pouvoir sauvegarder.', 1 ),
      /* traducteur: %d: nombre d'erreurs */
      'plural'   => _n( 'il y a %d d \'erreur qui doit être réparé avant de pouvoir sauvegarder.', 'il y a %d d \'erreurs qui doit être réparé avant de pouvoir sauvegarder.', 2 ), // @todo Cela fait défaut, car certaines langues ont une double forme dédiée. Pour une gestion correcte des pluriels dans JS, voir # 20491.
    ),
  );
  wp_add_inline_script( 'custom-html-widgets', sprintf( 'jQuery.extend( wp.customHtmlWidgets.l10n, %s );', wp_json_encode( $l10n ) ), 'after' );
  }


//Génère le formulaire des paramètres du widget EzLiz.

public function form( $instance ) {
  $instance = wp_parse_args( (array) $instance, $this->default_instance );
  ?>
  <input id="<?php echo $this->get_field_id( 'title' );?>" name="<?php echo $this->get_field_name( 'title' );?>" class="title sync-input" type="hidden" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
  <textarea id="<?php echo $this->get_field_id( 'content' );?>" name="<?php echo $this->get_field_name( 'content' );?>" class="content sync-input" hidden><?php echo esc_textarea( $instance['content'] ); ?>Entrez votre iframe ici</textarea>
  <?php
}



//Rendu des scripts du modèle de formulaire (permet d'avoir la structure du formulaire HTML)
//après la ligne 388 gère les balises HTML custom autorisées

public static function render_control_template_scripts() {

  ?><script type="text/html" id="tmpl-widget-custom-html-control-fields">
    <# var elementIdPrefix = 'el' + String( Math.random() ).replace( /\D/g, '' ) + '_' #>
    <p>
      <label for="{{ elementIdPrefix }}title"><?php esc_html_e( 'Title:' );?></label>
      <input id="{{ elementIdPrefix }}title" type="text" class="widefat title">
    </p>

    <p>
      <label for="{{ elementIdPrefix }}content" id="{{ elementIdPrefix }}content-label"><?php esc_html_e( 'Content:' ); ?></label>
      <textarea id="{{ elementIdPrefix }}content" class="widefat code content" rows="16" cols="20"></textarea>
    </p>

    <?php if ( ! current_user_can( 'unfiltered_html' ) ) :?>
      <?php
      $probably_unsafe_html = array( 'script', 'iframe', 'form', 'input', 'style' );
      $allowed_html         = wp_kses_allowed_html( 'post' );
      $disallowed_html      = array_diff( $probably_unsafe_html, array_keys( $allowed_html ) );
      ?>
      <?php if ( ! empty( $disallowed_html ) ) :?>
        <# if ( data.codeEditorDisabled ) { #>
          <p>
            <?php _e( 'Certaines balises ne sont pas permises, incluant:' );?>
            <code><?php echo join( '</code>, <code>', $disallowed_html ); ?></code>
          </p>
        <# } #>
      <?php endif;?>
    <?php endif;?>

    <div class="code-editor-error-container"></div>
  </script>
 <?php }
}?>
