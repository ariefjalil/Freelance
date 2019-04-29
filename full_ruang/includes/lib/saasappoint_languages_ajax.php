<?php 
session_start();
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");

/* Get selected language ajax */
if(isset($_POST['get_selected_lang_labels'])){ 
	$langnames = array( "en"=> urlencode("English (United States)"), "ary"=> urlencode("العربية المغربية"), "ar"=> urlencode("العربية"), "az"=> urlencode("Azərbaycan dili"), "azb"=> urlencode("گؤنئی آذربایجان"), "bg_BG"=> urlencode("Български"), "bn_BD"=> urlencode("বাংলা"), "bs_BA"=> urlencode("Bosanski"), "ca"=> urlencode("Català"), "ceb"=> urlencode("Cebuano"), "cs_CZ"=> urlencode("Čeština‎"), "cy"=> urlencode("Cymraeg"), "da_DK"=> urlencode("Dansk"), "de_CH_informal"=> urlencode("Deutsch (Schweiz, Du)"), "de_DE_formal"=> urlencode("Deutsch (Sie)"), "de_DE"=> urlencode("Deutsch"), "de_CH"=> urlencode("Deutsch (Schweiz)"), "el"=> urlencode("Ελληνικά"), "en_CA"=> urlencode("English (Canada)"), "en_GB"=> urlencode("English (UK)"), "en_NZ"=> urlencode("English (New Zealand)"), "en_ZA"=> urlencode("English (South Africa)"), "en_AU"=> urlencode("English (Australia)"), "eo"=> urlencode("Esperanto"), "es_ES"=> urlencode("Español"), "et"=> urlencode("Eesti"), "eu"=> urlencode("Euskara"), "fa_IR"=> urlencode("فارسی"), "fi"=> urlencode("Suomi"), "fr_FR"=> urlencode("Français"), "gd"=> urlencode("Gàidhlig"), "gl_ES"=> urlencode("Galego"), "gu"=> urlencode("ગુજરાતી"), "haz"=> urlencode("هزاره گی"), "hi_IN"=> urlencode("हिन्दी"), "hr"=> urlencode("Hrvatski"), "hu_HU"=> urlencode("Magyar"), "hy"=> urlencode("Հայերեն"), "id_ID"=> urlencode("Bahasa Indonesia"), "is_IS"=> urlencode("Íslenska"), "it_IT"=> urlencode("Italiano"), "ja"=> urlencode("日本語"), "ka_GE"=> urlencode("ქართული"), "ko_KR"=> urlencode("한국어"), "lt_LT"=> urlencode("Lietuvių kalba"), "lv"=> urlencode("Latviešu valoda"), "mk_MK"=> urlencode("Македонски јазик"), "mr"=> urlencode("मराठी"), "ms_MY"=> urlencode("Bahasa Melayu"), "my_MM"=> urlencode("Burmese"), "nb_NO"=> urlencode("Norsk bokmål"), "nl_NL"=> urlencode("Nederlands"), "nl_NL_formal"=> urlencode("Nederlands (Formeel)"), "nn_NO"=> urlencode("Norsk nynorsk"), "oci"=> urlencode("Occitan"), "pl_PL"=> urlencode("Polski"), "pt_PT"=> urlencode("Português"), "pt_BR"=> urlencode("Português do Brasil"), "ro_RO"=> urlencode("Română"), "ru_RU"=> urlencode("Русский"), "sk_SK"=> urlencode("Slovenčina"), "sl_SI"=> urlencode("Slovenščina"), "sq"=> urlencode("Shqip"), "sr_RS"=> urlencode("Српски језик"), "sv_SE"=> urlencode("Svenska"), "szl"=> urlencode("Ślōnskŏ gŏdka"), "th"=> urlencode("ไทย"), "tl"=> urlencode("Tagalog"), "tr_TR"=> urlencode("Türkçe"), "ug_CN"=> urlencode("Uyƣurqə"), "uk"=> urlencode("Українська"), "vi"=> urlencode("Tiếng Việt"), "zh_TW"=> urlencode("繁體中文"), "zh_HK"=> urlencode("香港中文版"), "zh_CN"=> urlencode("简体中文"), );

 	$langfiles = preg_grep('~^saasappoint-'.$_SESSION['business_id'].'-'.$_POST["lang"].'.*\.(php)$~', scandir(dirname(dirname(dirname(__FILE__)))."/includes/languages/"));
	$langfiles = array_values($langfiles); 
	?>
	<hr />
	<div class="saasappoint_languages_container_card border">
		<div class="saasappoint_languages_container_header border px-4 py-1">
			<h5><i class="fa fa-fw fa-language"></i> Selected Language: <?php echo urldecode($langnames[$_POST["lang"]]); ?></h5>
		</div>
		<div class="saasappoint_languages_container_body border px-4 py-1">
			<?php 
			if(sizeof($langfiles)>0){ 
				include(dirname(dirname(dirname(__FILE__)))."/includes/languages/saasappoint-".$_SESSION['business_id']."-".$_POST["lang"].".php"); 
			}else{
				include(dirname(dirname(dirname(__FILE__)))."/includes/languages/saasappoint-default-en.php"); 
			}
			foreach($langArr as $key => $value){ 
				?>
				<div class="row py-1 border-bottom">
					<div class="col-md-5"><label for="<?php echo $key; ?>"><?php echo $value; ?></label></div>
					<div class="col-md-7"><input class="form-control selected_language_inputs" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo $value; ?>" type="text" /></div>
				</div>
				<?php 
			} 
			?>
		</div>	
		<div class="saasappoint_languages_container_footer border px-4 py-1">
			<a id="save_language_translation" class="btn btn-success btn-block" href="javascript:void(0);">Save Language Translation</a>
		</div>
	</div>	
	<?php 
} 

/* Save selected language ajax */
else if(isset($_POST['save_selected_lang_labels'])){ 
	$lang_array = $_POST['lang_array'];
	$myfile = fopen(dirname(dirname(dirname(__FILE__)))."/includes/languages/saasappoint-".$_SESSION['business_id']."-".$_POST["lang"].".php", "w") or die("notexist");
	$txt = '<?php '."\n";
	$txt .= '$langArr = array( '."\n";
	foreach($lang_array as $key => $value){
		$txt .= '"'.$key.'" => "'.htmlspecialchars($value).'", '."\n";
	}
	$txt .= ");";
	fwrite($myfile, $txt);
	fclose($myfile);
} 

/* Delete selected language ajax */
else if(isset($_POST['delete_lang'])){ 
	$file = dirname(dirname(dirname(__FILE__)))."/includes/languages/saasappoint-".$_SESSION['business_id']."-".$_POST["lang"].".php";
	unlink($file);
}