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

test('halaman register dapat diakses', function () {
    $response = $this->get('/signup');

    $response->assertStatus(200);
});

test('register sukses membuat user baru dan redirect ke login', function () {
    $response = $this->withoutVite()->post('/signup', [
        'nama_lengkap' => 'Peserta Test',
        'username' => 'pesertatest',
        'email' => 'peserta.test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'username' => 'pesertatest',
        'email' => 'peserta.test@example.com',
        'role' => 'peserta_didik',
    ]);
});

test('register gagal jika konfirmasi password tidak cocok', function () {
    $response = $this->withoutVite()->post('/signup', [
        'nama_lengkap' => 'Peserta Test Fail',
        'username' => 'pesertatestfail',
        'email' => 'peserta.testfail@example.com',
        'password' => 'password123',
        'password_confirmation' => 'berbeda123',
    ]);

    $response->assertSessionHasErrors('password');
});

