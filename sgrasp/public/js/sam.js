function toggleSection(div_id, graph) {
   if(document.getElementById('section_'+div_id+'_forecast').style.display=='none'){
      display = "";
      document.getElementById('section_'+div_id+':arrow').src = "/images/rollminus.gif";
   }else{
      display = "none";
      document.getElementById('section_'+div_id+':arrow').src = "/images/rollplus.gif";
   }
   document.getElementById("section_"+div_id+"_forecast").style.display = display;
   if (!graph) {
      document.getElementById("section_"+div_id+"_quota").style.display = display;
      document.getElementById("section_"+div_id+"_percent").style.display = display;
      document.getElementById("section_"+div_id+"_invoiced").style.display = display;
   }
}

function toggleAllSections(graph) {
   imgs_elements = document.getElementsByTagName("img");
   if (document.getElementById('toggle_all_1:arrow').src.match("/images/rollminus.gif")) {
      display = "none";
      src = "/images/rollplus.gif";
   }else{
      display = "";
      src = "/images/rollminus.gif";
   }
   for (i=0; i<imgs_elements.length; i++) {
      if (imgs_elements[i].id.match(":arrow") && (imgs_elements[i].id.match("section_"))) {
         div_id = imgs_elements[i].id.substr(8, imgs_elements[i].id.indexOf(":")-8);
         document.getElementById("section_"+div_id+":arrow").src = src;
         document.getElementById("section_"+div_id+"_forecast").style.display = display;
         if (!graph) {
            document.getElementById("section_"+div_id+"_quota").style.display = display;
            document.getElementById("section_"+div_id+"_percent").style.display = display;
            document.getElementById("section_"+div_id+"_invoiced").style.display = display;         
         }
      }
   }
   document.getElementById("toggle_all_1:arrow").src = src;
   document.getElementById("toggle_all_2:arrow").src = src;
   
}

function toggleAll()
{
	exp = 0;

	toggleSection('total');
	if(document.getElementById('ae_'+jArray[0]+'_1').style.display=='none') {
		exp = 1;
	}
	for (i=0;i<jArray.length;i++) {
		//alert("toggling "+jArray[i]);
		if (exp) {
			for(s=1;s<4;s++) {
      			document.getElementById('ae_'+jArray[i]+'_'+s).style.display='';
      		}
       		document.getElementById('ae_view_'+jArray[i]+'_1:arrow').src='/images/rollminus.gif';
       		document.getElementById('ae_view_'+jArray[i]+'_2:arrow').src='/images/rollminus.gif';
		} else {
			for(s=1;s<4;s++) {
            	document.getElementById('ae_'+jArray[i]+'_'+s).style.display='none';
         	}
            document.getElementById('ae_view_'+jArray[i]+'_1:arrow').src='/images/rollplus.gif';
            document.getElementById('ae_view_'+jArray[i]+'_2:arrow').src='/images/rollplus.gif';
		}
	}

	if (exp) {
		document.getElementById('ae_view_all_0:arrow').src='/images/rollminus.gif';
		document.getElementById('ae_view_all_1:arrow').src='/images/rollminus.gif';
		document.getElementById('ae_view_all_2:arrow').src='/images/rollminus.gif';
		document.getElementById('ae_view_all_3:arrow').src='/images/rollminus.gif';
	} else {
		document.getElementById('ae_view_all_0:arrow').src='/images/rollplus.gif';
      	document.getElementById('ae_view_all_1:arrow').src='/images/rollplus.gif';
		document.getElementById('ae_view_all_2:arrow').src='/images/rollplus.gif';
      	document.getElementById('ae_view_all_3:arrow').src='/images/rollplus.gif';
	}

}


function CalculateDistribution(month, section, total) {
   var distributed = 0;
   var left = 0;
   o_td = document.getElementsByTagName("input");
   for(i=0; i<o_td.length; i++) {
      ii = o_td[i].id.split("_");
      if (ii[0]=="input"&&ii[1]==month && ii[2]==section) {
         distributed = distributed + parseFloat(o_td[i].value.replace(",", ""));
      }
   }
   left = total - distributed;
   document.getElementById("distributed_"+section+"_"+parseInt(month)).innerHTML = "$"+distributed.toFixed(2);
   document.getElementById("left_"+section+"_"+parseInt(month)).innerHTML = "$"+left.toFixed(2);
}

function CalculateForecastTotal(month, section) {
   var total = 0;
   o_td = document.getElementsByTagName("input");
   for(i=0; i<o_td.length; i++) {
      ii = o_td[i].id.split("_");
      //if (o_td[i].id.substring(0, 8)=="input_"+month) {
      if (ii[0]=="input" && ii[1]==month && ii[2]==section) {
         total = total + parseFloat(o_td[i].value.replace(",",""));
      }
   }
   document.getElementById("total_"+section+"_"+parseInt(month)).innerHTML = "$"+total.toFixed(2);
}