<?php
function grayscale_value($r, $g, $b) {
	return round(($r * 0.30) + ($g * 0.59) + ($b * 0.11));
}

function grayscale_pixel($OriginalPixel) {
	$gray = grayscale_value($OriginalPixel['red'], $OriginalPixel['green'], $OriginalPixel['blue']);
	return array('red'=>$gray, 'green'=>$gray, 'blue'=>$gray);
}
function get_pixel_color(&$img, $x, $y) {
	return @ImageColorsForIndex($img, @ImageColorAt($img, $x, $y));
}
function apply_mask(&$gdimg_mask, &$gdimg_image) { //{{{
	$gdimg_mask_resized = imagecreatetruecolor(ImageSX($gdimg_image), ImageSY($gdimg_image));

	ImageCopyResampled($gdimg_mask_resized, $gdimg_mask, 0, 0, 0, 0, ImageSX($gdimg_image), ImageSY($gdimg_image), ImageSX($gdimg_mask), ImageSY($gdimg_mask));
	$gdimg_mask_blendtemp = imagecreatetruecolor(ImageSX($gdimg_image), ImageSY($gdimg_image));
	$color_background = ImageColorAllocate($gdimg_mask_blendtemp, 0, 0, 0);
	ImageFilledRectangle($gdimg_mask_blendtemp, 0, 0, ImageSX($gdimg_mask_blendtemp), ImageSY($gdimg_mask_blendtemp), $color_background);
	ImageAlphaBlending($gdimg_mask_blendtemp, false);
	ImageSaveAlpha($gdimg_mask_blendtemp, true);
	for ($x = 0; $x < ImageSX($gdimg_image); $x++) {
		for ($y = 0; $y < ImageSY($gdimg_image); $y++) {
			//$RealPixel = get_pixel_color($gdimg_mask_blendtemp, $x, $y);
			$RealPixel = get_pixel_color($gdimg_image, $x, $y);
			$MaskPixel = grayscale_pixel(get_pixel_color($gdimg_mask_resized, $x, $y));
			$MaskAlpha = 127 - (floor($MaskPixel['red'] / 2) * (1 - ($RealPixel['alpha'] / 127)));
			$newcolor = ImageColorAllocateAlpha($gdimg_mask_blendtemp, $RealPixel['red'], $RealPixel['green'], $RealPixel['blue'], $MaskAlpha);
			ImageSetPixel($gdimg_mask_blendtemp, $x, $y, $newcolor);
		}
	}
	ImageAlphaBlending($gdimg_image, false);
	ImageSaveAlpha($gdimg_image, true);
	ImageCopy($gdimg_image, $gdimg_mask_blendtemp, 0, 0, 0, 0, ImageSX($gdimg_mask_blendtemp), ImageSY($gdimg_mask_blendtemp));
	ImageDestroy($gdimg_mask_blendtemp);

	ImageDestroy($gdimg_mask_resized);

	return true;
} //}}}

function image_round_corner(&$gdimg, $radius_x, $radius_y) { //{{{
	// generate mask at twice desired resolution and downsample afterwards for easy antialiasing
	// mask is generated as a white double-size elipse on a triple-size black background and copy-paste-resampled
	// onto a correct-size mask image as 4 corners due to errors when the entire mask is resampled at once (gray edges)
	$gdimg_cornermask_triple = imagecreatetruecolor($radius_x * 6, $radius_y * 6);
	$gdimg_cornermask = imagecreatetruecolor(ImageSX($gdimg), ImageSY($gdimg));

	$color_transparent = ImageColorAllocate($gdimg_cornermask_triple, 255, 255, 255);
	ImageFilledEllipse($gdimg_cornermask_triple, $radius_x * 3, $radius_y * 3, $radius_x * 4, $radius_y * 4, $color_transparent);

	ImageFilledRectangle($gdimg_cornermask, 0, 0, ImageSX($gdimg), ImageSY($gdimg), $color_transparent);

	ImageCopyResampled($gdimg_cornermask, $gdimg_cornermask_triple,                           0,                           0,     $radius_x,     $radius_y, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);
	ImageCopyResampled($gdimg_cornermask, $gdimg_cornermask_triple,                           0, ImageSY($gdimg) - $radius_y,     $radius_x, $radius_y * 3, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);
	ImageCopyResampled($gdimg_cornermask, $gdimg_cornermask_triple, ImageSX($gdimg) - $radius_x, ImageSY($gdimg) - $radius_y, $radius_x * 3, $radius_y * 3, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);
	ImageCopyResampled($gdimg_cornermask, $gdimg_cornermask_triple, ImageSX($gdimg) - $radius_x,                           0, $radius_x * 3,     $radius_y, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);

	apply_mask($gdimg_cornermask, $gdimg);
	ImageDestroy($gdimg_cornermask);
	ImageDestroy($gdimg_cornermask_triple);
	return true;
} //}}}

function image_thumb($file, $thumb_file, $max_width, $max_height, $crop) { //{{{
	/**
	 * create thumb from $file and save to $thumb_file, keep proportional
	 * @return true if successful else false
	 */
	try {
		$thumb=new Imagick($file);

		if ($max_width == 0 || $max_height == 0) {
			$thumb->thumbnailImage($max_width, $max_height);
		}
		else {
			if ($crop) {
				# max 400x400, origin 1600x800 => 400x400 (resize and then crop)
				$thumb->cropThumbnailImage($max_width, $max_height);
			}
			else {
				# max 400x400, origin 1600x800 => 400x200
				$thumb->thumbnailImage($max_width, $max_height, TRUE);
			}
		}
		$thumb->writeImage($thumb_file);
	} catch (Exception $e) {
		return FALSE;
	}
	return TRUE;
} //}}}
function image_resize($image, $imgInfo, $width, $height, $crop=FALSE) { # {{{
	$w = imagesx($image);
	$h = imagesy($image);

	if($w < $width and $h < $height) return $image;

	// resize
	if($crop){
		$ratio = max($width/$w, $height/$h);
		$h = $height / $ratio;
		$x = ($w - $width / $ratio) / 2;
		$w = $width / $ratio;
	}
	else{
		list($width, $height) = get_size_proportional($w, $h, $width, $height);
		$x = 0;
	}

	$newImg = imagecreatetruecolor($width, $height);

	/* preserve transparency for png/gif */
	if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
		imagealphablending($newImg, false);
		imagesavealpha($newImg,true);
		$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
		imagefilledrectangle($newImg, 0, 0, $width, $height, $transparent);
	}
	imagecopyresampled($newImg, $image, 0, 0, $x, 0, $width, $height, $w, $h);

	return $newImg;
} # }}}
function image_add_logo($image, $logo_file) { //{{{
	imagealphablending($image, TRUE);
	$logo_img = imagecreatefrompng($logo_file);
	$logow = imagesx($logo_img);
	$logoh = imagesy($logo_img);
	imagecopy($image, $logo_img, 0, imagesy($image) - $logoh, 0, 0, $logow, $logoh);
	return $image;
} //}}}
function get_size_proportional($w0, $h0, $max_width, $max_height) { //{{{
	if ($max_width / $w0 > $max_height / $h0) {
		$width = round($w0 * $max_height/$h0);
		$height = $max_height;
	}else{
		$width = $max_width;
		$height = round($h0 * $max_width/$w0);
	}
	return array($width, $height);
} //}}}
// vim: fdm=marker
