/**
* ValidateAccountByName()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function ValidateAccountByName(account_name)
{

   var f = document.forms[0];
   var account_type;
   var url;
   
   account_type = GetAccountType();
   
   //url = '?action=validate_account&account_name='+account_name+'&account_type='+account_type;
   url = '/account/validate/name/' + account_name + '/type/' + account_type;
   
   window.open(url,'app_bg');
}

/**
* ResetValidation()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function ResetValidation(obj_name, event_handler)
{
   var o_td = document.getElementById('td_'+obj_name);
   o_td.innerHTML = "<input type='text' name='"+obj_name+"' onchange='"+event_handler+"'; size='30'>";
}

/**
* ValidateAeAmByAccountId()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function ValidateAeAmByAccountId(o) 
{
   var account_id = o.value;
   var account_type = false; 
   var url;
   
   account_type = GetAccountType();
   
   if (account_id != '') {
      url = '?action=validate_aeam&account_id='+account_id+'&account_type='+account_type;
      window.open(url,'app_bg');
   }
   
}

/**
* GetAccountType()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function GetAccountType()
{
   var f = document.forms[0];
   var account_type = false;
   
    //find out which account type is checked
   for (i=0; i < f.account_type.length; i++) {
      if (f.account_type[i].checked) 
         account_type = f.account_type[i].value;
   }  
   
   return account_type;
}

/**
* GetGroupMembers()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function GetGroupMembers(o)
{
   var functional_group_id = o.value;
   
   if (functional_group_id != '') {
      url = "?action=get_group_members&functional_group_id="+functional_group_id;
      window.open(url,'app_bg');
   }
   
}

function FilterCountryByRegionId(o)
{
   var region_id = o.value;
   
   if (region_id != '') {
      url = "?action=get_country_by_region&region_id="+region_id;
      window.open(url,'app_bg');
   }
}

function FilterRegionByCountryCode(o)
{
   var country_code = o.value;
   
   if (country_code != '') {
      url = "?action=get_region_by_country&country_code="+country_code;
      window.open(url,'app_bg');
   }  
   
}


function ToggleAdvanceSearch()
{
   var advanced = 0;
   
   for (i=0;i<4;i++) {
      var a_search = document.getElementById('advance_search_'+i);
      if (a_search.style.display == 'block') {
         a_search.style.display = 'none';
         advanced = 0;
      } else {
         a_search.style.display = 'block';
         advanced = 1;
      }
   }
   
   var o_a = document.getElementById('div_advance_search');
   var o_b = document.getElementById('div_basic_search');
      
   
   if (advanced == 0) {
      o_a.style.display = 'block';
      o_b.style.display = 'none';
   } else {
      o_a.style.display = 'none';
      o_b.style.display = 'block';
   }
   
   var o_advanced_flag = document.getElementById('advanced_flag');
   o_advanced_flag.value = advanced;
   
}

function ValidateAccountName(o)
{
   var account_name = o.value;
   var obj_name     = o.name;
   var account_type = GetAccountType();
   url = '?action=validate_account_search&account_name='+account_name+'&account_type='+account_type+'&obj_name='+obj_name;
   url += '&func_name=ValidateAccountName';
   
   window.open(url,'app_bg');
   
}

function ValidateAccountId(o) 
{
   var account_id   = o.value;
   var obj_name     = o.name;
   var account_type = GetAccountType();
   url = '?action=validate_account_search&account_id='+account_id+'&account_type='+account_type+'&obj_name='+obj_name;
   url += '&func_name=ValidateAccountId';
   window.open(url,'app_bg');
   
}

function GetGroupMembersSearch(o)
{
   var functional_group_id = o.value;
   
   if (functional_group_id != '') {
      url = "?action=get_group_members&functional_group_id="+functional_group_id+'&add_blank_option=1';
      url += '&sel_name=sc_fg_login'
      window.open(url,'app_bg');
   }  
}

function toggleDetail(section_id)
{
   //alert(section_id);
   var o_tr = document.getElementsByTagName('tr');
   var toggle = 0;
   
   for (i=0;i < o_tr.length; i++) {
      if (o_tr[i].id == 'dtl_'+section_id) {
         
         if (o_tr[i].style.display == 'none') {
            o_tr[i].style.display = 'block';
            toggle = 1;
         } else {
            o_tr[i].style.display = 'none';
            toggle = 0;
         }
      }
   }

   var img = document.getElementById('img_'+section_id+':arrow');
   
   if (toggle == 0) {
      img.src='/images/rollplus.gif';
   } else {
      img.src='/images/rollminus.gif';
   }  
}

function toggleMainWindow(div_id)
{
   var o_tr = document.getElementsByTagName('tr');
   var toggle = 1;
   
   var img_name = document.all(div_id+'_1:arrow').src;
   
   if (img_name.indexOf('minus') == -1) {
      toggle = 0;
   }
   
   for (i=0;i < o_tr.length; i++) {
      if (o_tr[i].id != '') {
         var tr_name = o_tr[i].id.substring(0,4);
         var section_id = o_tr[i].id.substring(4);
         if (tr_name == 'dtl_') {
            if (toggle == 0) {
               o_tr[i].style.display = 'block';
            } else {
               o_tr[i].style.display = 'none';
            }
            
            var img = document.getElementById('img_'+section_id+':arrow');
   
            if (toggle == 1) {
               img.src='/images/rollplus.gif';
            } else {
               img.src='/images/rollminus.gif';
            }  
         }
      }
   }

   if (toggle == 0) {
      document.all(div_id+'_1:arrow').src='/images/rollminus.gif';
      document.all(div_id+'_2:arrow').src='/images/rollminus.gif';

   } else {
      document.all(div_id+'_1:arrow').src='/images/rollplus.gif';
      document.all(div_id+'_2:arrow').src='/images/rollplus.gif';
   }
}

function ClearSearch(f)
{
   for (i=0; i < f.elements.length; i++) {
      
      if (f.elements[i].type == 'text') {
         f.elements[i].value = '';
      } else if (f.elements[i].type == 'select-one') {
         f.elements[i].selectedIndex = 0;
      }
   }
   
   url = '?e=l0J/pvq/QVOfDBahnoubRnVu2imFQBIs';
   window.open(url,'_self');
   
}

function FormatCurrency(o)
{
   var number = o.value;
   
   if (o.value == '') return true;
   
   var re_first_alpha = new RegExp("^[^0-9]", "");
   var re_comma_replace = new RegExp(",", "g");
   var re_not_decimal = new RegExp("^[0-9]+\.?[0-9][0-9]$");
   
   number = number.replace(re_first_alpha, "");
   number = number.replace(re_comma_replace, "");
   
   
   if (number.match(re_not_decimal)) {
      o.value = number;
      return true;
   } 
   
   o.style.backgroundColor = 'yellow';
   alert('Invalid Currency Format');
   return false;
}

function DisplayWonRow(o)
{
   var tr_won_date = document.getElementById('tr_won_date')
   var won_date = document.getElementById('won_date');
   var d = document.getElementById('js_today');
   
   if (o.value == 2) {
      tr_won_date.style.display = 'block';
      won_date.value = d.value;
         
   } else {
      tr_won_date.style.display = 'none';
      won_date.value = '';
   }
   
}

function CheckEdit(f)
{
   var status = f.elements.proposal_status_id;
   var project_value = f.elements.project_value;
   
   if (status.value == 2 && project_value.value == '') {
      alert('You must specify a Project Value to Change Status to Won');
      return false;
   }
   
   return true;
   
}