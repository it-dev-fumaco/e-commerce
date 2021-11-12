<?php

namespace Mguinea\Robots;

use Mguinea\Robots\Contracts\Robots as RobotsContract;

class Robots implements RobotsContract
{
    const ALLOW = 'Allow';
    const DISALLOW = 'Disallow';
    const HOST = 'Host';
    const SITEMAP = 'Sitemap';
    const USER_AGENT = 'User-agent';
    const COMMENT = '#';
    const CRAWL_DELAY = 'crawl-delay';

    /**
     * The rows of for the robots.
     *
     * @var array
     */
    protected $rows = [];

    /**
     * Array with configuration set in constructor.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Robots constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
        $this->composeFromParameters();
    }

    /**
     * Create Robots object from array.
     */
    private function composeFromParameters(): void
    {
        foreach ($this->parameters as $key => $values) {
            if ($key === 'allows') {
                foreach ($values as $value) {
                    $this->addAllow($value);
                }
            } elseif ($key === 'disallows') {
                foreach ($values as $value) {
                    $this->addDisallow($value);
                }
            } elseif ($key === 'hosts') {
                foreach ($values as $value) {
                    $this->addHost($value);
                }
            } elseif ($key === 'sitemaps') {
                foreach ($values as $value) {
                    $this->addSitemap($value);
                }
            } elseif ($key === 'userAgents') {
                foreach ($values as $value) {
                    $this->addUserAgent($value);
                }
            } elseif ($key === 'crawlDelay') {
                foreach ($values as $value) {
                    $this->addCrawlDelay($value);
                }
            }
        }
    }

    /**
     * Add a allow rule to the robots.
     *
     * @param string|array $directories
     * @return RobotsContract;
     */
    public function addAllow($directories): RobotsContract
    {
        $this->addRuleLine($directories, self::ALLOW);

        return $this;
    }

    /**
     * Add a comment to the robots.
     *
     * @param string $comment
     * @return RobotsContract;
     */
    public function addComment(string $comment): RobotsContract
    {
        $this->addLine(self::COMMENT." $comment");

        return $this;
    }

    /**
     * Add a disallow rule to the robots.
     *
     * @param string|array $directories
     * @return RobotsContract;
     */
    public function addDisallow($directories): RobotsContract
    {
        $this->addRuleLine($directories, self::DISALLOW);

        return $this;
    }

    /**
     * Add a Host to the robots.
     *
     * @param string|array $host
     * @return RobotsContract;
     */
    public function addHost($hosts): RobotsContract
    {
        $this->addRuleLine($hosts, self::HOST);

        return $this;
    }

    /**
     * Add a row to the robots.
     *
     * @param string $row
     */
    protected function addLine($row)
    {
        $this->rows[] = (string) $row;
    }

    /**
     * Add multiple rows to the robots.
     *
     * @param string|array $rows
     */
    protected function addRows($rows)
    {
        foreach ((array) $rows as $row) {
            $this->addLine($row);
        }
    }

    /**
     * Add a rule to the robots.
     *
     * @param string|array $directories
     * @param string       $rule
     */
    protected function addRuleLine($directories, $rule)
    {
        foreach ((array) $directories as $directory) {
            $this->addLine("$rule: $directory");
        }
    }

    /**
     * Add a Sitemap to the robots.
     *
     * @param string|array $sitemap
     * @return RobotsContract;
     */
    public function addSitemap($sitemaps): RobotsContract
    {
        $this->addRuleLine($sitemaps, self::SITEMAP);

        return $this;
    }

    /**
     * Add a spacer to the robots.
     * @return RobotsContract;
     */
    public function addSpacer(): RobotsContract
    {
        $this->addLine('');

        return $this;
    }

    /**
     * Add a User-agent to the robots.
     *
     * @param string|array $userAgent
     * @return RobotsContract;
     */
    public function addUserAgent($userAgents): RobotsContract
    {
        $this->addRuleLine($userAgents, self::USER_AGENT);

        return $this;
    }

    /**
     * Add a allow rule to the robots.
     *
     * @param string|array $time
     * @return RobotsContract;
     */
    public function addCrawlDelay($time): RobotsContract
    {
        $this->addRuleLine($time, self::CRAWL_DELAY);

        return $this;
    }

    /**
     * Generate the robots data.
     *
     * @return string
     */
    public function generate(): string
    {
        return implode(PHP_EOL, $this->rows);
    }

    /**
     * Reset the rows.
     *
     * @return RobotsContract;
     */
    public function reset(): RobotsContract
    {
        $this->rows = [];

        return $this;
    }
}
