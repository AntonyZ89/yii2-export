<?php


namespace antonyz89\export\components;

use kartik\grid\GridView as GridViewBase;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\web\Request;
use Yii;

class GridView extends GridViewBase
{
    public $dataColumnClass = DataColumn::class;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->initModule();
        if (isset($this->_module->bsVersion)) {
            $this->bsVersion = $this->_module->bsVersion;
        }
        $this->initBsVersion();
        Html::addCssClass($this->options, 'is-bs' . ($this->isBs4() ? '4' : '3'));
        $this->initPjaxContainerId();
        if (!isset($this->itemLabelSingle)) {
            $this->itemLabelSingle = Yii::t('kvgrid', 'item');
        }
        if (!isset($this->itemLabelPlural)) {
            $this->itemLabelPlural = Yii::t('kvgrid', 'items');
        }
        if (!isset($this->itemLabelFew)) {
            $this->itemLabelFew = Yii::t('kvgrid', 'items-few');
        }
        if (!isset($this->itemLabelMany)) {
            $this->itemLabelMany = Yii::t('kvgrid', 'items-many');
        }
        if (!isset($this->itemLabelAccusative)) {
            $this->itemLabelAccusative = Yii::t('kvgrid', 'items-acc');
        }
        $isBs4 = $this->isBs4();
        if ($isBs4) {
            Html::addCssClass($this->options, 'kv-grid-bs4');
            $this->setPagerOptionClass('linkContainerOptions', 'page-item');
            $this->setPagerOptionClass('linkOptions', 'page-link');
            $this->setPagerOptionClass('disabledListItemSubTagOptions', 'page-link');
        }
        if (!$this->toggleData) {
            parent::init();
            return;
        }
        $this->_toggleDataKey = '_tog' . hash('crc32', $this->options['id']);
        /**
         * @var Request $request
         */
        $request = $this->_module->get('request', false);
        if ($request === null || !($request instanceof Request)) {
            $request = Yii::$app->request;
        }

        if ($request->isConsoleRequest) {
            /** @var \yii\console\Request $request */
            $this->_isShowAll = ($request->getParams()[$this->_toggleDataKey] ?? $this->defaultPagination) === 'all';
        } else {
            $this->_isShowAll = $request->getQueryParam($this->_toggleDataKey, $this->defaultPagination) === 'all';
        }

        if ($this->_isShowAll) {
            /** @noinspection PhpUndefinedFieldInspection */
            $this->dataProvider->pagination = false;
        }
        $this->_toggleButtonId = $this->options['id'] . '-togdata-' . ($this->_isShowAll ? 'all' : 'page');
        \yii\grid\GridView::init();
    }

}
