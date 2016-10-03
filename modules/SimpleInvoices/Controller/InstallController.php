<?php
namespace SimpleInvoices\Controller;

/**
 * @author Juan Pedro Gonzalez Gutierrez
 */
class InstallController
{
    protected $menu;
    
    protected $smarty;

    /**
     * TODO: Don't use globals!
     */
    public function __construct()
    {
        global $smarty;
        global $menu;

        $this->smarty = $smarty;
        $this->menu   = $menu;
    }
    
    public function indexAction()
    {
        $this->menu = false;
    }
    
    public function essentialAction()
    {
        global $install_data_exists;
        global $db;
        
        if ( (checkTableExists(TB_PREFIX."customers") == true) AND ($install_data_exists == false) )
        {
            //JSON import
            $importjson = new \importjson();
            $importjson->file            = "./databases/json/essential_data.json";
            $importjson->pattern_find    = array('si_','DOMAIN-ID','LOCALE','LANGUAGE');
            $importjson->pattern_replace = array(TB_PREFIX,'1','en_GB','en_GB');

            $db->query($importjson->collate());
        }
    }
    
    public function sampleDataAction()
    {
        global $db;
        
        $this->menu = false;
        
        //JSON import
        $samplejson = new \importjson();
        $samplejson->file            = "./databases/json/sample_data.json";
        $samplejson->pattern_find    = array('si_','DOMAIN-ID','LOCALE','LANGUAGE');
        $samplejson->pattern_replace = array(TB_PREFIX,'1','en_GB','en_GB');
        
        if($db->query($samplejson->collate()) )
        {
            $saved = true;
        } else {
            $saved = false;
        }
        
        $this->smarty->assign("saved", $saved);
    }
    
    public function structureAction()
    {
        global $db;
        
        $this->menu = false;
                
        if (checkTableExists() == false)
        {
            //SQL import
            $import = new \import();
            $import->file            = "./databases/mysql/structure.sql";
            $import->pattern_find    = array('si_','DOMAIN-ID','LOCALE','LANGUAGE');
            $import->pattern_replace = array(TB_PREFIX,'1','en_GB','en_GB');

            $db->query($import->collate());
        }
        
    }
}