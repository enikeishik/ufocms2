<?php
namespace Ufocms\Backend;

ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);
ini_set('scream.enabled', true);
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU.utf-8', 'rus_RUS.utf-8', 'ru_RU.utf8');
mb_internal_encoding('UTF-8');

require_once '../../../config.php';
require_once '../../autoload.php';

$config = new Config();
if (defined('C_THEME') && '' != C_THEME) {
    $siteCssPath = $config->templatesDir . '/' . C_THEME;
} else {
    $siteCssPath = $config->templatesDir . $config->themeDefault;
}
if (file_exists($config->rootPath . $siteCssPath . '/styles.css')) {
    $siteCssPath .= '/styles.css';
} else if (file_exists($config->rootPath . $siteCssPath . '/css/styles.css')) {
    $siteCssPath .= '/css/styles.css';
} else {
    $siteCssPath = '';
}

@header('Last-Modified: ' . gmdate("D, d M Y H:i:s", time() - 600) . ' GMT');
@header('Cache-Control: max-age=3600');
@header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 3600) . ' GMT');
@header('Content-type: text/javascript');
?>
tinyMCE.init({
    // General options
    mode : "specific_textareas",
    elements : "ajaxfilemanager",
    editor_deselector : "mceNoEditor",
    theme : "advanced",
    plugins : "contextmenu,advhr,advimage,advlink,advlist,autolink,directionality,emotions,fullscreen,iespell,inlinepopups,insertdatetime,layer,legacyoutput,lists,media,nonbreaking,noneditable,pagebreak,paste,preview,safari,searchreplace,style,table,typograph,visualchars,word2text,wordcount,xhtmlxtras",
    pagebreak_separator : "<?php echo C_SITE_PAGEBREAK_SEPERATOR; ?>",
    visual : false,
    language : "ru",
    
    // Theme options
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect,|,forecolor,backcolor",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,|,insertdate,inserttime,preview,|,code,|,typograph",
    theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,fullscreen,|,pagebreak,|,word2text",
    theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
    theme_advanced_blockformats : "p,div,h1,h2,h3,h4,h5,h6,blockquote,dt,dd,code,samp,pre",
    theme_advanced_font_sizes : "1 (8pt)=8pt,2 (10pt)=10pt,3 (12pt)=12pt,4 (14pt)=14pt,5 (18pt)=18pt,6 (24pt)=24pt,7 (36pt)=36pt,6px=6px,7px=7px,8px=8px,9px=9px,10px=10px,11px=11px,12px=12px,13px=13px,14px=14px,15px=15px,16px=16px,17px=17px,18px=18px,19px=19px,20px=20px,22px=22px,24px=24px,26px=26px,28px=28px,30px=30px,32px=32px,34px=34px,36px=36px,38px=38px,40px=40px,xx-small=xx-small,x-small=x-small,small=small,medium=medium,large=large,x-large=x-large,xx-large=xx-large",
    
    //file_browser_callback : "myFileBrowser",
    file_browser_callback : "ajaxfilemanager",
    
    paste_use_dialog : false,
    relative_urls : false,
    element_format : "html",
    
    // Example content CSS (should be your site CSS)
    content_css : "<?php echo $siteCssPath; ?>",
    
    // Drop lists for link/image/media/template dialogs
    //template_external_list_url : "lists/template_list.js",
    external_link_list_url : "extensions/tiny_mce/tiny_mce_links.php",
    //external_image_list_url : "lists/image_list.js",
    //media_external_list_url : "lists/media_list.js",
    
    extended_valid_elements : "iframe[name|src|framespacing|border|frameborder|scrolling|title|height|width|align],object[declare|classid|codebase|data|type|codetype|archive|standby|height|width|usemap|name|tabindex|align|border|hspace|vspace]",
    
    my_dummy : ""
});

window.onbeforeunload = function (evt) {
    for (edId in tinyMCE.editors) {
        if (tinyMCE.editors[edId].isDirty()) {
            var message = "»меютс¤ несохраненные изменени¤. ”йти со страницы без сохранени¤?";
            if (typeof evt == "undefined") {
                evt = window.event;
            }
            if (evt) {
                evt.returnValue = message;
            }
            return message;
        }
    }
}

function ajaxfilemanager(field_name, url, type, win)
{
    var ajaxfilemanagerurl = "extensions/tiny_mce/plugins/ajaxfilemanager.php?editor=tinymce";
    switch (type) {
        case "image":
            break;
        case "media":
            break;
        case "flash":
            break;
        case "file":
            break;
        default:
            return false;
    }
    tinyMCE.activeEditor.windowManager.open({
        url: "extensions/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php?editor=tinymce",
        width: 760,
        height: 470,
        inline : "yes",
        close_previous : "no"
    },{
        window : win,
        input : field_name
    });

/*            return false;
    var fileBrowserWindow = new Array();
    fileBrowserWindow["file"] = ajaxfilemanagerurl;
    fileBrowserWindow["title"] = "Ajax File Manager";
    fileBrowserWindow["width"] = "782";
    fileBrowserWindow["height"] = "440";
    fileBrowserWindow["close_previous"] = "no";
    tinyMCE.openWindow(fileBrowserWindow, {
      window : win,
      input : field_name,
      resizable : "yes",
      inline : "yes",
      editor_id : tinyMCE.getWindowArg("editor_id")
    });

    return false;*/
}

function myFileBrowser (field_name, url, type, win)
{
    alert("Field_Name: " + field_name + "\nURL: " + url + "\nType: " + type + "\nWin: " + win); // debug/testing

    /* If you work with sessions in PHP and your client doesn't accept cookies you might need to carry
    the session name and session ID in the request string (can look like this: "?PHPSESSID=88p0n70s9dsknra96qhuk6etm5").
    These lines of code extract the necessary parameters and add them back to the filebrowser URL again. */

    var cmsURL = window.location.toString();    // script URL - use an absolute path!
    if (cmsURL.indexOf("?") < 0) {
        //add the type as the only query parameter
        cmsURL = cmsURL + "?type=" + type;
    }
    else {
        //add the type as an additional query parameter
        // (PHP session ID is now included if there is one at all)
        cmsURL = cmsURL + "&type=" + type;
    }

    tinyMCE.activeEditor.windowManager.open({
        file : cmsURL,
        title : 'My File Browser',
        width : 400,  // Your dimensions may differ - toy around with them!
        height : 400,
        resizable : "yes",
        inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
        close_previous : "no"
    }, {
        window : win,
        input : field_name
    });
    return false;
}
