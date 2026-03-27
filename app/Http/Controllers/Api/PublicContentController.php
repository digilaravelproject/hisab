<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactQuery;
use App\Models\StaticPage;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class PublicContentController extends Controller
{
    use ApiResponseTrait;

    public function privacyPolicy()
    {
        $page = StaticPage::where('slug', 'privacy-policy')->active()->first();

        if (! $page) {
            return $this->errorResponse('Privacy policy not found.', null, 404);
        }

        return $this->successResponse(['page' => $page], 'Privacy policy fetched successfully.');
    }

    public function termsAndConditions()
    {
        $page = StaticPage::where('slug', 'terms-and-conditions')->active()->first();

        if (! $page) {
            return $this->errorResponse('Terms and conditions not found.', null, 404);
        }

        return $this->successResponse(['page' => $page], 'Terms and conditions fetched successfully.');
    }

    public function faqs()
    {
        $page = StaticPage::where('slug', 'faq')->active()->first();

        if (! $page) {
            return $this->errorResponse('FAQs not found.', null, 404);
        }

        // If faq content has structured JSON or HTML, return as is.
        return $this->successResponse(['page' => $page], 'FAQs fetched successfully.');
    }

    public function contactUs(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:2000',
            ]);

            $query = ContactQuery::create($data);

            return $this->successResponse(['query_id' => $query->id], 'Contact enquiry submitted successfully.');
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors()[array_key_first($e->errors())][0], $e->errors(), 422);
        } catch (Throwable $e) {
            return $this->errorResponse('Something went wrong. Please try again.', config('app.debug') ? $e->getMessage() : null, 500);
        }
    }
}
