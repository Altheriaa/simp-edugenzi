<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

test('login gagal mengarahkan kembali ke halaman login dengan error', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->withoutVite()->post('/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors('email');
});

test('login sukses mengarahkan admin ke dashboard admin', function () {
    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
    ]);

    $response = $this->withoutVite()->post('/login', [
        'email' => 'admin@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('login sukses mengarahkan mentor ke dashboard mentor', function () {
    $user = User::factory()->create([
        'email' => 'mentor@example.com',
        'password' => Hash::make('password123'),
        'role' => 'mentor',
    ]);

    $response = $this->withoutVite()->post('/login', [
        'email' => 'mentor@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('mentor.dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('login sukses mengarahkan peserta didik ke dashboard peserta', function () {
    $user = User::factory()->create([
        'email' => 'peserta@example.com',
        'password' => Hash::make('password123'),
        'role' => 'peserta_didik',
    ]);

    $response = $this->withoutVite()->post('/login', [
        'email' => 'peserta@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('peserta.dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('logout mengarahkan kembali ke halaman login', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/logout');

    $response->assertRedirect('/login');
    $this->assertGuest();
});
