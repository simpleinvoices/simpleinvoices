{include file=$path|cat:'inc_head.tpl'}


<div class="si_message_ok">
The Simple Invoices essential data has been imported.<br />
You can select to just start using Simple Invoices now or install sample data.
</div>


<div class="si_toolbar si_toolbar_form">
            <a href="./index.php" class="positive">
                <img src="./images/common/tick.png" alt="" />
                Start using Simple Invoices
            </a>
    
            <a href="./index.php?module=install&amp;view=sample_data" class="positive">
                <img src="./images/common/tick.png" alt="" />
                Install Sample Data
            </a>
</div>

{include file=$path|cat:'inc_foot.tpl'}
