/* jce - 2.9.23 | 2022-05-24 | https://www.joomlacontenteditor.net | Copyright (C) 2006 - 2022 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
!function(){var each=tinymce.each,PreviewCss=tinymce.util.PreviewCss,NodeType=tinymce.dom.NodeType,DOM=tinymce.DOM,Event=tinymce.dom.Event;tinymce.create("tinymce.plugins.StyleSelectPlugin",{init:function(ed,url){this.editor=ed},createControl:function(n,cf){var ed=this.editor;switch(n){case"styleselect":if(ed.getParam("styleselect_stylesheets")!==!1||ed.getParam("style_formats")||ed.getParam("styleselect_custom_classes"))return this._createStyleSelect()}},convertSelectorToFormat:function(selectorText){var format,ed=this.editor;if(selectorText){var selector=/^(?:([a-z0-9\-_]+))?(\.[a-z0-9_\-\.]+)$/i.exec(selectorText);if(selector){var elementName=selector[1];if("body"!==elementName){var classes=selector[2].substr(1).split(".").join(" "),inlineSelectorElements=tinymce.makeMap("a,img");return elementName?(format={title:selectorText},ed.schema.getTextBlockElements()[elementName]?format.block=elementName:ed.schema.getBlockElements()[elementName]||inlineSelectorElements[elementName.toLowerCase()]?format.selector=elementName:format.inline=elementName):selector[2]&&(format={inline:"span",selector:"*",title:selectorText.substr(1),split:!1,expand:!1,deep:!0}),ed.settings.importcss_merge_classes!==!1?format.classes=classes:format.attributes={class:classes},format.ceFalseOverride=!0,format}}}},_createStyleSelect:function(n){function removeFilterTags(){var filter=DOM.get("menu_"+ctrl.id+"_menu_filter");filter&&DOM.remove(DOM.select("button.mceButton",filter))}function removeFilterTag(tag,item){DOM.remove(tag),item||each(ctrl.items,function(n){if(n.value==tag.value)return item=n,!1}),item&&item.onAction&&item.onAction()}function addFilterTag(item){if(ctrl.menu){var filter=DOM.get("menu_"+ctrl.id+"_menu_filter"),btn=DOM.create("button",{class:"mceButton",value:item.value},"<label>"+item.title+"</label>");filter&&(DOM.insertBefore(btn,filter.lastChild),Event.add(btn,"click",function(evt){evt.preventDefault(),"LABEL"!==evt.target.nodeName&&removeFilterTag(btn,item)}))}}function loadClasses(){ed.settings.importcss_classes||ed.onImportCSS.dispatch(),Array.isArray(ed.settings.importcss_classes)&&(ctrl.hasClasses||(each(ed.settings.importcss_classes,function(item,idx){var name="style_"+(counter+idx);"string"==typeof item&&(item={selector:item,class:"",style:""});var fmt=self.convertSelectorToFormat(item.selector);fmt&&(ed.formatter.register(name,fmt),ctrl.add(fmt.title,name,{style:function(){return item.style||""}}))}),Array.isArray(ed.settings.importcss_classes)&&(ctrl.hasClasses=!0)))}var ctrl,self=this,ed=this.editor;ctrl=ed.controlManager.createListBox("styleselect",{title:"advanced.style_select",max_height:384,filter:!0,keepopen:!0,onselect:function(name){function isFakeRoot(node){return NodeType.isElement(node)&&node.hasAttribute("data-mce-root")}var fmt,removedFormat,matches=[],selection=ed.selection,node=selection.getNode(),collectNodesInRange=function(rng,predicate){if(rng.collapsed)return[];var contents=rng.cloneContents(),walker=new tinymce.dom.TreeWalker(contents.firstChild,contents),elements=[],current=contents.firstChild;do predicate(current)&&elements.push(current);while(current=walker.next());return elements},inlineTextElements=ed.schema.getTextInlineElements(),isElement=function(elm){return NodeType.isElement(elm)&&!NodeType.isInternal(elm)&&!inlineTextElements[elm.nodeName.toLowerCase()]},isOnlyTextSelected=function(){var elements=collectNodesInRange(ed.selection.getRng(),isElement);return 0===elements.length},nodes=tinymce.grep(selection.getSelectedBlocks(),function(n){return NodeType.isElement(n)&&!NodeType.isInternal(n)});return nodes.length||(nodes=[node]),ed.focus(),ed.undoManager.add(),each(nodes,function(node){var bookmark=selection.getBookmark();if(node==ed.getBody()&&!isOnlyTextSelected())return!1;if(isFakeRoot(node)&&selection.isCollapsed())return!1;if(each(ctrl.items,function(item){(fmt=ed.formatter.matchNode(node,item.value))&&matches.push(fmt)}),node=nodes.length>1||selection.isCollapsed()?node:null,!selection.isCollapsed()&&isOnlyTextSelected()&&(node=null),selection.moveToBookmark(bookmark),each(matches,function(match){name&&match.name!=name||(ed.execCommand("RemoveFormat",!1,{name:match.name,node:match.block?null:node}),removedFormat=!0)}),removedFormat||(ed.formatter.get(name)?ed.execCommand("ToggleFormat",!1,{name:name,node:node}):(node=selection.getNode(),ed.execCommand("ToggleFormat",!1,{name:"classname",node:node}),ctrl.add(name,name))),selection.moveToBookmark(bookmark),selection.isCollapsed()&&node&&node.parentNode){var rng=ed.dom.createRng();rng.setStart(node,0),rng.setEnd(node,0),rng.collapse(),ed.selection.setRng(rng),ed.nodeChanged()}}),!1}}),ctrl.onBeforeRenderMenu.add(function(ctrl,menu){loadClasses(),menu.onShowMenu.add(function(){removeFilterTags(),each(ctrl.items,function(item){item.selected&&addFilterTag(item)})})}),ctrl.onRenderMenu.add(function(ctrl,menu){menu.onFilterInput.add(function(menu,evt){if(8==evt.keyCode){var elm=evt.target,value=elm.value;if(value)return;var tags=DOM.select("button",elm.parentNode.parentNode);if(tags.length){var tag=tags.pop(),val=tag.textContent;removeFilterTag(tag),evt.preventDefault(),elm.value=val,elm.focus()}}})}),ed.settings.styleselect_stylesheets===!1&&(ctrl.hasClasses=!0);var counter=0;return ed.onNodeChange.add(function(ed,cm,node){var ctrl=cm.get("styleselect");if(ctrl){loadClasses(ed,ctrl);var matches=[];removeFilterTags(),each(ctrl.items,function(item){ed.formatter.matchNode(node,item.value)&&(matches.push(item.value),addFilterTag(item))}),ctrl.select(matches)}}),ed.onPreInit.add(function(){function isValidAttribute(name){var isvalid=!0,invalid=ed.settings.invalid_attributes;return!invalid||(each(invalid.split(","),function(val){name===val&&(isvalid=!1)}),isvalid)}var formats=ed.getParam("style_formats"),styles=ed.getParam("styleselect_custom_classes","","hash");if(ed.formatter.register("classname",{attributes:{class:"%value"},selector:"*",ceFalseOverride:!0}),formats){if("string"==typeof formats)try{formats=JSON.parse(formats)}catch(e){formats=[]}each(formats,function(fmt){var name,keys=0;if(each(fmt,function(){keys++}),keys>1){if(name=fmt.name=fmt.name||"style_"+counter++,tinymce.is(fmt.attributes,"string")){fmt.attributes=ed.dom.decode(fmt.attributes);var frag=ed.dom.createFragment("<div "+tinymce.trim(fmt.attributes)+"></div>"),attribs=ed.dom.getAttribs(frag.firstChild);fmt.attributes={},each(attribs,function(node){var key=node.name,value=""+node.value;return!isValidAttribute(key)||("onclick"!==key&&"ondblclick"!==key||(fmt.attributes[key]="return false;",key="data-mce-"+key),void(fmt.attributes[key]=ed.dom.decode(value)))})}tinymce.is(fmt.styles,"string")&&(fmt.styles=ed.dom.parseStyle(fmt.styles),each(fmt.styles,function(value,key){value=""+value,fmt.styles[key]=ed.dom.decode(value)})),tinymce.is(fmt.ceFalseOverride)||(fmt.ceFalseOverride=!0),ed.formatter.register(name,fmt),ctrl.add(fmt.title,name,{style:function(){return PreviewCss(ed,fmt)}})}else ctrl.add(fmt.title)})}styles&&each(styles,function(val,key){var name,fmt;val&&(val=val.replace(/^\./,""),name="style_"+counter++,fmt={inline:"span",classes:val,selector:"*",ceFalseOverride:!0},ed.formatter.register(name,fmt),key&&(key=key.replace(/^\./,"")),ctrl.add(ed.translate(key),name,{style:function(){return PreviewCss(ed,fmt)}}))})}),ctrl}}),tinymce.PluginManager.add("styleselect",tinymce.plugins.StyleSelectPlugin)}();