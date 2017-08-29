var blnUrlSet = false;
var blnTitleSet = false;
var blnDescrSet = false;
var blnKeysSet = false;

function checkForm(f)
{
    if (0 == f.elements["indic"].value.length) {
        alert("Укажите название");
        f.elements["indic"].focus();
        return false;
    /*
    } else if (0 == f.elements["title"].value.length) {
        alert("Укажите заголовок");
        f.elements["title"].focus();
        return false;
    */
    } else if (0 == f.elements["path"].value.length) {
        alert("Укажите адрес");
        f.elements["path"].focus();
        return false;
    }
    return true;
}

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

function convertToUrl(strIn)
{
    var strOut = "";
    for (var i=0; i<strIn.length; i++) {
        strOut += convertToLatin(strIn.substr(i, 1).toLowerCase());
    }
    return strOut;
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

function setUrl(f)
{
    if (!blnUrlSet) {
        f.elements["path"].value = convertToUrl(f.elements["indic"].value);
    }
    if (!blnTitleSet) {
        f.elements["title"].value = f.elements["indic"].value;
    }
    if (!blnDescrSet) {
        f.elements["metadesc"].value = f.elements["indic"].value;
    }
    if (!blnKeysSet) {
        f.elements["metakeys"].value = clearMetaKeys(f.elements["indic"].value);
    }
}

function checkUrl(f)
{
    f.elements["path"].value = f.elements["path"].value.replace(/[^A-Za-z0-9~_\-]/ig, "");
}

function checkInt(e)
{
    var i = parseInt(e.value);
    if (isNaN(i))
        i = 0;
    e.value = i;
}

function makeEditable(el)
{
    alert('При изменении URL существующего раздела рекомендуется создать HTTP перенаправление (301 Moved Permanently) со старого адреса на новый.');
    el.readOnly = false;
}

function displayPathChangeWarning(el)
{
    var p = el.value;
    if ('/' != p.charAt(0) || '/' != p.charAt(p.length - 1)) {
        if (!confirm('URL должен начинаться и заканчиваться символом "/", оставить текущий некорректное значение?')) {
            el.value = el.defaultValue;
        }
    }
}
