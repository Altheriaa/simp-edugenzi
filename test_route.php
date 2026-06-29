<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'admin')->first();
if($user) {
    Auth::login($user);
}

$request = Illuminate\Http\Request::create('/admin/peserta-didik', 'GET');
$response = $kernel->handle($request);

echo "STATUS: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() == 500) {
    echo $response->getContent();
}
