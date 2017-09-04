var blnAliasSet = false;
var blnDescrSet = false;
var blnKeysSet = false;


function convertToLatin(charIn)
{
    switch (charIn) {
        case "a": return "a";case "b": return "b";case "c": return "c";
        case "d": return "d";case "e": return "e";case "f": return "f";
        case "g": return "g";case "h": return "h";case "i": return "i";
        case "j": return "j";case "k": return "k";case "l": return "l";
        case "m": return "m";case "n": return "n";case "o": return "o";
        case "p": return "p";case "q": return "q";case "r": return "r";
        case "s": return "s";case "t": return "t";case "u": return "u";
        case "v": return "v";case "w": return "w";case "x": return "x";
        case "y": return "y";case "z": return "z";case "~": return "~";
        case "а": return "a";case "б": return "b";case "в": return "v";
        case "г": return "g";case "д": return "d";case "е": return "e";
        case "ё": return "yo";case "ж": return "zh";case "з": return "z";
        case "и": return "i";case "й": return "i";case "к": return "k";
        case "л": return "l";case "м": return "m";case "н": return "n";
        case "о": return "o";case "п": return "p";case "р": return "r";
        case "с": return "s";case "т": return "t";case "у": return "u";
        case "ф": return "f";case "х": return "h";case "ц": return "ts";
        case "ч": return "ch";case "ш": return "sh";case "щ": return "tsh";
        case "ъ": return "";case "ы": return "y";case "ь": return "";
        case "э": return "e";case "ю": return "yu";case "я": return "ya";
        case "0": return "0";case "1": return "1";case "2": return "2";
        case "3": return "3";case "4": return "4";case "5": return "5";
        case "6": return "6";case "7": return "7";case "8": return "8";
        case "9": return "9";case "-": return "-";case "—": return "-";
        case "–": return "-";case "«": return "";case "»": return "";
        case "'": return "";case '"': return "";case "`": return "";
        case ".": return "";case ",": return "";case ";": return "";
        case ":": return "";case "!": return "";case "?": return "";
        case "(": return "";case ")": return "";default: return "-";
    }
}

function convertToAlias(strIn)
{
    var strOut = "";
    for (var i=0; i<strIn.length; i++) {
        strOut += convertToLatin(strIn.substr(i, 1).toLowerCase());
    }
    return strOut;
}

function setAlias(elm)
{
    if (!blnAliasSet) {
        elm.form.elements["Url"].value = convertToAlias(elm.value);
    }
    if (!blnDescrSet) {
        elm.form.elements["MetaDesc"].value = elm.value;
    }
    if (!blnKeysSet) {
        elm.form.elements["MetaKeys"].value = clearMetaKeys(elm.value);
    }
}

function checkAlias(elmAlias)
{
    elmAlias.value = elmAlias.value.replace(/[^A-Za-z0-9~_\-]/ig, "");
    blnAliasSet = '' != elmAlias.value;
}

function makeEditable(el)
{
    alert('При изменении URL существующего раздела рекомендуется создать HTTP перенаправление (301 Moved Permanently) со старого адреса на новый.');
    el.readOnly = false;
}

function clearMetaKeys(strIn)
{
    var strOut = strIn.toString();
    strOut = strOut.replace(/[^A-Za-zА-Яа-яЁё0-9]/g, ' ');
    strOut = strOut.replace(/\s{2,}/g, ' ');
    var arrOut = strOut.split(' ');
    var cnt = arrOut.length
    strOut = '';
    for (var i = 0; i < cnt; i++) {
        if (arrOut[i].length > 1) {
            strOut += arrOut[i] + ' ';
        }
    }
    if (strOut.length > 0) {
        strOut = strOut.substr(0, strOut.length - 1);
    }
    return strOut;
}
