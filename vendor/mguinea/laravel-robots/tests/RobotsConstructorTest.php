<?php

namespace Mguinea\Robots\Tests;

use Mguinea\Robots\Robots;
use Orchestra\Testbench\TestCase as Orchestra;

class RobotsConstructorTest extends Orchestra
{
    /** @var \Mguinea\Robots\Robots */
    protected $robots;

    public function setUp(): void
    {
        parent::setUp();
        $this->robots = new Robots([
            'allows' => [
                'foo', 'bar',
            ],
            'disallows' => [
                'foo', 'bar',
            ],
            'hosts' => [
                'foo', 'bar',
            ],
            'sitemaps' => [
                'foo', 'bar',
            ],
            'userAgents' => [
                'foo', 'bar',
            ],
            'crawlDelay' => [
                10,
            ],
        ]);
    }

    public function testComposition()
    {
        $expected = "Allow: foo\nAllow: bar\n";
        $expected .= "Disallow: foo\nDisallow: bar\n";
        $expected .= "Host: foo\nHost: bar\n";
        $expected .= "Sitemap: foo\nSitemap: bar\n";
        $expected .= "User-agent: foo\nUser-agent: bar\n";
        $expected .= 'crawl-delay: 10';
        $this->assertEquals($expected, $this->robots->generate());
    }

    public function testAllows()
    {
        $robots = new Robots([
            'allows' => [
                'foo', 'bar',
            ],
        ]);
        $expected = "Allow: foo\nAllow: bar";
        $this->assertEquals($expected, $robots->generate());
    }
}
