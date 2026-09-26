<?php

test('usa la base de datos de testing', function () {
    expect(config('database.connections.mysql.database'))->toBe('proyectosavi_testing');
});

test('puede conectarse a la base de datos', function () {
    $result = DB::select('SELECT DATABASE() as db');
    expect($result[0]->db)->toBe('proyectosavi_testing');
});
