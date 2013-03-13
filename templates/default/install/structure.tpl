{include file=$path|cat:'inc_head.tpl'}


<div class="si_form si_message_install">
	The Simple Invoices database has now been installed.
    <br/>
    <br/>
	The next step is to import the essential data.<br />
	<br />
	Click the 'Install Essential Data' button below to continue the installation.
</div>


<div class="si_toolbar si_toolbar_form">
            <a href="./index.php?module=install&amp;view=essential" class="positive">
                <img src="./images/common/tick.png" alt="" />
                Install Essential Data
            </a>
    
            <a href="./index.php" class="negative">
                <img src="./images/common/cross.png" alt="" />
                Cancel
            </a>
</div>

{include file=$path|cat:'inc_foot.tpl'}
