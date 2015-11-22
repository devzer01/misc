<?php

class oracleDB extends oracleConnect
{
//   var $_settings = array();
	/* Constructur we will store the ID of the BR here */
	function oracleDB()
	{
		$this->oracleConnect();
//		$this->_settings = $this->GetSystemParams();
//		$this->SetOrgID($this->_settings['ORG_ID']);
}

	/**
	* GetAccountByName()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 29 15:02:31 PST 2005
	*/
	//Will check to see if there is an account for the company name provided, and will return an array
	//with info about the account, or false if not found.
	function GetAccountByName($company_name)
	{
		$q = "SELECT P.PARTY_NAME, P.PARTY_ID, A.CUST_ACCOUNT_ID FROM HZ_PARTIES P LEFT JOIN HZ_CUST_ACCOUNTS A ON P.PARTY_ID = A.PARTY_ID WHERE A.CUST_ACCOUNT_ID IS NOT NULL AND P.PARTY_TYPE = 'ORGANIZATION' AND TRIM(UPPER(P.PARTY_NAME)) = TRIM(UPPER('".$this->escape($company_name)."'))";
		$this->parse($q);
		$this->execute();
		return $this->fetch_assoc();
	}

	/**
	* GetAccountByFuzzyName()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Jul 25 13:11:39 PDT 2006
	*/
	function GetAccountByFuzzyName($company_name)
	{
		$q = "SELECT P.PARTY_NAME, P.PARTY_ID, A.CUST_ACCOUNT_ID FROM HZ_PARTIES P LEFT JOIN HZ_CUST_ACCOUNTS A ON P.PARTY_ID = A.PARTY_ID WHERE P.PARTY_TYPE = 'ORGANIZATION' AND UPPER(P.PARTY_NAME) LIKE UPPER('%".$this->escape($company_name)."%')";
		$this->parse($q);
		$this->execute();
		return $this->fetch_assoc();
	}

	/**
	* GetContactByName()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 15:01:40 PST 2005
	*/
	function GetContactByName($first_name, $last_name, $cust_acct_id, $cust_acct_site_id)
	{
		//$q = "SELECT P.PARTY_ID FROM HZ_PARTIES P LEFT JOIN HZ_RELATIONSHIPS R ON R.SUBJECT_ID = P.PARTY_ID AND R.SUBJECT_TYPE = 'PERSON' AND R.OBJECT_TYPE='ORGANIZATION' AND SUBJECT_TABLE_NAME = 'HZ_PARTIES' AND OBJECT_TABLE_NAME = 'HZ_PARTIES' LEFT JOIN HZ_CUST_ACCOUNTS A ON A.PARTY_ID = R.OBJECT_ID LEFT JOIN HZ_CUST_ACCT_SITES S ON S.CUST_ACCOUNT_ID = A.CUST_ACCOUNT_ID AND S.CUST_ACCT_SITE_ID = $cust_acct_site_id WHERE UPPER(TRIM(P.PARTY_NAME)) LIKE UPPER('%".$this->escape(trim($name))."%') AND P.PARTY_TYPE = 'PERSON' AND A.CUST_ACCOUNT_ID = $cust_acct_id";
		$q = "SELECT CONTACT_ID FROM RA_CUSTOMERS JOIN RA_ADDRESSES_ALL USING (CUSTOMER_ID) JOIN RA_CONTACTS USING (CUSTOMER_ID) WHERE CUSTOMER_ID = $cust_acct_id AND ADDRESS_ID = $cust_acct_site_id AND UPPER(FIRST_NAME) LIKE UPPER('%".$this->escape(trim($first_name))."%') AND UPPER(LAST_NAME) LIKE UPPER('%".$this->escape(trim($last_name))."%')";
		$this->parse($q);
		$this->execute();

		return $this->fetch_assoc();
	}

	/**
	* GetSiteByAddress()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Dec 08 12:32:43 PST 2005
	*/
	function GetSiteByAddress($address, $cust_account_id)
	{
		$q = "SELECT CS.CUST_ACCT_SITE_ID FROM HZ_CUST_ACCT_SITES CS LEFT JOIN HZ_PARTY_SITES PS ON PS.PARTY_SITE_ID = CS.PARTY_SITE_ID LEFT JOIN HZ_LOCATIONS L ON L.LOCATION_ID = PS.LOCATION_ID WHERE UPPER(TRIM(L.POSTAL_CODE)) LIKE UPPER('%".$this->escape(trim($address['zip']))."%') AND UPPER(TRIM(L.COUNTRY)) LIKE UPPER('%".$this->escape(trim($address['country']))."%') AND UPPER(TRIM(L.STATE)) LIKE UPPER('%".$this->escape(trim($address['state']))."%') AND UPPER(TRIM(L.ADDRESS1)) LIKE UPPER('%".$this->escape(trim($address['address1']))."%') AND CS.CUST_ACCOUNT_ID = '$cust_account_id'";
		$this->parse($q);
		$this->execute();
		return $this->fetch_assoc();
	}

	/**
	* NewGetSiteByAddress()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Dec 08 12:32:43 PST 2005
	*/
	function NewGetSiteByAddress($address, $cust_account_id)
	{
		$q =
		"SELECT
		    CS.CUST_ACCT_SITE_ID
		 FROM HZ_CUST_ACCT_SITES CS
		 LEFT JOIN HZ_PARTY_SITES PS ON PS.PARTY_SITE_ID = CS.PARTY_SITE_ID
		 LEFT JOIN HZ_LOCATIONS L ON L.LOCATION_ID = PS.LOCATION_ID
		 WHERE
		    UPPER(TRIM(L.CITY)) LIKE UPPER('%".$this->escape(trim($address['city']))."%')
		    AND UPPER(TRIM(L.COUNTRY)) LIKE UPPER('%".$this->escape(trim($address['country']))."%')
		    AND UPPER(TRIM(L.ADDRESS1)) LIKE UPPER('%".$this->escape(trim($address['address1']))."%')
		    AND CS.CUST_ACCOUNT_ID = '$cust_account_id'";
		$this->parse($q);
		$this->execute();
		return $this->fetch_assoc();
	}

	/**
	* GetInvoicePayments()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Jul 12 12:12:44 PDT 2006
	*/
	function GetInvoicePayments($invoice_number)
	{
	   $q =
	   "SELECT
	        T.TRX_NUMBER,
	        T.INVOICE_CURRENCY_CODE,
	        T.EXCHANGE_RATE AS INVOICE_EXCHANGE_RATE,
	        'Receipt' AS PAYMENT_TYPE,
	        R.CASH_RECEIPT_ID AS PAYMENT_ID,
	        R.RECEIPT_NUMBER AS DOCUMENT_NUMBER,
	        A.GL_DATE,
	        A.AMOUNT_APPLIED AS AMOUNT_PAID
	    FROM AR_RECEIVABLE_APPLICATIONS_ALL A
	    LEFT JOIN RA_CUSTOMER_TRX_ALL T ON T.CUSTOMER_TRX_ID=A.APPLIED_CUSTOMER_TRX_ID
	    LEFT JOIN AR_CASH_RECEIPTS_ALL R ON R.CASH_RECEIPT_ID=A.CASH_RECEIPT_ID
	    WHERE (T.TRX_NUMBER = '$invoice_number') AND (A.STATUS='APP') AND (A.APPLICATION_TYPE='CASH') AND (A.DISPLAY='Y')
	    UNION ALL
	    SELECT
	        T.TRX_NUMBER,
	        T.INVOICE_CURRENCY_CODE,
	        T.EXCHANGE_RATE AS INVOICE_EXCHANGE_RATE,
	        'Adjustment' AS PAYMENT_TYPE,
	        A.ADJUSTMENT_ID AS PAYMENT_ID,
	        A.ADJUSTMENT_NUMBER AS DOCUMENT_NUMBER,
	        A.GL_DATE,
	        -A.AMOUNT AS AMOUNT_PAID
	    FROM AR_ADJUSTMENTS_ALL A
	    LEFT JOIN RA_CUSTOMER_TRX_ALL T ON T.CUSTOMER_TRX_ID=A.CUSTOMER_TRX_ID
	    WHERE (T.TRX_NUMBER = '$invoice_number') AND (A.STATUS='A')
	    UNION ALL
       SELECT
	        T.TRX_NUMBER,
	        T.INVOICE_CURRENCY_CODE,
	        T.EXCHANGE_RATE AS INVOICE_EXCHANGE_RATE,
	        'Credit Memo' AS PAYMENT_TYPE,
	        A.CUSTOMER_TRX_ID AS PAYMENT_ID,
	        CMT.TRX_NUMBER AS DOCUMENT_NUMBER,
	        A.GL_DATE,
	        A.AMOUNT_APPLIED AS AMOUNT_PAID
       FROM AR_RECEIVABLE_APPLICATIONS_ALL A
       LEFT JOIN RA_CUSTOMER_TRX_ALL T ON T.CUSTOMER_TRX_ID=A.APPLIED_CUSTOMER_TRX_ID
       LEFT JOIN RA_CUSTOMER_TRX_ALL CMT ON CMT.CUSTOMER_TRX_ID=A.CUSTOMER_TRX_ID
       WHERE (T.TRX_NUMBER = '$invoice_number') AND (A.STATUS='APP') AND (A.APPLICATION_TYPE='CM') AND (A.DISPLAY='Y')
       ORDER BY PAYMENT_TYPE, GL_DATE";
	   //echo ($q);
	   $this->parse($q);
	   $this->execute();
	   $payments = array();
	   while ($payment = $this->fetch_assoc()) {
//	   	$receipts[$rcpt["TRX_NUMBER"]]["TRX_NUMBER"] = $rcpt["TRX_NUMBER"];
	   	$payments[] = $payment;
//	   	$receipts[$rcpt["TRX_NUMBER"]]["CASH_RECEIPT_ID"] = $rcpt["CASH_RECEIPT_ID"];
//	   	$receipts[$rcpt["TRX_NUMBER"]]["RECEIPT_NUMBER"] = $rcpt["RECEIPT_NUMBER"];
//	   	$receipts[$rcpt["TRX_NUMBER"]]["GL_DATE"] = date("Y-m-d", strtotime($rcpt["GL_DATE"], time()));
//	   	$receipts[$rcpt["TRX_NUMBER"]]["TOTAL_AMOUNT_APPLIED"] += $rcpt["AMOUNT_APPLIED"];
//	   	$receipts[$rcpt["TRX_NUMBER"]]["TRANS_TO_RECEIPT_RATE"] = $rcpt["TRANS_TO_RECEIPT_RATE"];
//	   	$receipts[$rcpt["TRX_NUMBER"]]["EXCHANGE_RATE"] = $rcpt["EXCHANGE_RATE"];
//	   	$receipts[$rcpt["TRX_NUMBER"]]["CURRENCY_CODE"] = $rcpt["CURRENCY_CODE"];
	   }
	   return $payments;
	}

//	/**
//	* CreateInvoices()
//	*
//	* @param
//	* @param -
//	* @return
//	* @throws
//	* @access
//	* @global
//	* @since  - Thu Dec 22 09:17:03 PST 2005
//	*/
//	function CreateInvoices($invoices, $inventory, $combinations, $settings)
//	{
//		$this->SetOrgID($settings['ORG_ID']);
//		$plsql = "DECLARE "
//		." p_trx_header_tbl        ar_invoice_api_pub.trx_header_tbl_type;"
//   		." p_trx_lines_tbl         ar_invoice_api_pub.trx_line_tbl_type;"
//   		." p_trx_dist_tbl          ar_invoice_api_pub.trx_dist_tbl_type;"
//	   	." p_trx_salescredits_tbl  ar_invoice_api_pub.trx_salescredits_tbl_type;"
//   		." p_batch_source_rec      ar_invoice_api_pub.batch_source_rec_type;"
//	   	." x_customer_trx_id number;"
//   		." x_return_status varchar2(2000);"
//	   	." x_msg_count number;"
//   		." x_msg_data varchar2(2000); "
//		."BEGIN "
//		." fnd_global.apps_initialize(".$settings['USER_ID'].", ".$settings['RESPONSIBILITY_ID'].", ".$settings['APPLICATION_ID'].", 0);"
//		." p_batch_source_rec.batch_source_id := ".$settings['BATCH_SOURCE_ID'].";";
//		$line_index = 1;
//		$header_index = 1;
//
//		foreach ($invoices as $index=>$i) {
//			if ($i['type']== TYPE_CREDIT_MEMO || $i['type'] == TYPE_CREDIT_MEMO_NOSTUDY) {
//				$cust_trx_type_id = $settings['CM_TRX_TYPE_ID'];
//			}else{
//				$cust_trx_type_id = $settings['INV_TRX_TYPE_ID'];
//			}
//    		$plsql .=
//    	 	" p_trx_header_tbl(".$header_index.").trx_header_id := $header_index;"
//	    	." p_trx_header_tbl(".$header_index.").cust_trx_type_id := $cust_trx_type_id;"
//    		." p_trx_header_tbl(".$header_index.").trx_date := TO_DATE('".substr($i['invoice_date'], 0, 10)."', 'yyyy-mm-dd');"
//    		." p_trx_header_tbl(".$header_index.").gl_date := TO_DATE('".substr($i['invoice_date'], 0, 10)."', 'yyyy-mm-dd');"
//    		." p_trx_header_tbl(".$header_index.").bill_to_customer_id := ".$i['company']['oracle_account_id'].";"
//	    	." p_trx_header_tbl(".$header_index.").bill_to_address_id := ".$i['contact']['ora_site_id'].";"
//    		." p_trx_header_tbl(".$header_index.").bill_to_contact_id := ".$i['contact']['ora_contact_id'].";"
//    		." p_trx_header_tbl(".$header_index.").ship_to_customer_id := ".$i['company']['oracle_account_id'].";"
//	    	." p_trx_header_tbl(".$header_index.").ship_to_address_id := ".$i['contact']['ora_site_id'].";"
//    		." p_trx_header_tbl(".$header_index.").ship_to_contact_id := ".$i['contact']['ora_contact_id'].";"
//    		." p_trx_header_tbl(".$header_index.").primary_salesrep_id := ".$i['company']['salesrep_id'].";"
//    		." p_trx_header_tbl(".$header_index.").remit_to_address_id := ".$settings['REMIT_TO_ADDRESS_ID'].";"
//	    	." p_trx_header_tbl(".$header_index.").printing_option := 'PRI';"
//			." p_trx_header_tbl(".$header_index.").interface_header_context := 'Hummingbird';";
//    		if (!$i['merged']) {
//    	   		$plsql .= " p_trx_header_tbl(".$header_index.").interface_header_attribute1 := '".$i['lines'][0]['study_id']."';";
//	    	}else{
//	    	      $plsql .= " p_trx_header_tbl(".$header_index.").interface_header_attribute1 := 'MERGED';";
//	    	}
//    		$plsql .= " p_trx_header_tbl(".$header_index.").interface_header_attribute2 := '".$i['br_id']."';";
//	    	$plsql .= " p_trx_header_tbl(".$header_index.").attribute_category := 'Hummingbird';"
//	    	." p_trx_header_tbl(".$header_index.").attribute1 := '".$i['project_name']."';"
//	    	." p_trx_header_tbl(".$header_index.").attribute2 := '".$i['po_number']."';"
//	    	." p_trx_header_tbl(".$header_index.").attribute3 := '".$i['job_number']."';"
//	    	." p_trx_header_tbl(".$header_index.").attribute4 := '".$i['pm_name']."';"
//	    	." p_trx_header_tbl(".$header_index.").attribute5 := '".$i['contact']['email']."';";
//          //." p_trx_header_tbl(".$header_index.").attribute6 := '".$i['br_id']."';";
//    		//." p_trx_header_tbl(".($index+1).").trx_currency := 'USD';"
//	    	$plsql .= " p_trx_header_tbl(".$header_index.").comments := '".$i['invoice_memo']."';";
//			foreach($i['lines'] as $indexx=>$line) {
//				if ($i['type']== TYPE_CREDIT_MEMO || $i['type'] == TYPE_CREDIT_MEMO_NOSTUDY) {
//					$qty = -$line['A_quantity'];
//				}else{
//					$qty = $line['A_quantity'];
//				}
//    			$plsql .= " p_trx_lines_tbl(".$line_index.").trx_header_id := $header_index;"
//    			." p_trx_lines_tbl(".$line_index.").trx_line_id := ".$line['br_line_id'].";"
//    			." p_trx_lines_tbl(".$line_index.").line_number := ".($indexx+1).";"
//	    		." p_trx_lines_tbl(".$line_index.").description := '".$line['description']."';"
//    			." p_trx_lines_tbl(".$line_index.").quantity_invoiced := $qty;"
//    			." p_trx_lines_tbl(".$line_index.").inventory_item_id := ".$inventory[$line['item_number']].";"
//    			." p_trx_lines_tbl(".$line_index.").unit_standard_price := ".$line['price'].";"
//	    		." p_trx_lines_tbl(".$line_index.").unit_selling_price := ".$line['unit_cost'].";"
//    			." p_trx_lines_tbl(".$line_index.").interface_line_context := 'Hummingbird';"
//    			." p_trx_lines_tbl(".$line_index.").interface_line_attribute1 := '".$line['study_id']."';"
//    			." p_trx_lines_tbl(".$line_index.").interface_line_attribute2 := '".$line['br_line_id']."';"
//            ." p_trx_lines_tbl(".$line_index.").attribute_category := 'Hummingbird';"
//            ." p_trx_lines_tbl(".$line_index.").attribute1 := '".$line['group_name']."';"
//            //." p_trx_lines_tbl(".$line_index.").attribute2 := '".$line['br_line_id']."';"
//    			." p_trx_lines_tbl(".$line_index.").line_type := 'LINE';";
//    			if (isset($combinations[$i['company']['company_code']."_".$i['contact']['country_code']."_".$i['company']['cost_center']."_".$line['gl_account_code']."_00_000_000000"])) {
//	    			$plsql .=
//    				 " p_trx_dist_tbl(".$line_index.").trx_header_id := ".$header_index.";"
//    				." p_trx_dist_tbl(".$line_index.").trx_line_id := ".$line['br_line_id'].";"
//    				." p_trx_dist_tbl(".$line_index.").trx_dist_id := ".($indexx+1).";"
//    				." p_trx_dist_tbl(".$line_index.").account_class := 'REV';"
//	    			//." p_trx_dist_tbl(".$line_index.").percent := 100;"
//	    			." p_trx_dist_tbl(".$line_index.").amount := ".$line['unit_cost']*$qty.";"
//    				." p_trx_dist_tbl(".$line_index.").code_combination_id := ".$combinations[$i['company']['company_code']."_".$i['contact']['country_code']."_".$i['company']['cost_center']."_".$line['gl_account_code']."_00_000_000000"].";";
//    			}
//    			$line_index++;
//			}
//			$header_index++;
//		}
//
//		$plsql .= " AR_INVOICE_API_PUB.create_invoice("
//	   	." 1.0,"
//   		." 'F',"
//	   	." 'F',"
//   		." p_batch_source_rec,"
//		." p_trx_header_tbl,"
//		." p_trx_lines_tbl,"
//		." p_trx_dist_tbl,"
//		." p_trx_salescredits_tbl,"
//		." :x_return_status,"
//		." :x_msg_count,"
//		." :x_msg_data); "
// 		." IF :x_msg_count > 1 THEN "
// 		."  FOR I IN 1..:x_msg_count "
// 		."  LOOP "
// 		."   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);"
// 		."  END LOOP;"
// 		." END IF;"
//		."END;";
//
//		//echo "<p>".$plsql;
//
//		$var['x_return_status'] = "";
//		$len['x_return_status'] = 2000;
//		$var['x_msg_count'] = 0;
//		$len['x_msg_count'] = 11;
//		$var['x_msg_data'] = "";
//		$len['x_msg_data'] = 2000;
//
//		$this->run($plsql, &$var, $len, OCI_DEFAULT);
//
//		return $var;
//
//	}

	/**
	* NewCreateInvoices()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Feb 20 14:42:03 PST 2006
	*/
	function NewCreateInvoices($invoices)
	{
		$this->SetOrgID($this->_settings['ORG_ID']);
		$plsql = "DECLARE "
		   ." p_trx_header_tbl        ar_invoice_api_pub.trx_header_tbl_type;"
   		." p_trx_lines_tbl         ar_invoice_api_pub.trx_line_tbl_type;"
   		." p_trx_dist_tbl          ar_invoice_api_pub.trx_dist_tbl_type;"
	   	." p_trx_salescredits_tbl  ar_invoice_api_pub.trx_salescredits_tbl_type;"
   		." p_batch_source_rec      ar_invoice_api_pub.batch_source_rec_type;"
	   	." x_customer_trx_id number;"
   		." x_return_status varchar2(2000);"
	   	." x_msg_count number;"
   		." x_msg_data varchar2(2000); "
		."BEGIN "
		." fnd_global.apps_initialize(".$this->_settings['USER_ID'].", ".$this->_settings['RESPONSIBILITY_ID'].", ".$this->_settings['APPLICATION_ID'].", 0);"
		." p_batch_source_rec.batch_source_id := ".$this->_settings['BATCH_SOURCE_ID'].";";
		$line_index = 1;
		$header_index = 1;

		foreach ($invoices as $index=>$i) {
   		//$cust_trx_type_id = $this->_settings['INV_TRX_TYPE_ID'];
    		$plsql .=
    	 	" p_trx_header_tbl(".$header_index.").trx_header_id := $header_index;"
	    	." p_trx_header_tbl(".$header_index.").cust_trx_type_id := ".$i["account"]["transaction_type_id"].";"
    		." p_trx_header_tbl(".$header_index.").trx_date := TO_DATE('".substr($i["invoice"]["transaction_date"], 0, 10)."', 'yyyy-mm-dd');"
    		." p_trx_header_tbl(".$header_index.").gl_date := TO_DATE('".substr($i["invoice"]["transaction_date"], 0, 10)."', 'yyyy-mm-dd');";
    		if ($i["account"]["currency"]!="USD") {
    		   $plsql .= " p_trx_header_tbl(".$header_index.").trx_currency := '".$i["account"]["currency"]."';"
    		   ." p_trx_header_tbl(".$header_index.").exchange_rate_type := 'User';"
    		   ." p_trx_header_tbl(".$header_index.").exchange_rate := ".$i["account"]["exchange_rate"].";";
    		}
                $plsql .= " p_trx_header_tbl(".$header_index.").invoicing_rule_id := -2;";
    		$plsql .= " p_trx_header_tbl(".$header_index.").bill_to_customer_id := ".$i["account"]["oracle_account_id"].";"
	    	." p_trx_header_tbl(".$header_index.").bill_to_address_id := ".$i["contact"]['ora_site_id'].";"
    		." p_trx_header_tbl(".$header_index.").bill_to_contact_id := ".$i["contact"]["ora_contact_id"].";"
    		." p_trx_header_tbl(".$header_index.").ship_to_customer_id := ".$i["account"]["oracle_account_id"].";"
	    	." p_trx_header_tbl(".$header_index.").ship_to_address_id := ".$i["contact"]["ora_site_id"].";"
    		." p_trx_header_tbl(".$header_index.").ship_to_contact_id := ".$i["contact"]["ora_contact_id"].";"
    		." p_trx_header_tbl(".$header_index.").primary_salesrep_id := ".$i["account"]["salesrep_id"].";"
    		." p_trx_header_tbl(".$header_index.").remit_to_address_id := ".$this->_settings['REMIT_TO_ADDRESS_ID'].";"
	    	." p_trx_header_tbl(".$header_index.").printing_option := 'PRI';"
			." p_trx_header_tbl(".$header_index.").interface_header_context := 'Hummingbird';"
    	   ." p_trx_header_tbl(".$header_index.").interface_header_attribute1 := '".$i["study"]["study_id"]."';"
    		." p_trx_header_tbl(".$header_index.").interface_header_attribute2 := '".$i['armc_id']."';"
	    	." p_trx_header_tbl(".$header_index.").attribute_category := 'Hummingbird';"
	    	." p_trx_header_tbl(".$header_index.").attribute1 := '".$i["study"]["study_name"]."';"
	    	." p_trx_header_tbl(".$header_index.").attribute2 := '".$i["invoice"]["po_number"]."';"
	    	." p_trx_header_tbl(".$header_index.").attribute3 := '".$i["invoice"]["job_number"]."';"
	    	." p_trx_header_tbl(".$header_index.").attribute4 := '".$i["invoice"]["pm_name"]."';"
	    	." p_trx_header_tbl(".$header_index.").attribute5 := '".$i["contact"]["email"]."';"
         ." p_trx_header_tbl(".$header_index.").attribute6 := '".($i["delivery_type"]["print"]?"Yes":"No")."';"
         ." p_trx_header_tbl(".$header_index.").attribute7 := '".$i["proposal_number"]."';"
         ." p_trx_header_tbl(".$header_index.").comments := '".$i["invoice"]["invoice_memo"]."';";
			foreach($i['lines'] as $indexx=>$line) {
    			$plsql .= " p_trx_lines_tbl(".$line_index.").trx_header_id := $header_index;"
    			." p_trx_lines_tbl(".$line_index.").trx_line_id := ".$line['armc_budget_line_item_id'].";"
    			." p_trx_lines_tbl(".$line_index.").line_number := ".($indexx+1).";"
	    		//." p_trx_lines_tbl(".$line_index.").description := '".$line['armc_budget_line_item_description']."';"
    			." p_trx_lines_tbl(".$line_index.").quantity_invoiced := ".$line["actual_quantity"].";"
    			." p_trx_lines_tbl(".$line_index.").inventory_item_id := ".$line["inventory_item_id"].";"
    			//." p_trx_lines_tbl(".$line_index.").unit_standard_price := ".$line["actual_rate"].";"
	    		." p_trx_lines_tbl(".$line_index.").unit_selling_price := ".$line["actual_rate_i18n"].";"
    			." p_trx_lines_tbl(".$line_index.").interface_line_context := 'Hummingbird';"
    			." p_trx_lines_tbl(".$line_index.").interface_line_attribute1 := '".$line["study_id"]."';"
    			." p_trx_lines_tbl(".$line_index.").interface_line_attribute2 := '".$line["armc_budget_line_item_id"]."';"
            ." p_trx_lines_tbl(".$line_index.").attribute_category := 'Hummingbird';"
            ." p_trx_lines_tbl(".$line_index.").attribute1 := '".$line["group_description"]."';"
            ." p_trx_lines_tbl(".$line_index.").attribute2 := '".$line["armc_budget_line_item_id"]."';"
    			." p_trx_lines_tbl(".$line_index.").line_type := 'LINE';"
            ." p_trx_lines_tbl(".$line_index.").accounting_rule_id := ".$line["accounting_rule_id"].";"
            ." p_trx_lines_tbl(".$line_index.").rule_start_date := TO_DATE('".substr($i["invoice"]["transaction_date"], 0, 10)."', 'yyyy-mm-dd');"
    		   ." p_trx_dist_tbl(".$line_index.").trx_header_id := ".$header_index.";"
    			." p_trx_dist_tbl(".$line_index.").trx_line_id := ".$line["armc_budget_line_item_id"].";"
    			." p_trx_dist_tbl(".$line_index.").trx_dist_id := ".($indexx+1).";"
    			." p_trx_dist_tbl(".$line_index.").account_class := 'REV';"
	    		." p_trx_dist_tbl(".$line_index.").percent := 100;"
	    		//." p_trx_dist_tbl(".$line_index.").amount := ".$line['actual_rate_i18n']*$line["actual_quantity"].";"
    			." p_trx_dist_tbl(".$line_index.").code_combination_id := ".$line["code_combination_id"].";";
    			$line_index++;
			}
			$header_index++;
		}

		$plsql .= " AR_INVOICE_API_PUB.create_invoice("
	   	." 1.0,"
   		." 'F',"
	   	." 'T',"
   		." p_batch_source_rec,"
		." p_trx_header_tbl,"
		." p_trx_lines_tbl,"
		." p_trx_dist_tbl,"
		." p_trx_salescredits_tbl,"
		." :x_return_status,"
		." :x_msg_count,"
		." :x_msg_data); "
 		." IF :x_msg_count > 1 THEN "
 		."  FOR I IN 1..:x_msg_count "
 		."  LOOP "
 		."   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);"
 		."  END LOOP;"
 		." END IF;"
		."END;";

		//echo "<p>".$plsql;

		$var['x_return_status'] = "";
		$len['x_return_status'] = 2000;
		$var['x_msg_count'] = 0;
		$len['x_msg_count'] = 11;
		$var['x_msg_data'] = "";
		$len['x_msg_data'] = 2000;

		$this->run($plsql, $var, $len, OCI_DEFAULT);

		return $var;

	}

	/**
	* CreateCM()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Jan 03 16:42:39 PST 2006
	*/
	function CreateCM($cm, $settings)
	{
		$this->SetOrgID($settings['ORG_ID']);
		//foreach($cms as $index=>$cm) {
      $plsql = ""
      ."DECLARE "
      ." lines AR_CREDIT_MEMO_API_PUB.cm_line_tbl_type_cover%type;"
   	."BEGIN "
   	." fnd_global.apps_initialize(".$settings['USER_ID'].", ".$settings['RESPONSIBILITY_ID'].", ".$settings['APPLICATION_ID'].", 0);";
   	$line_index = 1;
   	foreach($cm['lines'] as $line) {
         $plsql .=
          " lines($line_index).customer_trx_line_id := ".$line['customer_trx_line_id'].";"
         ." lines($line_index).extended_amount := ".($line['A_quantity']*$line['unit_cost']*-1).";"
         ." lines($line_index).quantity_credited := ".($line['A_quantity']*-1).";"
         ." lines($line_index).price := ".$line['unit_cost'].";";
         $line_index++;
   	}
      $plsql .=
       "AR_CREDIT_MEMO_API_PUB.Create_Request("
      ." p_api_version => 1.0,"
      ." x_return_status => :x_return_status,"
      ." x_msg_count => :x_msg_count,"
      ." x_msg_data => :x_msg_data,"
      ." p_customer_trx_id => ".$cm['apply_to_trx_id'].","
      ." p_line_credit_flag => 'Y',"
      ." p_cm_reason_code => 'CREDIT and REBILL',"
      ." p_cm_line_tbl => lines,"
      ." p_skip_workflow_flag => 'Y',"
      ." p_batch_source_name => 'Hummingbird',"
      ." x_request_id => :x_request_id);"
 		." IF :x_msg_count > 1 THEN "
 		."  FOR I IN 1..:x_msg_count "
 		."  LOOP "
 		."   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);"
 		."  END LOOP;"
 		." END IF;"
      ."END;";

      $var['x_return_status'] = "";
      $len['x_return_status'] = 2000;
      $var['x_msg_count'] = 0;
      $len['x_msg_count'] = 11;
      $var['x_msg_data'] = "";
      $len['x_msg_data'] = 2000;
      $var['x_request_id'] = "";
      $len['x_request_id'] = 2000;

      $this->debugPrint($plsql);

      $this->run($plsql, $var, $len, OCI_DEFAULT);


      if ($var['x_return_status']!=="S") {
         $this->rollback();
         return $var;
      }

      $this->commit();

      $plsql = ""
      ."DECLARE "
      ." v_status_meaning VARCHAR2(2000);"
      ." v_reason_meaning VARCHAR2(2000);"
      ." v_line_amount ra_cm_requests.line_amount%type;"
      ." v_tax_amount ra_cm_requests.tax_amount%type;"
      ." v_freight_amount ra_cm_requests.freight_amount%type;"
      ." v_line_credits_flag ra_cm_requests.line_credits_flag%type;"
      ." v_created_by wf_users.display_name%type;"
      ." v_creation_date DATE;"
      ." v_comments ra_cm_requests.comments%type;"
      ." v_cm_line_tbl AR_CREDIT_MEMO_API_PUB.cm_line_tbl_type_cover%type;"
      ." v_cm_activity_tbl AR_CREDIT_MEMO_API_PUB.cm_activity_tbl_type_cover;"
      ." v_cm_notes_tbl AR_CREDIT_MEMO_API_PUB.cm_notes_tbl_type_cover;"
      ."BEGIN "
   	." fnd_global.apps_initialize(".$settings['USER_ID'].", ".$settings['RESPONSIBILITY_ID'].", ".$settings['APPLICATION_ID'].", 0);"
      ."AR_CREDIT_MEMO_API_PUB.Get_Request_Status("
      ." p_api_version => 1.0,"
      ." x_return_status => :x_return_status,"
      ." x_msg_count => :x_msg_count,"
      ." x_msg_data => :x_msg_data,"
      ." p_request_id => ".$var['x_request_id'].","
      ." x_status_meaning => v_status_meaning,"
      ." x_reason_meaning => v_reason_meaning,"
      ." x_customer_trx_id => :x_customer_trx_id,"
      ." x_cm_customer_trx_id => :x_cm_customer_trx_id,"
      ." x_line_amount => v_line_amount,"
      ." x_tax_amount => v_tax_amount,"
      ." x_freight_amount => v_freight_amount,"
      ." x_line_credits_flag => v_line_credits_flag,"
      ." x_created_by => v_created_by,"
      ." x_creation_date => v_creation_date,"
      ." x_comments => v_comments,"
      ." x_approval_date => :x_approval_date,"
      ." x_cm_line_tbl => v_cm_line_tbl,"
      ." x_cm_activity_tbl => v_cm_activity_tbl,"
      ." x_cm_notes_tbl => v_cm_notes_tbl);"
 		." IF :x_msg_count > 1 THEN "
 		."  FOR I IN 1..:x_msg_count "
 		."  LOOP "
 		."   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);"
 		."  END LOOP;"
 		." END IF;"
 		."END;";

      $var['x_return_status'] = "";
      $len['x_return_status'] = 2000;
      $var['x_msg_count'] = 0;
      $len['x_msg_count'] = 11;
      $var['x_msg_data'] = "";
      $len['x_msg_data'] = 2000;
      $var['x_approval_date'] = "";
      $len['x_approval_date'] = 2000;
      $var['x_customer_trx_id'] = 0;
      $len['x_customer_trx_id'] = 11;
      $var['x_cm_customer_trx_id'] = 0;
      $len['x_cm_customer_trx_id'] = 11;

      $this->run($plsql, $var, $len, OCI_DEFAULT);

      if ($var['x_return_status']!=="S") {
         $this->rollback();
         return $var;
      }

      $this->commit();

      $plsql = "SELECT TRX_NUMBER FROM RA_CUSTOMER_TRX_ALL WHERE CUSTOMER_TRX_ID = ".$var['x_cm_customer_trx_id'];
      $this->parse($plsql);
      $this->execute();

      if ($fetch = $this->fetch_assoc()) {
         $var['x_trx_number'] = $fetch['TRX_NUMBER'];
      }else{
         $var['x_return_status'] = 'E';
         $var['x_msg_count'] = 1;
         $var['x_msg_data'] = 'Error retrieving Invoice Number !!!';
      }

      return $var;
	}

	/**
	* NewCreateCM()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Jan 03 16:42:39 PST 2006
	*/
	function NewCreateCM($cm)
	{
		$this->SetOrgID($this->_settings['ORG_ID']);
		//foreach($cms as $index=>$cm) {
      $plsql = ""
      ."DECLARE "
      ." lines AR_CREDIT_MEMO_API_PUB.cm_line_tbl_type_cover%type;"
   	."BEGIN "
   	." fnd_global.apps_initialize(".$this->_settings['USER_ID'].", ".$this->_settings['RESPONSIBILITY_ID'].", ".$this->_settings['APPLICATION_ID'].", 0);";
   	$line_index = 1;
   	foreach($cm['lines'] as $line) {
         $plsql .=
          " lines($line_index).customer_trx_line_id := ".$line['customer_trx_line_id'].";"
         ." lines($line_index).extended_amount := ".($line['actual_quantity']*$line['actual_rate_i18n']*-1).";"
         ." lines($line_index).quantity_credited := ".($line['actual_quantity']*-1).";"
         ." lines($line_index).price := ".$line['actual_rate_i18n'].";";
         $line_index++;
   	}
      $plsql .=
       "AR_CREDIT_MEMO_API_PUB.Create_Request("
      ." p_api_version => 1.0,"
      ." x_return_status => :x_return_status,"
      ." x_msg_count => :x_msg_count,"
      ." x_msg_data => :x_msg_data,"
      ." p_customer_trx_id => ".$cm['apply_to_trx_id'].","
      ." p_line_credit_flag => 'Y',"
      ." p_cm_reason_code => 'CREDIT and REBILL',"
      ." p_credit_method_rules => 'LIFO',"
      ." p_cm_line_tbl => lines,"
      ." p_skip_workflow_flag => 'Y',"
      ." p_batch_source_name => 'Hummingbird',"
      ." x_request_id => :x_request_id);"
 		." IF :x_msg_count > 1 THEN "
 		."  FOR I IN 1..:x_msg_count "
 		."  LOOP "
 		."   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);"
 		."  END LOOP;"
 		." END IF;"
      ."END;";

      $var['x_return_status'] = "";
      $len['x_return_status'] = 2000;
      $var['x_msg_count'] = 0;
      $len['x_msg_count'] = 11;
      $var['x_msg_data'] = "";
      $len['x_msg_data'] = 2000;
      $var['x_request_id'] = "";
      $len['x_request_id'] = 2000;

      //$this->debugPrint($plsql);

      $this->run($plsql, $var, $len, OCI_DEFAULT);


      if ($var['x_return_status']!=="S") {
         $this->rollback();
         return $var;
      }

      $this->commit();

      $plsql = ""
      ."DECLARE "
      ." v_status_meaning VARCHAR2(2000);"
      ." v_reason_meaning VARCHAR2(2000);"
      ." v_line_amount ra_cm_requests.line_amount%type;"
      ." v_tax_amount ra_cm_requests.tax_amount%type;"
      ." v_freight_amount ra_cm_requests.freight_amount%type;"
      ." v_line_credits_flag ra_cm_requests.line_credits_flag%type;"
      ." v_created_by wf_users.display_name%type;"
      ." v_creation_date DATE;"
      ." v_comments ra_cm_requests.comments%type;"
      ." v_cm_line_tbl AR_CREDIT_MEMO_API_PUB.cm_line_tbl_type_cover%type;"
      ." v_cm_activity_tbl AR_CREDIT_MEMO_API_PUB.cm_activity_tbl_type_cover;"
      ." v_cm_notes_tbl AR_CREDIT_MEMO_API_PUB.cm_notes_tbl_type_cover;"
      ."BEGIN "
   	." fnd_global.apps_initialize(".$this->_settings['USER_ID'].", ".$this->_settings['RESPONSIBILITY_ID'].", ".$this->_settings['APPLICATION_ID'].", 0);"
      ."AR_CREDIT_MEMO_API_PUB.Get_Request_Status("
      ." p_api_version => 1.0,"
      ." x_return_status => :x_return_status,"
      ." x_msg_count => :x_msg_count,"
      ." x_msg_data => :x_msg_data,"
      ." p_request_id => ".$var['x_request_id'].","
      ." x_status_meaning => v_status_meaning,"
      ." x_reason_meaning => v_reason_meaning,"
      ." x_customer_trx_id => :x_customer_trx_id,"
      ." x_cm_customer_trx_id => :x_cm_customer_trx_id,"
      ." x_line_amount => v_line_amount,"
      ." x_tax_amount => v_tax_amount,"
      ." x_freight_amount => v_freight_amount,"
      ." x_line_credits_flag => v_line_credits_flag,"
      ." x_created_by => v_created_by,"
      ." x_creation_date => v_creation_date,"
      ." x_comments => v_comments,"
      ." x_approval_date => :x_approval_date,"
      ." x_cm_line_tbl => v_cm_line_tbl,"
      ." x_cm_activity_tbl => v_cm_activity_tbl,"
      ." x_cm_notes_tbl => v_cm_notes_tbl);"
 		." IF :x_msg_count > 1 THEN "
 		."  FOR I IN 1..:x_msg_count "
 		."  LOOP "
 		."   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);"
 		."  END LOOP;"
 		." END IF;"
 		."END;";

      $var['x_return_status'] = "";
      $len['x_return_status'] = 2000;
      $var['x_msg_count'] = 0;
      $len['x_msg_count'] = 11;
      $var['x_msg_data'] = "";
      $len['x_msg_data'] = 2000;
      $var['x_approval_date'] = "";
      $len['x_approval_date'] = 2000;
      $var['x_customer_trx_id'] = 0;
      $len['x_customer_trx_id'] = 11;
      $var['x_cm_customer_trx_id'] = 0;
      $len['x_cm_customer_trx_id'] = 11;

      $this->run($plsql, $var, $len, OCI_DEFAULT);

      if ($var['x_return_status']!=="S") {
         $this->rollback();
         return $var;
      }

      $this->commit();

      //$plsql = "SELECT CUSTOMER_TRX_ID, TRX_NUMBER, TRX_DATE FROM RA_CUSTOMER_TRX_ALL WHERE CUSTOMER_TRX_ID = ".$var['x_cm_customer_trx_id'];
      $plsql = "SELECT MIN(H.CUSTOMER_TRX_ID) AS CUSTOMER_TRX_ID, MIN(H.TRX_NUMBER) AS TRX_NUMBER, MIN(D.GL_DATE) AS TRX_DATE FROM RA_CUSTOMER_TRX_ALL H LEFT JOIN RA_CUST_TRX_LINE_GL_DIST_ALL D ON D.CUSTOMER_TRX_ID=H.CUSTOMER_TRX_ID WHERE H.CUSTOMER_TRX_ID = ".$var['x_cm_customer_trx_id']." GROUP BY H.CUSTOMER_TRX_ID";
      $this->parse($plsql);
      $this->execute();

      if ($fetch = $this->fetch_assoc()) {
         $var["x_customer_trx_id"] = $fetch["CUSTOMER_TRX_ID"];
         $var["x_trx_number"] = $fetch["TRX_NUMBER"];
         $var["x_trx_date"] = $fetch["TRX_DATE"];
      }else{
         $var['x_return_status'] = 'E';
         $var['x_msg_count'] = 1;
         $var['x_msg_data'] = 'Error retrieving Invoice Number !!!';
      }

      return $var;
	}
	
	/**
	* InsertTrxAttr
	*
	* @param
	* @return
	* @throws
	* @version 2.0.1
	* @since Mon Jul 30 19:06:14 PDT 2007
	*/
	public function InsertTrxAttr($customer_trx_id, $trx_seq_number, $attribute_name, $attribute_value) 
	{
      $plsql = "INSERT INTO XXGMI_AR_CUSTOMER_TRX_ATTR (CUSTOMER_TRX_ID, TRX_SEQ_NUMBER, ATTRIBUTE_NAME, ATTRIBUTE_VALUE, CREATED_BY, LAST_UPDATED_BY) VALUES ('".$customer_trx_id."', '".$trx_seq_number."', '".$attribute_name."', '".$attribute_value."', '".$this->_settings["USER_ID"]."', '".$this->_settings["USER_ID"]."')";
		$this->parse($plsql);
		$this->execute();      
	}

	/**
	* DeleteAllContacts()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Dec 06 09:35:37 PST 2005
	*/
	function DeleteAllContacts()
	{
		$brDB = new brDB();
		$q = "DELETE FROM HZ_CUST_ACCOUNTS WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();

		$q = "DELETE FROM HZ_CUSTOMER_PROFILES WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();

		$q = "DELETE FROM HZ_PARTIES WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();

		$q = "DELETE FROM HZ_ORGANIZATION_PROFILES WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();

		$q = "DELETE FROM HZ_PERSON_PROFILES WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();

		$q = "SELECT LOCATION_ID FROM HZ_LOCATIONS WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();
		$ids = Array();
		while ($location = $this->fetch_assoc()) {
			$ids[] = $location['LOCATION_ID'];
		}

		foreach ($ids as $id) {
			$q = "DELETE FROM HZ_LOCATION_PROFILES WHERE LOCATION_ID = '$id'";
			$this->parse($q);
			$this->execute();
		}
		$q = "DELETE FROM HZ_LOCATIONS WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();

		$q = "DELETE FROM HZ_PARTY_SITES WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();

		$q = "DELETE FROM HZ_CUST_ACCT_SITES WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();

		$q = "DELETE FROM HZ_CUST_SITE_USES WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();

		$q = "DELETE FROM HZ_ORG_CONTACTS WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();

		$q = "DELETE FROM HZ_CUST_ACCOUNT_ROLES WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();

		$q = "DELETE FROM HZ_ROLE_RESPONSIBILITY WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();

		$q = "DELETE FROM HZ_CONTACT_POINTS WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();

		$q = "DELETE FROM HZ_RELATIONSHIPS WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
		$this->parse($q);
		$this->execute();
		$brDB->executeQuery("UPDATE partners_attr SET value = '' WHERE `key`='ORACCT'");
		$brDB->executeQuery("UPDATE contacts SET ora_contact_id = 0 WHERE ora_contact_id != 0");
		$brDB->executeQuery("UPDATE contacts SET ora_site_id = 0 WHERE ora_site_id != 0");
	}

	/**
	* DisplayAllContacts()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Dec 06 10:05:47 PST 2005
	*/
	function DisplayAllContacts()
	{
		$tables = Array('HZ_PARTIES', 'HZ_CUST_ACCOUNTS', 'HZ_CUSTOMER_PROFILES', 'HZ_ORGANIZATION_PROFILES', 'HZ_PERSON_PROFILES', 'HZ_LOCATIONS', 'HZ_PARTY_SITES', 'HZ_CUST_ACCT_SITES', 'HZ_CUST_SITE_USES', 'HZ_ORG_CONTACTS', 'HZ_CUST_ACCOUNT_ROLES', 'HZ_ROLE_RESPONSIBILITY', 'HZ_CONTACT_POINTS', 'HZ_RELATIONSHIPS');
		$fields['HZ_CUST_ACCOUNTS'] = Array('CUST_ACCOUNT_ID', 'PARTY_ID', 'ACCOUNT_NUMBER', 'CREATION_DATE', 'LAST_UPDATE_DATE');
		$fields['HZ_CUSTOMER_PROFILES'] = Array('PARTY_ID', 'CUST_ACCOUNT_PROFILE_ID', 'CUST_ACCOUNT_ID', 'CREATION_DATE', 'LAST_UPDATE_DATE');
		//$fields['HZ_CUSTOMER_PROFILES'] = Array('*');
		$fields['HZ_PARTIES'] = Array('PARTY_ID', 'PARTY_NUMBER', 'PARTY_NAME', 'PARTY_TYPE', 'CREATION_DATE', 'LAST_UPDATE_DATE');
		$fields['HZ_ORGANIZATION_PROFILES'] = Array('ORGANIZATION_PROFILE_ID', 'PARTY_ID', 'ORGANIZATION_NAME', 'CREATION_DATE', 'LAST_UPDATE_DATE');
		$fields['HZ_PERSON_PROFILES'] = Array('PERSON_PROFILE_ID', 'PARTY_ID', 'PERSON_NAME', 'CREATION_DATE', 'LAST_UPDATE_DATE');
		$fields['HZ_LOCATIONS'] = Array('LOCATION_ID', 'ADDRESS1', 'ADDRESS2', 'CITY', 'STATE', 'POSTAL_CODE', 'COUNTY', 'COUNTRY', 'CREATION_DATE', 'LAST_UPDATE_DATE');
		$fields['HZ_PARTY_SITES'] = Array('PARTY_SITE_ID', 'PARTY_ID', 'LOCATION_ID', 'PARTY_SITE_NUMBER', 'IDENTIFYING_ADDRESS_FLAG', 'CREATION_DATE', 'LAST_UPDATE_DATE');
		$fields['HZ_CUST_ACCT_SITES'] = Array('CUST_ACCT_SITE_ID', 'CUST_ACCOUNT_ID', 'PARTY_SITE_ID', 'CREATION_DATE', 'LAST_UPDATE_DATE');
		//$fields['HZ_CUST_SITE_USES'] = Array('SITE_USE_ID', 'CUST_ACCT_SITE_ID', 'SITE_USE_CODE', 'PRIMARY_FLAG', 'LOCATION', 'CREATION_DATE', 'LAST_UPDATE_DATE');
		$fields['HZ_CUST_SITE_USES'] = Array('*');
		$fields['HZ_ORG_CONTACTS'] = Array('*');
		$fields['HZ_CUST_ACCOUNT_ROLES'] = Array('*');
		$fields['HZ_ROLE_RESPONSIBILITY'] = Array('*');
		$fields['HZ_CONTACT_POINTS'] = Array('*');
		$fields['HZ_RELATIONSHIPS'] = Array('*');
		foreach($tables as $table) {

			$q = "SELECT ";
			foreach($fields[$table] as $field) {
				$q .= "$field, ";
			}
			$q = substr($q, 0, strlen($q)-2);
			$q .= " FROM $table WHERE CREATED_BY_MODULE = 'HUMMINGBIRD'";
			$this->parse($q);
			$this->execute();

			echo ("Query : $q<br>");
			echo ("Table : <b>$table</b><br>");

			while ($c = $this->fetch_assoc()) {
				foreach($c as $key=>$value) {
					echo ("$key => $value<br>");
				}
				echo ("=======================<br>");
			}
		}
	}

	/**
	* SetOrgID()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 15:35:12 PST 2005
	*/
//	function SetOrgID($org_id)
//	{
//		$plsql = "BEGIN dbms_application_info.set_client_info('$org_id'); END;";
//		$this->parse($plsql);
//		$this->execute();
//	}

	/**
	* GetSystemParams()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 21 10:07:24 PST 2005
	*/
//	function GetSystemParams()
//	{
//		$ret = Array("ORG_ID"=>0);
//		$this->SetOrgID($ret['ORG_ID']);
//		$q = "SELECT BATCH_SOURCE_ID FROM RA_BATCH_SOURCES WHERE UPPER(NAME) = 'HUMMINGBIRD'";
//		$this->parse($q);
//		$this->execute();
//		$ret = array_merge($ret, $this->fetch_assoc());
//
////		$q = "SELECT CUST_TRX_TYPE_ID, TYPE FROM RA_CUST_TRX_TYPES WHERE UPPER(NAME) LIKE '%HUMMINGBIRD%'";
////		$this->parse($q);
////		$this->execute();
////		while ($d = $this->fetch_assoc()) {
////			switch ($d['TYPE']) {
////			case 'INV' : $ret['INV_TRX_TYPE_ID'] = $d['CUST_TRX_TYPE_ID']; break;
////			case 'CM' : $ret['CM_TRX_TYPE_ID'] = $d['CUST_TRX_TYPE_ID']; break;
////			}
////		}
//
//		$q = "SELECT USER_ID FROM FND_USER WHERE UPPER(USER_NAME) LIKE '%HUMMINGBIRD%'";
//		$this->parse($q);
//		$this->execute();
//		$ret = array_merge($ret, $this->fetch_assoc());
//
//		$q = "SELECT APPLICATION_ID, RESPONSIBILITY_ID FROM FND_RESPONSIBILITY_TL WHERE UPPER(RESPONSIBILITY_NAME) LIKE '%RECEIVABLES MANAGER%'";
//		$this->parse($q);
//		$this->execute();
//		$ret = array_merge($ret, $this->fetch_assoc());
//
//		$q = "SELECT ADDRESS_ID REMIT_TO_ADDRESS_ID FROM AR_ACTIVE_REMIT_TO_ADDRESSES_V";
//		$this->parse($q);
//		$this->execute();
//		$ret = array_merge($ret, $this->fetch_assoc());
//
//		return $ret;
//	}

	/**
	* GetAccountCombinations()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 21 11:49:48 PST 2005
	*/
	function GetAccountCombinations($combinations)
	{
		$plsql =
		 "DECLARE "
		." segment_array fnd_flex_ext.SegmentArray;"
		." succ BOOLEAN;"
		."BEGIN ";
		foreach ($combinations as $comb=>$combination) {
			//$comb = $combination['SEGMENT1']."_".$combination['SEGMENT2']."_".$combination['SEGMENT3']."_".$combination['SEGMENT4']."_".$combination['SEGMENT5']."_".$combination['SEGMENT6']."_".$combination['SEGMENT7'];
			$var["id_".$comb] = 0;
			$len["id_".$comb] = 11;
			$var["succ_".$comb] = true;
			//$len["succ_".$comb] = 10;
			$plsql .=
			 " segment_array(1) := '".$combination['SEGMENT1']."';"
			." segment_array(2) := '".$combination['SEGMENT2']."';"
			." segment_array(3) := '".$combination['SEGMENT3']."';"
			." segment_array(4) := '".$combination['SEGMENT4']."';"
			." segment_array(5) := '".$combination['SEGMENT5']."';"
			." segment_array(6) := '".$combination['SEGMENT6']."';"
			." segment_array(7) := '".$combination['SEGMENT7']."';"
			." succ := FND_FLEX_EXT.GET_COMBINATION_ID("
			." application_short_name => 'SQLGL',"
			." key_flex_code => 'GL#',"
			." structure_number => 101,"
			." validation_date => SYSDATE,"
			." n_segments => 7,"
			." segments => segment_array,"
			." combination_id => :id_".$comb.");"
			." if (succ) then :succ_".$comb." := 1;"
			." else :succ_".$comb." := 0;"
			." end if;";
		}
		$plsql .= "END;";

		$this->run($plsql, $var, $len);

		foreach($combinations as $comb=>$combination) {
			if ($var["succ_".$comb]) {
				$ret[$comb] = $var["id_".$comb];
			}
		}
		return $ret;
	}

	/**
	* GetInventoryItems()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Dec 22 10:29:54 PST 2005
	*/
	function GetInventoryItems($items)
	{
		$ret = Array();
		$where_inv = "(";
		foreach($items as $key=>$item) {
			$where_inv .= "(segment1 = '$item') OR ";
		}
		$where_inv = substr($where_inv, 0, strlen($where_inv)-4).")";

		$sql = "SELECT inventory_item_id, segment1 FROM MTL_SYSTEM_ITEMS where $where_inv";
		$this->parse($sql);
		$this->execute();
		while ($item = $this->fetch_assoc()) {
			$ret[$item['SEGMENT1']] = $item['INVENTORY_ITEM_ID'];
		}
		return $ret;
	}

	/**
	* GetInventoryItem()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Feb 20 14:10:48 PST 2006
	*/
	function GetInventoryItem($item_number)
	{
      $sql = "SELECT i.inventory_item_id, i.segment1 AS item_number, i.description, i.accounting_rule_id, r.frequency, r.occurrences FROM MTL_SYSTEM_ITEMS i LEFT JOIN RA_RULES r ON r.rule_id=i.accounting_rule_id WHERE segment1 = '$item_number'";
      $this->parse($sql);
      $this->execute();
      return $this->fetch_assoc();
	}
	/**
	* CreateCustAccount()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 15:37:53 PST 2005
	*/
	function CreateCustAccount($company_name)
	{
		$plsql = "DECLARE ";
		$plsql .= " p_cust_account_rec HZ_CUST_ACCOUNT_V2PUB.CUST_ACCOUNT_REC_TYPE;";
		$plsql .= " p_organization_rec HZ_PARTY_V2PUB.ORGANIZATION_REC_TYPE;";
		$plsql .= " p_customer_profile_rec HZ_CUSTOMER_PROFILE_V2PUB.CUSTOMER_PROFILE_REC_TYPE;";
		$plsql .= " x_account_number VARCHAR2(2000);";
		$plsql .= " x_party_number VARCHAR2(2000);";
		$plsql .= " x_profile_id NUMBER;";
		$plsql .= "BEGIN ";
		$plsql .= " p_cust_account_rec.account_number := '';";
		$plsql .= " p_cust_account_rec.created_by_module := 'HUMMINGBIRD';";
		$plsql .= " p_organization_rec.organization_name := '".strtoupper($company_name)."';";
		$plsql .= " p_organization_rec.created_by_module := 'HUMMINGBIRD';";
		$plsql .= " hz_cust_account_v2pub.create_cust_account('T', p_cust_account_rec, p_organization_rec, p_customer_profile_rec, 'F', :x_cust_account_id, x_account_number, :x_party_id, x_party_number, x_profile_id, :x_return_status, :x_msg_count, :x_msg_data);";
 		$plsql .= " IF :x_msg_count > 1 THEN ";
 		$plsql .= "  FOR I IN 1..:x_msg_count ";
 		$plsql .= "  LOOP ";
 		$plsql .= "   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);";
 		$plsql .= "  END LOOP;";
 		$plsql .= " END IF;";
		$plsql .= "END;";

		$var['x_cust_account_id'] = 0;
		$len['x_cust_account_id'] = 11;
		$var['x_party_id'] = 0;
		$len['x_party_id'] = 11;
		$var['x_return_status'] = "";
		$len['x_return_status'] = 2000;
		$var['x_msg_count'] = 0;
		$len['x_msg_count'] = 11;
		$var['x_msg_data'] = "";
		$len['x_msg_data'] = 2000;

		$this->run($plsql, $var, $len, OCI_DEFAULT);

		if ($var['x_return_status'] !== 'S') {
//			echo ("<p>Query : $plsql<br>");
//			echo ($var['x_msg_count']." error(s) : ".$var['x_msg_data']."<P>");
//			print_r($var);
         $this->errorAlert("CreateCustAccount($company_name) returned with status : ".$var['x_return_status']." and ".$var['x_msg_count']." message(s)\n".$var['x_msg_data']);
			$this->rollback();
			return false;
		}

		return $var;
	}

	/**
	* CreateLocation()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 15:43:06 PST 2005
	*/
	function CreateLocation($address)
	{
		$plsql = "DECLARE ";
 		$plsql .= " p_location_rec HZ_LOCATION_V2PUB.LOCATION_REC_TYPE;";
		$plsql .= "BEGIN ";
 		$plsql .= " p_location_rec.country := '".strtoupper($address['country'])."';";
 		$plsql .= " p_location_rec.address1 := '".strtoupper($address['address1'])."';";
 		$plsql .= " p_location_rec.address2 := '".strtoupper($address['address2'])."';";
 		$plsql .= " p_location_rec.city := '".strtoupper($address['city'])."';";
 		$plsql .= " p_location_rec.postal_code := '".strtoupper($address['zip'])."';";
 		$plsql .= " p_location_rec.county := '".strtoupper($address['county'])."';";
 		$plsql .= " p_location_rec.state := '".strtoupper($address['state'])."';";
 		$plsql .= " p_location_rec.created_by_module := 'HUMMINGBIRD';";
 		$plsql .= " hz_location_v2pub.create_location(";
 		$plsql .= " 'T',";
 		$plsql .= " p_location_rec,";
 		$plsql .= " :x_location_id,";
 		$plsql .= " :x_return_status,";
 		$plsql .= " :x_msg_count,";
 		$plsql .= " :x_msg_data);";
 		$plsql .= " IF :x_msg_count > 1 THEN ";
 		$plsql .= "  FOR I IN 1..:x_msg_count ";
 		$plsql .= "  LOOP ";
 		$plsql .= "   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);";
 		$plsql .= "  END LOOP;";
 		$plsql .= " END IF;";
		$plsql .= " END;";

		$var['x_location_id'] = 0;
		$len['x_location_id'] = 11;
		$var['x_return_status'] = "";
		$len['x_return_status'] = 2000;
		$var['x_msg_count'] = 0;
		$len['x_msg_count'] = 11;
		$var['x_msg_data'] = "";
		$len['x_msg_data'] = 2000;

		$this->run($plsql, $var, $len, OCI_DEFAULT);

		if ($var['x_return_status'] !== 'S') {
//			echo ("<p>Query : $plsql<br>");
//			echo ($var['x_msg_count']." error(s) : ".$var['x_msg_data']."<P>");
//			print_r($var);
         $this->errorAlert("CreateLocation(".$address['address1'].", ".$address['city'].", ".$address['state']." ".$address['zip']." returned with status : ".$var['x_return_status']." and ".$var['x_msg_count']." message(s)\n".$var['x_msg_data']);
			$this->rollback();
			return false;
		}

		return $var;

	}

	/**
	* CreatePartySite()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 15:56:29 PST 2005
	*/
	function CreatePartySite($party_id, $location_id)
	{
		$plsql = "DECLARE ";
		$plsql .= " p_party_site_rec HZ_PARTY_SITE_V2PUB.PARTY_SITE_REC_TYPE;";
		$plsql .= " x_party_site_number VARCHAR2(2000);";
		$plsql .= "BEGIN ";
 		$plsql .= " p_party_site_rec.party_id := $party_id;";
 		$plsql .= " p_party_site_rec.location_id := $location_id;";
 		$plsql .= " p_party_site_rec.identifying_address_flag := 'Y';";
 		$plsql .= " p_party_site_rec.created_by_module := 'HUMMINGBIRD';";
 		$plsql .= " hz_party_site_v2pub.create_party_site(";
 		$plsql .= " 'T',";
 		$plsql .= " p_party_site_rec,";
 		$plsql .= " :x_party_site_id,";
 		$plsql .= " x_party_site_number,";
 		$plsql .= " :x_return_status,";
 		$plsql .= " :x_msg_count,";
 		$plsql .= " :x_msg_data);";
 		$plsql .= " IF :x_msg_count > 1 THEN ";
 		$plsql .= "  FOR I IN 1..:x_msg_count ";
 		$plsql .= "  LOOP ";
 		$plsql .= "   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);";
 		$plsql .= "  END LOOP;";
 		$plsql .= " END IF;";
 		$plsql .= "END;";

		$var['x_party_site_id'] = 0;
		$len['x_party_site_id'] = 11;
		$var['x_return_status'] = "";
		$len['x_return_status'] = 2000;
		$var['x_msg_count'] = 0;
		$len['x_msg_count'] = 11;
		$var['x_msg_data'] = "";
		$len['x_msg_data'] = 2000;

		$this->run($plsql, $var, $len, OCI_DEFAULT);

		if ($var['x_return_status'] !== 'S') {
//			echo ("<p>Query : $plsql<br>");
//			echo ($var['x_msg_count']." error(s) : ".$var['x_msg_data']."<P>");
//			print_r($var);
         $this->errorAlert("CreatePartySite($party_id, $location_id) returned with status : ".$var['x_return_status']." and ".$var['x_msg_count']." message(s)\n".$var['x_msg_data']);
			$this->rollback();
			return false;
		}
		return $var;
	}

	/**
	* CreateCustAcctSite()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 16:00:06 PST 2005
	*/
	function CreateCustAcctSite($cust_account_id, $party_site_id)
	{
		$plsql = "DECLARE ";
 		$plsql .= " p_cust_acct_site_rec HZ_CUST_ACCOUNT_SITE_V2PUB.CUST_ACCT_SITE_REC_TYPE;";
		$plsql .= "BEGIN ";
 		$plsql .= " p_cust_acct_site_rec.cust_account_id := $cust_account_id; ";
 		$plsql .= " p_cust_acct_site_rec.party_site_id := $party_site_id;";
 		//$plsql .= " p_cust_acct_site_rec.language := 'US';";
 		$plsql .= " p_cust_acct_site_rec.created_by_module := 'HUMMINGBIRD';";
 		$plsql .= " hz_cust_account_site_v2pub.create_cust_acct_site(";
 		$plsql .= " 'T',";
 		$plsql .= " p_cust_acct_site_rec,";
 		$plsql .= " :x_cust_acct_site_id,";
 		$plsql .= " :x_return_status,";
 		$plsql .= " :x_msg_count,";
 		$plsql .= " :x_msg_data);";
 		$plsql .= " IF :x_msg_count > 1 THEN ";
 		$plsql .= "  FOR I IN 1..:x_msg_count ";
 		$plsql .= "  LOOP ";
 		$plsql .= "   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);";
 		$plsql .= "  END LOOP;";
 		$plsql .= " END IF;";
 		$plsql .="END;";

 		$var['x_cust_acct_site_id'] = 12;
		$len['x_cust_acct_site_id'] = 11;
		$var['x_return_status'] = "";
		$len['x_return_status'] = 2000;
		$var['x_msg_count'] = 0;
		$len['x_msg_count'] = 11;
		$var['x_msg_data'] = "";
		$len['x_msg_data'] = 2000;

		$this->run($plsql, $var, $len, OCI_DEFAULT);

		if ($var['x_return_status'] !== 'S') {
//			echo ("<p>Query : $plsql<br>");
//			echo ($var['x_msg_count']." error(s) : ".$var['x_msg_data']."<P>");
//			print_r($var);
         $this->errorAlert("CreateCustAcctSite($cust_account_id, $party_site_id) returned with status : ".$var['x_return_status']." and ".$var['x_msg_count']." message(s)\n".$var['x_msg_data']);
			$this->rollback();
			return false;
		}
		return $var;

	}

	/**
	* CreateCustAcctSiteUse()
	*
	* @param
	* @todo NOT YET COMPLETED
	* @return
	* @since  - Thu Apr 05 12:23:36 PDT 2007
	*/
	function CreateCustAcctSiteUse($cust_acct_site_id, $location, $use, $bill_to_site_use_id = 0)
	{
		$plsql = "DECLARE ";
 		$plsql .= " p_cust_site_use_rec HZ_CUST_ACCOUNT_SITE_V2PUB.CUST_SITE_USE_REC_TYPE;";
 		$plsql .= " p_customer_profile_rec HZ_CUSTOMER_PROFILE_V2PUB.CUSTOMER_PROFILE_REC_TYPE;";
		$plsql .= " BEGIN ";
 		$plsql .= " p_cust_site_use_rec.cust_acct_site_id := $cust_acct_site_id;";
 		$plsql .= " p_cust_site_use_rec.location := '".strtoupper($location)."';";
 		if ($bill_to_site_use_id != 0) {
 			$plsql .= " p_cust_site_use_rec.bill_to_site_use_id := $bill_to_site_use_id;";
 		}
 		$plsql .= " p_cust_site_use_rec.primary_flag := 'Y';";
 		$plsql .= " p_cust_site_use_rec.site_use_code := '$use';";
 		$plsql .= " p_cust_site_use_rec.created_by_module := 'HUMMINGBIRD';";
 		$plsql .= " hz_cust_account_site_v2pub.create_cust_site_use(";
 		$plsql .= " 'T',";
 		$plsql .= " p_cust_site_use_rec,";
 		$plsql .= " p_customer_profile_rec,";
 		$plsql .= " 'F',";
 		$plsql .= " 'F',";
 		$plsql .= " :x_site_use_id,";
 		$plsql .= " :x_return_status,";
 		$plsql .= " :x_msg_count,";
 		$plsql .= " :x_msg_data);";
 		$plsql .= " IF :x_msg_count > 1 THEN ";
 		$plsql .= "  FOR I IN 1..:x_msg_count ";
 		$plsql .= "  LOOP ";
 		$plsql .= "   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);";
 		$plsql .= "  END LOOP;";
 		$plsql .= " END IF;";
		$plsql .= "END;";

		$var['x_site_use_id'] = 0;
		$len['x_site_use_id'] = 11;
		$var['x_return_status'] = "";
		$len['x_return_status'] = 2000;
		$var['x_msg_count'] = 0;
		$len['x_msg_count'] = 11;
		$var['x_msg_data'] = "";
		$len['x_msg_data'] = 2000;

		$this->run($plsql, $var, $len, OCI_DEFAULT);

		return $var;
	}


	/**
	* CreateCustAcctSiteUseBillTo()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 16:03:14 PST 2005
	*/
	function CreateCustAcctSiteUseBillTo($cust_acct_site_id, $location)
	{
		$var = $this->CreateCustAcctSiteUse($cust_acct_site_id, $location, 'BILL_TO');
		if ($var['x_return_status'] !== 'S') {
//			echo ("<p>Query : $plsql<br>");
//			echo ($var['x_msg_count']." error(s) : ".$var['x_msg_data']."<P>");
//			print_r($var);
         $this->errorAlert("CreateCustSiteUseBillTo($cust_acct_site_id, $location) returned with status : ".$var['x_return_status']." and ".$var['x_msg_count']." message(s)\n".$var['x_msg_data']);
			$this->rollback();
			return false;
		}
		return $var;
	}

	/**
	* CreateCustAcctSiteUseShipTo()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 16:07:37 PST 2005
	*/
	function CreateCustAcctSiteUseShipTo($cust_acct_site_id, $location, $bill_to_site_use_id)
	{
		$var = $this->CreateCustAcctSiteUse($cust_acct_site_id, $location, 'SHIP_TO', $bill_to_site_use_id);
		if ($var['x_return_status'] !== 'S') {
//			echo ("<p>Query : $plsql<br>");
//			echo ($var['x_msg_count']." error(s) : ".$var['x_msg_data']."<P>");
//			print_r($var);
         $this->errorAlert("CreateCustSiteUseShipTo($cust_acct_site_id, $location, $bill_to_site_use_id) returned with status : ".$var['x_return_status']." and ".$var['x_msg_count']." message(s)\n".$var['x_msg_data']);
			$this->rollback();
			return false;
		}
		return $var;
	}

	/**
	* CreatePerson()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 16:17:35 PST 2005
	*/
	function CreatePerson($person)
	{
		$plsql = "DECLARE ";
 		$plsql .= " p_create_person_rec HZ_PARTY_V2PUB.person_rec_type;";
 		$plsql .= " x_party_number VARCHAR2(2000);";
 		$plsql .= " x_profile_id NUMBER;";
		$plsql .= "BEGIN";
 		//$plsql .= " p_create_person_rec.salutation := '".$person['salutation']."';";
 		$plsql .= " p_create_person_rec.person_first_name := '".strtoupper($person['first_name'])."';";
 		$plsql .= " p_create_person_rec.person_last_name := '".strtoupper($person['last_name'])."';";
 		$plsql .= " p_create_person_rec.created_by_module := 'HUMMINGBIRD';";
 		$plsql .= " HZ_PARTY_V2PUB.create_person(";
 		$plsql .= " 'T',";
 		$plsql .= " p_create_person_rec	,";
 		$plsql .= " :x_party_id,";
 		$plsql .= " x_party_number,";
 		$plsql .= " x_profile_id,";
 		$plsql .= " :x_return_status,";
 		$plsql .= " :x_msg_count,";
 		$plsql .= " :x_msg_data);";
 		$plsql .= " IF :x_msg_count > 1 THEN ";
 		$plsql .= "  FOR I IN 1..:x_msg_count ";
 		$plsql .= "  LOOP ";
 		$plsql .= "   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);";
 		$plsql .= "  END LOOP;";
 		$plsql .= " END IF;";
		$plsql .= "END;";

 		$var['x_party_id'] = 0;
 		$len['x_party_id'] = 11;
		$var['x_return_status'] = "";
		$len['x_return_status'] = 2000;
		$var['x_msg_count'] = 0;
		$len['x_msg_count'] = 11;
		$var['x_msg_data'] = "";
		$len['x_msg_data'] = 2000;

 		$this->run($plsql, $var, $len, OCI_DEFAULT);
		if ($var['x_return_status'] !== 'S') {
//			echo ("<p>Query : $plsql<br>");
//			echo ($var['x_msg_count']." error(s) : ".$var['x_msg_data']."<P>");
//			print_r($var);
         $this->errorAlert("CreatePerson(".$person['first_name']." ".$person['last_name']." returned with status : ".$var['x_return_status']." and ".$var['x_msg_count']." message(s)\n".$var['x_msg_data']);
			$this->rollback();
			return false;
		}
		return $var;
	}

	/**
	* CreateOrgContact()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 16:23:16 PST 2005
	*/
	function CreateOrgContact($contact_party_id, $party_id)
	{
 		$plsql = "DECLARE ";
		$plsql .= " p_org_contact_rec HZ_PARTY_CONTACT_V2PUB.ORG_CONTACT_REC_TYPE;";
		$plsql .= " x_org_contact_id NUMBER;";
		$plsql .= " x_party_rel_id NUMBER;";
		$plsql .= " x_party_number VARCHAR2(2000);";
		$plsql .= "BEGIN";
		$plsql .= " p_org_contact_rec.created_by_module := 'HUMMINGBIRD';";
		$plsql .= " p_org_contact_rec.party_rel_rec.subject_id := $contact_party_id;";
		$plsql .= " p_org_contact_rec.party_rel_rec.subject_type := 'PERSON';";
		$plsql .= " p_org_contact_rec.party_rel_rec.subject_table_name := 'HZ_PARTIES';";
		$plsql .= " p_org_contact_rec.party_rel_rec.object_id := $party_id;";
		$plsql .= " p_org_contact_rec.party_rel_rec.object_type := 'ORGANIZATION';";
		$plsql .= " p_org_contact_rec.party_rel_rec.object_table_name := 'HZ_PARTIES';";
		$plsql .= " p_org_contact_rec.party_rel_rec.relationship_code := 'CONTACT_OF';";
		$plsql .= " p_org_contact_rec.party_rel_rec.relationship_type := 'CONTACT';";
		$plsql .= " hz_party_contact_v2pub.create_org_contact(";
		$plsql .= " 'T',";
		$plsql .= " p_org_contact_rec,";
		$plsql .= " x_org_contact_id,";
		$plsql .= " x_party_rel_id,";
		$plsql .= " :x_party_id,";
		$plsql .= " x_party_number,";
		$plsql .= " :x_return_status,";
		$plsql .= " :x_msg_count,";
		$plsql .= " :x_msg_data);";
 		$plsql .= " IF :x_msg_count > 1 THEN ";
 		$plsql .= "  FOR I IN 1..:x_msg_count ";
 		$plsql .= "  LOOP ";
 		$plsql .= "   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);";
 		$plsql .= "  END LOOP;";
 		$plsql .= " END IF;";
		$plsql .= "END;";

		$var['x_party_id'] = 0;
		$len['x_party_id'] = 11;
		$var['x_return_status'] = "";
		$len['x_return_status'] = 2000;
		$var['x_msg_count'] = 0;
		$len['x_msg_count'] = 11;
		$var['x_msg_data'] = "";
		$len['x_msg_data'] = 2000;

		$this->run($plsql, $var, $len, OCI_DEFAULT);

		if ($var['x_return_status'] !== 'S') {
//			echo ("<p>Query : $plsql<br>");
//			echo ($var['x_msg_count']." error(s) : ".$var['x_msg_data']."<P>");
//			print_r($var);
         $this->errorAlert("CreateOrgContact($contact_party_id, $party_id) returned with status : ".$var['x_return_status']." and ".$var['x_msg_count']." message(s)\n".$var['x_msg_data']);
			$this->rollback();
			return false;
		}
		return $var;
	}

	/**
	* CreateCustAccountRole()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 16:27:18 PST 2005
	*/
	function CreateCustAccountRole($org_contact_party_id, $cust_account_id, $cust_acct_site_id, $primary)
	{
		$plsql = "DECLARE ";
		$plsql .= " p_cr_cust_acc_role_rec HZ_CUST_ACCOUNT_ROLE_V2PUB.cust_account_role_rec_type;";
		$plsql .= "BEGIN";
		$plsql .= " p_cr_cust_acc_role_rec.party_id := $org_contact_party_id;";
		$plsql .= " p_cr_cust_acc_role_rec.cust_account_id := $cust_account_id;";
		$plsql .= " p_cr_cust_acc_role_rec.cust_acct_site_id := $cust_acct_site_id;";
		$plsql .= " p_cr_cust_acc_role_rec.primary_flag := '$primary';";
		$plsql .= " p_cr_cust_acc_role_rec.role_type := 'CONTACT';";
		$plsql .= " p_cr_cust_acc_role_rec.created_by_module := 'HUMMINGBIRD';";
		$plsql .= " HZ_CUST_ACCOUNT_ROLE_V2PUB.create_cust_account_role(";
		$plsql .= " 'T',";
		$plsql .= " p_cr_cust_acc_role_rec,";
		$plsql .= " :x_cust_account_role_id,";
		$plsql .= " :x_return_status,";
		$plsql .= " :x_msg_count,";
		$plsql .= " :x_msg_data);";
 		$plsql .= " IF :x_msg_count > 1 THEN ";
 		$plsql .= "  FOR I IN 1..:x_msg_count ";
 		$plsql .= "  LOOP ";
 		$plsql .= "   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);";
 		$plsql .= "  END LOOP;";
 		$plsql .= " END IF;";
		$plsql .= "END;";

		$var['x_cust_account_role_id'] = 0;
		$len['x_cust_account_role_id'] = 11;
		$var['x_return_status'] = "";
		$len['x_return_status'] = 2000;
		$var['x_msg_count'] = 0;
		$len['x_msg_count'] = 11;
		$var['x_msg_data'] = "";
		$len['x_msg_data'] = 2000;

		$this->run($plsql, $var, $len, OCI_DEFAULT);

		if ($var['x_return_status'] !== 'S') {
//			echo ("<p>Query : $plsql<br>");
//			echo ($var['x_msg_count']." error(s) : ".$var['x_msg_data']."<P>");
//			print_r($var);
         $this->errorAlert("CreateCustAccountRole($org_contact_party_id, $cust_account_id, $cust_acct_site_id, $primary) returned with status : ".$var['x_return_status']." and ".$var['x_msg_count']." message(s)\n".$var['x_msg_data']);
			$this->rollback();
			return false;
		}
		return $var;
	}

	/**
	* CreatePhoneContactPoint()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 16:29:54 PST 2005
	*/
	function CreatePhoneContactPoint($org_contact_party_id, $number, $type)
	{
		$plsql = "DECLARE "
		." p_contact_point_rec HZ_CONTACT_POINT_V2PUB.CONTACT_POINT_REC_TYPE;"
		." p_phone_rec HZ_CONTACT_POINT_V2PUB.phone_rec_type;"
		."BEGIN"
		." p_contact_point_rec.contact_point_type := 'PHONE';"
		." p_contact_point_rec.owner_table_name := 'HZ_PARTIES';"
		." p_contact_point_rec.owner_table_id := $org_contact_party_id;"
		." p_contact_point_rec.created_by_module := 'HUMMINGBIRD';"
		." p_phone_rec.Phone_number := '$number';"
		." p_phone_rec.phone_line_type := '$type';"
		." HZ_CONTACT_POINT_V2PUB.create_phone_contact_point ("
		." 'T',"
		." p_contact_point_rec,"
		." p_phone_rec,"
		." :x_contact_point_id,"
		." :x_return_status,"
		." :x_msg_count,"
		." :x_msg_data);"
 		." IF :x_msg_count > 1 THEN "
 		."  FOR I IN 1..:x_msg_count "
 		."  LOOP "
 		."   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);"
 		."  END LOOP;"
 		." END IF;"
		."END;";

		$var['x_contact_point_id'] = 0;
		$len['x_contact_point_id'] = 11;
		$var['x_return_status'] = "";
		$len['x_return_status'] = 2000;
		$var['x_msg_count'] = 0;
		$len['x_msg_count'] = 11;
		$var['x_msg_data'] = "";
		$len['x_msg_data'] = 2000;

		$this->run($plsql, $var, $len, OCI_DEFAULT);

		if ($var['x_return_status'] !== 'S') {
//			echo ("<p>Query : $plsql<br>");
//			echo ($var['x_msg_count']." error(s) : ".$var['x_msg_data']."<P>");
//			print_r($var);
         $this->errorAlert("CreatePhoneContactPoint($org_contact_party_id, $number, $type) returned with status : ".$var['x_return_status']." and ".$var['x_msg_count']." message(s)\n".$var['x_msg_data']);
			$this->rollback();
			return false;
		}
		return $var;
	}

	/**
	* CreateEmailContactPoint()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 16:33:37 PST 2005
	*/
	function CreateEmailContactPoint($org_contact_party_id, $email)
	{
		$plsql = "DECLARE "
		." p_contact_point_rec HZ_CONTACT_POINT_V2PUB.CONTACT_POINT_REC_TYPE;"
		." p_email_rec HZ_CONTACT_POINT_V2PUB.email_rec_type;"
		."BEGIN"
		." p_contact_point_rec.contact_point_type := 'EMAIL';"
		." p_contact_point_rec.owner_table_name := 'HZ_PARTIES';"
		." p_contact_point_rec.owner_table_id := $org_contact_party_id;"
		." p_contact_point_rec.created_by_module := 'HUMMINGBIRD';"
		." p_email_rec.email_address := '".strtoupper($email)."';"
		." HZ_CONTACT_POINT_V2PUB.create_email_contact_point ("
		." 'T',"
		." p_contact_point_rec,"
		." p_email_rec,"
		." :x_contact_point_id,"
		." :x_return_status,"
		." :x_msg_count,"
		." :x_msg_data);"
 		." IF :x_msg_count > 1 THEN "
 		."  FOR I IN 1..:x_msg_count "
 		."  LOOP "
 		."   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);"
 		."  END LOOP;"
 		." END IF;"
		."END;";

		$var['x_contact_point_id'] = 0;
		$len['x_contact_point_id'] = 11;
		$var['x_return_status'] = "";
		$len['x_return_status'] = 2000;
		$var['x_msg_count'] = 0;
		$len['x_msg_count'] = 11;
		$var['x_msg_data'] = "";
		$len['x_msg_data'] = 2000;

		$this->run($plsql, $var, $len, OCI_DEFAULT);

		if ($var['x_return_status'] !== 'S') {
//			echo ("<p>Query : $plsql<br>");
//			echo ($var['x_msg_count']." error(s) : ".$var['x_msg_data']."<P>");
//			print_r($var);
         $this->errorAlert("CreateEmailContactPoint($org_contact_party_id, $email) returned with status : ".$var['x_return_status']." and ".$var['x_msg_count']." message(s)\n".$var['x_msg_data']);
			$this->rollback();
			return false;
		}
		return $var;
	}

	/**
	* CreateRoleResponsibility()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 16:35:50 PST 2005
	*/
	function CreateRoleResponsibility($cust_account_role_id, $type)
	{
		$plsql = "DECLARE "
		." p_role_responsibility_rec HZ_CUST_ACCOUNT_ROLE_V2PUB.ROLE_RESPONSIBILITY_REC_TYPE;"
		."BEGIN"
		." p_role_responsibility_rec.cust_account_role_id := $cust_account_role_id;"
		." p_role_responsibility_rec.responsibility_type := '$type';"
		." p_role_responsibility_rec.created_by_module := 'HUMMINGBIRD';"
		." HZ_CUST_ACCOUNT_ROLE_V2PUB.create_role_responsibility ("
		." 'T',"
		." p_role_responsibility_rec,"
		." :x_responsibility_id,"
		." :x_return_status,"
		." :x_msg_count,"
		." :x_msg_data);"
		." IF :x_msg_count > 1 THEN "
 		."  FOR I IN 1..:x_msg_count "
 		."  LOOP "
 		."   :x_msg_data := :x_msg_data||'\n'||SubStr(FND_MSG_PUB.Get(p_encoded => FND_API.G_FALSE), 1, 255);"
 		."  END LOOP;"
 		." END IF;"
		."END;";

		$var['x_responsibility_id'] = 0;
		$len['x_responsibility_id'] = 11;
		$var['x_return_status'] = "";
		$len['x_return_status'] = 2000;
		$var['x_msg_count'] = 0;
		$len['x_msg_count'] = 11;
		$var['x_msg_data'] = "";
		$len['x_msg_data'] = 2000;

		$this->run($plsql, $var, $len, OCI_DEFAULT);

		if ($var['x_return_status'] !== 'S') {
//			echo ("<p>Query : $plsql<br>");
//			echo ($var['x_msg_count']." error(s) : ".$var['x_msg_data']."<P>");
//			print_r($var);
         $this->errorAlert("CreateRoleResponsibility($cust_account_role_id, $type) returned with status : ".$var['x_return_status']." and ".$var['x_msg_count']." message(s)\n".$var['x_msg_data']);
			$this->rollback();
			return false;
		}
		return $var;
	}

	/**
	* GetPartyIDByAccountID()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Dec 08 14:09:59 PST 2005
	*/
	function GetPartyIDByAccountID($account_id)
	{
		$q = "SELECT PARTY_ID FROM HZ_CUST_ACCOUNTS WHERE CUST_ACCOUNT_ID = $account_id";
		$this->parse($q);
		$this->execute();

		$data = $this->fetch_assoc();
		if ($data) {
			return $data['PARTY_ID'];
		}else{
			return false;
		}

	}

	/**
	* GetTrxErrorsCount()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Dec 22 09:22:07 PST 2005
	*/
	function GetTrxErrorsCount()
	{
		$q = "SELECT count(TRX_HEADER_ID) AS ERRORS FROM AR_TRX_ERRORS_GT";
		$this->parse($q);
		$this->execute();
		$e = $this->fetch_assoc();
		return $e['ERRORS'];
	}

	/**
	* GetTrxErrors()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Dec 22 09:27:48 PST 2005
	*/
	function GetTrxErrors()
	{
		$q = "SELECT ERROR_MESSAGE, INVALID_VALUE FROM AR_TRX_ERRORS_GT";
		$this->parse($q);
		$this->execute();
	}

	/**
	* GetLastBatchID()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Dec 22 09:30:50 PST 2005
	*/
	function GetLastBatchID()
	{
		$q = "SELECT BATCH_ID FROM AR_TRX_HEADER_GT";
		$this->parse($q);
		$this->execute();
		$b = $this->fetch_assoc();
		if ($b)
			return $b['BATCH_ID'];
		else
			return false;

	}

	/**
	* GetBatchTansactions()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Dec 22 09:35:40 PST 2005
	*/
	function GetBatchTransactions($batch_id)
	{
		$q = "SELECT CUSTOMER_TRX_ID, TRX_NUMBER, BILL_TO_CUSTOMER_ID, ATTRIBUTE1, INTERFACE_HEADER_ATTRIBUTE2 FROM RA_CUSTOMER_TRX_ALL WHERE BATCH_ID = $batch_id";
		$this->parse($q);
		$this->execute();

	}

	/**
	* GetTxnHeader()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Feb 13 12:24:16 PST 2006
	*/
	function GetTxnHeader($txn_number)
	{
	   $q =
	   "SELECT
	        CUSTOMER_TRX_ID,
	        TRX_NUMBER,
	        TRX_DATE,
	        BILL_TO_CUSTOMER_ID,
	        INVOICE_CURRENCY_CODE,
	        EXCHANGE_RATE
	   FROM RA_CUSTOMER_TRX_ALL
	   WHERE TRX_NUMBER = '$txn_number'";

	   $this->parse($q);
	   $this->execute();
	}

	/**
	* GetTxnLines()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Jan 04 09:54:54 PST 2006
	*/
	function GetTxnLines($txn_id)
	{
	   $q = "SELECT
	        L.CUSTOMER_TRX_LINE_ID,
	        L.INVENTORY_ITEM_ID,
	        L.DESCRIPTION,
	        L.INTERFACE_LINE_ATTRIBUTE1 STUDY_ID,
	        L.ATTRIBUTE1 GROUP_NAME,
	        I.SEGMENT1 ITEM_NUMBER,
	        L.QUANTITY_INVOICED,
	        L.QUANTITY_CREDITED,
	        L.UNIT_STANDARD_PRICE,
	        L.UNIT_SELLING_PRICE
	   FROM RA_CUSTOMER_TRX_LINES_ALL L
	   LEFT JOIN MTL_SYSTEM_ITEMS I ON I.INVENTORY_ITEM_ID = L.INVENTORY_ITEM_ID
	   WHERE L.CUSTOMER_TRX_ID = $txn_id AND L.LINE_TYPE='LINE'";
	   $this->parse($q);
	   $this->execute();
	}

	/**
	* GetSalesrepByName()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Sep 14 12:24:57 PDT 2006
	*/
	function GetSalesrepByName($name)
	{
	   $q = "SELECT SALESREP_ID, NAME, CREATION_DATE, START_DATE_ACTIVE, STATUS FROM JTF_RS_SALESREPS WHERE UPPER(NAME) LIKE UPPER('$name')";
	   $this->parse($q);
	   $this->execute();
		return $this->fetch_assoc();
	}

//	/**
//	* GetInvoiceHeader()
//	*
//	* @param
//	* @param -
//	* @return
//	* @throws
//	* @access
//	* @global
//	* @since  - Tue Jan 10 15:16:52 PST 2006
//	*/
//	function GetInvoiceHeader($customer_trx_id)
//	{
//      $q = "
//      SELECT
//         CT.TRX_NUMBER,
//         CT.TRX_DATE,
//         CT.BILL_TO_CUSTOMER_ID,
//         CT.BILL_TO_SITE_USE_ID,
//         CSU.CUST_ACCT_SITE_ID,
//         P.PARTY_ID,
//         CAS.PARTY_SITE_ID,
//         PS.LOCATION_ID,
//         P.PARTY_NAME BILL_TO_NAME,
//         L.ADDRESS1 BILL_TO_ADDRESS1,
//         L.ADDRESS2 BILL_TO_ADDRESS2,
//         L.CITY BILL_TO_CITY,
//         L.STATE BILL_TO_STATE,
//         L.POSTAL_CODE BILL_TO_POSTAL_CODE,
//         L.COUNTRY BILL_TO_COUNTRY,
//         CT.TERM_ID,
//         T.NAME TERM,
//         CT.ATTRIBUTE4 PROJECT_MANAGER,
//         CT.ATTRIBUTE3 JOB_NUMBER,
//         CT.ATTRIBUTE2 PO_NUMBER,
//         CT.ATTRIBUTE1 PROJECT_NAME,
//         CT.REMIT_TO_ADDRESS_ID,
//         RTA.ADDRESS1 REMIT_TO_ADDRESS1,
//         RTA.ADDRESS2 REMIT_TO_ADDRESS2,
//         RTA.CITY REMIT_TO_CITY,
//         RTA.STATE REMIT_TO_STATE,
//         RTA.POSTAL_CODE REMIT_TO_POSTAL_CODE,
//         RTA.COUNTRY REMIT_TO_COUNTRY,
//         CT.BILL_TO_CONTACT_ID,
//         C.CONTACT_NUMBER,
//         OC.PARTY_RELATIONSHIP_ID
//      FROM RA_CUSTOMER_TRX_ALL CT
//      LEFT JOIN HZ_CUST_ACCOUNTS_ALL CA ON CA.CUST_ACCOUNT_ID = CT.BILL_TO_CUSTOMER_ID
//      LEFT JOIN HZ_PARTIES P ON P.PARTY_ID = CA.PARTY_ID
//      LEFT JOIN HZ_CUST_SITE_USES_ALL CSU ON CSU.SITE_USE_ID = CT.BILL_TO_SITE_USE_ID
//      LEFT JOIN HZ_CUST_ACCT_SITES_ALL CAS ON CAS.CUST_ACCT_SITE_ID = CSU.CUST_ACCT_SITE_ID
//      LEFT JOIN HZ_PARTY_SITES PS ON PS.PARTY_SITE_ID = CAS.PARTY_SITE_ID
//      LEFT JOIN HZ_LOCATIONS L ON L.LOCATION_ID = PS.LOCATION_ID
//      LEFT JOIN RA_TERMS_TL T ON T.TERM_ID = CT.TERM_ID
//      LEFT JOIN AR_ACTIVE_REMIT_TO_ADDRESSES_V RTA ON RTA.ADDRESS_ID = CT.REMIT_TO_ADDRESS_ID
//      LEFT JOIN RA_CONTACTS C ON C.CONTACT_ID = CT.BILL_TO_CONTACT_ID AND C.CUSTOMER_ID = CT.BILL_TO_CUSTOMER_ID
//      LEFT JOIN HZ_ORG_CONTACTS OC ON OC.CONTACT_NUMBER = C.CONTACT_NUMBER
//      WHERE CT.CUSTOMER_TRX_ID = $customer_trx_id";
//
//      $this->parse($q);
//      $this->execute();
//
//      $ret = $this->fetch_assoc();
//      if (!$ret)
//         return $ret;
//
//
//      $q = "
//      SELECT
//         R.PARTY_ID,
//         R.SUBJECT_ID,
//         P.PARTY_NAME,
//         P.PERSON_FIRST_NAME,
//         P.PERSON_LAST_NAME,
//         CP.EMAIL_ADDRESS
//      FROM HZ_RELATIONSHIPS R
//      LEFT JOIN HZ_PARTIES P ON P.PARTY_ID = R.SUBJECT_ID
//      LEFT JOIN HZ_CONTACT_POINTS CP ON CP.OWNER_TABLE_ID = R.PARTY_ID AND CP.OWNER_TABLE_NAME = 'HZ_PARTIES' AND CP.CONTACT_POINT_TYPE = 'EMAIL'
//      WHERE R.RELATIONSHIP_ID = ".$ret['PARTY_RELATIONSHIP_ID']." AND R.SUBJECT_TYPE='PERSON'";
//      $this->parse($q);
//      $this->execute();
//      $ret1 = $this->fetch_assoc();
//      if (!$ret1)
//         return $ret1;
//
//      return array_merge($ret, $ret1);
//	}
//
//	/**
//	* GetInvoiceLines()
//	*
//	* @param
//	* @param -
//	* @return
//	* @throws
//	* @access
//	* @global
//	* @since  - Tue Jan 10 16:55:01 PST 2006
//	*/
//	function GetInvoiceLines($customer_trx_id)
//	{
//	   $ret = array();
//      $q = "
//      SELECT
//         DESCRIPTION,
//         QUANTITY_INVOICED,
//         UNIT_SELLING_PRICE,
//         REVENUE_AMOUNT,
//         QUANTITY_INVOICED*UNIT_SELLING_PRICE VALUE,
//         ATTRIBUTE1 GROUP_NAME,
//         INTERFACE_LINE_ATTRIBUTE1 STUDY_ID
//      FROM RA_CUSTOMER_TRX_LINES_ALL WHERE CUSTOMER_TRX_ID = $customer_trx_id";
//
//      $this->parse($q);
//      $this->execute();
//      while ($line = $this->fetch_assoc()) {
//         $ret[] = $line;
//      }
//      return $ret;
//	}
}
?>
