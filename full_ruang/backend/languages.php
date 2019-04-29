<?php include 'header.php';
$langnames = array( "ary"=> urlencode("العربية المغربية"), "ar"=> urlencode("العربية"), "az"=> urlencode("Azərbaycan dili"), "azb"=> urlencode("گؤنئی آذربایجان"), "bg_BG"=> urlencode("Български"), "bn_BD"=> urlencode("বাংলা"), "bs_BA"=> urlencode("Bosanski"), "ca"=> urlencode("Català"), "ceb"=> urlencode("Cebuano"), "cs_CZ"=> urlencode("Čeština‎"), "cy"=> urlencode("Cymraeg"), "da_DK"=> urlencode("Dansk"), "de_CH_informal"=> urlencode("Deutsch (Schweiz, Du)"), "de_DE_formal"=> urlencode("Deutsch (Sie)"), "de_DE"=> urlencode("Deutsch"), "de_CH"=> urlencode("Deutsch (Schweiz)"), "el"=> urlencode("Ελληνικά"), "en_CA"=> urlencode("English (Canada)"), "en_GB"=> urlencode("English (UK)"), "en_NZ"=> urlencode("English (New Zealand)"), "en_ZA"=> urlencode("English (South Africa)"), "en_AU"=> urlencode("English (Australia)"), "eo"=> urlencode("Esperanto"), "es_ES"=> urlencode("Español"), "et"=> urlencode("Eesti"), "eu"=> urlencode("Euskara"), "fa_IR"=> urlencode("فارسی"), "fi"=> urlencode("Suomi"), "fr_FR"=> urlencode("Français"), "gd"=> urlencode("Gàidhlig"), "gl_ES"=> urlencode("Galego"), "gu"=> urlencode("ગુજરાતી"), "haz"=> urlencode("هزاره گی"), "hi_IN"=> urlencode("हिन्दी"), "hr"=> urlencode("Hrvatski"), "hu_HU"=> urlencode("Magyar"), "hy"=> urlencode("Հայերեն"), "id_ID"=> urlencode("Bahasa Indonesia"), "is_IS"=> urlencode("Íslenska"), "it_IT"=> urlencode("Italiano"), "ja"=> urlencode("日本語"), "ka_GE"=> urlencode("ქართული"), "ko_KR"=> urlencode("한국어"), "lt_LT"=> urlencode("Lietuvių kalba"), "lv"=> urlencode("Latviešu valoda"), "mk_MK"=> urlencode("Македонски јазик"), "mr"=> urlencode("मराठी"), "ms_MY"=> urlencode("Bahasa Melayu"), "my_MM"=> urlencode("Burmese"), "nb_NO"=> urlencode("Norsk bokmål"), "nl_NL"=> urlencode("Nederlands"), "nl_NL_formal"=> urlencode("Nederlands (Formeel)"), "nn_NO"=> urlencode("Norsk nynorsk"), "oci"=> urlencode("Occitan"), "pl_PL"=> urlencode("Polski"), "pt_PT"=> urlencode("Português"), "pt_BR"=> urlencode("Português do Brasil"), "ro_RO"=> urlencode("Română"), "ru_RU"=> urlencode("Русский"), "sk_SK"=> urlencode("Slovenčina"), "sl_SI"=> urlencode("Slovenščina"), "sq"=> urlencode("Shqip"), "sr_RS"=> urlencode("Српски језик"), "sv_SE"=> urlencode("Svenska"), "szl"=> urlencode("Ślōnskŏ gŏdka"), "th"=> urlencode("ไทย"), "tl"=> urlencode("Tagalog"), "tr_TR"=> urlencode("Türkçe"), "ug_CN"=> urlencode("Uyƣurqə"), "uk"=> urlencode("Українська"), "vi"=> urlencode("Tiếng Việt"), "zh_TW"=> urlencode("繁體中文"), "zh_HK"=> urlencode("香港中文版"), "zh_CN"=> urlencode("简体中文") ); 
 ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Languages</li>
      </ol>
      <!-- Languages card-->
		<h5><i class="fa fa-fw fa-language"></i> Language Translator</h5>
        <div class="my-5 mx-2">
			<div class="col-md-12 my-2">
				<p>Translated Languages are:</p> 
			</div>
			<div class="col-md-12 my-2">
				<?php 
				$translated_languages = "English (United States)";
				$langfiles = preg_grep('~^saasappoint-'.$_SESSION['business_id'].'.*\.(php)$~', scandir(dirname(dirname(__FILE__))."/includes/languages/"));
				$langfiles = array_values($langfiles);
				if(sizeof($langfiles)>0){ 
					for($i=0;$i<sizeof($langfiles);$i++){
						foreach($langnames as $key => $value){
							if('saasappoint-'.$_SESSION['business_id'].'-'.$key.'.php' == $langfiles[$i]){ 
								$translated_languages .= '<label style="border-radius:10px" class="border p-3 m-3 saasappoint_remove_language_'.$key.'">'.urldecode($value)." <a href='javascript:void(0)' class='saasappoint_edit_language pl-2' data-lang='".$key."'><i class='fa fa-pencil text-primary'></i> &nbsp;<a href='javascript:void(0)' class='saasappoint_remove_language pl-1' data-lang='".$key."'><i class='fa fa-close text-danger'></i></a></label>"; 
							}
						}
					}
				}  
				?>
				<b><?php echo $translated_languages; ?></b>
			</div>
			<hr />
			<div class="col-md-12 my-4">
				<label for="saasappoint_langauges">Select Language to Translate</label>
				<select name="saasappoint_langauges" id="saasappoint_langauges" class="selectpicker" data-size="10" data-live-search="true" data-live-search-placeholder="Search">
					<option value="none">Select Language</option>
					<option value="en">English (United States)</option>
					<option value="ary" lang="ar">العربية المغربية</option>
					<option value="ar" lang="ar">العربية</option>
					<option value="az">Azərbaycan dili</option>
					<option value="azb" lang="az">گؤنئی آذربایجان</option>
					<option value="bg_BG">Български</option>
					<option value="bn_BD">বাংলা</option>
					<option value="bs_BA">Bosanski</option>
					<option value="ca">Català</option>
					<option value="ceb">Cebuano</option>
					<option value="cs_CZ">Čeština‎</option>
					<option value="cy">Cymraeg</option>
					<option value="da_DK">Dansk</option>
					<option value="de_CH_informal">Deutsch (Schweiz, Du)</option>
					<option value="de_DE_formal">Deutsch (Sie)</option>
					<option value="de_DE">Deutsch</option>
					<option value="de_CH">Deutsch (Schweiz)</option>
					<option value="el">Ελληνικά</option>
					<option value="en_CA">English (Canada)</option>
					<option value="en_GB">English (UK)</option>
					<option value="en_NZ">English (New Zealand)</option>
					<option value="en_ZA">English (South Africa)</option>
					<option value="en_AU">English (Australia)</option>
					<option value="eo">Esperanto</option>
					<option value="es_ES">Español</option>
					<option value="et">Eesti</option>
					<option value="eu">Euskara</option>
					<option value="fa_IR" lang="fa">فارسی</option>
					<option value="fi">Suomi</option>
					<option value="fr_FR">Français</option>
					<option value="gd">Gàidhlig</option>
					<option value="gl_ES">Galego</option>
					<option value="gu">ગુજરાતી</option>
					<option value="haz" lang="haz">هزاره گی</option>
					<option value="hi_IN">हिन्दी</option>
					<option value="hr">Hrvatski</option>
					<option value="hu_HU">Magyar</option>
					<option value="hy">Հայերեն</option>
					<option value="id_ID">Bahasa Indonesia</option>
					<option value="is_IS">Íslenska</option>
					<option value="it_IT">Italiano</option>
					<option value="ja">日本語</option>
					<option value="ka_GE">ქართული</option>
					<option value="ko_KR">한국어</option>
					<option value="lt_LT">Lietuvių kalba</option>
					<option value="lv">Latviešu valoda</option>
					<option value="mk_MK">Македонски јазик</option>
					<option value="mr">मराठी</option>
					<option value="ms_MY">Bahasa Melayu</option>
					<option value="my_MM">Burmese</option>
					<option value="nb_NO">Norsk bokmål</option>
					<option value="nl_NL">Nederlands</option>
					<option value="nl_NL_formal">Nederlands (Formeel)</option>
					<option value="nn_NO">Norsk nynorsk</option>
					<option value="oci">Occitan</option>
					<option value="pl_PL">Polski</option>
					<option value="pt_PT">Português</option>
					<option value="pt_BR">Português do Brasil</option>
					<option value="ro_RO">Română</option>
					<option value="ru_RU">Русский</option>
					<option value="sk_SK">Slovenčina</option>
					<option value="sl_SI">Slovenščina</option>
					<option value="sq">Shqip</option>
					<option value="sr_RS" >Српски језик</option>
					<option value="sv_SE">Svenska</option>
					<option value="szl">Ślōnskŏ gŏdka</option>
					<option value="th">ไทย</option>
					<option value="tl">Tagalog</option>
					<option value="tr_TR">Türkçe</option>
					<option value="ug_CN">Uyƣurqə</option>
					<option value="uk">Українська</option>
					<option value="vi">Tiếng Việt</option>
					<option value="zh_TW">繁體中文</option>
					<option value="zh_HK">香港中文版</option>
					<option value="zh_CN">简体中文</option>
				</select>
			</div>
			<div class="saasappoint_languages_container">
				<!-- Fetch Language labels from ajax -->
			</div>
        </div>
<?php include 'footer.php'; ?>