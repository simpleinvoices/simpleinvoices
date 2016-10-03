<?php
namespace SimpleInvoices\Controller;

/**
 * @author Juan Pedro Gonzalez Gutierrez
 */
class AdminController
{   
    public function adminAction()
    {
        echo "<h2>Admin Functions</h2>";
        echo "<ul>";
        echo "	<li>Cache Cleanup</li>";
        echo "</ul>";
    }
    
    public function cleanupAction()
    {
        echo "should dele all files in the cache folder...";
    }
}