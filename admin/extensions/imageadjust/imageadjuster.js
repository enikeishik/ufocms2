function ImageAdjuster(extensionPath, imageFieldId, width, height)
{
    //self link
    this.iaObj = this;
    
    //first time init flag
    this.initFirst = true;
    
    //image area select
    this.iasObj = null;
    this.iasImg = null;
    this.iasSel = null;
    
    //server-side handler
    this.handler = extensionPath + "index.php?key=KEY_VAL&adjust=1";
    
    //adjusting image
    this.imageFieldId = imageFieldId;
    
    //adjust constraints
    this.adjustWidthMax = 2000;
    this.adjustHeightMax = 2000;
    if ("undefined" != typeof(width) && 0 < width && this.adjustWidthMax >= width) {
        this.adjustWidth = width;
    } else {
        this.adjustWidth = 120;
    }
    if ("undefined" != typeof(height) && 0 < height && this.adjustWidthMax >= height) {
        this.adjustHeight = height;
    } else {
        this.adjustHeight = 90;
    }
    
    //src wrap
    this.elmCode = "";
    
    //extract src from value (if value contain more than url, img tag for example)
    this.val2Src = function(val) {
        var pos1 = val.indexOf('src="');
        if (-1 == pos1) {
            return val;
        }
        var src = val.substr(pos1 + 5);
        var pos2 = src.indexOf('"');
        if (-1 == pos2) {
            return val;
        }
        this.elmCode = val.substr(0, pos1) + "SRC_VAL" + src.substr(pos2 + 1);
        return src.substr(0, pos2);
    };
    
    //wrap src to saved in elmCode code
    this.src2Val = function(val) {
        if ("" != this.elmCode) {
            return this.elmCode.replace(/(.*)SRC_VAL(.*)/, '$1src="' + val + '"$2');
        }
        return val;
    };
    
    this.drawUI = function() {
        var html =  '<div id="' + this.imageFieldId + 'adjustwrap" style="display: none;">' + 
                    '   <div style="padding: 5px; margin-bottom: 5px;"><img src="" alt="" id="' + this.imageFieldId + 'adjustimage"></div>' + 
                    '   <div style="border-top: solid #999 1px; padding: 5px;">' + 
                    '       <div style="float: left; margin-right: 10px; width: 40%; min-width: 100px; max-width: 600px; min-height: 100px; max-height: 400px; overflow: auto;">' + 
                    '           <div id="' + this.imageFieldId + 'adjustpreviewdiv"><img src="" alt="" id="' + this.imageFieldId + 'adjustpreviewimg"></div>' + 
                    '       </div>' + 
                    '       <div style="display: inline-block;">' + 
                    '       <div style="margin-bottom: 10px;">' + 
                    '           <span style="display: inline-block; width: 150px;">Выделенная область: </span>' + 
                    '           <span style="display: inline-block; width: 100px;">ширина <span id="' + this.imageFieldId + 'adjustpreviewwidth" style="display: inline-block; width: 40px; text-align: right;">-</span></span>' + 
                    '           <span style="display: inline-block; width: 100px; margin-left: 20px;">высота <span id="' + this.imageFieldId + 'adjustpreviewheight" style="display: inline-block; width: 40px; text-align: right;">-</span></span>' + 
                    '       </div>' + 
                    '       <div style="margin-bottom: 5px;">' + 
                    '           <span style="display: inline-block; width: 150px;">Конечное изображение: </span>' + 
                    '           <span style="display: inline-block; width: 100px;">ширина <span id="' + this.imageFieldId + 'adjustminwidth" style="display: inline-block; width: 40px; text-align: right;">' + this.adjustWidth + '</span></span>' + 
                    '           <span style="display: inline-block; width: 100px; margin-left: 20px;">высота <span id="' + this.imageFieldId + 'adjustminheight" style="display: inline-block; width: 40px; text-align: right;">' + this.adjustHeight + '</span></span>' + 
                    '       </div>' + 
                    '       <div style="margin-bottom: 5px;">' + 
                    '           <span style="display: inline-block; width: 100px; margin-left: 150px;"><input type="range" min="1" max="' + this.adjustWidthMax + '" value="' + this.adjustWidth + '" id="' + this.imageFieldId + 'adjustminwidthrange" style="width: 100px;"></span>' + 
                    '           <span style="display: inline-block; width: 100px; margin-left: 20px;"><input type="range" min="1" max="' + this.adjustHeightMax + '" value="' + this.adjustHeight + '" id="' + this.imageFieldId + 'adjustminheightrange" style="width: 100px;"></span>' + 
                    '       </div>' + 
                    '       <div><input type="button" value="применить" id="' + this.imageFieldId + 'adjustaction"></div>' + 
                    '       </div>' + 
                    '       <div style="clear: both;"></div>' + 
                    '   </div>' + 
                    '</div>';
        $("#" + this.imageFieldId).parent().append(html);
    };
    
    //init adjust preview
    this.resetPreview = function() {
        $("#" + this.imageFieldId + "adjustpreviewimg").css({
            width:      this.adjustWidth,
            height:     this.adjustHeight,
            marginLeft: 0,
            marginTop:  0
        });
        $("#" + this.imageFieldId + "adjustpreviewwidth").html($("#" + this.imageFieldId + "adjustimage")[0].width ? $("#" + this.imageFieldId + "adjustimage")[0].width : "-");
        $("#" + this.imageFieldId + "adjustpreviewheight").html($("#" + this.imageFieldId + "adjustimage")[0].height ? $("#" + this.imageFieldId + "adjustimage")[0].height : "-");
    };
    
    this.changeSize = function(val, width) {
        if (width) {
            this.adjustWidth = val;
            $("#" + this.imageFieldId + "adjustminwidth").html(val);
        } else {
            this.adjustHeight = val;
            $("#" + this.imageFieldId + "adjustminheight").html(val);
        }
        //change preview
        if (null != this.iasObj) {
            this.iasObj.cancelSelection();
        }
        $("#" + this.imageFieldId + "adjustpreviewdiv").attr("style", "width: " + this.adjustWidth + "px; height: " + this.adjustHeight + "px; overflow: hidden;");
        $("#" + this.imageFieldId + "adjustpreviewimg").attr("style", "width: " + this.adjustWidth + "px; height: " + this.adjustHeight + "px;");
    };
    
    //adjuster initialization
    this.init = function() {
        //link to imageAdjuster for nested functions
        var imageAdjuster = this.iaObj;
        
        this.resetPreview();
        
        $("#" + this.imageFieldId + "adjustpreviewdiv").attr("style", "width: " + this.adjustWidth + "px; height: " + this.adjustHeight + "px; overflow: hidden;");
        $("#" + this.imageFieldId + "adjustpreviewimg").attr("style", "width: " + this.adjustWidth + "px; height: " + this.adjustHeight + "px;");
        
        if (!this.initFirst) {
            $("#" + this.imageFieldId).trigger("change");
            return;
        }
        
        //redefine external function
        var AppendIconAttributesOriginal = AppendIconAttributes;
        AppendIconAttributes = function(elementId) {
            AppendIconAttributesOriginal(elementId);
            $("#" + imageAdjuster.imageFieldId).trigger("change");
        };
        
        $.get(extensionPath + "index.php?key=1", function(data) {
            imageAdjuster.handler = imageAdjuster.handler.replace(/(.*)KEY_VAL(.*)/, "$1" + data + "$2");
        });
        
        $("#" + this.imageFieldId).on("change", function() {
            $("#" + imageAdjuster.imageFieldId + "adjustimage").attr("src", imageAdjuster.val2Src($(this).val()));
        });
        
        $("#" + this.imageFieldId + "adjustminwidthrange").on("input change", function() {
            imageAdjuster.changeSize($(this).val(), true);
        });
        $("#" + this.imageFieldId + "adjustminheightrange").on("input change", function() {
            imageAdjuster.changeSize($(this).val(), false);
        });
        
        $("#" + this.imageFieldId + "adjustimage").on("load", function() {
            $("#" + imageAdjuster.imageFieldId + "adjustpreviewimg").attr("src", $(this).attr("src"));
            $("#" + imageAdjuster.imageFieldId + "adjustpreviewwidth").html($("#" + imageAdjuster.imageFieldId + "adjustimage")[0].width);
            $("#" + imageAdjuster.imageFieldId + "adjustpreviewheight").html($("#" + imageAdjuster.imageFieldId + "adjustimage")[0].height);
            
            imageAdjuster.iasObj = $("#" + imageAdjuster.imageFieldId + "adjustimage").imgAreaSelect({
                instance: true,
                movable: true,
                resizable: true, // чтобы задать нужные нам размеры для иконки
                handles: true,    
                show: false,
                onSelectChange: function(i, s) {
                    if (!s.width || !s.height) {
                        return;
                    } else if (1 == s.width && 1 == s.height) {
                        imageAdjuster.resetPreview();
                        return;
                    }
                    
                    var scaleX = imageAdjuster.adjustWidth / s.width;
                    var scaleY = imageAdjuster.adjustHeight / s.height;
                    
                    $("#" + imageAdjuster.imageFieldId + "adjustpreviewimg").css({
                        width:      Math.round(scaleX * i.width),
                        height:     Math.round(scaleY * i.height),
                        marginLeft: -Math.round(scaleX * s.x1),
                        marginTop:  -Math.round(scaleY * s.y1)
                    });
                    
                    $("#" + imageAdjuster.imageFieldId + "adjustpreviewwidth").html(s.width);
                    $("#" + imageAdjuster.imageFieldId + "adjustpreviewheight").html(s.height);
                },
                onSelectEnd: function(i, s) {
                    imageAdjuster.iasImg = i;
                    imageAdjuster.iasSel = s;
                },
            })
            //for Opera using setTimeout instead of directly code
            //setTimeout(function() {
            //}, 500);
        });
        
        $("#" + this.imageFieldId + "adjustaction").click(function() {
            if (null == imageAdjuster.iasObj || null == imageAdjuster.iasImg || null == imageAdjuster.iasSel) {
                alert("Selection not set");
                return;
            }
            imageAdjuster.iasObj.cancelSelection();
            var site = window.location.protocol + "//" + window.location.hostname;
            $.post(
                imageAdjuster.handler,
                {
                    srcx: imageAdjuster.iasSel.x1,
                    srcy: imageAdjuster.iasSel.y1,
                    srcw: imageAdjuster.iasSel.width,
                    srch: imageAdjuster.iasSel.height,
                    dstw: imageAdjuster.adjustWidth,
                    dsth: imageAdjuster.adjustHeight,
                    path: imageAdjuster.iasImg.src.substr(site.length),
                },
                function(responseText) {
                    if (0 == responseText.toLowerCase().indexOf("error")) {
                        alert(responseText);
                        return;
                    }
                    $("#" + imageAdjuster.imageFieldId).val(imageAdjuster.src2Val(responseText));
                    $("#" + imageAdjuster.imageFieldId + "adjustimage").attr("src", responseText);
                    imageAdjuster.resetPreview();
                },
                "text"
            );
        });
        
        $("#" + this.imageFieldId).trigger("change");
        
        this.initFirst = false;
    };
    
    this.clear = function () {
        if (null != this.iasObj) {
            this.iasObj.cancelSelection();
        }
        $("#" + this.imageFieldId + "adjustimage").attr("src", "");
    };
};
