(function() {
    tinymce.PluginManager.requireLangPack("word2text");
    
    tinymce.create("tinymce.plugins.Word2text", {
        
        init : function(ed, url) {
            this.editor = ed;
            this.url = url;
            
            // Register the command so that it can be invoked by using 
            // tinyMCE.activeEditor.execCommand('mceWord2text');
            ed.addCommand('mceWord2text', function() {
                ed.windowManager.open({
                    file : url + '/php/index.php',
                    width : 640,
                    height : 480,
                    inline : 1
                }, {
                    plugin_url : url, // Plugin absolute URL
                    some_custom_arg : 'custom arg' // Custom argument
                });
            });

            // Register typograph button
            ed.addButton('word2text', {
                title : 'word2text.desc',
                cmd : 'mceWord2text',
                image : url + '/img/button.gif'
            });
        },
        
        
        createControl : function(n, cm) {
            return null;
        },
        
        
        getInfo : function() {
            return {
                longname : 'Word2text plugin',
                author : 'lifehacker',
                authorurl : 'http://sourceforge.net/projects/ufocms',
                infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/word2text',
                version : "0.1 beta"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('word2text', tinymce.plugins.Word2text);
})();
