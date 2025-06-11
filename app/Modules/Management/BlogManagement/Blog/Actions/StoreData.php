<?php

namespace App\Modules\Management\BlogManagement\Blog\Actions;

class StoreData
{
    static $model = \App\Modules\Management\BlogManagement\Blog\Models\Model::class;

    public static function execute($request)
    {
        try {
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

            // Create the main model
            $data = self::$model::query()->create($requestData);
            if ($data) {
                // Sync blog categories (BelongsToMany)
                $blogCategories = $request->has('blog_categories') ? json_decode($request->blog_categories, true) : [];
                if (!empty($blogCategories)) {
                    $data->blog_categories()->sync($blogCategories);
                }
                return messageResponse('Item added successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
