<?php
/**
 * @copyright
 */

namespace Ufocms\Backend;

/**
 * Sections tree generation
 */
class Tree
{
    /**
     * @var \Ufocms\Frontend\Debug
     */
    protected $debug = null;
    
    /**
     * Ссылка на объект конфигурации.
     * @var Config
     */
    protected $config = null;
    
    /**
     * @var Params
     */
    protected $params = null;
    
    /**
     * @var Core
     */
    protected $core = null;
    
    /**
     * @param Config &$config
     * @param Params &$params
     * @param Core &$core
     * @param \Ufocms\Frontend\Debug &$debug = null
     */
    public function __construct(&$config, &$params, &$core, &$debug = null)
    {
        $this->config =& $config;
        $this->params =& $params;
        $this->core   =& $core;
        $this->debug  =& $debug;
    }
    
    /**
     * @param array $sections
     * @param bool $last = false
     * @todo: move $s to template
     */
    public function drawSections(array $sections, $last = false)
    {
        $s = '<ul' . ($last ? ' class="l"' : '') . '>' . "\r\n";
        $last = end($sections);
        reset($sections);
        foreach ($sections as $sid => $section) {
            $selected = array_key_exists('selected', $section) && $section['selected'] ? ' style="color: #cc0000;"' : '';
            if ($section['isparent']) {
                $next = ($sid == $last['id'] ? 1 : 0);
                if (array_key_exists('children', $section)) {
                    $s .=   '<li><div><p><a href="#" class="oc" onclick="return UnHide(this, ' . $next . ')">&#9660;</a>' . 
                            '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $section['id'] . '"' . $selected . ' title="' . $section['mname'] . ' | ' . $section['path'] . '">' . $section['indic'] . '</a></p></div>' . 
                            $this->drawSections($section['children'], $next);
                } else {
                    $s .=   '<li class="cl"><div><p><a href="#" id="section' . $section['id'] . '" class="oc" onclick="return UnHide(this, ' . $next . ')">&#9658;</a>' . 
                            '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $section['id'] . '"' . $selected . ' title="' . $section['mname'] . ' | ' . $section['path'] . '">' . $section['indic'] . '</a></p></div>';
                }
                $s .=   '</li>' . "\r\n";
            } else if (0 == $section['levelid']) {
                $s .=   '<li class="cl"><div><p><a class="oc">&#9632;</a>' . 
                        '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $section['id'] . '"' . $selected . ' title="' . $section['mname'] . ' | ' . $section['path'] . '">' . $section['indic'] . '</a></p></div></li>' . "\r\n";
            } else {
                $s .=   '<li class="cl"><div><p>' . 
                        '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $section['id'] . '"' . $selected . ' title="' . $section['mname'] . ' | ' . $section['path'] . '">' . $section['indic'] . '</a></p></div></li>' . "\r\n";
            }
        }
        return $s . '</ul>' . "\r\n";
    }
    
    /**
     * @param array &$sections
     * @param string $selected
     */
    protected function appendChildren(array &$sections, $selected)
    {
        if (array_key_exists($selected, $sections)) {
            $sections[$selected]['selected'] = true;
            $children = $this->core->getSectionChildren($sections[$selected]['id']);
            if (is_array($children) && 0 < count($children)) {
                $sections[$selected]['children'] = $children;
            }
        } else {
            foreach ($sections as $sid => &$section) {
                if (array_key_exists('children', $section)) {
                    $this->appendChildren($section['children'], $selected);
                }
            }
        }
    }
    
    /**
     * @param array|null $sections = null
     * @param bool $last = false
     */
    public function render($sections = null, $last = false)
    {
        if (null === $sections) {
            //top sections
            $sections = $this->core->getSectionChildren(0);
            $sectionId = 0;
            if (0 != $this->params->sectionId) {
                $sectionId = $this->params->sectionId;
            } else if ('sections' == $this->params->coreModule && 0 != $this->params->itemId) {
                $sectionId = $this->params->itemId;
            }
            if (0 != $sectionId) {
                //parents chain for current section
                $arr = $this->core->getSectionParentsRecursive($sectionId);
                //get children for parents chain
                for ($i = 0, $cnt = count($arr); $i < $cnt; $i++) {
                    $parents =& $sections;
                    for ($j = 0; $j < $i; $j++) {
                        //goto deepest children
                        $parents =& $parents['id' . $arr[$j]]['children'];
                    }
                    //create container and fill it with children sections
                    $parents['id' . $arr[$i]]['children'] = $this->core->getSectionChildren($arr[$i]);
                }
                unset($parents);
                
                //append children for current section (if exists)
                $selected = 'id' . $sectionId;
                if (null !== $sections) {
                    $this->appendChildren($sections, $selected);
                }
            }
        }
        if (null !== $sections) {
            echo $this->drawSections($sections, $last);
        }
    }
}
