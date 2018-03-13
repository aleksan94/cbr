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

if(!empty($_REQUEST['do'])) {
	if($_REQUEST['do'] == 'send_mail') {
		foreach($arRatesCurrency as $k_cur => $cur) {
			if($cur['PROPERTY_CHARCODE_VALUE'] == "EUR") {
				$EUR = $cur['PROPERTY_VALUE_VALUE'];
			}
			else if($cur['PROPERTY_CHARCODE_VALUE'] == "USD") {
				$USD = $cur['PROPERTY_VALUE_VALUE'];
			}
		}

		$dateTime = date("d.m.Y H:i:s");

		$fields = array(
			"EMAIL" => $_REQUEST['email'],
			"DATETIME" => $dateTime,
			"EUR" => $EUR,
			"USD" => $USD
		);

		if(CEvent::Send("SEND_RATES", "s1", $fields, "N", 8)) {
			// получаем ID инфоблока из символьного кода
			$arFilter = array("CODE" => "send_rates", "ACTIVE" => "Y");
			$res = $iblock->GetList(array(), $arFilter);
			$row = $res->Fetch();
			$IBLOCK_ID = $row['ID'];

			$arFields = array(
				"NAME" => $_REQUEST['email'],
				"IBLOCK_ID" => $IBLOCK_ID,
				"ACTIVE" => 'Y',
				"PROPERTY_VALUES" => array(
					"EMAIL" => $_REQUEST['email'],
					"DATETIME" => $dateTime,
					"EUR" => $EUR,
					"USD" => $USD
				)
			);

			//echo "<pre>";print_r($arFields);echo "</pre>";return;

			if($blEl->Add($arFields)) {
				$arResult['isSend'] = true;
				$arResult['email'] = $_REQUEST['email'];
			}
		}
	}
}

$this->IncludeComponentTemplate();
?>