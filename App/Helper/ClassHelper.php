<?php


namespace App\Helper;


use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ClassHelper
{
    /**
     * @param string $namespace
     *
     * @return array
     */
    public function findRecursive(string $namespace): array
    {
        $namespace_path = $this->translateNamespacePath($namespace);
        if ($namespace_path === '') {
            return [];
        }

        return $this->searchClasses($namespace, $namespace_path);
    }

    /**
     * @param string $namespace
     *
     * @return string
     */
    protected function translateNamespacePath(string $namespace): string
    {
        $root_path = APP_DIR . DIRECTORY_SEPARATOR;

        $ns_parts = explode('\\', $namespace);
//        array_shift($nsParts);

        if (empty($ns_parts)) {
            return '';
        }

        return realpath($root_path. implode(DIRECTORY_SEPARATOR, $ns_parts)) ?: '';
    }

    private function searchClasses(string $namespace, string $namespace_path): array
    {
        $classes = [];

        /**
         * @var \RecursiveDirectoryIterator $iterator
         * @var \SplFileInfo $item
         */
        foreach ($iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($namespace_path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        ) as $item) {
            if ($item->isDir()) {
                $next_path = $iterator->current()->getPathname();
                $next_namespace = $namespace . '\\' . $item->getFilename();
                $classes = array_merge($classes, $this->searchClasses($next_namespace, $next_path));
                continue;
            }
            if ($item->isFile() && $item->getExtension() === 'php') {
                $class = $namespace . '\\' . $item->getBasename('.php');
                if (!class_exists($class)) {
                    continue;
                }
                $classes[] = $class;
            }
        }

        return $classes;
    }
}