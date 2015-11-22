/* Generic Validation Script Used For User Input Validations */

//////////////////////////////////////////////////////////////
function lookup(query,src,target) {
//////////////////////////////////////////////////////////////
	window.open('/lib/valid.php?query='+query+'&src='+src.value+'&target='+target,'fr2');
}

function lookup_v2(query,src,target) {
	document.all('validation').src = '/lib/valid.php?query='+query+'&src='+src.value+'&target='+target+'&v=2';
	document.all('validation').style.left = '300px';
	document.all('validation').style.top = '300px';
	document.all('validation').style.width = '500px';
	document.all('validation').style.display = 'block';
}

function lookup_v3(query,src,target) {
	var ex_string = ''; //to keep track of all the extra arguments we pass into this function
	if (arguments.length > 3) {
		for(i=3;i<arguments.length;i++) {
			ex_string += '&w_arg'+i+'='+arguments[i];
		}
	}
	//window.open('/lib/valid.php?query='+query+'&src='+src.value+'&target='+target+'&v=3'+ex_string);
	document.all('validation').src = '/lib/valid.php?query='+query+'&src='+src.value+'&target='+target+'&v=3'+ex_string;
	//window.open('http://cs.gmi-mr.com/lib/valid.php?query='+query+'&src='+src.value+'&target='+target+'&v=3');
}

function add_client() {
	document.forms[0].partner_id.value = 100;
	document.all.trclient.style.visibility = 'visible';
	document.all.trclient.style.display = 'block';
}

function showprid(skey) {
	if (skey != 'noshow') {
		document.all.trprid.style.visibility = 'visible';
		document.all.trprid.style.display = 'block';
	}
	document.all.score.value = 0;
}

function getprdtl(segment,prid) {
	var key1 = '20'+(prid.substr(0,2)); //year
	var key2 = prid.substr(2,2); //month
	//parse out the prefixing 0s or else we get weird number from parseint
	var rg = new RegExp('([^0][0-9]+)$','g');
	var key3 = rg.exec(prid.substr(4,4));
	//var key3 = parseInt(prid.substr(4,4));
	//var key3 = parseInt(prid.substr(6,4)); //proposal_id
	//alert(key3[1]);
	var url = "/lib/autofill.php?query="+segment+'&key1='+key1+'&key2='+key2+'&key3='+key3[1];
	wdpop = window.open(url,'fr2');
}

