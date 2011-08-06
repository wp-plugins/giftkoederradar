<?php

/**
 * Plugin Name: Giftköder Radar
 * Plugin URI: http://www.giftkoeder-radar.com
 * Description: Dieses Plugin zeigt dir mit Hilfe einer übersichtlichen Karte alle aktuellen und archivierten Fundorte der Plattform <a href="http://www.giftkoeder-radar.com">Gitfköder Radar</a> direkt in deinem Blog an. Bitte beachte, dass du zum Betrieb dieses Plugins einen <a href="http://www.giftkoeder-radar.com/blog/2011/07/giftkoderradarapi-offenes-okosystem-fur-entwickler/">kostenlosen API Schlüssel</a> benötigst.
 * Version: 1.1
 * Author: MountainGrafix
 * Author URI: http://www.facebook.com/MountainGrafix
 */

if (!defined("WP_CONTENT_URL")) {
	define("WP_CONTENT_URL", get_option("siteurl") . "/wp-content");
}

if (!defined("WP_PLUGIN_URL")) {
	define("WP_PLUGIN_URL",  WP_CONTENT_URL . "/plugins");
}

/**
 * Fügt einen zusätzlichen Stylesheet ein
 * 
 * @author Sascha Schoppengerd
 * @copyright MountainGrafix <http://www.mountaingrafix.eu>
 */
function GkRadar_Head() {
  	$css = WP_PLUGIN_URL . "/giftkoederradar/gkradar.css";
  
  	if (file_exists(STYLESHEETPATH . "/gkradar.css")) {
  		$css_url = get_bloginfo("stylesheet_directory") . "/gkradar.css";
 	}
  
  	echo "\n".'<link rel="stylesheet" href="' . $css . '" type="text/css" media="screen" />'."\n";
 	echo '<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;hl=de&amp;v=2&amp;key=' . get_option('GkRadarMapsApiKey') . '"></script>' . PHP_EOL;
}

/**
 * Fügt einen zusätzlichen Menüpunkt im ACP ein
 * 
 * @author Sascha Schoppengerd
 * @copyright MountainGrafix <http://www.mountaingrafix.eu>
 */
function GkRadar_AddMenu() {
	if (function_exists('add_submenu_page')) {
		add_submenu_page('options-general.php', 'Giftköder Radar', 'Giftköder Radar', 0, basename(__FILE__), 'GkRadar_PrintAdminHTML');
	}
}

/**
 * Shortcode für die Ausgabe der Karte
 * 
 * @author Sascha Schoppengerd
 * @copyright MountainGrafix <http://www.mountaingrafix.eu>
 */
function GkRadar_Map($param) {

	$param = shortcode_atts(array(
		'latitude' 	=> '0', 
		'longitude' => '0',
		'width' 	=> '500',
		'height' 	=> '600'
	), $param);

	$html = '<div id="gkradar-map" style="width:' . $param['width'] . 'px;height:' . $param['height'] . 'px;"></div>' . PHP_EOL;
	$html .= '<script type="text/javascript">var GkRadarApiKey = "' . get_option('GkRadarApiKey') . '";</script>';
	$html .= '<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: "de"}</script>';
	$html .= '<div id="gkradar-share-buttons">
			<p style="font-size:14px;"><b>Bitte unterstütze dieses Projekt mit einer Empfehlung:</b></p>
			<iframe scrolling="no" frameborder="0" style="float:left;width:110px;height:30px;" src="http://platform.twitter.com/widgets/tweet_button.html?url=http://www.giftkoeder-radar.com&amp;text=Giftköder Radar für iPhone und iPod Touch&amp;via=MountainGrafix" role="presentation" allowtransparency="true"></iframe>
			<iframe scrolling="no" frameborder="0" style="float:left;width:150px;height:30px;" allowtransparency="true" src="http://www.facebook.com/plugins/like.php?href=http://www.facebook.com/GiftkoederRadar&amp;layout=button_count&amp;show_faces=false&amp;width=150&amp;action=like"></iframe>
			<div style="float:left;width:90px;height:30px;"><g:plusone size="medium" href="http://www.giftkoeder-radar.com"></g:plusone></div>
			</div><div style="clear:both"></div>';
	
	return $html;
}

/**
 * Footer für den JS Request
 * 
 * @author Sascha Schoppengerd
 * @copyright MountainGrafix <http://www.mountaingrafix.eu>
 */
function GkRadar_Footer() {
	echo '<script type="text/javascript" src="' . WP_PLUGIN_URL. '/giftkoederradar/request.js"></script>';
}

/**
 * Repräsentiert die zusätzliche Einstellungsseite im ACP
 * 
 * @author Sascha Schoppengerd
 * @copyright MountainGrafix <http://www.mountaingrafix.eu>
 */
function GkRadar_PrintAdminHTML() {
	
	if (isset($_POST['gkradar-submit'])) {
		update_option('GkRadarApiKey', $_POST['GkRadarApiKey']);
		update_option('GkRadarMapsApiKey', $_POST['GkRadarMapsApiKey']);
		
		?> 
		
		<div style="margin-top:15px;" id="message" class="updated fade"><p>Einstellungen gespeichert</div> 
	
	<?php } ?>
	
	<div class="wrap">
		<div id="icon-tools" class="icon32"><br></div>
		<h2>Giftköder Radar für Wordpress 3.x</h2>
		<div style="margin-top:15px;" id="poststuff" class="ui-sortable">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=gkradar.php" method="post">
				<div id="gk-title" class="postbox">
				<h3>Einstellungen</h3>
				<div class="inside">
					<table class="form-table">
					<tbody>
					<tr>
						<th scope="row"><b>GiftköderRadar[API]</b></th>
						<td>
							<input type="text" name="GkRadarApiKey" id="GkRadarApiKey" class="maxi" value="<?php echo get_option('GkRadarApiKey'); ?>">
							<p>Deinen kostenlosen API Schlüssel für <b>GiftköderRadar[API]</b> kannst du <a href="http://www.giftkoeder-radar.com/blog/2011/07/giftkoderradarapi-offenes-okosystem-fur-entwickler/">hier</a> beantragen</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><b>GoogleMaps API</b></th>
						<td>
							<input type="text" name="GkRadarMapsApiKey" id="GkRadarMapsApiKey" class="maxi" value="<?php echo get_option('GkRadarMapsApiKey'); ?>">
							<p>Einen kostenlosen API Schlüssel für <b>GoogleMaps</b>kannst du <a href="http://code.google.com/intl/de-DE/apis/maps/signup.html">hier</a> beantragen</p>
						</td>
					</tr>
					<tr>
						<th>&nbsp;</th>
						<td><input type="submit" class="button-primary" name="gkradar-submit" value="Änderungen übernehmen"></td>
					</tr>
					</tbody>
					</table>
				</div>
				</div>
			</form>
				
			<div id="gk-info-title" class="postbox">
				<h3>Über Giftköder Radar</h3>
				<div class="inside">
					<table class="form-table">
					<tbody>
					<tr>
						<th><img style="padding:1px;border:1px solid #333333;" src="<?php echo WP_PLUGIN_URL; ?>/giftkoederradar/images/GKAvatar.png"></th>
						<td>
							<p>Fast täglich müssen Hunde elendig verenden, weil brutale Tierquäler ganz bewusst tödliche Fallen auslegt haben. Verhindern kann man das Auslegen dieser Giftköder nicht! Aber es gibt einen wirksamen Schutz und auch <b>DU</b> kannst dabei helfen:</p>
							<h2>1. Unterstütze diese Initiative auf Facebook</h2>
							<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FGiftkoederRadar&amp;width=550&amp;colorscheme=light&amp;connections=10&amp;stream=false&amp;header=false&amp;height=185" scrolling="no" frameborder="0" style="border:none;overflow:hidden;width:550px;height:185px;" allowTransparency="true"></iframe>
							<h2>2. Lade dir unsere kostenlose App herunter</h2>
							<p>Wenn du Besitzer eines <b>iPhone, iPad oder iPod Touch</b> bist, dann lade dir doch bitte unsere <a href="http://itunes.apple.com/de/app/giftkoder-radar/id442902860?mt=8">kostenlose App</a> im AppStore herunter. Mit Hilfe dieser App bist du nicht nur unterwegs stets über aktuelle Gefahren informiert, sondern du hast zudem auch die Möglichkeit andere Hundehalter direkt über die App vor neuen Giftködern zu warnen.</p>
							<p style="margin-top:15px;"><a href="http://itunes.apple.com/de/app/giftkoder-radar/id442902860?mt=8" title="Giftköder Radar jetzt im AppStore downloaden"><img src="<?php echo WP_PLUGIN_URL; ?>/giftkoederradar/images/appstore.gif"></a></p>
							<h2>3. Schreibe bitte eine kurze Rezension</h2>
							<p>Natürlich würden wir uns sehr freuen, wenn du dir nach deinem Download noch kurz Zeit nimmst um <a href="http://ax.itunes.apple.com/WebObjects/MZStore.woa/wa/viewContentsUserReviews?type=Purple+Software&id=442902860">eine kleine Rezension</a> für die App zu schreiben. Das dauert für dich in der Regel nicht mehr als <b>5 Minuten</b>, aber es hilft uns noch viele andere Hundehalter für dieses Projekt zu begeistern. Vielen Dank!</p>
							<h2>4. Crowdfunding</h2>
							<p>Der Betrieb einer Plattform wie <b>GiftköderRadar</b> verschlingt insbesondere im Bereich der Entwicklung sehr viel Geld, denn allein die Kosten für die iPhone App belaufen sich auf mehrere tausend Euro. Finanziert wird dieses Projekt <b>zu 100%</b> aus privaten Mittel und Spenden. Wenn du möchtest, dann kannst <b>auch DU</b> einen kleinen Teil zur Unterstützung dieser Initiative beitragen. Fühle dich daher frei, dieses Projekt mit einem Betrag deiner Wahl zu unterstützen!</p>
							<form style="margin-top:20px" action="https://www.paypal.com/cgi-bin/webscr" method="post">
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="hosted_button_id" value="73V7CJVAREBUS">
								<input type="image" src="https://www.paypalobjects.com/de_DE/AT/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Jetzt einfach, schnell und sicher online bezahlen – mit PayPal.">
								<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
							</form>
						</td>
					</tr>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
	<?php 
}

function GkRadar_LoadJS() {
	if (!is_admin()) {
    	wp_deregister_script('jquery'); 
    	wp_register_script('jquery', 'http://code.jquery.com/jquery-latest.pack.js', false, '');
    	wp_enqueue_script('jquery');
	}
}

add_action('init', 'GkRadar_LoadJS');
add_action('wp_head', 'GkRadar_Head');
add_action('admin_head', 'GkRadar_Head');
add_action('admin_menu', 'GkRadar_AddMenu');
add_shortcode('gkradar-map', 'GkRadar_Map');
add_action('wp_footer', 'GkRadar_Footer');

?>
