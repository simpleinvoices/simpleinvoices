</head>
<body>
<table  cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td> 
</td>



<script type='text/javascript'>

function Go(){return}

/***********************************************************************
    YOU CAN MANUALLY CHANGE THE MENU HERE, BUT BE VERY CAREFUL !
***********************************************************************/
Menu1=new Array("Home","./index.php","",0,20,100);
  Menu1_1=new Array("Manage Invoices","./manage_invoices.php","",0,20,180);

Menu2=new Array("New Invoice","./manage_invoices.php","",4,0,100);
  Menu2_1=new Array("Manage Invoices","./manage_invoices.php","",0,20,210);
  Menu2_2=new Array("New Invoice - Itemised style","./invoice_itemised.php","",0,20,200);
  Menu2_3=new Array("New Invoice - Total style","./invoice_total.php","",0,20,150);
  Menu2_4=new Array("New Invoice - Consulting style","./invoice_consulting.php","",0,20,150);

Menu3=new Array("Customers","./manage_customers.php","",2,0,100);
  Menu3_1=new Array("Add New Customer","./insert_customer.php","",0,20,200);
  Menu3_2=new Array("Manage Customers","./manage_customers.php","",0,20,200);

Menu4=new Array("Products","./manage_products.php","",2,0,100);
  Menu4_1=new Array("Add New Product","./insert_product.php","",0,20,200);
  Menu4_2=new Array("Manage Products","./manage_products.php","",0,20,200);

Menu5=new Array("Billers","./manage_billers.php","",2,0,100);
  Menu5_1=new Array("Add New Biller","./insert_biller.php","",0,20,150);
  Menu5_2=new Array("Manage Billers","./manage_billers.php","",0,20,0);

Menu6=new Array("Reports","./index.php.php","",6,0,100);
  Menu6_1=new Array("Sales","./report_sales_total.php","",4,20,150);
  Menu6_1_1=new Array("Total Sales","./report_sales_total.php","",0,20,150);
  Menu6_1_2=new Array("..","./report_sales_total.php","",0,20,150);
  Menu6_1_3=new Array("..","./report_sales_total.php","",0,20,150);
  Menu6_1_4=new Array("..","./report_sales_total.php","",0,20,150);
  Menu6_2=new Array("Sales by customers","./report_sales_customers_total","",4,20,220);
  Menu6_2_1=new Array("Total Sales by Customer","./report_sales_customers_total.php","",0,20,220);
  Menu6_2_2=new Array("..","./report_sales_customers_total.php","",0,20,150);
  Menu6_2_3=new Array("..","./report_sales_customers_total.php","",0,20,150);
  Menu6_2_4=new Array("..","./report_sales_customers_total.php","",0,20,150);
  Menu6_3=new Array("Tax","./report_tax_total.php","",4,20,220);
  Menu6_3_1=new Array("Taxes total","./report_tax_total.php","",0,20,150);
  Menu6_3_2=new Array("..","./report_tax_total.php","",0,20,150);
  Menu6_3_3=new Array("..","./report_tax_total.php","",0,20,150);
  Menu6_3_4=new Array("..","./report_tax_total.php","",0,20,150);
  Menu6_4=new Array("Product sales","./report_products_sold_total.php","",4,20,0);
  Menu6_4_1=new Array("Products sold - total","./report_products_sold_total.php","",0,20,220);
  Menu6_4_2=new Array("..","./report_products_sold_total.php","",0,20,150);
  Menu6_4_3=new Array("..","./report_products_sold_total.php","",0,20,150);
  Menu6_4_4=new Array("..","./report_products_sold_total.php","",0,20,150);
  Menu6_5=new Array("Products by customer","./report_products_sold_by_customer.php","",4,20,0);
  Menu6_5_1=new Array("Products sold - Customer - Total","./report_products_sold_by_customer.php","",0,20,220);
  Menu6_5_2=new Array("..","./report_products_sold_by_customer.php","",0,20,150);
  Menu6_5_3=new Array("..","./report_products_sold_by_customer.php","",0,20,150);
  Menu6_5_4=new Array("..","./report_products_sold_by_customer.php","",0,20,150);
  Menu6_6=new Array("Biller sales","./report_biller_total.php","",4,20,0);
  Menu6_6_1=new Array("Biller sales - Total","./report_biller_total.php","",0,20,220);
  Menu6_6_2=new Array("Biller sales by Customer - Total","./report_biller_by_customer.php","",0,20,220);
  Menu6_6_3=new Array("..","./report_products_sold_by_customer.php","",0,20,150);
  Menu6_6_4=new Array("..","./report_products_sold_by_customer.php","",0,20,150);

Menu7=new Array("Options","./index.php.php","",8,20,100);
  Menu7_1=new Array("System Defaults","./system_default_details.php","",0,20,200);
  Menu7_2=new Array("Tax Rates","manage_tax_rates.php","",2,20,100);
  Menu7_2_1=new Array("Manage Tax Rates","./manage_tax_rates.php","",0,20,150);
  Menu7_2_2=new Array("Add Custom Tax Rate","./insert_tax_rate.php","",0,20,100);
  Menu7_3=new Array("Invoice Preferences","./manage_preferences.php","",2,20,220);
  Menu7_3_1=new Array("Manage Invoice Preferences","./manage_preferences.php","",0,20,220);
  Menu7_3_2=new Array("Add New Invoice Preferences","./insert_preference.php","",0,20,220);
  Menu7_4=new Array("Database Upgrade Manager","./database_sqlpatches.php","",0,20,0);
  Menu7_5=new Array("Backup Database","./backup_database.php","",0,20,0);
  Menu7_6=new Array("Sanity check of invoices","./sanity_check.php","",0,20,200);
  Menu7_7=new Array("Help","inline_instructions.php","",6,20,220);
  Menu7_7_1=new Array("Installation","./inline_instructions.php#installation","",0,20,220);
  Menu7_7_2=new Array("Upgrading Simple Invoices","./inline_instructions.php#upgrading","",0,20,220);
  Menu7_7_3=new Array("Prepare Simple Invoices for use","./inline_instructions.php#prepare","",0,20,170);
  Menu7_7_4=new Array("Using Simple Invoices","./inline_instructions.php#use","",0,20,170);
  Menu7_7_5=new Array("Frequently Asked Questions","./inline_faqs.php","",0,20,220);
  Menu7_7_6=new Array("Get Help","help.php","",0,20,80);
  Menu7_8=new Array("About","about.php","",5,20,80);
  Menu7_8_1=new Array("About","./about.php","",0,20,170);
  Menu7_8_2=new Array("Change Log","./inline_changelog.php","",0,20,170);
  Menu7_8_3=new Array("Road Map","./inline_roadmap.php","",0,20,170);
  Menu7_8_4=new Array("Credits","./inline_credits.php","",0,20,170);
  Menu7_8_5=new Array("License","./inline_license.php","",0,20,170);


var NoOffFirstLineMenus=7;	// Number of first level items
var LowBgColor='D4D0C8';		// Background color when mouse is not over
var LowSubBgColor='D4D0C8';	// Background color when mouse is not over on subs
var HighBgColor='3A6EA5';	// Background color when mouse is over
var HighSubBgColor='3A6EA5';	// Background color when mouse is over on subs
var FontLowColor='000000';	// Font color when mouse is not over
var FontSubLowColor='000000';	// Font color subs when mouse is not over
var FontHighColor='FFFFFF';	// Font color when mouse is over
var FontSubHighColor='FFFFFF';	// Font color subs when mouse is over
var BorderColor='C0C0C0';	// Border color
var BorderSubColor='C0C0C0';	// Border color for subs
var BorderWidth=1;		// Border width
var BorderBtwnElmnts=1;		// Border between elements 1 or 0
var FontFamily="Tahoma, Verdana, Arial"	        // Font family menu items
var FontSize=10;			// Font size menu items
var FontBold=0;			// Bold menu items 1 or 0
var FontItalic=0;		// Italic menu items 1 or 0
var MenuTextCentered='left';	// Item text position 'left', 'center' or 'right'
var MenuCentered='left';	// Menu horizontal position 'left', 'center' or 'right'
var MenuVerticalCentered='top';	// Menu vertical position 'top', 'middle','bottom' or static
var ChildOverlap=.2;		// horizontal overlap child/ parent
var ChildVerticalOverlap=.2;	// vertical overlap child/ parent
var StartTop=1;		// Menu offset x coordinate
var StartLeft=1;		// Menu offset y coordinate
var VerCorrect=0;		// Multiple frames y correction
var HorCorrect=0;		// Multiple frames x correction
var LeftPaddng=3;		// Left padding
var TopPaddng=2;		// Top padding
var FirstLineHorizontal=1;	// SET TO 1 FOR HORIZONTAL MENU, 0 FOR VERTICAL
var MenuFramesVertical=1;	// Frames in cols or rows 1 or 0
var DissapearDelay=1000;	// delay before menu folds in
var TakeOverBgColor=1;		// Menu frame takes over background color subitem frame
var FirstLineFrame='navig';	// Frame where first level appears
var SecLineFrame='space';	// Frame where sub levels appear
var DocTargetFrame='space';	// Frame where target documents appear
var TargetLoc='';		// span id for relative positioning
var HideTop=0;			// Hide first level when loading new document 1 or 0
var MenuWrap=1;			// enables/ disables menu wrap 1 or 0
var RightToLeft=0;		// enables/ disables right to left unfold 1 or 0
var UnfoldsOnClick=0;		// Level 1 unfolds onclick/ onmouseover
var WebMasterCheck=0;		// menu tree checking on or off 1 or 0
var ShowArrow=1;		// Uses arrow gifs when 1
var KeepHilite=1;		// Keep selected path highligthed
var Arrws=['./images/tri.gif',5,10,'./images/tridown.gif',10,5,'./images/trileft.gif',5,10];	// Arrow source, width and height


/***********************************************************************
   DO NOT EDIT ANYTHING BELOW THIS LINE - IT WILL BREAK THE SCRIPT !
***********************************************************************/

var AgntUsr=navigator.userAgent.toLowerCase();
var DomYes=document.getElementById?1:0;
var NavYes=AgntUsr.indexOf('mozilla')!=-1&&AgntUsr.indexOf('compatible')==-1?1:0;
var ExpYes=AgntUsr.indexOf('msie')!=-1?1:0;
var Opr=AgntUsr.indexOf('opera')!=-1?1:0;
var Opr6orless=window.opera && navigator.userAgent.search(/opera.[1-6]/i)!=-1 //DynamicDrive.com added code
var DomNav=DomYes&&NavYes?1:0;
var DomExp=DomYes&&ExpYes?1:0;
var Nav4=NavYes&&!DomYes&&document.layers?1:0;
var Exp4=ExpYes&&!DomYes&&document.all?1:0;
var PosStrt=(NavYes||ExpYes)&&!Opr6orless?1:0;
var FrstLoc,ScLoc,DcLoc;
var ScWinWdth,ScWinHght,FrstWinWdth,FrstWinHght;
var ScLdAgainWin;
var FirstColPos,SecColPos,DocColPos;
var RcrsLvl=0;
var FrstCreat=1,Loadd=0,Creatd=0,IniFlg,AcrssFrms=1;
var FrstCntnr=null,CurrntOvr=null,CloseTmr=null;
var CntrTxt,TxtClose,ImgStr;
var Ztop=100;
var ShwFlg=0;
var M_StrtTp=StartTop,M_StrtLft=StartLeft;
var StaticPos=0;
var LftXtra=DomNav&&!Opr?LeftPaddng:0; //Changed for Opera
var TpXtra=DomNav?TopPaddng:0;
var M_Hide=Nav4?'hide':'hidden';
var M_Show=Nav4?'show':'visible';
var Par=parent.frames[0]&&FirstLineFrame!=SecLineFrame?parent:window;
var Doc=Par.document;
var Bod=Doc.body;
var Trigger=NavYes&&!Opr?Par:Bod; //Changed for Opera

MenuTextCentered=MenuTextCentered==1||MenuTextCentered=='center'?'center':MenuTextCentered==0||MenuTextCentered!='right'?'left':'right';
WbMstrAlrts=["Item not defined: ","Item needs height: ","Item needs width: "];

if(Trigger.onload)Dummy=Trigger.onload;
if(DomNav||Opr)Trigger.addEventListener('load',Go,false); //Changed for Opera
else Trigger.onload=Go;

function Dummy(){return}

function CnclSlct(){return false}

function RePos(){
	FrstWinWdth=ExpYes?FrstLoc.document.body.clientWidth:FrstLoc.innerWidth;
	FrstWinHght=ExpYes?FrstLoc.document.body.clientHeight:FrstLoc.innerHeight;
	ScWinWdth=ExpYes?ScLoc.document.body.clientWidth:ScLoc.innerWidth;
	ScWinHght=ExpYes?ScLoc.document.body.clientHeight:ScLoc.innerHeight;
	if(MenuCentered=='justify'&&FirstLineHorizontal){
		FrstCntnr.style.width=FrstWinWdth;
		ClcJus();
		var P=FrstCntnr.FrstMbr,W=Menu1[5],i;
		for(i=0;i<NoOffFirstLineMenus;i++){P.style.width=W;P=P.PrvMbr}}
	StaticPos=-1;
	if(TargetLoc)ClcTrgt();
	if(MenuCentered)ClcLft();
	if(MenuVerticalCentered)ClcTp();
	PosMenu(FrstCntnr,StartTop,StartLeft)}

function UnLoaded(){
	if(CloseTmr)clearTimeout(CloseTmr);
	Loadd=0; Creatd=0;
	if(HideTop){
		var FCStyle=Nav4?FrstCntnr:FrstCntnr.style;
		FCStyle.visibility=M_Hide}}

function ReDoWhole(){
	if(ScWinWdth!=ScLoc.innerWidth||ScWinHght!=ScLoc.innerHeight||FrstWinWdth!=FrstLoc.innerWidth||FrstWinHght!=FrstLoc.innerHeight)Doc.location.reload()}

function Check(WMnu,NoOf){
	var i,array,ArrayLoc;
	ArrayLoc=parent.frames[0]?parent.frames[FirstLineFrame]:self;
	for(i=0;i<NoOf;i++){
		array=WMnu+eval(i+1);
		if(!ArrayLoc[array]){WbMstrAlrt(0,array); return false}
		if(i==0){	if(!ArrayLoc[array][4]){WbMstrAlrt(1,array); return false}
			if(!ArrayLoc[array][5]){WbMstrAlrt(2,array); return false}}
		if(ArrayLoc[array][3])if(!Check(array+'_',ArrayLoc[array][3])) return false}
	return true}

function WbMstrAlrt(No,Xtra){
	return confirm(WbMstrAlrts[No]+Xtra+'   ')}

function Go(){
	Dummy();
	if(Loadd||!PosStrt)return;
	BeforeStart();
	Creatd=0; Loadd=1;
	status='Building menu';
	if(FrstCreat){
		if(FirstLineFrame =="" || !parent.frames[FirstLineFrame]){
			FirstLineFrame=SecLineFrame;
			if(FirstLineFrame =="" || !parent.frames[FirstLineFrame]){
				FirstLineFrame=SecLineFrame=DocTargetFrame;
				if(FirstLineFrame =="" || !parent.frames[FirstLineFrame])FirstLineFrame=SecLineFrame=DocTargetFrame=''}}
		if(SecLineFrame =="" || !parent.frames[SecLineFrame]){
			SecLineFrame=DocTargetFrame;
			if(SecLineFrame =="" || !parent.frames[SecLineFrame])SecLineFrame=DocTargetFrame=FirstLineFrame}
		if(DocTargetFrame =="" || !parent.frames[DocTargetFrame])DocTargetFrame=SecLineFrame;
		if(WebMasterCheck){	if(!Check('Menu',NoOffFirstLineMenus)){status='build aborted';return}}
		FrstLoc=FirstLineFrame!=""?parent.frames[FirstLineFrame]:window;
		ScLoc=SecLineFrame!=""?parent.frames[SecLineFrame]:window;
		DcLoc=DocTargetFrame!=""?parent.frames[DocTargetFrame]:window;
		if (FrstLoc==ScLoc) AcrssFrms=0;
		if (AcrssFrms)FirstLineHorizontal=MenuFramesVertical?0:1;
		FrstWinWdth=ExpYes?FrstLoc.document.body.clientWidth:FrstLoc.innerWidth;
		FrstWinHght=ExpYes?FrstLoc.document.body.clientHeight:FrstLoc.innerHeight;
		ScWinWdth=ExpYes?ScLoc.document.body.clientWidth:ScLoc.innerWidth;
		ScWinHght=ExpYes?ScLoc.document.body.clientHeight:ScLoc.innerHeight;
		if(Nav4){	CntrTxt=MenuTextCentered!='left'?"<div align='"+MenuTextCentered+"'>":"";
			TxtClose="</font>"+MenuTextCentered!='left'?"</div>":""}}
	FirstColPos=Nav4?FrstLoc.document:FrstLoc.document.body;
	SecColPos=Nav4?ScLoc.document:ScLoc.document.body;
	DocColPos=Nav4?DcLoc.document:ScLoc.document.body;
	if (TakeOverBgColor)FirstColPos.bgColor=AcrssFrms?SecColPos.bgColor:DocColPos.bgColor;
	if(MenuCentered=='justify'&&FirstLineHorizontal)ClcJus();
	if(FrstCreat){
		FrstCntnr=CreateMenuStructure('Menu',NoOffFirstLineMenus);
		FrstCreat=AcrssFrms?0:1}
	else CreateMenuStructureAgain('Menu',NoOffFirstLineMenus);
	if(TargetLoc)ClcTrgt();
	if(MenuCentered)ClcLft();
	if(MenuVerticalCentered)ClcTp();
	PosMenu(FrstCntnr,StartTop,StartLeft);
	IniFlg=1;
	Initiate();
	Creatd=1;
	if (AcrssFrms) 	//Added for Opera
		{	 //Added for Opera
		ScLdAgainWin=ExpYes?ScLoc.document.body:ScLoc;
		ScLdAgainWin.onunload=UnLoaded;
		}	 //Added for Opera
	Trigger.onresize=Nav4?ReDoWhole:RePos;
	AfterBuild();
	if(MenuVerticalCentered=='static'&&!AcrssFrms)setInterval('KeepPos()',250);
	status='Menu ready for use'}

function KeepPos(){
	var TS=ExpYes?FrstLoc.document.body.scrollTop:FrstLoc.pageYOffset;
	if(TS!=StaticPos){
		var FCStyle=Nav4?FrstCntnr:FrstCntnr.style;
		FCStyle.top=FrstCntnr.OrgTop=StartTop+TS;StaticPos=TS}}

function ClcJus(){
	var a=BorderBtwnElmnts?1:2,b=BorderBtwnElmnts?BorderWidth:0;
	var Size=Math.round(((FrstWinWdth-a*BorderWidth)/NoOffFirstLineMenus)-b),i,j;
	for(i=1;i<NoOffFirstLineMenus+1;i++){j=eval('Menu'+i);j[5]=Size}
	StartLeft=0}

function ClcTrgt(){
	var TLoc=Nav4?FrstLoc.document.layers[TargetLoc]:DomYes?FrstLoc.document.getElementById(TargetLoc):FrstLoc.document.all[TargetLoc];
	StartTop=M_StrtTp;
	StartLeft=M_StrtLft;
	if(DomYes){
		while(TLoc){StartTop+=TLoc.offsetTop;StartLeft+=TLoc.offsetLeft;TLoc=TLoc.offsetParent}}
	else{	StartTop+=Nav4?TLoc.pageY:TLoc.offsetTop;StartLeft+=Nav4?TLoc.pageX:TLoc.offsetLeft}}

function ClcLft(){
	if(MenuCentered!='left'&&MenuCentered!='justify'){
		var Size=FrstWinWdth-(!Nav4?parseInt(FrstCntnr.style.width):FrstCntnr.clip.width);
		StartLeft=M_StrtLft;
		StartLeft+=MenuCentered=='right'?Size:Size/2}}

function ClcTp(){
	if(MenuVerticalCentered!='top'&&MenuVerticalCentered!='static'){
		var Size=FrstWinHght-(!Nav4?parseInt(FrstCntnr.style.height):FrstCntnr.clip.height);
		StartTop=M_StrtTp;
		StartTop+=MenuVerticalCentered=='bottom'?Size:Size/2}}

function PosMenu(CntnrPntr,Tp,Lt){
	var Topi,Lefti,Hori;
	var Cntnr=CntnrPntr;
	var Mmbr=Cntnr.FrstMbr;
	var CntnrStyle=!Nav4?Cntnr.style:Cntnr;
	var MmbrStyle=!Nav4?Mmbr.style:Mmbr;
	var PadL=Mmbr.value.indexOf('<')==-1?LftXtra:0;
	var PadT=Mmbr.value.indexOf('<')==-1?TpXtra:0;
	var MmbrWt=!Nav4?parseInt(MmbrStyle.width)+PadL:MmbrStyle.clip.width;
	var MmbrHt=!Nav4?parseInt(MmbrStyle.height)+PadT:MmbrStyle.clip.height;
	var CntnrWt=!Nav4?parseInt(CntnrStyle.width):CntnrStyle.clip.width;
	var CntnrHt=!Nav4?parseInt(CntnrStyle.height):CntnrStyle.clip.height;
	var SubTp,SubLt;
	RcrsLvl++;
	if (RcrsLvl==1 && AcrssFrms)!MenuFramesVertical?Tp=FrstWinHght-CntnrHt+(Nav4?4:0):Lt=RightToLeft?0:FrstWinWdth-CntnrWt+(Nav4?4:0);
	if (RcrsLvl==2 && AcrssFrms)!MenuFramesVertical?Tp=0:Lt=RightToLeft?ScWinWdth-CntnrWt:0;
	if (RcrsLvl==2 && AcrssFrms){Tp+=VerCorrect;Lt+=HorCorrect}
	CntnrStyle.top=RcrsLvl==1?Tp:0;
	Cntnr.OrgTop=Tp;
	CntnrStyle.left=RcrsLvl==1?Lt:0;
	Cntnr.OrgLeft=Lt;
	if (RcrsLvl==1 && FirstLineHorizontal){
		Hori=1;Lefti=CntnrWt-MmbrWt-2*BorderWidth;Topi=0}
	else{	Hori=Lefti=0;Topi=CntnrHt-MmbrHt-2*BorderWidth}
	while(Mmbr!=null){
		MmbrStyle.left=Lefti+BorderWidth;
		MmbrStyle.top=Topi+BorderWidth;
		if(Nav4)Mmbr.CmdLyr.moveTo(Lefti+BorderWidth,Topi+BorderWidth);
		if(Mmbr.ChildCntnr){
			if(RightToLeft)ChldCntnrWdth=Nav4?Mmbr.ChildCntnr.clip.width:parseInt(Mmbr.ChildCntnr.style.width);
			if(Hori){	SubTp=Topi+MmbrHt+2*BorderWidth;
				SubLt=RightToLeft?Lefti+MmbrWt-ChldCntnrWdth:Lefti}
			else{	SubLt=RightToLeft?Lefti-ChldCntnrWdth+ChildOverlap*MmbrWt+BorderWidth:Lefti+(1-ChildOverlap)*MmbrWt+BorderWidth;
				SubTp=RcrsLvl==1&&AcrssFrms?Topi:Topi+ChildVerticalOverlap*MmbrHt}
			PosMenu(Mmbr.ChildCntnr,SubTp,SubLt)}
		Mmbr=Mmbr.PrvMbr;
		if(Mmbr){	MmbrStyle=!Nav4?Mmbr.style:Mmbr;
			PadL=Mmbr.value.indexOf('<')==-1?LftXtra:0;
			PadT=Mmbr.value.indexOf('<')==-1?TpXtra:0;
			MmbrWt=!Nav4?parseInt(MmbrStyle.width)+PadL:MmbrStyle.clip.width;
			MmbrHt=!Nav4?parseInt(MmbrStyle.height)+PadT:MmbrStyle.clip.height;
			Hori?Lefti-=BorderBtwnElmnts?(MmbrWt+BorderWidth):(MmbrWt):Topi-=BorderBtwnElmnts?(MmbrHt+BorderWidth):(MmbrHt)}}
	RcrsLvl--}

function Initiate(){
	if(IniFlg){	Init(FrstCntnr);IniFlg=0;
		if(ShwFlg)AfterCloseAll();ShwFlg=0}}

function Init(CntnrPntr){
	var Mmbr=CntnrPntr.FrstMbr;
	var MCStyle=Nav4?CntnrPntr:CntnrPntr.style;
	RcrsLvl++;
	MCStyle.visibility=RcrsLvl==1?M_Show:M_Hide;
	while(Mmbr!=null){
		if(Mmbr.Hilite){Mmbr.Hilite=0;if(KeepHilite)LowItem(Mmbr)}
		if(Mmbr.ChildCntnr) Init(Mmbr.ChildCntnr);
		Mmbr=Mmbr.PrvMbr}
	RcrsLvl--}

function ClearAllChilds(Pntr){
	var CPCCStyle;
	while (Pntr){
		if(Pntr.Hilite){
			Pntr.Hilite=0;
			if(KeepHilite)LowItem(Pntr);
			if(Pntr.ChildCntnr){
				CPCCStyle=Nav4?Pntr.ChildCntnr:Pntr.ChildCntnr.style;
				CPCCStyle.visibility=M_Hide;
				ClearAllChilds(Pntr.ChildCntnr.FrstMbr)}
			break}
		Pntr=Pntr.PrvMbr}}

function GoTo(){
	if(this.LinkTxt){
		status='';
		var HP=Nav4?this.LowLyr:this;
		LowItem(HP);
		this.LinkTxt.indexOf('javascript:')!=-1?eval(this.LinkTxt):DcLoc.location.href=this.LinkTxt}}

function HiliteItem(P){
	if(Nav4){
		if(P.ro)P.document.images[P.rid].src=P.ri2;
		else{	if(P.HiBck)P.bgColor=P.HiBck;
			if(P.value.indexOf('<img')==-1){
				P.document.write(P.Ovalue);
				P.document.close()}}}
	else{	if(P.ro){	var Lc=P.Level==1?FrstLoc:ScLoc;
			Lc.document.images[P.rid].src=P.ri2}
		else{	if(P.HiBck)P.style.backgroundColor=P.HiBck;
			if(P.HiFntClr)P.style.color=P.HiFntClr}}
	P.Hilite=1}

function LowItem(P){
	if(P.ro){	if(Nav4)P.document.images[P.rid].src=P.ri1;
		else{	var Lc=P.Level==1?FrstLoc:ScLoc;
			Lc.document.images[P.rid].src=P.ri1}}
	else{	if(Nav4){	if(P.LoBck)P.bgColor=P.LoBck;
			if(P.value.indexOf('<img')==-1){
				P.document.write(P.value);
				P.document.close()}}
		else{	if(P.LoBck)P.style.backgroundColor=P.LoBck;
			if(P.LwFntClr)P.style.color=P.LwFntClr}}}

function OpenMenu(){
	if(!Loadd||!Creatd) return;
	var TpScrlld=ExpYes?ScLoc.document.body.scrollTop:ScLoc.pageYOffset;
	var LScrlld=ExpYes?ScLoc.document.body.scrollLeft:ScLoc.pageXOffset;
	var CCnt=Nav4?this.LowLyr.ChildCntnr:this.ChildCntnr;
	var ThisHt=Nav4?this.clip.height:parseInt(this.style.height);
	var ThisWt=Nav4?this.clip.width:parseInt(this.style.width);
	var ThisLft=AcrssFrms&&this.Level==1&&!FirstLineHorizontal?0:Nav4?this.Container.left:parseInt(this.Container.style.left);
	var ThisTp=AcrssFrms&&this.Level==1&&FirstLineHorizontal?0:Nav4?this.Container.top:parseInt(this.Container.style.top);
	var HP=Nav4?this.LowLyr:this;
	CurrntOvr=this;
	IniFlg=0;
	ClearAllChilds(this.Container.FrstMbr);
	HiliteItem(HP);
	if(CCnt!=null){
		if(!ShwFlg){ShwFlg=1;	BeforeFirstOpen()}
		var CCW=Nav4?this.LowLyr.ChildCntnr.clip.width:parseInt(this.ChildCntnr.style.width);
		var CCH=Nav4?this.LowLyr.ChildCntnr.clip.height:parseInt(this.ChildCntnr.style.height);
		var ChCntTL=Nav4?this.LowLyr.ChildCntnr:this.ChildCntnr.style;
		var SubLt=AcrssFrms&&this.Level==1?CCnt.OrgLeft+ThisLft+LScrlld:CCnt.OrgLeft+ThisLft;
		var SubTp=AcrssFrms&&this.Level==1?CCnt.OrgTop+ThisTp+TpScrlld:CCnt.OrgTop+ThisTp;
		if(MenuWrap){
			if(RightToLeft){
				if(SubLt<LScrlld)SubLt=this.Level==1?LScrlld:SubLt+(CCW+(1-2*ChildOverlap)*ThisWt);
				if(SubLt+CCW>ScWinWdth+LScrlld)SubLt=ScWinWdth+LScrlld-CCW}
			else{	if(SubLt+CCW>ScWinWdth+LScrlld)SubLt=this.Level==1?ScWinWdth+LScrlld-CCW:SubLt-(CCW+(1-2*ChildOverlap)*ThisWt);
				if(SubLt<LScrlld)SubLt=LScrlld}
			if(SubTp+CCH>TpScrlld+ScWinHght)SubTp=this.Level==1?SubTp=TpScrlld+ScWinHght-CCH:SubTp-CCH+(1-2*ChildVerticalOverlap)*ThisHt;
			if(SubTp<TpScrlld)SubTp=TpScrlld}
		ChCntTL.top=SubTp;ChCntTL.left=SubLt;ChCntTL.visibility=M_Show}
	status=this.LinkTxt}

function OpenMenuClick(){
	if(!Loadd||!Creatd) return;
	var HP=Nav4?this.LowLyr:this;
	CurrntOvr=this;
	IniFlg=0;
	ClearAllChilds(this.Container.FrstMbr);
	HiliteItem(HP);
	status=this.LinkTxt}

function CloseMenu(){
	if(!Loadd||!Creatd) return;
	if(!KeepHilite){
		var HP=Nav4?this.LowLyr:this;
		LowItem(HP)}
	status='';
	if(this==CurrntOvr){
		IniFlg=1;
		if(CloseTmr)clearTimeout(CloseTmr);
		CloseTmr=setTimeout('Initiate(CurrntOvr)',DissapearDelay)}}

function CntnrSetUp(Wdth,Hght,NoOff){
	var x=RcrsLvl==1?BorderColor:BorderSubColor;
	this.FrstMbr=null;
	this.OrgLeft=this.OrgTop=0;
	if(x)this.bgColor=x;
	if(Nav4){	this.visibility='hide';
		this.resizeTo(Wdth,Hght)}
	else{	if(x)this.style.backgroundColor=x;
		this.style.width=Wdth;
		this.style.height=Hght;
		this.style.fontFamily=FontFamily;
		this.style.fontWeight=FontBold?'bold':'normal';
		this.style.fontStyle=FontItalic?'italic':'normal';
		this.style.fontSize=FontSize+'pt';
		this.style.zIndex=RcrsLvl+Ztop}}

function MbrSetUp(MmbrCntnr,PrMmbr,WhatMenu,Wdth,Hght){
	var Location=RcrsLvl==1?FrstLoc:ScLoc;
	var MemVal=eval(WhatMenu+'[0]');
	var t,T,L,W,H,S;
	var a,b,c,d;
	this.PrvMbr=PrMmbr;
	this.Level=RcrsLvl;
	this.LinkTxt=eval(WhatMenu+'[1]');
	this.Container=MmbrCntnr;
	this.ChildCntnr=null;
	this.Hilite=0;
	this.style.overflow='hidden';
	this.style.cursor=ExpYes&&(this.LinkTxt||(RcrsLvl==1&&UnfoldsOnClick))?'hand':'default';
	this.ro=0;
	if(MemVal.indexOf('rollover')!=-1){
		this.ro=1;
		this.ri1=MemVal.substring(MemVal.indexOf(':')+1,MemVal.lastIndexOf(':'));
		this.ri2=MemVal.substring(MemVal.lastIndexOf(':')+1,MemVal.length);
		this.rid=WhatMenu+'i';MemVal="<img src='"+this.ri1+"' name='"+this.rid+"'>"}
	this.value=MemVal;
	if(RcrsLvl==1){
		a=LowBgColor;
		b=HighBgColor;
		c=FontLowColor;
		d=FontHighColor}
	else{	a=LowSubBgColor;
		b=HighSubBgColor;
		c=FontSubLowColor;
		d=FontSubHighColor}
	this.LoBck=a;
	this.LwFntClr=c;
	this.HiBck=b;
	this.HiFntClr=d;
	this.style.color=this.LwFntClr;
	if(this.LoBck)this.style.backgroundColor=this.LoBck;
	this.style.textAlign=MenuTextCentered;
	if(eval(WhatMenu+'[2]'))this.style.backgroundImage="url(\'"+eval(WhatMenu+'[2]')+"\')";
	if(MemVal.indexOf('<')==-1){
		this.style.width=Wdth-LftXtra;
		this.style.height=Hght-TpXtra;
		this.style.paddingLeft=LeftPaddng;
		this.style.paddingTop=TopPaddng}
	else{	this.style.width=Wdth;
		this.style.height=Hght}
	if(MemVal.indexOf('<')==-1&&DomYes){
		t=Location.document.createTextNode(MemVal);
		this.appendChild(t)}
	else this.innerHTML=MemVal;
	if(eval(WhatMenu+'[3]')&&ShowArrow){
		a=RcrsLvl==1&&FirstLineHorizontal?3:RightToLeft?6:0;
		S=Arrws[a];
		W=Arrws[a+1];
		H=Arrws[a+2];
		T=RcrsLvl==1&&FirstLineHorizontal?Hght-H-2:(Hght-H)/2;
		L=RightToLeft?2:Wdth-W-2;
		if(DomYes){

			t=Location.document.createElement('img');
			this.appendChild(t);
			t.style.position='absolute';
			t.src=S;

			t.style.width=W;
			t.style.height=H;
			t.style.top=T;
			t.style.left=L}
		else{	MemVal+="<div style='position:absolute; top:"+T+"; left:"+L+"; width:"+W+"; height:"+H+";visibility:inherit'><img src='"+S+"'></div>";
			this.innerHTML=MemVal}}
	if(ExpYes){this.onselectstart=CnclSlct;
		this.onmouseover=RcrsLvl==1&&UnfoldsOnClick?OpenMenuClick:OpenMenu;
		this.onmouseout=CloseMenu;
		this.onclick=RcrsLvl==1&&UnfoldsOnClick&&eval(WhatMenu+'[3]')?OpenMenu:GoTo	}
	else{	RcrsLvl==1&&UnfoldsOnClick?this.addEventListener('mouseover',OpenMenuClick,false):this.addEventListener('mouseover',OpenMenu,false);
		this.addEventListener('mouseout',CloseMenu,false);
		RcrsLvl==1&&UnfoldsOnClick&&eval(WhatMenu+'[3]')?this.addEventListener('click',OpenMenu,false):this.addEventListener('click',GoTo,false)}}

function NavMbrSetUp(MmbrCntnr,PrMmbr,WhatMenu,Wdth,Hght){
	var a,b,c,d;
	if(RcrsLvl==1){
		a=LowBgColor;
		b=HighBgColor;
		c=FontLowColor;
		d=FontHighColor}
	else {	a=LowSubBgColor;
		b=HighSubBgColor;
		c=FontSubLowColor;
		d=FontSubHighColor	}
	this.value=eval(WhatMenu+'[0]');
	this.ro=0;
	if(this.value.indexOf('rollover')!=-1){
		this.ro=1;
		this.ri1=this.value.substring(this.value.indexOf(':')+1,this.value.lastIndexOf(':'));
		this.ri2=this.value.substring(this.value.lastIndexOf(':')+1,this.value.length);
		this.rid=WhatMenu+'i';this.value="<img src='"+this.ri1+"' name='"+this.rid+"'>"}
	if(LeftPaddng&&this.value.indexOf('<')==-1&&MenuTextCentered=='left')this.value='&nbsp\;'+this.value;
	if(FontBold)this.value=this.value.bold();
	if(FontItalic)this.value=this.value.italics();
	this.Ovalue=this.value;
	this.value=this.value.fontcolor(c);
	this.Ovalue=this.Ovalue.fontcolor(d);
	this.value=CntrTxt+"<font face='"+FontFamily+"' point-size='"+FontSize+"'>"+this.value+TxtClose;
	this.Ovalue=CntrTxt+"<font face='"+FontFamily+"' point-size='"+FontSize+"'>"+this.Ovalue+TxtClose;
	this.LoBck=a;
	this.HiBck=b;
	this.ChildCntnr=null;
	this.PrvMbr=PrMmbr;
	this.Hilite=0;
	this.visibility='inherit';
	if(this.LoBck)this.bgColor=this.LoBck;
	this.resizeTo(Wdth,Hght);
	if(!AcrssFrms&&eval(WhatMenu+'[2]'))this.background.src=eval(WhatMenu+'[2]');
	this.document.write(this.value);
	this.document.close();
	this.CmdLyr=new Layer(Wdth,MmbrCntnr);
	this.CmdLyr.Level=RcrsLvl;
	this.CmdLyr.LinkTxt=eval(WhatMenu+'[1]');
	this.CmdLyr.visibility='inherit';
	this.CmdLyr.onmouseover=RcrsLvl==1&&UnfoldsOnClick?OpenMenuClick:OpenMenu;
	this.CmdLyr.onmouseout=CloseMenu;
	this.CmdLyr.captureEvents(Event.MOUSEUP);
	this.CmdLyr.onmouseup=RcrsLvl==1&&UnfoldsOnClick&&eval(WhatMenu+'[3]')?OpenMenu:GoTo;
	this.CmdLyr.LowLyr=this;
	this.CmdLyr.resizeTo(Wdth,Hght);
	this.CmdLyr.Container=MmbrCntnr;
	if(eval(WhatMenu+'[3]')&&ShowArrow){
		a=RcrsLvl==1&&FirstLineHorizontal?3:RightToLeft?6:0;
		this.CmdLyr.ImgLyr=new Layer(Arrws[a+1],this.CmdLyr);
		this.CmdLyr.ImgLyr.visibility='inherit';
		this.CmdLyr.ImgLyr.top=RcrsLvl==1&&FirstLineHorizontal?Hght-Arrws[a+2]-2:(Hght-Arrws[a+2])/2;
		this.CmdLyr.ImgLyr.left=RightToLeft?2:Wdth-Arrws[a+1]-2;
		this.CmdLyr.ImgLyr.width=Arrws[a+1];
		this.CmdLyr.ImgLyr.height=Arrws[a+2];
		ImgStr="<img src='"+Arrws[a]+"' width='"+Arrws[a+1]+"' height='"+Arrws[a+2]+"'>";
		this.CmdLyr.ImgLyr.document.write(ImgStr);
		this.CmdLyr.ImgLyr.document.close()}}

function CreateMenuStructure(MName,NumberOf){
	RcrsLvl++;
	var i,NoOffSubs,Mbr,Wdth=0,Hght=0;
	var PrvMmbr=null;
	var WMnu=MName+'1';
	var MenuWidth=eval(WMnu+'[5]');
	var MenuHeight=eval(WMnu+'[4]');
	var Location=RcrsLvl==1?FrstLoc:ScLoc;
	if (RcrsLvl==1&&FirstLineHorizontal){
		for(i=1;i<NumberOf+1;i++){
			WMnu=MName+eval(i);
			Wdth=eval(WMnu+'[5]')?Wdth+eval(WMnu+'[5]'):Wdth+MenuWidth}
		Wdth=BorderBtwnElmnts?Wdth+(NumberOf+1)*BorderWidth:Wdth+2*BorderWidth;Hght=MenuHeight+2*BorderWidth}
	else{	for(i=1;i<NumberOf+1;i++){
			WMnu=MName+eval(i);
			Hght=eval(WMnu+'[4]')?Hght+eval(WMnu+'[4]'):Hght+MenuHeight}
		Hght=BorderBtwnElmnts?Hght+(NumberOf+1)*BorderWidth:Hght+2*BorderWidth;Wdth=MenuWidth+2*BorderWidth}
	if(DomYes){
		var MmbrCntnr=Location.document.createElement("div");
		MmbrCntnr.style.position='absolute';
		MmbrCntnr.style.visibility='hidden';
		Location.document.body.appendChild(MmbrCntnr)}
	else{	if(Nav4) var MmbrCntnr=new Layer(Wdth,Location)
		else{	WMnu+='c';
			Location.document.body.insertAdjacentHTML("AfterBegin","<div id='"+WMnu+"' style='visibility:hidden; position:absolute;'><\/div>");
			var MmbrCntnr=Location.document.all[WMnu]}}
	MmbrCntnr.SetUp=CntnrSetUp;
	MmbrCntnr.SetUp(Wdth,Hght,NumberOf);
	if(Exp4){	MmbrCntnr.InnerString='';
		for(i=1;i<NumberOf+1;i++){
			WMnu=MName+eval(i);
			MmbrCntnr.InnerString+="<div id='"+WMnu+"' style='position:absolute;'><\/div>"}
		MmbrCntnr.innerHTML=MmbrCntnr.InnerString}
	for(i=1;i<NumberOf+1;i++){
		WMnu=MName+eval(i);
		NoOffSubs=eval(WMnu+'[3]');
		Wdth=RcrsLvl==1&&FirstLineHorizontal?eval(WMnu+'[5]')?eval(WMnu+'[5]'):MenuWidth:MenuWidth;
		Hght=RcrsLvl==1&&FirstLineHorizontal?MenuHeight:eval(WMnu+'[4]')?eval(WMnu+'[4]'):MenuHeight;
		if(DomYes){
			Mbr=Location.document.createElement("div");
			Mbr.style.position='absolute';
			Mbr.style.visibility='inherit';
			MmbrCntnr.appendChild(Mbr)}
		else Mbr=Nav4?new Layer(Wdth,MmbrCntnr):Location.document.all[WMnu];
		Mbr.SetUp=Nav4?NavMbrSetUp:MbrSetUp;
		Mbr.SetUp(MmbrCntnr,PrvMmbr,WMnu,Wdth,Hght);
		if(NoOffSubs) Mbr.ChildCntnr=CreateMenuStructure(WMnu+'_',NoOffSubs);
		PrvMmbr=Mbr}
	MmbrCntnr.FrstMbr=Mbr;
	RcrsLvl--;
	return(MmbrCntnr)}

function CreateMenuStructureAgain(MName,NumberOf){
	var i,WMnu,NoOffSubs,PrvMmbr,Mbr=FrstCntnr.FrstMbr;
	RcrsLvl++;
	for(i=NumberOf;i>0;i--){
		WMnu=MName+eval(i);
		NoOffSubs=eval(WMnu+'[3]');
		PrvMmbr=Mbr;
		if(NoOffSubs)Mbr.ChildCntnr=CreateMenuStructure(WMnu+'_',NoOffSubs);
		Mbr=Mbr.PrvMbr}
	RcrsLvl--}

function BeforeStart(){return}
function AfterBuild(){return}
function BeforeFirstOpen(){return}
function AfterCloseAll(){return}

</script>
<noscript>Your browser does not support script</noscript>

<!-- REST OF BODY CONTENT BELOW HERE -->

</tr>
</tbody>
</table>
</div>

