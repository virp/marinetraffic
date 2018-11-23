<?php

declare(strict_types=1);

namespace Virp\Marinetraffic;

use Exception;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Virp\Marinetraffic\Exceptions\LoadContentException;
use Virp\Marinetraffic\Exceptions\PositionLinkException;

/**
 * Class Parser
 */
class Parser
{
    const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36';
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $content;

    /**
     * @param string $url
     * @return Position|null
     * @throws LoadContentException
     * @throws PositionLinkException
     */
    public function position(string $url): ?Position
    {
        $this->url = $url;

        return $this->load()->parse();
    }

    /**
     * @return Parser
     * @throws LoadContentException
     */
    private function load(): Parser
    {
        $client = new Client();
        try {
            $this->content = $client
                ->get(
                    $this->url,
                    [
                        'headers' => [
                            'User-Agent' => self::USER_AGENT,
                        ],
                    ]
                )
                ->getBody()
                ->getContents();
        } catch (Exception $e) {
            throw new LoadContentException('Failing load content from url: '.$this->url, 0, $e);
        }

        return $this;
    }

    /**
     * @return Position|null
     * @throws PositionLinkException
     */
    private function parse(): ?Position
    {
        $crawler = new Crawler($this->content);
        try {
            $positionLink = $crawler->filterXPath('//a[@class="details_data_link"]')->getNode(0);
        } catch (Exception $e) {
            throw new PositionLinkException('Failing parse position link', 1, $e);
        }

        if (!$positionLink) {
            return null;
        }

        $content = $positionLink->textContent;

        preg_match_all('/(-?\d*\.\d*)/', $content, $coordinates);

        if (!isset($coordinates[0]) || count($coordinates[0]) !== 2) {
            return null;
        }

        return new Position((float)$coordinates[0][0], (float)$coordinates[0][1]);
    }
}