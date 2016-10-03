<?php
namespace SimpleInvoices\Controller;

/**
 * @author Juan Pedro Gonzalez Gutierrez
 */
class PreferencesController
{
    protected $smarty;

    /**
     * TODO: Don't use globals!
     */
    public function __construct()
    {
        global $smarty;

        $this->smarty = $smarty;
    }

    public function addAction()
    {
        if ($_POST['p_description'] != "" ) {
            $this->saveAction();
        }
        
        $defaults    = getSystemDefaults();
        $preferences = getActivePreferences();
        $localelist  = \Zend_Locale::getLocaleList();
        
        $this->smarty->assign('preferences', $preferences);
        $this->smarty->assign('defaults', $defaults);
        $this->smarty->assign('localelist', $localelist);
        $this->smarty->assign('pageActive', 'preference');
        $this->smarty->assign('subPageActive', 'preferences_add');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function detailsAction()
    {
        global $LANG;
        
        //if valid then do save
        if ($_POST['p_description'] != "" ) {
            $this->saveAction();
        }
        
        #get the invoice id
        $preference_id = $_GET['id'];
        $preference    = getPreference($preference_id);
        $index_group   = getPreference($preference['index_group']);
        
        $preferences   = getActivePreferences();
        $defaults      = getSystemDefaults();
        $status        = array(array('id'=>'0','status'=>$LANG['draft']), array('id'=>'1','status'=>$LANG['real']));
        $localelist    = \Zend_Locale::getLocaleList();
        
        $subPageActive = $_GET['action'] =="view"  ? "preferences_view" : "preferences_edit" ;
        
        $this->smarty->assign('preference', $preference);
        $this->smarty->assign('defaults', $defaults);
        $this->smarty->assign('index_group', $index_group);
        $this->smarty->assign('preferences', $preferences);
        $this->smarty->assign('status', $status);
        $this->smarty->assign('localelist', $localelist);
        $this->smarty->assign('pageActive', 'preference');
        $this->smarty->assign('subPageActive', $subPageActive);
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function manageAction()
    {
        $preferences = getPreferences();
        
        $this->smarty->assign("preferences", $preferences);
        $this->smarty->assign('pageActive', 'preference');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function saveAction()
    {
        $saved = false;
        
        $op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;
        
        $include_online_payment ='';
        
        foreach ($_POST['include_online_payment'] as $k => $v) {
            $include_online_payment .= $v;
            if ($k !=  end(array_keys($_POST['include_online_payment'])))
            {
                $include_online_payment .= ',';
            }
        }
        
        #insert invoice_preference
        if (  $op === 'insert_preference' ) {
        
            $sql = "INSERT into
		".TB_PREFIX."preferences
		(
			domain_id,
			pref_description,
			pref_currency_sign,
			currency_code,
			pref_inv_heading,
			pref_inv_wording,
			pref_inv_detail_heading,
			pref_inv_detail_line,
			pref_inv_payment_method,
			pref_inv_payment_line1_name,
			pref_inv_payment_line1_value,
			pref_inv_payment_line2_name,
			pref_inv_payment_line2_value,
			pref_enabled,
		        status,
		        locale,
		        language,
		        index_group,
			include_online_payment
		)
	VALUES
		(
			:domain_id,
			:description,
			:currency_sign,
			:currency_code,
			:heading,
			:wording,
			:detail_heading,
			:detail_line,
			:payment_method,
			:payment_line1_name,
			:payment_line1_value,
			:payment_line2_name,
			:payment_line2_value,
			:enabled,
            :status,
            :locale,
            :language,
            :index_group,
			:include_online_payment
		 )";
        
            if (dbQuery($sql,
                ':domain_id', $auth_session->domain_id,
                ':description', $_POST['p_description'],
                ':currency_sign', $_POST['p_currency_sign'],
                ':currency_code', $_POST['currency_code'],
                ':heading', $_POST['p_inv_heading'],
                ':wording', $_POST['p_inv_wording'],
                ':detail_heading', $_POST['p_inv_detail_heading'],
                ':detail_line', $_POST['p_inv_detail_line'],
                ':payment_method', $_POST['p_inv_payment_method'],
                ':payment_line1_name', $_POST['p_inv_payment_line1_name'],
                ':payment_line1_value', $_POST['p_inv_payment_line1_value'],
                ':payment_line2_name', $_POST['p_inv_payment_line2_name'],
                ':payment_line2_value', $_POST['p_inv_payment_line2_value'],
                ':status', $_POST['status'],
                ':locale', $_POST['locale'],
                ':language', $_POST['locale'],
                ':index_group', empty($_POST['index_group']) ? lastInsertId() : $_POST['index_group']  ,
                ':include_online_payment', $include_online_payment,
                ':enabled', $_POST['pref_enabled']
                )) {
                    $saved = true;
        
                    if (empty($_POST['index_group']))
                    {
                        $sql_update = "UPDATE
                    ".TB_PREFIX."preferences
                SET
                    index_group = :index_group
                WHERE
                    pref_id = :pref_id
            ";
                        dbQuery($sql_update,
                            ':index_group',lastInsertId(),
                            ':pref_id',lastInsertId()
                            );
                    }
                    //$display_block = $LANG['save_preference_success'];
                } ELSE {
                    $saved = false;
                    //$display_block =  $LANG['save_preference_failure'];
                }
                //header( 'refresh: 2; url=manage_preferences.php' );
        
        }
        
        #edit preference
        
        else if (  $op === 'edit_preference' ) {
        
            if (isset($_POST['save_preference'])) {
                $sql = "UPDATE
				".TB_PREFIX."preferences
			SET
				pref_description = :description,
				pref_currency_sign = :currency_sign,
				currency_code = :currency_code,
				pref_inv_heading = :heading,
				pref_inv_wording = :wording,
				pref_inv_detail_heading = :detail_heading,
				pref_inv_detail_line = :detail_line,
				pref_inv_payment_method = :payment_method,
				pref_inv_payment_line1_name = :line1_name,
				pref_inv_payment_line1_value = :line1_value,
				pref_inv_payment_line2_name = :line2_name,
				pref_inv_payment_line2_value = :line2_value,
				pref_enabled = :enabled,
				status = :status,
				locale = :locale,
				language = :language,
 		        index_group = :index_group,
 		        include_online_payment = :include_online_payment
			WHERE
				pref_id = :id";
        
                if (dbQuery($sql,
                    ':description', $_POST['pref_description'],
                    ':currency_sign', $_POST['pref_currency_sign'],
                    ':currency_code', $_POST['currency_code'],
                    ':heading', $_POST['pref_inv_heading'],
                    ':wording', $_POST['pref_inv_wording'],
                    ':detail_heading', $_POST['pref_inv_detail_heading'],
                    ':detail_line', $_POST['pref_inv_detail_line'],
                    ':payment_method', $_POST['pref_inv_payment_method'],
                    ':line1_name', $_POST['pref_inv_payment_line1_name'],
                    ':line1_value', $_POST['pref_inv_payment_line1_value'],
                    ':line2_name', $_POST['pref_inv_payment_line2_name'],
                    ':line2_value', $_POST['pref_inv_payment_line2_value'],
                    ':enabled', $_POST['pref_enabled'],
                    ':status', $_POST['status'],
                    ':locale', $_POST['locale'],
                    ':language', $_POST['language'],
                    ':index_group', $_POST['index_group'],
                    ':include_online_payment', $include_online_payment,
                    ':id', $_GET['id']))
                {
                    $saved =true;
                    //	$display_block = $LANG['save_preference_success'];
                } else {
                    $saved = false;
                    //$display_block = $LANG['save_preference_failure'];
                }
        
                //header( 'refresh: 2; url=manage_preferences.php' );
                //	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=preferences&view=manage>";
        
            }
        
        }
        
        $this->smarty->assign('saved', $saved);
        $this->smarty->assign('pageActive', 'preference');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    protected function xmlSql($type='', $dir, $sort, $rp, $page )
    {
        global $config;
        global $LANG;
        global $auth_session;
        
        $valid_search_fields = array('pref_id', 'pref_description');
        
        //SC: Safety checking values that will be directly subbed in
        if (intval($start) != $start) {
            $start = 0;
        }
        if (intval($limit) != $limit) {
            $limit = 25;
        }
        
        /*SQL Limit - start*/
        $start = (($page-1) * $rp);
        $limit = "LIMIT $start, $rp";
        
        if($type =="count")
        {
            unset($limit);
        }
        /*SQL Limit - end*/
        
        if (!preg_match('/^(asc|desc)$/iD', $dir)) {
            $dir = 'ASC';
        }
        
        $where = "";
        $query = isset($_POST['query']) ? $_POST['query'] : null;
        $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
        if ( ! (empty($qtype) || empty($query)) ) {
            if ( in_array($qtype, $valid_search_fields) ) {
                $where = " AND $qtype LIKE :query ";
            } else {
                $qtype = null;
                $query = null;
            }
        }
        
        /*Check that the sort field is OK*/
        $validFields = array('pref_id', 'pref_description','enabled');
        
        if (in_array($sort, $validFields)) {
            $sort = $sort;
        } else {
            $sort = "pref_description";
        }
        
        $sql = "SELECT
					pref_id,
					pref_description,
					(SELECT (CASE  WHEN pref_enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
				FROM
					".TB_PREFIX."preferences
        					WHERE domain_id = :domain_id
        					$where
        					ORDER BY
        					$sort $dir
        					$limit";
        
        if (empty($query)) {
            $result = dbQuery($sql, ':domain_id', $auth_session->domain_id);
        } else {
            $result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':query', "%$query%");
        }
        
        return $result;
    }
        
    public function xmlAction()
    {
        global $LANG;
        
        header("Content-type: text/xml");
        
        $start          = (isset($_POST['start']))     ? $_POST['start']     : "0" ;
        $dir            = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
        $sort           = (isset($_POST['sortname']))  ? $_POST['sortname']  : "pref_description" ;
        $rp             = (isset($_POST['rp']))        ? $_POST['rp']        : "25" ;
        $page           = (isset($_POST['page']))      ? $_POST['page']      : "1" ;
        $sth            = $this->xmlSql('', $dir, $sort, $rp, $page);
        $sth_count_rows = $this->xmlSql('count',$dir, $sort, $rp, $page);
        $preferences    = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $count          = $sth_count_rows->rowCount();
        
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$count</total>";
        
        foreach ($preferences as $row) {
            $xml .= "<row id='".$row['pref_id']."'>";
            $xml .= "<cell><![CDATA[
            <a class='index_table' title='$LANG[view] $LANG[preference] ".$row['pref_description']."' href='index.php?module=preferences&view=details&id=$row[pref_id]&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            <a class='index_table' title='$LANG[edit] $LANG[preference] ".$row['pref_description']."' href='index.php?module=preferences&view=details&id=$row[pref_id]&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            ]]></cell>";
            $xml .= "<cell><![CDATA[".$row['pref_description']."]]></cell>";
            if ($row['enabled']==$LANG['enabled']) {
                $xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";
            }
            else {
                $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";
            }
            $xml .= "</row>";
        }
        $xml .= "</rows>";
        
        echo $xml;
    }
}