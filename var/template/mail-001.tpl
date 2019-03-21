<html>
	<body>
		Estimado {$companyName}
		Le adjuntamos el reporte de redenciones realizadas en el mes de {$monthName}, para la emisión de la factura.<br>
		Le rogamos no contestar este correo y enviar la factura electrónica a {$appMail} <br>
		En caso de dudas comunicarse al número {$appPhone} <br>
		El equipo de {$appName}.
		
		<table>
			<tr>
				<td>{$appLogo}
				</td>
				<td>Detalle {$docNumber} <br>
					Validaciones del {$docFrom} al {$docTo} del {$docYear}
				</td>
			</tr>
		</table>
		
		
		DATOS DE LA FACTURA

		Razón social: {$appCompanyName} <br>
		RUC: {$appCompanyRuc} <br>
		Dirección: {$appCompanyAddress} <br>
		Tipo de Moneda: {$docCurrency} <br>
		Concepto: Por convenio del {$docFrom} al {$docTo} según detalle {$docDetailNumber} <br>
		Valor Unitario: {$docCurrencySym} {$docSubtotal} <br>
		IGV: {$docCurrencySym} {$docIgv} <br>
		Importe total: {$docCurrencySym} {$docTotal}
		<br><br>

		<table>
			<thead>
				<th>Id Pedido</th>
				<th>Fecha y Hora</th>
				<th>Redención</th>
				<th>Bonificación</th>
				<th>DNI</th>
				<th>Precio</th>
			</thead>
			{foreach $detail as $item}
			<tr>			  
			  <td></td>
			  <td></td>
			  <td></td>
			  <td></td>
			  <td></td>
			  <td></td>
			</tr>
			{/foreach}
		</table>
		
	</body>
</html>
