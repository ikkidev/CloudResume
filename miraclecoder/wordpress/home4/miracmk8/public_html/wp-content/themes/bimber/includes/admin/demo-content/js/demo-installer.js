/* global jQuery */
/* global document */
/* global confirm */
/* global ajaxurl */

(function ($) {

    'use strict';

    var $demoWrapper;
    var imagesCount;
    var itemsToInstall;     // Plugins + images.
    var itemsInstalled;

    function demoInstaller(id, $demo, $plugins) {
        var imagesCount      = 0;
        var itemsToInstall   = 0;
        var itemsInstalled   = 0;
        var pluginsToInstall = [];

        var init = function() {
            bindEvents();
        };

        var bindEvents = function() {
            // Handle import type selection.
            $demo.find('.g1-import-type [type=checkbox]').on('change', importTypeChanged);

            // Handle uninstall type selection.
            $demo.find('.g1-uninstall-type [type=checkbox]').on('change', uninstallTypeChanged);

            // Uninstall demo data.
            $demo.find('.g1-uninstall-demo-data').on('click', uninstall);

            $demo.on('g1ItemInstallationEnded', updateProgressBar);

            // Check dependencies before run installation.
            $demo.find('.g1-install-demo-data').on('click', checkDependencies);

            $demo.on('g1DependenciesLoaded', preInstall);

            // All dependencies installed. Install required plugins.
            $demo.on('g1RunInstallation', initAndInstallPlugins);

            $demo.on('g1PluginsInstalled', uploadImages);

            $demo.on('g1ImagesUploaded', installContent);
        };

        var getImportType = function() {
            var types = [];

            $demo.find('.g1-import-type input:checked').each(function() {
                types.push($(this).val());
            });

            return types.join(',');
        };

        var getUninstallType = function() {
            var types = [];

            $demo.find('.g1-uninstall-type input:checked').each(function() {
                types.push($(this).val());
            });

            return types.join(',');
        };

        var importTypeChanged = function() {
            var $checkbox = $(this);
            var type = $checkbox.val();

            if ($checkbox.is(':checked')) {
                // Uncheck all other types.
                if ('all' === type) {
                    $demo.find('.g1-import-type input[type=checkbox]').not('[value=all]').removeAttr('checked');
                // Uncheck the All type.
                } else {
                    $demo.find('.g1-import-type input[value=all]').removeAttr('checked');
                }
            }
        };

        var uninstallTypeChanged = function() {
            var $checkbox = $(this);
            var type = $checkbox.val();

            if ($checkbox.is(':checked')) {
                // Uncheck all other types.
                if ('all' === type) {
                    $demo.find('.g1-uninstall-type input[type=checkbox]').not('[value=all]').removeAttr('checked');
                    // Uncheck the All type.
                } else {
                    $demo.find('.g1-uninstall-type input[value=all]').removeAttr('checked');
                }
            }
        };

        var uninstall = function(e) {
            e.preventDefault();

            var uninstallType = getUninstallType();

            if (!uninstallType) {
                alert('Please choose the uninstall type');
                return;
            }

            var uninstallUrl = $demo.find('a.g1-uninstall-demo-data').attr('href');

            uninstallUrl = uninstallUrl.replace('%TYPE%', uninstallType);

            window.location.href = uninstallUrl;
        };

        var checkDependencies = function(e) {
            e.preventDefault();

            var importType = getImportType();

            if (!importType) {
                alert('Please choose the import type');
                return;
            }

            if ( 'widgets' === importType || 'theme-options' === importType ) {
                installContent();
                return;
            }

            // Init the progress bar.
            $demo.addClass('g1ui-plugicon-pending');

            // Reset states of other demos.
            $demo.parents('.g1ui-demo-items').find('.g1ui-demo').each(function () {
                var $container = $(this);

                $container.removeClass('g1ui-plugicon-unchecked');
                $container.addClass('g1ui-plugicon-omitted');
            });

            $demo.removeClass('g1ui-plugicon-omitted');
            $demo.removeClass('g1ui-plugicon-checked');
            $demo.find('.g1ui-plugins-installed').addClass('g1ui-loading');

            installWordPressImporter(function(succeeded) {
                if (!succeeded) {
                    alert('WordPress Importer plugin installation failed! Please install it manually and run demo installation again.');
                    $demo.removeClass('g1ui-plugicon-pending');
                    return;
                }

                // All dependencies installed.
                $demo.trigger('g1DependenciesLoaded');
            });
        };

        var preInstall = function() {
            var importType = getImportType();

            var importImages = ('all' === importType || 'content' === importType);

            if (importImages) {
                uploadImagesStart(function(res) {
                    imagesCount = parseInt(res.count, 10);

                    itemsToInstall += imagesCount;

                    $demo.trigger('g1RunInstallation');
                });
            } else {
                $demo.trigger('g1RunInstallation');
            }
        };

        var initAndInstallPlugins = function() {
            setImportState('started');

            pluginsToInstall = getPluginsToInstall();

            // Set 3 times bigger weight for a plugin than an image. Just to make progress a little bit more resposive at the beginning.
            itemsToInstall += 3 * pluginsToInstall.length;

            installPlugins(pluginsToInstall);
        };

        var installContent = function() {
            var importType = getImportType();

            var installUrl = $demo.find('a.g1-install-demo-data').attr('href');

            installUrl = installUrl.replace('%TYPE%', importType);

            setTimeout(function () {
                setImportState('ended');

                $demo.find('.g1ui-progress-bar').css('width', '100%');
                $demo.find('.g1ui-progress-percentage').text('100%');
                $demo.find('.g1ui-plugins-installed').removeClass('g1ui-loading');

                window.location.href = installUrl;
            }, 1000);
        };

        var updateProgressBar = function(e, itemType, log) {
            if ('plugin' === itemType) {
                itemsInstalled += 3;
            } else {
                itemsInstalled++;
            }

            $('#g1-demo-import-log').append('<p>'+ log +' (' + itemsInstalled + '/' + itemsToInstall + ')</p>');

            var percentage = Math.round(itemsInstalled / itemsToInstall * 100);

            $demo.find('.g1ui-progress-bar').css('width', percentage + '%');
            $demo.find('.g1ui-progress-percentage').text(percentage + '%');

            if (itemsInstalled === itemsToInstall) {
                $demo.find('.g1ui-plugins-installed').removeClass('g1ui-loading');
            }
        };

        var getPluginsToInstall = function() {
            var plugins = [];

            $plugins.each(function () {
                var $container = $(this);

                // Skip, this one will be processed separately.
                if ($container.is('.g1ui-plugicon-wordpress-importer')) {
                    return;
                }

                // Install plugin only if enabled for current demo.
                if ($container.is('.g1ui-plugicon-checked') && ( $container.is('.g1-demos-all') || $container.is('.g1-demo-' + id ) )) {
                    $container.removeClass('g1ui-plugicon-checked');
                    $container.removeClass('g1ui-plugicon-unchecked');
                    $container.addClass('g1ui-plugicon-pending');
                    plugins.push($container);
                } else {
                    $container.removeClass('g1ui-plugicon-unchecked');
                    $container.addClass('g1ui-plugicon-omitted');
                }
            });

            return plugins;
        };

        var installWordPressImporter = function(finishCallback) {
            var $container = $plugins.filter('.g1ui-plugicon-wordpress-importer');

            // If there is no plugin container, this means that plugin has been installed already.
            if ($container.length === 0) {
                finishCallback(true);
                return;
            }

            var $plugin = $container.find('input.g1-plugin-to-install');
            var url = $plugin.attr('data-g1-install-url');

            $container.removeClass('g1ui-plugicon-pending');
            $container.addClass('g1ui-plugicon-loading');

            $.get(url, function (data) {
                var $errorMessage = $(data).find('#message.error');

                var status = $errorMessage.length === 0 ? 'success' : 'failure';

                $container.removeClass('g1ui-plugicon-loading');

                var succeeded = (status === 'success');

                if (succeeded) {
                    $container.addClass('g1ui-plugicon-succeed');
                } else {
                    $container.addClass('g1ui-plugicon-failed');
                }

                finishCallback(succeeded);
            });
        };

        var uploadImagesStart = function(callback) {
            var xhr = $.ajax({
                'type': 'GET',
                'url':  $('#g1-upload-demo-images-start').val(),
                'dataType': 'json',
                'data': {
                    'demo': id
                }
            });

            xhr.done(function (response) {
                callback(response);
            });
        };

        var uploadImagesEnd = function() {
            $.ajax({
                'type': 'GET',
                'url':  $('#g1-upload-demo-images-end').val(),
                'dataType': 'json'
            });
        };

        var setImportState = function(state) {
            $.ajax({
                'type': 'POST',
                'url': ajaxurl,
                'dataType': 'json',
                'data': {
                    'action': 'bimber_demo_import_' + state
                }
            });
        };

        var installPlugins = function(plugins) {
            if (plugins.length === 0) {
                $demo.trigger('g1PluginsInstalled');
                return;
            }

            // get first plugin from list
            var $container = plugins.shift();
            var $plugin = $container.find('input.g1-plugin-to-install');
            var pluginId = $container.attr('data-g1-plugin-id');
            var url = $plugin.attr('data-g1-install-url');

            $container.removeClass('g1ui-plugicon-pending');
            $container.addClass('g1ui-plugicon-loading');

            // install plugin
            $.get(url, function (data) {
                var $errorMessage = $(data).find('#message.error');

                var status = $errorMessage.length === 0 ? 'success' : 'failure';

                $container.removeClass('g1ui-plugicon-loading');

                if (status === 'success') {
                    $container.addClass('g1ui-plugicon-succeed');
                } else {
                    $container.addClass('g1ui-plugicon-failed');
                }

                var message = 'Plugin ' + pluginId + ' installation completed';

                $demo.trigger('g1ItemInstallationEnded', ['plugin', message]);

                // process the rest of plugins
                // it's done this way to use async ajax calls and in the same time install plugins one after another, not asynchronously
                // TGM has problem with installing more than one plugin via "Install" link. Batch action is for this.
                installPlugins(plugins);
            });
        };

        var uploadImages = function() {
            $demo.removeClass('g1ui-plugicon-pending');
            $demo.addClass('g1ui-plugicon-loading');

            if (imagesCount === 0) {
                uploadImagesEnd();
                $demo.trigger('g1ImagesUploaded');
                return;
            }

            var currentImageNb = 1;

            uploadImage(id, currentImageNb, function(processedImages) {
                var message = 'Image #'+ processedImages +' upload completed';

                $demo.trigger('g1ItemInstallationEnded', ['image', message]);

                if (processedImages >= imagesCount) {
                    uploadImagesEnd();
                    $demo.trigger('g1ImagesUploaded');

                }
            });
        };

        var uploadImage = function(demoId, currentImageNb, callback) {
            var xhr = $.ajax({
                'type': 'GET',
                'url':  $('#g1-upload-demo-image').val(),
                'data': {
                    'demo': demoId,
                    'nb':   currentImageNb
                }
            });

            xhr.done(function () {
                callback(currentImageNb);

                currentImageNb++;

                if (currentImageNb <= imagesCount) {
                    uploadImage(demoId, currentImageNb, callback);
                }
            });
        };

        return init();
    }

    $(document).ready(function () {
        var $wrapper = $('#g1ui-settings-section-demos');

        if ($wrapper.length === 0) {
            return;
        }

        var $demos   = $wrapper.find('.g1ui-demo-items > .g1ui-demo-item');
        var $plugins = $wrapper.find('.g1ui-plugicons > .g1ui-plugicon');

        $demos.each(function() {
            var $demoItem = $(this);
            var $demo = $demoItem.find('.g1ui-demo');
            var demoId = $demo.attr('data-g1-demo-id');

            demoInstaller(demoId, $demo, $plugins);
        });
    });

})(jQuery);
