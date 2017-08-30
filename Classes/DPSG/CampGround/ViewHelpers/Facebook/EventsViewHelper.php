<?php

namespace DPSG\CampGround\ViewHelpers\Facebook;

use Neos\Flow\Annotations as Flow;

class EventsViewHelper extends \Neos\FluidAdaptor\Core\ViewHelper\AbstractTagBasedViewHelper
{


    /**
     * @Flow\InjectConfiguration(path="FacebookIntegration.PageId")
     * @var integer
     */
    protected $pageId;


    /**
     * @Flow\InjectConfiguration(path="FacebookIntegration.AccessToken")
     * @var string
     */
    protected $accessToken;


    /**
     * @var Boolean
     */
    protected $configurationIsComplete = FALSE;


    /**
     * @var string
     */
    protected $fields = 'id,name,description,location,venue,timezone,start_time,end_time,cover';


    public function initializeObject()
    {
        $this->configurationIsComplete = $this->pageId != '' && $this->accessToken != '';
    }


    /**
     * @param integer $sinceDays
     * @param integer $untilDays
     * @param integer $maxEvents
     * @param string $coverType
     * @return string
     */
    public function render($sinceDays = 7, $untilDays = 365, $maxEvents = 0, $coverType = 'square')
    {


        if (!$this->configurationIsComplete) {
            return 'Bitte trage die Facebook Page Id und dein AceessToken in die Settings ein.';
        }

        $events = $this->loadEvents($sinceDays, $untilDays, $maxEvents);

        $output = '';
        $month = '';
        $year = '';

        foreach ($events as $event) {

            $event['url'] = sprintf('https://www.facebook.com/events/%s/', $event['id']);

            $date = new \DateTime($event['start_time']);

            if ($month !== $date->format('d')) {
                $month = $date->format('d');
                $this->templateVariableContainer->add('newMonth', TRUE);
            }

            if ($year !== $date->format('Y')) {
                $year = $date->format('Y');
                $this->templateVariableContainer->add('newYear', TRUE);
            }

            if ($coverType === 'source') {
                $event['cover']['url'] = $event['cover']['source'];
            } else {
                $event['cover']['url'] = sprintf('https://graph.facebook.com/%s/picture?type=%s', $event['id'], $coverType);
            }

            $this->templateVariableContainer->add('event', $event);
            $output .= $this->renderChildren();
            $this->templateVariableContainer->remove('event');

            if ($this->templateVariableContainer->exists('newMonth')) $this->templateVariableContainer->remove('newMonth');
            if ($this->templateVariableContainer->exists('newYear')) $this->templateVariableContainer->remove('newYear');
        }

        return $output;
    }


    /**
     * @param $sinceDays
     * @param $untilDays
     * @param $maxEvents
     * @return array
     */
    protected function loadEvents($sinceDays, $untilDays, $maxEvents)
    {
        $sinceTime = time() - ($sinceDays * 86400);
        $untilTime = time() + ($untilDays * 86400);

        $json_link = sprintf('https://graph.facebook.com/v2.2/%s/events/?fields=%s&access_token=%s&since=%s&until=%s',
            $this->pageId, $this->fields, $this->accessToken, $sinceTime, $untilTime
        );

        $jsonData = file_get_contents($json_link);


        $data = json_decode($jsonData, TRUE);
        $events = $data['data'];
        krsort($events);

        if ($maxEvents > 0) {
            $events = array_slice($events, 0, $maxEvents);
            return $events;
        }
        return $events;
    }
}
