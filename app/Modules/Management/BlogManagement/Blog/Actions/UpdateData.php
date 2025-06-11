<?php

namespace App\Modules\Management\BlogManagement\Blog\Actions;

class UpdateData
{
    static $model = \App\Modules\Management\BlogManagement\Blog\Models\Model::class;

    public static function execute($request, $slug)
    {
        try {
            if (!$data = self::$model::query()->where('slug', $slug)->first()) {
                return messageResponse('Data not found...', $data, 404, 'error');
            }
            $requestData = $request->validated();
            // Handle thumbnail image upload
            if ($request->hasFile('thumbnail_image')) {
                $banner_image = $request->file('thumbnail_image');
                $currentDate = now()->format('Y/m');
                $requestData['thumbnail_image'] = uploader($banner_image, 'uploads/blog/thumbnail_image/' . $currentDate);
            }

            // Handle multiple images upload
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $currentDate = now()->format('Y/m');
                    $requestData['images'][$key] = uploader($image, 'uploads/blog/image/' . $currentDate);
                }
            }

            // Handle contributors' images upload
            if (!empty($requestData['contributors'])) {
                foreach ($requestData['contributors'] as $index => $contributor) {
                    if (isset($contributor['image']) && $contributor['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $currentDate = now()->format('Y/m');
                        $uploadedPath = uploader($contributor['image'], 'uploads/blog/contributors/' . $currentDate);
                        $requestData['contributors'][$index]['image'] = $uploadedPath;
                    }
                }
            }

            
            $data->update($requestData);

            // Handle blog categories (BelongsToMany pivot table)
            if (isset($requestData['blog_categories']) && is_array($requestData['blog_categories'])) {
                $data->blog_categories()->sync($requestData['blog_categories']);
            }

            return messageResponse('Item updated successfully', $data, 201);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
