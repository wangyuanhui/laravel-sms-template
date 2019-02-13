<?php

namespace Wangyuanhui\SmsTemplate;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Wangyuanhui\SmsTemplate\SmsTemplate;

/**
 * Class SmsTemplateController
 * @package Wangyuanhui\SmsTemplate
 */
class SmsTemplateController extends Controller
{
    use ValidatesRequests;

    protected $service;

    public function __construct(SmsTemplate $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Http\Response 200|500
     */
    public function index()
    {
        try {
            $result = $this->service->all();
            return response()->json(['message' => 'ok', 'result' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error'], 422);
        }
    }

    /**
     * @param  numeric $id
     * @return \Illuminate\Http\Response 200|404|500
     */
    public function show($id)
    {
        try {
            $result = $this->service->getById((int) $id);
            if (empty($result)) {
                return response()->json(['message' => 'not found'], 404);
            }
            return response()->json(['message' => 'ok', 'result' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error'], 422);
        }
    }

    /**
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response 200|500
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required|string',
                'content' => 'required|string',
                'language' => 'required|string',
                'group' => 'required|string',
            ]);
            $title = $request->input('title');
            $content = $request->input('content');
            $language = $request->input('language');
            $group = $request->input('group');
            $result = $this->service->create($title, $content, $group, $language);
            return response()->json(['message' => 'ok', 'result' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error'], 422);
        }
    }

    /**
     * @param  numeric $id
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response 200|500
     */
    public function update($id, Request $request)
    {
        try {
            $result = $this->service->getById((int) $id);
            if (empty($result)) {
                return response()->json(['message' => 'not found'], 404);
            }
            $this->validate($request, [
                'title' => 'string',
                'content' => 'string',
                'language' => 'string',
                'group' => 'string',
            ]);
            $title = $request->has('title') ? $request->input('title') : null;
            $content = $request->has('content') ? $request->input('content') : null;
            $language = $request->has('language') ? $request->input('language') : null;
            $group = $request->has('group') ? $request->input('group') : null;
            $result = $this->service->update((int) $id, $title, $content, $group, $language);
            return response()->json(['message' => 'ok', 'result' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error'], 422);
        }
    }

    /**
     * @param  numeric $id
     * @return \Illuminate\Http\Response 200|500
     */
    public function destroy($id)
    {
        try {
            $result = $this->service->getById((int) $id);
            if (empty($result)) {
                return response()->json(['message' => 'not found'], 404);
            }
            $result = $this->service->deleteById((int) $id);
            return response()->json(['message' => 'ok', 'result' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error'], 422);
        }
    }

    /**
     * @param  numeric $id
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response 200|500
     */
    public function compose($id, Request $request)
    {
        try {
            $result = $this->service->getById((int) $id);
            if (empty($result)) {
                return response()->json(['message' => 'not found'], 404);
            }
            $this->validate($request, [
                'variables' => 'array',
            ]);
            $variables = $request->has('variables') ? $request->input('variables') : [];
            $result = $this->service->compose((int) $id, $variables);
            return response()->json(['message' => 'ok', 'result' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error'], 422);
        }
    }

    /**
     * Get directive symbol example
     * @return \Illuminate\Http\Response 200|500
     */
    public function directive()
    {
        try {
            $result = $this->service->directive('key');
            return response()->json(['message' => 'ok', 'result' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error'], 422);
        }
    }
}
