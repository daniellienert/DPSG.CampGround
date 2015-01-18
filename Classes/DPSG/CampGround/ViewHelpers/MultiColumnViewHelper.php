<?php
namespace DPSG\CampGround\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;

class MultiColumnViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * @var string
	 */
	protected $gridSpan;


	/**
	 * @var integer
	 */
	protected $itemsPerColumn;


	/**
	 * @var integer
	 */
	protected $gridColumns;


	/**
	 * @param array $each
	 * @param string $as
	 * @param integer $columns
	 * @param integer $itemsPerColumn
	 * @param integer $columnWidth
	 * @param string $rowTag
	 * @param string $columnTag
	 * @param string $direction
	 *
	 * @throws \TYPO3\Fluid\Exception
	 * @return string
	 */
	public function render(array $each, $as, $columns = 0, $itemsPerColumn = 0, $columnWidth = 200, $rowTag = 'div', $columnTag = 'div', $direction = 'vertical') {
		$itemCount = count($each);

		if($columns !== 0 && $itemsPerColumn !==0 || $columns == 0 && $itemsPerColumn == 0) {
			throw new \TYPO3\Fluid\Exception('You have to define either $columns or $itemsPerColumn', 1421582949);
		}

		if($columns > 0) {
			$this->calculateBootstrapGrids($columns);
			$this->itemsPerColumn = ceil($itemCount / $this->gridColumns);
		} else {
			$this->itemsPerColumn = $itemsPerColumn;
			$columns = ceil($itemCount / $itemsPerColumn);
			$this->calculateBootstrapGrids($columns);
		}

		$content = '';
		$itemIterator = 0;
		$newRow = TRUE;
		$newColumn = TRUE;
		$firstRow = TRUE;


		foreach($each as $item) {
			if (!$firstRow) {
				if ($direction === 'vertical') {
					$newRow = FALSE;
					$newColumn = $itemIterator % $this->itemsPerColumn === 0 ? TRUE : FALSE;
				} else {
					$newColumn = TRUE;
					$newRow = $itemIterator % $this->gridColumns === 0 ? TRUE : FALSE;
				}
			}

			if($newColumn && !$firstRow) $content .= sprintf('</%s>', $columnTag);
			if($newRow && !$firstRow) $content .= sprintf('</%s>', $rowTag);

			$this->templateVariableContainer->add('newRow', $newRow);
			$this->templateVariableContainer->add($as, $item);
			$this->templateVariableContainer->add('newColumn', $newColumn);
			$this->templateVariableContainer->add('itemCount', $itemCount);
			$this->templateVariableContainer->add('itemsPerColumn', $this->gridColumns);
			$this->templateVariableContainer->add('gridSpan', $this->gridSpan);
			$this->templateVariableContainer->add('containerWidth', $this->gridColumns * $columnWidth);

			$content .= $this->renderChildren();

			$this->templateVariableContainer->remove($as);
			$this->templateVariableContainer->remove('itemCount');
			$this->templateVariableContainer->remove('gridSpan');
			$this->templateVariableContainer->remove('containerWidth');
			$this->templateVariableContainer->remove('itemsPerColumn');
			$this->templateVariableContainer->remove('newRow');
			$this->templateVariableContainer->remove('newColumn');

			$itemIterator++;
			$firstRow = FALSE;
		}

		$content .= sprintf('</%s>', $columnTag);
		$content .= sprintf('</%s>', $rowTag);

		return $content;
	}


	protected function calculateBootstrapGrids($columns) {

		switch($columns) {
			case 1:
				$this->gridSpan = 12;
				$this->gridColumns = 1;
				break;
			case 2:
				$this->gridSpan = 6;
				$this->gridColumns = 2;
				break;
			case 3:
				$this->gridSpan = 4;
				$this->gridColumns = 3;
				break;
			case 4:
				$this->gridSpan = 3;
				$this->gridColumns = 4;
				break;
			case 5:
			case 6:
				$this->gridSpan = 2;
				$this->gridColumns = 6;
				break;
			default:
				$this->gridSpan = 1;
				$this->gridColumns = 12;
		}
	}
}
