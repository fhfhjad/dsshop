<?php

defined('THINK_PATH') or exit();
class PublicAction extends Action{
    public function verify(){
		ob_clean();
        import('ORG.Util.Image');
        Image::buildImageVerify();
    }
}

?>