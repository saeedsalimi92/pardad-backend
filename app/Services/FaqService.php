<?php

namespace App\Services;

class FaqService
{
    public function index($model, $data)
    {
        return $model->faqs()
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data["status"]);
            })
            ->paginate();
    }

    public function store($model, $data)
    {
        return $model->faqs()->create($data);
    }

    public function update($model, $id, $data)
    {
        $faq = $model->faqs()->find($id);
        if(!$faq) {
            return null;
        }

        return $faq->update($data);
    }

    public function findById($model, $faqId)
    {
        return $model->faqs()->find($faqId);
    }

    public function destroy($model, $id)
    {
        $faq = $model->faqs()->find($id);
        if(!$faq) {
            return null;
        }

        return $faq->delete();
    }
}
