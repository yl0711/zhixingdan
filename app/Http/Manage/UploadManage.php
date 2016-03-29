<?php
/**
 * [图片处理]
 * @Author: damon
 * @Date:   2015-09-15 17:28:45
 * @Last Modified by:   damon
 * @Last Modified time: 2015-09-18 17:35:34
 * @Email:damon.ning@aliyun.com
 */

namespace App\Http\Manage;
use App\Http\Model\liuchengdan\AttachmentModel;
use Intervention\Image\Facades\Image;

class UploadManage{
	
	/**
	 * [upload_file description]
	 * @param  Request $request [description]
	 * @param  string  $input   [表单名称]
	 * @param  string  $imgDir  [上传路径]
	 * @return [arr]           [全路径，url地址，数据库存入路径]
	 */
	public function upload_file($request, $input='uploadFile', $movePath='')
	{	

		if(!$request->hasFile($input) ) return false;
        $file = $request->file($input);
        if( !$file->isValid()) return false;
        $time = config('global.REQUEST_TIME');
        $imgDir = config('global.IMG_DIR');

        $fileName =  $time . mt_rand(1000,9999) . '.source.' .$file->getClientOriginalExtension();
        //$sourceFileName = $file->getClientOriginalName();
        //$mimeType = $file->getMimeType();
        $movePath = $movePath == '' ? '/source/' . date('y-m',$time) : $movePath;
       	//数据库存的地址
        $dbPath = $movePath . '/' . $fileName;
        //保存路径
        $movePath = $imgDir . $movePath;
        $file->move($movePath, $fileName);
        $imageUrl = config('global.DOMAIN.IMAGE') . $dbPath;

		$attachmentModel = new AttachmentModel();
		$attach_id = $attachmentModel->add([
			'filename' => $file->getClientOriginalName(),
			'path' => $dbPath,
		]);

        return array('imageUrl'=>'http://' . $imageUrl,'dbPath'=>$dbPath, 'attach_id'=>$attach_id);

	}



	public function createThumb($sourceFile, $thumbSize, $savePath='')
	{
		$imgDir = config('global.IMG_DIR');
		$fileName = strrchr($sourceFile , '/');
		$savePath = $savePath == '' ? dirname($sourceFile) : rtrim(str_replace('\\', '/', $savePath), '/');
		$sourceFile = $imgDir . '/' .  $sourceFile;
		if (!file_exists($sourceFile)) return false;
		$imgHandle = Image::make($sourceFile);
		if (!is_array($thumbSize) || !count($thumbSize)) return false;

		foreach ($thumbSize as $k => $v) {
			$saveFile = $savePath . str_replace('source', strtolower($k), $fileName);
			$imgHandle->resize($v[0], $v[1])->save($imgDir . $saveFile);
		}

		return $saveFile;
	}


	public function crop($sourceFile, $width, $height, $x, $y, $savePath='')
	{
		$imgDir = config('global.IMG_DIR');
		$fileName = strrchr($sourceFile , '/');
		$savePath = $savePath == '' ? dirname($sourceFile) : rtrim(str_replace('\\', '/', $savePath), '/');
		$sourceFile = $imgDir . '/' .  $sourceFile;
		if (!file_exists($sourceFile)) return false;

		$imgHandle = Image::make($sourceFile);
		$saveFile = $savePath . str_replace('source', 'thumb', $fileName);
		$imgHandle->crop($width, $height, $x, $y)->save($imgDir . $saveFile);
		return $saveFile;
	}
}


