<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Main
{
    use SoftDeletes;

    //region Module Fields
    /**
     * Set all the fields, to view in the listing or export into csv file.
     *
     * @var array
     */
    public static $module_fields = [
        'id'         => [
            'download'        => [
                'downloadable' => false,
                'map_field'    => '',
                'title'        => 'ID',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 'm',
        ],
        'sap_code'   => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Material Code',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 'm',
        ],
        'title'      => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Material Title',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 'm',
        ],
        'type'       => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Type',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 'm',
        ],
        'sort'       => [
            'download'        => [
                'downloadable' => false,
                'map_field'    => '',
                'title'        => 'Sort',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 'm',
        ],
        'active'     => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Status',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 'm',
        ],
        'created_at' => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Created At',
            ],
            'multiple'        => ['created_at_from', 'created_at_to'],
            'search_via_like' => false,
            'alias'           => 'm',
        ],
    ];
    //endregion

    //region Relations

    //endregion

    public static function getMaterials($options = array(), $arrayFormat = false)
    {
        $fields = array_keys(self::$module_fields);

        $options = array_map(function ($e) {
            return is_scalar($e) ? trim($e) : $e;
        }, $options);

        $options['start'] = isset($options['start']) ? $options['start'] : 0;
        $options['length'] = isset($options['length']) ? $options['length'] : 25;
        $options['orderBy'] = isset($options['orderBy']) ? $options['orderBy'] : 'm.id';
        $options['orderByDirection'] = isset($options['orderByDirection']) ? $options['orderByDirection'] : 'DESC';
        $options['filterBy'] = isset($options['filterBy']) ? $options['filterBy'] : false;

        // Setting orderBy field
        if ($fields[$options['order'][0]['column']]) {
            $options['orderBy'] = $fields[$options['order'][0]['column']];
            if (is_array($fields[$options['order'][0]['column']])) {
                $options['orderBy'] = key($options['orderBy']);
            }
        }

        $query = DB::table('materials AS m')
            //->join('roles AS r', 'r.id', '=', 'u.role_id')
            ->whereNull('m.deleted_at')
            ->select(
                [
                    'm.id',
                    'm.sap_code',
                    'm.title',
                    'm.type',
                    'm.description',
                    'm.sort',
                    'm.active',
                    'm.created_at',
                ]
            );

        /**
         * Filters
         */
        if (self::$module_fields) {
            foreach (self::$module_fields as $key => $field) {

                // apply range filters
                if (!empty($field['multiple'])) {
                    if (array_key_exists($field['multiple'][0], $options) && $options[$field['multiple'][0]] != '') {
                        $query->where($field['alias'] . '.' . $key, '>=', date('Y-m-d 00:00:00', strtotime($options[$field['multiple'][0]])));
                    }

                    if (array_key_exists($field['multiple'][1], $options) && $options[$field['multiple'][1]] != '') {
                        $query->where($field['alias'] . '.' . $key, '<=', date('Y-m-d 23:59:00', strtotime($options[$field['multiple'][1]])));
                    }
                }

                // apply where or like filters
                if ($options[$key] != '') {
                    // apply like filter
                    if ($field['search_via_like']) {
                        if (array_key_exists($key, $options) && $options[$key] != '') {
                            $query->where($field['alias'] . '.' . $key, 'LIKE', '%' . $options[$key] . '%');
                        }
                    } else {
                        // apply where filter
                        if (array_key_exists($key, $options) && $options[$key] != '') {
                            $query->where($field['alias'] . '.' . $key, $options[$key]);
                        }
                    }
                }

            } // endforeach
        }

        // $page = Page::find(\Auth::page()->id);
        // if (!$page->isSuper()) {
        //     $query->where('u.role_id', '!=', \Config::get('constants.ROLE_ID_SUPER'));
        // }

        if (!$query) {
            return false;
        }

        $count = $query->count();

        if (!$count) {
            return ['total' => $count];
        }

        $query->orderBy($options['orderBy'], $options['orderByDirection']);

        if ($options['length'] && $options['length'] > 0) {
            $list = $query->skip($options['start'])->take($options['length'])->get();
        } else {
            $list = $query->get();
        }

        \Log::info(['vehicle_type manage query: ' => $query->toSql(), 'options' => $options, 'count' => $count, 'list' => $list]);

        return ['total' => $count, 'dataset' => $list];
    }

    public static function getMaterialsForDropDown()
    {
        return self::where('active', 1)
            ->whereNull('deleted_at')
            ->orderBy('title', 'ASC')
            ->pluck('sap_code', 'id');
    }
}
