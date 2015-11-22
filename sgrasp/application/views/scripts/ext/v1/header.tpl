{literal}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>MyGMI</title>
<link rel="stylesheet" href="/css/ext/main.css" type="text/css">

<script language="Javascript" type="text/javascript">
function toggleDisplay(variable, thisImage, displayNoneImage, displayBlockImage)
{
	thisField=document.getElementById(variable);
	thisImage = document.getElementById(thisImage);
	if(thisField.style.display=="block") //If div1 is currently visible, hide it
	{
		thisField.style.display = "none";
		thisImage.src = displayNoneImage; //"/images/ext/plus.gif"
	}
	else //if div1 is currently invisible, display it
	{
		thisField.style.display = "block";
		thisImage.src = displayBlockImage; //"/images/ext/minus.gif"
	}
}
</script>

<script>

function toggleDropdown(variable)
{
	var thisSelectMenu=document.getElementById(variable);
	
	var i=0;
	for (i=0;i<thisSelectMenu.length;i++)
	{
		thisField=thisSelectMenu.options[i].value;
		thisDivTag=document.getElementById(thisField);
		thisDivTag.style.display = "none";
	}
	
	var thisField=thisSelectMenu.options[thisSelectMenu.selectedIndex].value;
	var thisDivTag=document.getElementById(thisField);
	thisDivTag.style.display = "block";

}
</script>

</head>

<body>

<!-- BEGIN MAIN HEADER TABLE -->
<table cellspacing="0" cellpadding="0" class="headerTable">
  <tr>
    <td width="242" rowspan="4" align="left" style="vertical-align:bottom"><a href="/"><img src="/images/ext/my_gmi.jpg" width="242" height="89" border="0"></a></td>
    <td colspan="6">&nbsp;</td>
    <td colspan="8">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
    <td width="86">&nbsp;</td>

    <td colspan="6" class="welcomeCell">Welcome, <span class="username">[insert name here]</span></td>
  </tr>
  <tr>
    <td height="30" colspan="6">&nbsp;</td>
    <td></td>
    <td width="9"><img src="/images/ext/icon-arrow-blue.gif" width="6" height="6"></td>
    <td width="52" class="headerLinks"><a href="/">Support</a></td>

    <td width="9"><img src="/images/ext/icon-arrow-blue.gif" width="6" height="6"></td>
    <td width="96" class="headerLinks"><a href="/">Training Center </a></td>
    <td width="18"><a href="#"><img src="/images/ext/x.gif" width="16" height="15" border="0"></a></td>
    <td width="75" class="headerLinks"><a href="#">Logout</a></td>
  </tr>
  <tr>
    <td width="61" class="nav"><a href="/app/ptm/"><img src="/images/ext/home.gif" width="61" height="21" border="0" onMouseOver="this.src='/images/ext/home2.gif';" onMouseOut="this.src='/images/ext/home.gif'"></a></td>
    <td width="79" class="nav"><a href="/app/pgen/"><img src="/images/ext/proposal.gif" width="79" height="21" border="0" onMouseOver="this.src='/images/ext/proposal2.gif';" onMouseOut="this.src='/images/ext/proposal.gif'"></a></td>

    <td width="74" class="nav"><a href="/app/pjm/"><img src="/images/ext/project.gif" width="74" height="21" border="0" onMouseOver="this.src='/images/ext/project2.gif';" onMouseOut="this.src='/images/ext/project.gif'"></a></td>
    <td width="66" class="nav"><a href="/app/stm/"><img src="/images/ext/study.gif" width="66" height="21" border="0" onMouseOver="this.src='/images/ext/study2.gif';" onMouseOut="this.src='/images/ext/study.gif'"></a></td>
    <td width="74" class="nav"><a href="/app/atm/armc/"><img src="/images/ext/billing.gif" width="74" height="21" border="0" onMouseOver="this.src='/images/ext/billing2.gif';" onMouseOut="this.src='/images/ext/billing.gif'"></a></td>
    <td width="80" class="nav"><a href="/app/acm/"><img src="/images/ext/account.gif" width="80" height="21" border="0" onMouseOver="this.src='/images/ext/account2.gif';" onMouseOut="this.src='/images/ext/account.gif'"></a></td>
    <td colspan="8" class="nav" background="/images/ext/right_nav.gif" style="background-image:url(/images/ext/right_nav.gif);background-position:bottom;background-repeat:repeat-x">&nbsp;</td>
  </tr>
</table>
<!-- END MAIN HEADER TABLE -->

<table width="1025"  border="0" cellspacing="0" cellpadding="0">
  <tr>

    <td class="sidebar"><!-- BEGIN GMI ACCOUNT STAFF BOX--><table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="sidebarTopHeader"><div>GMI ACCOUNT STAFF </div></td>
  </tr></table><table width="92%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td background="/images/ext/left_top_slice.gif"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="quickContacts">
          <tr>
            <td><img src="/images/ext/left_top.gif" width="242" height="12"></td>

          </tr>
          <tr>
            <td class="quickContactsCell"><img src="/images/ext/arrow.gif" width="9" height="9" name="pic67" id="pic67" onClick="toggleDisplay('div67','pic67','/images/ext/arrow.gif','/images/ext/arrow2.gif');"> <a onClick="toggleDisplay('div67','pic67','/images/ext/arrow.gif','/images/ext/arrow2.gif');">Yuko Samizo</a><br> 
<div id="div67" style="display:none"><table align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="197">Primary Account Manager<br>
                     Net-MR<br>

            <a href="mailto:tkita@asia-info.com">ysamizo@asia-info.com</a> </td>
                  </tr>
                </table><br></div>
                <img src="/images/ext/arrow.gif" width="9" height="9" name="pic66" id="pic66" onClick="toggleDisplay('div66','pic66','/images/ext/arrow.gif','/images/ext/arrow2.gif');"> <a onClick="toggleDisplay('div66','pic66','/images/ext/arrow.gif','/images/ext/arrow2.gif');">Tomoe Kita</a>
                <div id="div66" style="display:none"><table align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="197">Primary Account Executive<br>

                     Net-MR<br>
            <a href="mailto:tkita@asia-info.com">tkita@asia-info.com</a> </td>
                  </tr>
                </table></div>
                </td>
          </tr>
          <tr>

            <td><img src="/images/ext/left_top_bottom.gif" width="242" height="12"></td>
          </tr>
        </table></td>
      </tr>
    </table>
	<!-- END GMI ACCOUNT STAFF BOX-->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="sidebarOtherHeader"><div>QUICK CONTACTS</div></td>

  </tr>
  <tr>
    <td height="19"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="quickContacts">
      <tr>
        <td><img src="/images/ext/left_top.gif" width="242" height="12"></td>
      </tr>
      <tr>
        <td class="quickContactsCell"><img src="/images/ext/arrow.gif" width="9" height="9" name="pic69" id="pic69" onClick="toggleDisplay('div69','pic69','/images/ext/arrow.gif','/images/ext/arrow2.gif');"> <a onClick="toggleDisplay('div69','pic69','/images/ext/arrow.gif','/images/ext/arrow2.gif');">Chidori Koba</a> 

<div id="div69" style="display:none"><table align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="197">Tokyo, Japan<br>
<a href="chidori@asia-info.com">chidori@asia-info.com</a></td>
              </tr>
            </table></div><br>
            <img src="/images/ext/arrow.gif" width="9" height="9" name="pic68" id="pic68" onClick="toggleDisplay('div68','pic68','/images/ext/arrow.gif','/images/ext/arrow2.gif');"> <a onClick="toggleDisplay('div68','pic68','/images/ext/arrow.gif','/images/ext/arrow2.gif');">Akane Ekino</a>
            <div id="div68" style="display:none"><table align="center" cellpadding="0" cellspacing="0">

              <tr>
                <td width="197">4F Hakuwa bldg<br>
                  3-2 Kojimachi<br>
                  Tokyo, Japan 102-0083<br>
<a href="mailto:ekino@asia-info.com">ekino@asia-info.com</a>
<br>Fax: 81-3-5212-5261</td>
              </tr>

            </table></div>
            <br><img src="/images/ext/arrow.gif" width="9" height="9" name="pic70" id="pic70" onClick="toggleDisplay('div70','pic70','/images/ext/arrow.gif','/images/ext/arrow2.gif');"> <a onClick="toggleDisplay('div70','pic70','/images/ext/arrow.gif','/images/ext/arrow2.gif');">Kumi Kaneda</a> 
<div id="div70" style="display:none"><table align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="197">
                  Tokyo, Japan <br>
<a href="mailto:kkaneda@asia-info.com">kkaneda@asia-info.com</a>
</td>

              </tr>
            </table></div><br>
              <img src="/images/ext/arrow.gif" width="9" height="9" name="pic71" id="pic71" onClick="toggleDisplay('div71','pic71','/images/ext/arrow.gif','/images/ext/arrow2.gif');"> <a onClick="toggleDisplay('div71','pic71','/images/ext/arrow.gif','/images/ext/arrow2.gif');">Maggie Sung</a> 
<div id="div71" style="display:none"><table align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="197">4F Hakuwa bldg<br>
                  3-2 Kojimachi<br>

                  Tokyo, Japan 102-0083<br>
<a href="mailto:sung@asia-info.com">sung@asia-info.com</a>
</td>
              </tr>
            </table></div><br></td>
      </tr>
      <tr>
        <td><img src="/images/ext/left_top_bottom.gif" width="242" height="12"></td>
      </tr>

    </table></td>
    </tr>
  <tr>
    <td class="sidebarOtherHeader"><div>MY GMI LOCATION</div></td>
    </tr>
  <tr>
    <td height="19"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>

        <td><img src="/images/ext/left_top.gif" width="242" height="12"></td>
      </tr>
      <tr>
        <td background="/images/ext/left_top_slice.gif" class="bluetext" align="left"><img src="/images/ext/world.gif" width="28" height="16">&nbsp;<strong>GMI Seattle (HQ) </strong>          
<p>2835 82nd Ave. SE
<br>Suite S110
<br>Mercer Island, WA 98040 
<br>USA
<br><strong>Tel:</strong> +1 206-315-9300
<br><strong>Fax:</strong> +1 206-315-9301
	  
              <form name="nav" width="205">

              <SELECT NAME="SelectURL" onChange="document.location.href=document.nav.SelectURL.options[document.nav.SelectURL.selectedIndex].value" style="width:205px">
              <option value="" selected>Other GMI Office Locations</option>
		      <option value="#">Austin, TX</option>
              <option value="#">Budapest, Hungary</option>
              <option value="#">Chicago, IL</option>
		      <option value="#">Cincinatti, OH</option>

		      <option value="#">Drobak, Norway</option>
		       <option value="#">India</option>
               <option value="#">Los Angeles, CA</option>
               <option value="#">Minneapolis, MN</option>
               <option value="#">Munich, Germany</option>
               <option value="#">New York, NY</option>

               <option value="#">Paris, France</option>
		       <option value="#">Sofia, Bulgaria</option>
               <option value="#">Shanghai, China</option>
               <option value="#">Singapore</option>
		       <option value="#">South Africa</option>
		       <option value="#">Tokyo, Japan</option>

               <option value="#">Valencia, Spain</option>
            </select>
		</form>             
</td>
      </tr>
      <tr>
        <td><img src="/images/ext/left_top_bottom.gif" width="242" height="12"></td>
      </tr>
    </table></td>

    </tr>
  <tr>
    <td valign="top" style="padding-top:6px;text-align:center">
      <a href="#"><img src="/images/ext/ads.gif" width="206" height="179" border="0"></a></p>
      </td>
    </tr>
</table>

</td>
	<!-- END OF MAIN CELL 1-->
    <td align="left" valign="top" background="/images/ext/center_strip.gif" style="padding-left:0px">	
{/literal}