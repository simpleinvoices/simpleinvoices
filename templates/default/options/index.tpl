<div class="si_index si_index_settings">

	<h2>System settings</h2>
	<div class="si_toolbar">
            <a href="index.php?module=system_defaults&amp;view=manage" class="">
                <img src="./images/common/cog_edit.png" alt="" />
                {$LANG.system_preferences}
            </a>

            <a href="index.php?module=custom_fields&amp;view=manage" class="">
                <img src="./images/common/brick_edit.png" alt="" />
                {$LANG.custom_fields_upper}
            </a>

             <a href="index.php?module=extensions&view=manage" class="">
                <img src="./images/common/brick_edit.png" alt=""/>
                {$LANG.extensions}
            </a>
	</div>

	<h2>Invoice settings</h2>
	<div class="si_toolbar">
           <a href="index.php?module=tax_rates&amp;view=manage" class="">
                <img src="./images/common/money_delete.png" alt="" />
                 {$LANG.tax_rates}
            </a>

             <a href="index.php?module=preferences&amp;view=manage" class="">
                <img src="./images/common/page_white_edit.png" alt="" />
                 {$LANG.invoice_preferences}
            </a>

           <a href="index.php?module=payment_types&amp;view=manage" class="">
                <img src="./images/common/creditcards.png" alt="" />
                 {$LANG.payment_types}
            </a>
	</div>

	<h2>Database stuff</h2>
	<div class="si_toolbar">
             <a href="index.php?module=options&amp;view=backup_database" class="">
                <img src="./images/common/database_save.png" alt="" />
                {$LANG.backup_database}
            </a>

           <a href="index.php?module=options&amp;view=manage_sqlpatches" class="">
                <img src="./images/common/database.png" alt="" />
                 {$LANG.database_upgrade_manager}
            </a>

			<a href="index.php?module=options&amp;view=manage_cronlog" class="">
                <img src="./images/common/database_table.png" alt="" />
                 Cron {$LANG.database_log}
            </a>
	</div>

</div>
