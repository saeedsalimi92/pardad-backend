<?php

namespace App\Traits;

use App\Models\Faq;

trait HasFaq
{
    public function faqs(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable');
    }

    public function storeFaq($data)
    {
        return $this->faqs()->create($data);
    }

    public function updateFaq($id, $data)
    {
        $faq = $this->faqs()->find($id);
        if(!$faq) {
            return null;
        }

        return $faq->update($data);
    }

    public function deleteFaq($id): int
    {
        return $this->faqs()->where("id", $id)->delete();
    }
}
