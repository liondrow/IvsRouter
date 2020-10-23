<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router\Loader;


use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class AnnotationDirectoryLoader
{
    public function loadDirClasses(string $controllersDir) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($controllersDir));
        $regex    = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
        foreach ($regex as $file => $value) {
            $current = $this->parseTokens(token_get_all(file_get_contents(str_replace('\\', '/', $file))));
            if ($current !== false) {
                list($namespace, $class) = $current;
                $reflClass = new \ReflectionClass($namespace . $class);
                if ($reflClass->isAbstract()) {
                    continue;
                }
                foreach ($reflClass->getMethods() as $method) {
                    $class   = $method->getDeclaringClass();
                    $context = 'method ' . $class->getName() . '::' . $method->getName() . '()';
                    var_dump($context);
                    //TODO getting annotations
                }
            }
        }

        return true;
    }

    private function parseTokens(array $tokens) {
        $nsStart    = false;
        $classStart = false;
        $namespace  = '';
        foreach ($tokens as $token) {
            if ($token[0] === T_CLASS) {
                $classStart = true;
            }
            if ($classStart && $token[0] === T_STRING) {
                return [$namespace, $token[1]];
            }
            if ($token[0] === T_NAMESPACE) {
                $nsStart = true;
            }
            if ($nsStart && $token[0] === ';') {
                $nsStart = false;
            }
            if ($nsStart && $token[0] === T_STRING) {
                $namespace .= $token[1] . '\\';
            }
        }

        return false;
    }
}
