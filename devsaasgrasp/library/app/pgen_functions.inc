<?php

define('NUMBER_OF_BARS_PER_GRAPH', 10);

/* Components */

//require_once("pamc/AuctionManager.class");
//require_once("rcmc/RateCardManager.class");

/*
Proposal Manager business logic function
$Id: functions.inc 20389 2005-07-10 01:40:54Z nayana $
*/
/**
* HBRPC_GetAttrValues()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 03 14:05:10 PST 2006
*/
function HBRPC_PGEN_GetAttrValues($o)
{
	$p = new proposalDB();
	switch ($o['attr_name']) {
		case 'proposal_action_id':
			$data['data'] = PrepareSmartyArray($p->GetProposalActionList());
			$data['meta'] = array('index' => 'proposal_action_id', 'value' => 'proposal_action_description');
			break;

		default:
			break;
	}

	return $data;
}

/**
* HBRPC_GetProposalData()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 11:15:01 PST 2006
*/
function HBRPC_PGEN_GetProposalData($o)
{
	$p = new proposalDB();
	$p->SetProposalId($o['proposal_id']);

	$data = $p->GetBasicDetail();

	return $data;

}

/**
* HBRPC_GetPortlet()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Feb 22 15:36:14 PST 2006
*/
function HBRPC_PGEN_GetPortlet($o)
{
	$reports = new app_pgenReports();

	switch ($o['portlet_id']) {
		case PORTLET_ID_RECENT_PROPOSALS:
			$msg['body'] = $reports->GetRecentProposalPortlet($o['filter'], $o);
			break;
		case PORTLET_ID_PROPOSAL_WAITING_APPROVAL:
			$msg['body'] = $reports->GetWaitingApprovalPortlet($o['filter'], $o);
			break;
		default:
			break;
	}

	$msg['error_code'] = 0;
	$msg['error_message'] = 'Sucess';

	return $msg;
}

function HBRPC_PGEN_GetProposalList($o)
{
	$db = Hb_Util_Db_Connection::GetInstance();
	$select = $db->select();
	
	$select->from(array('pr' => 'proposal_revision'), array('proposal_id', 'proposal_revision_id', 'revision', 'proposal_revision_status_id'));
	$select->joinLeft(array('p' => 'proposal'), 'p.proposal_id = pr.proposal_id', array('proposal_status_id', 'proposal_name'));
	$select->where('p.status = ?', 'A');
	$select->where('p.account_id = ?', $o['account_id']);
	$select->where('pr.proposal_revision_status_id = ?', PROPOSAL_REVISION_STATUS_CLIENT_ACCEPTED);
	$select->order('p.proposal_id DESC');
	
	$rows = $db->fetchAll($select);
	
	$msg['data'] = serialize($rows);
	$msg['error_code'] = 0;
	
	return $msg;
}

function HBRPC_PGEN_GetProposalInfo($o)
{
	$db = Hb_Util_Db_Connection::GetInstance();
	$select = $db->select();
	
	$select->from(array('pr' => 'proposal_revision'), array('proposal_id', 'proposal_revision_id', 'revision', 'proposal_revision_status_id'));
	$select->joinLeft(array('p' => 'proposal'), 'p.proposal_id = pr.proposal_id', array('proposal_status_id', 'proposal_name'));
	$select->where('pr.proposal_revision_id = ?', $o['proposal_revision_id']);
	
	
	$rows = $db->fetchRow($select);
	
	$msg['data'] = serialize($rows);
	$msg['error_code'] = 0;
	
	return $msg;
}

function HBRPC_PGEN_GetOptionInfo($o)
{
	$db = Hb_Util_Db_Connection::GetInstance();
	$select = $db->select();
	
	$select->from(array('pr' => 'proposal_revision'), array('proposal_id', 'proposal_revision_id', 'revision', 'proposal_revision_status_id'));
	
	$select->joinLeft(
		array('pro' => 'proposal_revision_option'), 
		'pro.proposal_revision_id = pr.proposal_revision_id', 
		array(
			'proposal_revision_option_id', 'country_code', 'sub_group_description', 
			'completes', 'incidence_rate', 'sort_order', 'option_total', 'questions_programmed'));
		
	$select->joinLeft(array('p' => 'proposal'), 'p.proposal_id = pr.proposal_id', array('proposal_status_id', 'proposal_name'));
	
	$select->where('pr.proposal_revision_id = ?', $o['proposal_revision_id']);
	$select->where('pro.country_code = ?', $o['country_code']);
	
	
	$rows = $db->fetchAll($select);
	
	$msg['data'] = serialize($rows);
	$msg['error_code'] = 0;
	
	return $msg;
}
?>