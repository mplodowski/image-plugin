<?php

use System\Classes\PluginManager;

describe('Plugin Registration', function () {
    it('registers with October CMS and boots without errors', function () {
        $pluginManager = PluginManager::instance();
        $plugin = $pluginManager->findByIdentifier('Renatio.Image');

        expect($pluginManager->hasPlugin('Renatio.Image'))->toBeTrue()
            ->and($plugin)->not->toBeNull()
            ->and(fn () => $plugin->boot())->not->toThrow(Exception::class);
    });
});
