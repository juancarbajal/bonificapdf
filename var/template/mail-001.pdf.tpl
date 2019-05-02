<html>
  <head>
	<script type="text/css">
	  @page {
	  size: 21cm 29.7cm;
	  margin: 2cm;
      }
	  body {
	  padding: .5in;
	  margin: 2cm; 
	  }
	  </script>
  <head>
	<body>
		<table width="100%">
			<tr>
				<td width="40%"><img src="{$appLogo}"/>
				</td>
				<td width="60%" align="right">Detalle {$docNumber}<br/>
				  Validaciones del {$docFrom} al {$docTo}<br/>
				  {$companyName}
				</td>
			</tr>
		</table>
		

		<br/>
		<br/>
		<br/>
		<h3>DATOS DE LA FACTURA</h3>
		
        <p>
		<b>Razón social:</b> {$appCompanyName} <br>
		<b>RUC:</b> {$appCompanyRuc} <br>
		<b>Dirección:</b> {$appCompanyAddress} <br>
		<b>Tipo de Moneda:</b> {$docCurrency} <br>
		<b>Concepto:</b> Por convenio del {$docFrom} al {$docTo} según detalle {$docNumber} <br>
		<b>Valor Unitario:</b> {$docCurrencySym} {$docSubtotal} <br>
		<b>IGV:</b> {$docCurrencySym} {$docIgv} <br>
		<b>Importe total:</b> {$docCurrencySym} {$docTotal}
		</p>
		<br>
		<hr/>
		<br/>
		<table width="100%" border="1" style="border-collapse: collapse;">
			<tr  bgcolor="#BCDDFF">
				<th width="20%" align="center">Id Pedido</th>
				<th width="20%" align="center">Fecha y Hora<br/> Redencion</th>
				<th width="20%" align="center">Bonificación</th>
				<th width="20%" align="center">DNI</th>
				<th width="20%" align="center">Precio</th>
			</tr>
			{foreach $detail as $item}
			<tr>			  
			  <td align="center">{$item.idPedido}</td>
			  <td align="center">{$item.datePedido}</td>
			  <td align="center">{$item.bonificacionPedido}</td>
			  <td align="center">{$item.dniPedido}</td>
			  <td align="right">{$item.totalPedido}</td>			  
			</tr>
			{/foreach}
		</table>
		
	</body>
</html>
