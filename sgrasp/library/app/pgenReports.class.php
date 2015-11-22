<?php

class app_pgenReports extends app_Pgen
{
	private $__year       = 0;

	private $__year_month = 0;

	private $__date       = 0;

	private $__date_range =  array();

	private $__list = array();

	private $__filter = '';

	private $__first_key  = '';

	private $__second_key = '';

	private $__data_denominator = 1;

	private $__use_custom_denominator = true;

	/**
	* __construct()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Apr 17 14:54:17 PDT 2006
	*/
	function __construct()
	{
		parent::__construct();

		$this->__date       = date("Y-m-d");
		$this->__year_month = date("Y-m");
		$this->__year       = date("Y");
	}

	/**
	* SetDateRange()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 17:58:17
	*/
	function SetDateRange()
	{
		/** TODO need a way to generate these ranges rather than defining them */
		/* standard date range for tabular */
		$this->__date_range['tabular'] = array(
		'mtd' => array(
		'start_date' => $this->__year_month . '-01 00:00:00',
		'end_date'   => date("Y-m-d 23:59:59"),
		'label'      => 'Month To Date',
		'index'      => 2),
		'p7' => array(
		'start_date' => date("Y-m-d 00:00:00", strtotime("00:00 -8day")),
		'end_date'   => date("Y-m-d 23:59:59"),
		'label'      => 'Past 7 Days',
		'index'      => 1),
		'p24' => array(
		'start_date' => date("Y-m-d 00:00:00", strtotime("00:00 today")),
		'end_date'   => date("Y-m-d 23:59:59"),
		'label'      => 'Current Day',
		'index'      => 0),
		'p30' => array(
		'start_date' => date("Y-m-d 00:00:00", strtotime("-30 days")),
		//'start_date' => $this->__year .'-03-01 00:00:00',
		'end_date'   => date("Y-m-d 23:59:59"),
		'label'      => 'Past 30 Days',
		'index'      => 3),
		'ytd' => array(
		'start_date' => $this->__year .'-01-01 00:00:00',
		'end_date'   => date("Y-m-d 23:59:59"),
		'label'      => 'Year To Date',
		'index'      => 4)
		);


		/* past 12 month monthly range */
		$this->__date_range['past_12_months'] = array();
		$this->__date_range['graph']['month'] = array();

		$date = date("Y-m-", strtotime("-12 month"));
		$date .= "01";
		$start = strtotime($date);

		for ($i=0; $i < 12; $i++){
			$end = strtotime("+". $i ."month", $start);
			$date = date("Y-m-d", $end);

			$range = array(
			'start_date' => $date ." 00:00:00",
			'end_date' => date("Y-m-", $end) . $this->GetLastDateOfMonth($end) . " 23:59:59",
			'index' => $i,
			'denominator' => 1,
			'color' => 'yellow',
			'label' => date("Y-M", $end)
			);

			$this->__date_range['past_12_months'][] = $range;
			$this->__date_range['graph']['month'][] = $range;
		}

		//past 7x7 days with a daily average trend
		for ($i=1; $i < 8; $i++) {
			//end date for trending
			$end   = strtotime("23:59 -". $i ."day");

			//past 7 day trend begin
			$p7_begin  = strtotime("00:00 -6day", $end);

			//past 28 day trend begin
			$p28_begin = strtotime("00:00 -27day", $end);

			//daily actual begin
			$begin = strtotime("00:00 ", $end);


			$p7d['start_date'] = date("Y-m-d H:i:s", $p7_begin);
			$p7d['end_date']   = date("Y-m-d H:i:s", $end);
			$p7d['index']      = 7 - $i;
			$p7d['label']      = 'Past 7 Day Avg Trended';
			$p7d['column']     = date("M-d", $p7_begin) .' - '. date("M-d", $end);
			$p7d['color']      = 'blue';
			$p7d['denominator']  = 5;


			$p28d['start_date'] = date("Y-m-d H:i:s", $p28_begin);
			$p28d['end_date']   = date("Y-m-d H:i:s", $end);
			$p28d['index']      = 7 - $i;
			$p28d['label']      = 'Past 28 Day Avg Trended';
			$p28d['column']     = date("M-d", $p28_begin) .' - '. date("M-d", $end);
			$p28d['color']      = 'green';
			$p28d['denominator']  = 20;


			$p7df['start_date'] = date("Y-m-d H:i:s", $p7_begin);
			$p7df['end_date']   = date("Y-m-d H:i:s", $end);
			$p7df['index']      = 7 - $i;
			$p7df['label']      = 'Past 7 Day Actual';
			$p7df['column']     = date("M-d", $p7_begin) .' - '. date("M-d", $end);
			$p7df['color']      = 'blue';
			$p7df['denominator']  = 1;


			$p28df['start_date'] = date("Y-m-d H:i:s", $p28_begin);
			$p28df['end_date']   = date("Y-m-d H:i:s", $end);
			$p28df['index']      = 7 - $i;
			$p28df['label']      = 'Past 28 Day Actual';
			$p28df['column']     = date("M-d", $p28_begin) .' - '. date("M-d", $end);
			$p28df['color']      = 'green';
			$p28df['denominator']  = 1;

			$p24h['start_date'] = date("Y-m-d H:i:s", $begin);
			$p24h['end_date']   = date("Y-m-d H:i:s", $end);
			$p24h['index']      = 7 - $i;
			$p24h['label']      = 'Past 7 Day Actual ';
			$p24h['column']     = date("M-d", $begin) .' - '. date("M-d", $end);
			$p24h['color']      = 'red';
			$p24h['denominator']  = 1;

			$this->__date_range['graph']['p7d'][]    = $p7d;
			$this->__date_range['graph']['p28d'][]   = $p28d;
			$this->__date_range['graph']['p28df'][]   = $p28df;
			$this->__date_range['graph']['p7df'][]   = $p7df;
			$this->__date_range['graph']['p24h'][]   = $p24h;

		}
	}

	/**
	* DisplayReport()
	* displays start up screen for proposal
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Apr 17 10:21:59 PDT 2006
	*/
	public function DisplayReport()
	{
		$c = new commonDB();
		$list['location'] = $this->CreateSmartyArray($c->GetLocationList(), 'location_id', 'location_description');

		//TODO: replace with db
		$list['report']   = array(
		19001 => 'Project Bookings',
		19002 => 'Proposal Volume',
		);
		global $smarty;

		$smarty->assign('list', $list);

		$this->DisplayHeader("Proposal Report", "pgen", $this->__o['action']);

		$smarty->display('app/pgen/vw_report.tpl');

		$this->DisplayFooter();
	}

	/**
	* GetReportData()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Apr 19 07:10:53 PDT 2006
	*/
	public function GetReportData()
	{
		global $smarty;

		$this->SetDateRange();

		if ($this->__o['debug'] == 1) {
			$debug = "<pre>". print_r($this->__date_range, true) . "</pre>";
			$title = ' == '. __FUNCTION__ . " - ". __LINE__ ." == ";
			$this->__DebugPrintDiv($title, $debug);
		}

		switch ($this->__o['sub_action']) {

			case 'draw_custom_graph':
				$this->__use_custom_denominator = false;
				//print(1);
				break;

			case 'draw_normalize_graph':
				$report['report_html'] = $this->__GetReportDataCustom();
				//print(2);
				break;

			default:
				$report['report_html'] = $this->__GetReportData();
				//print(3);
				break;
		}


		$meta['placement'] = $this->__o['placement'];


		$smarty->assign('report', $report);
		$smarty->assign('meta', $meta);

		header("Content-Type: text/xml");
		$smarty->display('app/pgen/xml_report.tpl');


	}

	/**
	* __GetParamsForAnalysis()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue May 16 11:57:16 PDT 2006
	*/
	function __GetParamsForAnalysis()
	{
		$denominator = 1; $field = '';
		$c = new commonDB();

		$role_id = $this->__GetMasterRoleId();

		switch ($this->__o['placement'])
		{
			case 'regional':
				$field  = ($this->__o['base_filter'] == 'user') ? 'l.region_id' : 'p.region_id';

				switch ($this->__o['normalize_by'])
				{
					case 'user':
						$de_filter  = ' AND l.region_id = '. $this->__o['primary_key'];
						$de_filter .= ($this->__o['include_team'] == 1) ? ' AND u.login IN ('. implode(',', $this->GetReportees($this->__o['user_id'])) .') ' : '';
						$denominator = $c->GetRoleUserCountByFilter($role_id, $de_filter);
						break;

					case 'account':
						$denominator = $this->__db->GetProposalAccountsByFilter(" AND l.region_id = ". $this->__o['primary_key'] . " AND pu.role_id = ". $this->__o['role_id']);
						break;

					default:
						break;
				}

				break;

			case 'office':
				$field = 'l.location_id';

				switch ($this->__o['normalize_by'])
				{
					case 'user':
						$de_filter  = ' AND u.location_id = '. $this->__o['primary_key'];
						$de_filter .= ($this->__o['include_team'] == 1) ? ' AND u.login IN ('. implode(',', $this->GetReportees($this->__o['user_id'])) .') ' : '';
						$denominator = $c->GetRoleUserCountByFilter($role_id, $de_filter);
						break;

					case 'account':
						$denominator = $this->__db->GetProposalAccountsByFilter(" AND u.location_id = ". $this->__o['primary_key'] . " AND pu.role_id = ". $this->__o['role_id']);
						break;

					default:
						break;
				}

				break;

			case 'person':
				$field = "u.login";

				switch ($this->__o['normalize_by'])
				{
					case 'account':
						$denominator = $this->__db->GetProposalAccountsByFilter(" AND pu.login = ". $this->__o['primary_key']);
						break;
				}

			case 'country':
				$field = "p.country_code";
				break;

			case 'account':
				$field = "p.account_id";
				break;

			default:

				switch ($this->__o['normalize_by'])
				{
					case 'user':
						$denominator = ($this->__o['include_team'] == 1) ? count($this->GetReportees($this->__o['user_id'])) : $denominator = $c->GetRoleUserCountByFilter($role_id);
						break;

					default:
						break;
				}

				break;

		}

		/* Normalizing Used When Calling Custom Normalize Tables */
		if ($field != '')
		$filter = " AND ". $field ." = '". $this->__o['primary_key'] ."' ";

		$this->__filter .= $filter;
		$this->__data_denominator = ($denominator > 0 && $this->__use_custom_denominator == true) ? $denominator : 1;

	}

	/**
	* __GetMasterRoleId()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue May 16 12:31:41 PDT 2006
	*/
	function __GetMasterRoleId()
	{
		$role_id = 0;

		switch ($this->__o['role_id']) {
			case ROLE_PRIMARY_ACCT_MGR:
				$role_id = ROLE_ACCOUNT_MANAGER;
				break;

			case ROLE_PRIMARY_ACCT_EXEC:
				$role_id = ROLE_ACCOUNT_EXECUTIVE;
				break;

			default:
				break;
		}

		return $role_id;

	}

	/**
	* __GetKeyAndFilter()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Apr 27 08:26:15 PDT 2006
	*/
	private function __GetKeyAndFilter()
	{
		$c = new commonDB();
		$denominator = 1;

		$role_id = $this->__GetMasterRoleId();

		switch ($this->__o['placement']) {

			case 'regional':

				//the listing for the aggregration
				$list  = $this->CreateSmartyArray($c->GetCustomRegions(), 'region_id', 'region_name');
				$key   = 'region_id';

				//if  the base filter is differnt then we need to change the group by
				if ($this->__o['base_filter'] == 'user')
				$key = 'user_region_id';

				break;

			case 'office':

				$list = $this->CreateSmartyArray($c->GetLocationList(), 'location_id', 'location_description');

				if (isset($this->__o['drill_filter']) && $this->__o['drill_filter'] != 0)
				$filter = ' AND l.region_id = '. $this->__o['drill_filter'];

				$key   = 'location_id';

				break;

			case 'person':

				$list = $this->CreateSmartyArray($c->GetUsers(), 'login', 'name');

				if (isset($this->__o['drill_filter']) && $this->__o['drill_filter'] != 0)
				$filter = ' AND u.location_id = '. $this->__o['drill_filter'];

				$key   = 'user_login';

				break;

			case 'country':

				$list = $this->CreateSmartyArray($c->GetCountries(), 'country_code', 'country_description');

				if (isset($this->__o['drill_filter']) && $this->__o['drill_filter'] != 0)
				$filter = ' AND p.region_id = '. $this->__o['drill_filter'];

				$name  = 'country_description';
				$key   = 'country_code';
				break;

			case 'account':

				$list = $this->CreateSmartyArray($this->__db->GetProposalAccounts(), 'account_id', 'account_name');

				if (isset($this->__o['drill_filter']) && $this->__o['drill_filter'] != '0') {
					if ($this->__o['base_filter'] == 'user') {
						$filter = " AND pu.login = '". $this->__o['drill_filter'] ."' ";
					} else {
						$filter = " AND p.country_code = '". $this->__o['drill_filter'] ."' ";
					}
				}



				$name  = 'account_name';
				$key   = 'account_id';
				break;

			default:

				$name  = 'global';
				$key   = 'status';

				$list  = array('A' => $this->__o['global_data_name']);

				break;
		}

		$filter .= $this->__GetLevel2FilterAndKey();

		$this->__list               = $list;
		$this->__first_key          = $key;
		$this->__filter             = $filter;

		return array('list' => $list, 'name' => $name, 'key' => $key, 'filter' => $filter, 'key_2' => $key_2, 'denominator' => $denominator);

	}

	/**
	* __GetLevel2Filters()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue May 16 12:15:25 PDT 2006
	*/
	function __GetLevel2FilterAndKey()
	{
		$filter = ''; $key_2 = '';

		if (is_array($this->__o['location_id']) && count($this->__o['location_id']) > 0)
		$filter = ' AND l.location_id IN ('. implode(',', $this->__o['location_id']) .')';
		else
		{
			//restrict the role_id to use AM by default
			if (is_numeric($this->__o['role_id']))
			$filter = ' AND pu.role_id = '. $this->__o['role_id'];
			else
			$filter = ' AND pu.role_id = 1 ';

			if (is_numeric($this->__o['user_id']))
			$filter .= ($this->__o['include_team'] == 0) ? ' AND pu.login = '. $this->__o['user_id'] : ' AND pu.login IN ('. implode(',', $this->GetReportees($this->__o['user_id'], 0)) . ') ';
		}

		/* keys for the 2nd level disaggregration */
		switch ($this->__o['disaggr_by']) {
			case 'tier_id':
				$key_2    = ', tier ';
				$filter_3_field = ' pa_tier.proposal_attr_value ';
				break;

			case 'sample_type_id':
				$key_2 = ', sample_type_id ';
				$filter_3_field = ' prst.sample_type_id ';
				break;

			default:
				break;
		}

		/* filter for 2nd level disaggregration */
		if (is_array($this->__o['disaggr_value']) && count($this->__o['disaggr_value']) > 0 && !in_array('', $this->__o['disaggr_value']))
		$filter .= ' AND '. $filter_3_field .' IN ('. implode(',',$this->__o['disaggr_value']) .') ';

		/* account specific search */
		if (is_array($this->__o['account_id']) && count($this->__o['account_id']) > 0)
		$filter .= ' AND p.account_id IN ('. implode(',', $this->__o['account_id']) .') ';


		$this->__second_key = $key_2;

		return $filter;

	}

	/**
	* __GetTabularData()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed May 10 13:55:12 PDT 2006
	*/
	private function __GetTabularData($params)
	{
		/* loop through each date ranges to get data */
		foreach ($this->__current_range as $dfk => $dfv) {

			/* get the data and do post processing */
			$report['data'] = $this->PrepareSmartyArray(
			$this->__db->$params['func_name'](
			$this->__filter,
			$dfv['start_date'],
			$dfv['end_date'],
			$this->__first_key,
			$this->__second_key),
			'auto',
			$params['custom']);

			/* assign the correct label for this data set */
			$report['label']	= (isset($dfv['column'])) ? $dfv['column'] : $dfv['label'];

			/* assign data to the main data set */
			$data[$dfv['index']] = $report;
		}

		return $data;
	}

	/**
	* __GetGraphicalData()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed May 10 14:00:34 PDT 2006
	*/
	private function __GetGraphicalData($params)
	{
		//associative array for graphing data
		$data = array();
		$tick_marks_to_fill = 1;

		switch ($this->__o['disaggr_by']) {
			case 'tier_id':
				$key2 = 'tier';
				break;

			case 'sample_type_id':
				$key2 = 'sample_type_id';
				break;

			default:
				//$m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['data'][] = $v;
				break;
		}

		//loop through graphing ranges
		foreach ($this->__date_range['graph'] as $graph_key => $dv)
		{
			if (count($this->__o['graphical_range']) > 0 && !in_array($graph_key, $this->__o['graphical_range']))
			continue;

			$tick_marks_to_fill = count($this->__date_range['graph'][$graph_key]);

			//loop through each date in graphing range
			foreach ($this->__date_range['graph'][$graph_key] as $date_range_key => $date_range)
			{

				//retrive data we need to use 2nd group by here
				$rs = $this->__db->$params['func_name'](
				$this->__filter,
				$date_range['start_date'],
				$date_range['end_date'],
				$this->__first_key,
				$this->__second_key);

				//loop through each row for data processing
				while ($r = $this->__db->FetchAssoc($rs))
				{

					foreach ($params['graphs'] as $index => $graph_attr)
					{
						/* setting the x-axis label for the data point */
						if (!isset($data[$index]['meta']['x_axis'][$date_range['index']]))
						$data[$index]['meta']['x_axis'][$date_range['index']] = date("M-d",strtotime($date_range['start_date'])) . " - " . date("d",strtotime($date_range['end_date']));

						/* name of the graph from definitions */
						$graph_name       = $graph_attr['name'];

						/* the base key used for seperation of graphs */
						$group_by_key     = $r[$this->__first_key];

						/* 2nd group by key */
						$group_by_key2    = $r[$key2];

						/* which tick mark are we drawing */
						$date_range_index = $date_range['index'];


						//if we are dealing with a bar graph then we need to see how many groups are set
						if ($graph_attr['type'] == 'bar') {

							foreach ($graph_attr['groups'] as $group_key => $group) {
								/* which data field are we maping */
								$graph_data_set   = $group['data_field'];

								/* assign to the correct field if there are custom calculation need to be made on the data set */
								$r[$group['data_field']] = ($group['is_custom_field']) ? $this->__CalculateDynamicField($group['data_field'], $group['formula'], $r) : $r[$group['data_field']];

								/* use if there is a denominator for analyzer and also date range denomingator for averaging */
								$value = ($graph_attr['use_denominator']) ? $r[ $group['data_field'] ] / $date_range['denominator'] / $this->__data_denominator : $r[ $group['data_field'] ] / $this->__data_denominator;

								/* assign the value to the master data set */
								//$data[$graph_name][$graph_key][$graph_data_set]['data'][$group_by_key][$date_range_index] = $value;

								//if we have a 2nd level dis-aggregration we need to store graphical data differntly
								switch ($this->__o['disaggr_by']) {
									case 'tier_id':
									case 'sample_type_id':
										$data[$graph_name][$graph_key][$graph_data_set]['data'][$group_by_key][$group_by_key2][$date_range_index] = $value;
										break;

									default:
										$data[$graph_name][$graph_key][$graph_data_set]['data'][$group_by_key][$date_range_index] = $value;
										break;
								}
							}

						} elseif ($graph_attr['type'] == 'combine')  {

							foreach ($graph_attr['groups'] as $group_key => $group) {
								/* which data field are we maping */
								$graph_data_set   = $group['data_field'];

								/* assign to the correct field if there are custom calculation need to be made on the data set */
								$r[$group['data_field']] = ($group['is_custom_field']) ? $this->__CalculateDynamicField($group['data_field'], $group['formula'], $r) : $r[$group['data_field']];

								/* use if there is a denominator for analyzer and also date range denomingator for averaging */
								$value = ($graph_attr['use_denominator']) ? $r[ $group['data_field'] ] / $date_range['denominator'] / $this->__data_denominator : $r[ $group['data_field'] ] / $this->__data_denominator;

								/* assign the value to the master data set */
								//$data[$graph_name][$graph_key][$graph_data_set]['data'][$group_by_key][$date_range_index] = $value;

								switch ($this->__o['disaggr_by']) {
									case 'tier_id':
									case 'sample_type_id':
										$data[$graph_name][$graph_key][$graph_data_set]['data'][$group_by_key][$group_by_key2][$date_range_index] = $value;
										break;

									default:
										$data[$graph_name][$graph_key][$graph_data_set]['data'][$group_by_key][$date_range_index] = $value;
										break;
								}
							}

							/* lines */
							foreach ($graph_attr['lines'] as $group_key => $group)
							{
								/* which data field are we maping */
								$graph_data_set   = $group['data_field'];

								/* assign to the correct field if there are custom calculation need to be made on the data set */
								$r[$group['data_field']] = ($group['is_custom_field']) ? $this->__CalculateDynamicField($group['data_field'], $group['formula'], $r) : $r[$group['data_field']];

								/* use if there is a denominator for analyzer and also date range denomingator for averaging */
								$value = ($graph_attr['use_denominator']) ? $r[ $group['data_field'] ] / $date_range['denominator'] / $this->__data_denominator : $r[ $group['data_field'] ] / $this->__data_denominator;

								/* assign the value to the master data set */
								//$data[$graph_name][$graph_key][$graph_data_set]['data'][$group_by_key][$date_range_index] = $value;

								switch ($this->__o['disaggr_by']) {
									case 'tier_id':
									case 'sample_type_id':
										$data[$graph_name][$graph_key][$graph_data_set]['data'][$group_by_key][$group_by_key2][$date_range_index] = $value;
										break;

									default:
										$data[$graph_name][$graph_key][$graph_data_set]['data'][$group_by_key][$date_range_index] = $value;
										break;
								}

							}

						}
						elseif ($graph_attr['type'] == 'line')
						{
							/* which data field are we maping */
							//$graph_data_set   = $group['data_field'];

							//regular custom field
							$r[$graph_attr['data_field']] = ($graph_attr['is_custom_field']) ? $this->__CalculateDynamicField($graph_attr['data_field'], $graph_attr['formula'], $r) : $r[$graph_attr['data_field']];

							//data arrays graph hash with the graph_name index for graph_key data set of $r data field of date_range index
							$value = ($graph_attr['use_denominator']) ? $r[ $graph_attr['data_field'] ] / $date_range['denominator'] / $this->__data_denominator : $r[ $graph_attr['data_field'] ] / $this->__data_denominator;

							//$data[ $graph_attr['name'] ][ $graph_key ][ 'data' ][ $r[ $this->__first_key ] ][ $date_range['index'] ] =
							switch ($this->__o['disaggr_by']) {
								case 'tier_id':
								case 'sample_type_id':
									$data[$graph_name][$graph_key]['data'][$group_by_key][$group_by_key2][$date_range_index] = $value;
									break;

								default:
									$data[$graph_name][$graph_key]['data'][$group_by_key][$date_range_index] = $value;
									break;
							}
						}
					}
				}
			}

			if ($this->__o['debug'] == 1)
			{
				$debug = "<pre>". print_r($data, true) . "</pre>";
				$title = ' == '. __FUNCTION__ . " - ". __LINE__ ." == ";
				$this->__DebugPrintDiv($title, $debug);
			}
			//zero fill for graph data array

			foreach ($params['graphs'] as $graph_name => $graph_attr)
			{
				if ($graph_attr['type'] == 'line') {
					if ($this->__o['disaggr_by'] == 'tier_id' || $this->__o['disaggr_by'] == 'sample_type_id') {
						foreach ($data[$graph_name][$graph_key]['data'] as $disaggr => $foo) {
							$data[$graph_name][$graph_key]['data'][$disaggr] = $this->__ZeroFillArray($data[$graph_name][$graph_key]['data'][$disaggr], $tick_marks_to_fill);
						}
					} else {
						$data[$graph_name][$graph_key]['data'] = $this->__ZeroFillArray($data[$graph_name][$graph_key]['data'], $tick_marks_to_fill);
					}
				} else {
					/* bar groups for bar graph */
					foreach ($graph_attr['groups'] as $group_key => $group) {
						if ($this->__o['disaggr_by'] == 'tier_id' || $this->__o['disaggr_by'] == 'sample_type_id') {
							foreach ($data[$graph_name][$graph_key][$group['data_field']]['data'] as $disaggr => $foo) {
								//echo "<pre>". print_r($data[$graph_name][$graph_key][$group['data_field']]['data'][$disaggr], true) ."</pre>";
								$data[$graph_name][$graph_key][$group['data_field']]['data'][$disaggr] = $this->__ZeroFillArray($data[$graph_name][$graph_key][$group['data_field']]['data'][$disaggr], $tick_marks_to_fill);
							}
						} else {
							$data[$graph_name][$graph_key][$group['data_field']]['data'] = $this->__ZeroFillArray($data[$graph_name][$graph_key][$group['data_field']]['data'], $tick_marks_to_fill);
						}
					}

					/* lines */
					foreach ($graph_attr['lines'] as $group_key => $group) {
						if ($this->__o['disaggr_by'] == 'tier_id' || $this->__o['disaggr_by'] == 'sample_type_id') {
							foreach ($data[$graph_name][$graph_key][$group['data_field']]['data'] as $disaggr => $foo) {
								//echo "<pre>". print_r($data[$graph_name][$graph_key][$group['data_field']]['data'][$disaggr], true) ."</pre>";
								$data[$graph_name][$graph_key][$group['data_field']]['data'][$disaggr] = $this->__ZeroFillArray($data[$graph_name][$graph_key][$group['data_field']]['data'][$disaggr], $tick_marks_to_fill);
							}
						} else {
							$data[$graph_name][$graph_key][$group['data_field']]['data'] = $this->__ZeroFillArray($data[$graph_name][$graph_key][$group['data_field']]['data'], $tick_marks_to_fill);
						}

					}

				}
			}
		}

		if ($this->__o['debug'] == 1)
		{
			$debug = "<pre>". print_r($data, true) . "</pre>";
			$title = ' == '. __FUNCTION__ . " - ". __LINE__ ." == ";
			$this->__DebugPrintDiv($title, $debug);
		}

		return $data;
	}

	/**
	* __SetCustomRange()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Sun Jun 04 11:54:21 PDT 2006
	*/
	private function __SetCustomRange()
	{
		$start_date_ts = strtotime($this->__o['custom_start_date']);
		$end_date_ts   = strtotime($this->__o['custom_end_date']);

		$this->__o['custom_frequency'] = ($this->__o['custom_frequency'] == 0) ? $this->__o['custom_frequency_custom'] : $this->__o['custom_frequency'];
		$this->__o['custom_frequency_y'] = ($this->__o['custom_frequency_y'] == 0) ? $this->__o['custom_frequency_y_custom'] : $this->__o['custom_frequency_y'];

		$days = ($end_date_ts - $start_date_ts) / 60 / 60 / 24;
		$loop_length = $days / $this->__o['custom_frequency'];

		/* debug code */
		if ($this->__o['debug'] == 1) {
			$debug = "Start Date ". $this->__o['custom_start_date'] ."<br>"
			. "End Date ". $this->__o['custom_end_date'] ."<br>"
			."Days ". $days ."<br>"
			."Loop ". $loop_length ."<br>";
			$title = ' == '. __FUNCTION__ . " - ". __LINE__ ." == ";
			$this->__DebugPrintDiv($title, $debug);
		}



		for ($i=0; $i < $loop_length; $i++) {

			$segment['start_date'] = date("Y-m-d 00:00:00", $start_date_ts);

			//special case for 30 days which is considered 1 month
			if ($this->__o['custom_frequency'] == 30) {
				//$seg_end_date = strtotime("+" . ($this->__o['custom_frequency_y'] - 1) . " day", $start_date_ts);
				$segment['end_date']   = date("Y-m-t 23:59:59", $start_date_ts);
				$start_date_ts = strtotime("+1 month", $start_date_ts);
			} else {
				$seg_end_date = strtotime("+" . ($this->__o['custom_frequency_y'] - 1) . " day", $start_date_ts);
				$segment['end_date']   = date("Y-m-d 23:59:59", $seg_end_date);
				$start_date_ts = strtotime("+" . $this->__o['custom_frequency'] ."day", $start_date_ts);
			}


			$segment['index']		  = $i;

			$segment['label']      = 'Past 28 Day Actual';
			$segment['column']     = date("Y-m-d", strtotime($segment['start_date'])) .' - '. date("Y-m-d", strtotime($segment['end_date']));
			$segment['color']      = 'green';
			$segment['denominator']  = $this->__o['custom_denominator'];



			$this->__date_range['custom'][] = $segment;
			$this->__date_range['graph']['custom'][] = $segment;
		}
	}

	/**
	* __SetTabularRange()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue May 16 12:18:42 PDT 2006
	*/
	function __SetTabularRange()
	{
		switch ($this->__o['tabular_range'])
		{
			case 'p7d':
				$this->__current_range = $this->__date_range['graph']['p7d'];
				break;

			case 'p24h':
				$this->__current_range = $this->__date_range['graph']['p24h'];
				break;

			case 'p28d':
				$this->__current_range = $this->__date_range['graph']['p28d'];
				break;

			case 'month':
				$this->__current_range = $this->__date_range['past_12_months'];
				break;

			case 'custom':
				$this->__SetCustomRange();
				$this->__current_range = $this->__date_range['custom'];
				break;

			default:
				$this->__current_range = $this->__date_range['tabular'];
				break;
		}
	}

	/**
	* __PrintReportDebug()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue May 16 14:33:04 PDT 2006
	*/
	function __PrintReportDebug()
	{
		$debug = "<pre>"
		. "First Key - ". $this->__first_key ."\n"
		. "Filter - ". $this->__filter ."\n"
		. "Second Key - ". $this->__second_key ."\n"
		. "Data Denominator (Analysis Only) - ". $this->__data_denominator ."\n"
		. "</pre>";
		$title = ' == '. __FUNCTION__ . " - ". __LINE__ ." == ";
		$this->__DebugPrintDiv($title, $debug);
	}

	/**
	* __GetReportData()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Apr 20 09:11:35 PDT 2006
	*/
	private function __GetReportData()
	{
		global $smarty;

		$meta = array('graphical' => 0, 'tabular' => 0, 'side_by_side' => 0, 'tier_on' => 0, 'placement' => '');

		$meta = array_merge($meta, $this->__o);
		$this->__o['graphical'] = 1;

		/* default looping array is date ranges */
		$kf                    = $this->__GetKeyAndFilter();
		$list                  = $this->__list;
		$list['disaggr']       = $this->GetDisAggrData('assoc');
		$params                = $this->__GetReportParams($this->__o['report_id']);

		$this->__SetTabularRange();

		$data['tabular'] = array();
		$data['tabular'] = $this->__GetTabularData($params);

		//data for graphical version
		if ($this->__o['graphical'] == 1) {
			$data['graph'] = array();
			$data['graph']	= $this->__GetGraphicalData($params);
		}

		if ($this->__o['debug'] == 1)
		{
			$this->__PrintReportDebug();
			//echo "<pre>". print_r($master, true) ."</pre>";
		}

		$master = $this->__ProcessReportData($data, $params);

		//$smarty->assign('data', $data);
		$smarty->assign('master', $master);
		$smarty->assign('meta',   $meta);
		$smarty->assign('list',   $list);

		return $smarty->fetch($params['template']);
	}

	/**
	* __GetReportParams()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed May 10 13:40:31 PDT 2006
	*/
	private function __GetReportParams($report_id)
	{
		/* report configurations */

		switch ($report_id) {
			case REPORT_PPM_PROJECT_BOOKING:

				$params['func_name'] = 'GetProjectBookings';
				$params['template']  = 'app/pgen/ptl_new_project_booking.tpl';

				$params['graphs']['project_count'] = array(
				'name' => 'project_count',
				'type' => 'line',
				'data_field' => 'project_count',
				'use_denominator' => true,
				'is_custom_field' => false,
				'meta' => array('title' => 'Project Count', 'x_title' => 'Time', 'y_title' => 'Count')
				);

				$params['graphs']['project_value'] = array(
				'name' => 'project_value',
				'type' => 'line',
				'data_field' => 'project_value',
				'use_denominator' => true,
				'is_custom_field' => false,
				'meta' => array('title' => 'Project Value', 'x_title' => 'Time', 'y_title' => 'Value')
				);

				$params['graphs']['conversion_time'] = array(
				'name' => 'conversion_time',
				'type' => 'line',
				'data_field' => 'conversion_time',
				'use_denominator' => false,
				'is_custom_field' => false,
				'meta' => array('title' => 'Conversion Time', 'x_title' => 'Time', 'y_title' => 'Avg Time')
				);

				break;

			case REPORT_PPM_PROPOSAL_VOLUME:

				$params['func_name'] = 'GetMonthlyTotals';
				$params['template']  = 'app/pgen/ptl_monthly_totals.tpl';

				$params['custom'] = array(
				'percent_convert_count' => ' won_project_count / project_count * 100',
				'percent_convert_value' => ' won_project_value / project_value * 100'
				);

				$params['graphs']['project_count2'] = array(
				'name' => 'project_count2',
				'type' => 'combine',
				'data_field' => 'project_count',
				'use_denominator' => true,
				'is_custom_field' => false,
				'groups' => array(
				0 => array(
				'data_field' => 'won_project_count',
				'type'       => 'bar',
				'title'      => 'Won'),
				1 => array(
				'data_field'   => 'project_count_minus_won',
				'is_custom_field' => true,
				'formula'      => ' project_count - won_project_count ',
				'type'         => 'bar',
				'title'        => 'Not Won')
				),
				'lines' => array(
				0 => array(
				'data_field' => 'win_ratio',
				'use_denominator' => true,
				'is_custom_field' => true,
				'formula' => ' won_project_count / project_count * 100',
				'type'         => 'line',
				'title' => 'Win Ratio'
				)
				),
				'meta' => array(
				'title' => 'Project Count',
				'x_title' => 'Time',
				'y_title' => 'Count',
				'y2_title' => '%')
				);

				$params['graphs']['project_value2'] = array(
				'name' => 'project_value2',
				'type' => 'combine',
				'data_field' => 'project_value',
				'use_denominator' => true,
				'is_custom_field' => false,
				'groups' => array(
				0 => array('data_field' => 'won_project_value', 'type'         => 'bar', 'title' => 'Won'),
				1 => array(
				'data_field'   => 'project_value_minus_won',
				'is_custom_field' => true,
				'formula'      => ' project_value - won_project_value ',
				'type'         => 'bar',
				'title'        => 'Not Won'
				)
				),
				'lines' => array(
				0 => array(
				'data_field' => 'win_ratio',
				'use_denominator' => true,
				'is_custom_field' => true,
				'formula' => ' won_project_value / project_value * 100',
				'type'         => 'line',
				'title'   => 'Capture Ratio'
				)
				),
				'meta' => array(
				'title' => 'Project Value',
				'x_title' => 'Time',
				'y_title' => 'Value',
				'y2_title' => '%')
				);
				/*
				$params['graphs']['project_count'] = array(
				'name' => 'project_count',
				'type' => 'bar',
				'data_field' => 'project_count',
				'use_denominator' => true,
				'is_custom_field' => false,
				'groups' => array(
				0 => array(
				'data_field'   => 'project_count_minus_won',
				'is_custom_field' => true,
				'formula'      => ' project_count - won_project_count '
				),
				1 => array('data_field' => 'won_project_count')),
				'meta' => array('title' => 'Project Count', 'x_title' => 'Time', 'y_title' => 'Count')
				);

				$params['graphs']['project_value'] = array(
				'name' => 'project_value',
				'type' => 'bar',
				'data_field' => 'project_value',
				'use_denominator' => true,
				'is_custom_field' => false,
				'groups' => array(
				0 => array(
				'data_field' => 'project_value_minus_won',
				'is_custom_field' => true,
				'formula'      => ' project_value - won_project_value '
				),
				1 => array('data_field' => 'won_project_value')),
				'meta' => array('title' => 'Project Value', 'x_title' => 'Time', 'y_title' => 'Value')
				);

				$params['graphs']['win_ratio'] = array(
				'name' => 'win_ratio',
				'type' => 'line',
				'data_field' => 'win_ratio',
				'use_denominator' => true,
				'is_custom_field' => true,
				'formula' => ' won_project_count / project_count * 100',
				'meta' => array('title' => 'Win Ratio', 'x_title' => 'Time', 'y_title' => '%')
				);

				$params['graphs']['capture_ratio'] = array(
				'name' => 'capture_ratio',
				'type' => 'line',
				'data_field' => 'capture_ratio',
				'use_denominator' => true,
				'is_custom_field' => true,
				'formula' => ' won_project_value / project_value * 100',
				'meta' => array('title' => 'Capture Ratio', 'x_title' => 'Time', 'y_title' => '%')
				); */


				break;

			default:
				break;
		}

		return $params;

	}

	/**
	* __ProcessReportData()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed May 10 13:37:42 PDT 2006
	*/
	private function __ProcessReportData($data, $params)
	{
		if ($this->__o['debug']) {
			$debug = "<pre>". print_r($data, true) ."</pre>";
			$title = ' == '. __FUNCTION__ . " - ". __LINE__ ." == ";
			$this->__DebugPrintDiv($title, $debug);
		}

		//prepare the data
		for ($i=0; $i < count($data['tabular']); $i++) {

			//specifying the label
			$label = $data['tabular'][$i]['label'];

			//not including blank data sets
			if (!is_array($data['tabular'][$i]['data'])) {
				continue;
			}

			//loop through each data row in tabular data to assign to the master data set for display
			foreach ($data['tabular'][$i]['data'] as $k => $v) {

				//set the label for each row
				$v['label']  = $label;

				$disaggr_on = false;

				/* disaggregration based on user preference */
				switch ($this->__o['disaggr_by']) {
					case 'tier_id':
						$disaggr_on = true;
						$current_2nd_level = $v['tier'];
						$m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['data'][$v['tier']][] = $v;
						break;

					case 'sample_type_id':
						$disaggr_on = true;
						$current_2nd_level = $v['sample_type_id'];
						$m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['data'][$v['sample_type_id']][] = $v;
						break;

					default:
						$m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['data'][] = $v;
						break;
				}

				if (!$disaggr_on) {
					/* setting up meta values for the data set and generation of graphs */
					if (!isset($m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['meta'])) {

						if ($this->__o['graphical'] == 1) {
							$m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['meta']['images'] = $this->__ProcessGraphData($data, $params, $i, $k);
						}

						$m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['meta']['data_key']  = $data['tabular'][$i]['data'][$k][$this->__first_key];

						$m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['formdata'] = array(
						'placement'    => $this->__o['placement'],
						'primary_key'  => $data['tabular'][$i]['data'][$k][$this->__first_key],
						'base_filter'  => $this->__o['base_filter'],
						'report_id'    => $this->__o['report_id'],
						'filter'       => $this->__filter,
						'label'        => $this->__list[$data['tabular'][$i]['data'][$k][$this->__first_key]],
						'role_id'      => $this->__o['role_id'],
						'user_id'      => $this->__o['user_id'],
						'include_team' => $this->__o['include_team']);
					}
				} else {
					if (!isset($m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['meta'][$current_2nd_level])) {

						if ($this->__o['graphical'] == 1) {
							$m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['meta'][$current_2nd_level]['images'] = $this->__ProcessGraphData($data, $params, $i, $k, $current_2nd_level);
						}

						$m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['meta'][$current_2nd_level]['data_key']  = $data['tabular'][$i]['data'][$k][$this->__first_key];

						if (!isset($m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['meta']['data_key'])) {
							$m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['meta']['data_key'] = $data['tabular'][$i]['data'][$k][$this->__first_key];
						}
						//$m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['meta'][$current_2nd_level]['data_key']  =

						if (!isset($m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['formdata'])) {
							$m[$data['tabular'][$i]['data'][$k][$this->__first_key]]['formdata'] = array(
							'placement'    => $this->__o['placement'],
							'primary_key'  => $data['tabular'][$i]['data'][$k][$this->__first_key],
							'base_filter'  => $this->__o['base_filter'],
							'report_id'    => $this->__o['report_id'],
							'filter'       => $this->__filter,
							'label'        => $this->__list[$data['tabular'][$i]['data'][$k][$this->__first_key]],
							'role_id'      => $this->__o['role_id'],
							'user_id'      => $this->__o['user_id'],
							'include_team' => $this->__o['include_team']);
						}


					}

				}
			}
		}


		return $m;

	}

	/**
	* __ProcessGraphData()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed May 10 20:47:07 PDT 2006
	*/
	private function __ProcessGraphData($data, $params, $i, $k, $second_group_level = false)
	{
		$images = array();

		//configure graphs meta data
		foreach ($params['graphs'] as $index => $graph_attr)
		{
			$x_axis = $data['graph'][$graph_attr['name']]['meta']['x_axis'];
			$data['graph'][$graph_attr['name']]['meta'] = $graph_attr['meta'];
			$data['graph'][$graph_attr['name']]['meta']['x_axis'] = $x_axis;
		}

		foreach ($data['graph'] as $grp => $gdata)
		{
			//initialize the graph_lines
			$data_set = array();
			$graph_data = array();

			if ($second_group_level == false)
			$data['graph'][$grp]['meta']['file_name'] = md5($data['graph'][$grp]['meta']['title'] . $data['tabular'][$i]['data'][$k][$this->__first_key] . time());
			else
			$data['graph'][$grp]['meta']['file_name'] = md5($data['graph'][$grp]['meta']['title'] . $data['tabular'][$i]['data'][$k][$this->__first_key] . $second_group_level . time());

			foreach ($this->__date_range['graph'] as $dk => $dv)
			{
				if (count($this->__o['graphical_range']) > 0 && !in_array($dk, $this->__o['graphical_range']))
				continue;

				if ($params['graphs'][$grp]['type'] == 'line')
				{

					$data['graph'][$grp][$dk]['label']     = $this->__date_range['graph'][$dk][0]['label'];
					$data['graph'][$grp][$dk]['color']     = $this->__date_range['graph'][$dk][0]['color'];

					$data_set         = $data['graph'][$grp][$dk];
					if ($second_group_level === false) {
						$data_set['data'] = $data['graph'][$grp][$dk]['data'][ $data['tabular'][$i]['data'][$k][$this->__first_key]];
					} else {
						$data_set['data'] = $data['graph'][$grp][$dk]['data'][ $data['tabular'][$i]['data'][$k][$this->__first_key]][$second_group_level];
					}


					if (count($data_set['data']) > 0)
					$graph_data[] = $data_set;

				}
				elseif ($params['graphs'][$grp]['type'] == 'bar')
				{
					$global = array('group_name' => $dk, 'group_key' => 'global'. $dk, 'groups' => array());

					foreach ($params['graphs'][$grp]['groups'] as $group_key => $group)
					{

						$data_set['bar_title'] = $group['data_field'];
						$data_set['bar_key']   = $group['data_field'];
						$data_set['data']      = $data['graph'][$grp][$dk][$group['data_field']]['data'][$data['tabular'][$i]['data'][$k][$this->__first_key]];

						if (count($data_set['data']) > 0)
						$global['groups'][] = $data_set;

					}

					if (count($global) > 0)
					$graph_data[] = $global;
				}
				elseif ($params['graphs'][$grp]['type'] == 'combine')
				{
					$global = array('group_name' => $dk, 'group_key' => 'global'. $dk, 'groups' => array());

					foreach ($params['graphs'][$grp]['groups'] as $group_key => $group)
					{

						$data_set['bar_title'] = $group['title'];
						$data_set['bar_key']   = $group['data_field'];
						$data_set['type']      = $group['type'];
						if ($second_group_level === false) {
							$data_set['data']      = $data['graph'][$grp][$dk][$group['data_field']]['data'][$data['tabular'][$i]['data'][$k][$this->__first_key]];
						} else {
							$data_set['data']      = $data['graph'][$grp][$dk][$group['data_field']]['data'][$data['tabular'][$i]['data'][$k][$this->__first_key]][$second_group_level];
						}

						if ($this->__o['debug']) {
							$debug = "<pre>". print_r($data_set['data'], true) . "</pre>";
							$title = ' == '. __FUNCTION__ . " - ". __LINE__ ." == ";
							$this->__DebugPrintDiv($title, $debug);
						}

						if (count($data_set['data']) > 0)
						$global['groups'][] = $data_set;

					}

					foreach ($params['graphs'][$grp]['lines'] as $group_key => $group)
					{

						$data_set['bar_title'] = $group['title'];
						$data_set['bar_key']   = $group['data_field'];
						$data_set['type']   = $group['type'];
						if ($second_group_level === false) {
							$data_set['data']      = $data['graph'][$grp][$dk][$group['data_field']]['data'][$data['tabular'][$i]['data'][$k][$this->__first_key]];
						} else {
							$data_set['data']      = $data['graph'][$grp][$dk][$group['data_field']]['data'][$data['tabular'][$i]['data'][$k][$this->__first_key]][$second_group_level];
						}

						if (count($data_set['data']) > 0)
						$global['groups'][] = $data_set;

					}

					if (count($global) > 0)
					$graph_data[] = $global;
				}

			}

			if ($this->__o['debug']) {
				$debug = "<pre>". print_r($graph_data, true) . "</pre>";
				$title = ' == '. __FUNCTION__ . " - ". __LINE__ ." == ";
				$this->__DebugPrintDiv($title, $debug);
			}

			if ($params['graphs'][$grp]['type'] == 'line')
			{
				if (count($graph_data) > 0)
				$this->__DrawLinePlot($graph_data, $data['graph'][$grp]['meta']);
				else
				$data['graph'][$grp]['meta']['file_name'] = 'no_data';
			}
			elseif ($params['graphs'][$grp]['type'] == 'bar')
			{
				if (count($graph_data) > 0)
				$this->__DrawBarPlot($graph_data, $data['graph'][$grp]['meta']);
				else
				$data['graph'][$grp]['meta']['file_name'] = 'no_data';
			}
			elseif ($params['graphs'][$grp]['type'] == 'combine')
			{
				if (count($graph_data) > 0)
				$this->__DrawCombinePlot($graph_data, $data['graph'][$grp]['meta']);
				else
				$data['graph'][$grp]['meta']['file_name'] = 'no_data';
			}

			$images[] = $data['graph'][$grp]['meta']['file_name'];

		}

		return $images;

	}

	/**
	* __ZeroFillArray()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Apr 27 08:06:22 PDT 2006
	*/
	private function __ZeroFillArray($array, $length, $start = 0)
	{

		try
		{
			//throw new Exception("Error Message")
			if (!is_array($array))
			{
				throw new Exception("Non Array Argument Passed", -200);
			}
			foreach ($array as $item => $value)
			{
				if (is_array($value))
				{
					for ($i=$start; $i<$length; $i++)
					{
						if (!isset($array[$item][$i]))
						{
							$array[$item][$i] = 0;
						}
						elseif (is_array($array[$item][$i]))
						{
							$array[$item][$i] = $this->__ZeroFillArray($array[$item][$i], $length);
						}
					}
				}
			}
		} catch (Exception $e) {
			//echo $e->getMessage();
			//print_r($array);
		}

		return $array;
	}

	/**
	* __GetNormalizedReportData()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Apr 28 07:49:56 PDT 2006
	*/
	private function __GetReportDataCustom()
	{
		$colors = array('green', 'blue', 'red', 'silver', 'black', 'orange', 'purple', 'yellow', 'white');

		global $HTTP_RAW_POST_DATA;

		$parser = new xml2Array();
		$result = $parser->parse($HTTP_RAW_POST_DATA);

		$counter = 0;

		//we can do the following with an array_merge
		$this->__o['normalize_by']       = $result['xml']['meta']['normalize_by'];
		$this->__o['role_id']            = $result['xml']['meta']['role_id'];
		$this->__o['graphical_range'][0] = $result['xml']['meta']['analysis_range'];
		$this->__o['report_id']          = $result['xml']['meta']['report_id'];
		$this->__o['debug']              = $result['xml']['meta']['debug'];

		$params = $this->__GetReportParams($this->__o['report_id']);


		foreach ($result['xml']['form'] as $form => $elements)
		{
			//we need to treat each form as a
			$this->__o = array_merge($this->__o, $elements);

			$kf    = $this->__GetKeyAndFilter();
			$list  = $kf['list'];
			$label = (isset($list[$elements['primary_key']])) ? $list[$elements['primary_key']] : $elements['label'];
			$g['color'] = ($elements['color'] == 'auto') ? $colors[$counter % count($colors)] : $elements['color'];

			$this->__GetParamsForAnalysis();

			$data = $this->__GetGraphicalData($params);



			if ($this->__o['debug']) {
				$this->__PrintReportDebug();
				$debug = "<pre>Elements: ". print_r($elements, true) ."\n"
				. "Filter From Form:". $result['xml']['form'][$form]['filter'] ."\n"
				. "Filter Form Member:". $kf['filter'] ."\n"
				. "Filter Final:". $this->__filter ."\n"
				. "Data: ". print_r($result, true) ."\n</pre>";
				$title = ' == '. __FUNCTION__ . " - ". __LINE__ ." == ";
				$this->__DebugPrintDiv($title, $debug);
			}

			foreach ($params['graphs'] as $graph_key => $graph_attr) {

				if ($graph_attr['type'] == 'line') {
					$g['label'] = $label;
					$index = array_keys($data[$graph_key][$this->__o['graphical_range'][0]]['data']);
					$g['data']  = $data[$graph_key][$this->__o['graphical_range'][0]]['data'][$index[0]];
					$graph[$graph_key][] = $g;
				} elseif ($graph_attr['type'] == 'bar') {

					$global[$graph_key] = array('group_name' => $label, 'group_key' => 'globalp24'. $label, 'groups' => array());

					foreach ($graph_attr['groups'] as $group_key => $group) {
						$data_set['bar_title'] = $group['data_field'];
						$data_set['bar_key']   = $group['data_field'];
						$index                 = array_keys($data[$graph_key][$this->__o['graphical_range'][0]][$group['data_field']]['data']);
						$data_set['data']      = $data[$graph_key][$this->__o['graphical_range'][0]][$group['data_field']]['data'][$index[0]];

						if (count($data_set['data']) > 0)
						$global[$graph_key]['groups'][] = $data_set;
					}

					if (count($global[$graph_key]) > 0)
					$graph[$graph_key][] = $global[$graph_key];

				} elseif ($graph_attr['type'] == 'combine') {

					$global[$graph_key] = array('group_name' => $elements['label'], 'group_key' => 'key_'.$elements['primary_key'], 'groups' => array());

					foreach ($graph_attr['groups'] as $group_key => $group)
					{
						$g['color'] = ($elements['color'] == 'auto') ? $colors[$counter++ % count($colors)] : $elements['color'];

						$data_set['bar_title'] = $group['title'];
						$data_set['bar_key']   = $group['data_field'];
						$data_set['group_key'] = 'key_'.$elements['primary_key'];
						$data_set['type']      = $group['type'];
						$data_set['color']     = $g['color'];
						$index                 = array_keys($data[$graph_key][$this->__o['graphical_range'][0]][$group['data_field']]['data']);
						$data_set['data']      = $data[$graph_key][$this->__o['graphical_range'][0]][$group['data_field']]['data'][$index[0]];

						if ($this->__o['debug']) {
							$debug = "<pre>". print_r($data_set['data'], true) . "</pre>";
							$title = ' == '. __FUNCTION__ . " - ". __LINE__ ." == ";
							$this->__DebugPrintDiv($title, $debug);
						}

						if (count($data_set['data']) > 0)
						$global[$graph_key]['groups'][] = $data_set;

					}

					foreach ($graph_attr['lines'] as $group_key => $group)
					{

						$data_set['bar_title'] = $group['title'];
						$data_set['bar_key']   = $group['data_field'];
						$data_set['group_key'] = 'key_'.$elements['primary_key'];
						$data_set['type']      = $group['type'];
						$data_set['color']     = $g['color'];
						$index                 = array_keys($data[$graph_key][$this->__o['graphical_range'][0]][$group['data_field']]['data']);
						$data_set['data']      = $data[$graph_key][$this->__o['graphical_range'][0]][$group['data_field']]['data'][$index[0]];

						if (count($data_set['data']) > 0)
						$global[$graph_key]['groups'][] = $data_set;

					}

					if (count($global) > 0)
					$graph[$graph_key][] = $global[$graph_key];

				}

			}
			$counter++;
		}

		$html = "<div class=header1>". $result['xml']['meta']['report_name'] ."</div>";

		foreach ($params['graphs'] as $graph_key => $graph_attr)
		{
			$meta = $graph_attr['meta'];

			$meta['file_name'] = md5('CustomGraph_'. $graph_key . '_' . time());
			$html .= "<img src='/images/cache/". $meta['file_name'] .".png'><br/>";

			$func = '__DrawBarPlot';

			if ($graph_attr['type'] == 'line')
			$func = '__DrawLinePlot';
			elseif ($graph_attr['type'] == 'combine')
			$func = '__DrawCombinePlot';

			if ($this->__o['debug']) {
				$debug = "<pre>". print_r($graph[$graph_key], true) . "</pre>";
				$title = ' == '. __FUNCTION__ . " - ". __LINE__ ." == ";
				$this->__DebugPrintDiv($title, $debug);
			}

			$this->$func($graph[$graph_key], $meta);
		}

		return $html;
	}

	/**
	* __DrawLinePlot()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Apr 20 09:16:44 PDT 2006
	*/
	private function __DrawLinePlot($y, $meta = array())
	{
		//$title .= print_r($y, true);

		$g = new Graph(500, 300,"auto");
		$g->SetScale("textint", 0, 0, 0, 0);

		$g->img->SetMargin(60,20,40,60);
		$g->SetShadow();

		$g->title->Set($meta['title']);
		$g->title->SetFont(FF_FONT1,FS_BOLD);
		$g->xaxis->title->Set($meta['x_title']);
		$g->yaxis->title->Set($meta['y_title']);
		$g->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
		$g->xaxis->SetLabelAngle(45);
		$g->xaxis->SetTickLabels($meta['x_axis']);
		$g->xaxis->SetTextLabelInterval(20);
		//$g->xaxis->scale->ticks->Set("1");
		//$g->xaxis->SetTickLabels();

		//$gb_incidence->xaxis->scale->ticks->Set($range['incidence']);
		// Create the linear plot
		$lplots = array();

		if (is_array($y[0])) {

			for ($i=0; $i < count($y); $i++) {
				$lplots[$i] = new LinePlot($y[$i]['data']);
				//$lplots[$i]->value->Show();
				$lplots[$i]->SetLegend($y[$i]['label']);
				$lplots[$i]->SetColor($y[$i]['color']);
				$lplots[$i]->SetWeight(2);

				$g->Add($lplots[$i]);
			}

			$g->legend->SetLayout(LEGEND_HOR);
			$g->legend->Pos(0.4,0.95,"center","bottom");
			//$g->legend->Pos(0.01,0.5,"right","center");

		} else {
			$bplot = new LinePlot($y);
			//$bplot->value->Show();
			$g->Add($bplot);
		}

		$g->Stroke(PATH .'/images/cache/'. $meta['file_name'] .'.png');

	}

	/**
	* __DrawBarPlot()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon May 08 10:41:58 PDT 2006
	*/
	private function __DrawBarPlot($data, $meta = array())
	{
		$colors = array('green', 'blue', 'red', 'silver', 'black', 'orange', 'purple', 'yellow', 'white');

		-		// Create the graph. These two calls are always required
		$graph = new Graph(500, 400,"auto");
		$graph->img->SetMargin(70,40,140,40);
		$graph->SetScale("textlin");

		$graph->SetShadow();
		//$graph->img->SetMargin(40,30,20,40);

		$i = 1;

		for ($g=0; $g < count($data); $g++) {
			for ($b=0; $b < count($data[$g]['groups']); $b++) {

				if (count($data[$g]['groups'][$b]['data']) == 0)
				continue;

				$obj = new BarPlot($data[$g]['groups'][$b]['data']);

				/* set specified color or auto color*/
				if (isset($data[$g]['groups'][$b]['color'])) {
					$obj->SetFillColor($data[$g]['groups'][$b]['color']);
				} else {
					$obj->SetFillColor($colors[($i % count($colors))]);
				}

				/* set legend */
				if (isset($data[$g]['groups'][$b]['bar_title'])) {
					$plot_title = $data[$g]['group_name'] . " - " . $data[$g]['groups'][$b]['bar_title'];
					$obj->SetLegend($plot_title);
				}


				$accrbar[$data[$g]['group_key']][] = $obj;
				$i++;
			}
		}

		for ($g=0; $g < count($data); $g++) {
			if (count($accrbar[$data[$g]['group_key']]) > 0)
			$ab[] = new AccBarPlot($accrbar[$data[$g]['group_key']]);
		}

		if (count($ab) == 0)
		return false;

		$gbplot = new GroupBarPlot($ab);
		$graph->Add($gbplot);





		$graph->title->Set($meta['title']);
		$graph->xaxis->title->Set($meta['x_title']);
		$graph->yaxis->title->Set($meta['y_title']);

		$graph->title->SetFont(FF_FONT1,FS_BOLD);
		$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
		$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

		$graph->xaxis->SetTickLabels($meta['x_axis']);
		$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
		$graph->xaxis->SetLabelAngle(45);
		$graph->legend->Pos(0.6,0.01,"center","top");

		// Display the graph
		$graph->Stroke(PATH .'/images/cache/'. $meta['file_name'] .'.png');

	}

	/**
	* __DrawCombinePlot()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Jun 07 09:38:22 PDT 2006
	*/
	function __DrawCombinePlot($data, $meta = array())
	{
		$colors = array('green', 'red', 'blue', 'green', 'black', 'orange', 'purple', 'yellow', 'white');

		// Create the graph. These two calls are always required
		$graph = new Graph(500, 400,"auto");
		$graph->img->SetMargin(60,40,60,40);
		$graph->SetScale("textlin");
		$graph->SetY2Scale("lin");
		$graph->SetShadow();
		$graph->yaxis->SetColor("blue");
		$graph->y2axis->SetColor("orange");
		$graph->SetY2OrderBack(false);

		$i = 1;



		for ($g=0; $g < count($data); $g++) {



			for ($b=0; $b < count($data[$g]['groups']); $b++) {

				if (count($data[$g]['groups'][$b]['data']) == 0)
				continue;

				if ($data[$g]['groups'][$b]['type'] == 'bar') {
					$obj = new BarPlot($data[$g]['groups'][$b]['data']);
					$set_color_func = 'SetFillColor';
				} else {
					$obj = new LinePlot($data[$g]['groups'][$b]['data']);
					$set_color_func = 'SetColor';
				}

				/* set specified color or auto color*/
				if (isset($data[$g]['groups'][$b]['color'])) {
					$obj->$set_color_func($data[$g]['groups'][$b]['color']);
				} else {
					$obj->$set_color_func($colors[($i % count($colors))]);
				}

				/* set legend */
				if (isset($data[$g]['groups'][$b]['bar_title'])) {
					$plot_title = $data[$g]['group_name'] . " - " . $data[$g]['groups'][$b]['bar_title'];
					$obj->SetLegend($plot_title);
				}

				if ($data[$g]['groups'][$b]['type'] == 'bar') {
					$accrbar[$data[$g]['group_key']][] = $obj;
				} else {
					$obj->SetBarCenter();
					$obj->SetWeight(2);
					$graph->AddY2($obj);
				}

				$i++;
			}
		}

		for ($g=0; $g < count($data); $g++) {
			if (count($accrbar[$data[$g]['group_key']]) > 0)
			$ab[] = new AccBarPlot($accrbar[$data[$g]['group_key']]);
		}

		if (count($ab) == 0)
		return false;

		$gbplot = new GroupBarPlot($ab);
		$graph->Add($gbplot);

		$graph->title->Set($meta['title']);
		$graph->title->Align('left');
		$graph->xaxis->title->Set($meta['x_title']);
		$graph->yaxis->title->Set($meta['y_title']);
		$graph->y2axis->title->Set($meta['y2_title']);
		$graph->yaxis->SetTitleMargin(35);
		$graph->y2axis->SetTitleMargin(15);
		$graph->xaxis->SetTextLabelInterval(20);
		$graph->xaxis->scale->ticks->Set(20,1);

		$graph->title->SetFont(FF_FONT1,FS_BOLD);
		$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
		$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

		$graph->xaxis->SetTickLabels($meta['x_axis']);
		$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
		$graph->xaxis->SetLabelAngle(45);
		$graph->legend->SetLayout(LEGEND_HOR);
		$graph->legend->Pos(0.05,0.02,"right","top");
		$graph->legend->SetColumns(2);
		//$graph->legend->Pos(0.6,0.01,"center","top");

		// Display the graph
		$graph->Stroke(PATH .'/images/cache/'. $meta['file_name'] .'.png');

	}

	/**
	* PublishPdf()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Apr 28 14:06:38 PDT 2006
	*/
	public function PublishPdf()
	{
		global $smarty;
		//$header = $this->DisplayHeader("null", "null", "null", 0, 0);

		$html = $header . $this->__o['html'];

		/*		$tags = array(
		//                       "input",
		//                       "form",
		//                       "option",
		//                       "select",
		//                       "a",
		//                       "span"
		//               );
		//
		//      $keep_inner = array('a');
		//
		//
		//		foreach ($tags as $tag) {
		//			$replace = '';
		//			if (in_array($tag, $keep_inner)) {
		//				$replace = '${2}';
		//				//	$replace = '';
		//			}
		//
		//			$reg = "/<".$tag."[^>]*\/?>(([^>]*)<\/". $tag .">)?/";
		//
		//			$html = preg_replace($reg, $replace, $html);
		//	} */


		$filename = md5('DOC_' . microtime()) . ".html";

		$fp = fopen("/var/www/hbdev/tmp/". $filename, "w");
		fwrite($fp, $html);
		fclose($fp);

		//$this->CreatePdf("/var/www/hbdev/test/gmi/www.globaltestmarket.com/index.html", "/tmp/gtm.pdf");
		$fp = fopen("http://10.0.0.50/Url2Pdf.aspx?filename=". $filename, "r");

		header("Pragma: public"); // required
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); // required for certain browsers
		header('Content-Type: application/octet-stream');
		//header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="Testing2.pdf"');
		fpassthru($fp);
		fclose($fp);

	}

	/**
	* GetReportAttributes()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
 @global
	* @since  - Sat May 06 21:44:37 PDT 2006
	*/
	public function GetReportAttributes()
	{
		$report = array(
		REPORT_PPM_PROJECT_BOOKING => array(
		'graphical' => 1,
		'tabular' => 1,
		'tabular_date_range' => array('mtd' => 'MTD', 'ytd' => 'YTD'),
		'graphs' => array('pv' => 'Project Value', 'pc' => 'Project Count', 'ct' => 'Conversion Time')
		),
		REPORT_PPM_PROPOSAL_VOLUME => array(
		'graphical' => 1,
		'tabular' => 1,
		'tabular_date_range' => array('mtd' => 'MTD', 'ytd' => 'YTD'),
		'graphs' => array('ppv' => 'Proposal Project Value', 'ppc' => 'Proposal Project Count', 'wr' => 'Win Ratio', 'cp' => 'Capture Ratio')
		)
		);

		$xml = $this->Array2Xml($report[$this->__o['report_id']], 'meta');

		header("Content-Type: text/xml");
		echo $xml;
	}

	/**
	* GetDisAggrData()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue May 09 10:50:13 PDT 2006
	*/
	public function GetDisAggrData($output = 'xml')
	{
		$data = array(
		'tier_id' => array(
		'1' => 'Tier 1',
		'2' => 'Tier 2',
		'3' => 'Tier 3',
		'4' => 'Tier 4',
		'0' => 'Unassigned'),
		'sample_type_id' => $this->CreateSmartyArray($this->__db->GetSampleTypes(), 'sample_type_id', 'sample_type_description')
		);

		$header = "<xml version='1.0'><meta>";
		$footer = "</meta></xml>";

		if ($output != 'xml')
		return $data[$this->__o['disaggr_by']];

		foreach ($data[$this->__o['disaggr_by']] as $k => $v) {
			$xml .= "<item><name>". $v ."</name><value>". $k ."</value></item>";
		}

		$xml = $header . $xml . $footer;

		header("Content-Type: text/xml");

		echo $xml;

	}

	/**
* DisplaySummary()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 24 08:47:12 PST 2006
*/
	function DisplaySummary()
	{
		$this->LoadSmarty();

		$date = date("Y-m-d");
		$year_month = date("Y-m");
		$year = date("Y");

		//PAST 24 HOURS
		$date_end  = $date ." 23:59:59";
		$date_begin = $date ." 00:00:00";

		$filter = array('null' => 'null');

		$summary['aging'] = $this->GetProposalAgingPortlet($filter);
		$summary['aging_sample'] = $this->GetProposalSampleAgingCountPortlet($filter);
		$summary['aging_sample_value'] = $this->GetProposalSampleAgingValuePortlet($filter);

		$summary['over100k'] = $this->GetRecentProposalDetailedPortlet(
		array(
		'sf_max_value' => 'pr.max_amount',
		'so_max_value' => 'GT',
		'sc_max_value' => '100000' )
		, array('portlet_title' => 'Rcent Proposals Over 100K '
		)
		);

		$summary['aging_sample_won'] = $this->GetProposalSampleAgingCountWonPortlet($filter);
		$summary['aging_sample_value_won'] = $this->GetProposalSampleAgingValueWonPortlet($filter);
		$summary['aging_region_won'] = $this->GetProposalRegionAgingCountWonPortlet($filter);
		$summary['aging_region_value_won'] = $this->GetProposalRegionAgingValueWonPortlet($filter);
		$summary['wonpast24hours'] = $this->GetRecentProposalDetailedPortlet(
		array(
		'sf_proposal_status_id' => 'pr.proposal_revision_status_id',
		'sc_proposal_status_id' => PROPOSAL_REVISION_STATUS_CLIENT_ACCEPTED,
		'dtc_created_date' => 'pr.created_date',
		'created_date_begin' => $date_begin,
		'created_date_end' => $date_end,
		), array('portlet_title' => "Proposals Won Past 24 Hours"));

		$this->smarty->assign('summary', $summary);


		$this->DisplayHeader("Proposal Summary", "pgen", 'display_summary');

		$this->smarty->display('app/pgen/vr_summary.tpl');

		$this->DisplayFooter();

	}

	/**
* GetProposalAgingPortlet()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 24 08:48:52 PST 2006
*/
	function GetProposalAgingPortlet($filter, $meta = array())
	{
		global $userRights, $status_titles, $encryption; //, $servername; //, $smarty;
		$p = new proposalDB();

		$registry = Zend_Registry::getInstance();
   		$smarty = $registry['smarty'];
   		$list = array();
		
		//////////////////////////////////////
		$page_size = 10;

		$start = 0;

		$sort = 'p.created_date DESC';

		$page = 'LIMIT '.$start.','.$page_size;

		if (count($filter) > 0) {
			$filter = $p->BuildSearchString($filter);
		}

		//lets add our over-write filter
		//$filter .= " AND pr.proposal_revision_status_id IN (". PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL .", ". PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED .", ". PROPOSAL_REVISION_STATUS_WAITING_APPROVAL .") ";

		$date = date("Y-m-d");
		$year_month = date("Y-m");
		$year = date("Y");

		//PAST 24 HOURS
		$end_date  = $date ." 23:59:59";
		$start_date = $date ." 00:00:00";

		/* Win Rate */
		$unique_proposals = $p->GetProposalCount($filter, $start_date, $end_date);
		$won_proposals = $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_WON, $start_date, $end_date);
		@$win_rate = $won_proposals / $unique_proposals * 100;

		/* Caputure Rate */
		$won_value = $p->GetProposalRevisionValue(' AND pr.proposal_revision_status_id = '. PROPOSAL_REVISION_STATUS_CLIENT_ACCEPTED, $start_date, $end_date);
		$all_value = $p->GetProposalRevisionValue($filter, $start_date, $end_date);
		@$capture_rate = $won_value / $all_value * 100;

		$row = array(
		'period' => 'Past 24 Hours',
		'proposal' => $unique_proposals,
		'revision' => $p->GetProposalRevisionCount($filter, $start_date, $end_date),
		'total_value' => $all_value,
		'in_progress' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_WORK_PROGRESS, $start_date, $end_date),
		'postponed' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_POSTPONED, $start_date, $end_date),
		'cancelled' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_CANCELLED, $start_date, $end_date),
		'lost' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_LOST, $start_date, $end_date),
		'won' => $won_proposals,
		'won_value' => $won_value,
		'win_rate' => $win_rate,
		'capture_rate' => $capture_rate
		);

		$data[] = $row;

		//7 DAYS

		$start_date = date("Y-m-d H:i:s", strtotime("-1 week"));

		/* Win Rate */
		$unique_proposals = $p->GetProposalCount($filter, $start_date, $end_date);
		$won_proposals = $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_WON, $start_date, $end_date);
		@$win_rate = $won_proposals / $unique_proposals * 100;

		/* Caputure Rate */
		$won_value = $p->GetProposalRevisionValue(' AND pr.proposal_revision_status_id = '. PROPOSAL_REVISION_STATUS_CLIENT_ACCEPTED, $start_date, $end_date);
		$all_value = $p->GetProposalRevisionValue($filter, $start_date, $end_date);
		@$capture_rate = $won_value / $all_value * 100;

		$row = array(
		'period' => 'Past 7 Days',
		'proposal' => $unique_proposals,
		'revision' => $p->GetProposalRevisionCount($filter, $start_date, $end_date),
		'total_value' => $all_value,
		'in_progress' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_WORK_PROGRESS, $start_date, $end_date),
		'postponed' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_POSTPONED, $start_date, $end_date),
		'cancelled' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_CANCELLED, $start_date, $end_date),
		'lost' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_LOST, $start_date, $end_date),
		'won' => $won_proposals,
		'won_value' => $won_value,
		'win_rate' => $win_rate,
		'capture_rate' => $capture_rate
		);
		$data[] = $row;

		//30 DAYS
		$start_date = date("Y-m-d H:i:s", strtotime("-30 days"));

		/* Win Rate */
		$unique_proposals = $p->GetProposalCount($filter, $start_date, $end_date);
		$won_proposals = $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_WON, $start_date, $end_date);
		$win_rate = $won_proposals / $unique_proposals * 100;

		/* Caputure Rate */
		$won_value = $p->GetProposalRevisionValue(' AND pr.proposal_revision_status_id = '. PROPOSAL_REVISION_STATUS_CLIENT_ACCEPTED, $start_date, $end_date);
		$all_value = $p->GetProposalRevisionValue($filter, $start_date, $end_date);
		$capture_rate = $won_value / $all_value * 100;

		$row = array(
		'period' => 'Past 30 Days',
		'proposal' => $unique_proposals,
		'revision' => $p->GetProposalRevisionCount($filter, $start_date, $end_date),
		'total_value' => $all_value,
		'in_progress' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_WORK_PROGRESS, $start_date, $end_date),
		'postponed' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_POSTPONED, $start_date, $end_date),
		'cancelled' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_CANCELLED, $start_date, $end_date),
		'lost' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_LOST, $start_date, $end_date),
		'won' => $won_proposals,
		'won_value' => $won_value,
		'win_rate' => $win_rate,
		'capture_rate' => $capture_rate
		);
		$data[] = $row;


		//MTD
		$start_date = $year_month ."-01 00:00:00";

		/* Win Rate */
		$unique_proposals = $p->GetProposalCount($filter, $start_date, $end_date);
		$won_proposals = $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_WON, $start_date, $end_date);
		$win_rate = $won_proposals / $unique_proposals * 100;

		/* Caputure Rate */
		$won_value = $p->GetProposalRevisionValue(' AND pr.proposal_revision_status_id = '. PROPOSAL_REVISION_STATUS_CLIENT_ACCEPTED, $start_date, $end_date);
		$all_value = $p->GetProposalRevisionValue($filter, $start_date, $end_date);
		$capture_rate = $won_value / $all_value * 100;

		$row = array(
		'period' => 'Month to Date',
		'proposal' => $unique_proposals,
		'revision' => $p->GetProposalRevisionCount($filter, $start_date, $end_date),
		'total_value' => $all_value,
		'in_progress' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_WORK_PROGRESS, $start_date, $end_date),
		'postponed' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_POSTPONED, $start_date, $end_date),
		'cancelled' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_CANCELLED, $start_date, $end_date),
		'lost' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_LOST, $start_date, $end_date),
		'won' => $won_proposals,
		'won_value' => $won_value,
		'win_rate' => $win_rate,
		'capture_rate' => $capture_rate
		);
		$data[] = $row;


		//YTD
		$start_date = $year ."-01-01 00:00:00";

		/* Win Rate */
		$unique_proposals = $p->GetProposalCount($filter, $start_date, $end_date);
		$won_proposals = $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_WON, $start_date, $end_date);
		$win_rate = $won_proposals / $unique_proposals * 100;

		/* Caputure Rate */
		$won_value = $p->GetProposalRevisionValue(' AND pr.proposal_revision_status_id = '. PROPOSAL_REVISION_STATUS_CLIENT_ACCEPTED, $start_date, $end_date);
		$all_value = $p->GetProposalRevisionValue($filter, $start_date, $end_date);
		$capture_rate = $won_value / $all_value * 100;

		$row = array(
		'period' => 'Year To Date',
		'proposal' => $unique_proposals,
		'revision' => $p->GetProposalRevisionCount($filter, $start_date, $end_date),
		'total_value' => $all_value,
		'in_progress' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_WORK_PROGRESS, $start_date, $end_date),
		'postponed' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_POSTPONED, $start_date, $end_date),
		'cancelled' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_CANCELLED, $start_date, $end_date),
		'lost' => $p->GetProposalCount(' AND p.proposal_status_id = '. PROPOSAL_STATUS_LOST, $start_date, $end_date),
		'won' => $won_proposals,
		'won_value' => $won_value,
		'win_rate' => $win_rate,
		'capture_rate' => $capture_rate
		);
		$data[] = $row;


		//number of rows in the current page

		$header[0]['field'] = 'p.proposal_name';
		$header[0]['title'] = 'Time Period';

		$header[1]['field'] = 'account_name';
		$header[1]['title'] = 'Unique Proposals';

		$header[2]['field'] = 'pr.max_amount';
		$header[2]['title'] = 'Total Proposals';

		$header[3]['field'] = 'prs.proposal_revision_status_description';
		$header[3]['title'] = 'Total Proposal $$';

		$header[4]['field'] = 'prs.proposal_revision_status_description';
		$header[4]['title'] = 'In Progress';

		$header[5]['field'] = 'prs.proposal_revision_status_description';
		$header[5]['title'] = 'Postponed';

		$header[6]['field'] = 'prs.proposal_revision_status_description';
		$header[6]['title'] = 'Cancelled';

		$header[7]['field'] = 'prs.proposal_revision_status_description';
		$header[7]['title'] = 'Lost';

		$header[8]['field'] = 'prs.proposal_revision_status_description';
		$header[8]['title'] = 'Won';

		$header[9]['field'] = 'prs.proposal_revision_status_description';
		$header[9]['title'] = 'Value of Won Projects';

		$header[10]['field'] = 'prs.proposal_revision_status_description';
		$header[10]['title'] = 'Win Rate';

		$header[11]['field'] = 'prs.proposal_revision_status_description';
		$header[11]['title'] = 'Capture Rate';


		$smarty->assign('header',$header);
		$smarty->assign('data', $data);
		$smarty->assign('lang', $o['lbl']);
		$smarty->assign('l', $l);
		$smarty->assign('meta',$meta);

		return $smarty->fetch('quote/ptl_proposal_aging.tpl');

	}

	/**
* GetProposalRegionAgingValueWonLineGraph()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 24 08:48:52 PST 2006
*/
	function GetProposalRegionAgingValueWonLineGraph($filter, $meta = array())
	{
		$p = new proposalDB();
		$page_size = 10;

		$start = 0;

		$sort = 'p.created_date DESC';

		$page = 'LIMIT '.$start.','.$page_size;

		if (count($filter) > 0) {
			$filter = $p->BuildSearchString($filter);
		}

		//lets add our over-write filter
		//$filter .= " AND pr.proposal_revision_status_id IN (". PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL .", ". PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED .", ". PROPOSAL_REVISION_STATUS_WAITING_APPROVAL .") ";
		$filter = " AND p.proposal_status_id = ". PROPOSAL_STATUS_WON;

		$date = date("Y-m-d");
		$year_month = date("Y-m");
		$year = date("Y");

		//Loop past 12 months
		$end_date  = $date ." 23:59:59";

		for ($i=12; $i >= 0; $i--) {

			$start_date = date("Y-m", strtotime("-".$i ." month"));
			$start_date .= "-01 00:00:00";

			$end_date = date("Y-m", strtotime("-". $i ." month"));
			$last_day = GetLastDateOfMonth(strtotime("-". $i ." month"));
			$end_date = $end_date ."-". $last_day ." 23:59:59";


			$period[] = date("Y-M", strtotime("-".$i ." month"));
			$na[]     = $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_NORTH_AMERICA .") ", $start_date, $end_date);
			$eu[]     = $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_EUROPE .") ", $start_date, $end_date);
			$ea[]     = $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_AUSTRALIA .", ". REGION_MIDDLE_EAST .",". REGION_SOUTH_CENTRAL_ASIA .",". REGION_SOUTH_EAST_ASIA .") ", $start_date, $end_date);
			$china[]  = $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_EAST_ASIA .") ", $start_date, $end_date);
			$latam[]  = $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_SOUTH_AMERICA .") ", $start_date, $end_date);
		}

		// Create the graph. These two calls are always required
		$graph = new Graph(800,400,"auto");
		$graph->SetScale("textlin");

		// Create the linear plot
		$lineplot=new BarPlot($na);
		$lineplot->SetFillColor("blue");
		$lineplot->SetLegend('North America');
		$lineplot->SetWeight(2);


		$lineplot2=new BarPlot($eu);
		$lineplot2->SetFillColor("orange");
		$lineplot2->SetLegend('Europe');
		$lineplot2->SetWeight(2);


		$lineplot3=new BarPlot($ea);
		$lineplot3->SetFillColor("red");
		$lineplot3->SetLegend('Asia-Pacific');
		$lineplot3->SetWeight(2);


		$lineplot4=new BarPlot($china);
		$lineplot4->SetFillColor("yellow");
		$lineplot4->SetLegend('Chinca');
		$lineplot4->SetWeight(2);


		$lineplot5=new BarPlot($latam);
		$lineplot5->SetFillColor("brown");
		$lineplot5->SetLegend('Latin America');
		$lineplot5->SetWeight(2);

		$gbplot = new AccBarPlot(array($lineplot, $lineplot2, $lineplot3, $lineplot4, $lineplot5));
		$graph->add($gbplot);

		$graph->legend->Pos(0.05,0.5,"right","center");

		$graph->img->SetMargin(50,150,50,50);
		$graph->title->Set("Value Projects Won By Region");
		$graph->xaxis->SetTickLabels($period);
		$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
		$graph->xaxis->SetLabelAngle(45);
		$graph->yaxis->title->Set("Value");

		$graph->title->SetFont(FF_FONT1,FS_BOLD);
		$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);

		$graph->yaxis->SetColor("red");
		$graph->yaxis->SetWeight(2);
		$graph->SetShadow();

		// Display the graph
		$graph->Stroke();


		//print_r($data);


	}

	/**
* GetProposalRegionAgingCountWonLineGraph()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 24 08:48:52 PST 2006
*/
	function GetProposalRegionAgingCountWonLineGraph($filter, $meta = array())
	{
		$p = new proposalDB();
		$page_size = 10;

		$start = 0;

		$sort = 'p.created_date DESC';

		$page = 'LIMIT '.$start.','.$page_size;

		if (count($filter) > 0) {
			$filter = $p->BuildSearchString($filter);
		}

		//lets add our over-write filter
		//$filter .= " AND pr.proposal_revision_status_id IN (". PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL .", ". PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED .", ". PROPOSAL_REVISION_STATUS_WAITING_APPROVAL .") ";
		$filter = " AND p.proposal_status_id = ". PROPOSAL_STATUS_WON;

		$date = date("Y-m-d");
		$year_month = date("Y-m");
		$year = date("Y");

		//Loop past 12 months
		$end_date  = $date ." 23:59:59";

		for ($i=12; $i >= 0; $i--) {

			$start_date = date("Y-m", strtotime("-".$i ." month"));
			$start_date .= "-01 00:00:00";

			$end_date = date("Y-m", strtotime("-". $i ." month"));
			$last_day = GetLastDateOfMonth(strtotime("-". $i ." month"));
			$end_date = $end_date ."-". $last_day ." 23:59:59";


			$period[] = date("Y-M", strtotime("-".$i ." month"));
			$na[]     = $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_NORTH_AMERICA .") ", $start_date, $end_date);
			$eu[]     = $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_EUROPE .") ", $start_date, $end_date);
			$ea[]     = $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_AUSTRALIA .", ". REGION_MIDDLE_EAST .",". REGION_SOUTH_CENTRAL_ASIA .",". REGION_SOUTH_EAST_ASIA .") ", $start_date, $end_date);
			$china[]  = $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_EAST_ASIA .") ", $start_date, $end_date);
			$latam[]  = $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_SOUTH_AMERICA .") ", $start_date, $end_date);
		}

		// Create the graph. These two calls are always required
		$graph = new Graph(800,400,"auto");
		$graph->SetScale("textlin");

		// Create the linear plot
		$lineplot=new LinePlot($na);
		$lineplot->SetColor("blue");
		$lineplot->SetWeight(2);
		$lineplot->SetLegend("North America");
		$graph->Add($lineplot);

		$lineplot2=new LinePlot($eu);
		$lineplot2->SetColor("orange");
		$lineplot2->SetWeight(2);
		$lineplot2->SetLegend("Europe");
		$graph->Add($lineplot2);

		$lineplot3=new LinePlot($ea);
		$lineplot3->SetColor("red");
		$lineplot3->SetWeight(2);
		$lineplot3->SetLegend("Asia Pacific");
		$graph->Add($lineplot3);

		$lineplot4=new LinePlot($china);
		$lineplot4->SetColor("yellow");
		$lineplot4->SetWeight(2);
		$lineplot4->SetLegend("China");
		$graph->Add($lineplot4);

		$lineplot5=new LinePlot($latam);
		$lineplot5->SetColor("brown");
		$lineplot5->SetWeight(2);
		$lineplot5->SetLegend("Latin America");
		$graph->Add($lineplot5);

		$graph->legend->Pos(0.05,0.5,"right","center");

		$graph->img->SetMargin(50,150,50,50);
		$graph->title->Set("Projects Won By Region");
		$graph->xaxis->SetTickLabels($period);
		$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
		$graph->xaxis->SetLabelAngle(45);
		$graph->yaxis->title->Set("Volume");

		$graph->title->SetFont(FF_FONT1,FS_BOLD);
		$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);





		$graph->yaxis->SetColor("red");
		$graph->yaxis->SetWeight(2);
		$graph->SetShadow();

		// Display the graph
		$graph->Stroke();


		//print_r($data);


	}

	/**
* GetProposalSampleAgingValueWonPortlet()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 24 08:48:52 PST 2006
*/
	function GetProposalRegionAgingValueWonPortlet($filter, $meta = array())
	{
		global $userRights, $status_titles, $encryption; //, $servername; //, $smarty;
		$p = new proposalDB();

		///////////////////////////////////////
		if ($_SESSION['admin_id'] == SYSTEM_USER) {

			global $cfg, $servername;
			$smarty = new Smarty();

			$smarty->template_dir = '/var/www/smarty/' . $servername . '/templates';
			$smarty->compile_dir = '/var/www/smarty/' . $servername . '/templates_c';
			$smarty->cache_dir = '/var/www/smarty/' . $servername . '/cache';
			$smarty->config_dir = '/var/www/smarty/' . $servername . '/configs';


			$smarty->plugins_dir = array('plugins', $cfg['base_dir'].'/include/smarty_plugins');

			//require_once ($smarty->_get_plugin_filepath('modifier', 'url_encrypt'));

			//$smarty->register_modifier('url_encrypt', 'url_encrypt');


			$smarty->compile_check = TRUE;
			$smarty->force_compile = TRUE;
		} else {
			global $smarty;
		}

		///////////////////////////////////////

		//timezone stuff
		//$tz = GetTimeZone($filter);e
		//$p->tz = $tz;

		$page_size = 10;

		$start = 0;

		$sort = 'p.created_date DESC';

		$page = 'LIMIT '.$start.','.$page_size;

		if (count($filter) > 0) {
			$filter = $p->BuildSearchString($filter);
		}

		//lets add our over-write filter
		//$filter .= " AND pr.proposal_revision_status_id IN (". PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL .", ". PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED .", ". PROPOSAL_REVISION_STATUS_WAITING_APPROVAL .") ";
		$filter = " AND p.proposal_status_id = ". PROPOSAL_STATUS_WON;

		$date = date("Y-m-d");
		$year_month = date("Y-m");
		$year = date("Y");

		//PAST 24 HOURS
		$end_date  = $date ." 23:59:59";
		$start_date = $date ." 00:00:00";

		$row = array(
		'period' => 'Past 24 Hours',
		'proposal' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'north_america' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_NORTH_AMERICA .") ", $start_date, $end_date),
		'europe' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_EUROPE .") ", $start_date, $end_date),
		'asia_pacific' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_AUSTRALIA .", ". REGION_MIDDLE_EAST .",". REGION_SOUTH_CENTRAL_ASIA .",". REGION_SOUTH_EAST_ASIA .") ", $start_date, $end_date),
		'china' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_EAST_ASIA .") ", $start_date, $end_date),
		'latam' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_SOUTH_AMERICA .") ", $start_date, $end_date)
		);

		$data[] = $row;

		//7 DAYS

		$start_date = date("Y-m-d H:i:s", strtotime("-1 week"));

		$row = array(
		'period' => 'Past 7 Days',
		'proposal' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'north_america' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_NORTH_AMERICA .") ", $start_date, $end_date),
		'europe' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_EUROPE .") ", $start_date, $end_date),
		'asia_pacific' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_AUSTRALIA .", ". REGION_MIDDLE_EAST .",". REGION_SOUTH_CENTRAL_ASIA .",". REGION_SOUTH_EAST_ASIA .") ", $start_date, $end_date),
		'china' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_EAST_ASIA .") ", $start_date, $end_date),
		'latam' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_SOUTH_AMERICA .") ", $start_date, $end_date)
		);
		$data[] = $row;

		//30 DAYS
		$start_date = date("Y-m-d H:i:s", strtotime("-30 days"));

		$row = array(
		'period' => 'Past 30 Days',
		'proposal' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'north_america' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_NORTH_AMERICA .") ", $start_date, $end_date),
		'europe' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_EUROPE .") ", $start_date, $end_date),
		'asia_pacific' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_AUSTRALIA .", ". REGION_MIDDLE_EAST .",". REGION_SOUTH_CENTRAL_ASIA .",". REGION_SOUTH_EAST_ASIA .") ", $start_date, $end_date),
		'china' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_EAST_ASIA .") ", $start_date, $end_date),
		'latam' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_SOUTH_AMERICA .") ", $start_date, $end_date)
		);
		$data[] = $row;


		//MTD
		$start_date = $year_month ."-01 00:00:00";

		$row = array(
		'period' => 'Month to Date',
		'proposal' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'north_america' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_NORTH_AMERICA .") ", $start_date, $end_date),
		'europe' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_EUROPE .") ", $start_date, $end_date),
		'asia_pacific' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_AUSTRALIA .", ". REGION_MIDDLE_EAST .",". REGION_SOUTH_CENTRAL_ASIA .",". REGION_SOUTH_EAST_ASIA .") ", $start_date, $end_date),
		'china' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_EAST_ASIA .") ", $start_date, $end_date),
		'latam' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_SOUTH_AMERICA .") ", $start_date, $end_date)
		);
		$data[] = $row;


		//YTD
		$start_date = $year ."-01-01 00:00:00";

		$row = array(
		'period' => 'Year To Date',
		'proposal' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'north_america' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_NORTH_AMERICA .") ", $start_date, $end_date),
		'europe' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_EUROPE .") ", $start_date, $end_date),
		'asia_pacific' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_AUSTRALIA .", ". REGION_MIDDLE_EAST .",". REGION_SOUTH_CENTRAL_ASIA .",". REGION_SOUTH_EAST_ASIA .") ", $start_date, $end_date),
		'china' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_EAST_ASIA .") ", $start_date, $end_date),
		'latam' => $p->GetProposalRevisionValue($filter .' AND p.region_id IN ('. REGION_SOUTH_AMERICA .") ", $start_date, $end_date)
		);
		$data[] = $row;


		//number of rows in the current page

		$header[0]['field'] = 'p.proposal_name';
		$header[0]['title'] = 'Time Period';

		$header[1]['field'] = 'account_name';
		$header[1]['title'] = 'Unique Proposals';

		$header[2]['field'] = 'pr.max_amount';
		$header[2]['title'] = 'North America';

		$header[3]['field'] = 'prs.proposal_revision_status_description';
		$header[3]['title'] = 'Europe';

		$header[4]['field'] = 'prs.proposal_revision_status_description';
		$header[4]['title'] = 'Asia-Pacific';

		$header[5]['field'] = 'prs.proposal_revision_status_description';
		$header[5]['title'] = 'China (East Asia)';

		$header[6]['field'] = 'prs.proposal_revision_status_description';
		$header[6]['title'] = 'Latin America';


		$smarty->assign('header',$header);
		$smarty->assign('data', $data);
		$smarty->assign('lang', $o['lbl']);
		$smarty->assign('l', $l);
		$smarty->assign('meta',$meta);

		return $smarty->fetch('app/pgen/ptl_proposal_region_aging_value_won.tpl');
	}

	/**
* GetProposalSampleAgingCountWonPortlet()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 24 08:48:52 PST 2006
*/
	function GetProposalRegionAgingCountWonPortlet($filter, $meta = array())
	{
		global $userRights, $status_titles, $encryption; //, $servername; //, $smarty;
		$p = new proposalDB();

		///////////////////////////////////////
		if ($_SESSION['admin_id'] == SYSTEM_USER) {

			global $cfg, $servername;
			$smarty = new Smarty();

			$smarty->template_dir = '/var/www/smarty/' . $servername . '/templates';
			$smarty->compile_dir = '/var/www/smarty/' . $servername . '/templates_c';
			$smarty->cache_dir = '/var/www/smarty/' . $servername . '/cache';
			$smarty->config_dir = '/var/www/smarty/' . $servername . '/configs';


			$smarty->plugins_dir = array('plugins', $cfg['base_dir'].'/include/smarty_plugins');

			//require_once ($smarty->_get_plugin_filepath('modifier', 'url_encrypt'));

			//$smarty->register_modifier('url_encrypt', 'url_encrypt');


			$smarty->compile_check = TRUE;
			$smarty->force_compile = TRUE;
		} else {
			global $smarty;
		}

		///////////////////////////////////////

		//timezone stuff
		//$tz = GetTimeZone($filter);e
		//$p->tz = $tz;

		$page_size = 10;

		$start = 0;

		$sort = 'p.created_date DESC';

		$page = 'LIMIT '.$start.','.$page_size;

		if (count($filter) > 0) {
			$filter = $p->BuildSearchString($filter);
		}

		//lets add our over-write filter
		//$filter .= " AND pr.proposal_revision_status_id IN (". PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL .", ". PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED .", ". PROPOSAL_REVISION_STATUS_WAITING_APPROVAL .") ";
		$filter = " AND p.proposal_status_id = ". PROPOSAL_STATUS_WON;

		$date = date("Y-m-d");
		$year_month = date("Y-m");
		$year = date("Y");

		//PAST 24 HOURS
		$end_date  = $date ." 23:59:59";
		$start_date = $date ." 00:00:00";

		$row = array(
		'period' => 'Past 24 Hours',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'north_america' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_NORTH_AMERICA .") ", $start_date, $end_date),
		'europe' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_EUROPE .") ", $start_date, $end_date),
		'asia_pacific' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_AUSTRALIA .", ". REGION_MIDDLE_EAST .",". REGION_SOUTH_CENTRAL_ASIA .",". REGION_SOUTH_EAST_ASIA .") ", $start_date, $end_date),
		'china' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_EAST_ASIA .") ", $start_date, $end_date),
		'latam' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_SOUTH_AMERICA .") ", $start_date, $end_date)
		);

		$data[] = $row;

		//7 DAYS

		$start_date = date("Y-m-d H:i:s", strtotime("-1 week"));

		$row = array(
		'period' => 'Past 7 Days',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'north_america' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_NORTH_AMERICA .") ", $start_date, $end_date),
		'europe' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_EUROPE .") ", $start_date, $end_date),
		'asia_pacific' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_AUSTRALIA .", ". REGION_MIDDLE_EAST .",". REGION_SOUTH_CENTRAL_ASIA .",". REGION_SOUTH_EAST_ASIA .") ", $start_date, $end_date),
		'china' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_EAST_ASIA .") ", $start_date, $end_date),
		'latam' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_SOUTH_AMERICA .") ", $start_date, $end_date)
		);
		$data[] = $row;

		//30 DAYS
		$start_date = date("Y-m-d H:i:s", strtotime("-30 days"));

		$row = array(
		'period' => 'Past 30 Days',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'north_america' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_NORTH_AMERICA .") ", $start_date, $end_date),
		'europe' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_EUROPE .") ", $start_date, $end_date),
		'asia_pacific' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_AUSTRALIA .", ". REGION_MIDDLE_EAST .",". REGION_SOUTH_CENTRAL_ASIA .",". REGION_SOUTH_EAST_ASIA .") ", $start_date, $end_date),
		'china' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_EAST_ASIA .") ", $start_date, $end_date),
		'latam' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_SOUTH_AMERICA .") ", $start_date, $end_date)

		);
		$data[] = $row;


		//MTD
		$start_date = $year_month ."-01 00:00:00";

		$row = array(
		'period' => 'Month to Date',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'north_america' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_NORTH_AMERICA .") ", $start_date, $end_date),
		'europe' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_EUROPE .") ", $start_date, $end_date),
		'asia_pacific' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_AUSTRALIA .", ". REGION_MIDDLE_EAST .",". REGION_SOUTH_CENTRAL_ASIA .",". REGION_SOUTH_EAST_ASIA .") ", $start_date, $end_date),
		'china' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_EAST_ASIA .") ", $start_date, $end_date),
		'latam' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_SOUTH_AMERICA .") ", $start_date, $end_date)
		);
		$data[] = $row;


		//YTD
		$start_date = $year ."-01-01 00:00:00";

		$row = array(
		'period' => 'Year To Date',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'north_america' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_NORTH_AMERICA .") ", $start_date, $end_date),
		'europe' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_EUROPE .") ", $start_date, $end_date),
		'asia_pacific' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_AUSTRALIA .", ". REGION_MIDDLE_EAST .",". REGION_SOUTH_CENTRAL_ASIA .",". REGION_SOUTH_EAST_ASIA .") ", $start_date, $end_date),
		'china' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_EAST_ASIA .") ", $start_date, $end_date),
		'latam' => $p->GetProposalCount($filter .' AND p.region_id IN ('. REGION_SOUTH_AMERICA .") ", $start_date, $end_date)
		);
		$data[] = $row;


		//number of rows in the current page

		$header[0]['field'] = 'p.proposal_name';
		$header[0]['title'] = 'Time Period';

		$header[1]['field'] = 'account_name';
		$header[1]['title'] = 'Unique Proposals';

		$header[2]['field'] = 'pr.max_amount';
		$header[2]['title'] = 'North America';

		$header[3]['field'] = 'prs.proposal_revision_status_description';
		$header[3]['title'] = 'Europe';

		$header[4]['field'] = 'prs.proposal_revision_status_description';
		$header[4]['title'] = 'Asia-Pacific';

		$header[5]['field'] = 'prs.proposal_revision_status_description';
		$header[5]['title'] = 'China (East Asia)';

		$header[6]['field'] = 'prs.proposal_revision_status_description';
		$header[6]['title'] = 'Latin America';


		$smarty->assign('header',$header);
		$smarty->assign('data', $data);
		$smarty->assign('lang', $o['lbl']);
		$smarty->assign('l', $l);
		$smarty->assign('meta',$meta);

		return $smarty->fetch('app/pgen/ptl_proposal_region_aging_count_won.tpl');
	}

	/**
* GetProposalSampleAgingValueWonPortlet()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 24 08:48:52 PST 2006
*/
	function GetProposalSampleAgingValueWonPortlet($filter, $meta = array())
	{
		global $userRights, $status_titles, $encryption; //, $servername; //, $smarty;
		$p = new proposalDB();

		///////////////////////////////////////
		if ($_SESSION['admin_id'] == SYSTEM_USER) {

			global $cfg, $servername;
			$smarty = new Smarty();

			$smarty->template_dir = '/var/www/smarty/' . $servername . '/templates';
			$smarty->compile_dir = '/var/www/smarty/' . $servername . '/templates_c';
			$smarty->cache_dir = '/var/www/smarty/' . $servername . '/cache';
			$smarty->config_dir = '/var/www/smarty/' . $servername . '/configs';


			$smarty->plugins_dir = array('plugins', $cfg['base_dir'].'/include/smarty_plugins');

			//require_once ($smarty->_get_plugin_filepath('modifier', 'url_encrypt'));

			//$smarty->register_modifier('url_encrypt', 'url_encrypt');


			$smarty->compile_check = TRUE;
			$smarty->force_compile = TRUE;
		} else {
			global $smarty;
		}

		///////////////////////////////////////

		//timezone stuff
		//$tz = GetTimeZone($filter);e
		//$p->tz = $tz;

		$page_size = 10;

		$start = 0;

		$sort = 'p.created_date DESC';

		$page = 'LIMIT '.$start.','.$page_size;

		if (count($filter) > 0) {
			$filter = $p->BuildSearchString($filter);
		}

		//lets add our over-write filter
		//$filter .= " AND pr.proposal_revision_status_id IN (". PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL .", ". PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED .", ". PROPOSAL_REVISION_STATUS_WAITING_APPROVAL .") ";
		$filter = " AND p.proposal_status_id = ". PROPOSAL_STATUS_WON;

		$date = date("Y-m-d");
		$year_month = date("Y-m");
		$year = date("Y");

		//PAST 24 HOURS
		$end_date  = $date ." 23:59:59";
		$start_date = $date ." 00:00:00";

		$row = array(
		'period' => 'Past 24 Hours',
		'proposal_value' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);

		$data[] = $row;

		//7 DAYS

		$start_date = date("Y-m-d H:i:s", strtotime("-1 week"));

		$row = array(
		'period' => 'Past 7 Days',
		'proposal_value' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);
		$data[] = $row;

		//30 DAYS
		$start_date = date("Y-m-d H:i:s", strtotime("-30 days"));

		$row = array(
		'period' => 'Past 30 Days',
		'proposal_value' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);
		$data[] = $row;


		//MTD
		$start_date = $year_month ."-01 00:00:00";

		$row = array(
		'period' => 'Month to Date',
		'proposal_value' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);
		$data[] = $row;


		//YTD
		$start_date = $year ."-01-01 00:00:00";

		$row = array(
		'period' => 'Year To Date',
		'proposal_value' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalValueBySampleType($filter .' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);
		$data[] = $row;


		//number of rows in the current page

		$header[0]['field'] = 'p.proposal_name';
		$header[0]['title'] = 'Time Period';

		$header[1]['field'] = 'account_name';
		$header[1]['title'] = 'Unique Proposals';

		$header[2]['field'] = 'pr.max_amount';
		$header[2]['title'] = 'Consumer General';

		$header[3]['field'] = 'prs.proposal_revision_status_description';
		$header[3]['title'] = 'Medical Professionals';

		$header[4]['field'] = 'prs.proposal_revision_status_description';
		$header[4]['title'] = 'Consumer (Medical)';

		$header[5]['field'] = 'prs.proposal_revision_status_description';
		$header[5]['title'] = 'B2B IT';

		$header[6]['field'] = 'prs.proposal_revision_status_description';
		$header[6]['title'] = 'Consumer (Youth)';

		$header[7]['field'] = 'prs.proposal_revision_status_description';
		$header[7]['title'] = 'B2B General';

		$header[8]['field'] = 'prs.proposal_revision_status_description';
		$header[8]['title'] = 'Others (Not Given)';


		$smarty->assign('header',$header);
		$smarty->assign('data', $data);
		$smarty->assign('lang', $o['lbl']);
		$smarty->assign('l', $l);
		$smarty->assign('meta',$meta);

		return $smarty->fetch('app/pgen/ptl_proposal_sample_aging_value_won.tpl');
	}

	/**
* GetProposalSampleAgingCountWonPortlet()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 24 08:48:52 PST 2006
*/
	function GetProposalSampleAgingCountWonPortlet($filter, $meta = array())
	{
		global $userRights, $status_titles, $encryption; //, $servername; //, $smarty;
		$p = new proposalDB();

		///////////////////////////////////////
		if ($_SESSION['admin_id'] == SYSTEM_USER) {

			global $cfg, $servername;
			$smarty = new Smarty();

			$smarty->template_dir = '/var/www/smarty/' . $servername . '/templates';
			$smarty->compile_dir = '/var/www/smarty/' . $servername . '/templates_c';
			$smarty->cache_dir = '/var/www/smarty/' . $servername . '/cache';
			$smarty->config_dir = '/var/www/smarty/' . $servername . '/configs';


			$smarty->plugins_dir = array('plugins', $cfg['base_dir'].'/include/smarty_plugins');

			//require_once ($smarty->_get_plugin_filepath('modifier', 'url_encrypt'));

			//$smarty->register_modifier('url_encrypt', 'url_encrypt');


			$smarty->compile_check = TRUE;
			$smarty->force_compile = TRUE;
		} else {
			global $smarty;
		}

		///////////////////////////////////////

		//timezone stuff
		//$tz = GetTimeZone($filter);e
		//$p->tz = $tz;

		$page_size = 10;

		$start = 0;

		$sort = 'p.created_date DESC';

		$page = 'LIMIT '.$start.','.$page_size;

		if (count($filter) > 0) {
			$filter = $p->BuildSearchString($filter);
		}

		//lets add our over-write filter
		//$filter .= " AND pr.proposal_revision_status_id IN (". PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL .", ". PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED .", ". PROPOSAL_REVISION_STATUS_WAITING_APPROVAL .") ";
		$filter = " AND p.proposal_status_id = ". PROPOSAL_STATUS_WON;

		$date = date("Y-m-d");
		$year_month = date("Y-m");
		$year = date("Y");

		//PAST 24 HOURS
		$end_date  = $date ." 23:59:59";
		$start_date = $date ." 00:00:00";

		$row = array(
		'period' => 'Past 24 Hours',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);

		$data[] = $row;

		//7 DAYS

		$start_date = date("Y-m-d H:i:s", strtotime("-1 week"));

		$row = array(
		'period' => 'Past 7 Days',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);
		$data[] = $row;

		//30 DAYS
		$start_date = date("Y-m-d H:i:s", strtotime("-30 days"));

		$row = array(
		'period' => 'Past 30 Days',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);
		$data[] = $row;


		//MTD
		$start_date = $year_month ."-01 00:00:00";

		$row = array(
		'period' => 'Month to Date',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);
		$data[] = $row;


		//YTD
		$start_date = $year ."-01-01 00:00:00";

		$row = array(
		'period' => 'Year To Date',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalCountBySampleType($filter .' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);
		$data[] = $row;


		//number of rows in the current page

		$header[0]['field'] = 'p.proposal_name';
		$header[0]['title'] = 'Time Period';

		$header[1]['field'] = 'account_name';
		$header[1]['title'] = 'Unique Proposals';

		$header[2]['field'] = 'pr.max_amount';
		$header[2]['title'] = 'Consumer General';

		$header[3]['field'] = 'prs.proposal_revision_status_description';
		$header[3]['title'] = 'Medical Professionals';

		$header[4]['field'] = 'prs.proposal_revision_status_description';
		$header[4]['title'] = 'Consumer (Medical)';

		$header[5]['field'] = 'prs.proposal_revision_status_description';
		$header[5]['title'] = 'B2B IT';

		$header[6]['field'] = 'prs.proposal_revision_status_description';
		$header[6]['title'] = 'Consumer (Youth)';

		$header[7]['field'] = 'prs.proposal_revision_status_description';
		$header[7]['title'] = 'B2B General';

		$header[8]['field'] = 'prs.proposal_revision_status_description';
		$header[8]['title'] = 'Others (Not Given)';


		$smarty->assign('header',$header);
		$smarty->assign('data', $data);
		$smarty->assign('lang', $o['lbl']);
		$smarty->assign('l', $l);
		$smarty->assign('meta',$meta);

		return $smarty->fetch('app/pgen/ptl_proposal_sample_aging_count_won.tpl');
	}


	/**
* GetRecentProposalDetailedPortlet()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Mar 16 14:31:58 PST 2006
*/
	function GetRecentProposalDetailedPortlet($filter, $meta = array())
	{
		global $userRights, $status_titles, $encryption; //, $servername; //, $smarty;
		$p = new proposalDB();

		///////////////////////////////////////
		if ($_SESSION['admin_id'] == SYSTEM_USER) {

			global $cfg, $servername;
			$smarty = new Smarty();

			$smarty->template_dir = '/var/www/smarty/' . $servername . '/templates';
			$smarty->compile_dir = '/var/www/smarty/' . $servername . '/templates_c';
			$smarty->cache_dir = '/var/www/smarty/' . $servername . '/cache';
			$smarty->config_dir = '/var/www/smarty/' . $servername . '/configs';


			$smarty->plugins_dir = array('plugins', $cfg['base_dir'].'/include/smarty_plugins');

			//require_once ($smarty->_get_plugin_filepath('modifier', 'url_encrypt'));

			//$smarty->register_modifier('url_encrypt', 'url_encrypt');


			$smarty->compile_check = TRUE;
			$smarty->force_compile = TRUE;
		} else {
			global $smarty;
		}

		///////////////////////////////////////

		//timezone stuff
		//$tz = GetTimeZone($filter);e
		//$p->tz = $tz;

		$page_size = 10;

		$start = 0;

		$sort = 'p.created_date DESC, pr.max_amount DESC';

		$page = 'LIMIT '.$start.','.$page_size;

		if (count($filter) > 0) {
			$filter = $p->BuildSearchString($filter);
		}

		$list = PrepareSmartyArray($p->GetListGroupedBy($filter, $page, $sort));
		//number of rows in the current page

		$header[0]['field'] = 'p.proposal_name';
		$header[0]['title'] = 'Proposal<br>Name';

		$header[1]['field'] = 'account_id';
		$header[1]['title'] = 'Account<br>ID';

		$header[2]['field'] = 'account_name';
		$header[2]['title'] = 'Account<br>Name';

		$header[3]['field'] = 'pr.proposal_type_id';
		$header[3]['title'] = 'Proposal Type';

		$header[4]['field'] = 'pr.pricing_type_id';
		$header[4]['title'] = 'Pricing Type';

		//   $header[3]['field'] = 'c.country_description';
		//   $header[3]['title'] = 'Country';

		$header[5]['field'] = 'ae_name';
		$header[5]['title'] = 'Account<br>Executive';

		$header[6]['field'] = 'am_name';
		$header[6]['title'] = 'Account<br>Manager';


		//   $header[6]['field'] = 'fg.functional_group_description';
		//   $header[6]['title'] = 'Department';

		$header[7]['field'] = 'fg_user_name';
		$header[7]['title'] = 'Proposal Writer';

		$header[8]['field'] = 'p.proposal_date';
		$header[8]['title'] = 'Proposal<br>Date';

		$header[9]['field'] = 'p.proposal_id';
		$header[9]['title'] = 'Proposal Number';

		$header[10]['field'] = 'prst.proposal_revision_sample_type_id';
		$header[10]['title'] = 'Sample Type';

		//   $header[10]['field'] = 'pr.min_proposal_value';
		//   $header[10]['title'] = 'Min Proposal<br>Value';

		$header[11]['field'] = 'pr.max_amount';
		$header[11]['title'] = 'Max Proposal<br>Value';

		$header[12]['field'] = 'ps.proposal_status_description';
		$header[12]['title'] = 'Status';

		$header[13]['field'] = 'prs.proposal_revision_status_description';
		$header[13]['title'] = 'Revision Status';



		$smarty->assign('header',$header);
		$smarty->assign('list', $list);
		$smarty->assign('lang', $o['lbl']);
		$smarty->assign('l', $l);
		$smarty->assign('meta',$meta);

		return $smarty->fetch('app/pgen/ptl_proposal_list_detail.tpl');
	}

	/**
* GetSampleTypePieGraph()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 24 10:34:07 PST 2006
*/
	function GetSampleTypePieGraph($filter, $meta = array())
	{
		global $userRights, $status_titles, $encryption; //, $servername; //, $smarty;
		$p = new proposalDB();

		$date = date("Y-m-d");
		$year_month = date("Y-m");
		$year = date("Y");

		$end_date  = $date ." 23:59:59";

		//30 DAYS
		$start_date = date("Y-m-d H:i:s", strtotime("-30 days"));

		$data[0] = $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date);
		$data[1] = $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date);
		$data[2] = $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date);
		$data[3] = $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date);
		$data[4] = $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date);
		$data[5] = $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date);
		$data[6] = $p->GetProposalCountBySampleType(' AND prst.sample_type_id = 0 ', $start_date, $end_date);

		include ("misc/jpgraph_pie.php");

		$graph = new PieGraph(600,400,"auto");
		$graph->SetShadow();

		$graph->title->Set("RFP Breakdown By Count");
		$graph->title->SetFont(FF_FONT1,FS_BOLD);

		$p1 = new PiePlot($data);
		$p1->SetLegends(array("Consumer General", "Medical Profesionals", "Consumer Medical", "B2B IT", "Consumer Youth", "B2B General", "Other"));
		$p1->SetSliceColors(array_reverse(array('blue','yellow','brown', 'orange', 'pink', 'green', 'white')));
		$p1->SetCenter(0.4);

		$graph->Add($p1);
		$graph->Stroke();


	}

	/**
* GetProposalSampleAgingValuePortlet()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 24 08:48:52 PST 2006
*/
	function GetProposalSampleAgingValuePortlet($filter, $meta = array())
	{
		global $userRights, $status_titles, $encryption; //, $servername; //, $smarty;
		$p = new proposalDB();

		///////////////////////////////////////
		if ($_SESSION['admin_id'] == SYSTEM_USER) {

			global $cfg, $servername;
			$smarty = new Smarty();

			$smarty->template_dir = '/var/www/smarty/' . $servername . '/templates';
			$smarty->compile_dir = '/var/www/smarty/' . $servername . '/templates_c';
			$smarty->cache_dir = '/var/www/smarty/' . $servername . '/cache';
			$smarty->config_dir = '/var/www/smarty/' . $servername . '/configs';


			$smarty->plugins_dir = array('plugins', $cfg['base_dir'].'/include/smarty_plugins');

			//require_once ($smarty->_get_plugin_filepath('modifier', 'url_encrypt'));

			//$smarty->register_modifier('url_encrypt', 'url_encrypt');


			$smarty->compile_check = TRUE;
			$smarty->force_compile = TRUE;
		} else {
			global $smarty;
		}

		///////////////////////////////////////

		//timezone stuff
		//$tz = GetTimeZone($filter);e
		//$p->tz = $tz;

		$page_size = 10;

		$start = 0;

		$sort = 'p.created_date DESC';

		$page = 'LIMIT '.$start.','.$page_size;

		if (count($filter) > 0) {
			$filter = $p->BuildSearchString($filter);
		}

		//lets add our over-write filter
		//$filter .= " AND pr.proposal_revision_status_id IN (". PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL .", ". PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED .", ". PROPOSAL_REVISION_STATUS_WAITING_APPROVAL .") ";

		$date = date("Y-m-d");
		$year_month = date("Y-m");
		$year = date("Y");

		//PAST 24 HOURS
		$end_date  = $date ." 23:59:59";
		$start_date = $date ." 00:00:00";

		$row = array(
		'period' => 'Past 24 Hours',
		'proposal_value' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);

		$data[] = $row;

		//7 DAYS

		$start_date = date("Y-m-d H:i:s", strtotime("-1 week"));

		$row = array(
		'period' => 'Past 7 Days',
		'proposal_value' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);
		$data[] = $row;

		//30 DAYS
		$start_date = date("Y-m-d H:i:s", strtotime("-30 days"));

		$row = array(
		'period' => 'Past 30 Days',
		'proposal_value' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);
		$data[] = $row;


		//MTD
		$start_date = $year_month ."-01 00:00:00";

		$row = array(
		'period' => 'Month to Date',
		'proposal_value' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);
		$data[] = $row;


		//YTD
		$start_date = $year ."-01-01 00:00:00";

		$row = array(
		'period' => 'Year To Date',
		'proposal_value' => $p->GetProposalRevisionValue($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalValueBySampleType(' AND prst.sample_type_id = 0 ', $start_date, $end_date)
		);
		$data[] = $row;


		//number of rows in the current page

		$header[0]['field'] = 'p.proposal_name';
		$header[0]['title'] = 'Time Period';

		$header[1]['field'] = 'account_name';
		$header[1]['title'] = 'Unique Proposals';

		$header[2]['field'] = 'pr.max_amount';
		$header[2]['title'] = 'Consumer General';

		$header[3]['field'] = 'prs.proposal_revision_status_description';
		$header[3]['title'] = 'Medical Professionals';

		$header[4]['field'] = 'prs.proposal_revision_status_description';
		$header[4]['title'] = 'Consumer (Medical)';

		$header[5]['field'] = 'prs.proposal_revision_status_description';
		$header[5]['title'] = 'B2B IT';

		$header[6]['field'] = 'prs.proposal_revision_status_description';
		$header[6]['title'] = 'Consumer (Youth)';

		$header[7]['field'] = 'prs.proposal_revision_status_description';
		$header[7]['title'] = 'B2B General';

		$header[8]['field'] = 'prs.proposal_revision_status_description';
		$header[8]['title'] = 'Others (Not Given)';


		$smarty->assign('header',$header);
		$smarty->assign('data', $data);
		$smarty->assign('lang', $o['lbl']);
		$smarty->assign('l', $l);
		$smarty->assign('meta',$meta);

		return $smarty->fetch('app/pgen/ptl_proposal_sample_aging_value.tpl');
	}

	/**
* GetProposalSampleAgingCountPortlet()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 24 08:48:52 PST 2006
*/
	function GetProposalSampleAgingCountPortlet($filter, $meta = array())
	{
		global $userRights, $status_titles, $encryption; //, $servername; //, $smarty;
		$p = new proposalDB();

		///////////////////////////////////////
		if ($_SESSION['admin_id'] == SYSTEM_USER) {

			global $cfg, $servername;
			$smarty = new Smarty();

			$smarty->template_dir = '/var/www/smarty/' . $servername . '/templates';
			$smarty->compile_dir = '/var/www/smarty/' . $servername . '/templates_c';
			$smarty->cache_dir = '/var/www/smarty/' . $servername . '/cache';
			$smarty->config_dir = '/var/www/smarty/' . $servername . '/configs';


			$smarty->plugins_dir = array('plugins', $cfg['base_dir'].'/include/smarty_plugins');

			//require_once ($smarty->_get_plugin_filepath('modifier', 'url_encrypt'));

			//$smarty->register_modifier('url_encrypt', 'url_encrypt');


			$smarty->compile_check = TRUE;
			$smarty->force_compile = TRUE;
		} else {
			global $smarty;
		}

		///////////////////////////////////////

		//timezone stuff
		//$tz = GetTimeZone($filter);e
		//$p->tz = $tz;

		$page_size = 10;

		$start = 0;

		$sort = 'p.created_date DESC';

		$page = 'LIMIT '.$start.','.$page_size;

		if (count($filter) > 0) {
			$filter = $p->BuildSearchString($filter);
		}

		//lets add our over-write filter
		//$filter .= " AND pr.proposal_revision_status_id IN (". PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL .", ". PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED .", ". PROPOSAL_REVISION_STATUS_WAITING_APPROVAL .") ";

		$date = date("Y-m-d");
		$year_month = date("Y-m");
		$year = date("Y");

		//PAST 24 HOURS
		$end_date  = $date ." 23:59:59";
		$start_date = $date ." 00:00:00";

		$row = array(
		'period' => 'Past 24 Hours',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = 0 ', $start_date, $end_date),
		);

		$data[] = $row;

		//7 DAYS

		$start_date = date("Y-m-d H:i:s", strtotime("-1 week"));

		$row = array(
		'period' => 'Past 7 Days',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = 0 ', $start_date, $end_date),
		);
		$data[] = $row;

		//30 DAYS
		$start_date = date("Y-m-d H:i:s", strtotime("-30 days"));

		$row = array(
		'period' => 'Past 30 Days',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = 0 ', $start_date, $end_date),			);
		$data[] = $row;


		//MTD
		$start_date = $year_month ."-01 00:00:00";

		$row = array(
		'period' => 'Month to Date',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = 0 ', $start_date, $end_date),			);
		$data[] = $row;


		//YTD
		$start_date = $year ."-01-01 00:00:00";

		$row = array(
		'period' => 'Year To Date',
		'proposal' => $p->GetProposalCount($filter, $start_date, $end_date),
		'consumer_general' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_GENERAL, $start_date, $end_date),
		'medical_prof' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_MEDICAL_PROF, $start_date, $end_date),
		'consumer_medical' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_MEDICAL, $start_date, $end_date),
		'b2bit' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2BIT, $start_date, $end_date),
		'consumer_youth' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_CONSUMER_YOUTH, $start_date, $end_date),
		'b2bgeneral' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = '. SAMPLE_TYPE_B2B_GENERAL, $start_date, $end_date),
		'other' => $p->GetProposalCountBySampleType(' AND prst.sample_type_id = 0 ', $start_date, $end_date),
		);
		$data[] = $row;


		//number of rows in the current page

		$header[0]['field'] = 'p.proposal_name';
		$header[0]['title'] = 'Time Period';

		$header[1]['field'] = 'account_name';
		$header[1]['title'] = 'Unique Proposals';

		$header[2]['field'] = 'pr.max_amount';
		$header[2]['title'] = 'Consumer General';

		$header[3]['field'] = 'prs.proposal_revision_status_description';
		$header[3]['title'] = 'Medical Professionals';

		$header[4]['field'] = 'prs.proposal_revision_status_description';
		$header[4]['title'] = 'Consumer (Medical)';

		$header[5]['field'] = 'prs.proposal_revision_status_description';
		$header[5]['title'] = 'B2B IT';

		$header[6]['field'] = 'prs.proposal_revision_status_description';
		$header[6]['title'] = 'Consumer (Youth)';

		$header[7]['field'] = 'prs.proposal_revision_status_description';
		$header[7]['title'] = 'B2B General';

		$header[8]['field'] = 'prs.proposal_revision_status_description';
		$header[8]['title'] = 'Others (Not Given)';


		$smarty->assign('header',$header);
		$smarty->assign('data', $data);
		$smarty->assign('lang', $o['lbl']);
		$smarty->assign('l', $l);
		$smarty->assign('meta',$meta);

		return $smarty->fetch('app/pgen/ptl_proposal_sample_aging_count.tpl');
	}


	/**
	* CalculateRevisionOptionsTotal()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 03 09:14:51 PST 2006
	*/
	function CalculateRevisionOptionsTotal($proposal_id, $proposal_revision_id)
	{
		$p = new proposalDB();
		$p->SetProposalId($proposal_id);
		$p->SetRevisionId($proposal_revision_id);

		$proposal = $p->GetBasicDetail();
		$revision = $p->GetRevisionDetail();

		$rs_options = $p->GetRevisionOptions();

		while ($r_option = mysql_fetch_assoc($rs_options)) {
			$proposal['country_list'] .= $r_option['country_description'] . ", ";
			$proposal['sample_source_list'] .= $r_option['study_datasource_description'] . ", ";
			$proposal['questions_programmed'] = $r_option['questions_programmed'];
			$proposal['questions_per_interview'] = $r_option['questions_per_interview'];
			$proposal['programming_type_description'] = $list['study_programming_type'][$r_option['programming']];

			if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] >= 1) {
				$country[$r_option['option_number']] = $r_option['country_description'];
				$incidence[$r_option['option_number']] = $r_option['incidence_rate'];
				$completes[$r_option['option_number']] = $r_option['completes'];
				$qlength[$r_option['option_number']] = $r_option['questions_programmed'];
				$p_sub_group[$r_option['option_number']] = $r_option['sub_group_description'];
			} else {
				$country[$r_option['option_number']][$r_option['sort_order']] = $r_option['country_description'];
				$incidence[$r_option['option_number']][$r_option['sort_order']] = $r_option['incidence_rate'];
				$completes[$r_option['option_number']][$r_option['sort_order']] = $r_option['completes'];
				$qlength[$r_option['option_number']][$r_option['sort_order']] = $r_option['questions_programmed'];
				$p_sub_group[$r_option['option_number']][$r_option['sort_order']] = $r_option['sub_group_description'];
				$t_completes[$r_option['option_number']] += $r_option['completes'];
			}
		}


		if ($revision['pricing_type_id'] == PRICING_TYPE_CUSTOM) {

			$rs = $p->GetRevisionCustomPricing();

			if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] >= 1) {

				while ($r = mysql_fetch_assoc($rs)) {
					switch ($r['pricing_item_group_id']) {
						case PRICING_GROUP_SETUP:
							$setup[$r['option_number']] = $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
						case PRICING_GROUP_PANEL:
							$panel[$r['option_number']] = $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
						case PRICING_GROUP_HOSTING:
							$hosting[$r['option_number']] = $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
						case PRICING_GROUP_DP:
							$dp[$r['option_number']] = $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
					}
				}

				for ($i = 1; $i <= $revision['number_of_options']; $i++) {
					$cpc[$i] = $total[$i] / $completes[$i];
					$panel_cpc[$i] = $panel[$i] / $completes[$i];

					//set option total
					$p->SetOptionTotalByOptionCountry($i, 1, $total[$i]);

					//set option panel total
					$p->SetOptionPanelTotalByOptionCountry($i, 1, $panel[$i]);

					$p->SetOptionCpcByOptionCountry($i, 1, $cpc[$i]);
				}


			} else {

				while ($r = mysql_fetch_assoc($rs)) {
					switch ($r['pricing_item_group_id']) {
						case PRICING_GROUP_SETUP:
							$setup[$r['option_number']][$r['sort_order']] = $r['amount'];
							$total[$r['option_number']][$r['sort_order']] += $r['amount'];
							$t_setup[$r['option_number']] += $r['amount'];
							break;
						case PRICING_GROUP_PANEL:
							$panel[$r['option_number']][$r['sort_order']] = $r['amount'];
							$total[$r['option_number']][$r['sort_order']] += $r['amount'];
							$t_panel[$r['option_number']] += $r['amount'];
							break;
						case PRICING_GROUP_HOSTING:
							$hosting[$r['option_number']][$r['sort_order']] = $r['amount'];
							$total[$r['option_number']][$r['sort_order']] += $r['amount'];
							$t_hosting[$r['option_number']] += $r['amount'];
							break;
						case PRICING_GROUP_DP:
							$dp[$r['option_number']][$r['sort_order']] = $r['amount'];
							$total[$r['option_number']][$r['sort_order']] += $r['amount'];
							$t_dp[$r['option_number']] += $r['amount'];
							break;
					}
				}

				for ($o = 1; $o <= $revision['number_of_options']; $o++) {
					for ($c = 1; $c <= $revision['number_of_countries']; $c++) {
						$cpc[$o][$c] = $total[$o][$c] / $completes[$o][$c];
						$t_total[$o] += $total[$o][$c];$p->SetOptionCpcByOptionCountry($o, $c, $cpc[$o][$c]);
						$t_cpc[$o] = $t_total[$o] / $t_completes[$o];
						$panel_cpc[$o][$c] = $panel[$o][$c] / $completes[$o][$c];

						//set option total
						$p->SetOptionTotalByOptionCountry($o, $c, $total[$o][$c]);

						//set option panel total
						$p->SetOptionPanelTotalByOptionCountry($o, $c, $panel[$o][$c]);

						$p->SetOptionCpcByOptionCountry($o, $c, $cpc[$o][$c]);

					}
				}

			}


		} else {

			$rs = $p->GetRevisionBudgetLineItemSummary($proposal_revision_id);

			if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] >= 1) {

				while ($r = mysql_fetch_assoc($rs)) {
					switch ($r['proposal_budget_line_item_id']) {
						//            	case PROPOSAL_BUDGET_LICENSE:
						//            	  $license[$r['option_number']]	= $r['amount'];
						//            	  $total[$r['option_number']] += $r['amount'];
						//            	  break;
						case PROPOSAL_BUDGET_TOTAL_PROJECT_SETUP:
							$setup[$r['option_number']]	= $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
						case PROPOSAL_BUDGET_TOTAL_HOSTING:
							$hosting[$r['option_number']]	= $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
						case PROPOSAL_BUDGET_TOTAL_PANEL:
							$panel[$r['option_number']]	= $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
						case PROPOSAL_BUDGET_TOTAL_DP:
							$dp[$r['option_number']]	= $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
						default:
							break;
					}
					$options[$r['sort_order']][$r['option_number']] = $r;
				}

				for ($i = 1; $i <= $revision['number_of_options']; $i++) {
					$cpc[$i] = $total[$i] / $completes[$i];
					$panel_cpc[$i] = $panel[$i] / $completes[$i];

					//set option total
					$p->SetOptionTotalByOptionCountry($i, 1, $total[$i]);

					//set option panel total
					$p->SetOptionPanelTotalByOptionCountry($i, 1, $panel[$i]);

					$p->SetOptionCpcByOptionCountry($i, 1, $cpc[$i]);

				}

			} else {

				while ($r = mysql_fetch_assoc($rs)) {
					switch ($r['proposal_budget_line_item_id']) {
						//            	case PROPOSAL_BUDGET_LICENSE:
						//            	  $license[$r['option_number']][$r['o_sort_order']] = $r['amount'];
						//            	  $total[$r['option_number']][$r['o_sort_order']] += $r['amount'];
						//            	  break;
						case PROPOSAL_BUDGET_TOTAL_PROJECT_SETUP:
							$setup[$r['option_number']][$r['o_sort_order']]	= $r['amount'];
							$total[$r['option_number']][$r['o_sort_order']] += $r['amount'];
							$t_setup[$r['option_number']] += $r['amount'];
							break;
						case PROPOSAL_BUDGET_TOTAL_HOSTING:
							$hosting[$r['option_number']][$r['o_sort_order']]	= $r['amount'];
							$total[$r['option_number']][$r['o_sort_order']] += $r['amount'];
							$t_hosting[$r['option_number']] += $r['amount'];
							break;
						case PROPOSAL_BUDGET_TOTAL_PANEL:
							$panel[$r['option_number']][$r['o_sort_order']]	= $r['amount'];
							$total[$r['option_number']][$r['o_sort_order']] += $r['amount'];
							$t_panel[$r['option_number']] += $r['amount'];
							break;
						case PROPOSAL_BUDGET_TOTAL_DP:
							$dp[$r['option_number']][$r['o_sort_order']]	= $r['amount'];
							$total[$r['option_number']][$r['o_sort_order']] += $r['amount'];
							$t_dp[$r['option_number']] += $r['amount'];
							break;
						default:
							break;
					}
					$options[$r['option_number']][$r['sort_order']][] = $r;
				}

				for ($o = 1; $o <= $revision['number_of_options']; $o++) {
					for ($c = 1; $c <= $revision['number_of_countries']; $c++) {
						$cpc[$o][$c] = $total[$o][$c] / $completes[$o][$c];
						$t_total[$o] += $total[$o][$c];
						$t_cpc[$o] = $t_total[$o] / $t_completes[$o];
						$panel_cpc[$o][$c] = $panel[$o][$c] / $completes[$o][$c];

						$p->SetOptionCpcByOptionCountry($o, $c, $cpc[$o][$c]);
						//set option total
						$p->SetOptionTotalByOptionCountry($o, $c, $total[$o][$c]);

						//set option panel total
						$p->SetOptionPanelTotalByOptionCountry($o, $c, $panel[$o][$c]);

					}
				}

			}
		}

		return true;

	}

	/**
	* MigrateTotalCost()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 03 09:49:18 PST 2006
	*/
	function MigrateTotalCost()
	{
		if ($_SESSION['admin_id'] != 10312) {
			echo "Not Authorized to Run This Script";
			return false;
		}

		$p = new proposalDB();

		$rs = $p->GetActiveRevisions();

		while ($r = mysql_fetch_assoc($rs)) {
			$this->CalculateRevisionOptionsTotal($r['proposal_id'], $r['proposal_revision_id']);
		}

		return true;
	}

	/**
	* DisplayIncidenceScatterPlot()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Mar 02 14:41:09 PST 2006
	*/
	function DisplayIncidenceScatterPlot($o)
	{
		global $smarty;

		$p = new proposalDB();

		if (isset($_SESSION['pgen_summary_filter'])) {
			$filter = $_SESSION['pgen_summary_filter'];
			//unset($_SESSION['pgen_summary_filter']);
		}

		$data_all = $_SESSION['data_all'];
		$data_win = $_SESSION['data_win'];
		$win_rate = $_SESSION['win_rate'];

		session_write_close();


		if (!$data_all) DisplayNoDataImage();




		switch ($o['graph_type']) {
			case 'scatter':

				if (!isset($o['_jpg_csimd']))
				echo "<html><head><script src='/js/pgen.js'></script>";
				$this->DisplayScatterPlot($data_all, $data_win);

				break;

			case 'line':
				$this->DisplayLinePlot($win_rate, $data_all['bar']['x']);
				break;
			default:
				$this->DisplayBarPlot($data_all);
				break;
		}

	}

	/**
	* DisplayScatterPlot()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Mar 06 11:49:07 PST 2006
	*/
	function DisplayScatterPlot($data_all, $data_win)
	{
		$meta = $_SESSION['pgen_summary_graph'];
		$title = $meta['graph_title'];

		//incidence vs cpc scatter
		$g = new Graph(600,600,"auto");
		$g->SetScale("linlin");

		$g->img->SetMargin(40,40,40,40);
		$g->SetShadow();

		$g->title->Set($title ." vs CPC");
		$g->title->SetFont(FF_FONT1,FS_BOLD);
		$g->xaxis->title->Set($title);
		$g->yaxis->title->Set("CPC");

		//all proposals scatter plot
		$sp1 = new ScatterPlot($data_all['scatter']['y'],$data_all['scatter']['x']);
		$sp1->mark->SetFillColor("blue");
		$sp1->SetCSIMTargets($data_all['scatter']['csim']['url'], $data_all['scatter']['csim']['alt']);
		$g->Add($sp1);


		if ($data_win) {
			$sp2 = new ScatterPlot($data_win['scatter']['y'],$data_win['scatter']['x']);
			$sp2->mark->SetFillColor("red");
			$sp2->mark->SetType(MARK_DIAMOND);
			$sp2->mark->SetSize(10);
			$sp2->mark->SetColor('red');
			$sp2->SetCSIMTargets($data_win['scatter']['csim']['url'], $data_win['scatter']['csim']['alt']);
			$g->Add($sp2);
		}

		$g->StrokeCSIM();

	}

	/**
	* DisplayBarPlot()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Mar 06 11:52:18 PST 2006
	*/
	function DisplayBarPlot($data_all)
	{
		$meta = $_SESSION['pgen_summary_graph'];
		$start = $meta['start'];
		$end   = $meta['end'];
		$range = $meta['range'];


		$g = new Graph(600, 600,"auto");
		$g->SetScale("intint", 0, 0, $start, ($end + $range));

		$g->img->SetMargin(40,40,40,40);
		$g->SetShadow();

		$g->title->Set($meta['graph_title'] ." Volume");
		$g->title->SetFont(FF_FONT1,FS_BOLD);
		$g->xaxis->title->Set($meta['graph_title']);
		$g->yaxis->title->Set("# of Proposals");
		$g->xaxis->scale->ticks->Set($range);

		//$gb_incidence->xaxis->scale->ticks->Set($range['incidence']);
		// Create the linear plot
		$bplot = new BarPlot($data_all['bar']['y'], $data_all['bar']['x']);

		$bplot->SetFillColor('blue');
		$bplot->SetWidth($range);
		$bplot->value->Show();
		$g->Add($bplot);


		$g->Stroke();
	}

	/**
	* DisplayLinePlot()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Mar 06 15:29:37 PST 2006
	*/
	function DisplayLinePlot($y, $x)
	{
		$meta = $_SESSION['pgen_summary_graph'];


		$g = new Graph(600, 600,"auto");
		$g->SetScale("textint", 0, 0, 0, 0);

		$g->img->SetMargin(40,40,40,40);
		$g->SetShadow();

		$g->title->Set($meta['graph_title']. " Win Rate");
		$g->title->SetFont(FF_FONT1,FS_BOLD);
		$g->xaxis->title->Set($meta['graph_title']);
		$g->yaxis->title->Set("%");
		$g->xaxis->scale->ticks->Set($meta['range']);
		$g->xaxis->SetTickLabels($x);

		//$gb_incidence->xaxis->scale->ticks->Set($range['incidence']);
		// Create the linear plot
		$bplot = new LinePlot($y);

		//$bplot->SetFillColor('blue');
		//$bplot->SetWidth($range);
		$bplot->value->Show();
		$g->Add($bplot);

		$g->Stroke();
	}


	/**
	* DisplaySummaryReport()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Mar 02 15:22:11 PST 2006
	*/
	function DisplaySummaryReport($o)
	{
		global $smarty;

		$c = new commonDB();
		$p = new proposalDB();
		$s = new studyDB();

		$__default_meta = array('start' => 0, 'end' => 100, 'range' => 10, 'x_axix_type' => 'incidence_rate', 'graph_title' => 'Incidence', 'group_by' => 'none');
		$meta = array();

		//if we are running a search
		if ($o['do_search'] == 1) {
			//set the meta values according to the search criteria
			switch ($o['x_axix_type']) {
				case 'incidence':
					$graph_title = 'Incidence';
					$x_axix_type = 'incidence_rate';
					break;
				case 'qlength':
					$graph_title = 'Questionair Length';
					$x_axix_type = 'questions_programmed';
					break;
				case 'completes':
					$graph_title = 'Completes';
					$x_axix_type = 'completes';
					break;
				case 'discounts':
					$graph_title = 'Discounts';
					break;
				default:
					echo "Default Case";
					break;
			}

			//set the default range to 0 to 100 if its not set
			if ($o[$x_axix_type ."_begin"] == '') {
				$o[$x_axix_type ."_begin"] = 0;
			}

			if ($o[$x_axix_type ."_end"] == '') {
				$o[$x_axix_type ."_end"] = 100;
			}


			$filter = $p->buildSearchString($o);
			$_SESSION['pgen_summary_filter'] = $filter;
			$o['so'] = $o;

			$_SESSION['pgen_summary_search'] = $o;



			$start = $o[$x_axix_type .'_begin'];
			$end   = $o[$x_axix_type .'_end'];

			//calculate the range for bars by using max and min values,
			$range = round(($end - $start) / NUMBER_OF_BARS_PER_GRAPH);

			$accepted_ranges = array(1, 2, 5, 10, 25, 50, 100, 250, 500, 1000);

			for ($i=0; $i < count($accepted_ranges); $i++) {
				if ($range <= $accepted_ranges[$i]) {
					$range = $accepted_ranges[$i];
					break;
				}
			}

			$data_type = 'all';

			if (isset($o['data_type']) && $o['data_type'] == 'unique')
			$data_type = 'unique';

			$meta = array('start' => $start, 'end' => $end, 'range' => $range, 'graph_title' => $graph_title, 'x_axix_type' => $x_axix_type, 'data_type' => $data_type);

			if ($o['panel_cpc_begin'] != '' && $o['panel_cpc_end'] != '')
			$having = " AND panel_cpc BETWEEN '". $o['panel_cpc_begin'] ."' AND '". $o['panel_cpc_end'] ."' ";

		} else {

			$o['sf_country_code'] = 'pro.country_code';
			$o['sc_country_code'] = 'USA';
			$o['dtc_proposal_date'] = 'p.proposal_date';
			$o['proposal_date_begin'] = date("Y-m-d", strtotime("-30 day"));
			$o['proposal_date_end'] = date("Y-m-d");

			//set the default filter so that all x axis items are in the range of 0 to 100
			$filter = $p->buildSearchString($o);
			$_SESSION['pgen_summary_filter'] = $filter;
			$o['so'] = $o;

			$_SESSION['pgen_summary_search'] = $o;

			$having = '';
		}

		$meta = array_merge($__default_meta, $meta);

		$_SESSION['pgen_summary_graph'] = $meta;

		//prepare data
		$all_filter = $filter . " AND pr.proposal_revision_status_id != ". PROPOSAL_REVISION_STATUS_CLIENT_ACCEPTED;
		$data_all = $this->GetOptionData($all_filter, $meta, $having);
		$_SESSION['data_all'] = $data_all;

		//prepare win data
		$win_filter = $filter . " AND pr.proposal_revision_status_id = ". PROPOSAL_REVISION_STATUS_CLIENT_ACCEPTED;
		$data_win = $this->GetOptionData($win_filter, $meta, $having);
		$_SESSION['data_win'] = $data_win;

		$win_rate = $this->GetWinRate($data_all['bar']['y'], $data_win['bar']['y']);
		$_SESSION['win_rate'] = $win_rate;


		$l['region']     = CreateSmartyArray($c->GetCustomRegions(),'region_id','region_name');
		$l['country']    = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
		$l['department'] = CreateSmartyArray($c->GetSourceDepartments(), 'functional_group_id', 'functional_group_description');
		//$l['fg_members'] = CreateSmartyArray($c->GetSourceDepartmentMembers($r['functional_group_id']), 'login', 'name');
		$l['p_writer']   = CreateSmartyArray($c->GetUsersByRoleId(ROLE_PROPOSAL_WRITER), 'login', 'name');
		$l['f_assesor']  = CreateSmartyArray($c->GetUsersByRoleId(ROLE_FEASIBILITY_ASSESSOR), 'login', 'name');
		$l['p_type']     = CreateSmartyArray($p->GetProposalTypeList(), 'proposal_type_id', 'proposal_type_description');
		$l['p_status']   = CreateSmartyArray($p->GetProposalStatusList(), 'proposal_status_id', 'proposal_status_description');
		$l['am']  = CreateSmartyArray($c->GetUsersByRoleId(ROLE_ACCOUNT_MANAGER), 'login', 'name');
		$l['ae']  = CreateSmartyArray($c->GetUsersByRoleId(ROLE_ACCOUNT_EXECUTIVE), 'login', 'name');
		$l['pricing_type'] = CreateSmartyArray($p->GetPricingTypes(), 'pricing_type_id', 'pricing_type_description');
		$l['sample_type']  = CreateSmartyArray($p->GetSampleTypes(), 'sample_type_id', 'sample_type_description');
		$l['revision_status'] = CreateSmartyArray($p->GetRevisionStatusList(), 'proposal_revision_status_id', 'proposal_revision_status_description');
		$l['study_datasource'] = $s->getDataSources();
		$l['x_axix_type'] = array('incidence' => 'By Incidence', 'qlength' => 'By Questionnaire Length', 'completes' => 'By Completes');
		$l['data_type'] = array('all' => 'All Revisions', 'unique' => 'Unique Proposals');

		$smarty->assign('l', $l);
		$smarty->assign('meta', $o);

		DisplayHeader("Proposal", "pgen");

		$smarty->display('app/pgen/vr_summary_report.tpl');

		DisplayFooter();

		return true;

	}


	/**
	* GetOptionData()
	*
	* @param $filter int SQL filter to plug in to the query
	* @param $array_type int the data type to be returned
	* @return mixed false if no data was found an array if data is found
	* @access public
	* @since  - Mon Mar 06 09:17:50 PST 2006
	*/
	function GetOptionData($filter, $meta = array(), $having = '')
	{
		$p = new proposalDB();

		if ($meta['data_type'] == 'all') {
			$rs = $p->GetOptionTotal($filter, $having);
		} else {
			$rs = $p->GetOptionTotalByProposal($filter, $having); //, 'p.proposal_id');
		}

		//if we dont have any data then so no data image
		if ($p->rows == 0) {
			return false;
		}

		$start = $meta['start'];
		$end   = $meta['end'];
		$range = $meta['range'];

		while ($r = mysql_fetch_assoc($rs)) {

			//setting x,y for scatter plot
			$data['scatter']['x'][] = $r[$meta['x_axix_type']];
			$data['scatter']['y'][] = $r['option_cpc'];
			$data['scatter']['csim']['url'][] = "javascript:call_url('/app/pgen/?action=display_revision&proposal_id=". $r['proposal_id'] ."&proposal_revision_id=". $r['proposal_revision_id'] ."')";
			$data['scatter']['csim']['alt'][] = $r['proposal_name'] ." - ". $r['partner_id'] ." - ". $r['company_name'];

			//figure out the range we are using between bars
			$n = (int) $r[$meta['x_axix_type']];
			$i = (int) ($n - $start) / $range;

			//set the incidence volume data
			if (!isset($data['bar']['x'][$i])) {
				//intitial incidence value
				$data['bar']['x'][$i] = 1;
			} else {
				//increment the count
				$data['bar']['x'][$i]++;
			}
		}

		//sort the keys so we have items starting from first point
		ksort($data['bar']['x']);

		//the counter
		$c = 0;

		for ($i=$start; $i <= $end; $i += $range) {

			//the counter is always the loop counter devide by the range
			$c = (int) ($i - $start) / $range;

			//do a zero fill for keys we dont have set but are part of the range
			if (!isset($data['bar']['x'][$c]))
			$data['bar']['x'][$c] = 0;

			//let x be the data counter
			$x[] = $i; // * $range['incidence'];

			//let y be the actual data value
			$y[] = $data['bar']['x'][$c];
		}


		//now put it all back in the array
		$data['bar']['x'] = $x;
		$data['bar']['y'] = $y;

		return $data;
	}

	/**
	* GetWinRate()
	*
	* @param - $all_data array An array of all data
	* @param - $win_data array An Array of win data
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Mar 06 15:56:08 PST 2006
	*/
	function GetWinRate($all_data, $win_data)
	{
		for ($i=0; $i < count($all_data); $i++) {
			@$win_rate[$i] = ($win_data[$i] / $all_data[$i]) * 100;
			//devide the # of i in all by # in win and multiple by 100
		}

		return $win_rate;

	}

	/**
	* GetRecentProposalPortlet()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Mar 16 14:31:58 PST 2006
	*/
	function GetRecentProposalPortlet($filter, $meta = array())
	{
		global $userRights, $status_titles, $encryption; //, $servername; //, $smarty;
		$registry = Zend_Registry::getInstance();
   		$smarty = $registry['smarty'];
   		$list = array();
		
		$p = new proposalDB();

		///////////////////////////////////////

		//timezone stuff
		//$tz = GetTimeZone($filter);e
		//$p->tz = $tz;

		$page_size = 10;

		$start = 0;

		$sort = 'p.created_date DESC';

		$page = 'LIMIT '.$start.','.$page_size;

		if (count($filter) > 0) {
			$filter = $p->BuildSearchString($filter);
		}

		$list = PrepareSmartyArray($p->GetListGroupedBy($filter, $page, $sort));
		//number of rows in the current page
		$meta = array();
		$header[0] = array('field' => 'p.proposal_name', 'title' => 'Name');

		if (isset($meta['hide_account']) && $meta['hide_account'] == 0) {
			$header[] = array('field' => 'account_name', 'title' => 'Account');
		}

		$header[] = array('field' => 'pr.max_amount', 'title' => 'Value');
		$header[] = array('field' => 'prs.proposal_revision_status_description', 'title' => 'Status');
		$header[] = array('field' => 'pr.ae', 'title' => 'AE');
		$header[] = array('field' => 'pr.created_date', 'title' => 'Date');


		$smarty->assign('header',$header);
		$smarty->assign('list', $list);
		//$smarty->assign('lang', $o['lbl']);
		//$smarty->assign('l', $l);
		$smarty->assign('meta',$meta);

		$template = 'quote/ptl_proposal_list.tpl';

		if ($_SESSION['user_type_id'] == USER_TYPE_EXTERNAL) {
			$template = 'app/pgen/ext/ptl_proposal_list.tpl';
		}


		return $smarty->fetch($template);
	}
	
	/**
	* GetWaitingApprovalPortlet()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 24 08:32:27 PST 2006
	*/
	function GetWaitingApprovalPortlet($filter, $meta = array())
	{
		global $userRights, $status_titles, $encryption; //, $servername; //, $smarty;
		$p = new proposalDB();

		$registry = Zend_Registry::getInstance();
   		$smarty = $registry['smarty'];
   		$list = array();
		
		$page_size = 10;

		$start = 0;

		$sort = 'p.created_date DESC';

		$page = 'LIMIT '.$start.','.$page_size;

		if (count($filter) > 0) {
			$filter = $p->BuildSearchString($filter);
		}

		//lets add our over-write filter
		$filter .= " AND pr.proposal_revision_status_id IN (". PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL .", ". PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED .", ". PROPOSAL_REVISION_STATUS_WAITING_APPROVAL .") ";

		$list = PrepareSmartyArray($p->GetListGroupedBy($filter, $page, $sort));
		//number of rows in the current page

		$header[0]['field'] = 'p.proposal_name';
		$header[0]['title'] = 'Name';

		$header[1]['field'] = 'account_name';
		$header[1]['title'] = 'Account';

		$header[2]['field'] = 'pr.max_amount';
		$header[2]['title'] = 'Value';

		$header[3]['field'] = 'pr.ae';
		$header[3]['title'] = 'AE';

		$header[4]['field'] = 'pr.ae';
		$header[4]['title'] = 'AM';

		$header[5]['field'] = 'pr.created_date';
		$header[5]['title'] = 'Age';



		$smarty->assign('header',$header);
		$smarty->assign('list', $list);
		$smarty->assign('lang', $o['lbl']);
		$smarty->assign('l', $l);
		$smarty->assign('meta',$meta);

		return $smarty->fetch('quote/ptl_proposal_list_approval.tpl');
	}

	/**
	* DisplayByAccount()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Sat Jul 23 19:34:51 PDT 2005
	*/
	function DisplayByAccount($o)
	{
		global $smarty;
		$p = new proposalDB();
		$e = new Encryption();

		$filter = '';

		//turn of the filter
		if (isset($o['filter']) && $o['filter'] == 0) {
			unset($_SESSION['ppm_rpt_filter']);
			unset($_SESSION['proposal_date_begin']); // = $o['proposal_date_begin'];
			unset($_SESSION['proposal_date_end']); // = $o['proposal_date_end'];
		}

		//we are getting input form a search
		if ($o['frm'] == 'search') {
			$_SESSION['ppm_rpt_filter'] = $p->BuildSearchString($o);
			$_SESSION['proposal_date_begin'] = $o['proposal_date_begin'];
			$_SESSION['proposal_date_end'] = $o['proposal_date_end'];
			$url = "action=display_by_account";
			$url = $e->Encrypt($url);
			header("Location: ?e=".$url);
		}

		//see if we have a filter set
		if (isset($_SESSION['ppm_rpt_filter']) && $_SESSION['ppm_rpt_filter'] != '') {
			$filter = $_SESSION['ppm_rpt_filter'];
		}



		$list['summary'] = PrepareSmartyArray($p->GetSummaryListByAccount($filter)); //should be StatusSummary

		$rs = $p->GetStatusListByAccount($filter);

		while ($r = mysql_fetch_assoc($rs)) {
			$detail[$r['account_id']][] = $r;
		}

		$o['rpt'] = 'display_by_account';
		$meta = array_merge($_SESSION, $o);
		//print_r($meta);
		$smarty->assign('meta', $meta);

		if (!isset($o['excel'])) {

			DisplayHeader("Proposal Manager", "pgen", $o['action']);

			$smarty->assign('list', $list);
			$smarty->assign('detail_list', $detail);
			$smarty->display('app/pgen/vw_list_by_account.tpl');

			DisplayFooter();
		} else {
			header('Content-type: application/vnd.ms-excel');
			header("Content-disposition: attachment; filename=\"summary_by_account_".date('Ymd').".xls\"");
			$smarty->assign('list', $list);
			$smarty->display('app/pgen/rp_list_by_account.tpl');
		}
	}

	/**
* DisplayByRegion()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sat Jul 23 22:41:25 PDT 2005
*/
	function DisplayByRegion($o)
	{
		global $smarty;
		$p = new proposalDB();
		$e = new Encryption();

		$filter = '';

		//turn of the filter
		if (isset($o['filter']) && $o['filter'] == 0) {
			unset($_SESSION['ppm_rpt_filter']);
			unset($_SESSION['proposal_date_begin']); // = $o['proposal_date_begin'];
			unset($_SESSION['proposal_date_end']); // = $o['proposal_date_end'];
		}

		//we are getting input form a search
		if ($o['frm'] == 'search') {
			$_SESSION['ppm_rpt_filter'] = $p->BuildSearchString($o);
			$_SESSION['proposal_date_begin'] = $o['proposal_date_begin'];
			$_SESSION['proposal_date_end'] = $o['proposal_date_end'];
			$url = "action=display_by_region";
			$url = $e->Encrypt($url);
			header("Location: ?e=".$url);
		}

		//see if we have a filter set
		if (isset($_SESSION['ppm_rpt_filter']) && $_SESSION['ppm_rpt_filter'] != '') {
			$filter = $_SESSION['ppm_rpt_filter'];
		}

		$list['summary'] = PrepareSmartyArray($p->GetSummaryListByRegion($filter)); //should be StatusSummary

		$rs = $p->GetStatusListByCountry($filter);

		while ($r = mysql_fetch_assoc($rs)) {
			$detail[$r['region_id']][] = $r;
		}

		$o['rpt'] = 'display_by_region';
		$meta = array_merge($_SESSION, $o);
		$smarty->assign('meta', $meta);

		if (!isset($o['excel'])) {
			DisplayHeader("Proposal Manager", "pgen");

			$smarty->assign('list', $list);
			$smarty->assign('detail_list', $detail);
			$smarty->display('app/ppm/vw_list_by_region.tpl');
			DisplayFooter();

		} else {
			header('Content-type: application/vnd.ms-excel');
			header("Content-disposition: attachment; filename=\"summary_by_region_".date('Ymd').".xls\"");
			$smarty->assign('list', $list);
			$smarty->display('app/ppm/rp_list_by_region.tpl');
		}
	}

	/**
* DisplayByAe()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sat Jul 23 23:18:16 PDT 2005
*/
	function DisplayByAe($o)
	{
		global $smarty;
		$p = new proposalDB();
		$e = new Encryption();

		$filter = '';

		//turn of the filter
		if (isset($o['filter']) && $o['filter'] == 0) {
			unset($_SESSION['ppm_rpt_filter']);
			unset($_SESSION['proposal_date_begin']); // = $o['proposal_date_begin'];
			unset($_SESSION['proposal_date_end']); // = $o['proposal_date_end'];
		}

		//we are getting input form a search
		if ($o['frm'] == 'search') {
			$_SESSION['ppm_rpt_filter'] = $p->BuildSearchString($o);
			$_SESSION['proposal_date_begin'] = $o['proposal_date_begin'];
			$_SESSION['proposal_date_end'] = $o['proposal_date_end'];
			$url = "action=display_by_ae";
			$url = $e->Encrypt($url);
			header("Location: ?e=".$url);
		}

		//see if we have a filter set
		if (isset($_SESSION['ppm_rpt_filter']) && $_SESSION['ppm_rpt_filter'] != '') {
			$filter = $_SESSION['ppm_rpt_filter'];
		}


		$list['summary'] = PrepareSmartyArray($p->GetSummaryListByAe($filter)); //should be StatusSummary

		$rs = $p->GetSummaryListByAccountAndAe($filter);

		while ($r = mysql_fetch_assoc($rs)) {
			$detail[$r['ae_login']][] = $r;
		}

		$o['rpt'] = 'display_by_ae';
		$meta = array_merge($_SESSION, $o);
		$smarty->assign('meta', $meta);


		if (!isset($o['excel'])) {

			DisplayHeader("Proposal Manager", "pgen");

			$smarty->assign('list', $list);
			$smarty->assign('detail_list', $detail);
			$smarty->display('app/pgen/vw_list_by_ae.tpl');


			DisplayFooter();
		} else {
			header('Content-type: application/vnd.ms-excel');
			header("Content-disposition: attachment; filename=\"summary_by_ae_".date('Ymd').".xls\"");
			$smarty->assign('list', $list);
			$smarty->display('app/pgen/rp_list_by_ae.tpl');
		}

	}

	/**
* DisplayByAm()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sat Jul 23 23:18:23 PDT 2005
*/
	function DisplayByAm($o)
	{
		global $smarty;
		$p = new proposalDB();
		$e = new Encryption();

		$filter = '';

		//turn of the filter
		if (isset($o['filter']) && $o['filter'] == 0) {
			unset($_SESSION['ppm_rpt_filter']);
			unset($_SESSION['proposal_date_begin']); // = $o['proposal_date_begin'];
			unset($_SESSION['proposal_date_end']); // = $o['proposal_date_end'];
		}

		//we are getting input form a search
		if ($o['frm'] == 'search') {
			$_SESSION['ppm_rpt_filter'] = $p->BuildSearchString($o);
			$_SESSION['proposal_date_begin'] = $o['proposal_date_begin'];
			$_SESSION['proposal_date_end'] = $o['proposal_date_end'];
			$url = "action=display_by_am";
			$url = $e->Encrypt($url);
			header("Location: ?e=".$url);
		}

		//see if we have a filter set
		if (isset($_SESSION['ppm_rpt_filter']) && $_SESSION['ppm_rpt_filter'] != '') {
			$filter = $_SESSION['ppm_rpt_filter'];
		}

		$list['summary'] = PrepareSmartyArray($p->GetSummaryListByAm($filter)); //should be StatusSummary

		$rs = $p->GetSummaryListByAccountAndAm($filter);

		while ($r = mysql_fetch_assoc($rs)) {
			$detail[$r['am_login']][] = $r;
		}

		$o['rpt'] = 'display_by_am';
		$meta = array_merge($_SESSION, $o);
		$smarty->assign('meta', $meta);

		if (!isset($o['excel'])) {

			DisplayHeader("Proposal Manager", "pgen");

			$smarty->assign('list', $list);
			$smarty->assign('detail_list', $detail);
			$smarty->display('app/ppm/vw_list_by_am.tpl');


			DisplayFooter();
		} else {
			header('Content-type: application/vnd.ms-excel');
			header("Content-disposition: attachment; filename=\"summary_by_am_".date('Ymd').".xls\"");
			$smarty->assign('list', $list);
			$smarty->display('app/ppm/rp_list_by_am.tpl');
		}

	}



}
?>