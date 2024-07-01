<?php
// classes/SEOMonitor.php
class SEOMonitor
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getMetaTags()
    {
        $html = file_get_contents($this->url);

        // Korrigierte reguläre Ausdrücke
        preg_match('/<title>(.*?)<\/title>/', $html, $titleMatches);
        preg_match('/<meta\s+name="description"\s+content="(.*?)"/i', $html, $descriptionMatches);

        return [
            'title' => $titleMatches[1] ?? 'Kein Titel gefunden',
            'description' => $descriptionMatches[1] ?? 'Keine Beschreibung gefunden'
        ];
    }

    public function getXMLSitemap()
    {
        $sitemapUrl = $this->url . '/sitemap.xml';
        $headers = get_headers($sitemapUrl, 1);
        return strpos($headers[0], '200') !== false ? $sitemapUrl : 'Keine Sitemap gefunden';
    }

    public function getRobotsTxt()
    {
        $robotsUrl = $this->url . '/robots.txt';
        $content = @file_get_contents($robotsUrl);
        return $content !== false ? $content : 'Keine robots.txt gefunden';
    }
}
