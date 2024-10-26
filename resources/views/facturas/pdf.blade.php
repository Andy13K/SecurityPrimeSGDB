<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Factura #{{ str_pad($factura->no_factura, 8, '0', STR_PAD_LEFT) }}</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url({{ storage_path('fonts/DejaVuSans.ttf') }});
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 10px;
        }
        .datos-factura {
            margin-bottom: 20px;
        }
        .datos-cliente {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f8f9fa;
        }
        .totales {
            margin-top: 20px;
            text-align: right;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FACTURA</h1>
        <h2>No. {{ str_pad($factura->no_factura, 8, '0', STR_PAD_LEFT) }}</h2>
    </div>

    <div class="datos-factura">
        <table>
            <tr>
                <td><strong>Fecha de Emisión:</strong></td>
                <td>{{ is_object($factura->fecha_emision) ? $factura->fecha_emision->format('d/m/Y') : date('d/m/Y', strtotime($factura->fecha_emision)) }}</td>
                <td><strong>NIT:</strong></td>
                <td>{{ $factura->nit }}</td>
            </tr>
        </table>
    </div>

    <div class="datos-cliente">
        <h3>Datos del Cliente</h3>
        <table>
            <tr>
                <td><strong>Cliente:</strong></td>
                <td>{{ $factura->proyecto->cliente->nombre ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Dirección:</strong></td>
                <td>{{ $factura->proyecto->cliente->direccion ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Proyecto:</strong></td>
                <td>{{ $factura->proyecto->nombre ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <h3>Detalle de Factura</h3>
    @if(count($detalles['recursos']) > 0)
    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles['recursos'] as $recurso)
            <tr>
                <td>{{ $recurso['nombre'] }}</td>
                <td style="text-align: center;">{{ number_format($recurso['cantidad'], 0) }}</td>
                <td style="text-align: right;">Q {{ number_format($recurso['precio_unitario'], 2) }}</td>
                <td style="text-align: right;">Q {{ number_format($recurso['subtotal'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Subtotal Recursos:</strong></td>
                <td style="text-align: right;">Q {{ number_format($detalles['subtotal_recursos'], 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Mano de Obra:</strong></td>
                <td style="text-align: right;">Q {{ number_format($detalles['mano_obra'], 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>TOTAL:</strong></td>
                <td style="text-align: right;"><strong>Q {{ number_format($factura->total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    @else
    <p>No hay recursos registrados para esta factura.</p>
    @endif

    <div class="footer">
        <p>Factura generada el {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>