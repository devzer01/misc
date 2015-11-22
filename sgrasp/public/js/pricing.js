//////////////////////////////////////////////////////////////////////////////////
function retrive_selected_values(f)
{
   if (f.value == '')
      return 0;

   url = '?action=retrive_selected_values&pricing_item_rule_table_id=' + f.value;
   window.open(url,'app_bg');
}