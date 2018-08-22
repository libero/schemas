<?php

namespace tests\Libero\Schemas;

use DOMDocument;
use FluentDOM;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;
use function Functional\pluck;
use function str_replace;

final class SchemaTest extends TestCase
{
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
        $actual = [];
        set_error_handler(
            function (int $number, string $string) use (&$actual) : void {
                $actual[] = str_replace('DOMDocument::relaxNGValidate(): ', '', $string);
            }
        );
        $result = $document->relaxNGValidate($schema);
        restore_error_handler();

        $this->assertFalse($result);
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

            $expectedFailures = pluck(
                $dom('/processing-instruction("expected-failure")'),
                'nodeValue'
            );

            yield $file->getRelativePathname() => [$dom, $schema, $expectedFailures];
        }
    }
}
