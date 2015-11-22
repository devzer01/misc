/**
* LoadSubTask()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 23:08:04 PST 2006
*/
function TrmAJAX(action, value, target, callback, key, description, element)
{
	url = "?action="+ action +"&value="+ value +"&target="+ target +"&key="+ key +"&description="+ description +"&element="+ element;
	AbortCurrentCallIfInUse(http);
	http.open("GET", url, true);
	http.onreadystatechange = eval(callback);
	http.send("");
}


function loadSubTask(parent)
{
	url = '?action=populate_sub_task&task_id='+ parent;
	//url = '?f=load_sub_task&a=sp&parent_id='+parent;
   window.open(url,'app_bg');
}

function deltask(task_id)
{
	if (confirm('Are You Sure You Want To Delete This Task ?')) {
		window.location.href='?frm_action=del_task&id='+task_id;
	}
}

function addContact(partner_id)
{
      //lert(partner_id);
   window.open('?f=add_contact&a=frm&partner_id='+partner_id,'_top');
   //window.location.href='?x';

}

function validateTrainingRequest()
{
    var rFields = new Array('partner_id','requested_start_date','requested_end_date');
    var rMessages = new Array('Partner ID','Preffered Start Date','Preffered End Date');
    
    for(var i=0;i<rFields.length;i++)
    {
        var obj = document.getElementById(rFields[i]);
        if (obj.value == '') {
            alert("Missing "+rMessages[i]);
            obj.focus();
            return false;
        }

    }
    return true;
}

function validateAddTask()
{
    //var partner_required = document.getElementById('partner_required');
    //var partner_id = document.getElementById('partner_id');

    //this is easier to handle to see if a task is selected because if the partner_required isnt populated the user hasn't selected a task
    
    if(partner_required.value == '') {
        alert('You must select a task to continue');
        return false;
    }

    if (partner_required.value == 1 && partner_id.value == '') {
        alert('This task requires partner information to be filled in');
        partner_id.focus();
        return false;
    }
    return true;
}

function isPartnerRequired(task_id)
{
    url = '?frm_action=isPartnerRequired&task_id='+task_id;
    window.open(url,'app_bg');
}
