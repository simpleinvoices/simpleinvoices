<?php

// /simple/extensions/matts_luxury_pack/include/class

class myproduct extends product
{
	public $domain_id;
	public $defaults;
	
	public function __construct()
	{
		parent::__construct();
		$this->defaults = getSystemDefaults();
		$this->domain_id = isset($domain_id) ? domain_id::get($domain_id) : 1;
	}


/* from init.php */
	public function insertNew ($enabled=1,$visible=1, $domain_id='')
	{
		global $logger;
		$domain_id = domain_id::get ($domain_id);
	
		if (isset ($_POST['enabled'])) $enabled = $_POST['enabled'];
		//select all attribts
		$sql = "SELECT * FROM ".TB_PREFIX."products_attributes";
		$sth =  dbQuery($sql);
		$attributes = $sth->fetchAll();

		$logger->log ('Attr: '.var_export ($attributes,true), Zend_Log::INFO);
		$attr = array();
		foreach ($attributes as $k=>$v)
		{
			$logger->log ('Attr key: '.$k, Zend_Log::INFO);
			$logger->log ('Attr value: '.var_export ($v,true), Zend_Log::INFO);
			$logger->log( 'Attr set value: '.$k, Zend_Log::INFO);
			if ($_POST['attribute'.$v[id]] == 'true')
			{
				//$attr[$k]['attr_id'] = $v['id'];
				$attr[$v['id']] = $_POST['attribute'.$v[id]];
		//            $attr[$k]['a$v['id']] = $_POST['attribute'.$v[id]];
			}
		}
		$logger->log ('Attr array: '.var_export ($attr,true), Zend_Log::INFO);
		$notes_as_description = ($_POST['notes_as_description'] == 'true' ? 'Y' : NULL) ;
		$show_description =  ($_POST['show_description'] == 'true' ? 'Y' : NULL) ;

		$sql = "INSERT into
			".TB_PREFIX."products
			(
				domain_id, 
				description,
				unit_price, 
				unit_list_price2, 
				unit_list_price3, 
				unit_list_price4, 
				cost,
				reorder_level,
				custom_field1, 
				custom_field2,
				custom_field3,
				custom_field4, 
				notes, 
				default_tax_id, 
				enabled, 
				visible,
				attribute,
				notes_as_description,
				show_description
			) VALUES (	
				:domain_id,
				:description,
				:unit_price,
				:unit_list_price2,
				:unit_list_price3,
				:unit_list_price4,
				:cost,
				:reorder_level,
				:custom_field1,
				:custom_field2,
				:custom_field3,
				:custom_field4,
				:notes,
				:default_tax_id,
				:enabled,
				:visible,
				:attribute,
				:notes_as_description,
				:show_description
			)";

		return dbQuery ($sql,
			':domain_id',			$domain_id,	
			':description', 		$_POST['description'],
			':unit_price', 			$_POST['unit_price'],
			':unit_price_list2', 		$_POST['unit_price_list2'],
			':unit_price_list3', 		$_POST['unit_price_list3'],
			':unit_price_list4', 		$_POST['unit_price_list4'],
			':cost', 			$_POST['cost'],
			':reorder_level', 		$_POST['reorder_level'],
			':custom_field1', 		$_POST['custom_field1'],
			':custom_field2', 		$_POST['custom_field2'],
			':custom_field3', 		$_POST['custom_field3'],
			':custom_field4', 		$_POST['custom_field4'],
			':notes', 			"". $_POST['notes'],
			':default_tax_id', 		$_POST['default_tax_id'],
			':enabled', 			$enabled,
			':visible', 			$visible,
			':attribute', 			json_encode($attr),
			':notes_as_description', 	$notes_as_description,
			':show_description', 		$show_description
		);
	}


	public function update ($domain_id='') {

		$domain_id = domain_id::get ($domain_id);

		//select all attributes
		$sql = "SELECT * FROM ".TB_PREFIX."products_attributes";
		$sth =  dbQuery ($sql);
		$attributes = $sth->fetchAll();

		$attr = array();
		foreach ($attributes as $k=>$v)
		{
			if (isset($_POST['attribute'.$v['id']]) && $_POST['attribute'.$v['id']] == 'true')
			{
				$attr[$v['id']] = $_POST['attribute'.$v['id']];
			}
		}
		$notes_as_description = (isset($_POST['notes_as_description']) && $_POST['notes_as_description'] == 'true' ? 'Y' : NULL) ;
		$show_description =  (isset($_POST['show_description']) && $_POST['show_description'] == 'true' ? 'Y' : NULL) ;

		$sql = "UPDATE ".TB_PREFIX."products
				SET	description = :description,
					enabled = :enabled,
					default_tax_id = :default_tax_id,
					notes = :notes,
					custom_field1 = :custom_field1,
					custom_field2 = :custom_field2,
					custom_field3 = :custom_field3,
					custom_field4 = :custom_field4,
					unit_price = :unit_price,
					unit_list_price2 = :unit_list_price2,
					unit_list_price3 = :unit_list_price3,
					unit_list_price4 = :unit_list_price4,
					cost = :cost,
					reorder_level = :reorder_level,
					attribute = :attribute,
					notes_as_description = :notes_as_description,
					show_description = :show_description
				WHERE	id = :id
				AND	domain_id = :domain_id";

	//	echo "<script>alert('sql=$sql')</script>";

		return dbQuery ($sql,
			':domain_id',			$domain_id, 
			':description', 		$_POST['description'],
			':enabled', 			$_POST['enabled'],
			':notes', 			$_POST['notes'],
			':default_tax_id', 		$_POST['default_tax_id'],
			':custom_field1', 		$_POST['custom_field1'],
			':custom_field2', 		$_POST['custom_field2'],
			':custom_field3', 		$_POST['custom_field3'],
			':custom_field4', 		$_POST['custom_field4'],
			':unit_price', 			$_POST['unit_price'],
			':unit_list_price2', 		$_POST['unit_list_price2'],
			':unit_list_price3', 		$_POST['unit_list_price3'],
			':unit_list_price4', 		$_POST['unit_list_price4'],
			':cost', 			$_POST['cost'],
			':reorder_level', 		$_POST['reorder_level'],
			':attribute', 			json_encode($attr),
			':notes_as_description', 	$notes_as_description,
			':show_description', 		$show_description,
			':id', 				$_GET['id']
		);
	}

/*
	public function insertComplete($enabled=1, $visible=1, $description, $unit_price, $custom_field1=NULL, $custom_field2, $custom_field3, $custom_field4, $weight, $length, $width, $height, $notes, $unit_price_list2, $unit_price_list3, $unit_price_list4, $domain_id='')
	{
		$sql = "INSERT into
		".TB_PREFIX."products (
				domain_id, 
				description, 
				unit_price, 
				custom_field1, 
				custom_field2,
				custom_field3, 
				custom_field4";
		if ($defaults['product_lwhw'])		$sql.= ", weight, length, width, height";
		$sql.= ", notes, 
				enabled, 
				visible";
		if ($defaults['price_list'])		$sql.= ", unit_price_list2, unit_price_list3, unit_price_list4";
		$sql.= ") VALUES (	
				:domain_id,
				:description,
				:unit_price,
				:custom_field1,
				:custom_field2,
				:custom_field3,
				:custom_field4";
		if ($defaults['product_lwhw'])		$sql.= ", :weight, :length, :width, :height";
		$sql.= ", :notes,
				:enabled,
				:visible";
		if ($defaults['price_list'])		$sql.= ", :unit_price_list2, :unit_price_list3, :unit_price_list4";
		$sql.= ")";

		if ($defaults['product_lwhw'] && $defaults['price_list']) {
			return dbQuery($sql,
				':domain_id', $this->domain_id,
				':description', $description,
				':unit_price', $unit_price,
				':custom_field1', $custom_field1,
				':custom_field2', $custom_field2,
				':custom_field3', $custom_field3,
				':custom_field4', $custom_field4,
				':weight', $weight,
				':length', $length,
				':width', $width,
				':height', $height,
				':notes', "".$notes,
				':enabled', $enabled,
				':visible', $visible,
				':unit_price_list2', $unit_price_list2,
				':unit_price_list3', $unit_price_list3,
				':unit_price_list4', $unit_price_list4
			);
		}
		elseif ($defaults['product_lwhw'] && !$defaults['price_list']) {
			return dbQuery($sql,
				':domain_id', $this->domain_id,
				':description', $description,
				':unit_price', $unit_price,
				':custom_field1', $custom_field1,
				':custom_field2', $custom_field2,
				':custom_field3', $custom_field3,
				':custom_field4', $custom_field4,
				':weight', $weight,
				':length', $length,
				':width', $width,
				':height', $height,
				':notes', "".$notes,
				':enabled', $enabled,
				':visible', $visible
			);
		}
		elseif (!$defaults['product_lwhw'] && !$defaults['price_list']) {
			return dbQuery($sql,
				':domain_id', $this->domain_id,
				':description', $description,
				':unit_price', $unit_price,
				':custom_field1', $custom_field1,
				':custom_field2', $custom_field2,
				':custom_field3', $custom_field3,
				':custom_field4', $custom_field4,
				':notes', "".$notes,
				':enabled', $enabled,
				':visible', $visible,
				':unit_price_list2', $unit_price_list2,
				':unit_price_list3', $unit_price_list3,
				':unit_price_list4', $unit_price_list4
			);
		}
		elseif (!$defaults['product_lwhw'] && !$defaults['price_list']) {
			return dbQuery($sql,
				':domain_id', $this->domain_id,
				':description', $description,
				':unit_price', $unit_price,
				':custom_field1', $custom_field1,
				':custom_field2', $custom_field2,
				':custom_field3', $custom_field3,
				':custom_field4', $custom_field4,
				':notes', "".$notes,
				':enabled', $enabled,
				':visible', $visible
			);
		}
	}

	public function insert($enabled=1, $visible=1, $domain_id='')
	{
		global $logger;
		
		if (isset($_POST['enabled'])) $enabled = $_POST['enabled'];
		//select all attribts
		$sql = "SELECT * FROM ".TB_PREFIX."products_attributes";
		$sth =  dbQuery($sql);
		$attributes = $sth->fetchAll();

		$logger->log('Attr: '.var_export($attributes,true), Zend_Log::INFO);
		$attr = array();
		foreach ($attributes as $k=>$v)
		{
			$logger->log('Attr key: '.$k, Zend_Log::INFO);
			$logger->log('Attr value: '.var_export($v,true), Zend_Log::INFO);
			$logger->log('Attr set value: '.$k, Zend_Log::INFO);
			if ($_POST['attribute'.$v[id]] == 'true')
			{
				//$attr[$k]['attr_id'] = $v['id'];
				$attr[$v['id']] = $_POST['attribute'.$v[id]];
	//            $attr[$k]['a$v['id']] = $_POST['attribute'.$v[id]];
			}
			
		}
		$logger->log('Attr array: '.var_export($attr,true), Zend_Log::INFO);
		$notes_as_description = ($_POST['notes_as_description'] == 'true' ? 'Y' : NULL) ;
		$show_description =  ($_POST['show_description'] == 'true' ? 'Y' : NULL) ;

		$sql = "INSERT into
			".TB_PREFIX."products
			(
				domain_id, 
				description, 
				unit_price, 
				cost,
				reorder_level,
				custom_field1, 
				custom_field2,
				custom_field3,
				custom_field4";
		if ($defaults['product_lwhw'])		$sql.= ", weight, length, width, height";
		$sql.= ", notes, 
				default_tax_id, 
				enabled, 
				visible,
				attribute,
				notes_as_description,
				show_description";
		if ($defaults['price_list'])		$sql.= ", unit_price_list2, unit_price_list3, unit_price_list4";
		$sql.= ")
		VALUES
			(	
				:domain_id,
				:description,
				:unit_price,
				:cost,
				:reorder_level,
				:custom_field1,
				:custom_field2,
				:custom_field3,
				:custom_field4";
		if ($defaults['product_lwhw'])		$sql.= ", :weight, :length, :width, :height";
		$sql.= ", :notes, 
				:default_tax_id,
				:enabled,
				:visible,
				:attribute,
				:notes_as_description,
				:show_description";
		if ($defaults['price_list'])		$sql.= ", :unit_price_list2, :unit_price_list3, :unit_price_list4";
		$sql .=")";

		if ($defaults['product_lwhw'] && $defaults['price_list']) {
			return dbQuery($sql,
				':domain_id', (isset($domain_id) ? $domain_id : $this->domain_id),
				':description', $_POST['description'],
				':unit_price', $_POST['unit_price'],
				':cost', $_POST['cost'],
				':reorder_level', $_POST['reorder_level'],
				':custom_field1', $_POST['custom_field1'],
				':custom_field2', $_POST['custom_field2'],
				':custom_field3', $_POST['custom_field3'],
				':custom_field4', $_POST['custom_field4'],
				':weight', $_POST['weight'],
				':length', $_POST['length'],
				':width', $_POST['width'],
				':height', $_POST['height'],
				':notes', "".$_POST['notes'],
				':default_tax_id', $_POST['default_tax_id'],
				':enabled', $enabled,
				':visible', $visible,
				':attribute', json_encode($attr),
				':notes_as_description', $notes_as_description,
				':show_description', $show_description,
				':unit_price_list2', $_POST['unit_price_list2'],
				':unit_price_list3', $_POST['unit_price_list3'],
				':unit_price_list4', $_POST['unit_price_list4']
			);
		}
		elseif ($defaults['product_lwhw'] && !$defaults['price_list']) {
			return dbQuery($sql,
				':domain_id', (isset($domain_id) ? $domain_id : $this->domain_id),
				':description', $_POST['description'],
				':unit_price', $_POST['unit_price'],
				':cost', $_POST['cost'],
				':reorder_level', $_POST['reorder_level'],
				':custom_field1', $_POST['custom_field1'],
				':custom_field2', $_POST['custom_field2'],
				':custom_field3', $_POST['custom_field3'],
				':custom_field4', $_POST['custom_field4'],
				':weight', $_POST['weight'],
				':length', $_POST['length'],
				':width', $_POST['width'],
				':height', $_POST['height'],
				':notes', "".$_POST['notes'],
				':default_tax_id', $_POST['default_tax_id'],
				':enabled', $enabled,
				':visible', $visible,
				':attribute', json_encode($attr),
				':notes_as_description', $notes_as_description,
				':show_description', $show_description
			);
		}
		elseif (!$defaults['product_lwhw'] && $defaults['price_list']) {
			return dbQuery($sql,
				':domain_id', (isset($domain_id) ? $domain_id : $this->domain_id),
				':description', $_POST['description'],
				':unit_price', $_POST['unit_price'],
				':cost', $_POST['cost'],
				':reorder_level', $_POST['reorder_level'],
				':custom_field1', $_POST['custom_field1'],
				':custom_field2', $_POST['custom_field2'],
				':custom_field3', $_POST['custom_field3'],
				':custom_field4', $_POST['custom_field4'],
				':notes', "".$_POST['notes'],
				':default_tax_id', $_POST['default_tax_id'],
				':enabled', $enabled,
				':visible', $visible,
				':attribute', json_encode($attr),
				':notes_as_description', $notes_as_description,
				':show_description', $show_description,
				':unit_price_list2', $_POST['unit_price_list2'],
				':unit_price_list3', $_POST['unit_price_list3'],
				':unit_price_list4', $_POST['unit_price_list4']
			);
		}
		elseif (!$defaults['product_lwhw'] && !$defaults['price_list']) {
			return dbQuery($sql,
				':domain_id', (isset($domain_id) ? $domain_id : $this->domain_id),
				':description', $_POST['description'],
				':unit_price', $_POST['unit_price'],
				':cost', $_POST['cost'],
				':reorder_level', $_POST['reorder_level'],
				':custom_field1', $_POST['custom_field1'],
				':custom_field2', $_POST['custom_field2'],
				':custom_field3', $_POST['custom_field3'],
				':custom_field4', $_POST['custom_field4'],
				':notes', "".$_POST['notes'],
				':default_tax_id', $_POST['default_tax_id'],
				':enabled', $enabled,
				':visible', $visible,
				':attribute', json_encode($attr),
				':notes_as_description', $notes_as_description,
				':show_description', $show_description
			);
		}
	}


	public function update($domain_id='')
	{
		//select all attributes
		$sql = "SELECT * FROM ".TB_PREFIX."products_attributes";
		$sth =  dbQuery($sql);
		$attributes = $sth->fetchAll();

		$attr = array();
		foreach ($attributes as $k=>$v)
		{
			if ($_POST['attribute'.$v[id]] == 'true')
			{
				$attr[$v['id']] = $_POST['attribute'.$v[id]];
			}
			
		}
		$notes_as_description = ($_POST['notes_as_description'] == 'true' ? 'Y' : NULL) ;
		$show_description =  ($_POST['show_description'] == 'true' ? 'Y' : NULL) ;

		$sql = "UPDATE ".TB_PREFIX."products
				SET
					description = :description,
					enabled = :enabled,
					default_tax_id = :default_tax_id,
					notes = :notes,
					custom_field1 = :custom_field1,
					custom_field2 = :custom_field2,
					custom_field3 = :custom_field3,
					custom_field4 = :custom_field4";
		if ($defaults['product_lwhw'])		$sql.= ", weight = :weight, length = :length, width = :width, height = :height";
		$sql.= ",	unit_price = :unit_price,
					cost = :cost,
					reorder_level = :reorder_level,
					attribute = :attribute,
					notes_as_description = :notes_as_description,
					show_description = :show_description";
		if ($defaults['price_list'])		$sql.= ", unit_price_list2 = :unit_price_list2, unit_price_list3 = :unit_price_list3, unit_price_list4 = :unit_price_list4";
		$sql.= "WHERE
					id = :id
				AND domain_id = :domain_id";

		if ($defaults['product_lwhw'] && $defaults['price_list']) {
			return dbQuery($sql,
				':domain_id', (isset($domain_id) ? $domain_id : $this->domain_id),
				':description', $_POST['description'],
				':enabled', $_POST['enabled'],
				':notes', $_POST['notes'],
				':default_tax_id', $_POST['default_tax_id'],
				':custom_field1', $_POST['custom_field1'],
				':custom_field2', $_POST['custom_field2'],
				':custom_field3', $_POST['custom_field3'],
				':custom_field4', $_POST['custom_field4'],
				':weight', $_POST['weight'],
				':length', $_POST['length'],
				':width', $_POST['width'],
				':height', $_POST['height'],
				':unit_price', $_POST['unit_price'],
				':cost', $_POST['cost'],
				':reorder_level', $_POST['reorder_level'],
				':attribute', json_encode($attr),
				':notes_as_description', $notes_as_description,
				':show_description', $show_description,
				':unit_price_list2', $_POST['unit_price_list2'],
				':unit_price_list3', $_POST['unit_price_list3'],
				':unit_price_list4', $_POST['unit_price_list4'],
				':id', $_GET['id']
			);
		}
		elseif (!$defaults['product_lwhw'] && $defaults['price_list']) {
			return dbQuery($sql,
				':domain_id', (isset($domain_id) ? $domain_id : $this->domain_id),
				':description', $_POST['description'],
				':enabled', $_POST['enabled'],
				':notes', $_POST['notes'],
				':default_tax_id', $_POST['default_tax_id'],
				':custom_field1', $_POST['custom_field1'],
				':custom_field2', $_POST['custom_field2'],
				':custom_field3', $_POST['custom_field3'],
				':custom_field4', $_POST['custom_field4'],
				':unit_price', $_POST['unit_price'],
				':cost', $_POST['cost'],
				':reorder_level', $_POST['reorder_level'],
				':attribute', json_encode($attr),
				':notes_as_description', $notes_as_description,
				':show_description', $show_description,
				':unit_price_list2', $_POST['unit_price_list2'],
				':unit_price_list3', $_POST['unit_price_list3'],
				':unit_price_list4', $_POST['unit_price_list4'],
				':id', $_GET['id']
			);
		}
		elseif ($defaults['product_lwhw'] && !$defaults['price_list']) {
			return dbQuery($sql,
				':domain_id', (isset($domain_id) ? $domain_id : $this->domain_id),
				':description', $_POST['description'],
				':enabled', $_POST['enabled'],
				':notes', $_POST['notes'],
				':default_tax_id', $_POST['default_tax_id'],
				':custom_field1', $_POST['custom_field1'],
				':custom_field2', $_POST['custom_field2'],
				':custom_field3', $_POST['custom_field3'],
				':custom_field4', $_POST['custom_field4'],
				':weight', $_POST['weight'],
				':length', $_POST['length'],
				':width', $_POST['width'],
				':height', $_POST['height'],
				':unit_price', $_POST['unit_price'],
				':cost', $_POST['cost'],
				':reorder_level', $_POST['reorder_level'],
				':attribute', json_encode($attr),
				':notes_as_description', $notes_as_description,
				':show_description', $show_description,
				':id', $_GET['id']
			);
		}
		elseif (!$defaults['product_lwhw'] && !$defaults['price_list']) {
			return dbQuery($sql,
				':domain_id', (isset($domain_id) ? $domain_id : $this->domain_id),
				':description', $_POST['description'],
				':enabled', $_POST['enabled'],
				':notes', $_POST['notes'],
				':default_tax_id', $_POST['default_tax_id'],
				':custom_field1', $_POST['custom_field1'],
				':custom_field2', $_POST['custom_field2'],
				':custom_field3', $_POST['custom_field3'],
				':custom_field4', $_POST['custom_field4'],
				':unit_price', $_POST['unit_price'],
				':cost', $_POST['cost'],
				':reorder_level', $_POST['reorder_level'],
				':attribute', json_encode($attr),
				':notes_as_description', $notes_as_description,
				':show_description', $show_description,
				':id', $_GET['id']
			);
		}
	}
*/
}
