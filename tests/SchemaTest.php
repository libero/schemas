<?php

namespace tests\Libero\Schemas;

use DOMDocument;
use FluentDOM;
use FluentDOM\DOM\ProcessingInstruction;
use LibXMLError;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;
use function Functional\map;
use function libxml_clear_errors;
use function preg_match;

final class SchemaTest extends TestCase
{
    /**
     * @before
     */
    public function clearLibXmlErrors() : void
    {
        libxml_clear_errors();
    }

    /**
     * @dataProvider validFileProvider
     */
    public function testValid(DOMDocument $document, string $schema) : void
    {
        $result = $document->relaxNGValidate($schema);

        $this->assertTrue($result);
    }

    /**
     * @dataProvider invalidFileProvider
     */
    public function testInvalid(DOMDocument $document, string $schema, array $expected) : void
    {
        $document->relaxNGValidate($schema);

        $actual = map(
            libxml_get_errors(),
            function (LibXMLError $error) : array {
                return [
                    'line' => $error->line,
                    'text' => trim($error->message),
                ];
            }
        );

        $this->assertSame($expected, $actual);
    }

    public function validFileProvider() : iterable
    {
        $files = Finder::create()->files()
            ->name('*.xml')
            ->in(__DIR__)
            ->path('~/valid/~');

        return $this->extractSchemas($files);
    }

    public function invalidFileProvider() : iterable
    {
        $files = Finder::create()->files()
            ->name('*.xml')
            ->in(__DIR__)
            ->path('~/invalid/~');

        return $this->extractSchemas($files);
    }

    private function extractSchemas(Finder $files) : iterable
    {
        foreach ($files as $file) {
            $dom = FluentDOM::load($file->getContents());

            $schema = $dom('substring-before(substring-after(/processing-instruction("xml-model"), \'"\'), \'"\')');
            $schema = "{$file->getPath()}/{$schema}";

            $expectedFailures = map(
                $dom('/processing-instruction("expected-failure")'),
                function (ProcessingInstruction $instruction) : array {
                    preg_match('~line="([0-9]+)"\s+text="([^"]*?)"~', $instruction->nodeValue, $matches);

                    return [
                        'line' => (int) $matches[1],
                        'text' => $matches[2],
                    ];
                }
            );

            yield $file->getRelativePathname() => [$dom, $schema, $expectedFailures];
        }
    }
}
