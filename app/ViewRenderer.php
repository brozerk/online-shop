<?php

namespace App;

class ViewRenderer
{
    public function render(string $view, array $params,bool $isLayout): string
    {
        extract($params);

        if ($isLayout) {
            ob_start();

            require_once $view;

            $content = ob_get_clean();

            $layout = file_get_contents('../Views/layout.phtml');
//var_dump($params['cartGoodsQuantity']);die;
            if (isset($params['cartGoodsQuantity'])) {
                return str_replace(['{cartGoodsQuantity}', '{content}'], [$params['cartGoodsQuantity'], $content], $layout);
            }
            return str_replace('{content}', $content, $layout);
        }
        return $view;
    }
}