<?php

test('halaman utama redirect ke login', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});

test('halaman login dapat diakses', function () {
    $response = $this->withoutVite()->get('/login');

    $response->assertStatus(200);
});
