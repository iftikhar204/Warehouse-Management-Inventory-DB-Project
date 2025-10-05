<?php

// app/Models/Setting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    // Optional: Cast values based on their 'type' if you need more complex handling
    // protected $casts = [
    //     'value' => 'string', // Default, but you can override based on 'type'
    // ];

    /**
     * Get a setting value by its key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        if ($setting) {
            // Simple casting for common types. Expand as needed.
            if ($setting->type === 'boolean') {
                return (bool) $setting->value;
            }
            if ($setting->type === 'integer') {
                return (int) $setting->value;
            }
            if ($setting->type === 'float') {
                return (float) $setting->value;
            }
            if ($setting->type === 'json') {
                return json_decode($setting->value, true);
            }
            return $setting->value;
        }
        return $default;
    }

    /**
     * Set a setting value by its key.
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string|null $description
     * @return static
     */
    public static function set($key, $value, $type = 'string', $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => is_array($value) ? 'json' : $type, // Auto-detect json
                'description' => $description,
            ]
        );
    }
}
