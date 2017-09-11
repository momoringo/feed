<?php

class AutoLoader
{
    /*
        オートローダーが探しにいくディレクトリ
    */
    protected $dirs;

    /*
        __autoload()の実装として、下記のautoLoad()を登録する
    */
    public function register()
    {

        spl_autoload_register(array($this, 'autoLoad'));
    }

    /*
        探索ディレクトリを登録するメソッド
    */
    public function registerDir($dir)
    {
        $this->dirs[] = $dir;
    }

    /*
        autoloadはインスタンス生成時に呼ばれるがそのとき対象となるクラス名を引数として引き受ける
    */
    public function autoLoad($className)
    {
var_dump($className);
        foreach ($this->dirs as $dir) {
            $file = $dir . '/' . $className . '.php';

            
            if (is_readable($file)) {
                require $file;

                return;
            }
        }
    }
}