<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github.com:aristidesneto/fcontrol-api.git');

add('shared_files', [
    '.env'
]);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts
host('develop')
    ->set('hostname', '24.199.90.78')
    ->set('remote_user', 'deploy')
    ->set('port', '22345')
    ->set('branch', 'develop')
    ->set('keep_releases', 3)
    ->set('deploy_path', '/var/www/html/fcontrol-api.linkinside.com.br');


desc('Deploys your project');
task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:config:cache',
    'artisan:route:cache',
    'artisan:view:cache',
    'artisan:event:cache',
    // 'artisan:migrate',
    'deploy:publish',
]);

// Hooks

after('deploy:failed', 'deploy:unlock');
