<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

header("Content-Type: text/html; charset=utf-8");

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

$date = date("d.m.Y");
$today = date("d/m/Y"); // Текущая дата
// получаем значения валют
$content = simplexml_load_file("https://www.cbr.ru/scripts/XML_daily.asp?date_req=".$today);
$k = 0;
foreach ($content->Valute as $k_cur => $cur) {
	$arCurrency[$k]['NumCode'] = $cur->NumCode->__toString();
	$arCurrency[$k]['CharCode'] = $cur->CharCode->__toString();
	$arCurrency[$k]['Nominal'] = $cur->Nominal->__toString();
	$arCurrency[$k]['Name'] = $cur->Name->__toString();
	$arCurrency[$k]['Value'] = $cur->Value->__toString();
	$k++;
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

// если элемент существует, то обновляем его, иначе - создаём новый
foreach ($arCurrency as $k_cur => $cur) {
	$isSet = false;
	foreach ($arRatesCurrency as $k_rate => $rate) {
		if($rate["PROPERTY_NUMCODE_VALUE"] == $cur['NumCode']) {
			$isSet = true;
			$ID = $rate['ID'];
			break;
		}
	}
	$arFields = array(
		"NAME" => $cur['Name'],
		"PROPERTY_VALUES" => array(
			"NumCode" => $cur['NumCode'],
			"CharCode" => $cur['CharCode'],
			"Nominal" => $cur['Nominal'],
			"Name" => $cur['Name'],
			"Value" => $cur['Value'],
			"Date" => $date
		)
	);
	if($isSet) {
		$blEl->Update($ID, $arFields);
	}
	else {
		$arFields['IBLOCK_ID'] = $IBLOCK_ID;
		$arFields['IBLOCK_SECTION_ID'] = $SECTION_ID['currency'];
		$arFields['ACTIVE'] = 'Y';
		$blEl->Add($arFields);
	}
}

// получаем значения котировок металлов
$content = simplexml_load_file("http://www.cbr.ru/scripts/xml_metall.asp?date_req1=" . $today . "&date_req2=" . $today);
$k = 0;
foreach ($content->Record as $k_rec => $rec) {
	$arMetall[$k]['Code'] = $rec->attributes()->Code->__toString();
	$arMetall[$k]['Buy'] = $rec->Buy->__toString();
	$arMetall[$k]['Sell'] = $rec->Sell->__toString();
	$k++;
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

// если элемент существует, то обновляем его, иначе - создаём новый
foreach ($arMetall as $k_met => $met) {
	$isSet = false;
	foreach ($arRatesMetall as $k_rate => $rate) {
		if($rate["PROPERTY_CODE_VALUE"] == $met['Code']) {
			$isSet = true;
			$ID = $rate['ID'];
			break;
		}
	}
	$arFields = array(
		"NAME" => $met['Code'],
		"PROPERTY_VALUES" => array( 
			"Buy" => $met['Buy'],
			"Sell" => $met['Sell'],
			"Code" => $met['Code'],
			"Date" => $date
		)
	);
	if($isSet) {
		$blEl->Update($ID, $arFields);
	}
	else {
		$arFields['IBLOCK_ID'] = $IBLOCK_ID;
		$arFields['IBLOCK_SECTION_ID'] = $SECTION_ID['metall'];
		$arFields['ACTIVE'] = 'Y';
		$blEl->Add($arFields);
	}
}


//echo "<pre>";print_r($arMetall);echo "</pre>";
?>
