<?php
Namespace DPSG\CampGround\Transformations;

/*
 *  (c) 2017 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\ContentRepository\Migration\Transformations\AbstractTransformation;
use Neos\Flow\Annotations as Flow;

class DateFixingTransformation extends AbstractTransformation
{

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Log\SystemLoggerInterface
     */
    protected $logger;


    public function execute(NodeData $node)
    {
        $dateProperty = 'storyStartDate';
        $dateToFix = $node->getProperty($dateProperty);

        if(isset($dateToFix['date'])) {
            $dateToFix['dateFormat'] = 'Y-m-d H:i:s.u';
            unset($dateToFix['timezone_type']);
        } else {
            $dateToFix = [];
        }
        $node->setProperty($dateProperty, $dateToFix);
    }
}
