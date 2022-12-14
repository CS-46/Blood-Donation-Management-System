<?php
namespace Core;
class View
{
    public string $title='';
    public function renderView($view,$params=[])
    {

        $viewContent=$this->renderOnlyView($view,$params);
        $layoutContent=$this->layoutContent($params);
//        var_dump($layoutContent);
//        foreach ($params as $key=>$value)
//        {
//            if(is_array($value))
//            {
//                $d='';
//                foreach ($value as $k=>$v)
//                {
//                    if(is_array($v))
//                    {
//                        $d.=implode(' ',$v);
//                    }else{
//                        $d.=$v;
//                    }
//                }
//                $viewContent=str_replace('{{'.$key.'}}',$d,$viewContent);
//
//            }else{
//                $viewContent=str_replace('{{'.$key.'}}',$value,$viewContent);
//            }
//        }
        return str_replace('{{content}}',$viewContent,$layoutContent);
    }



    private function renderContent($viewContent): array|bool|string
    {
        $layoutContent=$this->layoutContent();
        return str_replace('{{content}}',$viewContent,$layoutContent);
    }



    protected function layoutContent($params): bool|string
    {
        $layout=Application::$app->layout;
        if(Application::$app->controller)
        {
            $layout=Application::$app->controller->layout;
        }
        foreach ($params as $key=>$value){
            $$key=$value;
        }
        ob_start();
        include_once Application::$ROOT_DIR ."/app/view/layout/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view,$params): bool|string
    {
        foreach ($params as $key=>$value){
            $$key=$value;
        }
        include_once Application::$ROOT_DIR ."/app/view/pages/$view.php";
        return ob_get_clean();
    }

//    protected function RenderCSS($css): bool|string
//    {
//        ob_start();
//        include_once Application::$ROOT_DIR ."/public/styles/".$css.".css";
//        return ob_get_clean();
//    }
//
//    protected function RenderJS($js): bool|string
//    {
//        ob_start();
//        include_once Application::$ROOT_DIR ."/public/scripts/".$js.".js";
//        return ob_get_clean();
//    }

}