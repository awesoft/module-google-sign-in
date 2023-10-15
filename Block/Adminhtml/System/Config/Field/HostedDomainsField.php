<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Block\Adminhtml\System\Config\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class HostedDomainsField extends AbstractFieldArray
{
    /**
     * Prepare table to render
     *
     * @return void
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn('domain', ['label' => __('Domain'), 'class' => 'required-entry']);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}
