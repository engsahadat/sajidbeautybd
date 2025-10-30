<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the Settings page
     */
    public function index()
    {
        $groups = $this->definition();

        // Collect all keys we need and fetch existing values in one query
        $allKeys = collect($groups)->flatMap(function ($g) { return collect($g['fields'])->pluck('key'); })->unique()->values();
        $existing = Setting::whereIn('key_name', $allKeys)->get()->keyBy('key_name');

        // Map values back to the groups for easy rendering
        foreach ($groups as &$group) {
            foreach ($group['fields'] as &$field) {
                $key = $field['key'];
                $record = $existing->get($key);
                $field['value'] = $record ? $record->casted_value : null;
            }
        }

        return view('admin.settings.index', [ 'groups' => $groups ]);
    }

    /**
     * Persist settings
     */
    public function update(Request $request)
    {
        $groups = $this->definition();
        $rules = [];

        foreach ($groups as $group) {
            foreach ($group['fields'] as $f) {
                $rules[$f['key']] = $f['rules'] ?? 'nullable';
            }
        }

        $data = $request->validate($rules);

        foreach ($groups as $group) {
            foreach ($group['fields'] as $f) {
                $key = $f['key'];
                $type = $f['type'] ?? 'string';
                $val = $data[$key] ?? null;

                // Normalize boolean checkboxes
                if ($type === 'boolean') {
                    $val = (bool) ($request->has($key) ? $request->boolean($key) : false);
                }

                Setting::set($key, $val, $type, $f['label'] ?? null, $f['public'] ?? false);
            }
        }

        return back()->with('message', 'Settings updated successfully');
    }

    /**
     * Settings schema/definition in one place
     */
    protected function definition(): array
    {
        return [
            [
                'title' => 'General',
                'fields' => [
                    ['key' => 'site_name', 'label' => 'Site Name', 'type' => 'string', 'rules' => 'required|string|max:120', 'public' => true],
                    ['key' => 'site_tagline', 'label' => 'Tagline', 'type' => 'string', 'rules' => 'nullable|string|max:180', 'public' => true],
                    ['key' => 'delivery_days', 'label' => 'Delivery Days', 'type' => 'integer', 'rules' => 'nullable|integer|min:1|max:60', 'public' => true],
                    ['key' => 'about_intro', 'label' => 'About Intro', 'type' => 'string', 'rules' => 'nullable|string|max:1000', 'public' => true],
                ],
            ],
            [
                'title' => 'Contact',
                'fields' => [
                    ['key' => 'contact_email', 'label' => 'Contact Email', 'type' => 'string', 'rules' => 'nullable|email|max:120', 'public' => true],
                    ['key' => 'contact_phone', 'label' => 'Contact Phone', 'type' => 'string', 'rules' => 'nullable|string|max:50', 'public' => true],
                    ['key' => 'contact_address', 'label' => 'Address', 'type' => 'string', 'rules' => 'nullable|string|max:255', 'public' => true],
                    ['key' => 'map_iframe_url', 'label' => 'Map Embed URL', 'type' => 'string', 'rules' => 'nullable|url|max:2000', 'public' => true],
                ],
            ],
                [
                    'title' => 'Business Hours',
                    'fields' => [
                        ['key' => 'hours_sunday', 'label' => 'Sunday', 'type' => 'string', 'rules' => 'nullable|string|max:50', 'public' => true],
                        ['key' => 'hours_monday', 'label' => 'Monday', 'type' => 'string', 'rules' => 'nullable|string|max:50', 'public' => true],
                        ['key' => 'hours_tuesday', 'label' => 'Tuesday', 'type' => 'string', 'rules' => 'nullable|string|max:50', 'public' => true],
                        ['key' => 'hours_wednesday', 'label' => 'Wednesday', 'type' => 'string', 'rules' => 'nullable|string|max:50', 'public' => true],
                        ['key' => 'hours_thursday', 'label' => 'Thursday', 'type' => 'string', 'rules' => 'nullable|string|max:50', 'public' => true],
                        ['key' => 'hours_friday', 'label' => 'Friday', 'type' => 'string', 'rules' => 'nullable|string|max:50', 'public' => true],
                        ['key' => 'hours_saturday', 'label' => 'Saturday', 'type' => 'string', 'rules' => 'nullable|string|max:50', 'public' => true],
                    ],
                ],
            [
                'title' => 'Social',
                'fields' => [
                    ['key' => 'facebook_url', 'label' => 'Facebook URL', 'type' => 'string', 'rules' => 'nullable|url|max:255', 'public' => true],
                    ['key' => 'instagram_url', 'label' => 'Instagram URL', 'type' => 'string', 'rules' => 'nullable|url|max:255', 'public' => true],
                    ['key' => 'youtube_url', 'label' => 'YouTube URL', 'type' => 'string', 'rules' => 'nullable|url|max:255', 'public' => true],
                    ['key' => 'twitter_url', 'label' => 'Twitter/X URL', 'type' => 'string', 'rules' => 'nullable|url|max:255', 'public' => true],
                ],
            ],
        ];
    }
}
