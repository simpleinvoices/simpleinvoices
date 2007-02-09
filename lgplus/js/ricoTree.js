//  Rico Tree Control
//  by Matt Brown
//  Oct 2006
//  email: dowdybrown@yahoo.com

//  Requires prototype.js and ricoCommon.js

// each node in nodeIndex is an Array with 6+n positions
//  node[0] is 0/1 when the node is closed/open
//  node[1] is 0/1 when the folder is closed/open
//  node[2] is 1 if the node is a leaf node
//  node[3] is the node id
//  node[4] is the node description
//  node[5] is 1 when the node is selectable, 0 otherwise
//  node[6]...node[6+n] are the child nodes

Rico.TreeControl = Class.create();

Rico.TreeControl.prototype = {

  initialize: function(id,url,options) {
    this.img=[];
    this.FirstChildNode=6;
    this.nodeIndex={};
    this.nodeCount=0;
    this.foldersTree=0;
    this.timeOutId=0;
    this.id=id;
    this.options = {
      nodeIdDisplay:'none',   // first, last, tooltip, or none
      showCheckBox: false,
      showFolders: false,
      showPlusMinus: true,
      defaultAction: this.nodeClick.bindAsEventListener(this),
      height: '300px',
      width: '300px',
      leafIcon: Rico.imgDir+'doc.dif'
    }
    Object.extend(this.options, options || {});
    this.dataSource=url;
  },

  // stop event bubbling from the container so that it doesn't turn off the row highlight
  containerClick: function(e) {
    if (e.stopPropagation) {
      e.stopPropagation();
    } else {
      e.cancelBubble = true;
    }
    return true;
  },
  
  atLoad : function() {
    var imgsrc = new Array("node.gif","nodelast.gif","folderopen.gif","folderclosed.gif");
    for (i=0;i<imgsrc.length;i++) {
      this.img[i] = new Image
      this.img[i].src = Rico.imgDir+imgsrc[i]
      //this.img[i].src = Rico.imgDir + imgsrc[i]
    }
    this.treeDiv=document.createElement("div");
    this.treeDiv.id=this.id;
    this.treeDiv.className='ricoTree';
    this.treeDiv.style.height=this.options.height;
    this.treeDiv.style.width=this.options.width;
    this.container=document.createElement("div");
    this.container.style.display="none"
    this.container.className='ricoTreeContainer';
    this.container.appendChild(this.treeDiv);
    document.body.appendChild(this.container);
    this.shim=new Rico.Shim();
    Event.observe(this.container,"click", this.containerClick.bindAsEventListener(this), false);
    this.close();
  },

  // Building the data in the tree
  open: function() {
    this.container.style.display="block";
    if (this.nodeCount==0) this.loadXMLDoc();
  },

  close : function() {
    this.shim.hide();
    this.container.style.display="none"
  },

  loadXMLDoc: function(branchPin) {
    var parms="id="+this.id;
    if (branchPin) parms+="&Parent="+branchPin;
    //alert('loadXMLDoc:\n'+parms+'\n'+this.dataSource);
    new Ajax.Request(this.dataSource, {parameters:parms,method:'get',onComplete:this.processResponse.bind(this)});
  },

  processResponse: function(request) {
    var response = request.responseXML.getElementsByTagName("ajax-response");
    if (response == null || response.length != 1) return;
    var rowsElement = response[0].getElementsByTagName('rows')[0];
    var trs = rowsElement.getElementsByTagName("tr");
    //alert('processResponse: '+trs.length);
    for ( var i=0 ; i < trs.length; i++ ) {
      var cells = trs[i].getElementsByTagName("td");
      if (cells.length != 5) continue;
      // cells[0]=parent node id
      // cells[1]=node id
      // cells[2]=description
      // cells[3]=L/zero (leaf), C/non-zero (container)
      // cells[4]= 0->not selectable, 1->selectable (use default action), otherwise the node is selectable and cells[4] contains the action
      var content=[];
      for (var j=0; j<cells.length; j++)
        content[j]=this.getContent(cells[j]);
        //content[j]=RicoUtil.getContentAsString(cells[j]);
      var node=this.addNode(content[3],content[1],content[2],content[4]);
      if (this.foldersTree==0) {
        this.foldersTree = node;
        node[0]=1;
        node[1]=1;
      } else {
        var parentNode=this.nodeIndex[content[0]]
        if (typeof parentNode=='undefined')
          alert('ERROR!\nReceived invalid response from server - could not find parent in existing tree:\n'+content[0]);
        else
          parentNode.push(node);
      }
    }
    if (this.nodeCount==1 && node[2])
      this.loadXMLDoc(node[3]);
    else
      this.redrawTree();
  },

  getContent: function(cell) {
    if (cell.innerHTML) return cell.innerHTML;
    switch (cell.childNodes.length) {
      case 0:  return "";
      case 1:  return cell.firstChild.nodeValue;
      default: return cell.childNodes[1].nodeValue;
    }
  },

  // create new node
  // NodeType is "C" or non-zero (container), or "L" or zero (leaf)
  // id is the unique identifier for the node
  // desc is the text displayed to the user
  addNode: function(NodeType,id,desc,selectable) {
    var arrayAux
    //alert("addNode: " + desc + " (" + selectable + ")")
    arrayAux = new Array
    arrayAux[0] = 0
    arrayAux[1] = 0
    arrayAux[2] = (NodeType=='0' || NodeType.toUpperCase()=='L' ? 0 : 1)
    arrayAux[3] = id
    arrayAux[4] = desc
    arrayAux[5] = parseInt(selectable);
    this.nodeIndex[id]=arrayAux
    this.nodeCount++;
  
    return arrayAux
  },

  RemoveAllChildren: function(obj) {
  	while (obj.hasChildNodes()) {
  		this.RemoveAllChildren(obj.childNodes[0])
  		obj.removeChild(obj.childNodes[0])
  	}
  },

  redrawTree: function() {
    //alert('redrawTree');
    this.RemoveAllChildren(this.treeDiv)
    this.redrawNode(this.foldersTree, 0, 1, [])
  },

  DisplayImages: function(row,arNames) {
    var i,img,td
    for(i=0;i<arNames.length;i++) {
      img = document.createElement("img")
      img.src=Rico.imgDir+arNames[i] + ".gif"
      td=row.insertCell(-1)
      td.appendChild(img)
    }
  },

  redrawNode: function(foldersNode, level, lastNode, leftSide) {
    var tab,row
    //alert("redrawNode at level " + level + " (" + foldersNode[3] + ")")
    
    tab = document.createElement("table")
    tab.border=0
    tab.cellSpacing=0
    tab.cellPadding=0
    row=tab.insertRow(0)
    this.DisplayImages(row,leftSide)
    var newLeft=leftSide.slice(0);
    if (level>0) {
      var suffix=lastNode ? 'last' : '';
      if (this.options.showPlusMinus && foldersNode[2])
        this.showPlusMinus(row.insertCell(-1),foldersNode,suffix);
      else
        this.NodeImage(row.insertCell(-1),suffix)
      newLeft.push(lastNode ? "nodeblank" : "nodeline")
    }
    if (this.options.showFolders)
      this.showFolders(row.insertCell(-1),foldersNode);
    if (this.options.showCheckBox && foldersNode[5])
      this.showCheckBox(row.insertCell(-1),foldersNode);
    this.displayLabel(row,foldersNode)
    this.treeDiv.appendChild(tab)
  
    if (foldersNode.length > this.FirstChildNode && foldersNode[0]) {
      //there are sub-nodes and the folder is open
      for (var i=this.FirstChildNode; i<foldersNode.length;i++)
        this.redrawNode(foldersNode[i], level+1, (i==foldersNode.length-1 ? 1 : 0), newLeft)
    }
  },

  NodeImage: function(td, suffix) {
    var img
    img = document.createElement("img")
    img.src=Rico.imgDir+"node"+suffix+".gif"
    td.appendChild(img)
  },


  showPlusMinus: function(td,foldersNode,suffix) {
    var img = document.createElement("img")
    img.name=foldersNode[3];
    img.style.cursor='pointer';
    if (foldersNode.length > this.FirstChildNode)
      img.onclick=this.openBranch.bindAsEventListener(this);
    else
      img.onclick=this.getChildren.bindAsEventListener(this);
    var prefix=foldersNode[1] ? "nodem" : "nodep"
    img.src=Rico.imgDir+prefix+suffix+".gif";
    td.appendChild(img)
  },

  showFolders: function(td,foldersNode) {
    var img = document.createElement("img")
    if (!foldersNode[2]) {
      img.src=this.options.leafIcon;
    } else {
      img.name=foldersNode[3];
      img.style.cursor='pointer';
      if (foldersNode.length > this.FirstChildNode)
        img.onclick=this.openBranch.bindAsEventListener(this);
      else
        img.onclick=this.getChildren.bindAsEventListener(this);
      img.src=Rico.imgDir+(foldersNode[1] ? "folderopen.gif" : "folderclosed.gif");
    }
    td.appendChild(img)
  },

  showCheckBox: function(td,foldersNode) {
    var inp=document.createElement("input")
    inp.type="checkbox"
    inp.name=foldersNode[3]
    td.appendChild(inp)
  },

  displayLabel: function(row,foldersNode) {
    if (foldersNode[5]) {
      var span=document.createElement('a');
      span.href='#';
      span.onclick=this.options.defaultAction;
    } else {
      var span=document.createElement('p');
    }
    span.id=this.id+"__"+foldersNode[3];
    var desc=foldersNode[4];
    switch (this.options.nodeIdDisplay) {
      case 'last': desc+=' ('+foldersNode[3]+')'; break;
      case 'first': desc=foldersNode[3]+' - '+desc; break;
      case 'tooltip': span.title=foldersNode[3]; break;
    }
  	span.appendChild(document.createTextNode(desc))
    var td=row.insertCell(-1)
    td.appendChild(span)
  },

  //when a parent is closed all children also are
  closeFolders: function(foldersNode) {
    var i=0
    if (foldersNode[2]) {
      for (i=this.FirstChildNode; i< foldersNode.length; i++)
        this.closeFolders(foldersNode[i])
    }
    foldersNode[0] = 0
    foldersNode[1] = 0
  },
  
  nodeClick: function(e) {
    var node=Event.element(e);
    if (this.returnValue) {
      var v=node.id;
      var i=v.indexOf('__');
      if (i>=0) v=v.substr(i+2);
      this.returnValue(v,node.innerHTML);
    }
    this.close();
  },

  //recurse over the tree structure
  //called by openbranch
  clickOnFolderRec: function(foldersNode, folderName) {
    var i=0
    if (foldersNode[3] == folderName) {
      if (foldersNode[0]) {
        this.closeFolders(foldersNode)
      } else {
        foldersNode[0] = 1
        foldersNode[1] = 1
      }
    } else if (foldersNode[2]) {
      for (i=this.FirstChildNode; i< foldersNode.length; i++)
        this.clickOnFolderRec(foldersNode[i], folderName)
    }
  },

  openBranch: function(e) {
    var node=Event.element(e);
    this.clickOnFolderRec(this.foldersTree, node.name)
    this.timeOutId = setTimeout(this.redrawTree.bind(this),100)
  },

  getChildren: function(e) {
    var node=Event.element(e);
    this.loadXMLDoc(node.name)
    this.openBranch(e)
  }

}

Rico.addPreloadMsg('exec: ricoTree.js');
