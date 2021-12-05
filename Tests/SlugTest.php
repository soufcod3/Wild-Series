<?php

use PHPUnit\Framework\TestCase;
use App\Service\Slugify;

class SlugTest extends TestCase
{
    private string $string = 'série';

    public function testSlugsWork(string $string, Slugify $slugify)
    {
        $slug = $slugify->generate($string);


        $this->assertSame('serie', $slug);
        $this->assertSame('série', $slug);
    }
}