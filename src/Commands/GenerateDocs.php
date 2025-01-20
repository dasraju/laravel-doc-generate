<?php

namespace Raju\DocGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateDocs extends Command
{
    protected $signature = 'doc:generate {path=app/Http/Controllers}';
    protected $description = 'Generate documentation from controller comments.';

    public function handle()
    {
        $path = base_path($this->argument('path')); // Path to controllers
        $files = File::allFiles($path);

        $docs = [];
        foreach ($files as $file) {
            $content = File::get($file->getPathname());
            $className = $this->getClassNameFromFile($file->getPathname());

            if ($className) {
                $methods = $this->getMethodsWithMarkup($content);

                foreach ($methods as $methodName => $docBlocks) {
                    foreach ($docBlocks as $docBlock) {
                        $parsedDoc = $this->parseDocBlock($docBlock);
                        $parsedDoc['Controller'] = $className;
                        $parsedDoc['Function'] = $methodName;
                        $docs[] = $parsedDoc;
                    }
                }
            }
        }

        $outputFile = storage_path('docs.json');
        File::put($outputFile, json_encode($docs, JSON_PRETTY_PRINT));

        $this->info("Documentation generated successfully: {$outputFile}");
    }

    private function getClassNameFromFile($filePath)
    {
        $content = File::get($filePath);

        if (preg_match('/namespace\s+(.+);/', $content, $namespaceMatch)) {
            $namespace = $namespaceMatch[1];

            if (preg_match('/class\s+(\w+)/', $content, $classMatch)) {
                return $namespace . '\\' . $classMatch[1];
            }
        }

        return null;
    }

    private function getMethodsWithMarkup($content)
    {
        $methodsWithMarkup = [];

        // Match functions
        preg_match_all('/function\s+(\w+)\s*\(/', $content, $functionMatches, PREG_OFFSET_CAPTURE);

        foreach ($functionMatches[1] as $match) {
            $methodName = $match[0];
            $startPosition = $match[1];

            // Find /* raju */ comment blocks before each function
            $preFunctionContent = substr($content, 0, $startPosition);
            preg_match_all('/\/\* raju(.*?)\*\//s', $preFunctionContent, $docMatches, PREG_OFFSET_CAPTURE);

            if (!empty($docMatches[1])) {
                $methodsWithMarkup[$methodName] = array_map(fn($match) => trim($match[0]), $docMatches[1]);
            }
        }

        return $methodsWithMarkup;
    }

    private function parseDocBlock($docBlock)
    {
        $lines = explode("\n", $docBlock);
        $data = [];

        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                [$key, $value] = explode(':', $line, 2);
                $data[trim($key)] = trim($value);
            }
        }

        return $data;
    }
}
