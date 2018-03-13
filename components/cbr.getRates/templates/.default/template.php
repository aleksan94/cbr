<h2>Курс котировок металлов на <b><?=$arResult['date']?></b></h2>
<table>
	<thead>
		<th>Цифровой код</th>
		<th>Покупка, руб.</th>
		<th>Продажа, руб.</th>
	</thead>
	<tbody>
		<?foreach($arResult['rates']['metall'] as $k_rate => $rate):?>
			<tr>
				<td><?=$rate['PROPERTY_CODE_VALUE']?></td>
				<td><?=$rate['PROPERTY_BUY_VALUE']?></td>
				<td><?=$rate['PROPERTY_SELL_VALUE']?></td>
			</tr>
		<?endforeach;?>
	</tbody>
</table>

<h2>Курс валют на <b><?=$arResult['date']?></b></h2>
<table>
	<thead>
		<th>Цифровой код</th>
		<th>Символьный код</th>
		<th>Название</th>
		<th>Номинал</th>
		<th>Значение, руб.</th>
	</thead>
	<tbody>
		<?foreach($arResult['rates']['currency'] as $k_rate => $rate):?>
			<tr>
				<td><?=$rate['PROPERTY_NUMCODE_VALUE']?></td>
				<td><?=$rate['PROPERTY_CHARCODE_VALUE']?></td>
				<td><?=$rate['PROPERTY_NAME_VALUE']?></td>
				<td><?=$rate['PROPERTY_NOMINAL_VALUE']?></td>
				<td><?=$rate['PROPERTY_VALUE_VALUE']?></td>
			</tr>
		<?endforeach;?>
	</tbody>
</table>

<style type="text/css">
	table {
		width: 100%;
	}
	table th {
		text-align: left;
	}
</style>