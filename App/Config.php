<?php


namespace App;


use App\Exception\ConfigException;

class Config
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * @var string
     */
    private $main_config_file = APP_DIR . '/config/config.php';

    /**
     * @var string
     */
    private $default_config_dir = APP_DIR . '/config.d';

    public function __construct(string $main_config_file = '', string $default_config_dir = '')
    {
        if ($main_config_file) {
            $this->setMainConfigFile($main_config_file);
        }
        if ($default_config_dir) {
            $this->setDefaultConfigDir($default_config_dir);
        }
        $main_config = $this->requireMainConfig();
        $default_config = $this->parseDefaultConfigDir();
        $this->config = $this->mergeConfigs($default_config, $main_config);
    }

    /**
     * @return array
     * @throws ConfigException
     */
    private function requireMainConfig(): array
    {
        $main_config_file = $this->getMainConfigFile();
        if (!is_dir($main_config_file) && file_exists($main_config_file)) {
            $main_config = require realpath($main_config_file);
        } else {
            throw new ConfigException("Config '$main_config_file' is not found");
        }
        return $main_config;
    }

    /**
     * @param string $default_config_dir
     *
     * @return array
     */
    private function parseDefaultConfigDir(string $default_config_dir = ''): array
    {
        $default_config = [];
        if (!$default_config_dir) {
            $default_config_dir = realpath($this->getDefaultConfigDir());
        }
        if (!is_dir($default_config_dir)) {
            return [];
        }
        $default_config_dir_items = glob($default_config_dir . '/*', GLOB_MARK);
        foreach ($default_config_dir_items as $default_config_dir_item) {
            $key = pathinfo($default_config_dir_item)['filename'];
            if (is_dir($default_config_dir_item)) {
                $result = $this->parseDefaultConfigDir($default_config_dir_item);
            } else {
                try {
                    $result = require $default_config_dir_item;
                } catch (\Exception $e) {
                    $result = null;
                }
            }
            if (!is_null($result)) {
                $default_config[$key] = $result;
            }
        }
        return $default_config;
    }

    /**
     * @param array $main_config
     * @param array $default_config
     *
     * @return array
     */
    private function mergeConfigs(array $default_config, array $main_config): array
    {
        return array_replace_recursive($default_config, $main_config);
    }

    /**
     * @param string $item_path
     *
     * @return array|mixed|null
     */
    public function get(string $item_path)
    {
        $result = $this->config;
        $item_path_parts = explode('.', $item_path);
        foreach ($item_path_parts as $item_path_part) {
            if (is_array($result) && isset($result[$item_path_part])) {
                $result = $result[$item_path_part];
            } else {
                $result = null;
                break;
            }
        }
        return $result;
    }

    /**
     * @return string
     */
    private function getMainConfigFile(): string
    {
        return $this->main_config_file;
    }

    /**
     * @param string $main_config_file
     */
    private function setMainConfigFile(string $main_config_file): void
    {
        $this->main_config_file = $main_config_file;
    }

    /**
     * @return string
     */
    private function getDefaultConfigDir(): string
    {
        return $this->default_config_dir;
    }

    /**
     * @param string $default_config_dir
     */
    private function setDefaultConfigDir(string $default_config_dir): void
    {
        $this->default_config_dir = $default_config_dir;
    }
}