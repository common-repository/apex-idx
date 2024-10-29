<div class="row apexidx-awidgetsMarginHeader">
	<div class="col-md-12">
		<span class="apexidx-acoHeading"> <b>Apex Idx </b>by RealtyTech </span>
	</div>
</div>
<div class="container-fluid widgetsWidthContainer">
   <div class="row">
      <div class="col-md-11 apexidx-acarouselText">
         <span  class="apexidx-aboxHeading">Manage your Default Pages </span>
         </br>
         <span class="apexidx-aboxDesc">Featured Properties, Featured Offices, Sold Properties, Open Offices : These pages are available by default in Apex Idx. To make wordpress page just check the checkboxes and click on "Save Changes" button, now pages will start appearing as page in wordpress page section. These pages can be easily added to your webSite navigation. You can manage any of the below mentioned pages by going to your <a href="//apexidx.com/admin/login.php" target="_blank">Apex IDX Admin</a> account.</span>
      </div>
   </div>
   <div class="row apexidx-ablock">
      <div class="col-md-11 apexidx-ablockcol">
         <div class="row apexidx-ablockcolrow">
            <div class="col-md-12" >
               <h4 class="apexidx-ablockHdng">Default Pages</h4>
            </div>
         </div>
         <div class="row apexidx-ablockcol2">
            <div class="col-md-12">
               <form name="defaultLinks" id="defaultLinks" method="post">
                  <?php wp_nonce_field('update-default-pages'); ?>
                  <?php
                     if(!empty($defaultLinks))
                     {
                     	foreach($defaultLinks as $defaultLink)
                     	{
                     		$isChecked = empty($defaultLink->post_id) ? '' : 'checked = "checked"';
                     	?>
                  <div class="apexidx-links">
                     <input type="checkbox" name="defaultLinks[]" value="<?php echo $defaultLink->link_id; ?>" <?php echo $isChecked ;?> />
                     <label class="linkLabel"><?php echo $defaultLink->link_name; ?></label>
                  </div>
                  <?php		
                     }
                     ?>
                  <input type="hidden" name="defaultPage" id="defaultPage" value="1" />
                  <div class="apexidx-asaveChngBlock">
                     <input type="submit" class="btn btn-primary apexidx-asavechng"  name="submit" id="submit" value="Save Changes" />
                  </div>
                  <?php
                     }
                     else
                     {
                     	echo "<p>No Default Links Found</p>";
                     }
                     ?>
               </form>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-11 apexidx-acustomLink">
         <span  class="apexidx-aboxHeading">Manage your Offices</span>
         </br>
         <span class="apexidx-aboxDesc">If you are a broker then please click on "Refresh Gallery Offices" button to get latest changes from broker gallery account These pages can be easily added to your webSite navigation. To make wordpress page just check the checkboxes and click on "Save Changes" button, now pages will start appearing as page in wordpress page section. You can manage any of the below mentioned offices by going to your Broker Gallery Admin account.
      </div>
   </div>
   <div class="row apexidx-acustomLinkRefresh">
      <div class="col-md-11">
         <form action="" name="getBrokerInfo" id=" getBrokerInfo" method="post" action="">
            <input type="hidden" name="getBrokerInfo" id="getBrokerInfo" value="1" />
            <input type="submit" class="btn btn-info btn-lg apexidx-arefreshCsLink apexidx-arefreshCsLinkPos" name="submit" id="submit" value="Refresh Gallery Offices" />
         </form>
		 <?php if($apBgalUname !='' && $isBroker == 1){?>
			<a href="<?php echo '//gallery.realtytech.com/'.$apBgalUname.'/broker/' ?>" target="_blank">Click to manage your Broker Gallery Account</a>
		 <?php }?>
      </div>
   </div>
   <div class="row apexidx-ablock">
      <div class="col-md-11 apexidx-ablockcol">
         <div class="row apexidx-ablockcolrow">
            <div class="col-md-12" >
               <h4 class="apexidx-ablockHdng">Broker Gallery Offices</h4>
            </div>
         </div>
         <div class="row apexidx-ablockcol2">
            <div class="col-md-12">
               <form name="galleryLinks" id="galleryLinks" method="post">
                  <?php wp_nonce_field('update-default-pages'); ?>
                  <?php
                     if(!empty($officeLinks))
                     {
                     	
                     	foreach($officeLinks as $officeLink)
                     	{
                     		$isChecked = empty($officeLink->post_id) ? '' : 'checked = "checked"';
                     	?>
                  <div class="apexidx-links">
                     <input type="checkbox" name="officeLinks[]" value="<?php echo $officeLink->link_id; ?>" <?php echo $isChecked ;?> />
                     <label class="linkLabel"><?php echo $officeLink->link_name; ?></label>
                  </div>
                  <?php		
                     }
                     ?>
                  <input type="hidden" name="officePage" id="officePage" value="1" />
                  <div class="apexidx-asaveChngBlock">
                     <input type="submit" class="btn btn-primary apexidx-asavechng"  name="submit" id="submit" value="Save Changes" />
                  </div>
                  <?php
                     }
                     else
                     {
                     	if($isBroker == 1) 
                     	{
                     		echo "<p>There is no office found in Your Gallery</p>";
                     	}
                     	else
                     	{
                     		echo "<strong>Call-877 832 4428 to upgrade account</strong>";
                     	}
                     }
                     ?>
               </form>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-11 apexidx-acustomLink">
         <span  class="apexidx-aboxHeading">Add your Custom Links </span>
         </br>
         <span class="apexidx-aboxDesc"> Please Click on "Refresh Custom Link" button to get latest changes from your Apex Idx admin account. These pages can be easily added to your webSite navigation. To make wordpress page just check the checkboxes and click on "Save Changes" button, now pages will start appearing as page in wordpress page section. These pages can be added to you Website Navigation. You can manage any of the below mentioned pages by going to your <a href="//apexidx.com/admin/login.php" target="_blank">Apex IDX Admin</a> account.</span>
      </div>
   </div>
    <div class="row apexidx-acustomLinkRefresh">
      <div class="col-md-11">
         <form action="" name="getLatestUpdate" id="getLatestUpdate" method="post" action="">
            <input type="hidden" name="getLatestSelection" id="getLatestSelection" value="1" />
            <input type="submit" class="btn btn-info btn-lg apexidx-arefreshCsLink apexidx-arefreshCsLinkPos" name="submit" id="submit" value="Refresh Custom Links" />
         </form>
      </div>
   </div>
   <div class="row apexidx-acustomLinkRefresh">
      <div class="col-md-11 apexidx-ablockcol">
         <div class="row apexidx-ablockcolrow">
            <div class="col-md-12" >
               <h4 class="apexidx-ablockHdng">Custom Links</h4>
            </div>
         </div>
         <div class="row apexidx-ablockcol2">
            <div class="col-md-12">
               <form name="customLinks" id="customLinks" method="post">
                  <?php wp_nonce_field('update-custom-pages'); ?>
                  <?php
                     if(!empty($customLinks))
                     {
                     	foreach($customLinks as $customLink)
                     	{
                     		$isChecked = empty($customLink->post_id) ? '' : 'checked = "checked"';
                     	?>
                  <div class="apexidx-links">
                     <input type="checkbox" name="customLinks[]" value="<?php echo $customLink->link_id; ?>" <?php echo $isChecked ;?> />
                     <label  class="linkLabel"><?php echo $customLink->link_name; ?></label>
                  </div>
                  <?php		
                     }
                     ?>
                  <div class="apexidx-asaveChngBlock">
                     <input type="hidden" name="customPage" id="customPage" value="1" />
                     <input class="btn btn-primary apexidx-asavechng" type="submit" name="submit" id="submit" value="Save Changes" />
                  </div>
                  <?php
                     }
                     else
                     {
                     	echo "<p>No Custom Links Found</p>";
                     }
                     
                     ?>
               </form>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-11 apexidx-acustomLinkRefresh">
         <span class="apexidx-aboxHeading">Wrapper integration</span>
         </br>
         <span class="apexidx-aboxDesc">This is the page internally used by plugin to pull your header/footer HTML and show into your IDX pages. Enter the name of wrapper page and click on the "Save changes" button, then you will see a link of wrapper page. Copy the URL of Wrapper page then go to your <a href="//apexidx.com/admin/login.php" target="_blank">Apex IDX Admin</a> account. Now goto Manage Wrapper under SETTINGS tab and put Wrapper URL into textbox and click on save button.</span>
      </div>
   </div>
   <div class="row apexidx-acustomLinkRefresh">
      <div class="col-md-11 apexidx-ablockcol">
         <div class="row apexidx-ablockcolrow">
            <div class="col-md-12" >
               <h4 class="apexidx-ablockHdng">Create Wrapper Page</h4>
            </div>
         </div>
         <div class="row apexidx-ablockcol2">
            <?php if(empty($wrapperLinks))
               {?>
            <div class="col-md-12">
               <form name="wrapperForm" id="wrapperForm" method="post">
                  <input type="hidden" name="wrapperPage" id="wrapperPage" value="1" />
                  <label>Enter your Page Name:</label>
                  <input type="text" name="wrapperPageName" id="wrapperPageName" value="" placeholder="Enter your page name" required="" />
                  <input class="btn btn-primary apexidx-asavechng" type="submit" name="submit" id="submit" value="Save Changes" />
               </form>
            </div>
            <?php } if(!empty($wrapperLinks)){?>
            <div class="col-md-12">
               Your page URL is: <strong><?php echo $wrapperLinks[0]->url; ?></strong>
            </div>
            <?php } ?>
         </div>
      </div>
   </div>
</div>