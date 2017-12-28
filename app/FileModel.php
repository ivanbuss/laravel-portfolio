<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\ImageProcessor;


class FileModel extends Model
{

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'files';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'type', 'name', 'user_id'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [

	];

	public function getOriginalFilePath() {
		$processor = new ImageProcessor();
		switch ($this->type) {
			case 'deck_img':
				$directory = $processor->deck_img_path;
				break;
			case 'profile_image':
				$directory = $processor->profile_img_path;
				break;
			case 'gallery_img':
                $directory = $processor->gallery_img_path;
				break;
			case 'review_img':
				$directory = $processor->review_img_path;
				break;
			default:
				$directory = $processor->deck_img_path;
				break;
		}
		return $directory . '/' . $this->name;
	}

}
