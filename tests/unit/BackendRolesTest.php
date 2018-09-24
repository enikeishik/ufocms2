<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';

use \Ufocms\Backend\Config;
use \Ufocms\Frontend\Db;
use \Ufocms\Backend\Roles;
 
class BackendRolesTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var Config
     */
    protected $config;
    
    /**
     * @var Db
     */
    protected $db;
    
    /**
     * Roles
     */
    protected $roles;
    
    public function _before()
    {
        $this->config = new Config();
        $this->db = new Db();
        $this->setDbData();
        $this->createRoles();
    }
    
    public function _after()
    {
        $this->removeDbData();
        $this->db->close();
        $this->db = null;
        $this->config = null;
    }
    
    protected function createRoles() //to avoid Roles methods caching
    {
        if (null !== $this->roles) {
            unset($this->roles);
            $this->roles = null;
        }
        $this->roles = new Roles($this->config, $this->db);
    }
    
    protected function setDbData()
    {
        $sql =  'INSERT INTO `' . C_DB_TABLE_PREFIX . 'users`' . 
                ' (`Id`, `Login`, `Password`)' . 
                ' VALUES' . 
                " (-101, 'test101', 'test101')," . //admin
                " (-102, 'test102', 'test102')," . //editor
                " (-103, 'test103', 'test103')," . //moderator
                " (-104, 'test104', 'test104')," . //journalist
                " (-105, 'test105', 'test105')," . //writer
                " (-106, 'test106', 'test106')," . //custom manager, can create Modules content, can create and edit Core content, without restrictions
                " (-107, 'test107', 'test107')," . //without rights
                " (-108, 'test108', 'test108')," . //restrict to all
                " (-109, 'test109', 'test109')" .  //no roles
                '';
        $this->db->query($sql);
        
        $sql =  'INSERT INTO `' . C_DB_TABLE_PREFIX . 'users_roles`' . 
                ' (`Id`, `IsSystem`, `Title`, `Description`)' . 
                ' VALUES' . 
                " (-106, 0, 'Manager', 'Test custom manager')," . 
                " (-107, 0, 'Manager', 'Test user without rights')," . 
                " (-108, 0, 'Manager', 'Test user restrict to all')" . 
                '';
        $this->db->query($sql);
        
        $sql =  'INSERT INTO `' . C_DB_TABLE_PREFIX . 'users_roles_perms_mods`' . 
                ' (`RoleId`, `ModuleId`, `Permissions`)' . 
                ' VALUES' . 
                ' (-106, 0, \'{"create":true,"edit":false,"disable":false,"enable":false,"delete":false}\'),' . 
                ' (-108, 0, \'{"create":true,"edit":true,"disable":false,"enable":false,"delete":false}\')' . 
                '';
        $this->db->query($sql);
        
        $sql =  'INSERT INTO `' . C_DB_TABLE_PREFIX . 'users_roles_perms_core`' . 
                ' (`RoleId`, `CoreModule`, `Permissions`)' . 
                ' VALUES' . 
                ' (-106, \'\', \'{"create":true,"edit":true,"disable":false,"enable":false,"delete":false}\'),' . 
                ' (-108, \'\', \'{"create":true,"edit":true,"disable":false,"enable":false,"delete":false}\')' . 
                '';
        $this->db->query($sql);
        
        $sql =  'INSERT INTO `' . C_DB_TABLE_PREFIX . 'users_roles_restrictions`' . 
                ' (`RoleId`, `CoreModules`, `Modules`)' . 
                ' VALUES' . 
                " (-106, '', '')," . 
                " (-107, '', '')," . 
                " (-108, 'none', 'none')" . 
                '';
        $this->db->query($sql);
        
        $sql =  'INSERT INTO `' . C_DB_TABLE_PREFIX . 'users_roles_relations`' . 
                ' (`UserId`, `RoleId`)' . 
                ' VALUES' . 
                '(-101, 1),' . 
                '(-102, 2),' . 
                '(-103, 3),' . 
                '(-104, 4),' . 
                '(-105, 5),' . 
                '(-106, -106),' . 
                '(-107, -107),' . 
                '(-108, -108)' . 
                '';
        $this->db->query($sql);
    }
    
    protected function removeDbData()
    {
        $sql =  'DELETE FROM `' . C_DB_TABLE_PREFIX . 'users_roles_relations`' . 
                ' WHERE `UserId` IN (-101, -102, -103, -104, -105, -106, -107, -108, -109)';
        $this->db->query($sql);
        
        $sql =  'DELETE FROM `' . C_DB_TABLE_PREFIX . 'users_roles_restrictions`' . 
                ' WHERE `RoleId` IN (-106, -107, -108)';
        $this->db->query($sql);
        
        $sql =  'DELETE FROM `' . C_DB_TABLE_PREFIX . 'users_roles_perms_core`' . 
                ' WHERE `RoleId` IN (-106, -108)';
        $this->db->query($sql);
        
        $sql =  'DELETE FROM `' . C_DB_TABLE_PREFIX . 'users_roles_perms_mods`' . 
                ' WHERE `RoleId` IN (-106, -108)';
        $this->db->query($sql);
        
        $sql =  'DELETE FROM `' . C_DB_TABLE_PREFIX . 'users_roles`' . 
                ' WHERE `Id` IN (-106, -107, -108)';
        $this->db->query($sql);
        
        $sql =  'DELETE FROM `' . C_DB_TABLE_PREFIX . 'users`' . 
                ' WHERE `Id` IN (-101, -102, -103, -104, -105, -106, -107, -108, -109)';
        $this->db->query($sql);
    }
    
    public function rolesRestrictedDataProvider()
    {
        return [
            //system user `admin` with system role 1 (rights not checked)
            ['-1', 'CoreSections', null, false], 
            ['-1', 'CoreInteraction', null, false], 
            ['-1', 1, null, false], 
            ['-1', 4, null, false], 
            ['-1', -1, null, false], 
            ['-1', -1, -1, false], 
            ['-1', 1, 1, false], 
            ['-1', 9999, 9999, false], 
            ['-1', '', null, false], 
            ['-1', 'SomeUnexistsCoreModule', 9999, false], 
            ['-1', 'Mod', 9999, false], 
            
            //editor
            ['-102', 'CoreRoles', null, true], 
            ['-102', 'CoreSite', null, true], 
            ['-102', 'CoreSections', null, true], 
            ['-102', 'CoreTools', null, true], 
            ['-102', 'CoreUsers', null, true], 
            ['-102', 'CoreWidgets', null, false], 
            ['-102', 'CoreQuotes', null, false], 
            ['-102', 'CoreInteraction', null, false], 
            ['-102', 'CoreComments', null, false], 
            ['-102', 'CoreSendform', null, false], 
            ['-102', 'CoreFilemanager', null, false], 
            ['-102', 'CoreXmlsitemap', null, false], 
            ['-102', 'Mod', null, true], //module not exists
            ['-102', 1, null, false], //module documents allowed (no restrictions)
            ['-102', -1, null, false], 
            ['-102', 2, null, false], 
            ['-102', 999, null, false], 
            ['-102', 0, null, false], //module not exists
            
            //moderator
            ['-103', 'CoreSections', null, true], 
            ['-103', 'CoreInteraction', null, false], 
            ['-103', 'Mod', null, true], //module not exists
            ['-103', 1, null, true], //module documents restricted
            ['-103', 3, null, false], //module gbook allowed
            ['-103', 4, null, false], 
            ['-103', 6, null, false], 
            ['-103', 10, null, false], 
            ['-103', 17, null, false], 
            ['-103', 0, null, true], //module not exists
            
            //journalist
            ['-104', 'CoreRoles', null, true], 
            ['-104', 'CoreSite', null, true], 
            ['-104', 'CoreSections', null, true], 
            ['-104', 'CoreTools', null, true], 
            ['-104', 'CoreUsers', null, true], 
            ['-104', 'CoreWidgets', null, true], 
            ['-104', 'CoreQuotes', null, true], 
            ['-104', 'CoreInteraction', null, true], 
            ['-104', 'CoreComments', null, true], 
            ['-104', 'CoreSendform', null, true], 
            ['-104', 'CoreFilemanager', null, true], 
            ['-104', 'CoreXmlsitemap', null, true], 
            ['-104', 'Mod', null, true], //module not exists
            ['-104', 1, null, true], //module documents restricted
            ['-104', 2, null, false], //module news allowed
            ['-104', 6, null, false], 
            ['-104', 16, null, false], 
            ['-104', 17, null, false], 
            ['-104', 25, null, false], 
            ['-104', 0, null, true], //module not exists
            
            //writer
            ['-105', 'CoreRoles', null, true], 
            ['-105', 'CoreSite', null, true], 
            ['-105', 'CoreSections', null, true], 
            ['-105', 'CoreTools', null, true], 
            ['-105', 'CoreUsers', null, true], 
            ['-105', 'CoreWidgets', null, true], 
            ['-105', 'CoreQuotes', null, true], 
            ['-105', 'CoreInteraction', null, true], 
            ['-105', 'CoreComments', null, true], 
            ['-105', 'CoreSendform', null, true], 
            ['-105', 'CoreFilemanager', null, true], 
            ['-105', 'CoreXmlsitemap', null, true], 
            ['-105', 'Mod', null, true], //module not exists
            ['-105', 1, null, true], //module documents restricted
            ['-105', 2, null, false], //module news allowed
            ['-105', 6, null, false], 
            ['-105', 16, null, false], 
            ['-105', 17, null, false], 
            ['-105', 25, null, false], 
            ['-105', 0, null, true], //module not exists
            
        ];
    }
    
    /**
     * @dataProvider rolesRestrictedDataProvider
     */
    public function testRolesRestricted($userId, $module, $sectionId, $expected)
    {
        $result = $this->roles->rolesRestricted($userId, $module, $sectionId);
        $this->assertEquals($expected, $result);
    }
    
    public function rolesPermittedActionDataProvider()
    {
        return [
            //system user `admin` with system role 1 (rights not checked)
            ['-1', 'CoreSections', 'create', true], 
            ['-1', 'CoreComments', 'delete', true], 
            ['-1', 1, 'edit', true], 
            ['-1', 'CoreInteraction', 'enable', true], 
            
            //editor
            ['-102', 'CoreSections', 'create', true], //action permitted for all modules, but modules restricted
            ['-102', 'CoreSections', 'edit', true], 
            ['-102', 'CoreSections', 'disable', true], 
            ['-102', 'CoreSections', 'enable', true], 
            ['-102', 'CoreSections', 'delete', true], 
            ['-102', 1, 'create', true], 
            ['-102', 1, 'edit', true], 
            ['-102', 1, 'disable', true], 
            ['-102', 1, 'enable', true], 
            ['-102', 1, 'delete', true], 
            
            //moderator
            ['-103', 'CoreComments', 'enable', true], 
            ['-103', 'CoreComments', 'disable', true], 
            ['-103', 'CoreInteraction', 'enable', true], 
            ['-103', 'CoreInteraction', 'disable', true], 
            ['-103', 'CoreSections', 'enable', true], //action permitted for all modules, but modules restricted
            ['-103', 'CoreSections', 'disable', true], 
            ['-103', -1, 'enable', true], 
            ['-103', -1, 'disable', true], 
            
            //journalist
            ['-104', 2, 'create', true], 
            ['-104', 2, 'edit', true], 
            ['-104', 2, 'enable', false], 
            ['-104', 2, 'disable', false], 
            ['-104', 2, 'delete', false], 
            
            //writer
            ['-105', 2, 'create', true], 
            ['-105', 2, 'edit', true], 
            ['-105', 2, 'enable', true], 
            ['-105', 2, 'disable', true], 
            ['-105', 2, 'delete', false], 
            
        ];
    }
    
    /**
     * @dataProvider rolesPermittedActionDataProvider
     */
    public function testRolesPermittedAction($userId, $module, $action, $expected)
    {
        $result = $this->roles->rolesPermittedAction($userId, $module, $action);
        $this->assertEquals($expected, $result);
    }
    
    //united test
    
    public function roleAccessDataProvider()
    {
        return [
            //system user `admin` with system role 1 (rights not checked)
            ['-1', 'CoreSections', null, 'create', true], 
            ['-1', 'Mod', null, 'create', true], 
            
            //editor
            ['-102', 'CoreSections', null, 'create', false], 
            ['-102', 'CoreSections', null, 'edit', false], 
            ['-102', 'CoreSections', null, 'disable', false], 
            ['-102', 'CoreSections', null, 'enable', false], 
            ['-102', 'CoreSections', null, 'delete', false], 
            ['-102', 'CoreWidgets', null, 'create', true], 
            ['-102', 'CoreWidgets', null, 'edit', true], 
            ['-102', 'CoreWidgets', null, 'disable', true], 
            ['-102', 'CoreWidgets', null, 'enable', true], 
            ['-102', 'CoreWidgets', null, 'delete', true], 
            ['-102', 'Mod', null, 'create', false], 
            
            //moderator
            ['-103', 'CoreSections', null, 'enable', false], 
            ['-103', 'CoreComments', null, 'enable', true], 
            ['-103', 'CoreInteraction', null, 'enable', true], 
            ['-103', 'Mod', null, 'create', false], 
            ['-103', 3, null, 'create', false], 
            ['-103', 3, null, 'edit', false], 
            ['-103', 3, null, 'disable', true], 
            ['-103', 3, null, 'enable', true], 
            ['-103', 3, null, 'delete', false], 
            
            //journalist
            ['-104', 'CoreSections', null, 'create', false], 
            ['-104', 'Mod', null, 'create', false], 
            ['-104', 2, null, 'create', true], 
            ['-104', 2, null, 'edit', true], 
            ['-104', 2, null, 'disable', false], 
            ['-104', 2, null, 'enable', false], 
            ['-104', 2, null, 'delete', false], 
            ['-104', 1, null, 'create', false], 
            ['-104', 1, null, 'edit', false], 
            ['-104', 1, null, 'disable', false], 
            ['-104', 1, null, 'enable', false], 
            ['-104', 1, null, 'delete', false], 
            
            //writer
            ['-105', 'CoreSections', null, 'create', false], 
            ['-105', 'Mod', null, 'create', false], 
            ['-105', 2, null, 'create', true], 
            ['-105', 2, null, 'edit', true], 
            ['-105', 2, null, 'disable', true], 
            ['-105', 2, null, 'enable', true], 
            ['-105', 2, null, 'delete', false], 
            ['-105', 1, null, 'create', false], 
            ['-105', 1, null, 'edit', false], 
            ['-105', 1, null, 'disable', false], 
            ['-105', 1, null, 'enable', false], 
            ['-105', 1, null, 'delete', false], 
            
        ];
    }
    
    /**
     * @dataProvider roleAccessDataProvider
     */
    public function testRoleAccess($userId, $module, $sectionId, $action, $expected)
    {
        $result = 
            !$this->roles->rolesRestricted($userId, $module, $sectionId) 
            && 
            $this->roles->rolesPermittedAction($userId, $module, $action);
        $this->assertEquals($expected, $result);
    }
}
