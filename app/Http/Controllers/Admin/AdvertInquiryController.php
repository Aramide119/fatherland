<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAdvertInquiryRequest;
use App\Http\Requests\StoreAdvertInquiryRequest;
use App\Http\Requests\UpdateAdvertInquiryRequest;
use App\Models\AdvertInquiry;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertInquiryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('advert_inquiry_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertInquiries = AdvertInquiry::all();

        return view('admin.advertInquiries.index', compact('advertInquiries'));
    }

    public function create()
    {
        abort_if(Gate::denies('advert_inquiry_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.advertInquiries.create');
    }

    public function store(StoreAdvertInquiryRequest $request)
    {
        $advertInquiry = AdvertInquiry::create($request->all());

        return redirect()->route('admin.advert-inquiries.index');
    }

    public function edit(AdvertInquiry $advertInquiry)
    {
        abort_if(Gate::denies('advert_inquiry_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.advertInquiries.edit', compact('advertInquiry'));
    }

    public function update(UpdateAdvertInquiryRequest $request, AdvertInquiry $advertInquiry)
    {
        $advertInquiry->update($request->all());

        return redirect()->route('admin.advert-inquiries.index');
    }

    public function show(AdvertInquiry $advertInquiry)
    {
        abort_if(Gate::denies('advert_inquiry_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.advertInquiries.show', compact('advertInquiry'));
    }

    public function destroy(AdvertInquiry $advertInquiry)
    {
        abort_if(Gate::denies('advert_inquiry_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertInquiry->delete();

        return back();
    }

    public function massDestroy(MassDestroyAdvertInquiryRequest $request)
    {
        $advertInquiries = AdvertInquiry::find(request('ids'));

        foreach ($advertInquiries as $advertInquiry) {
            $advertInquiry->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
