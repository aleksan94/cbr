<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
 
$date = date("d.m.Y");

CModule::IncludeModule('iblock');
$iblock = new CIBlock();
$blEl = new CIBlockElement();
$blSec = new CIBlockSection();

// получаем ID инфоблока из символьного кода
$arFilter = array("CODE" => "cbr_rates", "ACTIVE" => "Y");
$res = $iblock->GetList(array(), $arFilter);
$row = $res->Fetch();
$IBLOCK_ID = $row['ID'];

// получаем ID секций из символьных кодов
$arFilter = array("IBLOCK_ID" => $IBLOCK_ID, array("LOGIC" => "OR", "CODE" => "currency", "CODE" => "metall"), "ACTIVE" => "Y");
$res = $blSec->GetList(array(), $arFilter);
while($row = $res->Fetch()) {
	$SECTION_ID[$row['CODE']] = $row['ID'];
}

// выбираем элементы валют по текущей дате
$arFilter = array(
	"IBLOCK_ID" => $IBLOCK_ID,
	"SECTION_ID" => $SECTION_ID['currency'],
	"ACTIVE" => "Y",
	"PROPERTY_Date_VALUE" => $date
);
$arSelect = array("ID", "IBLOCK_ID", "PROPERTY_NumCode", "PROPERTY_CharCode", "PROPERTY_Nominal", "PROPERTY_Name", "PROPERTY_Value", "PROPERTY_Date");
$res = $blEl->GetList(array(), $arFilter, false, false, $arSelect);
while($row = $res->Fetch()) {
	$arRatesCurrency[] = $row;
}

// выбираем элементы котировок металлов по текущей дате
$arFilter = array(
	"IBLOCK_ID" => $IBLOCK_ID,
	"SECTION_ID" => $SECTION_ID['metall'],
	"ACTIVE" => "Y",
	"PROPERTY_Date_VALUE" => $date
);
$arSelect = array("ID", "IBLOCK_ID", "PROPERTY_Buy", "PROPERTY_Sell", "PROPERTY_Code", "PROPERTY_Date");
$res = $blEl->GetList(array(), $arFilter, false, false, $arSelect);
while($row = $res->Fetch()) {
	$arRatesMetall[] = $row;
}

$arResult['date'] = $date;
$arResult['rates']['currency'] = $arRatesCurrency;
$arResult['rates']['metall'] = $arRatesMetall;

$this->IncludeComponentTemplate();
?>