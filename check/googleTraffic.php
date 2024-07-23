<?php
require '../vendor/autoload.php';

use Google\Client;
use Google\Service\AnalyticsData;

// URL und Property ID aus den GET-Parametern holen
$url = isset($_GET['url']) ? $_GET['url'] : null;
$propertyId = isset($_GET['propertyId']) ? $_GET['propertyId'] : null;

if ($url && $propertyId) {
    function initializeAnalytics()
    {
        $KEY_FILE_LOCATION = '../auth/dev-karafinds-fa95634a602e.json'; // Pfad zur JSON-Schlüsseldatei

        $client = new Google\Client();
        $client->setApplicationName("Google Analytics Reporting");
        $client->setAuthConfig($KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new Google\Service\AnalyticsData($client);

        return $analytics;
    }

    function getReport($analytics, $propertyId)
    {
        $request = new Google\Service\AnalyticsData\RunReportRequest([
            'dateRanges' => [
                new Google\Service\AnalyticsData\DateRange([
                    // 'startDate' => '7daysAgo',
                    'startDate' => '30daysAgo', // Zeitraum erweitern
                    'endDate' => 'today',
                ]),
            ],
            'dimensions' => [
                new Google\Service\AnalyticsData\Dimension([
                    'name' => 'sessionSourceMedium',
                ]),
                new Google\Service\AnalyticsData\Dimension([
                    'name' => 'sessionCampaignId',
                ]),
            ],
            'metrics' => [
                new Google\Service\AnalyticsData\Metric([
                    'name' => 'sessions',
                ]),
            ],
        ]);

        return $analytics->properties->runReport('properties/' . $propertyId, $request);
    }

    function printResults($report)
    {
        $rows = [];

        if ($report->getRows()) {
            foreach ($report->getRows() as $row) {
                $rowArray = [];
                foreach ($row->getDimensionValues() as $index => $dimensionValue) {
                    $rowArray['dimension' . $index] = $dimensionValue->getValue();
                }
                foreach ($row->getMetricValues() as $index => $metricValue) {
                    $rowArray['metric' . $index] = $metricValue->getValue();
                }
                $rows[] = $rowArray;
            }
        }

        return $rows;
    }

    try {
        $analytics = initializeAnalytics();
        $response = getReport($analytics, $propertyId);
        $data = printResults($response);

        header('Content-Type: application/json');
        $jsonData = json_encode(['data' => $data]); // Hinzufügen eines Wrappers zur Klarstellung
        echo $jsonData;
    } catch (Google_Service_Exception $e) {
        header('Content-Type: application/json');
        $errorData = json_encode([
            'error' => 'There was an error accessing the Google Analytics API',
            'message' => $e->getMessage(),
            'errors' => $e->getErrors()
        ]);

        echo $errorData;
    } catch (Exception $e) {
        header('Content-Type: application/json');
        $errorData = json_encode([
            'error' => 'An unexpected error occurred',
            'message' => $e->getMessage()
        ]);

        echo $errorData;
    }
} else {
    echo json_encode(['error' => 'Keine URL oder Property ID angegeben']);
}
