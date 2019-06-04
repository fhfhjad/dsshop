<?php

defined('THINK_PATH') or exit();
class PublicAction extends Action{
	public function verify($width = '80', $height = 30,$size="18"){
		ob_clean();
        import('ORG.Util.Image');
        Image::buildDedeVerify(4,1, 'png',$width, $height, 'verify',$size);
    }
}
?>
