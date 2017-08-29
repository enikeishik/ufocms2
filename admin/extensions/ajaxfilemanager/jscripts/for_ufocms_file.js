function selectFile(url)
{
    window.opener.document.getElementById(elementId).value = url.replace(/http:\/\/[^\/]+\//, '/');
    window.close() ;
}

function cancelSelectFile()
{
    window.close() ;
}
