<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

class test_showtable extends CModule
{
    var $MODULE_ID;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    function __construct() {
        // заполняем обязательные поля
        $this->MODULE_ID= Loc::getMessage("MODULE_ID");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("MODULE_DESCRIPTION");
    }
    
    /**
     * Установка модуля
     */
    function DoInstall()
    {
        // регистрируем в системе
        ModuleManager::registerModule($this->MODULE_ID);
    }

    /**
     * Удаление модуля
     */
    function DoUninstall()
    {
        // удаляем из системы
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}
