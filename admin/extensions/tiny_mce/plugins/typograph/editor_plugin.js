(function() {
	tinymce.PluginManager.requireLangPack("typograph");
	
	tinymce.create("tinymce.plugins.Typograph", {
		
		init : function(ed, url) {
			this.editor = ed;
			this.url = url;
			
			// Register the command so that it can be invoked by using 
			// tinyMCE.activeEditor.execCommand('mceTypograph');
			ed.addCommand('mceTypograph', function() {
				var txt = tinyMCE.activeEditor.getContent({format : 'html'});
				var txt_ = '';
				while (txt_ != txt && 
						(  -1 != txt.indexOf('"') || -1 != txt.indexOf("'") 
						|| -1 != txt.indexOf('-') || -1 != txt.indexOf('–')  )) {
					txt_ = txt;
					txt = txt.replace(/(>[^'<]*)'([^'<]+)'{1,2}/g,'$1«$2»')
                             .replace(/(>[^"<]*)"([^"<]+)"{1,2}/g,'$1«$2»')
                             .replace(/(>[^<]*)-(\s+)/g,'$1—$2')
                             .replace(/(>[^<]*)–(\s+)/g,'$1—$2');
				}
				txt = txt.replace(/(>[^<]*)'([A-Za-z0-9А-Яа-я]+)/g,'$1«$2')
                         .replace(/(>[^<]*)([A-Za-z0-9А-Яа-я]+)'/g,'$1$2»')
                         .replace(/(>[^<]*)"([A-Za-z0-9А-Яа-я]+)/g,'$1«$2')
                         .replace(/(>[^<]*)([A-Za-z0-9А-Яа-я]+)"/g,'$1$2»')
                         .replace(/(>[^<]*)»([A-Za-z0-9А-Яа-я]+)/g,'$1«$2')
                         .replace(/(>[^<]*)([A-Za-z0-9А-Яа-я]+)«/g,'$1$2»')
                         .replace(/“/g,'«').replace(/”/g,'»')
                         .replace(/&ldquo;/g,'«').replace(/&rdquo;/g,'»')
                         .replace(/&ndash;/g,'—')
                         .replace(/‘/g,'«').replace(/&lsquo;/g,'«').replace(/&bdquo;/g,'«');
				tinyMCE.activeEditor.setContent(txt, {format : 'html'});
			});

			// Register typograph button
			ed.addButton('typograph', {
				title : 'typograph.desc',
				cmd : 'mceTypograph',
				image : url + '/img/button.gif'
			});
		},
		
		
		createControl : function(n, cm) {
			return null;
		},
		
		
		getInfo : function() {
			return {
				longname : 'Typograph plugin',
				author : 'lifehacker',
				authorurl : 'http://sourceforge.net/projects/ufocms',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/typograph',
				version : "2.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('typograph', tinymce.plugins.Typograph);
})();
