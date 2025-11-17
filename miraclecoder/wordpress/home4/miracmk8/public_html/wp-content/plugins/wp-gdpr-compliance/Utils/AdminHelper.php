<?php

namespace WPGDPRC\Utils;

use WPGDPRC\Integrations\Plugins\ContactForm;
use WPGDPRC\WordPress\Plugin;

/**
 * Class HelperAdmin
 * @package WPGDPRC\Utils
 */
class AdminHelper
{

    /**
     * Wraps the WP admin notice properly
     *
     * @param string $message
     * @param string $type
     * @param bool $dismissible
     *
     * @return string
     */
    public static function wrapNotice($message = '', $type = 'success', $dismissible = true)
    {
        Template::render(
            'Admin/Elements/notice-simple',
            [
                'message'     => $message,
                'type'        => $type,
                'dismissible' => $dismissible,
            ]
        );
    }

    /**
     * Gets the admin page url (with keeping the other GET data)
     *
     * @param string $page
     *
     * @return string
     */
    public static function getPageUrl($page = '')
    {
        $args         = [];
        $args['page'] = $page;

        return add_query_arg($args, admin_url('admin.php'));
    }

    /**
     * Checks if the plugin is installed
     *
     * @param string $file
     *
     * @return bool
     */
    public static function pluginInstalled($file = '')
    {
        if (empty($file)) {
            return false;
        }

        return file_exists(WP_PLUGIN_DIR . '/' . $file);
    }

    /**
     * Checks if the plugin is activated
     *
     * @param string $file
     *
     * @return bool
     */
    public static function pluginActivated($file = '')
    {
        if (empty($file)) {
            return false;
        }

        return is_plugin_active($file);
    }

    /**
     * Checks if the plugin is enabled
     *
     * @param string $file
     *
     * @return bool
     */
    public static function pluginEnabled($file = '')
    {
        $list = self::activePlugins();

        return in_array($file, $list, true);
    }

    /**
     * Lists all the active plugins for the current site
     *
     * @param bool $show_data
     *
     * @return array
     */
    public static function activePlugins($show_data = false)
    {
        $list = get_option('active_plugins', []);
        if (!is_array($list)) {
            $list = [];
        }

        // check for multi site plugins
        if (is_multisite()) {
            $option = get_site_option('active_sitewide_plugins', []);
            if (is_iterable($option)) {
                foreach ($option as $file => $timestamp) {
                    if (in_array($file, $list, true)) {
                        continue;
                    }
                    $list[] = $file;
                }
            }
        }
        if (empty($list)) {
            return [];
        }

        // remove current plugin from array
        $key = array_search(plugin_basename(WPGDPRC_ROOT_FILE), $list);
        if ($key !== false) {
            unset($list[$key]);
        }
        if (empty($show_data)) {
            return $list;
        }

        foreach ($list as $key => $file) {
            $list[$key] = [
                'basename' => plugin_basename($file),
                'file'     => $file,
            ];

            $data = get_plugin_data(WP_PLUGIN_DIR . '/' . $file);
            if (isset($data['Name'])) {
                $list[$key]['slug'] = sanitize_title($data['Name']);
                $list[$key]['name'] = $data['Name'];
            }

            if (isset($data['Description'])) {
                $list[$key]['description'] = $data['Description'];
            }
        }

        return $list;
    }

    /**
     * Checks if a mail plugin is installed (& activated)
     * @return bool
     */
    public static function hasMailPlugin()
    {
        foreach (self::activePlugins() as $plugin) {
            if (strpos(strtolower($plugin), 'mail') !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the plugin version is supported
     *
     * @param string $file
     * @param string $version
     *
     * @return bool
     */
    public static function pluginSupported($file = '', $version = '')
    {
        if (empty($file)) {
            return false;
        }
        if (empty($version)) {
            return false;
        }

        $path = WP_PLUGIN_DIR . '/' . $file;
        if (!file_exists($path)) {
            return false;
        }

        $data = get_plugin_data($path);

        return (bool)version_compare($data['Version'], $version, '>=');
    }

    /**
     * @param string $type
     * @param int $site_id
     *
     * @return string
     */
    public static function getSiteData($type = '', $site_id = 0)
    {
        if (empty($type)) {
            return '';
        }

        if (empty($site_id)) {
            $site_id = get_current_blog_id();
        }

        return is_multisite() ? get_blog_option($site_id, $type, '') : get_option($type, '');
    }

    /**
     * @param int $site_id
     *
     * @return array
     */
    public static function getSiteInfo($site_id = 0)
    {
        return [
            'name'  => self::getSiteData('blogname', $site_id),
            'email' => self::getSiteData('admin_email', $site_id),
            'url'   => self::getSiteData('siteurl', $site_id),
        ];
    }

    /**
     * Checks if table exists in WP database
     *
     * @param string $table
     *
     * @return bool
     */
    public static function tableExists($table = '')
    {
        if (empty($table)) {
            return false;
        }

        global $wpdb;

        return $wpdb->get_var("SHOW TABLES LIKE '" . $table . "'") === $table;
    }

    /**
     * @param string $plugin
     *
     * @return string
     */
    public static function getAllowedHTMLTagsOutput($plugin = '')
    {
        $output = sprintf(
            '<strong>%1s:</strong> %2s',
            strtoupper(_x('Note', 'admin', 'wp-gdpr-compliance')),
            _x('No HTML allowed due to plugin limitations.', 'admin', 'wp-gdpr-compliance')
        );

        $allowed = self::getAllowedHTMLTags($plugin);
        if (empty($allowed)) {
            return $output;
        }

        $tags = PrivacyPolicy::REPLACER;
        foreach ($allowed as $tag => $attributes) {
            $tags .= ' <' . $tag;
            if (empty($attributes)) {
                $tags .= '>';
                continue;
            }

            foreach ($attributes as $attribute => $data) {
                $tags .= ' ' . $attribute . '=""';
            }
            $tags .= '>';
        }

        /* translators: %1s: The allowed html tags as a list. */
        return esc_html(sprintf(__('You can use: %1s', 'wp-gdpr-compliance'), $tags));
    }

    /**
     * @param string $plugin
     *
     * @return mixed
     */
    public static function getAllowedHTMLTags($plugin = '')
    {
        switch ($plugin) {
            case ContactForm::getInstance()->getID():
                $output = '';
                break;

            default:
                $output = [
                    'a'      => [
                        'class'    => [],
                        'href'     => [],
                        'hreflang' => [],
                        'title'    => [],
                        'target'   => [],
                        'rel'      => [],
                    ],
                    'br'     => [],
                    'em'     => [],
                    'strong' => [],
                    'u'      => [],
                    'strike' => [],
                    'span'   => [
                        'class' => [],
                    ],
                    'h2'     => [],
                    'p'      => [],
                    'ol'     => [],
                    'ul'     => [],
                    'li'     => [],
                    'abbr'   => [
                        'class' => [],
                        'title' => []
                    ],
                    'span'   => [
                        'data-icon' => [],
                        'class'     => [],
                    ],
                    'legend' => [
                        'class' => []
                    ],
                    'label'  => [
                        'class' => [],
                        'for'   => []
                    ],
                    'small'  => [
                        'class' => true,
                    ],
                ];
                break;
        }

        return apply_filters(Plugin::PREFIX . '_allowed_html_tags', $output, $plugin);
    }

    /**
     * @return \bool[][]
     */
    public static function getAllowedSvgTags(): array
    {
        return [
            'span'    => [
                'data-icon' => true,
                'class'     => true
            ],
            'svg'     => [
                'class'           => true,
                'aria-hidden'     => true,
                'aria-labelledby' => true,
                'role'            => true,
                'xmlns'           => true,
                'width'           => true,
                'height'          => true,
                'viewbox'         => true,
                'data-icon'       => true,
            ],
            'g'       => ['fill' => true],
            'title'   => ['title' => true],
            'path'    => [
                'd'    => true,
                'fill' => true,
            ],
            'polygon' => [
                'points' => true,
                'fill'   => true,
                'id'     => true,
            ],
            'use'     => [
                'href' => true,
            ]
        ];
    }

    /**
     * @return array
     */
    public static function getAllAllowedSvgTags(): array
    {
        return array_merge(self::getAllowedSvgTags(), self::getAllowedHTMLTags());
    }

    /**
     * Check if the user can manage plugin settings
     *
     * @return bool
     */
    public static function userIsAdmin(): bool
    {
        return current_user_can('manage_options');
    }
}
