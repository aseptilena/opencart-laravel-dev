<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
	protected $table = 'upload';
	protected $primaryKey = 'upload_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	public function isImage()
	{
		$pathinfo = pathinfo($this->name, PATHINFO_EXTENSION);
		return $pathinfo == 'jpg' || $pathinfo == 'jpeg' || $pathinfo == 'png';
	}

	public function filepath()
	{
		return 'system/upload/'.$this->filename;
	}
	static public function defaultCooperation()
	{
		$upload = new Upload();
		$upload->filename = 'people.jpg';
		return $upload;
	}
}