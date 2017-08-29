function getHtml(groupId)
{
	prompt('Скопируйте код и вставьте в шаблон/код страницы, в месте, где необходимо выводить цитаты группы', 
			"<script type='text/javascript'>var quote" + groupId + "='';</script>" + 
			"<script type='text/javascript' src='/quotes.php?groupid=" + groupId + "'></script>" + 
			"<script type='text/javascript'>document.write(quote" + groupId + ");</script>");
}
function getPhp(groupId)
{
	prompt('Скопируйте код и вставьте в шаблон, в месте, где необходимо выводить цитаты группы', 
			"<?php $this->renderQuotes(['GroupId' => " + groupId + "]); ?>");
}
