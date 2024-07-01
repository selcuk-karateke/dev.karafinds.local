<?php
// classes/SEOMonitor.php
class SEOMonitor
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    private function fetchContent($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');

        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode === 200) {
            return $content;
        } else {
            return false;
        }
    }

    public function getMetaTags()
    {
        $html = $this->fetchContent($this->url);

        if ($html) {
            preg_match('/<title>(.*?)<\/title>/', $html, $titleMatches);
            preg_match('/<meta\s+name="description"\s+content="(.*?)"/i', $html, $descriptionMatches);

            return [
                'title' => $titleMatches[1] ?? 'Kein Titel gefunden',
                'description' => $descriptionMatches[1] ?? 'Keine Beschreibung gefunden'
            ];
        } else {
            return [
                'title' => 'Kein Titel gefunden',
                'description' => 'Keine Beschreibung gefunden'
            ];
        }
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
        $content = $this->fetchContent($robotsUrl);
        return $content !== false ? $content : 'Keine robots.txt gefunden';
    }
}
