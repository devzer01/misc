<?php

$lbl['Login'] = "Login";
$lbl['FirstName'] = "First Name";
$lbl['LastName'] = "Last Name";
$lbl['LastLogin'] = "Last Login";
$lbl['Department'] = "Department";
$lbl['Group'] = "Group";
$lbl['Email'] = "Email Address";
$lbl['SMS'] = "SMS Address";
$lbl['ReportsTo'] = "Reports To";
$lbl['Reportees'] = "Reportees";


/**
 * Administrative labels
 *
 * Values preceeded with NAME_ are module names that show up in the title bar
 */

//Name of the table administration tool
$admin_lbl['NAME_TABLE_ADMIN'] = "Other Table Administration";
//Name of the functional groups administration tool
$admin_lbl['NAME_FGROUPS'] = "Functional Groups Administration";
$admin_lbl['NAME_SGROUPS'] = "Security Groups Administration";
$admin_lbl['NAME_APPLICATION_SECURITY'] = "Application Security";
$admin_lbl['NAME_ROLES'] = "Roles Administration";
$admin_lbl['NAME_STM'] = "Study Manager Task Administration";
//What database table to select
$admin_lbl['TABLE'] = "Table:";
//Results to show on each page
$admin_lbl['RESULTSPP'] = "Results per page:";
//Show the non-active users (those with status != 'A')
$admin_lbl['SHOWNAR'] = "Show non-active rows:";
//Guess the databse's foriegn key associations
$admin_lbl['GUESSFK'] = "Guess foriegn keys:";
//show the attributes for each row (from the ..._attr tables)
$admin_lbl['SHOWATTR'] = "Show attributes:";
//Stats on how many results a query generates
//%1 - first result on the page
//%2 - last result on the page
//%3 - total number of results
$admin_lbl['RESULT_FORMAT'] = "Results %1 - %2 of %3";
//Add a row to the database
$admin_lbl['ADD_ROW'] = "Add Row";
//edit a row from the database
$admin_lbl['EDIT_ROW'] = "Edit Row";
$admin_lbl['ATTR_DEF_ADDED'] = "Attribute definition successfully added";
//an update has occured, but all values are unchanged
$admin_lbl['NOTHINGTOUPDATE'] = "Nothing to update";
$admin_lbl['ATTR_DEF_DELETED'] = "Attribute definition successfully deleted";
$admin_lbl['ATTR_UPDATED'] = "Attribute updated successfully";
$admin_lbl['ATTR_ADDED'] = "Attribute successfully added";
$admin_lbl['ROW_DELETED'] = "Row successfully deleted";
$admin_lbl['ROW_ADDED'] = "Row successfully added";
$admin_lbl['ROW_UPDATED'] = "Row updated successfully";
$admin_lbl['MANAGE_ATTRS'] = "Manage Attributes:";
$admin_lbl['EDIT_ATTR_DEFS'] = "Edit attribue Definitions";
$admin_lbl['ADD_ATTR_DEF'] = "Add an attribute definition";
$admin_lbl['EDIT_ATTR'] = "Edit Attribute";
$admin_lbl['ADD_ATTR'] = "Add Attribute";
//Used when a user is added to a group (functional or security)
//%1 - the login of the user added
$admin_lbl['USER_ADDED'] = "User %1 added successfully";
//Used when a user is removed from a group (functional or security)=
//%1 - the login of the user removed
$admin_lbl['USER_REMOVED'] = "User %1 has been removed from the group";
$admin_lbl['FGROUPS'] = "Functional Groups";
//applicable to functional groups
$admin_lbl['ABBREV'] = "Abbreviation:";
$admin_lbl['DESCRIPTION'] = "Description:";
//The number of users associated with a certain group (row)
$admin_lbl['USER_COUNT'] = "User Count:";
//applicable to functional groups
$admin_lbl['GROUP_OWNER'] = "Group Owner:";
//applicable to functional groups
$admin_lbl['NEW_OWNER'] = "New Owner:";
$admin_lbl['LIST_USERS'] = "List users in this group";
//The users associated with a group
//%1 - the name of the group
$admin_lbl['USERSINGROUP_FORMAT'] = "Users in %1";
//Adds a user to the group/list
$admin_lbl['ADD_USER'] = "Add User";
$admin_lbl['DESCRIPTION_UPDATED'] = "Description successfully updated";
$admin_lbl['EDIT_SEC_DESCRIPTION'] = "Edit Security Description";
$admin_lbl['PERMISSION'] = "Permission:";
$admin_lbl['SGROUPS'] = "Security Groups";
$admin_lbl['GROUP_NAME'] = "Group Name:";
$admin_lbl['GROUP_DESCRIPTION'] = "Group Description:";
$admin_lbl['GROUP_RIGHTS'] = "Group Rights:";
$admin_lbl['ROLE_ADDED'] = "Role successfully added";
$admin_lbl['ROLE_REMOVED'] = "Role removed successfully";
$admin_lbl['USER_ROLE_ADDED'] = "User role successfully added";
$admin_lbl['USER_ROLE_REMOVED'] = "User role removed successfully";
$admin_lbl['MODULE_ROLES_UPDATED'] = "Module roles successfully updated<br>";
$admin_lbl['MODULE_ROLES_UPDATED_HINT'] = "(you need to actually press the 'add role' button to add a new role)";
$admin_lbl['ROLE'] = "Role";
$admin_lbl['REMOVE_ROLE'] = "Remove Role";
$admin_lbl['ADD_ROLE'] = "Add role";
$admin_lbl['SELECT_STUDY_TYPE'] = "Select Study Type:";
$admin_lbl['AVAILABLE_STUDY_TASKS'] = "<b>Available Study Tasks</b> (not necessarily in any order)";
$admin_lbl['ADD_NEW_STUDY_TASK'] = "Add New Study Task";
$admin_lbl['TASK_NAME'] = "Task Name:";
$admin_lbl['PRIMARY'] = "Primary:";
$admin_lbl['REQ_MEMO'] = "Requires memo to complete:";
$admin_lbl['MOVE'] = "Move";
$admin_lbl['MOVE_UP'] = "Up";
$admin_lbl['MOVE_DOWN'] = "Down";
$admin_lbl['TASK_DURATION'] = "Task Duration:";
$admin_lbl['ADD_EXISTING_TASK'] = "Add existing task to template";
$admin_lbl['SU_LOGGED_IN'] = "You are now logged in as %1.";
$admin_lbl['SU_LOGIN'] = "Your login:";
$admin_lbl['SU_PASSWD'] = "Your password:";
$admin_lbl['SU_NEW_LOGIN'] = "New login:";

$admin_err['INVALIDTABLE'] = "Invalid table name";
//called from other/functions.inc
$admin_err['TOOMANYATTRS'] = "There are more than 100 attrs for %1_id = %2. <br>That's a lot. Too many, in fact. See if you can do something about that.";
//called from other/functions.inc
$admin_err['NODESCRIPTOR'] = "You need to set a descriptor for table %1 in constant.inc".
//called from other/functions.inc
$admin_err['BADFKCACHE'] = "Chutzpah! This is not supposed to happen. Contact HB@ and tell them the FKCache is broken. <br/>".
			"A likely cause of this error is a foreign key set to a value that is not defined in the foreign key's table.<br/>".
			"table: %1<br/>".
			"desc: %2<br/>".
			"fk: %3<br/>".
			"fkid: %4<br/>";
//called from other/functions.inc
$admin_err['BADATTRDEF'] = "Bad PK value in vieweditattr";
$admin_err['UNAUTH'] = "You are not authorized to do that";
$admin_err['INVALIDPARAMS'] = "Invalid parameters";
$admin_err['SU_HBDEVEL'] = "You need to be a HB developer to do this!";
$admin_err['SU_NAN'] = "The login you entered is not a number";
$admin_err['SU_OWN_LOGIN'] = "You must enter your own login";
$admin_err['SU_BAD_PASSWD'] = "Invalid password";
$admin_err['SU_VALID_LOGIN'] = "You must enter a valid login for the new user";
$admin_err['SU_USER_EXIST'] = "That user login doesn't exist";

//brings up a window to manage the associated ..._attr_def table
$buttons['MANAGEATTRS'] = "Manage Attributes";
//synonymous with change
$buttons['EDIT'] = "Edit";
//synonymous with remove
$buttons['DELETE'] = "Delete";
$buttons['SAVE_CHANGES'] = "Save Changes";
//save changes
$buttons['SAVE'] = "Save";
$buttons['ADD_GROUP'] = "Add Group";
//synonymous with edit
$buttons['CHANGE'] = "Change";
//synonymous with remove
$buttons['REMOVE'] = "Remove";
$buttons['ADD'] = "Add";

//value in the select box when a quick user search is done and no users match the query
$lbl['NO_MATCHING_USERS'] = "No matching users";
//A user needs to choose one from a list of options
$lbl['SELECT_ONE'] = "Select One";
//whatever you would call entries in the ..._attr tables
$lbl['ATTRIBUTES'] = "Attributes";
//Identification number
$lbl['ID'] = "ID";
$lbl['DESCRIPTION'] = "Description";
$lbl['NAME'] = "Name";
$lbl['VALUE'] = "Value";
$lbl['FIELD'] = "Field";


/**
 * Help Module
 */
//name of the help manager
$hem_lbl['NAME'] = "Help Manager";
$hem_lbl['GROUP_ADDED'] = "Group successfully addded.";
$hem_lbl['GROUP_UPDATED'] = "Group successfully updated.";
$hem_lbl['QUESTION_ADDED'] = "Question added successfully";
$hem_lbl['QUESTION_UPDATED'] = "Question updated successfully";
//The 'from' header of emails sent out from the bug/feature tracker
$hem_lbl['EMAIL_TRACKER_FROM'] = "HB Bug/Feature Tracker <bugconfirm@%1>";
//Where tracker emails should go
$hem_lbl['EMAIL_TRACKER_TO_ADR'] = "hb@gmi-mr.com";
//a name for above
$hem_lbl['EMAIL_TRACKER_TO_NAME'] = "HB Team";
//%1 - The bug/feature type (bug, feature, question, task, ...)
$hem_lbl['EMAIL_TRACKER_SUBJECT'] = "Pending Review: %1 in Bug and Feature Tracker";
$hem_lbl['EMAIL_TRACKER_LINK'] = "Click here to review the bug/feature";
//the subject of the request
$hem_lbl['SUBJECT'] = "Subject:";
//who reported the request
$hem_lbl['REPORTEDBY'] = "Reported By:";
$hem_lbl['REQUEST_SUBMITTED'] = "Your request has been submitted successfully.";
$hem_lbl['UPLOAD_COMPLETE'] = "Upload completed successfully";
//the 'from' field of emails sent out from a bug/feature update (after review)
$hem_lbl['EMAIL_UPDATE_FROM'] = "HB Bug/Feature Tracker <bugconfirm@%1>";
$hem_lbl['EMAIL_UPDATE_SUBJECT'] = "Update in bug and feature tracker";
$hem_lbl['EMAIL_UPDATE_DESCR'] = "Bug/feature status update:";
$hem_lbl['BUG_NUMBER'] = "Bug Number:";
$hem_lbl['REPORTED_DATE'] = "Date Reported:";
$hem_lbl['TYPE'] = "Type:";
$hem_lbl['STATUS'] = "Status:";
$hem_lbl['MODULE'] = "Module:";
$hem_lbl['COMPONENT'] = "Component:";
$hem_lbl['COMMENTS'] = "Comments:";
$hem_lbl['BUGFEATURE_UPDATED'] = "Bug/Feature updated successfully";
$hem_lbl['RELEASENOTE_DELETED'] = "release note deleted";
$hem_lbl['RELEASENOTES_DELETED'] = "release notes deleted";
$hem_lbl['NODELETE'] = "Nothing to be deleted.";
$hem_lbl['FAQ_DELETED'] = "frequently asked question deleted";
$hem_lbl['FAQS_DELETED'] = "frequently asked questions deleted";
$hem_lbl['GROUP_DELETED'] = "group deleted";
$hem_lbl['GROUPS_DELETED'] = "groups deleted";
$hem_lbl['BUGFEATURE_SEARCH'] = "Bug/Feature Search";
$hem_lbl['BUGFEATURE_STATUS'] = "Bug/Feature Status";
$hem_lbl['NUMBER'] = "Number:";
$hem_lbl['ADD_GROUP'] = "Add Group";
$hem_lbl['EDIT_GROUP'] = "Edit Group";
$hem_lbl['GROUP'] = "Group:";
$hem_lbl['ADD_QUESTION'] = "Add Question";
$hem_lbl['EDIT_QUESTION'] = "Edit Question";
$hem_lbl['MODULE_ASSOC'] = "Module Association:";
$hem_lbl['OPTIONAL'] = "Optional";
$hem_lbl['QUESTION'] = "Question:";
$hem_lbl['ANSWER'] = "Answer:";
//text that tells you how many characters you've used when entering a comment or FAQ in a textbox
//<--
$hem_lbl['CHARS_DELIM'] = "of";
$hem_lbl['CHARS_USED'] = "characters used";
//-->
$hem_lbl['ORDER_GROUPS'] = "Order Groups";
$hem_lbl['ORDER_QUESTIONS'] = "Order Questions";
//the following are from the bug/feature detail page
//<---
$hem_lbl['BUGFEATURE_DETAIL'] = "Bug/Feature Detail";
$hem_lbl['VERSION'] = "Version:";
$hem_lbl['EMAIL'] = "Email:";
$hem_lbl['BROWSER'] = "Browser:";
$hem_lbl['ASSIGNEDTO'] = "Assigned To:";
$hem_lbl['QACONTACT'] = "QA Contact";
$hem_lbl['FEATURENUMBER'] = "Feature Number:";
$hem_lbl['OTHERCONTACTS'] = "Other Contacts:";
$hem_lbl['PRIORITY'] = "Priority:";
$hem_lbl['ATTACHMENTS'] = "Attachments:";
$hem_lbl['DESCRIPTION'] = "Description:";
$hem_lbl['COMMENT'] = "Comment:";
$hem_lbl['OPT1'] = "Create New Bug and Send Response to Reporter";
$hem_lbl['OPT2'] = "Do NOT Create a Bug. Only Send Response to Reporter";
//-->
$hem_lbl['RELEASE_NOTES_ADMIN'] = "Release Notes Administration";
$hem_lbl['RELEASE_NOTES_UPLOAD'] = "Upload New Release Note";
$hem_lbl['RELEASE_NOTES_TITLE'] = "Release Note Title:";
$hem_lbl['RELEASE_NOTES_VERSION'] = "Release Note Version:";
$hem_lbl['RELEASE_NOTES_DATE'] = "Release Note Date (YYYYMMDD):";
$hem_lbl['RELEASE_NOTES_FILE'] = "<b>File to Upload:</b><br />".
								 "<small>Upload a single htm, html, txt, doc, or pdf document, or upload a zip file containing a group of html documents.</small>";
$hem_lbl['DELETE_RN'] = "Delete Release Notes";
$hem_lbl['ORDER_RN'] = "Order Release Notes";
$hem_lbl['CONFIRM_DELETE_RN'] = "Delete Document?";
$hem_lbl['FAQS_ADMIN'] = "FAQs Administration";
$hem_lbl['ORDER_QUESTIONS'] = "Order Questions";
$hem_lbl['CONFIRM_DELETE_FAQ'] = "Delete FAQs?";
$hem_lbl['REVIEW_LIST'] = "Review List";
$hem_lbl['HELP_FORM_LOG'] = "Help Form Log";
$hem_lbl['RELEASE_NOTES_DOC'] = "Release Notes and Documentation";
$hem_lbl['FAQS'] = "FAQs";
$hem_lbl['HELP_FORM'] = "Help Form";
$hem_lbl['HELP_FORM_MSG'] = "Dear Hummingbird Users,<br>".
							"<br>".
							"Please fill out this form to submit bugs you find in Hummingbird, feature requests, tasks, and questions about the application. You must complete all fields on this form in order to submit it. <br>".
							"<br>".
							"Please write a detailed description of the issue or question including the names of relevant views and fields in Hummingbird and the actions you took step-by-step. You may also attach a screen shot to this form. The more detail you include, the more likely we are to be able to help you with your problem. Thank you!<br>".
							"<br>".
							"Regards,<br>".
							"<br>".
							"<i>The Hummingbird Team</i>".
							"<br><br>";
$hem_lbl['FULL_NAME'] = "Full Name:";
$hem_lbl['FILE'] = "File:";
$hem_lbl['CHECK_STATUS'] = "Check Bug/Feature Status";
$hem_lbl['BUGFEATURE_NUMBER'] = "Bug/Feature Number:";
$hem_lbl['BUGFEATURE'] = "Bug/Feature";


$hem_err['NOADMIN'] = "You need to have administrative privileges to do that.<br>";
$hem_err['INVALIDBUGID'] = "Invalid bug id passed to tracker";
$hem_err['FILETOOBIG'] = "That file is to big. The current max file size is %1";
//descriptions of the various $_FILES[]['error'] codes
//<--
$hem_err['FERROR0'] = "There was an error with your upload.";
$hem_err['FERROR1'] = "PHP says the file you are trying to upload is too large.";
$hem_err['FERROR2'] = "PHP says the file you are trying to upload is too large.";
$hem_err['FERROR3'] = "The file transfer was interrupted..";
$hem_err['FERROR4'] = "You must specify a file to upload.";
$hem_err['FERROROTHER'] = "There was an unidentified PHP error with your file upload.";
//-->
$hem_err['DATEERR'] = "Please enter a valid date in the format YYYYMMDD.";
$hem_err['REQ_SUBJECT'] = "Subject is required";
$hem_err['REQ_MODULE'] = "Module is required";
$hem_err['REQ_TYPE'] = "Type is required";
$hem_err['REQ_STATUS'] = "Status is required";
$hem_err['REQ_COMMENT'] = "A comment is required";
$hem_err['REQ_DESCRIPTION'] = "A description is required";
$hem_err['TYPE_FULL'] = "Type of Inquiry:";


$lbl['REPORTEDBY'] = "Reported By";
$lbl['SUBJECT'] = "Subject";
$lbl['NONE_SELECTED'] = "None Selected";
$lbl['TITLE'] = "Title";
$lbl['VERSION'] = "Version";


$buttons['CLOSE'] = "Close";
$buttons['BACK'] = "Back";
$buttons['REMOVESELECTED'] = "Remove Selected";
$buttons['ADDCONTACT'] = "Add Contact";
$buttons['CANCEL'] = "Cancel";
$buttons['SUBMIT'] = "Submit";
$buttons['NEXT'] = "Next";
$buttons['PREVIOUS'] = "Previous";
$buttons['BACKTOHFL'] = "Return to Log";

/**
 * Employee directory
 */

//the alphabet... used on the left side of the index to filter results
$dir_lbl['ALPHABET'] = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
//Show results from all letters in the alphabet
$dir_lbl['ALPHABET_ALL'] = "All";
//title of the page
$dir_lbl['GMITITLE'] = "GMI Company Directory";
$dir_lbl['FILTER'] = "Filter:";
$dir_lbl['FILTER_IS_EQUAL_TO'] = "is equal to";
$dir_lbl['FILTER_AND'] = "AND";
$dir_lbl['FILTER_NONE'] = "No Filtering";
$dir_lbl['FILTER_CITY'] = "City";
$dir_lbl['FILTER_TIMEZONE'] = "Time Zone";
$dir_lbl['FILTER_DEPARTMENT'] = "Department";
$dir_lbl['NONE_SELECTED'] = "None Selected";
$dir_lbl['EMAIL'] = "Email:";
$dir_lbl['SMS'] = "SMS:";
$dir_lbl['TIPIC'] = "TIPIC:";
$dir_lbl['WEB'] = "Web:";
$dir_lbl['SEARCH'] = "Search:";
$dir_lbl['SEARCH_DESC'] = "Enter at least two characters from an employee name or ID number to search.";
$dir_lbl['INDEX'] = "Index:";
$dir_lbl['SORT_BY'] = "Sort By:";

$lbl['NO_USERS_FOUND'] = "No Users Found";;
$lbl['NICKNAME'] = "Nickname";
$lbl['NEXT'] = "Next";
$lbl['PREVIOUS'] = "Previous";



//study manager
$stm_lbl['PROGRAM']       = "GMI Provides Programming";
$stm_lbl['TRANSLATE']     = "GMI Provides Translation";
$stm_lbl['GMI_DATA_PROCESSING']     = "GMI provides Data Processing / Reports";
$stm_lbl['TOTALQS']       = "Total Questions Programmed";
$stm_lbl['LANGOVERLAY']   = "GMI Provides Language Overlay";
$stm_lbl['INCIDENT']      = "Proposed Incidence Rate";
$stm_lbl['N_LANG']        = "Number of Languages";
$stm_lbl['N_COMPLETE']    = "Number of Completes";
$stm_lbl['DATA_TAB']      = "Internal Data Delivery Cost Estimate";
$stm_lbl['OUT_SAMPLE']    = "Number of Outside Sample Supplier";
$stm_lbl['DATA_DELIVER']  = "Data Processing Hours";
$stm_lbl['TRACKER']       = "Tracker Study";
$stm_lbl['CLIENT_MAINT']  = "Client Maintenance";
$stm_lbl['PROJECT_VALUE'] = "Study Estimated Value";
$stm_lbl['PROJECT_QUOTED_VALUE']   = "Study Quoted Value";
$stm_lbl['RETAINER']      = 'Retainer Amount Received';
$stm_lbl['QUOTA_DESC']    = 'Detailed Quota Description';
$stm_lbl['SUPPORT_TICKET']= 'Key Service Ticket(s)';
$stm_lbl['CLIENT_TRANS']  = 'Client Provides Translation';
$stm_lbl['TRANS_VENDOR']  = 'Translation House';
$stm_lbl['DP_VENDOR']     = 'Data Processing House';
$stm_lbl['SPECIAL_INSTRUCT'] = 'Special Study Instructions';
$stm_lbl['DD_INSTRUCT']      = 'Data Processing Instructions';
$stm_lbl['DP_EXPORT_FT']  = 'Data Processing Export File Types';


$lbl['ACCOUNT_ID'] = 'Account ID';
$lbl['ACCOUNT'] = 'Account';
$lbl['ACCOUNT_NAME'] = 'Account Name';
$lbl['ACCOUNT_EXECUTIVE'] = 'Account Executive';
$lbl['ACCOUNT_MANAGER'] = 'Account Manager';
$lbl['REGION'] = 'Region';
$lbl['COUNTRY'] = 'Country';
$lbl['SOURCE_DEPARTMENT'] = 'Department';
$lbl['SOURCE_EMPLOYEE'] = 'Source Employee';
$lbl['PROPOSAL_WRITER'] = 'Proposal Writer';
$lbl['TYPE'] = 'Type';
$lbl['START_DATE'] = 'Start Date';
$lbl['END_DATE'] = 'End Date';
$lbl['MONTH'] = 'Month';
$lbl['YEAR'] = 'Year';
$lbl['STATUS'] = 'Status';
$lbl['PROPOSAL_NAME'] = 'Proposal Name';
$lbl['REVISION_NUMBER'] = 'Revision Number';
$lbl['PROPOSAL_DATE'] = 'Proposal Date';
$lbl['PROPOSAL_VALUE'] = 'Proposal Value';
$lbl['PROJECT_VALUE'] = 'Project Value';
$lbl['PROPOSAL_WON_DATE'] = 'Proposal Won Date';
$lbl['ACCOUNT_TYPE'] = 'Account Type';
$lbl['FEASIBILITY_ASSESSOR'] = 'Feasibility Assessor';
$lbl['DATE'] = 'Date';
$lbl['NOTES'] = 'Notes';
$lbl['REQUIRED'] = 'Required';
$lbl['PROPOSAL_DOCUMENT'] = 'Proposal Document';

$lbl['PRICING_REGIME'] = 'Pricing Regime';
$lbl['CHARGE_FOR_HOSTING'] = 'Charge For Hosting';
$lbl['SPECIAL_CHARGES'] = 'Special Charges';
$lbl['CONTACT_NAME'] = 'Contact Name';
$lbl['PROPOSAL_TYPE'] = 'Proposal Type';
$lbl['DATA_COLLECTION_METHOD'] = 'Data Collection Method';
$lbl['FIELD_WORK_PERIOD'] = 'Field Work Period';
$lbl['PROJECT_SETUP_PERIOD'] = 'Project Setup Period';
$lbl['DATA_PROCESSING_PERIOD'] = 'Data Processing Period';
$lbl['PROPOSAL_OPTION'] = 'Proposal Options';
$lbl['QUALIFYING_CRITERIA'] = 'Qualifying Criteria';
$lbl['PROPOSAL_OPTION_TYPE'] = 'Proposal Option Type';
$lbl['NUMBER_OF_COUNTRIES'] = 'Number of Countries';
$lbl['NUMBER_OF_STUDY_OPTIONS'] = 'Number of Study Options';
$lbl['REVIEW_PRICING_BEFORE_PROPOSAL'] = 'Review Pricing Before Entering Proposal';


$lbl['TRANSACTION_ID'] = 'Transaction ID';
$lbl['CUSTOMER'] = 'Customer';
$lbl['FIRST_NAME'] = 'First Name';
$lbl['LAST_NAME'] = 'Last Name';

//Billing Reports Language
$lbl['BR'] = "Billing Report";
$lbl['INVOICE'] = "Invoice";
$lbl['PERIOD'] = "Period";
$lbl['STUDY_ID'] = "Study ID";
$lbl['PARTNER'] = "Partner";
$lbl['BR_DATE'] = "BR Date";
$lbl['TRX_DATE'] = "Invoice Date";
$lbl['BR_STATUS'] = "BR Status";
$lbl['BR_TYPE'] = "BR Type";
$lbl['MERGED'] = "Merged/Single?";
$lbl['MONTH_END'] = "Month End?";
$col_hdr['Login'] = "Login";

$buttons['Submit'] = "Submit";
