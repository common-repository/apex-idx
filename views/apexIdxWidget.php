<?php  $url = plugins_url('', plugin_basename(dirname(__FILE__))); ?>

<div class="row apexidx-awidgetsMarginHeader">
	<div class="col-md-12">
			<span class="apexidx-acoHeading"> <b>Apex Idx </b>by RealtyTech </span>
	</div>
</div>
<div class="container-fluid widgetsWidthContainer">
	<div class="row">
		<div class="col-md-12">
			<div class="apexidx-awidgetText"><h1>Widgets</h1> 
				<form action="" name="getLatestUpdate" id="getLatestUpdate" method="post">
					<input type="hidden" name="getLatestSelection" id="getLatestSelection" value="1" />
					<label><strong>To Begin: </strong></label>
					<input type="submit" class="btn btn-info apexidx-arefreshCsLink" style="" name="submit" id="submit" value="Refresh Custom Links" />
				</form>
			</div>
		</div>
	</div>
	
	<div class="row apexidx-aborder">
		<div class="col-md-12">
			<div class="row">
				<div class="row">
					<div class="col-xs-4">
						<div class="apexidx-arowGridInstHedng">
							Instructions
							<div class="apexidx-arowGridInstdesc">
								To use this widget please follow the below instructions:
								click on copy button(or simply copy) and paste the following code into your website code where you want to show the widget.
								<div class="apexidx-arowGridInstdesc2">Animated Slider and Gridview widget are fully resposive.</div>
							</div>
						</div>
					</div>
					<div class="col-xs-4">
						<img class="img-responsive apexidx-asImg" width="300"  src="<?php echo $url."/images/rowslider.jpg" ?>">
						<div class="apexidx-aasText"> Animated Slider<small style="color:#8c8c8c">(Images not to scale)</small> </div>
					</div>
					<div class="col-xs-4">
						<img class="img-responsive" width="300" src="<?php echo $url."/images/GridSlider.png" ?>">
						<div class="apexidx-aasText">Gridview Widgets<small style="color:#8c8c8c">(Images not to scale)</small></div>
					</div>
				</div>	
					
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive"> 
							<table class="table table-bordered table-striped table-responsive"> 
								<thead> 
									<tr> 
										<th></th> 
										<th>Animated Slider</th> 
										<th>Gridview Widgets</th> 
									</tr> 
								</thead> 
								<tbody> 
									<tr> 
										<th class="text-nowrap" scope="row">Featured Homes-Default</th> 
										<td><span id="defaulteR">[realtyTech-slider type="default"]</span><button class="btn copyClipBoard btn-primary" data-clipboard-action="copy" data-clipboard-target="#defaulteR">Copy</button></td> 
										<td><span id="defaultG">[realtyTech-grid type="default"]</span><button class="btn copyClipBoard btn-primary" data-clipboard-action="copy" data-clipboard-target="#defaultG">Copy</button></td>
									</tr> 
									<tr> 
										<th class="text-nowrap" scope="row">Sold Homes-Default</th> 
										<td><span id="defaulteSS">[realtyTech-slider type="sold"]</span><button class="btn copyClipBoard btn-primary" data-clipboard-action="copy" data-clipboard-target="#defaulteSS">Copy</button></td> 
										<td><span id="defaultSG">[realtyTech-grid type="sold"]</span><button class="btn copyClipBoard btn-primary" data-clipboard-action="copy" data-clipboard-target="#defaultSG">Copy</button></td>
									</tr> 
									<tr> 
										<th class="text-nowrap" scope="row">Featured Offices-Default</th> 
										<td><span id="defaulteFS">[realtyTech-slider type="fOffices"]</span><button class="btn copyClipBoard btn-primary" data-clipboard-action="copy" data-clipboard-target="#defaulteFS">Copy</button></td> 
										<td><span id="defaultFG">[realtyTech-grid type="fOffices"]</span><button class="btn copyClipBoard btn-primary" data-clipboard-action="copy" data-clipboard-target="#defaultFG">Copy</button></td>
									</tr> 
									<?php if(!empty($customLinks))
									  {
										foreach($customLinks as $customLink){
									?>
									<tr>
										<th class="text-nowrap" scope="row"><?php echo $customLink->link_name; ?>- Custom Link</th>
										<td><span id="cR<?php  echo $customLink->link_id; ?>">[realtyTech-slider type="c" id="<?php  echo $customLink->link_id; ?>"]</span><button class="btn copyClipBoard btn-primary" aria-label="Press Ctrl-C to copy" data-clipboard-action="copy" data-clipboard-target="#cR<?php  echo $customLink->link_id; ?>">Copy</button></td> 
										<td><span id="cG<?php  echo $customLink->link_id; ?>">[realtyTech-grid type="c" id="<?php  echo $customLink->link_id;?>"]</span><button class="btn copyClipBoard btn-primary" data-clipboard-action="copy" data-clipboard-target="#cG<?php  echo $customLink->link_id; ?>">Copy</button></td> 
									</tr>
								<?php }} ?>
								</tbody> 
							</table> 
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row apexidx-awidgetmargin apexidx-aborder">
		<div class="col-md-6" >
			<div class="apexidx-asrchBox">
				<div class="apexidx-asrchText">Search Box Widget</div>
					<img class="img-responsive" width="400" src="<?php echo $url."/images/searchwidget.png" ?>">
					<div class="apexidx-asrchText">
							<!-- Button trigger modal -->
							<button type="button" class="btn btn-primary" data-clipboard-action="copy" data-clipboard-target="#searchWidget">Copy Code</button>
							<div class="apex-aidxsortCode" id="searchWidget">[realtyTech-search-form]</div>
					</div>
			</div>
		</div>
					
		<div class="col-md-6" >
			<div class="apexidx-asnpshot">
				<div class="apexidx-asrchText">Market Snapshot Widget</div>
					<img class="img-responsive" width="400"  src="<?php echo $url."/images/snpshot.png" ?>">
				<div class="apexidx-asrchText">
					<button type="button" class="btn btn-primary" data-toggle="modal" data-clipboard-action="copy" data-clipboard-target="#marketSnapShot">Copy Code</button>
					<div class="apex-aidxsortCode" id="marketSnapShot">[realtyTech-featured-market]</div>
				</div>
			</div>
					</div>
					
				</div>
</div>
<!-- Popup Modal for Featured Properties widget -->
<div class="modal fade" id="apexIdxWidget1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Featured Properties</h4>
			</div>
			<div class="modal-body">
				[realtyTech-slider]
			</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
    </div>
  </div>
</div>





   <script>
    var clipboard = new Clipboard('.btn');

    clipboard.on('success', function(e) {
        console.log(e);
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });
    </script>