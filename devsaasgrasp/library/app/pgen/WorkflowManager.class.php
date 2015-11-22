<?php

class pgen_WorkflowManager
{

	/**
* SaveAction()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Dec 21 23:00:41 PST 2005
*/
	function SaveAction($o)
	{
		$communication = new pgen_CommunicationManager();
		$e = new Encryption();
		$p = new proposalDB();
		$p->SetRevisionId($o['proposal_revision_id']);

		/* Check if this unauthorized attempt */
		if (!$p->isUserOnRevisionApprovalList($_SESSION['admin_id'])) {

			if (isset($_SESSION['login_via_pda']) && $_SESSION['login_via_pda'] == 1) {
				echo "You are not authorized to take action against this proposal";
				return true;
			}

			$_SESSION['ppm_message'] = 'You are not authorized to approve this proposal';
			header("Location: ?e=". $e->Encrypt("action=display_revision&proposal_revision_id=". $o['proposal_revision_id']."&proposal_id=". $o['proposal_id']));
			return true;

		}

		$p->SetRevisionAction($o['proposal_review_group_id'], $_SESSION['admin_id'], $o['proposal_action_id'], 'NOW()', $o['action_comment'] );

		//based on the action we need to change the revision_status and notify the rest of the group
		/*
		1. check if there are other groups remaining to be approved
		2. notify the group of the Actioner regarding the users action
		3. if the revision is approved set the status to approved_ready_to_send
		*/

		if ($o['proposal_action_id'] == PROPOSAL_ACTION_APPROVED) {

			$rs = $p->GetRemainingReviewGroups();

			if ($p->rows == 0) {
				//mark the revision ready to go
				$p->UpdateRevisionStatus(PROPOSAL_REVISION_STATUS_APPROVED);
			} else {
				//remind the remaining group
				$p->UpdateRevisionStatus(PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED);
			}
		} elseif ($o['proposal_action_id'] == PROPOSAL_ACTION_REJECTED) {
			$p->UpdateRevisionStatus(PROPOSAL_REVISION_STATUS_REJECTED);
		}

		$communication->SendUpdateNotification($o['proposal_id'], $o['proposal_revision_id'], $o['proposal_action_id'], $o['action_comment'], $_SESSION['admin_id']);
		//get all the users we notified regarding this decision
		/*$rs = $p->GetRevisionReviewLog();

		while ($r = mysql_fetch_assoc($rs)) {
		SendUpdateNotification($o['proposal_id'], $o['proposal_revision_id'], $r['first_name'],
		$r['last_name'], $r['email_address'], $o['proposal_action_id'], $o['action_comment'], $_SESSION['admin_id']);
		}*/

		if (isset($_SESSION['login_via_pda']) && $_SESSION['login_via_pda'] == 1) {
			echo "Your Action is Taken Against the proposal, You May Close the Browser Window";
			return true;
		}

		header("Location: ?e=". $e->Encrypt("action=display_revision&proposal_revision_id=". $o['proposal_revision_id']."&proposal_id=". $o['proposal_id']));
	}

	/**
* ApprovalRequest()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Dec 21 15:21:26 PST 2005
*/
function ApprovalRequest($o)
{
	$communication = new pgen_CommunicationManager();
	
	$e = new Encryption();
   $p = new proposalDB();
   $p->SetProposalId($o['proposal_id']);
   $p->SetRevisionId($o['proposal_revision_id']);
   
   $proposal_review_group_id = 0; //init 
   
   $status = $this->GetReviewStatusCode($o['proposal_id'], $o['proposal_revision_id']);

   //see which groups are responsible for review ( rules will be hard coded for now )
   //1. if client is in CS watch list send to cs group
   //2. if the proposal is over 100,000 send to EC group
   //3. if the proposal is done with custom pricing send to EC group

//   if ($status['cs_watch_list'] == 1) {
//      //rule 1
//      $rs = $p->GetReviewGroupMembersByGroupId(PROPOSAL_REVIEW_GROUP_CS);      
//      $proposal_review_group_id = PROPOSAL_REVIEW_GROUP_CS;
//      $reason = "Account in CS Watch List";
//   }

   if ($status['max_amount'] >= 100000) {
      //$rs = $p->GetReviewGroupMembersByGroupId(PROPOSAL_REVIEW_GROUP_CS);
      $proposal_review_group_id = PROPOSAL_REVIEW_GROUP_CS;
      $reason = "Value is over $100,000.00";
   }

//   if ($status['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
//      //rule 3
//      $rs = $p->GetReviewGroupMembersByGroupId(PROPOSAL_REVIEW_GROUP_EC);
//      $proposal_review_group_id = PROPOSAL_REVIEW_GROUP_EC;
//      $reason = "Pricing Method Custom";
//   }

	if ($proposal_review_group_id != 0) {
		$communication->SendApprovalNotification($o['proposal_id'], $o['proposal_revision_id'], $proposal_review_group_id, $reason);
	}

	/*
   //send an email to all the members of the group ( individual emails or one email with everyones name on it )
   while ($r = mysql_fetch_assoc($rs)) {
      SendApprovalNotification($o['proposal_id'], $o['proposal_revision_id'], $r['first_name'], $r['last_name'], $r['email_address'], $reason);
      
      //need to write to a log saying request sent to the users with the time stamp      
      $p->SetRevisionReviewLog($proposal_review_group_id, $r['login']);
   }*/
   
   //set the status to waiting for approval
   $p->UpdateRevisionStatus(PROPOSAL_REVISION_STATUS_WAITING_APPROVAL);
   
   $_SESSION['pgen_message'] = "Request Sent For Approval";
   
   header("Location: ?e=". $e->Encrypt("action=display_revision&proposal_id=". $o['proposal_id'] ."&proposal_revision_id=". $o['proposal_revision_id']));

   //when a user comes back to the review page, see if the revision is on waiting for approval status, and see if they belong to any pending groups

   //if so display accepted / rejected actions

   //once a group has reviewed send an email to the group again notifying them about the changes

}

/**
* GetReviewStatusCode()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Dec 28 11:08:35 PST 2005
*/
function GetReviewStatusCode($proposal_id, $proposal_revision_id)
{
   $p = new proposalDB();
   $p->SetProposalId($proposal_id);
   $p->SetRevisionId($proposal_revision_id);

   $status['cs_watch_list'] = 0;
   $status['max_amount'] = 0;
   $status['pricing_type_id'] = 0;

   $proposal = $p->GetBasicDetail();

   //$partner = new partnerDB($proposal['account_id']); GLOBAL_CS_WATCH_LIST

   if ($p->GetAttr('GLOBAL_CS_WATCH_LIST')) {
      $status['cs_watch_list'] = 1;
   }
   
   $r = $p->GetRevisionMaxPrice();

   $status['max_amount'] = (float) $r['max_amount'];

   $status['pricing_type_id'] = $proposal['pricing_type_id'];
   
   return $status;
   
}


}
?>