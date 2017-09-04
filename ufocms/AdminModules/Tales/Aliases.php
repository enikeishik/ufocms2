<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Tales;

/**
 * Aliases manipulations
 */
trait Aliases
{
    /**
     * @param string $text
     * @return string
     */
    protected static function getAliasFromText($text) {
        $arrFind = array(' ', '-', '_', 'а', 'б', 'в', 'г', 'д', 'е', 'ё',  'ж',  'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц',  'ч',  'ш',  'щ',  'ъ', 'ы', 'ь', 'э', 'ю',  'я');
        $arrRepl = array('-', '-', '-', 'a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i', 'i', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sh', '',  'y', '',  'e', 'yu', 'ya');
        $alias = preg_replace('/[^a-z0-9\-]/i', '', str_replace($arrFind, $arrRepl, mb_strtolower($text)));
        if ('' == $alias) {
            $alias = str_replace(' ', '-', substr(microtime(), 2));
        }
        return $alias;
    }
    
    /**
     * @param string $alias
     * @param array &$aliases
     * @return string
     */
    protected static function getUnicAlias($alias, &$aliases) {
        $n = 1;
		$aliasNew = $alias;
        while (in_array($aliasNew, $aliases)) {
            $aliasNew = $alias . ++$n;
        }
        $aliases[] = $aliasNew;
        return $aliasNew;
    }
}
