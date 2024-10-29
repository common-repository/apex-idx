<?php  $url = plugins_url('', plugin_basename(dirname(__FILE__))); ?>
<div id="page_sWidget_pWidget" class="apexidx-rsbsearchbox">			 	
			<div class="apexidx-rsbsearch-rt-form">
				<form action="" method="post" name="ApexSearchForm" id="Apexidx-SearchForm">
				<div class="apexidx-mpoint">
					<div class="validationError" style="display:none">
						<div class="apexidx-rsbSearchTextbox">
							<p style="color:red">Empty form can not be send</p>	
						</div>	
					</div>					
						<div class="apexidx-rsbSearchTextbox">
							<div class="apexidx-mtextcode"><input name="auto-search" type="text" value="" placeholder="Search by City, Zip Code or MLS Number" id="auto-search" class="search ui-autocomplete-input"  autocomplete="off"></div>
						<div class="apexidx-AaSBtnPoistion"><button id="apexidx-submitRTSearch" type="button"><img class="apexidx-hide" src="<?php echo $url."/images/search-icon.png" ?>"/><span class="apexidxmsearcspan">Search</span></button> </div>
						 
						</div>	
                           <div class="apexidx-mvalue">						
							<div class="apexidx-rsbleft-search-option">
								<select name="minPrice" id="minPrice">
									<option value="">Min Price</option>
									<option value="100000">$100,000</option>
									<option value="125000">$125,000</option>
									<option value="150000">$150,000</option>
									<option value="175000">$175,000</option>
									<option value="200000">$200,000</option>
									<option value="225000">$225,000</option>
									<option value="250000">$250,000</option>
									<option value="275000">$275,000</option>
									<option value="300000">$300,000</option>
									<option value="325000">$325,000</option>
									<option value="350000">$350,000</option>
									<option value="375000">$375,000</option>
									<option value="400000">$400,000</option>
									<option value="425000">$425,000</option>
									<option value="450000">$450,000</option>
									<option value="475000">$475,000</option>
									<option value="500000">$500,000</option>
									<option value="525000">$525,000</option>
									<option value="550000">$550,000</option>
									<option value="575000">$575,000</option>
									<option value="600000">$600,000</option>
									<option value="625000">$625,000</option>
									<option value="650000">$650,000</option>
									<option value="675000">$675,000</option>
									<option value="700000">$700,000</option>
									<option value="725000">$725,000</option>
									<option value="750000">$750,000</option>
									<option value="775000">$775,000</option>
									<option value="800000">$800,000</option>
									<option value="825000">$825,000</option>
									<option value="850000">$850,000</option>
									<option value="875000">$875,000</option>
									<option value="900000">$900,000</option>
									<option value="925000">$925,000</option>
									<option value="950000">$950,000</option>
									<option value="975000">$975,000</option>
									<option value="1000000">$1,000,000</option>
									<option value="1250000">$1,250,000</option>
									<option value="1500000">$1,500,000</option>
									<option value="1750000">$1,750,000</option>
									<option value="2000000">$2,000,000</option>
									<option value="2500000">$2,500,000</option>
									<option value="3000000">$3,000,000</option>
									<option value="3500000">$3,500,000</option>
									<option value="4000000">$4,000,000</option>
									<option value="4500000">$4,500,000</option>
									<option value="5000000">$5,000,000</option>
									<option value="6000000">$6,000,000</option>
									<option value="7000000">$7,000,000</option>
									<option value="8000000">$8,000,000</option>
									<option value="9000000">$9,000,000</option>
									<option value="10000000">$10,000,000+</option>
								</select>
							</div> 
							<div class="apexidx-rsbleft-search-option apexidx-anspace apexidx-anfirst">
								<select name="maxPrice" id="maxPrice">
									<option value="">Max Price</option>
									<option value="100000">$100,000</option>
									<option value="125000">$125,000</option>
									<option value="150000">$150,000</option>
									<option value="175000">$175,000</option>
									<option value="200000">$200,000</option>
									<option value="225000">$225,000</option>
									<option value="250000">$250,000</option>
									<option value="275000">$275,000</option>
									<option value="300000">$300,000</option>
									<option value="325000">$325,000</option>
									<option value="350000">$350,000</option>
									<option value="375000">$375,000</option>
									<option value="400000">$400,000</option>
									<option value="425000">$425,000</option>
									<option value="450000">$450,000</option>
									<option value="475000">$475,000</option>
									<option value="500000">$500,000</option>
									<option value="525000">$525,000</option>
									<option value="550000">$550,000</option>
									<option value="575000">$575,000</option>
									<option value="600000">$600,000</option>
									<option value="625000">$625,000</option>
									<option value="650000">$650,000</option>
									<option value="675000">$675,000</option>
									<option value="700000">$700,000</option>
									<option value="725000">$725,000</option>
									<option value="750000">$750,000</option>
									<option value="775000">$775,000</option>
									<option value="800000">$800,000</option>
									<option value="825000">$825,000</option>
									<option value="850000">$850,000</option>
									<option value="875000">$875,000</option>
									<option value="900000">$900,000</option>
									<option value="925000">$925,000</option>
									<option value="950000">$950,000</option>
									<option value="975000">$975,000</option>
									<option value="1000000">$1,000,000</option>
									<option value="1250000">$1,250,000</option>
									<option value="1500000">$1,500,000</option>
									<option value="1750000">$1,750,000</option>
									<option value="2000000">$2,000,000</option>
									<option value="2500000">$2,500,000</option>
									<option value="3000000">$3,000,000</option>
									<option value="3500000">$3,500,000</option>
									<option value="4000000">$4,000,000</option>
									<option value="4500000">$4,500,000</option>
									<option value="5000000">$5,000,000</option>
									<option value="6000000">$6,000,000</option>
									<option value="7000000">$7,000,000</option>
									<option value="8000000">$8,000,000</option>
									<option value="9000000">$9,000,000</option>
									<option value="10000000">$10,000,000+</option>
								</select>
							</div>

							<div class="apexidx-rsbleft-search-option apexidx-anspace">
								<select name="beds" id="page_sWidget_listMinBed">
									<option value="">Beds</option>
									<option value="1">1 or more</option>
									<option value="2">2 or more</option>
									<option value="3">3 or more</option>
									<option value="4">4 or more</option>
									<option value="5">5 or more</option>
									<option value="6">6 or more</option>
									<option value="7">7 or more</option>
									<option value="8">8 or more</option>
									<option value="9">9 or more</option>
								</select>
							</div>

							<div class="apexidx-rsbleft-search-option apexidx-anspace apexidx-anfirst">
								<select name="baths" id="page_sWidget_listMinBath">
									<option value="">Baths</option>
									<option value="1">1 or more</option>
									<option value="2">2 or more</option>
									<option value="3">3 or more</option>
									<option value="4">4 or more</option>
									<option value="5">5 or more</option>
									<option value="6">6 or more</option>
									<option value="7">7 or more</option>
									<option value="8">8 or more</option>
									<option value="9">9 or more</option>
								</select>
							</div>
							</div>
						
						<div class="apexidx-AaSLinkPoistion"><a href="" class="apexidx-AadvancedSearchLink">Advanced Search</a></div>
				</div>
				</form>
			</div>		 
	</div>
 
 