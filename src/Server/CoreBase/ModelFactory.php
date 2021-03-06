<?php
namespace Server\CoreBase;
/**
 * Model工厂模式
 * Created by PhpStorm.
 * User: tmtbe
 * Date: 16-7-15
 * Time: 下午12:03
 */
class ModelFactory
{
    private $pool = [];
    /**
     * @var ModelFactory
     */
    private static $instance;
    /**
     * 获取单例
     * @return ModelFactory
     */
    public static function getInstance(){
        if(self::$instance==null){
            new ModelFactory();
        }
        return self::$instance;
    }
    /**
     * ModelFactory constructor.
     */
    public function __construct()
    {
        self::$instance = $this;
    }
    /**
     * 获取一个model
     * @param $model string
     */
    public function getModel($model){
        if(!key_exists($model, $this->pool)){
            $this->pool[$model] = [];
        }
        if(count($this->pool[$model])>0){
            $model_instance = array_pop($this->pool[$model]);
            $model_instance->reUse();
            return $model_instance;
        }
        $class_name = "\\Server\\Models\\$model";
        $model_instance = new $class_name;
        $model_instance->core_name = $model;
        return $model_instance;
    }

    /**
     * 归还一个model
     * @param $model Model
     */
    public function revertModel($model){
        if(!$model->is_destroy) {
            $model->destroy();
        }
        $this->pool[$model->core_name][] = $model;
    }
}