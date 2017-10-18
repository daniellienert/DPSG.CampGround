<?php

Namespace DPSG\CampGround\Transformations;

/*
 *  (c) 2017 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\ContentRepository\Migration\Transformations\AbstractTransformation;
use Neos\Flow\Annotations as Flow;
use Doctrine\Common\Persistence\ObjectManager;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\ResourceManagement\ResourceRepository;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Repository\AssetRepository;
use Neos\Utility\Arrays;
use Neos\Utility\ObjectAccess;
use Neos\Utility\TypeHandling;

class AssetFixingTransformation extends AbstractTransformation
{

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Log\SystemLoggerInterface
     */
    protected $logger;

    /**
     * Doctrine's Entity Manager. Note that "ObjectManager" is the name of the related interface.
     *
     * @Flow\Inject
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject
     * @var AssetRepository
     */
    protected $assetRepository;

    /**
     * @Flow\Inject
     * @var ResourceRepository
     */
    protected $resourceRepository;

    public function execute(NodeData $node)
    {
        $propertyName = 'storyHeaderImage';
        $image = $node->getProperty($propertyName);
        if (is_array($image)) {
            return;
        }

        $nodeRecordQuery = $this->entityManager->getConnection()->prepare('SELECT properties FROM typo3_typo3cr_domain_model_nodedata WHERE persistence_object_identifier=?');
        $nodeRecordQuery->execute([$this->persistenceManager->getIdentifierByObject($node)]);
        $nodeRecord = $nodeRecordQuery->fetch(\PDO::FETCH_ASSOC);
        $nodeProperties = unserialize($nodeRecord['properties']);

        $searchString = '"TYPO3\Flow\Resource\Resource";s:10:"identifier";s:36:"';
        $startPosition = strpos($nodeRecord['properties'], $searchString) + strlen($searchString);
        $resourceObjectIdentifier = substr($nodeRecord['properties'], $startPosition, 36);

        $resource = $this->resourceRepository->findByIdentifier($resourceObjectIdentifier);
        /** @var AssetInterface $asset */
        $assetObject = $this->assetRepository->findOneByResource($resource);

        if ($assetObject === null) {
            $this->logger->log(sprintf('Unable to resolve asset for node %s with resourceObjectIdentifier %s', $this->persistenceManager->getIdentifierByObject($node), $resourceObjectIdentifier));
            return;
        }

        $objectIdentifier = ObjectAccess::getProperty($assetObject, 'Persistence_Object_Identifier', true);

        $node->setProperty($propertyName, [
            '__flow_object_type' => TypeHandling::getTypeForValue($assetObject),
            '__identifier' => $objectIdentifier
        ]);

        $this->logger->log(sprintf('Work on node with persistence identifier %s. Found resourceObjectIdentifier %s, resolved asset %s ', $this->persistenceManager->getIdentifierByObject($node), $resourceObjectIdentifier, $assetObject->getIdentifier()), LOG_INFO);
    }
}
