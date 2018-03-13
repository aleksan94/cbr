<?
namespace Test\Parse;

/**
* 
*/
class ParseRates
{
    
    function __construct()
    {
        
    }

    public static function Start()
    {
        $APPLICATION->IncludeComponent(
            "cbr.parseRates"
        );
    }
}

