<?php

namespace App\Helpers;

class NumeroALetras
{
    public static function convertir($numero, $moneda = 'USD')
    {
        // Implementación básica - puedes usar una librería como "NumeroALetras"
        $monedas = ['USD' => 'DÓLARES AMERICANOS', 'MXN' => 'PESOS MEXICANOS', 'EUR' => 'EUROS'];
        $monedaStr = $monedas[$moneda] ?? 'DÓLARES';

        $entero = floor($numero);
        $decimal = round(($numero - $entero) * 100);

        $letras = self::convertirNumero($entero);
        return strtoupper($letras) . " $monedaStr $decimal/100.";
    }

    private static function convertirNumero($numero)
    {
        // Función simplificada - puedes usar una implementación más completa
        $unidades = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

        if ($numero == 100) return 'CIEN';
        if ($numero < 10) return $unidades[$numero];
        if ($numero < 20) {
            $especiales = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISÉIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
            return $especiales[$numero - 10];
        }
        if ($numero < 100) {
            $d = intdiv($numero, 10);
            $u = $numero % 10;
            if ($u == 0) return $decenas[$d];
            return $decenas[$d] . ' Y ' . $unidades[$u];
        }
        if ($numero < 1000) {
            $c = intdiv($numero, 100);
            $r = $numero % 100;
            if ($r == 0) return $centenas[$c];
            return $centenas[$c] . ' ' . self::convertirNumero($r);
        }
        if ($numero < 1000000) {
            $m = intdiv($numero, 1000);
            $r = $numero % 1000;
            $texto = ($m == 1) ? 'MIL' : self::convertirNumero($m) . ' MIL';
            if ($r == 0) return $texto;
            return $texto . ' ' . self::convertirNumero($r);
        }
        if ($numero < 1000000000) {
            $mm = intdiv($numero, 1000000);
            $r = $numero % 1000000;
            $texto = ($mm == 1) ? 'UN MILLÓN' : self::convertirNumero($mm) . ' MILLONES';
            if ($r == 0) return $texto;
            return $texto . ' ' . self::convertirNumero($r);
        }
        return 'NÚMERO DEMASIADO GRANDE';
    }
}