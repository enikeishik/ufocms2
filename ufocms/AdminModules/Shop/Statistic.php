<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * Statistic manipulations
 */
trait Statistic
{
    public function statisticUpdate()
    {
        $tp = C_DB_TABLE_PREFIX;
        $mspl = ModelCategories::MASK_SPL;
        
        $sql = 'SELECT COUNT(*) AS Cnt FROM `' . $tp . 'shop_items` WHERE SectionId=' . $this->params->sectionId;
        if (!$this->db->getValue($sql, 'Cnt')) {
            return false;
        }
        
$sql = <<<EOD
UPDATE `{$tp}shop_categories` AS c
SET
	c.TopId = (SELECT Id FROM (SELECT Id, Mask FROM `{$tp}shop_categories` WHERE SectionId={$this->params->sectionId}) AS tmp WHERE Mask=SUBSTR(c.Mask, 1, {$mspl})), 
    c.OrderId = CAST(SUBSTR(c.Mask, LENGTH(c.Mask) - {$mspl} + 1) AS SIGNED), 
    c.LevelId = CAST(LENGTH(c.Mask)/{$mspl} AS SIGNED), 
    c.IsParent = (EXISTS (SELECT * FROM (SELECT Mask FROM `{$tp}shop_categories` WHERE SectionId={$this->params->sectionId}) AS tmp WHERE Mask!=c.Mask AND Mask LIKE CONCAT(c.Mask, '%'))), 
    c.SelfItemsCount = (SELECT COUNT(*) FROM `{$tp}shop_items` WHERE SectionId={$this->params->sectionId} AND CategoryId=c.Id AND IsHidden=0), 
	c.TotalItemsCount = (SELECT COUNT(*) FROM `{$tp}shop_items` WHERE CategoryId IN (SELECT Id FROM (SELECT Id, Mask FROM `{$tp}shop_categories` WHERE SectionId={$this->params->sectionId} AND IsHidden=0) AS tmp WHERE Mask LIKE CONCAT(c.Mask, '%')) AND IsHidden=0)

EOD;
        return $this->db->query($sql);
    }
}
