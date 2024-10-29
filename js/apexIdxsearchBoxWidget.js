jQuery(document).ready(function($){"function"!=typeof String.prototype.trim&&(String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g,"")});jQuery(".apexidx-AadvancedSearchLink").attr("href", searchFormParams.ApexIdxDomainUrl+'advancedsearch');
jQuery("#auto-search").autocomplete({
	source: function (request, response) {
		var apProType = '';
		var apListingStatus = '';
		var apSearchText = jQuery("#auto-search").val(); if (apSearchText.length < 3) { return false; }
		jQuery.ajax({
			type: "POST",
			url: searchFormParams.ApexIdxDomainUrl+"webservices/getListing.php",
			dataType: "JSON",
			data: { proType: apProType, listingStatus: apListingStatus, searchText: apSearchText, action: "getAutoSearchApexVer2" },
			success: function (data) {
				$("#auto-search").removeClass("ui-autocomplete-loading");
				response(data);
			}
		});
	},
	select: function (event, ui) {
		event.preventDefault();
		$(this).attr('data-selected-value',ui.item.value);
		$(this).val(ui.item.label);
	}
});

jQuery("#apexidx-submitRTSearch").click(function(){
	var e="",r="",a="";jQuery(".validationError").hide();
	var i=jQuery('#Apexidx-SearchForm').serializeArray();
	return jQuery.each(i,function(i,u){
		if(jQuery.trim(u.value))
			if("minPrice"==u.name||"maxPrice"==u.name)"minPrice"==u.name?r+=u.value:"maxPrice"==u.name&&(r=""==r?0:r,parseInt(r)>parseInt(u.value)?(maxPrice=r,r=u.value,r+="-"+maxPrice):r+="-"+u.value);
		else if("auto-search"==u.name)
		{
			var sv  = $('#auto-search').data('selected-value');
			if(sv !== undefined && sv.indexOf('addressredirect') > -1)
			{
				var address = u.value.trim().replace(/[, ]+/g, "-").trim();
				var proId   = sv.trim().replace('addressredirect', '')+'$detailViewId';
				dvRedirect  = searchFormParams.ApexIdxDomainUrl + "homedetails/" + address +'/'+ proId;
				return false;
			}
			else
			{
				dvRedirect = "";
				var t=u.value.trim().replace(/\ |\//gi,"-");
				if(sv)
				{
					var t = sv.replace(/-/gi, ".dash.").replace(/\ |\//gi, "-");
				}
				else
				{
					var t = u.value.trim().replace(/-/gi, ".dash.").replace(/\ |\//gi, "-").replace(/#/gi, ".unit.");
				} 
			}
			a+=t+"_autosearch",e+=a
		}
		else e+=""==e?u.value+"_"+u.name:"/"+u.value+"_"+u.name
		
	}),(dvRedirect != '') ? window.location.href = dvRedirect :
(""!=r&&(e+=""==e?e+=r+"_price":"/"+r+"_price"),""==e?(jQuery(".validationError").show(),!1):(e+="/1_p",e=searchFormParams.ApexIdxDomainUrl+"results/"+e,window.location.href=e,!1))}),$(".apexidx-rsbsearch-rt-form").keydown(function(e){return"13"==e.keyCode?(e.preventDefault(),$("#apexidx-submitRTSearch").click(),!1):void 0});});