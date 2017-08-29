function selectFolder(url)
{
    window.opener.document.getElementById(elementId).value = url.replace(/http:\/\/[^\/]+\//, '/');
    window.close() ;
}
