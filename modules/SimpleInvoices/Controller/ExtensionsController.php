<?php
namespace SimpleInvoices\Controller;

/**
 * @author Juan Pedro Gonzalez Gutierrez
 */
class ExtensionsController
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
 
    public function manageAction()
    {
        global $LANG;
        
        isset($_GET['id']) && $extension_id = $_GET['id'];
        isset($_GET['action']) && $action = $_GET['action'];
        
        if ($action == 'toggle') {
            setStatusExtension($extension_id) or die(htmlsafe("Something went wrong with the status change!"));
        }
        
        $sql        = "SELECT * FROM ".TB_PREFIX."extensions WHERE domain_id = 0 OR domain_id = :domain_id ORDER BY name";
        $sth        = dbQuery($sql, ':domain_id', \domain_id::get());
        $extensions = null;
        
        for($i=0; $ext = $sth->fetch(); $i++) {
            $extensions[$i] = $ext;
        }
        
        $this->smarty->assign("exts", $extensions);
        $this->smarty->assign('pageActive', 'setting');
        $this->smarty->assign('active_tab', '#setting');
        $this->smarty->assign('subPageActive', 'setting_extensions');
    }
    
    public function registerAction()
    {
        $extension_id   = $_GET['id'];
        $extension_name = $_GET['name'];
        $action         = $_GET['action'];
        $extension_desc = $_GET['description'];
        $domain_id      = \domain_id::get();
        
        if ($extension_id !== null) {
            //retrieve name and description from DB
            $sql            = "SELECT name, description FROM ".TB_PREFIX."extensions WHERE id = :id AND domain_id = :domain_id;";
            $sth            = dbQuery($sql,':id',$extension_id, ':domain_id', $domain_id);
            $info           = $sth->fetchAll(\PDO::FETCH_ASSOC);
            $extension_name = $info[0]['name'];
            $extension_desc = $info[0]['description'];
            $sql            = "SELECT * FROM ".TB_PREFIX."system_defaults WHERE extension_id = :id AND (domain_id = :domain_id OR domain_id = 0);";
            $sth            = dbQuery($sql,':id',$extension_id, ':domain_id', $domain_id);
            $info           = $sth->fetchAll(\PDO::FETCH_ASSOC);
            $count          = count($info);
        }
        
        $this->smarty-> assign('id', $extension_id);
        $this->smarty-> assign('action', $action);
        $this->smarty-> assign('name', $extension_name);
        $this->smarty-> assign('count', $count);
        $this->smarty-> assign('description', $extension_desc);
        $this->smarty-> assign('pageActive', 'extensions');
        $this->smarty-> assign('active_tab', '#settings');
    }
    
    /**
     * TODO: Add checks
     */
    public function saveAction()
    {
        $domain_id = \domain_id::get();
        
        if ($_POST['action'] == "register") {
            $sql = "INSERT INTO ".TB_PREFIX."extensions (`id`,`name`,`description`,`domain_id`,`enabled`) VALUES ( NULL, :name ,  :description , :domain_id , '0');";
            $sth = dbQuery($sql, ':name',$_POST['name'],':description',$_POST['description'],':domain_id', $domain_id);
        } elseif ($_POST['action'] == "unregister") {
            $sql = "DELETE FROM ".TB_PREFIX."extensions WHERE id = :id AND domain_id = :domain_id; DELETE FROM ".TB_PREFIX."system_defaults WHERE extension_id = :id AND domain_id = :domain_id;";
            $sth = dbQuery($sql, ':id', $_POST['id'],':domain_id', $domain_id);
        } else {
            die("Dude, this action is unknown to me!");
        }
    }
    
    public function xmlAction()
    {
        global $LANG;
        
        header("Content-type: text/xml");
        
        $start                = (isset($_POST['start']))     ? $_POST['start']     : "0" ;
        $dir                  = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
        $rp                   = (isset($_POST['rp']))        ? $_POST['rp']        : "25" ;
        $page                 = (isset($_POST['page']))      ? $_POST['page']      : "1" ;
        $sort                 = "id" ;
        $domain_id            = \domain_id::get();
        $valid_search_fields  = array('id', 'name', 'description');
        $xml                  = "";
        $extension_dir        = './extensions';
        $extension_entries    = array_diff( scandir( $extension_dir ), Array( ".", ".." ) ); 	//Skip entries starting with a dot from dir list
        $available_extensions = array();
        
        foreach ($extension_entries as $entry) {
            if (file_exists ($extension_dir."/".$entry."/DESCRIPTION"))
            {
                $description = file_get_contents($extension_dir."/".$entry."/DESCRIPTION") ;
            } else {
                $description = "DESCRIPTION not available (in $extension_dir/$entry/)";
            }
        
            $available_extensions[$entry] = array("name"=>$entry,"enabled"=>0, "registered"=>0, "description"=>$description , "id" =>"");
        }
        
        //SC: Safety checking values that will be directly subbed in
        if (intval($start) != $start) {
            $start = 0;
        }
        if (intval($rp) != $rp) {
            $rp = 25;
        }
        if (!preg_match('/^(asc|desc)$/iD', $dir)) {
            $dir = 'ASC';
        }
        
        $limit = " LIMIT $start, $rp";
        
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
        
        $plugin[0] = " <img src='images/famfam/plugin_disabled.png' alt='".$LANG['plugin_not_registered']."' />";
        $plugin[1] = " <img src='images/famfam/plugin.png' alt='".$LANG['plugin_registered']."' />";
        $plugin[2] = " <img src='images/famfam/plugin_delete.png' alt='".$LANG['plugin_unregister']."' />";
        $plugin[3] = " <img src='images/famfam/plugin_add.png' alt='".$LANG['plugin_register']."' />";
        $light[0]  = " <img src='images/famfam/lightbulb_off.png' alt='".$LANG['disabled']."' />";
        $light[1]  = " <img src='images/famfam/lightbulb.png' alt='".$LANG['enabled']."' />";
        $light[2]  = " <img src='images/common/lightswitch16x16.png' alt='".$LANG['toggle_status']."' />";
        
        /*Check that the sort field is OK*/
        $validFields = array('id', 'name','description','enabled');
        
        $sql = "SELECT
				id,
				name,
				description,
				1 AS registered,
				enabled
			FROM
				".TB_PREFIX."extensions
        	WHERE  (domain_id = 0
        		OR domain_id = :domain_id)
        		$where
        	ORDER BY
        		$sort $dir
        		$limit";
        
        if (empty($query)) {
            $sth = dbQuery($sql, ':domain_id', $domain_id);
        } else {
            $sth = dbQuery($sql, ':domain_id', $domain_id, ':query', "%$query%");
        }
        
        $registered_extensions = $sth->fetchAll(\PDO::FETCH_ASSOC);
        
        // registered_extensions have all extensions in the database
        // available_extensions have all extensions in the distribution
        // extensions will have a complete list of the extensions, with status info (enabled, registered)
        
        foreach ($registered_extensions as $reg_ext) {
            $reg_name = $reg_ext['name'];
            if (isset($available_extensions[$reg_name])) { unset($available_extensions[$reg_name]); }
        }
        $extensions = array_merge($registered_extensions,$available_extensions);
        
        $count = count($extensions);
        
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$count</total>";
        
        foreach ($extensions as $row) {
            $xml .= "<row id='".$row['id']."'>";
            $xml .= "<cell><![CDATA[";
            if ($row['id'] == 0 && $row['registered'] ==1) { 
                $xml .= "Always enabled "; 
            } else {
                if ($row['registered'] == 1) {
                    $xml .="<a class='index_table' title='$LANG[plugin_unregister] $LANG[extensions] ".$row['name']."' href='index.php?module=extensions&view=register&id=$row[id]&action=unregister'> ".$plugin[3-$row['registered']]."</a>";
                    if ($row['enabled'] == 1) {
                        $xml .= " <a class='index_table' title='$LANG[disable] $LANG[extensions] ".$row['name']."' href='index.php?module=extensions&view=manage&id=$row[id]&action=toggle'>".$light[2]."</a>";
                    } else {
                        $xml .= " <a class='index_table' title='$LANG[enable] $LANG[extensions] ".$row['name']."' href='index.php?module=extensions&view=manage&id=$row[id]&action=toggle'>".$light[2]."</a>";
                    }
                } else {
                    $xml .="<a class='index_table' title='$LANG[plugin_register] $LANG[extensions] ".$row['name']."' href='index.php?module=extensions&view=register&name=$row[name]&action=register&description=$row[description]'> ".$plugin[3-$row['registered']]."</a>";
                }
            }
            $xml .= "]]></cell>";
            $xml .= "<cell><![CDATA[".$row['id']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['name']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['description']."]]></cell>";
            $xml .= "<cell><![CDATA[".$light[$row['enabled']].$plugin[$row['registered']]."]]></cell>";
            $xml .= "</row>";
        }
        $xml .= "</rows>";
        
        echo $xml;
    }
}