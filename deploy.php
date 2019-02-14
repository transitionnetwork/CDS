<?php
namespace Deployer;

require 'recipe/common.php';

// Start editable

set('repository', 'git@github.com:transitionnetwork/CDS.git');

set('ssh_type', 'native');
set('ssh_multiplexing', true);

// End editable

// Do not modify anything under this line unless you know what you're doing

inventory('servers.yml');

task('deploy:gulp', function() {
  $do_gulp = askConfirmation('Run Gulp?', false);
  if( $do_gulp ) { runLocally('gulp --production'); }
})->desc('Create dist folder');

task('deploy:upload_dist', function() {
  upload('dist', '{{release_path}}');
})->desc('Upload dist folder to server');

task('deploy:theme_composer', function() {
  cd('{{release_path}}');
  run('~/composer.phar install');
})->desc('Remote composer install');

task('setup', [
  'deploy:prepare',
])->desc('Inital deployment setup');

task('deploy', [
  'deploy:gulp',
  'deploy:release',
  'deploy:update_code',
  'deploy:upload_dist',
  'deploy:theme_composer',
  'deploy:symlink',
  'cleanup'
])->desc('Executing Deploy task');

