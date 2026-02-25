<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$src = file_get_contents(resource_path('views/frontend/layouts/app.blade.php'));
$compiled = app('blade.compiler')->compileString($src);
$path = storage_path('app/blade_layout_compiled.php.txt');
file_put_contents($path, $compiled);
echo $path;
