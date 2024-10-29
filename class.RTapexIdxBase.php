<?php
class RTapexIdxBase
{
	     
	public static function  RTapexInit()
	{
		self::RTapexInitHooks();   
		self::RTapexInitFilters();
		self::RTapexInitShortCode();
		self::RTapexInitRegister();
		self::RTapexInitRewriteAgentsUrl();
	}  
	
	public static function RTapexActivation()
	{
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix."realty_tech_links" . " (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`post_id` int(11) NULL,
				`url` varchar(500) NOT NULL,
				`link_id` varchar(255) NOT NULL,
				`link_name` varchar(250) NOT NULL,
				`page_type` varchar(50) NOT NULL,
				`use_widget` varchar(50) NOT NULL default '0',
				PRIMARY KEY (`id`)
				)";
		dbDelta($sql);
		$sql  = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix."realty_tech_api" . " (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`api` varchar(255) NOT NULL,
				PRIMARY KEY (`id`)
				)";
		dbDelta($sql);
	}
	
	public static function RTapexRemove()
	{
		global $wpdb;
		
		update_option( 'apexAgentApiKey', '' ); 
		update_option( 'ApexIdxDomainUrl', '' ); 
		update_option( 'ApexIdxBrokerGalUname', '' ); 
		update_option( 'ApexIdxIsBroker', '' );
		
		$deletePages = $wpdb->get_col("SELECT post_id from ".$wpdb->prefix."realty_tech_links");

		$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->prefix."realty_tech_links");
		
		$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->prefix."realty_tech_api");
		
		if($deletePages)
		{
			foreach($deletePages as $pageId) 
			{
				wp_delete_post($pageId,true);
			}
		}
			
	}	
	
	public static function RTapexInitHooks()
	{	
		add_action('admin_enqueue_scripts', array( 'RTapexIdxBase', 'RTapexAddScript' ) );
		add_action('admin_enqueue_scripts', array( 'RTapexIdxBase', 'RTapexAddStyle' ));
		add_action('admin_menu', array( 'RTapexIdxBase', 'RTapexAddMenu' ));
		add_action('admin_menu', array('RTapexIdxBase','RTapexValidateApi'));
		add_action('before_delete_post', array('RTapexIdxBase','RTapexUpdateLinksOnDeletePage'));
		add_action('save_post', array('RTapexIdxBase','RTapexUpdateWrapperPage'));
	}
	
	public static function RTapexInitFilters()
	{
		add_filter("plugin_action_links_" . RTAI_PLUGIN_FILE_LOC, array( 'RTapexIdxBase', 'RTapexActLinks'));
		add_filter('page_link',	array( 'RTapexIdxBase', 'RTapexSetNewPageLink'), 25, 2);
		add_filter('get_pages', array( 'RTapexIdxBase', 'RTapexFilterWrapperPage'));
		add_filter('the_content', array( 'RTapexIdxBase', 'RTapexFilterAgentPage'));
		add_filter('query_vars', array( 'RTapexIdxBase', 'RTapexQueryvars'));
		add_filter('widget_text','do_shortcode');
	}
	
	public static function RTapexInitShortCode()
	{
		add_shortcode('realtyTech-search-form', array( 'RTapexIdxBase','RTapexSearchForm'));
		add_shortcode('realtyTech-slider', array( 'RTapexIdxBase','RTapexSlider'));
		add_shortcode('realtyTech-grid', array( 'RTapexIdxBase','RTapexGrid'));
		add_shortcode('realtyTech-featured-market', array( 'RTapexIdxBase','RTapexFeaturedMarket'));
	}
	
	public static function RTapexInitRegister()
	{
		
		/*css for search box widget*/		
        wp_register_style('apexIdxSearchBoxStyle', plugins_url('css/apexIdxSearchBoxWidget.css', __FILE__));
		/*end css for search box widget*/
		
		
		/*js for search box widget*/
		wp_register_script('apexIdxsearchBoxWidget', plugins_url('js/apexIdxsearchBoxWidget.js', __FILE__));
		/*end js for search box widget*/
		
		/*js for search box widget*/
		wp_register_script('apexIdxClipboard', plugins_url('js/clipboard.min.js', __FILE__));
		/*end js for search box widget*/
		
		/*css for featured market*/
		wp_register_style('apexIdxFeaturedMarket', plugins_url('css/apexIdxFeaturedMarket.css', __FILE__));
		/*end css for featured market*/
		
		/*js for featured market*/
		wp_register_script('apexIdxFeaturedMarket', plugins_url('js/apexIdxFeaturedMarket.js', __FILE__), RTAI_VERSION);
		/*end js for featured market*/
		
		/*css for property slider*/
		wp_register_style('apexIdxFPSlider', plugins_url('css/apexIdxFPSlider.css', __FILE__));
		wp_register_style('apexIdxFPSliderGrid', plugins_url('css/apexIdxFPGridSlider.css', __FILE__), array(), RTAI_VERSION);
		/*end css for property slider*/
		
		/*js for property grid view*/
		wp_register_script('apexIdxInitializeSlider', plugins_url('js/apexIdxInitializeSlider.js', __FILE__), array(), RTAI_VERSION);
		/*end js for property slider*/
		
		/*css for fixing global changes from apexidx.com*/
		wp_register_style('apexIdxGlobalCSS', 'https://apexidx.com/custom/apexidxWpPluginFiles/global.css', array(), time());
		/*css for fixing global changes from apexidx.com*/
	}
	
	public static function RTapexInitRewriteAgentsUrl()
	{
		global $wpdb;
		$offices = $wpdb->get_results("SELECT post_id FROM ".$wpdb->prefix."realty_tech_links WHERE page_type='office'", ARRAY_A);
        
		if(count($offices) > 0)
		{
			foreach($offices as $office)
			{
				if($office['post_id'])
				{
					$apOslug = basename(get_permalink($office['post_id']));
					add_rewrite_rule('^'.$apOslug.'/([^/]*)/([^/]*)/?', 'index.php?page_id='.$office['post_id'].'&agentId=$matches[2]', 'top');
				}
			}
			flush_rewrite_rules();	
		}
    }	
	
	
	public static function RTapexActLinks( $exsitingsLinksArray ) 
	{
		// Add a link to this plugin's settings page
		$apexPageLink = '<a href="options-general.php?page=apexApiVerification">Settings</a>';
		array_unshift( $exsitingsLinksArray, $apexPageLink );
		return $exsitingsLinksArray;
	}
	
	public static function RTapexAddScript($page)
	{ 
		// Add  css files to this page in backend
		if( 'toplevel_page_apexApiVerification' === $page || 'apex-idx_page_RTapexAgentWidgets' === $page || 'apex-idx_page_RTapexAgentDefaultPages' === $page):
			wp_enqueue_script('apexIdxbackend', plugins_url('js/apexIdxbackend.js', __FILE__));
		endif;
		
		if( 'apex-idx_page_RTapexAgentWidgets' === $page ):
			wp_enqueue_script('apexIdxClipboard');
		endif;
		
	}
	
	public static function RTapexAddStyle($page)
	{   
		// Add  js files to this page in backend
		if( 'toplevel_page_apexApiVerification' === $page || 'apex-idx_page_RTapexAgentWidgets' === $page || 'apex-idx_page_RTapexAgentDefaultPages' === $page):
			wp_enqueue_style('apexIdxbackend', plugins_url('css/apexIdxBackend.css', __FILE__));
			wp_enqueue_style('apexBootstrapMin', plugins_url('css/bootstrap.min.css', __FILE__));
		endif;
	}
	
	public static function RTapexAddMenu()
	{
	    // Add menu and submenu page for wordpress admin user access
		add_menu_page('REALTYTECH MLS Plugin Options', 'Apex IDX', 'administrator', 'apexApiVerification', array( 'RTapexIdxBase', 'RTapexApiVerification' ) );
		add_submenu_page('apexApiVerification', 'API Console', 'i. API Console', 'administrator', 'apexApiVerification', array( 'RTapexIdxBase', 'RTapexApiVerification' ) );
		add_submenu_page('apexApiVerification', 'Manage Apex Idx Widgets', 'ii. Widgets', 'administrator', 'RTapexAgentWidgets', array( 'RTapexIdxBase', 'RTapexAgentWidgets' ) );
		add_submenu_page('apexApiVerification', 'Manage Apex Idx Pages', 'iii. Idx Pages', 'administrator', 'RTapexAgentDefaultPages', array( 'RTapexIdxBase', 'RTapexAgentDefaultPages' ) );
	}
	
	public static function RTapexValidateApi()
	{
		global $wpdb;
		global $apexApiValidationError;
		global $apexApiValidationSuc;
		
	    $pageName = get_admin_page_title();
		
		register_setting('apexIdx-settings-group', "apexAgentApiKey");
		
		if(get_option('apexAgentApiKey') != '') 
		{
			$apikey = get_option('apexAgentApiKey');
			$existApi  = $wpdb->get_var("SELECT * from ".$wpdb->prefix."realty_tech_api WHERE api = '$apikey'");
			
			if(!$existApi) 
			{
				if(in_array($pageName ,array('Manage Apex Idx Widgets', 'Manage Apex Idx Pages')))
				{
					wp_redirect('admin.php?page=apexApiVerification'); 	
				}
				$status = RTapexApiAuthentication('authenticate');
				
				if(is_wp_error($status) ) 
				{
					$apexApiValidationError = $status->get_error_message();
				}
				else
				{
					$apexApiValidationSuc = 'You have been registered successfully';
				    
					$id = self::RTapexSaveApi($apikey);
					
					if($id)
					{
						self::RTapexSaveLinks($status);
					}
					
				}
			}
			else
			{
				$apexApiValidationSuc = 'You are already registered';
			}
		}
		else
		{
			if(in_array($pageName ,array('Manage Apex Idx Widgets', 'Manage Apex Idx Pages')))
			{   
				wp_redirect('admin.php?page=apexApiVerification'); 	
				exit;
			}
		}
	
	}
	
	public static function RTapexApiVerification()
	{
		global $apexApiValidationError;
		global $apexApiValidationSuc;
		
		include RTAI_PLUGIN_DIR . '/views/apexIdxForm.php';
	}
	
	public static function RTapexAgentWidgets()
	{
		global $wpdb;
		
		if(isset($_POST['getLatestSelection']) && $_POST['getLatestSelection'] == 1)
		{
			self::RTapexGetLatestUpdate();
		}
		
		$customLinks   = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}realty_tech_links where page_type = 'custom' and use_widget= '1'");
		
		include RTAI_PLUGIN_DIR . '/views/apexIdxWidget.php';
	}
	
	public static function RTapexAgentDefaultPages()
	{
		global $wpdb;
		$link = array();
		
		if(isset($_POST['getBrokerInfo']) && $_POST['getBrokerInfo'] == 1)
		{
			self::RTapexGetLatestUpdate('getOffices');
		}
		
		if(isset($_POST['getLatestSelection']) && $_POST['getLatestSelection'] == 1)
		{
			self::RTapexGetLatestUpdate('getLinks');
		}
		
		if(isset($_POST['defaultPage']) && $_POST['defaultPage'] == 1)
		{
			$defaultLinks = ($_POST['defaultLinks']) ? $_POST['defaultLinks'] : array();
			self::RTapexManagePages($defaultLinks, 'default');
		}
		
		if(isset($_POST['customPage']) && $_POST['customPage'] == 1)
		{
			$customLinks = ($_POST['customLinks']) ? $_POST['customLinks'] : array();
			self::RTapexManagePages($customLinks, 'custom');
		}
		
		if(isset($_POST['officePage']) && $_POST['officePage'] == 1)
		{
			$officeLinks = ($_POST['officeLinks']) ? $_POST['officeLinks'] : array();
			self::RTapexCreateOfficePage($officeLinks, 'office');
		}
		
		if(isset($_POST['wrapperPage']) && $_POST['wrapperPage'] == 1)
		{
			$wrapperPageName = ($_POST['wrapperPageName']) ? $_POST['wrapperPageName'] : '';
			if(!empty($wrapperPageName))
			self::RTapexCreateWrapperPage($wrapperPageName);
		}
		
		$defaultLinks  = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}realty_tech_links where page_type = 'default'");
		$officeLinks   = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}realty_tech_links where page_type = 'office'");
		$customLinks   = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}realty_tech_links where page_type = 'custom'");
		$wrapperLinks  = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}realty_tech_links where page_type = 'wrapper'");
		
		//Check if user is broker
		$isBroker 		= get_option('ApexIdxIsBroker');
		$apBgalUname	= get_option('ApexIdxBrokerGalUname');
		
		include RTAI_PLUGIN_DIR . '/views/apexIdxPages.php';
	}
	
	public static function RTapexIsApiVerified()
	{
		global $wpdb;
		if (get_option('apexAgentApiKey') != '') 
		{
			$apikey 	= get_option('apexAgentApiKey');
			$existApi	= $wpdb->get_var("SELECT * from ".$wpdb->prefix."realty_tech_api WHERE api = '$apikey'");
			if(!$existApi)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	
	public static function RTapexSearchForm()
	{
		ob_start();
		$isIsApiVerified = self::RTapexIsApiVerified();
		if(!$isIsApiVerified)
		{
			return;
		}
		
		wp_enqueue_script('jquery');
		
		wp_localize_script( 'apexIdxsearchBoxWidget', 'searchFormParams', array(
        'ApexIdxDomainUrl' => get_option('ApexIdxDomainUrl')
        ) );
		
		wp_enqueue_style('apexIdxSearchBoxStyle');
		wp_enqueue_script('jquery-ui-autocomplete');
		wp_enqueue_script('apexIdxsearchBoxWidget');
		wp_enqueue_style('apexIdxGlobalCSS');
		
		include RTAI_PLUGIN_DIR . 'views/apexIdxSearchFormWidget.php';
		
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	} 
	
	public static function RTapexSlider($atts){
		ob_start();
		global $wpdb;
		$isIsApiVerified = self::RTapexIsApiVerified();
		if(!$isIsApiVerified)
		{
			return;
		}
		
		wp_enqueue_script('jquery');
		
		$apexIdxDomainUrl = get_option('ApexIdxDomainUrl');
		$idxDomainUrlResults = $apexIdxDomainUrl.'homedetails/';
		
		if($atts['type'] == 'c')
		{
			$url  = $apexIdxDomainUrl.'webservices/getcustomLinkProperties.php';
		}
		else if($atts['type'] == 'sold')
		{
			$url  = $apexIdxDomainUrl.'webservices/getSoldProperties.php';
		}
		else if($atts['type'] == 'fOffices')
		{
			$url  = $apexIdxDomainUrl.'webservices/getFeaturedOffices.php';
		}
		else
		{
			$url  = $apexIdxDomainUrl.'webservices/getFeaturedProperties.php';
		}
		$eleSubId = uniqid();
		
		wp_enqueue_style('apexIdxFPSlider');
		wp_enqueue_script('apexIdxInitializeSlider');
		wp_enqueue_style('apexIdxGlobalCSS');
		?>
		<div>
			<div id="fpSliderapexidx-rfs">
				<div>
					<div>
						<div class="apexidx-rfsspan12">
							<div id="fpSlides<?php echo $eleSubId; ?>" class="owl-carousel">
								<img style="position:relative; margin: 0 auto; top: 2%;margin-left:50%;float:left;" src="<?php echo plugins_url( 'images/loading.gif', __FILE__ ) ?>" alt="loading" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><style>@media (max-width:550px){.slider-grid-img{height:60vw !important;}#apexidx-mgfs-slider{padding: 0px 10px;}}</style>
			<script type="text/javascript">
			<!-- 
			window.addEventListener("load",function(event) {
				jQuery(document).ready(function(s){var a={action:"getListing",type:"<?php echo $atts['type']; ?>",id:<?php echo ($atts['type'] == 'c') ? $atts['id'] : 0; ?>,listingPerPage:20};s.ajax({type:"POST",url:"<?php echo $url; ?>",dataType:"json",data:a,beforeSend:function(){}}).done(function(a){var e="<?php echo $idxDomainUrlResults ?>",d=a,r="",l="https://apexidx.com/custom/imageResizer/resize2.php?w=304&h=203&img=";for(s(window).width()<641&&(l="https://apexidx.com/custom/imageResizer/resize2.php?w=420&h=250&img="),i=0;i<d.length;i++)r+='<div><div class="apexidx-rfsslide-spacing"><a href="'+e+d[i].pUrl+'"><img  src="'+d[i].firstImage+'" class="slider-grid-img" style="height:255px;" height="255" alt="Touch" /></a><div class="apexidx-rfsslide-detail-sec"><a href="'+e+d[i].pUrl+'">'+d[i].addre+'</a><a class="apexidx-rfswidgetcolorbeds" href="'+e+d[i].pUrl+'"><span class="apexidx-rfsfontbold ">'+d[i].beds+'</span><span class="apexidx-rfsslidermarright"> bd</span> <span class="apexidx-rfsfontbold">'+d[i].baths+'</span><span class="apexidx-rfsslidermarright"> ba</span><span class="apexidx-rfsfontbold" >'+d[i].sqFt+'</span><span class="apexidx-rfsslidermarright"> sq ft</span> <span class="apexidx-rfspricefontsize"> $'+d[i].currentPrice+'</span></a></div></div><a href="'+e+d[i].pUrl+'"><div class="imgMDescription"></div><div class="apexidx-mas-rdetailview">View Details</div></a></div>';var items=1,sliderWidth=s("#fpSliderapexidx-rfs").width(),navDots=!0;if(sliderWidth<500&&sliderWidth>0)var items=1,navDots=!1;else if(sliderWidth<1e3&&sliderWidth>499)var items=2,navDots=!0;else var items=3,navDots=!0;s("#fpSlides<?php echo $eleSubId; ?>").html(r),s("#fpSlides<?php echo $eleSubId; ?>").owlCarousel({loop:1,items:items,dots:navDots,margin:0,autoplay:!0,autoplayHoverPause:!0,autoplayTimeout:7e3,slideBy:1})})});
			},false);
			//-->
			</script>
	<?php			 
		return ob_get_clean();
	}
	
	public static function RTapexGrid($atts)
	{
		ob_start();
		global $wpdb;
		
		$isIsApiVerified = self::RTapexIsApiVerified();
		
		if(!$isIsApiVerified)
		{
			return;
		}
		$apexIdxDomainUrl = get_option('ApexIdxDomainUrl');
		$idxDomainUrlResults = $apexIdxDomainUrl.'homedetails/';
		
		if($atts['type'] == 'c')
		{
			$url  = $apexIdxDomainUrl.'webservices/getcustomLinkProperties.php';
		}
		else if($atts['type'] == 'sold')
		{
			$url  = $apexIdxDomainUrl.'webservices/getSoldProperties.php';
		}
		else if($atts['type'] == 'fOffices')
		{
			$url  = $apexIdxDomainUrl.'webservices/getFeaturedOffices.php';
		}
		else
		{
			$url  = $apexIdxDomainUrl.'webservices/getFeaturedProperties.php';
		}
		
		wp_enqueue_script('jquery');
		wp_enqueue_style('apexIdxFPSliderGrid');
		wp_enqueue_style('apexIdxGlobalCSS');
		$eleSubId = uniqid();
		?>
		 <div id="apexidx-mgfs-slider<?php echo $eleSubId ?>" class="apexidx-mgfs-wrap"><img style="position:relative; margin: 0 auto; top: 2%;margin-left:50%;float:left;" src="<?php echo plugins_url( 'images/loading.gif', __FILE__ ) ?>" alt="loading" /></div><style>@media (max-width:550px){.slider-grid-img{height:60vw !important;}#apexidx-mgfs-slider{padding: 0px 10px;}}</style>
		<script type="text/javascript">
			<!-- 
			window.addEventListener("load",function(event) {
				jQuery(document).ready(function(a){var s={action:"getListing",type:"<?php echo $atts['type']; ?>",id:<?php echo ($atts['type'] == 'c') ? $atts['id'] : 0; ?>,listingPerPage:12};a.ajax({type:"POST",url:"<?php echo $url; ?>",dataType:"json",data:s,beforeSend:function(){}}).done(function(s){var d="<?php echo $idxDomainUrlResults ?>",e=s,r="",t="https://apexidx.com/custom/imageResizer/resize3.php?w=304&h=203&img=";for(a(window).width()<641&&(t="https://apexidx.com/custom/imageResizer/resize3.php?w=420&h=250&img="),i=0;i<e.length;i++)r+='<div class="apexidx-mgfs-box"><div class="apexidx-mgfs-boxInner"><a href="'+d+e[i].pUrl+'"><img height="255" style="height:261px" class="slider-grid-img" src="'+e[i].firstImage+'" alt="Touch" /></a><a href="'+d+e[i].pUrl+'$detailViewId"><div class="apexidx-mgfs-titleBox">'+e[i].addre+'</a><div class="apexidx-mgfs-homeaddress"><a class="apexidx-mgfs-widgetcolorbeds" href="'+d+e[i].pUrl+'"><span class="apexidx-mgfs-fontbold">'+e[i].beds+'</span><span class="apexidx-mgfs-slidermarright"> bd</span> <span class="apexidx-mgfs-fontbold">'+e[i].baths+'</span><span class="apexidx-mgfs-slidermarright"> ba </span><span class="apexidx-mgfs-fontbold" >'+e[i].sqFt+'</span><span class="apexidx-mgfs-slidermarright"> sq ft </span> <span class="apexidx-mgfs-pricefontsize"> $'+e[i].currentPrice+'</span></a></div></div><a href="'+d+e[i].pUrl+'"><div class="imgDescription"></div><div class="apexidx-mgfs-rdetailview">View Details</div></a></div></div>';a("#apexidx-mgfs-slider<?php echo $eleSubId ?>").html(r)})});
			},false);
			//-->
		</script>
		<?php
		return ob_get_clean();
	}
	
	public static function RTapexFeaturedMarket()
	{
		ob_start();
		$isIsApiVerified = self::RTapexIsApiVerified();
		if(!$isIsApiVerified)
		{
			return;
		}
		
		wp_enqueue_script('jquery');
		
		wp_localize_script( 'apexIdxFeaturedMarket', 'fMarketParams', array(
        'ApexIdxDomainUrl' => get_option('ApexIdxDomainUrl')
        ) );
		
		wp_enqueue_style('apexIdxFeaturedMarket');
		wp_enqueue_script('apexIdxFeaturedMarket');
		wp_enqueue_style('apexIdxGlobalCSS');
		
		include RTAI_PLUGIN_DIR . 'views/apexIdxFeaturedMarket.php';
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	
	public static function RTapexUpdateLinksOnDeletePage($postID)
	{
	   global $wpdb;
		
	   $row = $wpdb->get_row("SELECT page_type FROM ".$wpdb->prefix."realty_tech_links WHERE post_id = '{$postID}' ", ARRAY_A);
      
	   if($row['page_type'] == 'wrapper')
		{
			$wpdb->query("DELETE FROM ".$wpdb->prefix."realty_tech_links where post_id = '{$postID}' AND page_type = 'wrapper'");
			
		}
		else
		{
			$wpdb->update(
				$wpdb->prefix."realty_tech_links",
				array(
					'post_id' => NULL
				),
				array(
					'post_id' => $postID
				),
				array(
					'%d'
				),
				array(
					'%d'
				)
			);
		}
	}
	
	public static function RTapexUpdateWrapperPage($postId)
	{
		global $wpdb;
		
		if (wp_is_post_revision($postId))
		return;	
		
	   $row = $wpdb->get_row("SELECT page_type FROM ".$wpdb->prefix."realty_tech_links WHERE post_id = '{$postId}' ", ARRAY_A);
       

	   if($row && $row['page_type'] == 'wrapper')
		{
			
			$postTitle = get_the_title($postId);
			$postUrl   = get_permalink($postId);
			//Update links table add genrated post id 
			$wpdb->update(
				$wpdb->prefix."realty_tech_links",
				array(
					'url' => $postUrl,
					'link_name' => $postTitle,
					'link_id' => $postTitle
				),
				array(
					'post_id' => $postId
				),
				array(
					'%s',
					'%s',
					'%s'
				),
				array(
					'%d'
				)
			);
		
		}
	}
	
	public static function RTapexSetNewPageLink($newlink, $pid)
	{
		global $wpdb;
		$row = $wpdb->get_row("SELECT url FROM ".$wpdb->prefix."realty_tech_links WHERE post_id = '{$pid}' and page_type != 'office' ", ARRAY_A);

		if($row && $row['url'] && !empty($row['url']))
		{
			$newlink = $row['url'];
		}
		
		return $newlink;
	}
	
	public static function RTapexFilterWrapperPage($pages)
	{
		global $wpdb;
		
		$pages = array_values($pages);
		
		$row = $wpdb->get_row("SELECT post_id FROM ".$wpdb->prefix."realty_tech_links WHERE page_type='wrapper'", ARRAY_A);
        
		if($row) 
		{
			
			$pagesArray = $pages;
			for($i=0; $i< count($pages); $i++)
			{
				if($pages[$i]->ID == $row['post_id'])
				{
					unset($pagesArray[$i]);
				}
			}
			
			return $pagesArray;
		} 
		else 
		{
			return $pages;
		}
		
	}
	
	public static function RTapexQueryvars( $qvars ) {
		$qvars[] = 'agentId';
		return $qvars;
	}
	
	public static function RTapexFilterAgentPage($content)
	{
		global $wpdb, $wp_query;
		$post = get_post();
		
		$apOfficeInfo = $wpdb->get_results("SELECT link_id,post_id,url FROM ".$wpdb->prefix."realty_tech_links WHERE page_type='office' and post_id is not null", ARRAY_A);
        
		//Check if current page is added as a office page in database
		$apIsPageOffice = array_search($post->ID, array_map(function ($v){ return $v['post_id']; }, $apOfficeInfo));
		
		if($apIsPageOffice !== false)
		{
			//Get the broker gallery user name
			$apBrokerGalUname = trim(get_option('ApexIdxBrokerGalUname'));
			
			//Get Active Offices List
			$apActiveOffices 	= implode(",",array_map(function ($v){ return $v['link_id']; }, $apOfficeInfo));
			
			//Get Active Offices URL
			$apActiveOfficesURL = implode(",",array_map(function ($v){ return $v['url']; }, $apOfficeInfo));
			
			//Get agent page URL
			$apAgentPageUrl = esc_url(get_permalink($post->ID));
			
			//Check current domain scheme i.e. http|https
            $apCurDomScheme =  (is_ssl() == true) ? 'https:' : 'http:' ;
			
			//Check if it is a gallery or single agent detail page
			if(isset($wp_query->query_vars['agentId']))
			{
				$apUrlForGallery =  $apCurDomScheme."//gallery.realtytech.com/$apBrokerGalUname/iframe/?v=agent&galleryFor=apexIDX&id=".$wp_query->query_vars['agentId'];
			}
			else
			{
				$apUrlForGallery =  $apCurDomScheme."//gallery.realtytech.com/$apBrokerGalUname/iframe/?v=gallery&galleryFor=apexIDX&oid=".$apOfficeInfo[$apIsPageOffice]['link_id'];
			}
			
			//Set header for api response
			$headers = array(
				'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
				'outputtype' => 'text/html'
			);
			
			// Send api request to get gallery information
			$apGalleryApiResponse = wp_remote_post( $apUrlForGallery, array(
				'method' => 'POST',
				'timeout' => 120,
				'sslverify' => false,
				'headers' => $headers,
				'body' => array( 'agentPageUrl' => $apAgentPageUrl, 'apActiveOffice' => $apActiveOffices, 'apActiveOfficesURL'=>$apActiveOfficesURL ),
				'cookies' => array()
				)
			);
			
			//Check if response is valid, if not then through the error
			try 
			{
				if(is_array($apGalleryApiResponse) && array_key_exists('body', $apGalleryApiResponse ))
				{
					$apGalleryHTML = $apGalleryApiResponse['body'];
					return $apGalleryHTML;
				}
				else
				{
					return 'Agent gallery Could not be fetched';
				}					
			} 
			catch (Exception $ex) 
			{
				return 'Agent gallery Could not be fetched';
			} 
		}
		else
		{
			return $content;
		}
		
		
	}
	
	public static function RTapexManagePages($selectedPages, $pageType)
	{
		global $wpdb;
		$links = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}realty_tech_links where page_type = '{$pageType}'");
		
		foreach($links as $link)
		{
			if(in_array($link->link_id, $selectedPages))
			{
				self::RTapexInsertUpdatePage($link);
			}
			else
			{
				($link->post_id != NULL) ? self::RTapexDeletePage($link) : '';
			}
	
		}
		echo '<div class="updated"><p>your selection has been updated successfully</p></div>';
	}
	
	public static function RTapexCreateWrapperPage($pageName)
	{
		global $wpdb;
		
		$pageContent 	= '<div id="apexIdxMiddle" style="display: none;"></div>';
		$pageTitle 		= $pageName ;
		$wrapperPage = array(
			'post_title' => $pageTitle,
			'post_name' =>  sanitize_title_with_dashes($pageTitle,'','save'),
			'post_content' => $pageContent,
			'post_type' => 'page',
			'post_status' => 'publish'
		);
		
		$wrapperPageId   = wp_insert_post($wrapperPage);
		$wrapperPageUrl  = get_permalink($wrapperPageId);
		
		//Insert into links table
		$wpdb->insert(
			$wpdb->prefix."realty_tech_links",
			array(
				'url' =>  $wrapperPageUrl,
				'link_id' =>$pageName,
				'post_id' => $wrapperPageId,
				'link_name' =>$pageName,
				'page_type' =>'wrapper'
			),
			array(
				'%s',
				'%s',
				'%d',
				'%s',
				'%s'
			)
		);
			
	}
	
	public static function RTapexCreateOfficePage($selectedPages, $pageType)
	{
		global $wpdb;
		
		$offices = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}realty_tech_links where page_type = '{$pageType}'");
		
		foreach($offices as $office)
		{
			if(in_array($office->link_id, $selectedPages))
			{
				self::RTapexInsertUpdatePage($office);
			}
			else
			{
				($office->post_id != NULL) ? self::RTapexDeletePage($office) : '';
			}
		}
		
		echo '<div class="updated"><p>your selection has been updated successfully</p></div>';
	}
	
	
	public static function RTapexInsertUpdatePage($link)
	{
		global $wpdb;
		$row = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."realty_tech_links WHERE link_id = '{$link->link_id}' ", ARRAY_A);
		
		if($row['post_id'] === NULL || empty($row['post_id']))
		{
			//Insert into post table
			$wpdb->insert(
				$wpdb->posts,
				array(
					'post_title' => $row['link_name'],
					'post_type' => 'page',
					'post_name' => sanitize_title_with_dashes($row['link_name'],'','save')
				),
				array(
					'%s',
					'%s',
					'%s'
				)
			);
			$postId	= $wpdb->insert_id;
			$url	 	= ($row['page_type'] == 'office' ? get_permalink($postId) : $link->url);
			//Update links table add genrated post id 
			$wpdb->update(
				$wpdb->prefix."realty_tech_links",
				array(
					'post_id' => $postId,
					'url'=>$url
				),
				array(
					'link_id' => $link->link_id
				),
				array(
					'%d',
					'%s'
				),
				array(
					'%s'
				)
			);
		}
		else 
		{
			
			$wpdb->update(
				$wpdb->posts,
				array(
					'post_title' => $row['link_name'],
					'post_type' => 'page',
					'post_name' => sanitize_title_with_dashes($row['link_name'],'','save')
				),
				array(
					'ID' => $row['post_id']
				),
				array(
					'%s',
					'%s',
					'%s'
				),
				array(
					'%d'
				)
			);
			
		}
	}
	
	public static function RTapexUpdateOffice($office)
	{
		global $wpdb;
		if($row = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."realty_tech_links WHERE link_id = '{$office['link_id']}' ", ARRAY_A))
		{
			
			$apOfficeUrl	= '';
			
			if($row['post_id'] != NULL && !empty($row['post_id']))
			{
				//Update post related to this link
				$wpdb->update(
					$wpdb->posts,
					array(
						'post_title' => $office['link_name'],
						'post_type'  => 'page',
						'post_name'  => sanitize_title_with_dashes($office['link_name'],'','save')
					),
					array(
						'ID' => $row['post_id']
					),
					array(
						'%s',
						'%s',
						'%s'
					),
					array(
						'%d'
					)
				);
				//Get new url for page
				$apOfficeUrl = get_permalink($row['post_id']);
			}
			
			//Update links table add genrated post id
			$wpdb->update(
				$wpdb->prefix."realty_tech_links",
				array(
					'link_name'  => $office['link_name'],
					'url'		 => $apOfficeUrl
				),
				array(
					'link_id' => $office['link_id']
				),
				array(
					'%s',
					'%s'
				),
				array(
					'%s'
				)
			);
			
			
		}
		else 
		{
			//Insert into links table
			$wpdb->insert(
				$wpdb->prefix."realty_tech_links",
				array(
						'link_id'   =>$office['link_id'],
						'link_name' =>$office['link_name'],
						'page_type' =>'office'
				),
				array(
					'%s',
					'%s',
					'%s'
				)
			);
			
		}
	}
	
	public static function RTapexUpdateCustomLinks($link)
	{
	
		global $wpdb;
		if($row = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."realty_tech_links WHERE link_id = '{$link['link_id']}' ", ARRAY_A))
		{
			//Update links table add genrated post id 
			$wpdb->update(
				$wpdb->prefix."realty_tech_links",
				array(
					'url' => $link['url'],
					'link_name'  => $link['link_name'],
					'use_widget' => $link['use_widget'],
				),
				array(
					'link_id' => $link['link_id']
				),
				array(
					'%s',
					'%s',
					'%s'
				),
				array(
					'%d'
				)
			);
		
			
			if($row['post_id'] != NULL && !empty($row['post_id']))
			{
			
				//Update post related to this link
				$wpdb->update(
					$wpdb->posts,
					array(
						'post_title' => $link['link_name'],
						'post_type'  => 'page',
						'post_name'  => sanitize_title_with_dashes($link['link_name'],'','save')
					),
					array(
						'ID' => $row['post_id']
					),
					array(
						'%s',
						'%s',
						'%s'
					),
					array(
						'%d'
					)
				);
			}
		}
		else 
		{
			//Insert into links table
			$wpdb->insert(
				$wpdb->prefix."realty_tech_links",
				array(
						'url' =>  $link['url'],
						'link_id'   =>$link['link_id'],
						'link_name' =>$link['link_name'],
						'page_type' =>'custom',
						'use_widget'=> $link['use_widget']
				),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s'
				)
			);
			
		}
	}
	
	public static function RTapexDeletePage($link)
	{
		global $wpdb;
		wp_delete_post($link->post_id,true);
        $wpdb->query("DELETE from ".$wpdb->prefix."postmeta where post_id = $link->post_id");
		
		//Update links table set post id null
		$wpdb->update(
			$wpdb->prefix."realty_tech_links",
			array(
				'post_id' => null,
				'url' => ($link->page_type == 'office' ? '' : $link->url),
			),
			array(
				'link_id' => $link->link_id
			),
			array(
				'%d',
				'%s'
			),
			array(
				'%s'
			)
		);
		
	}
	
	public static function RTapexGetLatestUpdate($infoFor = 'getLinks')
	{
		$status = RTapexApiAuthentication($infoFor);
		
		if( is_wp_error($status) ) 
		{
			$apiError = $status->get_error_message();
			echo '<div class="error"><p>'.$apiError.'</p></div>';
			return;
		}
		else
		{
			if($infoFor == 'getLinks')
			{
				self::RTapexUpdateLinks($status);
			}
			else
			{
				self::RTapexUpdateOffices($status);
			}
			
			echo '<div class="updated"><p>Your latest update has been fetched successfully. Please take an action</p></div>';
		}
		
	}
	
	public static function RTapexSaveApi($apikey)
	{
		global $wpdb;
		
		$wpdb->insert($wpdb->prefix.'realty_tech_api',
			array('api' => $apikey),
			array('%s')
		);
		
		return $wpdb->insert_id;
	}
	public static function RTapexUpdateLinks($response = array())
	{
		global $wpdb;
		
		
		$links 	= $response->links;
		$cname 	= $response->cname;
		$domain = $response->domain;
		$newLinks = array();
		
		$ApexIdxDomainUrl = '//'.$cname.'.'.$domain.'/idx/';
		update_option('ApexIdxDomainUrl', $ApexIdxDomainUrl);
		
		//update Default Pages url if cname change
		$defaultPages = array('Advanced Search', 'Sold Properties', 'Featured Properties', 'Open Houses', 'Featured Offices');
		
		foreach($defaultPages as $defaultPage)
		{
			//Parse some information before insert
			$dpName = $defaultPage;
			$dpUrl  = '//'.$cname.'.'.$domain.'/idx/'.str_replace(' ','', strtolower($dpName));
			
			$wpdb->update(
				$wpdb->prefix."realty_tech_links",
				array(
					'url' => $dpUrl
				),
				array(
					'link_id' =>str_replace(' ','', $dpName)
				),
				array(
					'%s'
				),
				array(
					'%s'
				)
			);
			
		}
		
		//Create update custom Links Page
		if(count($links) > 0)
		{
			foreach($links as $link)
			{
				$data = array();
				
				//Parse some information before insert
				$name = str_replace('-', ' ', $link->name);
				$url  = '//'.$cname.'.'.$domain.'/idx/c/'.$link->name;
				$data['link_name']  = $name;
				$data['use_widget'] = $link->useAsWidget;
				$data['url']  = $url;
				$data['link_id']   = $link->id;
				$newLinks[] = $link->id;
				self::RTapexUpdateCustomLinks($data);
			}
			
		}
		$SavedLinks  = $wpdb->get_col("SELECT link_id FROM {$wpdb->prefix}realty_tech_links where page_type = 'custom'");

		$deleteLinks = array_diff($SavedLinks, $newLinks);

		if($deleteLinks > 0) 
		{
			self::RTapexDeleteLinksWithPages($deleteLinks);
		}
	}
	
	public static function RTapexUpdateOffices($apResponse = array())
	{
		global $wpdb;
		
		$apOffices		= $apResponse->offices;
		$apGalleryUName	= $apResponse->galleryUName;
		$isBrokerIDX 	= $apResponse->isBrokerIDX;
		$apAllOffices	= array();
		
		
		//Save and update idx broker status and broker gallery username
		update_option('ApexIdxIsBroker', $isBrokerIDX);
		update_option('ApexIdxBrokerGalUname', $apGalleryUName);
		
		
		//Create update custom Links Page
		if(count($apOffices) > 0)
		{
			foreach($apOffices as $apOffice)
			{
				$apData = array();
				
				//Parse some information before insert
				$apData['link_name']	= $apOffice->office_name;
				$apData['link_id']    	= $apOffice->office_id;
				$apAllOffices[] 		= $apOffice->office_id;
				self::RTapexUpdateOffice($apData);
			}
			
		}
		$apSavedOffices  = $wpdb->get_col("SELECT link_id FROM {$wpdb->prefix}realty_tech_links where page_type = 'office'");

		$apDeleteOffice = array_diff($apSavedOffices, $apAllOffices);

		if($apDeleteOffice > 0) 
		{
			self::RTapexDeleteOfiicesWithPages($apDeleteOffice);
		}
	
	}
	
	public static function RTapexDeleteLinksWithPages($deleteLinks)
	{
		
		global $wpdb;
		$linkString = "";

		if(count($deleteLinks) > 0) 
		{
			foreach($deleteLinks as $linkId) 
			{
				$linkString .= "'$linkId',";
			}
			$linkString = rtrim($linkString,',');
			
			$deletePages = $wpdb->get_col("SELECT post_id from ".$wpdb->prefix."realty_tech_links where link_id IN ($linkString) AND post_id != '' AND page_type = 'custom'");

			if($wpdb->query("DELETE from ".$wpdb->prefix."realty_tech_links where link_id IN ($linkString) AND page_type = 'custom'") !== false) {
				foreach($deletePages as $pageId)
				{
					wp_delete_post($pageId,true);
					$wpdb->query("DELETE from ".$wpdb->prefix."postmeta where post_id = $pageId");
				}
			}
			return true;
		}
		return false;
	}
	
	public static function RTapexDeleteOfiicesWithPages($apDeleteOffices)
	{
		
		global $wpdb;
		$apOfficeString = "";

		if(count($apDeleteOffices) > 0) 
		{
			foreach($apDeleteOffices as $apOfficeId) 
			{
				$apOfficeString .= "'$apOfficeId',";
			}
			$apOfficeString = rtrim($apOfficeString,',');
			
			$apDeleteOfficePages = $wpdb->get_col("SELECT post_id from ".$wpdb->prefix."realty_tech_links where link_id IN ($apOfficeString) AND post_id != '' AND page_type = 'office'");

			if($wpdb->query("DELETE from ".$wpdb->prefix."realty_tech_links where link_id IN ($apOfficeString) AND page_type = 'office'") !== false) {
				foreach($apDeleteOfficePages as $apOfficePageId)
				{
					wp_delete_post($apOfficePageId,true);
					$wpdb->query("DELETE from ".$wpdb->prefix."postmeta where post_id = $apOfficePageId");
				}
			}
			return true;
		}
		return false;
	}
	
	public static function RTapexSaveLinks($response = array())
	{
		global $wpdb;
		
		$links 				= $response->links;
		$offices			= $response->offices;
		$cname 				= $response->cname;
		$domain 			= $response->domain;
		$ApexIdxDomainUrl 	= '//'.$cname.'.'.$domain.'/idx/';
		$apGalleryUName 	= $response->galleryUName;
		$isBrokerIDX 		= $response->isBrokerIDX;
		
		//Set some option key for future use
		update_option('ApexIdxDomainUrl', $ApexIdxDomainUrl);
		update_option('ApexIdxIsBroker', $isBrokerIDX);
		update_option('ApexIdxBrokerGalUname', $apGalleryUName);
		
		//Create custom Links Page
		if($links && count($links) > 0)
		{
			foreach($links as $link)
			{
				//Parse some information before insert
				$name = str_replace('-', ' ', $link->name);
				$url  = '//'.$cname.'.'.$domain.'/idx/c/'.$link->name;
				
				//Insert into links table
				$wpdb->insert(
					$wpdb->prefix."realty_tech_links",
					array(
							'url' => $url,
							'link_id' =>$link->id,
							'link_name' =>$name,
							'page_type' =>'custom',
							'use_widget' =>$link->useAsWidget
					),
					array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					)
				);
			}
		}
		
		//Create Default Pages
		$defaultPages = array('Advanced Search', 'Sold Properties', 'Featured Properties', 'Open Houses', 'Featured Offices');
		
		foreach($defaultPages as $defaultPage)
		{
			//Parse some information before insert
			$name = $defaultPage;
			$url  = '//'.$cname.'.'.$domain.'/idx/'.str_replace(' ','', strtolower($name));
			
			$wpdb->insert(
				$wpdb->prefix."realty_tech_links",
				array(
						'url' => $url,
						'link_id' =>str_replace(' ','', $name),
						'link_name' =>$name,
						'page_type' =>'default'
				),
				array(
					'%s',
					'%s',
					'%s',
					'%s'
				)
			);
			
		}
	
		//Create Gallery Pages
		if($isBrokerIDX == 1)
		{
			//Create Gallery offices Page
			if($offices && count($offices) > 0)
			{
				foreach($offices as $office)
				{
					//Insert into links table
					$wpdb->insert(
						$wpdb->prefix."realty_tech_links",
						array(
							'link_id' => $office->office_id,
							'link_name' =>$office->office_name,
							'page_type' =>'office'
						),
						array(
							'%s',
							'%s',
							'%s'
						)
					);
				}
			}
		}
	}
}//End of class RTapexIdxBase