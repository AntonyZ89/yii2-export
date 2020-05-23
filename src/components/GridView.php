<?php


namespace antonyz89\export\components;

use kartik\grid\GridView as GridViewBase;

class GridView extends GridViewBase
{
    public $dataColumnClass = DataColumn::class;
}
