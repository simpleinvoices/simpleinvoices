<?php
	/******************************************************************************
	*																										*
	*	Useful functions and classes to deal with PHPReports stuff.						*
	*	This file is part of the standard PHPReports package.								*
	*																										*
	******************************************************************************/	

	/******************************************************************************
	*																										*
	*	This function will return if there is a PHPReports path in the PHP			*
	*	ini_get("include_path").																	*
	*																										*
	******************************************************************************/
	function getPHPReportsIncludePath(){
		$aPaths = explode((stristr(PHP_OS,"WIN")?";":":"),ini_get("include_path"));
		foreach($aPaths as $sPath)
			if(stristr($sPath,"phpreports"))
				return $sPath;
		return null;
	}

	/******************************************************************************
	*																										*
	*	Returns the temporary file path. It's up to your operational system to		*
	*	return that. In most cases, on Linux it will return /tmp and on				*
	*	Windows c:\temp																				*
	*																										*
	******************************************************************************/
	function getPHPReportsTmpPath(){
		$sPath = tempnam(null,"check");
		unlink($sPath);
		return realpath(dirname($sPath));
	}

	/******************************************************************************
	*																										*
	*	This function will return the file path where the PHPReports classes			*
	*	are.																								* 
	*																										*
	******************************************************************************/
	function getPHPReportsFilePath(){
		$sPath = getPHPReportsIncludePath();
		if(!is_null($sPath))
			return $sPath;
		// put your distro path here
		return "/var/htdocs/phpreports/"; 
	}

	/******************************************************************************
	*																										*
	*	XSLTProcessorClass																			*
	*	This class is used as base for XSLT process.											*
	*																										*
	******************************************************************************/	
	class XSLTProcessorClass{
		var $_sXML;
		var $_sXSLT;
		var $_sOutput;
		var $_aParms;

		/**
			Constructor
		*/
		function XSLTProcessorClass(){
			$this->_sXML	=null;
			$this->_sXSLT	=null;
			$this->_sOutput=null;
			$this->_aParms	=null;
		}

		/**
			Sets the XML data file path
		*/			
		function setXML($sXML_=null){
			$this->_sXML=$sXML_;
		}
		
		/**
			Returns the XML data file path
		*/
		function getXML(){
			return $this->_sXML;
		}
		
		/**
			Sets the style sheet file path
		*/
		function setXSLT($sXSLT_=null){
			$this->_sXSLT=$sXSLT_;
		}

		/**
			Returns the style sheet file path
		*/
		function getXSLT(){
			return $this->_sXSLT;
		}
		
		/**
			Specify the output file path
			A null just returns the result on the run method
		*/
		function setOutput($sOutput_=null){
			$this->_sOutput=$sOutput_;
		}

		/**
			Return the output file path
		*/		
		function getOutput(){
			return $this->_sOutput;
		}

		/**
			Specify the parameters array
		*/
		function setParms($aParms_=null){
			if(is_null($aParms_))
				return;
			if(!is_array($aParms_))
				return;	
			$this->_aParms=$aParms_;	
		}
		
		/**
			Insert a parameter
			sParm_ - parameter name
			oVal_  - parameter value
		*/			
		function setParm($sParm_=null,$oVal_=null){
			if(is_null($sParm_))
				return;
			$this->_aParms[$sParm_]=$oVal_;
		}

		/**
			Returns a parameter value
			sParm_ - parameter name
		*/
		function getParm($sParm_){
			if(!array_key_exists($sParm_))
				return null;
			return $this->_aParms[$sParm_];
		}
		
		/**
			Remove a parameter
			sParm_ - parameter name
		*/
		function removeParm($sParm_=null){
			if(is_null($sParm_))
				return;
			if(!array_key_exists($sParm_,$this->_aParms))
				return;					
			unset($this->_aParms[$sParm_]);					
		}

		/**
			This method MUST be overwritten on every subclass to reflect
			the behaviour of the desired XSLT processor.
			It MUST return the result, and if defined an output, save it.
		*/
		function run(){
		}
	}
	
	/******************************************************************************
	*																										*
	*	Sablotron processor																			*
	*	http://www.gingerall.com/charlie/ga/xml/p_sab.xml									*
	*	http://www.php.net/manual/en/ref.xslt.php												*
	*	Used on PHP4 or installed from the PECL modules.									*
	*																										*
	******************************************************************************/
	class Sablotron_xp extends XSLTProcessorClass{
		function run(){
			if(is_null($this->_sXML)){
				print "ERROR: no XML file specified";
				return;
			}
			if(is_null($this->_sXSLT)){
				print "ERROR: no XSLT file specified";
				return;
			}
			$oXSLT = xslt_create();
			$sRst	 = xslt_process($oXSLT,$this->_sXML,$this->_sXSLT,$this->_sOutput,null,$this->_aParms);
			xslt_free($oXSLT);					
			return $sRst;
		}
	}

	/******************************************************************************
	*																										*
	*	PHP5 XSL processing																			*
	*	Uses libxslt																					*
	*	http://www.php.net/manual/en/ref.xsl.php												*
	*																										*
	******************************************************************************/
	class PHPXSL_xp extends XSLTProcessorClass{
		function run(){
			// xml document
			$oXML = new DomDocument();
			$oXML->load($this->_sXML);
			$oXML->xinclude();

			// xslt document
			$oXSL = new DomDocument();
			$oXSL->load($this->_sXSLT);

			// xslt processor
			$oProc = new XSLTProcessor();
			$oProc->importStyleSheet($oXSL);
			
			// set all the parameters
			if(!is_null($this->_aParms)){
				foreach($this->_aParms as $k => $v)
					$oProc->setParameter("",$k,$v);
			}	

			// make the transformation				
			$sRst = $oProc->transformToXML($oXML);
			unset($oProc);
			unset($oXSL);
			unset($oXML);

			// if output is not null, save the result there
			if(!is_null($this->_sOutput)){
				$fHand = @fopen($this->_sOutput,"w");
				@fputs($fHand,$sRst);
				@fclose($fHand);
			}
			return $sRst;
		}
	}
	
	/******************************************************************************
	*																										*
	*	XSLT Processor factory																		*
	*	Returns a XSLT processor based on the current environment						*
	*	or the user choice (need to hack the code below).									*
	*																										*
	******************************************************************************/
	class XSLTProcessorFactory{
		function get(){
			// PHP major version number
			$iVer = intval(substr(phpversion(),0,1));

			// if PHP4 and Sablotron is installed
			if($iVer<=4 && function_exists("xslt_create"))
				return new Sablotron_xp();
			// if PHP5 and Sablotron is installed				
			else if($iVer>=5 && function_exists("xslt_create"))
				return new Sablotron_xp();	
			// if PHP5, Sablotron is not installed	and XSL support is compiled			
			else if($iVer>=5 && !function_exists("xslt_create") && class_exists("XSLTProcessor"))
				return new PHPXSL_xp();
			// there is no XSLT processor installed!				
			else
				return null;				
		}
	}

	/******************************************************************************
	*																										*
	*	PHPReportsError																				*
	*	Process error messages																		*
	*																										*
	******************************************************************************/
	class PHPReportsError{
		function PHPReportsError($sMsg_=null,$sURL_=null){
			if(is_null($sMsg_))
				return;
				
			print "<p style='width:400px;background-color:#F5F5F5;border-style:solid;border-width:2;border-color:#CCCCCC;padding:10px 10px 10px 10px;margin:20px;font-family:verdana,arial,helvetica,sans-serif;color:#505050;font-size:12px;'>";
			print "<span style='font-size:18px;color:#FF0000;font-weight:bold;'>OOOOPS, THERE'S AN ERROR HERE.</span><br/><br/>";
			print $sMsg_."<br/><br/>";
			
			if(!is_null($sURL_))
				print "<a href='$sPath/help/$sURL_'>More about this error here.</a><br/><br/>";

			print "<span style='font-size:10px;font-weight:bold;'>This error message was generated by PHPReports</span>";
			print "</p>";
			exit();				
		}
	}

	class PHPReportsErrorTr {
		var $_aMsgs;
		
		function PHPReportsErrorTr(){
			$this->_aMsgs = Array();

			// default English messages
			$this->_aMsgs["OPS"]["default"]				= "OOOOPS, THERE'S AN ERROR HERE.";
			$this->_aMsgs["ERROR"]["default"]			= "This error message was generated by phpReports.";
			$this->_aMsgs["NODATA"]["default"]			= "No data was found.";
			$this->_aMsgs["NOPAGE"]["default"]			= "No PAGE element was found on your XML file.";
			$this->_aMsgs["NOIF"]["default"]				= "No database interface '%s' available.";
			$this->_aMsgs["REFUSEDCON"]["default"]		= "Connection refused.";
			$this->_aMsgs["QUERYERROR"]["default"]		= "There's an error on your SQL query.";
			$this->_aMsgs["NOCOLUMNS"]["default"]		= "No columns returned from your query.";
			$this->_aMsgs["PAGEPARSER"]["default"]		= "Could not copy the temporary page parser to the temporary directory.";
			$this->_aMsgs["DYNLINK"]["default"]			= "Specified a dynamic link but no COLUMN element found";
			$this->_aMsgs["EXPLINK"]["default"]			= "Specified an expression link but no COLUMN element found";
			$this->_aMsgs["NOFIELD"]["default"]			= "You're trying to retrieve the <b>VALUE</b> of a field called <b>%s</b>, but it is not on your SQL query. Please check your query.";
			$this->_aMsgs["NOFIELDSUM"]["default"]		= "You're trying to retrieve the <b>SUM</b> of a field called <b>%s</b>, but it is not on your SQL query. Please check your query.";
			$this->_aMsgs["NOFIELDMAX"]["default"]		= "You're trying to retrieve the <b>MAX VALUE</b> of a field called <b>%s</b>, but it is not on your SQL query. Please check your query.";
			$this->_aMsgs["NOFIELDMIN"]["default"]		= "You're trying to retrieve the <b>MIN VALUE</b> of a field called <b>%s</b>, but it is not on your SQL query. Please check your query.";
			$this->_aMsgs["NOFIELDAVG"]["default"]		= "You're trying to retrieve the <b>AVERAGE</b> of a field called <b>%s</b>, but it is not on your SQL query. Please check your query.";
			$this->_aMsgs["CANTWRITEPAGE"]["default"]	= "Can't write file <b>%s</b> to the disk. Check your disk quota/space and rights.";
			$this->_aMsgs["DYNBOOK"]["default"]			= "Specified a dynamic bookmark but no COLUMN element found";
			$this->_aMsgs["EXPBOOK"]["default"]			= "Specified an expression bookmark but no COLUMN element found";
			$this->_aMsgs["NOXMLTRANS"]["default"]		= "COL parameter <b>%s</b> not found on XML translation.";
			$this->_aMsgs["NOXSLT"]["default"]			= "There is no XSLT processor available. Check if you compiled PHP with <b>--enable-xslt</b> and the <a href=\"http://www.gingerall.com/charlie/ga/xml/p_sab.xml\">Sablotron</a> library (for <a href=\"http://www.php.net/manual/en/ref.xslt.php\">PHP4</a>) or with <b>--enable-xsl</b> (for <a href=\"http://www.php.net/manual/en/ref.xsl.php\">PHP5</a>).";
			$this->_aMsgs["NOPATH"]["default"]			= "Seems that you didn't specified the phpReports path on the PHP <b>include_path</b> statement or <b>php.ini</b>. I don't know there the classes are.";
			$this->_aMsgs["NOCODE"]["default"]			= "Could not create the output code to run your report. Please check if the webserver user have rights to write in your <b>%s</b> directory.";
			$this->_aMsgs["NOXML"]["default"]			= "Could not find the XML file with your data (<b>%s</b>) to run your report. Please check the filename and if the webserver user have rights to write in your temporary directory.";
			$this->_aMsgs["NOXMLSET"]["default"]		= "The XML input file <b>%s</b> was not found.";
			$this->_aMsgs["NOXSLTSET"]["default"]		= "The XSLT input file <b>%s</b> was not found.";
			$this->_aMsgs["NOPLUGIN"]["default"]		= "There is no <b>%s</b> output plugin (<b>%s</b>).";
			$this->_aMsgs["NOLOAD"]["default"]			= "Could not find file <b>%s</b> for report loading.";
			$this->_aMsgs["NOTEMPLATE"]["default"]		= "The template file <b>%s</b> was not found.";
			$this->_aMsgs["INVALIDCON"]["default"]		= "Your database connection handle is not valid.";

			// Brazilian Portuguese messages
			$this->_aMsgs["OPS"]["pt_BR"]					= "OOOOPS, OCORREU UM ERRO AQUI.";
			$this->_aMsgs["ERROR"]["pt_BR"]				= "Essa mensagem de erro foi gerada pelo phpReports.";
			$this->_aMsgs["NODATA"]["pt_BR"]				= "Não foram encontrados dados.";
			$this->_aMsgs["NOPAGE"]["pt_BR"]				= "Não há um elemento PAGE (página) no seu relatório.";
			$this->_aMsgs["NOIF"]["pt_BR"]				= "Não há disponível a interface '%s' para banco de dados.";
			$this->_aMsgs["REFUSEDCON"]["pt_BR"]		= "Conexão recusada.";
			$this->_aMsgs["QUERYERROR"]["pt_BR"]		= "Erro na consulta SQL.";
			$this->_aMsgs["NOCOLUMNS"]["pt_BR"]			= "Não foram retornados colunas de dados na sua consulta.";
			$this->_aMsgs["PAGEPARSER"]["pt_BR"]		= "Não consegui copiar o conversor de páginas para o diretório temporário.";
			$this->_aMsgs["DYNLINK"]["pt_BR"]			= "Foi especificado um link dinâmico mas não existe um elemento COLUMN.";
			$this->_aMsgs["EXPLINK"]["pt_BR"]			= "Foi especificado um link com uma expressão mas não existe um elemento COLUMN.";
			$this->_aMsgs["NOFIELD"]["pt_BR"]			= "Você está tentando recuperar o <b>VALOR</b> de um campo chamado <b>%s</b>, mas ele não existe na sua consulta. Por favor revise sua consulta.";
			$this->_aMsgs["NOFIELDSUM"]["pt_BR"]		= "Você está tentando recuperar a <b>SOMA</b> de um campo chamado <b>%s</b>, mas ele não existe na sua consulta. Por favor revise sua consulta.";
			$this->_aMsgs["NOFIELDMAX"]["pt_BR"]		= "Você está tentando recuperar o <b>VALOR MÁXIMO</b> de um campo chamado <b>%s</b>, mas ele não existe na sua consulta. Por favor revise sua consulta.";
			$this->_aMsgs["NOFIELDMIN"]["pt_BR"]		= "Você está tentando recuperar o <b>VALOR MÍNIMO</b> de um campo chamado <b>%s</b>, mas ele não existe na sua consulta. Por favor revise sua consulta.";
			$this->_aMsgs["NOFIELDAVG"]["pt_BR"]		= "Você está tentando recuperar o <b>VALOR MÉDIO</b> de um campo chamado <b>%s</b>, mas ele não existe na sua consulta. Por favor revise sua consulta.";
			$this->_aMsgs["CANTWRITEPAGE"]["pt_BR"]	= "Não consegui escrever o arquivo <b>%s</b> no disco. Verifique suas permissões e espaço em disco.";
			$this->_aMsgs["DYNBOOK"]["pt_BR"]			= "Foi especificado um bookmark dinâmico mas não existe um elemento COLUMN.";
			$this->_aMsgs["EXPBOOK"]["pt_BR"]			= "Foi especificado um bookmark com uma expressão mas não existe um elemento COLUMN.";
			$this->_aMsgs["NOXMLTRANS"]["pt_BR"]		= "O parâmetro <b>%s</b> de COL não foi encontrado na tradução para XML.";
			$this->_aMsgs["NOXSLT"]["pt_BR"]				= "Não há um processador XSLT disponível. Verifique se você compilou o PHP com <b>--enable-xslt</b> e a library <a href=\"http://www.gingerall.com/charlie/ga/xml/p_sab.xml\">Sablotron</a> (para o <a href=\"http://www.php.net/manual/en/ref.xslt.php\">PHP4</a>) ou com <b>--enable-xsl</b> (para o <a href=\"http://www.php.net/manual/en/ref.xsl.php\">PHP5</a>).";
			$this->_aMsgs["NOPATH"]["pt_BR"]				= "Parece que você não especificou o path do phpReports com o comando <b>include_path</b> ou no <b>php.ini</b>. Não sei onde as classes estão.";
			$this->_aMsgs["NOCODE"]["pt_BR"]				= "Não pude criar o código de saída para rodar seu relatório. Por favor verifique se o usuário do servidor web tem direitos para escrever no diretório <b>%s</b>.";
			$this->_aMsgs["NOXML"]["pt_BR"]				= "Não pude encontrar o arquivo XML com seus dados (<b>%s</b>) para rodar seu relatório. Por favor verifique o nome do arquivo e se o usuário do servidor web tem direitos de escrita no seu diretório de arquivos temporários.";
			$this->_aMsgs["NOXMLSET"]["pt_BR"]			= "O arquivo XML de entrada <b>%s</b> não foi encontrado.";
			$this->_aMsgs["NOXSLTSET"]["pt_BR"]			= "O arquivo XSLT de entrada <b>%s</b> não foi encontrado.";
			$this->_aMsgs["NOPLUGIN"]["pt_BR"]			= "O plugin de saída <b>%s</b> não existe (<b>%s</b>).";
			$this->_aMsgs["NOLOAD"]["pt_BR"]				= "Não encontrei o arquivo <b>%s</b> para carregar o relatório.";
			$this->_aMsgs["NOTEMPLATE"]["pt_BR"]		= "O arquivo de template <b>%s</b> não foi encontrado.";
			$this->_aMsgs["INVALIDCON"]["pt_BR"]		= "A variável da conexão com o banco de dados não é válida. %s";
		}

		function showMsg($sMsg_=null,$oParms_=null){
			if(!sMsg_)
				return;
			if($_SESSION["phpReportsLanguage"])
				$sLang = $_SESSION["phpReportsLanguage"];
			else
				$sLang = $GLOBALS["phpReportsLanguage"];
			if(!$sLang)
				$sLang = "default";

			$sTitle	= $this->_aMsgs["OPS"][$sLang];
			$sError	= $this->_aMsgs["ERROR"][$sLang];
			$sMsg		= $this->_aMsgs[$sMsg_][$sLang];

			// if the message have no translation
			if(!$sMsg)
				$sMsg = $this->_aMsgs[$sMsg_]["default"];
			// if the message is still null ...
			if(!$sMsg)
				$sMsg = "$sMsg_?";
			
			if($oParms_)
				$sMsg = vsprintf($sMsg,$oParms_);
			
			print "<p style='width:400px;background-color:#F5F5F5;border-style:solid;border-width:2;border-color:#CCCCCC;padding:10px 10px 10px 10px;margin:20px;font-family:verdana,arial,helvetica,sans-serif;color:#505050;font-size:12px;'>";
			print "<span style='font-size:18px;color:#FF0000;font-weight:bold;'>$sTitle</span><br/><br/>";
			print "$sMsg<br/><br/>";
			print "<span style='font-size:10px;font-weight:bold;'>$sError</span>";
			print "</p>";
			exit();				
		}
	}

	function isNumericType($sType=null){
		$sStr = "NUMBER,NUMERIC,INT,DOUBLE,DECIMAL,REAL,TINY,SHORT,LONG,FLOAT,LONGLONG,INT24,YEAR,CID,FLOAT4,FLOAT8,INT2,".
		"INT4,MONEY,OID,RELTIME,XID,DOUBLE PRECISION,SMALLINT,TINYINT,BIGINT,INT64,INT8,DATE,DATETIME";
		return stristr($sStr,$sType);
	}
?>
