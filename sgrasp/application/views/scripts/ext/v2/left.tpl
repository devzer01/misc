{literal}
<script language="javascript">
	function ShowOffice(id)
	{
		var last_id = document.getElementById('last_location');
		document.getElementById('loc_' + id).style.display = 'block';
		document.getElementById('loc_' + last_id.value).style.display = 'none';
		last_id.value = id;
	}
</script>
{/literal}

<table border="0" cellspacing="0" cellpadding="0" style="width:100%">
  <tr>
    <td class="sidebar" width="242"><!-- BEGIN GMI ACCOUNT STAFF BOX--><table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="sidebarTopHeader"><div>GMI ACCOUNT STAFF </div></td>

  </tr></table><table width="92%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td background="/images/ext/left_top_slice.gif"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="quickContacts">
          <tr>
            <td><img src="/images/ext/left_top.gif" width="242" height="12"></td>
          </tr>
          <tr>
            <td class="quickContactsCell">
            {section name=int loop=$ac.user}
            <img src="/images/ext/arrow.gif" width="9" height="9" 
                 name="pic{$ac.user[int].account_user_id}" id="pic{$ac.user[int].account_user_id}" onClick="toggleDisplay('div{$ac.user[int].account_user_id}','pic{$ac.user[int].account_user_id}','/images/ext/arrow.gif','/images/ext/arrow2.gif');"> <a onClick="toggleDisplay('div{$ac.user[int].account_user_id}','pic{$ac.user[int].account_user_id}','/images/ext/arrow.gif','/images/ext/arrow2.gif');">{$ac.user[int].first_name} {$ac.user[int].last_name}</a>
            <br>
            <div id="div{$ac.user[int].account_user_id}" style="display:none">
               <table align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="197">{$ac.user[int].role_description}<br>
                     {$ac.user[int].product_description}<br>
                     <a href="mailto:{$ac.user[int].email_address}">{$ac.user[int].email_address}</a>
                    </td>
                  </tr>
                </table>
                <br>
            </div>
            {/section}
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
        <td class="quickContactsCell">
        {section name=cnt loop=$ac.contacts}
        
        <img src="/images/ext/arrow.gif" width="9" height="9" name="pic{$ac.contacts[cnt].account_contact_id}" id="pic{$ac.contacts[cnt].account_contact_id}" onClick="toggleDisplay('div{$ac.contacts[cnt].account_contact_id}','pic{$ac.contacts[cnt].account_contact_id}','/images/ext/arrow.gif','/images/ext/arrow2.gif');"> 
        <a onClick="toggleDisplay('div{$ac.contacts[cnt].account_contact_id}','pic{$ac.contacts[cnt].account_contact_id}','/images/ext/arrow.gif','/images/ext/arrow2.gif');">{$ac.contacts[cnt].contact_first_name} {$ac.contacts[cnt].contact_last_name}</a>
<div id="div{$ac.contacts[cnt].account_contact_id}" style="display:none"><table align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="197">
                {$ac.contacts[cnt].address_street_1}<br>
                {$ac.contacts[cnt].address_city} {$ac.contacts[cnt].country_description}<br>
                <a href="mailto:{$ac.contacts[cnt].contact_email}">{$ac.contacts[cnt].contact_email}</a></td>
              </tr>
            </table></div><br>
         {/section}
        </td>
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
        <td background="/images/ext/left_top_slice.gif" class="bluetext" align="left">
        	<!--<img src="/images/ext/world.gif" width="28" height="16">-->
        		{foreach from=$rss_office->office item=location name=fe_loc}
        			{if $smarty.foreach.fe_loc.first eq "true"}
        				<div id='loc_{$smarty.foreach.fe_loc.iteration}' style="display:">
        			{else}
						<div id='loc_{$smarty.foreach.fe_loc.iteration}' style="display: none;">
					{/if}
					
						<strong>{$location->name}</strong>
						<p>{$location->address1}
						<br>{$location->address2}
						<br>{$location->city}, {$location->state} {$location->zip}
						<br>{$location->country}
						<br><strong>Tel:</strong> {$location->tel}
						<br><strong>Fax:</strong> {$location->fax}
					</div>
				{/foreach}
              <form name="nav" width="205">
              		<input type="hidden" id='last_location' name="last_location" value="1">
              		<SELECT NAME="SelectURL" onChange="ShowOffice(this.value)" style="width:205px">
              		{foreach from=$rss_office->office item=location name=fe_loc}
              			<option value="{$smarty.foreach.fe_loc.iteration}">{$location->name}</option>
              		{/foreach}
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
      {foreach from=$rss_feed_1 item=item}
          <a href="{$item.link}" target="_blank"><img src="{$item.description}" width="227" height="179" border="0" alt="{$item.title}"></a><br>
       {/foreach}
    </td>
    </tr>
</table>
</td>
	<!-- END OF MAIN CELL 1-->
    
