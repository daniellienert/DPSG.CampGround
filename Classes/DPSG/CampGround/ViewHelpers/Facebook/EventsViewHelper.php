<?php
namespace DPSG\CampGround\ViewHelpers\Facebook;

use TYPO3\Flow\Annotations as Flow;

class EventsViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * @Flow\Inject(setting="FacebookIntegration.PageId")
	 * @var integer
	 */
	protected $pageId;


	/**
	 * @Flow\Inject(setting="FacebookIntegration.AccessToken")
	 * @var string
	 */
	protected $accessToken;


	/**
	 * @var string
	 */
	protected $fields = 'id,name,description,location,venue,timezone,start_time,end_time,cover';

	/**
	 * @param integer $sinceDays
	 * @param integer $untilDays
	 * @param integer $maxEvents
	 * @param string $coverType
	 * @return string
	 */
	public function render($sinceDays = 7, $untilDays = 365, $maxEvents = 0, $coverType = 'square') {

		$sinceTime = time() - ($sinceDays * 86400);
		$untilTime = time() + ($untilDays * 86400);

		$json_link = sprintf('https://graph.facebook.com/%s/events/feed/?fields=%s&access_token=%s&since=%s&until=%s',
			$this->pageId, $this->fields, $this->accessToken, $sinceTime, $untilTime
		);

		$jsonData = file_get_contents($json_link);
		$data = json_decode($jsonData, TRUE);
		$events = $data['data'];
		krsort($events);

		if($maxEvents > 0) {
			$events = array_slice($events, 0, $maxEvents);
		}

		$output = '';
		$month = '';
		$year = '';

		foreach($events as $event) {

			$event['url'] = sprintf('https://www.facebook.com/events/%s/', $event['id']);

			$date = new \DateTime($event['start_time']);

			if($month !== $date->format('d')) {
				$month = $date->format('d');
				$this->templateVariableContainer->add('newMonth', TRUE);
			}

			if($year !== $date->format('Y')) {
				$year = $date->format('Y');
				$this->templateVariableContainer->add('newYear', TRUE);
			}

			if($coverType === 'source') {
				$event['cover']['url'] = $event['cover']['source'];
			} else {
				$event['cover']['url'] = sprintf('https://graph.facebook.com/%s/picture?type=%s', $event['id'], $coverType);
			}

			$this->templateVariableContainer->add('event', $event);
			$output .= $this->renderChildren();
			$this->templateVariableContainer->remove('event');
			$this->templateVariableContainer->remove('newMonth');
			$this->templateVariableContainer->remove('newYear');
		}

		return $output;
	}
}
