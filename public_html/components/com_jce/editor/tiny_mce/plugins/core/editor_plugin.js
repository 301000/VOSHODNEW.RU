/* jce - 2.9.22 | 2022-03-31 | https://www.joomlacontenteditor.net | Copyright (C) 2006 - 2022 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
!function(){var Entities=tinymce.html.Entities,each=tinymce.each;tinymce.PluginManager.add("core",function(ed,url){function isEmpty(){return"TEXTAREA"===elm.nodeName?""==elm.value:""==elm.innerHTML}function insertContent(value){return value=Entities.decode(value),value&&("TEXTAREA"===elm.nodeName?elm.value=value:elm.innerHTML=value),!0}var store;ed.onUpdateMedia=new tinymce.util.Dispatcher;var contentLoaded=!1,elm=ed.getElement(),startup_content_html=ed.settings.startup_content_html||"";ed.onBeforeRenderUI.add(function(){if(startup_content_html&&elm&&!contentLoaded&&isEmpty())return contentLoaded=!0,insertContent(startup_content_html)}),ed.onKeyUp.add(function(ed,e){var quoted="&ldquo;{$selection}&rdquo;";"de"==ed.settings.language&&(quoted="&bdquo;{$selection}&ldquo;"),("'"===e.key||'"'==e.key)&&e.shiftKey&&e.ctrlKey&&(ed.undoManager.add(),ed.execCommand("mceReplaceContent",!1,quoted))}),ed.onExecCommand.add(function(ed,cmd,ui,val,args){"Undo"!=cmd&&"Redo"!=cmd&&"mceReApply"!=cmd&&"mceRepaint"!=cmd&&(store={cmd:cmd,ui:ui,value:val,args:args})}),ed.addShortcut("ctrl+alt+z","","mceReApply"),ed.addCommand("mceReApply",function(){if(store&&store.cmd)return ed.execCommand(store.cmd,store.ui,store.value,store.args)}),ed.onPreInit.add(function(){ed.onUpdateMedia.add(function(ed,o){function updateSrcSet(elm,o){var srcset=elm.getAttribute("srcset");if(srcset){for(var sets=srcset.split(","),i=0;i<sets.length;i++){var values=sets[i].trim().split(" ");o.before==values[0]&&(values[0]=o.after),sets[i]=values.join(" ")}elm.setAttribute("srcset",sets.join(","))}}each(ed.dom.select("img,poster"),function(elm){var src=elm.getAttribute("src"),val=src.substring(0,src.indexOf("?"));if(val==o.before){var after=o.after,stamp="?"+(new Date).getTime();src.indexOf("?")!==-1&&after.indexOf("?")===-1&&(after+=stamp),ed.dom.setAttribs(elm,{src:after,"data-mce-src":o.after})}elm.getAttribute("srcset")&&updateSrcSet(elm,o)}),each(ed.dom.select("a[href]"),function(elm){var href=ed.dom.getAttrib(elm,"href");href==o.before&&ed.dom.setAttribs(elm,{href:o.after,"data-mce-href":o.after})})})})})}();