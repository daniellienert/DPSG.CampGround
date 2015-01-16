<?php
namespace DPSG\CampGround\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;

class MultiColumnMenuViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {


	/**
	 * @param array $items
	 * @param integer $itemsPerColumn
	 * @param integer $columnWidth
	 * @return mixed
	 */
	public function render(array $items, $itemsPerColumn = 12, $columnWidth = 200) {
		$itemCount = count($items);

		$columns = ceil($itemCount / $itemsPerColumn);
		$gridSpan = ceil(12 / $columns);

		$this->templateVariableContainer->add('itemCount', $itemCount);
		$this->templateVariableContainer->add('itemsPerColumn', $itemsPerColumn);
		$this->templateVariableContainer->add('gridSpan', $gridSpan);
		$this->templateVariableContainer->add('menuWidth', $columns * $columnWidth);

		$content = $this->renderChildren();

		$this->templateVariableContainer->remove('itemCount');
		$this->templateVariableContainer->remove('gridSpan');
		$this->templateVariableContainer->remove('menuWidth');
		$this->templateVariableContainer->remove('itemsPerColumn');

		return $content;
	}
}
