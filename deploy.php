<?php

namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:belbeche/blog_2022.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('https://blog.nversios.com')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/devBlog');

// Hooks

after('deploy:failed', 'deploy:unlock');
