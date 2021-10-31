<?php

namespace DPSG\CampGround\ViewHelpers;

use Neos\Flow\Annotations as Flow;

class MultiColumnViewHelper extends \Neos\FluidAdaptor\Core\ViewHelper\AbstractTagBasedViewHelper
{

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
     * @throws \Neos\FluidAdaptor\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        $this->registerUniversalTagAttributes();
        $this->registerArgument('each', 'array', '');
        $this->registerArgument('as', 'string', '');
        $this->registerArgument('columns', 'integer', '');
        $this->registerArgument('itemsPerColumn', 'integer', '');
        $this->registerArgument('columnWidth', 'integer', '',false, 200);
        $this->registerArgument('rowTag', 'string', '');
        $this->registerArgument('columnTag', 'string', '', false, 'div');
        $this->registerArgument('direction', 'string', '', false, 'vertical');
    }

    /**
     * @throws \Neos\FluidAdaptor\Exception
     * @return string
     */
    public function render()
    {
        $itemCount = count($this->arguments['each']);

        if (((int)$this->arguments['columns'] !== 0 && (int)$this->arguments['itemsPerColumn'] !== 0) || ((int)$this->arguments['columns'] === 0 && (int)$this->arguments['itemsPerColumn'] === 0)) {
            throw new \Neos\FluidAdaptor\Exception('You have to define either $columns or $itemsPerColumn', 1421582949);
        }

        if ($this->arguments['columns'] > 0) {
            $this->calculateBootstrapGrids($this->arguments['columns']);
            $this->itemsPerColumn = ceil($itemCount / $this->gridColumns);
        } else {
            $this->itemsPerColumn = $this->arguments['itemsPerColumn'];
            $this->arguments['columns'] = ceil($itemCount / $this->arguments['itemsPerColumn']);
            $this->calculateBootstrapGrids($this->arguments['columns']);
        }

        $content = '';
        $itemIterator = 0;
        $newRow = true;
        $newColumn = true;
        $firstRow = true;


        foreach ($this->arguments['each'] as $item) {
            if (!$firstRow) {
                if ($this->arguments['direction'] === 'vertical') {
                    $newRow = FALSE;
                    $newColumn = $itemIterator % $this->itemsPerColumn === 0 ? TRUE : FALSE;
                } else {
                    $newColumn = TRUE;
                    $newRow = $itemIterator % $this->gridColumns === 0 ? TRUE : FALSE;
                }
            }

            if ($newColumn && !$firstRow) $content .= sprintf('</%s>', $this->arguments['columnTag']);
            if ($newRow && !$firstRow) $content .= sprintf('</%s>', $this->arguments['rowTag']);

            $this->templateVariableContainer->add('newRow', $newRow);
            $this->templateVariableContainer->add($this->arguments['as'], $item);
            $this->templateVariableContainer->add('newColumn', $newColumn);
            $this->templateVariableContainer->add('itemCount', $itemCount);
            $this->templateVariableContainer->add('itemsPerColumn', $this->gridColumns);
            $this->templateVariableContainer->add('gridSpan', $this->gridSpan);
            $this->templateVariableContainer->add('containerWidth', $this->gridColumns * $this->arguments['columnWidth']);
            $content .= $this->renderChildren();

            $this->templateVariableContainer->remove($this->arguments['as']);
            $this->templateVariableContainer->remove('itemCount');
            $this->templateVariableContainer->remove('gridSpan');
            $this->templateVariableContainer->remove('containerWidth');
            $this->templateVariableContainer->remove('itemsPerColumn');
            $this->templateVariableContainer->remove('newRow');
            $this->templateVariableContainer->remove('newColumn');

            $itemIterator++;
            $firstRow = FALSE;
        }

        $content .= sprintf('</%s>', $this->arguments['columnTag']);
        $content .= sprintf('</%s>', $this->arguments['rowTag']);

        return $content;
    }


    protected function calculateBootstrapGrids($columns)
    {

        switch ($columns) {
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
