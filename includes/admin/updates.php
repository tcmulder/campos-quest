<?php 
/**
 * Plugin update handler
 */

require_once CAMPOS_QUEST_PLUGIN_INC . 'admin/updater-checker.php';

$github_username = 'tcmulder';
$github_repository = 'campos-quest';
$plugin_basename = CAMPOS_QUEST_BASENAME;
$plugin_current_version = CAMPOS_QUEST_VERSION; // Use the current version of the plugin

$updater = new Campos_Quest_Updater_Checker(
    $github_username,
    $github_repository,
    $plugin_basename,
    $plugin_current_version
);
$updater->set_hooks();