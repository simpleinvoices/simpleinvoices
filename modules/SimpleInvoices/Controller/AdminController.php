<?php
/**
 * Simple Invoices (http://www.simpleinvoices.org/)
 *
 * @link      http://github.com/simpleinvoices/simpleinvoices for the source repository
 * @copyright Copyright (c) 2005-2016 Simple Invoices Community (http://www.simpleinvoices.org)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

namespace SimpleInvoices\Controller;

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