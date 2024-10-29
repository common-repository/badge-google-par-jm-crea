<?php
/**
 * Plugin Name: Badge Google+ par JM Créa
 * Plugin URI: http://www.jm-crea.com
 * Description: Intégrez votre badge Google+ sur vos pages afin que les internautes vous suivent.
 * Version: 1.2
 * Author: JM Créa
 * Author URI: JM Créa
 */
#################################### INSTALLATION DU PLUGIN

//On créé le menu
function menu_gpb() {
add_submenu_page( 'tools.php', 'Google+ Badge', 'Google+ Badge', 'manage_options', 'gpb', 'gpb' ); 
}
add_action('admin_menu', 'menu_gpb');



//On créé la table mysql
function creer_table_gpb() {
global $wpdb;
$table_gpb = $wpdb->prefix . 'gpb';
$sql = "CREATE TABLE $table_gpb (
id_gpb int(11) NOT NULL AUTO_INCREMENT,
url text DEFAULT NULL,
langue text DEFAULT NULL,
disposition text DEFAULT NULL,
largeur text DEFAULT NULL,
couleur text DEFAULT NULL,
photo text DEFAULT NULL,
UNIQUE KEY id (id_gpb)
);";
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
}



//On insere les infos dans la table
function insert_table_gpb() {
global $wpdb;
$table_gpb = $wpdb->prefix . 'gpb';
$wpdb->insert( 
$table_gpb, 
array('url'=>'https://plus.google.com/+Jmcrea06','langue'=>'fr','disposition'=>'portrait','largeur'=>'300','couleur'=>'clair','photo'=>'ON'), 
array('%s','%s','%s','%s','%s','%s')
);
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
}

register_activation_hook( __FILE__, 'creer_table_gpb' );
register_activation_hook( __FILE__, 'insert_table_gpb' );

####### APERCU DU BADGE
function apercu_bgp() {

global $wpdb;

$table_gpb = $wpdb->prefix . "gpb";
$req_apercu_gpb = "SELECT * FROM $table_gpb WHERE id_gpb='1'";
$req_apercu_gpb_exec  = mysql_query($req_apercu_gpb);
$apercu_gpb = mysql_fetch_assoc($req_apercu_gpb_exec);

echo "<div style='float:left; margin-left:50px; margin-top:30px;'>";
echo "
<script src='https://apis.google.com/js/platform.js' async defer>
{lang: '" . $apercu_gpb['langue']  . "'}
</script>";

if ($apercu_gpb['disposition'] == 'paysage') {	
if ($apercu_gpb['couleur'] == 'clair') {
echo "<div class='g-page' data-width='" . $apercu_gpb['largeur'] . "' data-href='" . $apercu_gpb['url'] . "' data-rel='publisher' data-layout='landscape' data-showcoverphoto='false' data-showcoverphoto='false'></div>";
}
elseif ($apercu_gpb['couleur'] == 'sombre') {	
echo "<div class='g-page' data-width='" . $apercu_gpb['largeur'] . "' data-href='" . $apercu_gpb['url'] . "' data-rel='publisher' data-theme='dark' data-layout='landscape' data-showcoverphoto='false'></div>";
}
}
if ($apercu_gpb['disposition'] == 'portrait') {	
if ($apercu_gpb['photo'] == 'ON') {	
if ($apercu_gpb['couleur'] == 'clair') {
echo "<div class='g-page' data-width='" . $apercu_gpb['largeur'] . "' data-href='" . $apercu_gpb['url'] . "' data-rel='publisher'></div>";
}
elseif ($apercu_gpb['couleur'] == 'sombre') {
echo "<div class='g-page' data-width='" . $apercu_gpb['largeur'] . "' data-href='" . $apercu_gpb['url'] . "' data-rel='publisher' data-theme='dark'></div>";
}
}
if ($apercu_gpb['photo'] == 'OFF') {	
if ($apercu_gpb['couleur'] == 'clair') {
echo "<div class='g-page' data-width='" . $apercu_gpb['largeur'] . "' data-href='" . $apercu_gpb['url'] . "' data-rel='publisher' data-showcoverphoto='false'></div>";
}
elseif ($apercu_gpb['couleur'] == 'sombre') {
echo "<div class='g-page' data-width='" . $apercu_gpb['largeur'] . "' data-href='" . $apercu_gpb['url'] . "' data-rel='publisher' data-showcoverphoto='false' data-theme='dark'></div>";
}
}
}
echo "</div>";	
}


####### MISE A JOUR DU BADGE
function gpb() {
global $wpdb;

$table_gpb = $wpdb->prefix . "gpb";
$req_gpb = "SELECT * FROM $table_gpb WHERE id_gpb='1'";
$req_gpb_exec  = mysql_query($req_gpb);
$voir_gpb = mysql_fetch_assoc($req_gpb_exec);

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

echo "<h1>Google+ badge par JM Créa</h1>
<h2>Intégrez votre badge Google +</h2>
<p>Personnalisez votre badge avec les options ci-dessous.</p>
<p>Vous pouvez appeler le badge sur vos pages avec le shortcode : <strong>[bgp_by_jm_crea]</strong></p>";

if (isset($_GET['action'])&&($_GET['action'] == 'maj-ok')) {
echo '<p>&nbsp;</p><div style="color:#02a13a;background-color:#fff;padding:4px;border:solid #000 1px; float:left;">Badge Google+ mis à jour avec succès !</div><p>&nbsp;</p>';		
}


//On met à jour la table
if (isset($_POST['maj'])) {

$url = mysql_real_escape_string($_POST['url']);
$langue = mysql_real_escape_string($_POST['langue']);
$disposition = mysql_real_escape_string($_POST['disposition']);
$largeur = mysql_real_escape_string($_POST['largeur']);
$couleur = mysql_real_escape_string($_POST['couleur']);
$photo = mysql_real_escape_string($_POST['photo']);

global $wpdb;
$table_gpb = $wpdb->prefix . "gpb";
$wpdb->query($wpdb->prepare("UPDATE $table_gpb SET url='$url',langue='$langue',disposition='$disposition',largeur='$largeur',couleur='$couleur',photo='$photo' WHERE id_gpb='1'"));
echo '<script>document.location.href="tools.php?page=gpb&action=maj-ok"</script>';
}

echo '
<div style="padding:10px; background-color:#FFF; border:solid #cfcfcf 1px; width:auto; float:left; margin-top:30px;">
<form id="form1" name="form1" method="post" action="">
<table border="0" cellspacing="8" cellpadding="0">
<tr>
<td>URL Google+ :</td>
<td><input type="text" name="url" id="url" value="' . $voir_gpb['url'] . '"></td>
</tr>

<tr>
<td>Langue :</td>
<td>
<select name="langue" id="langue">';
if ($voir_gpb['langue'] == 'af') { echo '<option value="af">Afrikaans</option>'; }
if ($voir_gpb['langue'] == 'de') { echo '<option value="de">Allemand</option>'; }
if ($voir_gpb['langue'] == 'am') { echo '<option value="am">Amharique</option>'; }
if ($voir_gpb['langue'] == 'en-GB') { echo '<option value="en-GB">Anglais</option>'; }
if ($voir_gpb['langue'] == 'ar') { echo '<option value="ar">Arabe</option>'; }
if ($voir_gpb['langue'] == 'eu') { echo '<option value="eu">Basque euskara</option>'; }
if ($voir_gpb['langue'] == 'bn') { echo '<option value="bn">Bengali</option>'; }
if ($voir_gpb['langue'] == 'bg') { echo '<option value="bg">Bulgare</option>'; }
if ($voir_gpb['langue'] == 'ca') { echo '<option value="ca">Catalan</option>'; }
if ($voir_gpb['langue'] == 'zh-HK') { echo '<option value="zh-HK">Chinois (Hong Kong)</option>'; }
if ($voir_gpb['langue'] == 'zh-CN') { echo '<option value="zh-CN">Chinois (simplifié)</option>'; }
if ($voir_gpb['langue'] == 'co') { echo '<option value="co">Coréen</option>'; }
if ($voir_gpb['langue'] == 'hr') { echo '<option value="hr">Croate</option>'; }
if ($voir_gpb['langue'] == 'da') { echo '<option value="da">Danois</option>'; }
if ($voir_gpb['langue'] == 'es-419') { echo '<option value="es-419">Espagnol (Amérique latine)</option>'; }
if ($voir_gpb['langue'] == 'es') { echo '<option value="es">Espagnol (Espagne)</option>'; }
if ($voir_gpb['langue'] == 'et') { echo '<option value="et">Estonien</option>'; }
if ($voir_gpb['langue'] == 'fil') { echo '<option value="fil">Filipino</option>'; }
if ($voir_gpb['langue'] == 'fi') { echo '<option value="fi">Finnois</option>'; }
if ($voir_gpb['langue'] == 'fr-CA') { echo '<option value="fr-CA">Français (Canada)</option>'; }
if ($voir_gpb['langue'] == 'fr') { echo '<option value="fr">Français (France)</option>'; }
if ($voir_gpb['langue'] == 'gl') { echo '<option value="gl">Galicien</option>'; }
if ($voir_gpb['langue'] == 'el') { echo '<option value="el">Grec</option>'; }
if ($voir_gpb['langue'] == 'gu') { echo '<option value="gu">Gujarati</option>'; }
if ($voir_gpb['langue'] == 'iw') { echo '<option value="iw">Hébreu</option>'; }
if ($voir_gpb['langue'] == 'hi') { echo '<option value="hi">Hindi</option>'; }
if ($voir_gpb['langue'] == 'hu') { echo '<option value="hu">Hongrois</option>'; }
if ($voir_gpb['langue'] == 'id') { echo '<option value="id">Indonésien</option>'; }
if ($voir_gpb['langue'] == 'is') { echo '<option value="is">Islandais</option>'; }
if ($voir_gpb['langue'] == 'it') { echo '<option value="it">Italien</option>'; }
if ($voir_gpb['langue'] == 'ja') { echo '<option value="ja">Japonais</option>'; }
if ($voir_gpb['langue'] == 'kn') { echo '<option value="kn">Kannada</option>'; }
if ($voir_gpb['langue'] == 'lv') { echo '<option value="lv">Letton</option>'; }
if ($voir_gpb['langue'] == 'lt') { echo '<option value="lt">Lituanien</option>'; }
if ($voir_gpb['langue'] == 'ms') { echo '<option value="ms">Malais</option>'; }
if ($voir_gpb['langue'] == 'ml') { echo '<option value="ml">Malayalam</option>'; }
if ($voir_gpb['langue'] == 'mr') { echo '<option value="mr">Marathe</option>'; }
if ($voir_gpb['langue'] == 'nl') { echo '<option value="nl">Néerlandais</option>'; }
if ($voir_gpb['langue'] == 'no') { echo '<option value="no">Norvégien</option>'; }
if ($voir_gpb['langue'] == 'ur') { echo '<option value="ur">Ourdou</option>'; }
if ($voir_gpb['langue'] == 'fa') { echo '<option value="fa">Persan</option>'; }
if ($voir_gpb['langue'] == 'pl') { echo '<option value="pl">Polonais</option>'; }
if ($voir_gpb['langue'] == 'pt-BR') { echo '<option value="pt-BR">Portugais (Brésil)</option>'; }
if ($voir_gpb['langue'] == 'pt-PT') { echo '<option value="pt-PT">Portugal (Portugal)</option>'; }
if ($voir_gpb['langue'] == 'ro') { echo '<option value="ro">Roumain</option>'; }
if ($voir_gpb['langue'] == 'ru') { echo '<option value="ru">Russe</option>'; }
if ($voir_gpb['langue'] == 'sr') { echo '<option value="sr">Serbe</option>'; }
if ($voir_gpb['langue'] == 'sk') { echo '<option value="sk">Slovaque</option>'; }
if ($voir_gpb['langue'] == 'sl') { echo '<option value="sl">Slovène</option>'; }
if ($voir_gpb['langue'] == 'sv') { echo '<option value="sv">Suédois</option>'; }
if ($voir_gpb['langue'] == 'sw') { echo '<option value="sw">Swahili</option>'; }
if ($voir_gpb['langue'] == 'ta') { echo '<option value="ta">Tamoul</option>'; }
if ($voir_gpb['langue'] == 'cs') { echo '<option value="cs">Tchèque</option>'; }
if ($voir_gpb['langue'] == 'te') { echo '<option value="te">Télougou</option>'; }
if ($voir_gpb['langue'] == 'th') { echo '<option value="th">Thai</option>'; }
if ($voir_gpb['langue'] == 'tr') { echo '<option value="tr">Turc</option>'; }
if ($voir_gpb['langue'] == 'uk') { echo '<option value="uk">Ukrainien</option>'; }
if ($voir_gpb['langue'] == 'vi') { echo '<option value="vi">Vietnamien</option>'; }
if ($voir_gpb['langue'] == 'zu') { echo '<option value="zu">Zoulou</option>'; }
echo '
<option value="af">Afrikaans</option>
<option value="de">Allemand</option>
<option value="am">Amharique</option>
<option value="en-GB">Anglais</option>
<option value="ar">Arabe</option>
<option value="eu">Basque euskara</option>
<option value="bn">Bengali</option>
<option value="bg">Bulgare</option>
<option value="ca">Catalan</option>
<option value="zh-HK">Chinois (Hong Kong)</option>
<option value="zh-CN">Chinois (simplifié)</option>
<option value="ko">Coréen</option>
<option value="hr">Croate</option>
<option value="da">Danois</option>
<option value="es-419">Espagnol (Amérique latine)</option>
<option value="es">Espagnol (Espagne)</option>
<option value="et">Estonien</option>
<option value="fil">Filipino</option>
<option value="fi">Finnois</option>
<option value="fr-CA">Français (Canada)</option>
<option value="fr">Français (France)</option>
<option value="gl">Galicien</option>
<option value="el">Grec</option>
<option value="gu">Gujarati</option>
<option value="iw">Hébreu</option>
<option value="hi">Hindi</option>
<option value="hu">Hongrois</option>
<option value="id">Indonésien</option>
<option value="is">Islandais</option>
<option value="it">Italien</option>
<option value="ja">Japonais</option>
<option value="kn">Kannada</option>
<option value="lv">Letton</option>
<option value="lt">Lituanien</option>
<option value="ms">Malais</option>
<option value="ml">Malayalam</option>
<option value="mr">Marathe</option>
<option value="nl">Néerlandais</option>
<option value="no">Norvégien</option>
<option value="ur">Ourdou</option>
<option value="fa">Persan</option>
<option value="pl">Polonais</option>
<option value="pt-BR">Portugais (Brésil)</option>
<option value="pt-PT">Portugal (Portugal)</option>
<option value="ro">Roumain</option>
<option value="ru">Russe</option>
<option value="sr">Serbe</option>
<option value="sk">Slovaque</option>
<option value="sl">Slovène</option>
<option value="sv">Suédois</option>
<option value="sw">Swahili</option>
<option value="ta">Tamoul</option>
<option value="cs">Tchèque</option>
<option value="te">Télougou</option>
<option value="th">Thai</option>
<option value="tr">Turc</option>
<option value="uk">Ukrainien</option>
<option value="vi">Vietnamien</option>
<option value="zu">Zoulou</option>
</select>
</td>
</tr>
<tr>
<td>Disposition :</td>';
if ($voir_gpb['disposition'] == 'portrait') {
echo '
<td>
<input type="radio" name="disposition" id="radio" value="portrait" checked="checked"> Portrait 
<input type="radio" name="disposition" id="radio2" value="paysage"> Paysage
</td>';
}
elseif ($voir_gpb['disposition'] == 'paysage')  {
echo '
<td>
<input type="radio" name="disposition" id="radio" value="portrait"> Portrait 
<input type="radio" name="disposition" id="radio2" value="paysage" checked="checked"> Paysage
</td>';	
}
echo '
</tr>
<tr>
<td>Largeur :</td>
<td><input name="largeur" type="text" id="largeur" size="3" maxlength="3" value="' . $voir_gpb['largeur'] . '"> px (450 max.)</td>
</tr>
<tr>
<td>Couleur :</td>';
if ($voir_gpb['couleur'] == 'clair') {
echo '
<td><input name="couleur" type="radio" id="radio3" value="clair" checked="checked"> Clair 
<input type="radio" name="couleur" id="radio4" value="sombre"> Sombre
</td>';
}
elseif ($voir_gpb['couleur'] == 'sombre') {
echo '
<td><input name="couleur" type="radio" id="radio3" value="clair"> Clair 
<input type="radio" name="couleur" id="radio4" value="sombre" checked="checked"> Sombre
</td>';	
}
echo '
</tr>
<tr>
<td>Photo :</td>';
if ($voir_gpb['photo'] == 'ON') {
echo '
<td><input type="radio" name="photo" id="radio5" value="ON" checked="checked"> Avec 
<input type="radio" name="photo" id="radio6" value="OFF"> Sans
</td>';
}
elseif ($voir_gpb['photo'] == 'OFF') {
echo '
<td><input type="radio" name="photo" id="radio5" value="ON"> Avec 
<input type="radio" name="photo" id="radio6" value="OFF" checked="checked"> Sans
</td>';	
}
echo '
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td colspan="2" align="right"><input type="submit" name="maj" id="maj" value="Mettre à jour" class="button button-primary"></td>
</tr>
</table>
</form>
</div>';
apercu_bgp();
}
?>


<?php
############ PREPARATION DU BADGE EN SHORTCODE
function bgp_contenu() {
global $wpdb;

$table_gpb = $wpdb->prefix . "gpb";
$req_apercu_gpb = "SELECT * FROM $table_gpb WHERE id_gpb='1'";
$req_apercu_gpb_exec  = mysql_query($req_apercu_gpb);
$apercu_gpb = mysql_fetch_assoc($req_apercu_gpb_exec);


$badge = "
<script src='https://apis.google.com/js/platform.js' async defer>\n
{lang: '" . $apercu_gpb['langue'] . "'}\n
</script>\n";

if ($apercu_gpb['disposition'] == 'paysage') {	
if ($apercu_gpb['couleur'] == 'clair') {
$badge .= "<div class='g-page' data-width='" . $apercu_gpb['largeur'] . "' data-href='" . $apercu_gpb['url'] . "' data-rel='publisher' data-layout='landscape' data-showcoverphoto='false'></div>\n";
}
elseif ($apercu_gpb['couleur'] == 'sombre') {	
$badge .= "<div class='g-page' data-width='" . $apercu_gpb['largeur'] . "' data-href='" . $apercu_gpb['url'] . "' data-rel='publisher' data-theme='dark' data-layout='landscape' data-showcoverphoto='false'></div>\n";
}
}
if ($apercu_gpb['disposition'] == 'portrait') {	
if ($apercu_gpb['photo'] == 'ON') {	
if ($apercu_gpb['couleur'] == 'clair') {
$badge .= "<div class='g-page' data-width='" . $apercu_gpb['largeur'] . "' data-href='" . $apercu_gpb['url'] . "' data-rel='publisher'></div>\n";
}
elseif ($apercu_gpb['couleur'] == 'sombre') {
$badge .= "<div class='g-page' data-width='" . $apercu_gpb['largeur'] . "' data-href='" . $apercu_gpb['url'] . "' data-rel='publisher' data-theme='dark'></div>\n";
}
}
if ($apercu_gpb['photo'] == 'OFF') {	
if ($apercu_gpb['couleur'] == 'clair') {
$badge .= "<div class='g-page' data-width='" . $apercu_gpb['largeur'] . "' data-href='" . $apercu_gpb['url'] . "' data-rel='publisher' data-showcoverphoto='false'></div>\n";
}
elseif ($apercu_gpb['couleur'] == 'sombre') {
$badge .= "<div class='g-page' data-width='" . $apercu_gpb['largeur'] . "' data-href='" . $apercu_gpb['url'] . "' data-rel='publisher' data-showcoverphoto='false' data-theme='dark'></div>\n";
}
}
}
return $badge;
}
add_shortcode('bgp_by_jm_crea','bgp_contenu');








?>


<?php
function widget_1($args) {
extract($args);
?>
<?php echo $before_widget; ?>
<?php echo $before_title . 'My Unique Widget' . $after_title; ?>
test
<?php echo $after_widget; ?>
<?php
}
register_sidebar_widget('My Unique Widget', 'widget_1');
?> 