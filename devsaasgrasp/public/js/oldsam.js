function toggleSection(div_id) {
    if(document.getElementById('ae_'+div_id+'_1').style.display=='none'){
		for(i=1;i<4;i++) {
      		document.getElementById('ae_'+div_id+'_'+i).style.display='';
		}
      	document.getElementById('ae_view_'+div_id+'_1:arrow').src='/images/rollminus.gif';
      	document.getElementById('ae_view_'+div_id+'_2:arrow').src='/images/rollminus.gif';
    }
    else{
		for(i=1;i<4;i++) {
     	 	document.getElementById('ae_'+div_id+'_'+i).style.display='none';
		}
	   	document.getElementById('ae_view_'+div_id+'_1:arrow').src='/images/rollplus.gif';
	   	document.getElementById('ae_view_'+div_id+'_2:arrow').src='/images/rollplus.gif';
    }
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
