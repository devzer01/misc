<?php

class workloadMetricDB extends dbConnect {

   var $timezone = '';
   var $_datasource = 0;
   var $_template_id = 0;

   /**
   * workloadMetricDB()
   *
   * Class Constructor
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function workloadMetricDB($timezone="+00:00")
   {
      $this->timezone = $timezone;
      parent::dbConnect();
   }

   /**
   *
   *
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function CreateReport(&$wlm_report_id, $login)
   {
      $qry  = "INSERT INTO wlm_report "
            . "(login, created_by, created_date) "
            . "VALUES ($login, {$this->created_by}, NOW()) ";
      $this->executeQuery($qry);
      $wlm_report_id = mysql_insert_id();
   }


   /**
   *
   *
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function SetReportComment($wlm_report_comment_id, $wlm_report_id, $wlm_report_comment_type_id, $comment)
   {
      $comment = mysql_real_escape_string($comment);
      $wlm_report_comment_id = sprintf('%0d', $wlm_report_comment_id);
      $wlm_report_id = sprintf('%0d', $wlm_report_id);
      $wlm_report_comment_type_id = sprintf('%0d', $wlm_report_comment_type_id);

      $qry  = "UPDATE wlm_report_comment "
            . "SET wlm_report_comment_type_id=$wlm_report_comment_type_id, comment='$comment', status='A', modified_by={$this->created_by}, modified_date=NOW() "
            . "WHERE wlm_report_comment_id=$wlm_report_comment_id ";

      $this->executeQuery($qry);
   }


   /**
   *
   *
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function AddReportComment(&$wlm_report_comment_id, $wlm_report_id, $wlm_report_comment_type_id, $comment)
   {
      $comment = mysql_real_escape_string($comment);
      $wlm_report_id = sprintf('%0d', $wlm_report_id);
      $wlm_report_comment_type_id = sprintf('%0d', $wlm_report_comment_type_id);

      $qry = "INSERT INTO wlm_report_comment "
           . "(wlm_report_id, wlm_report_comment_type_id, comment, created_by, created_date) "
           . "VALUES ($wlm_report_id, $wlm_report_comment_type_id, '$comment', {$this->created_by}, NOW()) ";
      $this->executeQuery($qry);
      $wlm_report_comment_id = mysql_insert_id();
   }


   /**
   *
   *
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetReportInfo(&$info, $wlm_report_id)
   {
      $wlm_report_id = sprintf('%0d', $wlm_report_id);
      $qry  = "SELECT wr.wlm_report_id, "
            . "  wr.login, "
            . "  u.first_name, "
            . "  u.last_name, "
            . "  DATE_FORMAT(CONVERT_TZ(wr.created_date,'+00:00','$this->timezone'),'%Y-%m-%d %h:%i %p') AS created_date "
            . "FROM wlm_report AS wr "
            . "  LEFT OUTER JOIN user AS u ON u.login = wr.login "
            . "WHERE wlm_report_id=$wlm_report_id ";

      $rs = $this->executeQuery($qry);
      $info = mysql_fetch_assoc($rs);
   }




   /**
   *
   *
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetReportsByLogin(&$reports, $employee_ids)
   {
      if (!is_array($employee_ids)){
         $employee_ids = array($employee_ids);
      }

      $employee_list = "-1,"; // add -1 as stuffer; if $mployee_ids == array(), $emploee_list will equal "" and sql IN operator don't like it!
      foreach ($employee_ids as $employee_id){
         $employee_list .= sprintf("'%0d',", $employee_id);
      }
      $employee_list = preg_replace("/,$/", "", $employee_list);

      $qry  = "SELECT wr.wlm_report_id, "
            . "  wr.login, "
            . "  UNIX_TIMESTAMP(CONVERT_TZ(wr.created_date,'+00:00','$this->timezone')) AS created_timestamp "
            . "FROM wlm_report AS wr "
            . "WHERE wr.login IN ($employee_list) ";

      $rs = $this->executeQuery($qry);
      $reports = array();
      while ($r = mysql_fetch_assoc($rs)){
         $wlm_report_id = $r['wlm_report_id'];
         $reports[$wlm_report_id] = $r;
      }
   }

   /**
   *
   *
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetReportsByLoginInPeriod(&$reports, $employee_ids, $report_start_time, $report_end_time, $order_by="")
   {
      if (!is_array($employee_ids)){
         $employee_ids = array($employee_ids);
      }

      $employee_list = "-1,"; // add -1 as stuffer; if $mployee_ids == array(), $emploee_list will equal "" and sql IN operator don't like it!
      foreach ($employee_ids as $employee_id){
         $employee_list .= sprintf("'%0d',", $employee_id);
      }
      $employee_list = preg_replace("/,$/", "", $employee_list);

      $qry  = "SELECT wr.wlm_report_id, "
            . "  wr.login, "
            . "  DATE_FORMAT(CONVERT_TZ(wr.created_date,'+00:00','$this->timezone'),'%Y-%m-%d %h:%i %p') AS created_date, "
            . "  wrc.comment, "
            . "  COUNT(wri_netmr_study.wlm_report_item_id) AS total_netmr_studies, "
            . "  SUM(wri_netmr_study.item_score) AS total_score, "
            . "  u.first_name, "
            . "  u.last_name "
            . "FROM wlm_report AS wr "
            . "  LEFT OUTER JOIN wlm_report_comment AS wrc ON wrc.wlm_report_id = wr.wlm_report_id "
            . "  LEFT OUTER JOIN wlm_report_item AS wri_netmr_study ON wri_netmr_study.wlm_report_id = wr.wlm_report_id AND wri_netmr_study.wlm_report_item_type_id = 1 "
            . "  LEFT OUTER JOIN user AS u ON u.login = wr.login "
            . "WHERE wr.login IN ($employee_list) "
            . "  AND CONVERT_TZ('{$report_start_time}','{$this->timezone}','+00:00') <= wr.created_date "
            . "  AND wr.created_date <= CONVERT_TZ('{$report_end_time}','{$this->timezone}','+00:00') "
            . "GROUP BY wlm_report_id ";

      if ($order_by){
         $qry .= "ORDER BY $order_by ";
      }

      $rs = $this->executeQuery($qry);
      $reports = array();
      while ($r = mysql_fetch_assoc($rs)){
         $wlm_report_id = $r['wlm_report_id'];
         $reports[$wlm_report_id] = $r;
      }
   }


   /**
   * GetReportComments()
   * Retrieve comments of a report
   *
   * @param array $comments [out]
   * @param integer $wlm_report_id [in]
   * @param integer $wlm_report_comment_type_id [in] Type of the comments to be retireved. Pass in "" for any type of comments
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetReportComments(&$comments, $wlm_report_id, $wlm_report_comment_type_id="")
   {
      $wlm_report_id = sprintf('%0d', $wlm_report_id);

      $qry  = "SELECT wrc.wlm_report_comment_id,  "
            . "  wrc.comment, "
            . "  DATE_FORMAT(CONVERT_TZ(wrc.created_date,'+00:00','$this->timezone'),'%Y-%m-%d %h:%i %p') AS created_date, "
            . "  DATE_FORMAT(CONVERT_TZ(IF(wrc.modified_date, wrc.modified_date, wrc.created_date),'+00:00','$this->timezone'),'%Y-%m-%d %h:%i %p') AS modified_date, "
            . "  u.first_name, "
            . "  u.last_name "
            . "FROM wlm_report_comment AS wrc "
            . "  LEFT OUTER JOIN user AS u ON u.login = wrc.created_by "
            . "WHERE wrc.wlm_report_id=$wlm_report_id ";

      if ($wlm_report_comment_type_id){
         $wlm_report_comment_type_id = sprintf('%0d', $wlm_report_comment_type_id);
         $qry .= " AND wrc.wlm_report_comment_type_id=$wlm_report_comment_type_id ";
      }

      $rs = $this->executeQuery($qry);
      $comments = array();
      while ($r = mysql_fetch_assoc($rs)){
         $wlm_report_comment_id = $r['wlm_report_comment_id'];
         $comments[$wlm_report_comment_id] = $r;
      }
   }


   /**
   * GetReportItems()
   * Retrieve all the items of a report
   *
   * @param array $report_items [out]
   * @param integer $wlm_report_id [in]
   * @param itneger $wlm_report_item_type_id [in] Type of the items to be retrieved.  Pass in "" for any type of items
   * @return
   * @throws
   * @access
   * @global
   */
   function GetReportItems(&$report_items, $wlm_report_id, $wlm_report_item_type_id="")
   {
      $wlm_report_id = sprintf('%0d', $wlm_report_id);
      $qry  = "SELECT wri.wlm_report_item_id, "
            . "  wri.wlm_report_item_type_id, "
            . "  wri.item_score, "
            . "  wri.item_description, "
            . "  wri.reference_number, "
            . "  writ.wlm_report_item_type_description "
            . "FROM wlm_report_item AS wri "
            . "  LEFT OUTER JOIN wlm_report_item_type AS writ ON writ.wlm_report_item_type_id = wri.wlm_report_item_type_id "
            . "WHERE wri.wlm_report_id=$wlm_report_id ";

      if ($wlm_report_item_type_id){
         $wlm_report_item_type_id = sprintf('%0d', $wlm_report_item_type_id);
         $qry .= "  AND wri.wlm_report_item_type_id=$wlm_report_item_type_id ";
      }

      $qry .= " ORDER BY wri.item_score DESC ";
      $rs = $this->executeQuery($qry);
      $report_items = array();
       while ($r = mysql_fetch_assoc($rs)){
         $wlm_report_item_id = $r['wlm_report_item_id'];
         $report_items[$wlm_report_item_id] = $r;
      }
   }

   /**
   * DeleteAllReportItems()
   * Delete all items belongs to a report
   *
   * @param integer $wlm_report_id [in] ID of the report
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function DeleteAllReportItems($wlm_report_id)
   {
      $wlm_report_id = sprintf('%0d', $wlm_report_id);
      $qry = "DELETE FROM wlm_report_item WHERE wlm_report_id=$wlm_report_id ";
      $this->executeQuery($qry);
   }

   /**
   * AddReportItem()
   * Add a new item to a report
   *
   * @param integer $wlm_report_item_id [out]
   * @param integer $wlm_report_id [in]
   * @param integer $wlm_report_item_type_id [in]
   * @param string $reference_number [in]
   * @param string $item_description [in]
   * @param float $item_score [in]
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function AddReportItem(&$wlm_report_item_id, $wlm_report_id, $wlm_report_item_type_id, $reference_number, $item_description, $item_score)
   {
      $wlm_report_id = sprintf('%0d', $wlm_report_id);
      $wlm_report_comment_type_id = sprintf('%0d', $wlm_report_comment_type_id);
      $reference_number = mysql_real_escape_string($reference_number);
      $item_description = mysql_real_escape_string($item_description);
      $item_score = sprintf('%0.3f', $item_score);

      $qry = "INSERT INTO wlm_report_item "
           . "(wlm_report_id, wlm_report_item_type_id, reference_number, item_description, item_score, created_by, created_date) "
           . "VALUES ($wlm_report_id, '$wlm_report_item_type_id', '$reference_number', '$item_description', $item_score, {$this->created_by}, NOW()) ";
      $this->executeQuery($qry);
      $wlm_report_item_id = mysql_insert_id();
   }













}

?>