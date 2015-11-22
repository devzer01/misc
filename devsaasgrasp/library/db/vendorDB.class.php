<?php
//db Class for entity type vendor
class vendorDB extends dbConnect 
{
   
   /**
   * vendorDB()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function vendorDB()
   {
      parent::dbConnect();
   }
   
   /**
   * GetVendorList()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetVendorList($vendor_type)
   {
      $qry  = "SELECT vendor_id, vendor_name, vendor_country_code ";
      $qry .= "FROM vendor ";
      $qry .= "WHERE vendor_type_id = ".$vendor_type." AND status = 'A'";
      
      return $this->executeQuery($qry);
   }
}