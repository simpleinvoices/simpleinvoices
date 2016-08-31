{*
/*
 * Script: ./extensions/matts_luxury_pack/templates/default/system_defaults/manage.tpl
 * 	Edit a System Preference
 *
 * Authors:
 *	 yumatechnical@gmail.com
 *
 * Last edited:
 * 	 2016-08-30
 *
 * License:
 *	 GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
*}
{* This section will be added to the system_defaults manage screen. *}
		<tr>
			<th>{$LANG.default_delnote}
				<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_default_delnote" title="{$LANG.default_delnote}"><img src="./images/common/help-small.png" alt="help" /></a>
			</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=default_delnote'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaults.delnote|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.product_lwhw}
				<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_product_lwhw" title="{$LANG.product_lwhw}"><img src="./images/common/help-small.png" alt="help" /></a>
			</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=product_lwhw'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaultProductLWHW|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.default_nrows}
				<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_nrows" title="{$LANG.default_nrows}"><img src="./images/common/help-small.png" alt="help" /></a>
			</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=default_nrows'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaults.default_nrows|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.price_lists}
				<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_price_list" title="{$LANG.price_list}"><img src="./images/common/help-small.png" alt="help" /></a>
			</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=price_list'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$price_list|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.Modal}
				<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_modal" title="{$LANG.Modal}"><img src="./images/common/help-small.png" alt="help" /></a>
			</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=use_modal'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$use_modal|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.ship_to}
				<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_ship_to" title="{$LANG.ship_to}"><img src="./images/common/help-small.png" alt="help" /></a>
			</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=use_ship_to'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$use_ship_to|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.terms}
				<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_terms" title="{$LANG.logging}"><img src="./images/common/help-small.png" alt="help" /></a>
			</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=use_terms'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$use_terms|htmlsafe}</td>
		</tr>
