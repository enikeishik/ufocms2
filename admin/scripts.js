function showHide(id)
{
    if (document.getElementById(id)) {
        document.getElementById(id).style.display = 'none' == document.getElementById(id).style.display ? '' : 'none';
    }
}

window.onload = function() {
    document.getElementById('clop').onclick = function(e) {
        if ('<a>&lt;</a>' == this.innerHTML) {
            this.innerHTML = '<a>&gt;</a>';
            document.getElementById('frameleft').style.width = '0px';
            this.style.left = '1px';
            document.getElementById('framemain').style.left = '15px';
            document.cookie = 'ufo_clop_state=1';
        } else {
            this.innerHTML = '<a>&lt;</a>';
            document.getElementById('framemain').style.left = '230px';
            this.style.left = '215px';
            document.getElementById('frameleft').style.width = '215px';
            document.cookie = 'ufo_clop_state=0';
        }
    };
    if (-1 != document.cookie.indexOf('ufo_clop_state=1')) {
        document.getElementById('clop').click();
    }
};

/**
 *  Open/close sections tree nodes
 */
function UnHide(eThis, last)
{
    if (9658 == eThis.innerHTML.charCodeAt(0)) {
        eThis.innerHTML = '&#9679;'
        var li = eThis.parentNode.parentNode.parentNode;
        li.className = '';
        if (1 == li.childElementCount) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4) {
                    if (this.status == 200) {
                        li.innerHTML += xhttp.responseText;
                    } else {
                        alert('Error: ' + xhttp.statusText);
                    }
                }
            };
            xhttp.open('GET', 'tree.php?id=' + eThis.id.substr(7) + '&last=' + last + 'r=' + Math.random(), true);
            xhttp.send();
        }
        eThis.innerHTML = '&#9660;'
    } else {
        eThis.innerHTML = '&#9658;'
        eThis.parentNode.parentNode.parentNode.className = 'cl';
    }
    return false;
}

/**
 *  Use sections settings for image type fields in form
 */
function AppendIconAttributes(elementId)
{
    var c = document.getElementById(elementId);
    if (!c) {
        alert('Не найдено поле картинки');
        return;
    }
    c.value = '<img src="' + c.value + '" alt="">';
    return;
    /*
    var s = document.getElementById('sectionid');
    if (!s || !s.options) {
        alert('Не найден список разделов');
        return;
    }
    
    var t = s.options[s.selectedIndex].text;
    if (-1 == t.lastIndexOf(' ')) {
        alert('У выбранного раздела не указан URL');
        return;
    }
    
    var p = t.substr(t.lastIndexOf(' ') + 1);
    for (var i = 1; i < arrIconsAttributes.length; i++) {
        if (arrIconsAttributes[i].P == p) {
            var a = arrIconsAttributes[i].A;
            if ('' == a) {
                c.value = '<img src="' + c.value + '" alt="" />';
                return;
            }
            if (' ' != a.substr(0, 1)) {
                a = ' ' + a;
            }
            c.value = '<img src="' + c.value + '"' + a + ' alt="" />';
            return;
        }
    }
    */
}
