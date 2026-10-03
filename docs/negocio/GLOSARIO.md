## Inventario y trazabilidad (actualizado 30 sep 2026)

| Término | Definición |
|---|---|
| **APEA** | Código de ubicación física. Formato `A1S2L4` = Anaquel 1, Sección 2, Nivel 4. Nullable. |
| **Apartado / Reserva** | Material comprometido por cotización ACEPTADA, aún no entregado. |
| **Liberar apartado** | Acción manual (botón) que libera una reserva sin haber hecho salida física. |
| **Existencia** | Cantidad disponible = total_físico − apartados. |
| **Familia** | Categoría superior del inventario. Son 4: material de instalaciones, equipos electrónicos, consumibles, herramientas. |
| **Remisión** | PDF generado en cada salida: detalle + quién entrega + quién recibe + proyecto. |
| **Salida** | Movimiento de inventario hacia afuera. A proyecto o uso interno. |
| **Devolución** | Movimiento de material que regresa al almacén. |
| **Consumo** | Material que sale y no regresa (instalado o entregado al cliente). |
| **Uso interno** | Salida sin proyecto asociado (pruebas, área de sistemas). |
| **Mano de obra** | Línea de cotización con precio variable. NO ligada a inventario. Su presencia activa el flujo de instalación. |
| **Línea libre** | Línea de cotización sin FK a inventario. Análoga a mano de obra. |
| **Almacén** | Espacio físico de almacenamiento. Se identifica por nombre. |
| **Familia de herramientas** | Categoría del inventario para herramientas de trabajo. Se mezclan con material en la misma tabla. |