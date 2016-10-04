<?php
/**
 * Simple Invoices (http://www.simpleinvoices.org/)
 *
 * @link      http://github.com/simpleinvoices/simpleinvoices for the source repository
 * @copyright Copyright (c) 2005-2016 Simple Invoices Community (http://www.simpleinvoices.org)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

namespace SimpleInvoices\Controller;

class CustomFieldsController
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
    
    public function detailsAction()
    {
        global $auth_session;
        global $dbh;
        
        $cf_id         = $_GET["id"];
        $print_product = "SELECT * FROM ".TB_PREFIX."custom_fields WHERE cf_id = :id AND domain_id = :domain_id";
        $sth           = dbQuery($print_product, ':id', $cf_id, ':domain_id', $auth_session->domain_id) or die(end($dbh->errorInfo()));
        $cf            = $sth->fetch();
        $cf['name']    = get_custom_field_name($cf['cf_custom_field']);
        $pageActive    = "options";
        $subPageActive = $_GET['action'] =="view"  ? "custom_fields_view" : "custom_fields_edit" ;
        
        $this->smarty -> assign('pageActive', $pageActive);
        $this->smarty -> assign("cf", $cf);
        $this->smarty -> assign('pageActive', 'custom_field');
        $this->smarty -> assign('subPageActive', $subPageActive);
        $this->smarty -> assign('active_tab', '#setting');
    }
    
    public function manageAction()
    {
        global $dbh;
        global $auth_session;
        
        $sql            = "SELECT * FROM ".TB_PREFIX."custom_fields WHERE domain_id = :domain_id ORDER BY cf_custom_field";
        $sth            = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(end($dbh->errorInfo()));
        $cfs            = null;
        $number_of_rows = 0;
        
        for($i=0; $cf = $sth->fetch();$i++) {
            $cfs[$i] = $cf;
            $cfs[$i]['filed_name'] = get_custom_field_name($cf['cf_custom_field']);
            $number_of_rows = $i;
        }
        
        $this->smarty->assign("cfs", $cfs);
        $this->smarty->assign('pageActive', 'custom_field');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function saveAction()
    {
        global $LANG;
        global $auth_session;
        global $dbh;
        
        # Deal with op and add some basic sanity checking
        $op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;
        
        #edit custom field
        if (  $op === 'edit_custom_field' ) {
            if (isset($_POST['save_custom_field'])) {
        
                // @formatter:off
                $sql = "UPDATE ".TB_PREFIX."custom_fields
            SET cf_custom_label = :label
            WHERE cf_id = :id
			  AND domain_id = :domain_id";
        
                // @formatter:on
                if (dbQuery($sql, ':id', $_GET['id'], ':label', $_POST['cf_custom_label'], ':domain_id', $auth_session->domain_id)) {
                    $display_block =  $LANG['save_custom_field_success'];
                } else {
                    $display_block =  $LANG['save_custom_field_failure'];
                    $display_block .=  end($dbh->errorInfo());
                }
        
                //header( 'refresh: 2; url=manage_custom_fields.php' );
                $refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=custom_fields&amp;view=manage' />";
        
            } else if (isset($_POST['cancel'])) {
        
                //header( 'refresh: 0; url=manage_custom_fields.php' );
                $refresh_total = "<meta http-equiv='refresh' content='0;url=index.php?module=custom_fields&amp;view=manage' />";
            }
        }
        
        $refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';
        
        $this->smarty->assign('display_block',$display_block);
        $this->smarty->assign('refresh_total',$refresh_total);
        $this->smarty->assign('pageActive', 'custom_field');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function xmlAction()
    {
        global $LANG;
        global $auth_session;
        
        header("Content-type: text/xml");
        
        $start = (isset($_POST['start']))     ? $_POST['start']     : "0" ;
        $dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
        $sort  = (isset($_POST['sortname']))  ? $_POST['sortname']  : "cf_id" ;
        $limit = (isset($_POST['rp']))        ? $_POST['rp'] : "25" ;
        $page  = (isset($_POST['page'])) ?    $_POST['page'] : "1"  ;
        $xml   = "";
        
        //SC: Safety checking values that will be directly subbed in
        if (intval($start) != $start) {
            $start = 0;
        }
        if (intval($limit) != $limit) {
            $limit = 25;
        }
        if (!preg_match('/^(asc|desc)$/iD', $dir)) {
            $dir = 'ASC';
        }
        
        $where = " WHERE domain_id = :domain_id";
        
        /*Check that the sort field is OK*/
        $validFields = array('cf_id', 'cf_custom_label','enabled');
        
        if (in_array($sort, $validFields)) {
            $sort = $sort;
        } else {
            $sort = "cf_id";
        }
        
        $sql = "SELECT
				cf_id,
				cf_custom_field,
				cf_custom_label
			FROM
				".TB_PREFIX."custom_fields
        				$where
        				ORDER BY
        				$sort $dir
        				LIMIT
        				$start, $limit";
        					
        $sth = dbQuery($sql,':domain_id', $auth_session->domain_id);
        $count = $sth->rowCount();
        
        $cfs = null;
        
        $number_of_rows = 0;
        for($i=0; $cf = $sth->fetch();$i++) {
            $cfs[$i] = $cf;
            $cfs[$i]['field_name_nice'] = get_custom_field_name($cf['cf_custom_field']);
            $number_of_rows = $i;
        }
        
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$count</total>";
        
        foreach ($cfs as $row) {
            $xml .= "<row id='".htmlsafe($row['cf_id'])."'>";
            $xml .= "<cell><![CDATA[
                     <a class='index_table' title='$LANG[view] $LANG[custom_field] ".htmlsafe($row['field_name_nice'])."' href='index.php?module=custom_fields&view=details&id=$row[cf_id]&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
        		     <a class='index_table' title='$LANG[edit] $LANG[custom_field] ".htmlsafe($row['field_name_nice'])."' href='index.php?module=custom_fields&view=details&id=$row[cf_id]&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
        		    ]]></cell>";
            $xml .= "<cell><![CDATA[".htmlsafe($row['cf_id'])."]]></cell>";
            $xml .= "<cell><![CDATA[".htmlsafe($row['field_name_nice'])."]]></cell>";
            $xml .= "<cell><![CDATA[".htmlsafe($row['cf_custom_label'])."]]></cell>";
            $xml .= "</row>";
        }
        $xml .= "</rows>";
        
        echo $xml;
    }
}